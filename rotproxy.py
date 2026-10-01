#!/usr/bin/env python3
"""
rotproxy.py - Rotating proxy gateway (Python 3.11+, asyncio)

pip install requests uvloop
sudo python3 rotproxy.py        # ports 80/443 need root or CAP_NET_BIND_SERVICE

- Pulls proxies from hproxy.com + proxyscrape every REFRESH_EVERY seconds
- Validates each one with a REAL TLS handshake through the tunnel (rejects MITM/junk)
- 100 "http" accounts  : any upstream type (http/https/socks5)
- 100 "strict" accounts: upstream socks5/https only
- Client always speaks plain HTTP proxy (http://user:pass@IP:443)
- Per account: the same upstream proxy IP is not reused for ROTATE_SECONDS (1h)
- Failover is silent: CONNECT races 3 upstreams at once, first one wins
"""
import asyncio, base64, hmac, json, os, random, re, secrets, ssl, string, struct, time
from dataclasses import dataclass
from urllib.parse import urlsplit
import requests

PUBLIC_HOST = "bendjara.duckdns.org"
DUCKDNS_DOMAIN = "bendjara"                                   # the part before .duckdns.org
DUCKDNS_TOKEN = os.environ.get("DUCKDNS_TOKEN", "ee2ecf47-a75d-46ba-95fb-ef6948751bb8")
LISTEN_PORTS = [80, 443]
ACCOUNTS_FILE = "accounts.json"
ACCOUNTS_TXT = "accounts.txt"

REFRESH_EVERY = 300          # fetch new lists every 5 min
REVALIDATE_EVERY = 600       # re-test existing pool every 10 min
TEST_CONCURRENCY = 400
TEST_TIMEOUT = 5.0
MAX_LATENCY = 2.5            # discard anything slower
CONNECT_TIMEOUT = 4.0        # per upstream attempt
RACE_WIDTH = 3               # parallel upstream attempts
ROUNDS = 4                   # race rounds before giving up
ROTATE_SECONDS = 3600        # never reuse same proxy IP for same account within 1h
MAX_FAILS = 3
POOL_CAP = 4000
IDLE_TIMEOUT = 120
BUF = 65535
TEST_HOST = "www.cloudflare.com"

SSL_CTX = ssl.create_default_context()


@dataclass
class Proxy:
    proto: str
    host: str
    port: int
    latency: float = 99.0
    fails: int = 0

    @property
    def key(self):
        return f"{self.proto}://{self.host}:{self.port}"


POOL: dict[str, Proxy] = {}
USED: dict[str, dict[str, float]] = {}   # account -> {proxy_host: last_used_ts}
ACCOUNTS: dict[str, dict] = {}           # user -> {"pw":..., "group":...}


# ---------------------------------------------------------------- accounts
def _rand(n=6):
    a = string.ascii_letters          # letters only, upper + lower
    return "".join(secrets.choice(a) for _ in range(n))


def _unique_user(data):
    while True:
        u = _rand(6)
        if u not in data:
            return u


def _accounts_ok():
    """True only if accounts.json exists and uses the 6-letter format."""
    try:
        with open(ACCOUNTS_FILE) as f:
            d = json.load(f)
        return bool(d) and all(re.fullmatch(r"[A-Za-z]{6}", u) and len(v["pw"]) == 6
                               for u, v in d.items())
    except Exception:
        return False


def load_accounts():
    if not _accounts_ok():            # missing or old format -> regenerate automatically
        data = {}
        for _ in range(100):
            data[_unique_user(data)] = {"pw": _rand(6), "group": "http"}
        for _ in range(100):
            data[_unique_user(data)] = {"pw": _rand(6), "group": "strict"}
        with open(ACCOUNTS_FILE, "w") as f:
            json.dump(data, f, indent=1)
        with open(ACCOUNTS_TXT, "w") as f:
            for u, v in data.items():
                f.write(f"http://{u}:{v['pw']}@{PUBLIC_HOST}:443   [{v['group']}]\n")
        os.chmod(ACCOUNTS_FILE, 0o600)
        print(f"[+] generated 200 accounts -> {ACCOUNTS_TXT}")
    with open(ACCOUNTS_FILE) as f:
        ACCOUNTS.update(json.load(f))


