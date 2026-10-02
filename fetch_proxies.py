#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
fetch_proxies.py — يجلب ويختبر البروكسيات العامة ويحفظ الأفضل في /tmp/public_proxies.json
الاستخدام:
  python3 fetch_proxies.py              ← جلب + اختبار + حفظ
  python3 fetch_proxies.py --limit 80   ← أفضل 80
  python3 fetch_proxies.py --timeout 4  ← timeout 4 ثانية
  python3 fetch_proxies.py --workers 80 ← 80 thread
  python3 fetch_proxies.py --no-test    ← بدون اختبار (أسرع)
"""

import requests, json, concurrent.futures, time, argparse
from urllib.parse import urlparse

PUBLIC_PROXIES_FILE = '/tmp/public_proxies.json'
CURL_HTTP   = 0
CURL_SOCKS4 = 4
CURL_SOCKS5 = 5

HEADERS = {
    'User-Agent': (
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) '
        'AppleWebKit/537.36 (KHTML, like Gecko) '
        'Chrome/124.0.0.0 Safari/537.36'
    ),
    'Accept': 'text/html,application/json,*/*',
    'Accept-Language': 'en-US,en;q=0.9',
}

# ══════════════════════════════════════════════════════
# المصادر
# ══════════════════════════════════════════════════════

def fetch_proxyscrape() -> list:
    """proxyscrape — نص عادي protocol://ip:port"""
    url = (
        "https://api.proxyscrape.com/v4/free-proxy-list/get"
        "?request=display_proxies&proxy_format=protocolipport&format=text"
    )
    try:
        r = requests.get(url, headers=HEADERS, timeout=20)
        r.raise_for_status()
        lines   = [l.strip() for l in r.text.splitlines() if l.strip()]
        proxies = [p for p in (parse_proxy_string(l) for l in lines) if p]
        print(f"  ✅ proxyscrape  → {len(proxies)}")
        return proxies
    except Exception as e:
        print(f"  ❌ proxyscrape  → {e}")
        return []


def fetch_hproxy() -> list:
    """hproxy.com — JSON مع فلاتر uptime/latency"""
    try:
        r = requests.get(
            "https://hproxy.com/api/proxy-list",
            headers=HEADERS,
            params={
                "format":         "json",
                "protocol":       "http,socks5",
                "min_uptime_pct": 70,
                "max_latency_ms": 1000,
                "sort":           "uptime",
                "limit":          200,
            },
            timeout=20,
        )
        r.raise_for_status()
        data  = r.json()
        items = (data if isinstance(data, list)
                 else data.get('proxies') or data.get('data') or data.get('result') or [])
        proxies = []
        for item in items:
            if isinstance(item, str):
                p = parse_proxy_string(item)
                if p: proxies.append(p)
            elif isinstance(item, dict):
                ip    = (item.get('ip') or item.get('host')
                         or item.get('address') or '')
                port  = str(item.get('port') or '')
                proto = str(item.get('protocol') or item.get('type') or 'http').lower()
                if ip and port.isdigit():
                    proxies.append(build_proxy(ip, port, proto))
        print(f"  ✅ hproxy       → {len(proxies)}")
        return proxies
    except Exception as e:
        print(f"  ❌ hproxy       → {e}")
        return []


def fetch_geonode() -> list:
    """geonode — API مجاني بدون مفتاح"""
    try:
        r = requests.get(
            "https://proxylist.geonode.com/api/proxy-list",
            headers=HEADERS,
            params={
                "limit":          500,
                "page":           1,
                "sort_by":        "speed",
                "sort_type":      "asc",
                "protocols":      "http,socks5",
                "filterUpTime":   70,
            },
            timeout=20,
        )
        r.raise_for_status()
        items   = r.json().get('data', [])
        proxies = []
        for item in items:
            ip    = item.get('ip', '')
            port  = str(item.get('port', ''))
            proto = (item.get('protocols') or ['http'])[0].lower()
            if ip and port.isdigit():
                proxies.append(build_proxy(ip, port, proto))
        print(f"  ✅ geonode      → {len(proxies)}")
        return proxies
    except Exception as e:
        print(f"  ❌ geonode      → {e}")
        return []


