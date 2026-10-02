#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
patch_bot.py — يُضيف دعم البروكسيات العامة (IP PORT) لـ bot1.php
تشغيل: python3 patch_bot.py
"""

import re, shutil, sys, os

TARGET = os.path.expanduser('~/New-bot/bot1.php')
BACKUP = TARGET + '.bak'

# ══════════════════════════════════════════════════════
# قراءة الملف
# ══════════════════════════════════════════════════════
with open(TARGET, 'r', encoding='utf-8') as f:
    src = f.read()

shutil.copy2(TARGET, BACKUP)
print(f"✅ نسخة احتياطية: {BACKUP}")

changes = 0

# ══════════════════════════════════════════════════════
# 1. ثوابت جديدة بعد OOREDOO_PROXIES_FILE
# ══════════════════════════════════════════════════════
OLD1 = "define('OOREDOO_PROXIES_FILE',       '/tmp/ooredoo_proxies.json');"
NEW1 = OLD1 + """
define('PUBLIC_PROXIES_FILE',        '/tmp/public_proxies.json');
define('DJEZZY_PROXY_MODE_FILE',     '/tmp/djezzy_proxy_mode.json');
define('OOREDOO_PROXY_MODE_FILE',    '/tmp/ooredoo_proxy_mode.json');"""

if OLD1 in src:
    src = src.replace(OLD1, NEW1, 1); changes += 1; print("✅ [1] constants added")
else:
    print("❌ [1] anchor not found — OOREDOO_PROXIES_FILE line"); sys.exit(1)

# ══════════════════════════════════════════════════════
# 2. دوال جديدة بعد parseOoredooProxy
# ══════════════════════════════════════════════════════
OLD2 = """function parseOoredooProxy(string $raw): array
{
    // format: ip:port:user:pass
    $parts = explode(':', $raw, 4);
    return [
        'host'     => ($parts[0] ?? '') . ':' . ($parts[1] ?? ''),
        'userpass' => ($parts[2] ?? '') . ':' . ($parts[3] ?? ''),
    ];
}"""

NEW2 = OLD2 + """

// ════════════════════════════════════════════════════════════════════════════
// PUBLIC PROXY — بروكسيات عامة (IP PORT) منفصلة تماماً عن الخاصة
// ════════════════════════════════════════════════════════════════════════════
function loadPublicProxies(): array
{
    if (!file_exists(PUBLIC_PROXIES_FILE)) return [];
    $d = json_decode(file_get_contents(PUBLIC_PROXIES_FILE), true);
    return is_array($d) ? $d : [];
}
function savePublicProxies(array $list): void
{
    file_put_contents(PUBLIC_PROXIES_FILE, json_encode($list, JSON_PRETTY_PRINT));
}
/**
 * parsePublicProxy — يقرأ:
 *   "212.132.75.49 1080"          → http
 *   "212.132.75.49 1080 socks5"   → socks5
 *   "212.132.75.49 1080 http"     → http
 *   "212.132.75.49:1080:socks5"   → socks5
 * يُعيد ['host','userpass','type'] متوافق مع curlWithAllProxies/ooredooCurlRaw
 */
function parsePublicProxy(string $raw): array
{
    $raw = trim($raw);
    // حاول "IP PORT [type]"
    $parts = preg_split('/\\s+/', $raw, 3);
    if (count($parts) < 2) {
        // حاول "IP:PORT[:type]"
        $parts = explode(':', $raw, 3);
    }
    $ip       = $parts[0] ?? '';
    $port     = $parts[1] ?? '80';
    $typeStr  = strtolower(trim($parts[2] ?? 'http'));
    if (str_contains($typeStr, 'socks5'))     { $ct = CURLPROXY_SOCKS5; }
    elseif (str_contains($typeStr, 'socks4')) { $ct = CURLPROXY_SOCKS4; }
    else                                       { $ct = CURLPROXY_HTTP;   }
    return ['host' => "{$ip}:{$port}", 'userpass' => '', 'type' => $ct];
}