def _duckdns_update():
    # empty ip= makes DuckDNS use this server's public IP automatically
    r = requests.get("https://www.duckdns.org/update", params={
        "domains": DUCKDNS_DOMAIN, "token": DUCKDNS_TOKEN, "ip": ""}, timeout=15)
    return r.text.strip()


async def duckdns_loop():
    while True:
        try:
            res = await asyncio.to_thread(_duckdns_update)
            print(f"[dns] {PUBLIC_HOST} update -> {res}")   # OK = linked, KO = bad token/domain
        except Exception as e:
            print("[!] duckdns:", e)
        await asyncio.sleep(300)


# ---------------------------------------------------------------- upstream
async def open_tunnel(p: Proxy, host: str, port: int, timeout: float):
    """Return (reader, writer) already tunnelled to host:port through p."""
    w = None
    try:
        r, w = await asyncio.wait_for(asyncio.open_connection(p.host, p.port), timeout)
        if p.proto in ("http", "https"):
            w.write(f"CONNECT {host}:{port} HTTP/1.1\r\nHost: {host}:{port}\r\n\r\n".encode())
            await w.drain()
            head = await asyncio.wait_for(r.readuntil(b"\r\n\r\n"), timeout)
            if b" 200" not in head.split(b"\r\n", 1)[0]:
                raise ConnectionError("CONNECT refused")
        else:  # socks5, no auth
            w.write(b"\x05\x01\x00")
            if await asyncio.wait_for(r.readexactly(2), timeout) != b"\x05\x00":
                raise ConnectionError("socks5 auth")
            hb = host.encode()
            w.write(b"\x05\x01\x00\x03" + bytes([len(hb)]) + hb + struct.pack(">H", port))
            await w.drain()
            h = await asyncio.wait_for(r.readexactly(4), timeout)
            if h[1] != 0:
                raise ConnectionError("socks5 connect failed")
            n = {1: 4, 4: 16}.get(h[3])
            if n is None:
                n = (await r.readexactly(1))[0]
            await r.readexactly(n + 2)
        return r, w
    except BaseException:
        if w:
            w.close()
        raise


async def validate(p: Proxy) -> bool:
    t0 = time.monotonic()
    w = None
    try:
        r, w = await open_tunnel(p, TEST_HOST, 443, TEST_TIMEOUT)
        loop = asyncio.get_running_loop()
        tr = w.transport
        ntr = await asyncio.wait_for(
            loop.start_tls(tr, tr.get_protocol(), SSL_CTX, server_hostname=TEST_HOST), TEST_TIMEOUT)
        ntr.write(f"HEAD / HTTP/1.1\r\nHost: {TEST_HOST}\r\nConnection: close\r\n\r\n".encode())
        data = await asyncio.wait_for(r.read(64), TEST_TIMEOUT)
        ntr.close()
        lat = time.monotonic() - t0
        if data.startswith(b"HTTP/") and lat <= MAX_LATENCY:
            p.latency, p.fails = lat, 0
            return True
    except BaseException as e:
        if isinstance(e, asyncio.CancelledError):
            raise
    finally:
        if w:
            w.close()
    return False


# ---------------------------------------------------------------- sources
def _norm_proto(x):
    x = (x or "http").lower().strip()
    if x.startswith("socks5"):
        return "socks5"
    if x == "https":
        return "https"
    if x == "http":
        return "http"
    return None  # socks4 etc. not supported


def _parse(item, default="http"):
    try:
        if isinstance(item, str):
            item = item.strip()
            proto = default
            if "://" in item:
                proto, item = item.split("://", 1)
            host, port = item.rsplit(":", 1)
        else:
            host = item.get("ip") or item.get("host") or item.get("address")
            port = item.get("port")
            proto = item.get("protocol") or item.get("type") or default
            if isinstance(proto, list):
                proto = proto[0]
        proto = _norm_proto(proto)
        if not (proto and host and port):
            return None
        return Proxy(proto, str(host), int(port))
    except Exception:
        return None