def fetch_proxifly() -> list:
    """proxifly — قائمة بسيطة JSON"""
    try:
        r = requests.get(
            "https://cdn.jsdelivr.net/gh/proxifly/free-proxy-list@main"
            "/proxies/protocols/socks5/data.json",
            headers=HEADERS,
            timeout=20,
        )
        r.raise_for_status()
        items   = r.json()
        proxies = []
        for item in items:
            ip    = item.get('ip', '')
            port  = str(item.get('port', ''))
            proto = item.get('protocol', 'socks5').lower()
            if ip and port.isdigit():
                proxies.append(build_proxy(ip, port, proto))
        print(f"  ✅ proxifly     → {len(proxies)}")
        return proxies
    except Exception as e:
        print(f"  ❌ proxifly     → {e}")
        return []


def fetch_proxifly_http() -> list:
    """proxifly HTTP"""
    try:
        r = requests.get(
            "https://cdn.jsdelivr.net/gh/proxifly/free-proxy-list@main"
            "/proxies/protocols/http/data.json",
            headers=HEADERS,
            timeout=20,
        )
        r.raise_for_status()
        items   = r.json()
        proxies = []
        for item in items:
            ip    = item.get('ip', '')
            port  = str(item.get('port', ''))
            if ip and port.isdigit():
                proxies.append(build_proxy(ip, port, 'http'))
        print(f"  ✅ proxifly-http→ {len(proxies)}")
        return proxies
    except Exception as e:
        print(f"  ❌ proxifly-http→ {e}")
        return []


# ══════════════════════════════════════════════════════
# تحليل صيغ البروكسي
# ══════════════════════════════════════════════════════

def parse_proxy_string(s: str) -> dict | None:
    s = s.strip()
    if not s:
        return None
    if '://' in s:
        try:
            p     = urlparse(s)
            proto = p.scheme.lower()
            ip    = p.hostname or ''
            port  = str(p.port) if p.port else ''
        except Exception:
            return None
    elif ' ' in s:
        parts = s.split()
        ip, port = parts[0], parts[1]
        proto = parts[2].lower() if len(parts) > 2 else 'http'
    elif s.count(':') == 1:
        ip, port = s.split(':', 1)
        proto = 'http'
    else:
        return None
    if not ip or not port or not port.isdigit():
        return None
    if not (1 <= int(port) <= 65535):
        return None
    return build_proxy(ip, port, proto)


def build_proxy(ip: str, port: str, proto: str) -> dict:
    proto = proto.lower()
    if 'socks5' in proto:
        ct, scheme = CURL_SOCKS5, 'socks5'
    elif 'socks4' in proto:
        ct, scheme = CURL_SOCKS4, 'socks4'
    else:
        ct, scheme = CURL_HTTP, 'http'
    return {'host': f"{ip}:{port}", 'userpass': '', 'type': ct, '_scheme': scheme}


# ══════════════════════════════════════════════════════
# اختبار
# ══════════════════════════════════════════════════════

TEST_URL = 'http://httpbin.org/ip'

def test_proxy(proxy: dict, timeout: int) -> tuple | None:
    scheme    = proxy.get('_scheme', 'http')
    proxy_url = f"{scheme}://{proxy['host']}"
    prx       = {'http': proxy_url, 'https': proxy_url}
    try:
        t0 = time.time()
        r  = requests.get(TEST_URL, proxies=prx, timeout=timeout,
                          headers={'User-Agent': 'curl/7.88'})
        if r.status_code == 200:
            return (proxy, int((time.time() - t0) * 1000))
    except Exception:
        pass
    return None


# ══════════════════════════════════════════════════════
# Main
# ══════════════════════════════════════════════════════