// ════ وضع البروكسي لكل مشغّل ════
function getDjezzyProxyMode(): string
{
    if (!file_exists(DJEZZY_PROXY_MODE_FILE)) return 'private';
    $d = json_decode(file_get_contents(DJEZZY_PROXY_MODE_FILE), true);
    return ($d['mode'] ?? 'private') === 'public' ? 'public' : 'private';
}
function setDjezzyProxyMode(string $mode): void
{
    file_put_contents(DJEZZY_PROXY_MODE_FILE, json_encode(['mode' => $mode]));
}
function getOoredooProxyMode(): string
{
    if (!file_exists(OOREDOO_PROXY_MODE_FILE)) return 'private';
    $d = json_decode(file_get_contents(OOREDOO_PROXY_MODE_FILE), true);
    return ($d['mode'] ?? 'private') === 'public' ? 'public' : 'private';
}
function setOoredooProxyMode(string $mode): void
{
    file_put_contents(OOREDOO_PROXY_MODE_FILE, json_encode(['mode' => $mode]));
}

/**
 * getDjezzyActiveProxies — بروكسيات جيزي حسب الوضع
 * public  → loadPublicProxies() (arrays جاهزة)
 * private → strings من PROXY_LIST_FILE + API
 */
function getDjezzyActiveProxies(): array
{
    if (getDjezzyProxyMode() === 'public') {
        return loadPublicProxies();
    }
    $local    = loadProxies();
    $fromApi  = refreshProxies();
    $combined = array_unique(array_merge($local, $fromApi));
    return array_values($combined);
}

/**
 * getOoredooActiveProxies — بروكسيات أوريدو حسب الوضع
 * public  → loadPublicProxies() (arrays جاهزة)
 * private → getOoredooProxies() (strings خاصة)
 */
function getOoredooActiveProxies(): array
{
    if (getOoredooProxyMode() === 'public') {
        return loadPublicProxies();
    }
    return getOoredooProxies();
}

// ════ Handler: إضافة بروكسيات عامة من تلقرام ════
function handleTgSetPublicProxies(string $chatId): void
{
    setTgState($chatId, ['action' => 'awaiting_public_proxies']);
    tgSendMessage($chatId,
        "📡 <b>إضافة بروكسيات عامة</b>\\n\\n"
        . "كل سطر يحتوي على بروكسي واحد بالصيغة:\\n"
        . "<code>212.132.75.49 1080</code>  ← http افتراضي\\n"
        . "<code>1.2.3.4 1080 socks5</code>\\n"
        . "<code>5.6.7.8 3128 http</code>\\n\\n"
        . "⚠️ البروكسيات العامة مستقلة تماماً عن الخاصة.\\n"
        . "/cancel للإلغاء"
    );
}

function handleTgPublicProxiesInput(string $chatId, string $text): void
{
    if (trim($text) === '/cancel') {
        clearTgState($chatId);
        tgSendMessage($chatId, '❌ تم الإلغاء.');
        sendTgMainMenu($chatId);
        return;
    }
    $lines = array_filter(array_map('trim', explode("\\n", $text)));
    $valid = [];
    foreach ($lines as $line) {
        $parts = preg_split('/\\s+/', $line, 3);
        if (count($parts) < 2) $parts = explode(':', $line, 3);
        $ip   = $parts[0] ?? '';
        $port = (int)($parts[1] ?? 0);
        if (!filter_var($ip, FILTER_VALIDATE_IP) || $port < 1 || $port > 65535) continue;
        $valid[] = parsePublicProxy($line);
    }
    if (empty($valid)) {
        tgSendMessage($chatId, "❌ لم يتم التعرف على أي بروكسي صالح.\\nالصيغة: <code>IP PORT [http|socks5]</code>");
        return;
    }
    savePublicProxies($valid);
    clearTgState($chatId);
    $list = implode("\\n", array_map(fn($p) => "✅ <code>{$p['host']}</code>", $valid));
    tgSendMessage($chatId, "📡 تم حفظ <b>" . count($valid) . "</b> بروكسي عام:\\n{$list}");
    sendTgMainMenu($chatId);
}