def fetch_sources() -> list[Proxy]:
    out = []
    try:
        r = requests.get("https://hproxy.com/api/proxy-list", params={
            "format": "json", "protocol": "http,socks5",
            "min_uptime_pct": 70, "max_latency_ms": 1000,
            "sort": "uptime", "limit": 200}, timeout=20)
        js = r.json()
        if isinstance(js, dict):  # tolerate {"proxies":[...]} / {"data":[...]}
            js = js.get("proxies") or js.get("data") or js.get("items") or []
        out += [x for x in (_parse(i) for i in js) if x]
    except Exception as e:
        print("[!] hproxy:", e)
    try:
        r = requests.get("https://api.proxyscrape.com/v4/free-proxy-list/get",
                         params={"request": "display_proxies",
                                 "proxy_format": "protocolipport", "format": "text"}, timeout=30)
        out += [x for x in (_parse(l) for l in r.text.splitlines() if l.strip()) if x]
    except Exception as e:
        print("[!] proxyscrape:", e)
    return out


async def refresh_loop():
    sem = asyncio.Semaphore(TEST_CONCURRENCY)

    async def test(p):
        async with sem:
            if await validate(p):
                POOL[p.key] = p

    while True:
        cands = await asyncio.to_thread(fetch_sources)
        new = [p for p in cands if p.key not in POOL]
        random.shuffle(new)
        await asyncio.gather(*(test(p) for p in new))
        if len(POOL) > POOL_CAP:
            for p in sorted(POOL.values(), key=lambda x: x.latency)[POOL_CAP:]:
                POOL.pop(p.key, None)
        print(f"[pool] fetched={len(cands)} tested={len(new)} alive={len(POOL)}")
        await asyncio.sleep(REFRESH_EVERY)


async def revalidate_loop():
    sem = asyncio.Semaphore(TEST_CONCURRENCY)

    async def test(p):
        async with sem:
            if not await validate(p):
                p.fails += 1
                if p.fails >= MAX_FAILS:
                    POOL.pop(p.key, None)

    while True:
        await asyncio.sleep(REVALIDATE_EVERY)
        await asyncio.gather(*(test(p) for p in list(POOL.values())))


# ---------------------------------------------------------------- selection
def pick(user: str, n: int, tried: set) -> list[Proxy]:
    strict = ACCOUNTS[user]["group"] == "strict"
    now = time.time()
    used = USED.setdefault(user, {})
    for h in [h for h, t in used.items() if now - t > ROTATE_SECONDS]:
        del used[h]
    base = [p for p in POOL.values()
            if p.fails < MAX_FAILS and p.key not in tried
            and (not strict or p.proto in ("socks5", "https"))]
    fresh = [p for p in base if p.host not in used]
    pool = fresh or sorted(base, key=lambda p: used.get(p.host, 0))  # fallback: least recently used
    pool.sort(key=lambda p: p.latency)
    top = pool[:max(n * 6, 20)]
    return random.sample(top, min(n, len(top)))


async def race_connect(user, host, port):
    tried = set()
    for _ in range(ROUNDS):
        cands = pick(user, RACE_WIDTH, tried)
        if not cands:
            break
        tried.update(c.key for c in cands)
        tasks = {asyncio.create_task(open_tunnel(c, host, port, CONNECT_TIMEOUT)): c for c in cands}
        pending, winner = set(tasks), None
        while pending and not winner:
            done, pending = await asyncio.wait(pending, return_when=asyncio.FIRST_COMPLETED)
            for t in done:
                c = tasks[t]
                try:
                    r, w = t.result()
                except Exception:
                    c.fails += 1
                    continue
                if winner is None:
                    winner = (r, w, c)
                else:
                    w.close()
        for t in pending:
            t.cancel()
        if winner:
            USED[user][winner[2].host] = time.time()
            return winner
    raise ConnectionError("no upstream")


# ---------------------------------------------------------------- relay
async def pipe(r, w):
    try:
        while True:
            d = await asyncio.wait_for(r.read(BUF), IDLE_TIMEOUT)
            if not d:
                break
            w.write(d)
            await w.drain()
    except Exception:
        pass
    finally:
        try:
            w.close()
        except Exception:
            pass


async def relay(cr, cw, ur, uw):
    await asyncio.gather(pipe(cr, uw), pipe(ur, cw))


def deny(w, code, msg, extra=""):
    w.write(f"HTTP/1.1 {code} {msg}\r\n{extra}Content-Length: 0\r\nConnection: close\r\n\r\n".encode())