def main():
    global TEST_URL
    parser = argparse.ArgumentParser()
    parser.add_argument('--limit',    type=int, default=60)
    parser.add_argument('--timeout',  type=int, default=5)
    parser.add_argument('--workers',  type=int, default=60)
    parser.add_argument('--test-url', default=TEST_URL)
    parser.add_argument('--no-test',  action='store_true')
    args = parser.parse_args()
    TEST_URL = args.test_url

    print("=" * 54)
    print("  🔄  fetch_proxies — بروكسيات عامة")
    print("=" * 54)
    print("\n📡 جلب من المصادر:")

    # جلب من جميع المصادر بالتوازي
    with concurrent.futures.ThreadPoolExecutor(max_workers=5) as ex:
        fs = [
            ex.submit(fetch_proxyscrape),
            ex.submit(fetch_hproxy),
            ex.submit(fetch_geonode),
            ex.submit(fetch_proxifly),
            ex.submit(fetch_proxifly_http),
        ]
        all_raw = []
        for f in concurrent.futures.as_completed(fs):
            all_raw.extend(f.result())

    # إزالة التكرار
    seen, unique = set(), []
    for p in all_raw:
        if p['host'] not in seen:
            seen.add(p['host']); unique.append(p)

    print(f"\n📋 إجمالي: {len(all_raw)} → فريد: {len(unique)}")

    if args.no_test or len(unique) == 0:
        to_save = [{'host': p['host'], 'userpass': '', 'type': p['type']}
                   for p in unique[:args.limit]]
        with open(PUBLIC_PROXIES_FILE, 'w') as f:
            json.dump(to_save, f, indent=2)
        print(f"\n✅ حُفظ {len(to_save)} بروكسي (بدون اختبار) → {PUBLIC_PROXIES_FILE}")
        return

    # اختبار متوازٍ
    print(f"\n🧪 اختبار ({args.workers} thread | timeout={args.timeout}s | {TEST_URL})\n")
    working = []
    t_start = time.time()

    with concurrent.futures.ThreadPoolExecutor(max_workers=args.workers) as ex:
        futures = {ex.submit(test_proxy, p, args.timeout): p for p in unique}
        done = 0
        for fut in concurrent.futures.as_completed(futures):
            done += 1
            res = fut.result()
            if res:
                working.append(res)
            if done % 100 == 0 or done == len(unique):
                pct = int(done / len(unique) * 100)
                print(f"  [{pct:3d}%] {done}/{len(unique)} | ✅ {len(working)}", end='\r')
    print()

    # ترتيب + حفظ
    working.sort(key=lambda x: x[1])
    best    = working[:args.limit]
    to_save = [{'host': p['host'], 'userpass': '', 'type': p['type']} for p, _ in best]

    with open(PUBLIC_PROXIES_FILE, 'w') as f:
        json.dump(to_save, f, indent=2)

    elapsed  = int(time.time() - t_start)
    type_map = {CURL_HTTP: 'HTTP  ', CURL_SOCKS4: 'SOCKS4', CURL_SOCKS5: 'SOCKS5'}

    print(f"\n{'=' * 54}")
    print(f"  ✅ النتائج")
    print(f"{'─' * 54}")
    print(f"  فُحص:    {len(unique)}")
    print(f"  يعمل:    {len(working)}  ({int(len(working)/len(unique)*100)}%)")
    print(f"  حُفظ:    {len(to_save)}")
    print(f"  الوقت:   {elapsed}s")
    print(f"  الملف:   {PUBLIC_PROXIES_FILE}")
    print(f"\n{'─' * 54}")
    print(f"  🏆 أسرع 15:")
    print(f"{'─' * 54}")
    for proxy, ms in best[:15]:
        t = type_map.get(proxy['type'], '?   ')
        print(f"  {proxy['host']:26s} {t}  {ms}ms")
    print(f"{'=' * 54}")


if __name__ == '__main__':
    main()