function handleTgProxyModes(string $chatId): void
{
    $djMode   = getDjezzyProxyMode()   === 'public' ? '📡 عام' : '🔐 خاص';
    $ooMode   = getOoredooProxyMode()  === 'public' ? '📡 عام' : '🔐 خاص';
    $pubCount = count(loadPublicProxies());
    $text = "⚙️ <b>وضع البروكسيات</b>\\n\\n"
          . "📶 جيزي: <b>{$djMode}</b>\\n"
          . "🌐 أوريدو: <b>{$ooMode}</b>\\n"
          . "📡 عدد العامة المحفوظة: <b>{$pubCount}</b>\\n\\n"
          . "اختر الوضع للتبديل:";
    $keyboard = [
        [
            ['text' => '📶 جيزي → عام',     'callback_data' => 'tg_djezzy_public'],
            ['text' => '📶 جيزي → خاص',     'callback_data' => 'tg_djezzy_private'],
        ],
        [
            ['text' => '🌐 أوريدو → عام',   'callback_data' => 'tg_ooredoo_public'],
            ['text' => '🌐 أوريدو → خاص',   'callback_data' => 'tg_ooredoo_private'],
        ],
        [
            ['text' => '📡 إضافة عامة',     'callback_data' => 'tg_set_public_proxies'],
            ['text' => '📋 عرض العامة',      'callback_data' => 'tg_show_public_proxies'],
        ],
    ];
    tgSendMessage($chatId, $text, $keyboard);
}"""

if OLD2 in src:
    src = src.replace(OLD2, NEW2, 1); changes += 1; print("✅ [2] public proxy functions added")
else:
    print("❌ [2] anchor not found — parseOoredooProxy block"); sys.exit(1)

# ══════════════════════════════════════════════════════
# 3. getAllProxies → يفوّض لـ getDjezzyActiveProxies
# ══════════════════════════════════════════════════════
OLD3 = """function getAllProxies(): array
{
    $local     = loadProxies();
    $fromApi   = refreshProxies();
    $combined  = array_unique(array_merge($local, $fromApi));
    return array_values($combined);
}"""

NEW3 = """function getAllProxies(): array
{
    return getDjezzyActiveProxies();
}"""

if OLD3 in src:
    src = src.replace(OLD3, NEW3, 1); changes += 1; print("✅ [3] getAllProxies() patched")
else:
    print("❌ [3] anchor not found — getAllProxies body"); sys.exit(1)

# ══════════════════════════════════════════════════════
# 4. curlWithAllProxies — الجزء المتوازي (parallel batch)
#    parseProxy → is_array check + dynamic type + optional userpass
# ══════════════════════════════════════════════════════
OLD4 = """    for ($i = 0; $i < $batchSize; $i++) {
        $pp  = parseProxy($proxies[$i]);
        $ch  = curl_init($url);
        $opts = [
            CURLOPT_HTTPHEADER      => $headers,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => 'gzip',
            CURLOPT_TIMEOUT         => $timeout,
            CURLOPT_CONNECTTIMEOUT  => 3,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_PROXY           => $pp['host'],
            CURLOPT_PROXYUSERPWD    => $pp['userpass'],
            CURLOPT_PROXYTYPE       => CURLPROXY_HTTP,
            CURLOPT_FOLLOWLOCATION  => true,
        ];"""

NEW4 = """    for ($i = 0; $i < $batchSize; $i++) {
        $pp  = is_array($proxies[$i]) ? $proxies[$i] : parseProxy($proxies[$i]);
        $ch  = curl_init($url);
        $opts = [
            CURLOPT_HTTPHEADER      => $headers,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => 'gzip',
            CURLOPT_TIMEOUT         => $timeout,
            CURLOPT_CONNECTTIMEOUT  => 3,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_PROXY           => $pp['host'],
            CURLOPT_PROXYTYPE       => $pp['type'] ?? CURLPROXY_HTTP,
            CURLOPT_FOLLOWLOCATION  => true,
        ];
        if (!empty($pp['userpass'])) { $opts[CURLOPT_PROXYUSERPWD] = $pp['userpass']; }"""

if OLD4 in src:
    src = src.replace(OLD4, NEW4, 1); changes += 1; print("✅ [4] curlWithAllProxies parallel batch patched")
else:
    print("❌ [4] anchor not found — curlWithAllProxies parallel for loop"); sys.exit(1)

# ══════════════════════════════════════════════════════
# 5. curlWithAllProxies — الجزء التسلسلي (sequential fallback)
# ══════════════════════════════════════════════════════
OLD5 = """    for ($i = $batchSize; $i < $totalProxies; $i++) {
        $pp = parseProxy($proxies[$i]);
        $ch = curl_init($url);
        $opts = [
            CURLOPT_HTTPHEADER      => $headers,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => 'gzip',
            CURLOPT_TIMEOUT         => $timeout,
            CURLOPT_CONNECTTIMEOUT  => 3,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_PROXY           => $pp['host'],
            CURLOPT_PROXYUSERPWD    => $pp['userpass'],
            CURLOPT_PROXYTYPE       => CURLPROXY_HTTP,
            CURLOPT_FOLLOWLOCATION  => true,
        ];"""

NEW5 = """    for ($i = $batchSize; $i < $totalProxies; $i++) {
        $pp = is_array($proxies[$i]) ? $proxies[$i] : parseProxy($proxies[$i]);
        $ch = curl_init($url);
        $opts = [
            CURLOPT_HTTPHEADER      => $headers,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => 'gzip',
            CURLOPT_TIMEOUT         => $timeout,
            CURLOPT_CONNECTTIMEOUT  => 3,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_PROXY           => $pp['host'],
            CURLOPT_PROXYTYPE       => $pp['type'] ?? CURLPROXY_HTTP,
            CURLOPT_FOLLOWLOCATION  => true,
        ];
        if (!empty($pp['userpass'])) { $opts[CURLOPT_PROXYUSERPWD] = $pp['userpass']; }"""

if OLD5 in src:
    src = src.replace(OLD5, NEW5, 1); changes += 1; print("✅ [5] curlWithAllProxies sequential fallback patched")
else:
    print("❌ [5] anchor not found — curlWithAllProxies sequential for loop"); sys.exit(1)

# ══════════════════════════════════════════════════════
# 6. ooredooCurlRaw — proxy type ديناميكي + optional userpass
# ══════════════════════════════════════════════════════
OLD6 = """    if ($proxy !== null) {
        $opts[CURLOPT_PROXY]        = $proxy['host'];
        $opts[CURLOPT_PROXYUSERPWD] = $proxy['userpass'];
        $opts[CURLOPT_PROXYTYPE]    = CURLPROXY_HTTP;
    }"""

NEW6 = """    if ($proxy !== null) {
        $opts[CURLOPT_PROXY]     = $proxy['host'];
        $opts[CURLOPT_PROXYTYPE] = $proxy['type'] ?? CURLPROXY_HTTP;
        if (!empty($proxy['userpass'])) {
            $opts[CURLOPT_PROXYUSERPWD] = $proxy['userpass'];
        }
    }"""

if OLD6 in src:
    src = src.replace(OLD6, NEW6, 1); changes += 1; print("✅ [6] ooredooCurlRaw proxy type patched")
else:
    print("❌ [6] anchor not found — ooredooCurlRaw proxy block"); sys.exit(1)

# ══════════════════════════════════════════════════════
# 7. ooredooRequest — إصلاح BUG: كانت تستخدم getAllProxies (جيزي!)
#    الآن تستخدم getOoredooActiveProxies + parseOoredooProxy
# ══════════════════════════════════════════════════════
OLD7 = """    // تجربة البروكسيات واحدة تلو الأخرى
    $proxies = getAllProxies();
    if (empty($proxies)) {
        ooredooLog(\"[{$logTag}] No proxies available!\");
        tgNotifyAdmin(\"⚠️ [Ooredoo] لا توجد بروكسيات! [{$logTag}]\");
        return null;
    }
    shuffle($proxies); // توزيع عشوائي لتقليل الضغط
    foreach ($proxies as $p) {
        $pp = parseProxy($p);
        $r  = ooredooCurlRaw($url, $method, $payload, $headers, $logTag, $timeout, $pp);"""

NEW7 = """    // تجربة البروكسيات واحدة تلو الأخرى
    $proxies = getOoredooActiveProxies();   // FIX: كانت getAllProxies (بروكسيات جيزي!) — صُلحت
    if (empty($proxies)) {
        ooredooLog(\"[{$logTag}] No proxies available!\");
        tgNotifyAdmin(\"⚠️ [Ooredoo] لا توجد بروكسيات! [{$logTag}]\");
        return null;
    }
    shuffle($proxies); // توزيع عشوائي لتقليل الضغط
    foreach ($proxies as $p) {
        $pp = is_array($p) ? $p : parseOoredooProxy($p);
        $r  = ooredooCurlRaw($url, $method, $payload, $headers, $logTag, $timeout, $pp);"""

if OLD7 in src:
    src = src.replace(OLD7, NEW7, 1); changes += 1; print("✅ [7] ooredooRequest BUG fixed + patched")
else:
    print("❌ [7] anchor not found — ooredooRequest proxy loop"); sys.exit(1)

# ══════════════════════════════════════════════════════
# 8. state handler — أضف awaiting_public_proxies قبل awaiting_qr
# ══════════════════════════════════════════════════════
OLD8 = """    // حالة انتظار QR Code للهدية
    if (($state['action'] ?? '') === 'awaiting_qr') {"""

NEW8 = """    // حالة انتظار بروكسيات عامة
    if (($state['action'] ?? '') === 'awaiting_public_proxies') {
        handleTgPublicProxiesInput($chatId, $text);
        return;
    }
    // حالة انتظار QR Code للهدية
    if (($state['action'] ?? '') === 'awaiting_qr') {"""

if OLD8 in src:
    src = src.replace(OLD8, NEW8, 1); changes += 1; print("✅ [8] awaiting_public_proxies state handler added")
else:
    print("❌ [8] anchor not found — awaiting_qr state block"); sys.exit(1)

# ══════════════════════════════════════════════════════
# 9. أوامر تلقرام جديدة في switch
# ══════════════════════════════════════════════════════
OLD9 = "        case '/matchgift':\n            handleTgMatchGift($chatId);\n            break;"

NEW9 = OLD9 + """
        case '/setpublicproxies':
            handleTgSetPublicProxies($chatId);
            break;
        case '/proxymodes':
            handleTgProxyModes($chatId);
            break;"""

if OLD9 in src:
    src = src.replace(OLD9, NEW9, 1); changes += 1; print("✅ [9] /setpublicproxies + /proxymodes commands added")
else:
    print("❌ [9] anchor not found — /matchgift case"); sys.exit(1)

# ══════════════════════════════════════════════════════
# 10. sendTgMainMenu — أضف صف البروكسيات العامة/الوضع
# ══════════════════════════════════════════════════════
OLD10 = """        [
            ['text' => ooredooProxyEnabled() ? '🟡 إيقاف بروكسي أوريدو' : '🟡 تفعيل بروكسي أوريدو', 'callback_data' => 'tg_toggle_ooredoo_proxy'],
            ['text' => '✏️ تعديل بروكسيات أوريدو', 'callback_data' => 'tg_edit_ooredoo_proxies'],
        ],
    ];"""

NEW10 = """        [
            ['text' => ooredooProxyEnabled() ? '🟡 إيقاف بروكسي أوريدو' : '🟡 تفعيل بروكسي أوريدو', 'callback_data' => 'tg_toggle_ooredoo_proxy'],
            ['text' => '✏️ تعديل بروكسيات أوريدو', 'callback_data' => 'tg_edit_ooredoo_proxies'],
        ],
        [
            ['text' => '⚙️ وضع البروكسيات (عام/خاص)', 'callback_data' => 'tg_proxy_modes'],
            ['text' => '📡 إضافة بروكسيات عامة',       'callback_data' => 'tg_set_public_proxies'],
        ],
    ];"""

if OLD10 in src:
    src = src.replace(OLD10, NEW10, 1); changes += 1; print("✅ [10] main menu buttons added")
else:
    print("❌ [10] anchor not found — sendTgMainMenu keyboard end"); sys.exit(1)

# ══════════════════════════════════════════════════════
# 11. handleTgCallback — أضف cases جديدة
# ══════════════════════════════════════════════════════
OLD11 = """        case 'tg_edit_ooredoo_proxies':
            $current = implode(\"\\n\", getOoredooProxies());"""

NEW11 = """        case 'tg_proxy_modes':
            handleTgProxyModes($chatId);
            break;
        case 'tg_set_public_proxies':
            handleTgSetPublicProxies($chatId);
            break;
        case 'tg_show_public_proxies':
            $pub = loadPublicProxies();
            $cnt = count($pub);
            if ($cnt === 0) {
                tgSendMessage($chatId, "📡 لا توجد بروكسيات عامة محفوظة.\\n\\n/setpublicproxies لإضافة.");
            } else {
                $preview = implode("\\n", array_map(fn($p) => "<code>{$p['host']}</code>", array_slice($pub, 0, 15)));
                $more    = $cnt > 15 ? "\\n...و" . ($cnt - 15) . " أخرى" : '';
                tgSendMessage($chatId, "📡 <b>البروكسيات العامة ({$cnt})</b>:\\n{$preview}{$more}");
            }
            break;
        case 'tg_djezzy_public':
            setDjezzyProxyMode('public');
            tgSendMessage($chatId, "📡 جيزي ← بروكسيات <b>عامة</b> ✅");
            sendTgMainMenu($chatId);
            break;
        case 'tg_djezzy_private':
            setDjezzyProxyMode('private');
            tgSendMessage($chatId, "🔐 جيزي ← بروكسيات <b>خاصة</b> ✅");
            sendTgMainMenu($chatId);
            break;
        case 'tg_ooredoo_public':
            setOoredooProxyMode('public');
            tgSendMessage($chatId, "📡 أوريدو ← بروكسيات <b>عامة</b> ✅");
            sendTgMainMenu($chatId);
            break;
        case 'tg_ooredoo_private':
            setOoredooProxyMode('private');
            tgSendMessage($chatId, "🔐 أوريدو ← بروكسيات <b>خاصة</b> ✅");
            sendTgMainMenu($chatId);
            break;
        case 'tg_edit_ooredoo_proxies':
            $current = implode(\"\\n\", getOoredooProxies());"""

if OLD11 in src:
    src = src.replace(OLD11, NEW11, 1); changes += 1; print("✅ [11] callback cases added")
else:
    print("❌ [11] anchor not found — tg_edit_ooredoo_proxies case"); sys.exit(1)

# ══════════════════════════════════════════════════════
# حفظ الملف
# ══════════════════════════════════════════════════════
with open(TARGET, 'w', encoding='utf-8') as f:
    f.write(src)

print(f"\n✅✅✅ الباتش اكتمل — {changes}/11 تعديل طُبّق على {TARGET}")
print("🔍 للتحقق:  php -l ~/New-bot/bot1.php")
print("🔄 للرجوع:  cp ~/New-bot/bot1.php.bak ~/New-bot/bot1.php")