async def handle(cr, cw):
    try:
        head = await asyncio.wait_for(cr.readuntil(b"\r\n\r\n"), 10)
        lines = head.split(b"\r\n")
        method, target, ver = lines[0].decode().split(" ", 2)
        hdr = {}
        for l in lines[1:]:
            if b":" in l:
                k, v = l.split(b":", 1)
                hdr[k.decode().strip().lower()] = v.decode().strip()

        # --- auth
        user = None
        try:
            scheme, tok = hdr.get("proxy-authorization", "").split(" ", 1)
            u, pw = base64.b64decode(tok).decode().split(":", 1)
            acc = ACCOUNTS.get(u)
            if scheme.lower() == "basic" and acc and hmac.compare_digest(acc["pw"], pw):
                user = u
        except Exception:
            pass
        if not user:
            deny(cw, 407, "Proxy Authentication Required", 'Proxy-Authenticate: Basic realm="proxy"\r\n')
            await cw.drain()
            return

        for _ in range(60):          # wait up to 30s for first proxies at cold start
            if POOL:
                break
            await asyncio.sleep(0.5)

        # --- HTTPS tunnel
        if method == "CONNECT":
            host, port = target.rsplit(":", 1)
            ur, uw, _p = await race_connect(user, host, int(port))
            cw.write(b"HTTP/1.1 200 Connection Established\r\n\r\n")
            await cw.drain()
            await relay(cr, cw, ur, uw)
            return

        # --- plain HTTP
        u = urlsplit(target)
        host, port = u.hostname, u.port or 80
        path = (u.path or "/") + (f"?{u.query}" if u.query else "")
        body = b""
        cl = int(hdr.get("content-length", 0) or 0)
        if cl > 2_000_000:
            deny(cw, 413, "Payload Too Large")
            return
        if cl:
            body = await cr.readexactly(cl)
        keep = [(k, v) for k, v in (
            (l.split(b":", 1)[0].decode(), l.split(b":", 1)[1].decode().strip())
            for l in lines[1:] if b":" in l)
            if k.lower() not in ("proxy-authorization", "proxy-connection", "connection")]
        hbytes = "".join(f"{k}: {v}\r\n" for k, v in keep) + "Connection: close\r\n\r\n"
        req_abs = f"{method} {target} {ver}\r\n{hbytes}".encode() + body
        req_org = f"{method} {path} {ver}\r\n{hbytes}".encode() + body

        tried = set()
        for _ in range(6):
            cands = pick(user, 1, tried)
            if not cands:
                break
            p = cands[0]
            tried.add(p.key)
            uw = None
            try:
                if p.proto == "socks5":
                    ur, uw = await open_tunnel(p, host, port, CONNECT_TIMEOUT)
                    uw.write(req_org)
                else:
                    ur, uw = await asyncio.wait_for(asyncio.open_connection(p.host, p.port), CONNECT_TIMEOUT)
                    uw.write(req_abs)
                await uw.drain()
                first = await asyncio.wait_for(ur.read(BUF), 8)
                code = first[9:12]
                if first.startswith(b"HTTP/") and not (p.proto != "socks5" and code in (b"407", b"502", b"503", b"504")):
                    USED[user][p.host] = time.time()
                    cw.write(first)
                    await cw.drain()
                    await relay(cr, cw, ur, uw)
                    return
                raise ConnectionError("bad upstream reply")
            except Exception:
                p.fails += 1
                if uw:
                    uw.close()
        deny(cw, 502, "Bad Gateway")
        await cw.drain()
    except Exception:
        pass
    finally:
        try:
            cw.close()
        except Exception:
            pass


# ---------------------------------------------------------------- main
async def main():
    load_accounts()
    asyncio.create_task(duckdns_loop())
    asyncio.create_task(refresh_loop())
    asyncio.create_task(revalidate_loop())
    servers = []
    for port in LISTEN_PORTS:
        servers.append(await asyncio.start_server(handle, "0.0.0.0", port, backlog=4096, limit=BUF))
        print(f"[+] listening on :{port}")
    await asyncio.gather(*(s.serve_forever() for s in servers))


if __name__ == "__main__":
    try:
        import uvloop
        uvloop.install()
    except ImportError:
        pass
    asyncio.run(main())
