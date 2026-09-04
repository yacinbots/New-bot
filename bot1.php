<?php
// ════════════════════════════════════════════════════════════════════════════
// TASJIL BOT — Facebook Messenger + Telegram Admin Bot
// ════════════════════════════════════════════════════════════════════════════

if (!isset($input)) {
    $input = json_decode(file_get_contents("php://input"), true);
}
if (!isset($event)) {
    $event = $input['entry'][0]['messaging'][0] ?? [];
}
$sender_id = $event['sender']['id'] ?? null;
$message   = $event['message']['text'] ?? '';

// ════════ Facebook Config ════════
define('FB_TOKEN',        'EAAFYLlWaXQkBSOhl9K4ZBkD5StOSm7DnlasNSbG14xViuqgIULzhhU2HkZA1jpj1LXH02jAYUc2eDqCZCcO65YDlFv0QFesE4EILjt98Ry8R4MLO6BkWcSQjZCSUH57ZAB6DId6lzd0PotL152kII3G4MNCjNT8Cq7ZAhWSkuGGA4gfJzoMeZA4tZC24tNPsJynTo4CP8ZAJhZBwZDZD');
define('VERIFY_TOKEN',    'Yacin');

// ════════ Telegram Config ════════
define('TG_TOKEN',   '8723811941:AAGi5C0AwV-G45PAoou2rYOZJovI5AmhdJM');
define('TG_ADMIN_ID', '8499896271');
define('TG_API',     'https://api.telegram.org/bot' . TG_TOKEN);

// ════════ Paths ═══════════════════════════════════════════════════════════
define('PROXY_LIST_FILE',  '/tmp/proxies.json');
define('PROXY_API_URL',    'https://dev-bendjarayacine.pantheonsite.io/wp-admin/maint/proxy.json');
define('SESSIONS_DIR',     '/tmp/fb_sessions');
define('USERS_DIR',        '/tmp/fb_users');
define('PHONE_MAP_FILE',   '/tmp/fb_phone_map.json');
define('PENDING_DIR',      '/tmp/fb_pending');
define('DB_FILE',          '/tmp/fb_dedup.sqlite');
define('NEW_USERS_FILE',   '/tmp/fb_new_users.json');
define('RATE_LIMIT_DIR',   '/tmp/fb_rate_limit');
define('TG_STATE_DIR',     '/tmp/tg_states');
define('MATCH_GIFT_FILE',  '/tmp/match_gift_config.json');
define('BROADCAST_LOG',    '/tmp/broadcast_log.json');

define('RATE_LIMIT_SECONDS', 600);

// ════════ Client Credentials — Djezzy ════════════════════════════════════
define('CLIENT_ID_OLD',     '87pIExRhxBb3_wGsA5eSEfyATloa');
define('CLIENT_SECRET_OLD', 'uf82p68Bgisp8Yg1Uz8Pf6_v1XYa');
define('CLIENT_ID_NEW',     '6E6CwTkp8H1CyQxraPmcEJPQ7xka');
define('CLIENT_SECRET_NEW', 'MVpXHW_ImuMsxKIwrJpoVVMHjRsa');

// ════════ Client Credentials — Ooredoo ════════════════════════════════════
define('OOREDOO_CLIENT_ID',        'myooredoo-app');
define('OOREDOO_AUTH_URL',         'https://apis.ooredoo.dz/api/auth/realms/myooredoo/protocol/openid-connect/token');
define('OOREDOO_BFF_BASE',         'https://apis.ooredoo.dz/api/ooredoo-bff');
define('OOREDOO_X_VERSION',        '1.5.15');
define('OOREDOO_X_SIGNATURE',      'f320f896f3da2a5a0284f9af316efb4ab0432b26406413568db116fa9dc60feb');
define('OOREDOO_PLATFORM',         'android');
define('OOREDOO_X_PLATFORM_ORIGIN','mobile-android');
define('OOREDOO_DEVICE_ID',        '9c416930-a4be-11f1-b35d-ed3f971366c3');
define('OOREDOO_INSTANCE_ID',      '9c416930-a4be-11f1-b35d-ed3f971366c31788127399747');
define('OOREDOO_PLATFORM_DATA_SIG','eyJwbGF0Zm9ybSI6ImFuZHJvaWQiLCJpcy1waHlzaWNhbC1kZXZpY2UiOnRydWUsImRldmljZS1pZCI6IjljNDE2OTMwLWE0YmUtMTFmMS1iMzVkLWVkM2Y5NzEzNjZjMzE3ODgxMjczOTk3NDcifQ==');

// ════════ Ooredoo — ثوابت الطلبات (مستخرجة من التسجيلات) ════════════════
define('OOREDOO_STATUS_FINGERPRINT',    '391d1f55cf33097ac656d9c1c8f4ef94fd427a7d6b01d5b17e490886a8e1c5ea');
define('OOREDOO_STATUS_CORRELATION',    'eb6453a0-a555-11f1-b11e-b52f92bd0a701788192386522');
define('OOREDOO_STATUS_TIMESTAMP',      '1788192386564');

define('OOREDOO_USERINFO_FINGERPRINT',  '03a0ff3c4002aaae3a2902683136d8cbca1104dd60e69b3392f3e2f67238e070');
define('OOREDOO_USERINFO_CORRELATION',  'ed48a400-a555-11f1-b11e-b52f92bd0a701788192389696');
define('OOREDOO_USERINFO_TIMESTAMP',    '1788192389709');

define('OOREDOO_CP1_FINGERPRINT',       'd717f779e8ab59712211d85641a7e66fdc77fc21722d5c0b88395202420a0d69');
define('OOREDOO_CP1_CORRELATION',       'edb2fe90-a555-11f1-b11e-b52f92bd0a701788192390393');
define('OOREDOO_CP1_TIMESTAMP',         '1788192390400');
define('OOREDOO_CP1_INTEGRITY',         'JWBTpxXcH7KY9EXYeIRp8NjQMx2dt3vF8ejjoARazyoFzOw6Cu8+wkeV+zSVHSTeI6a12SG/FU+Q/9uBrcN71RlYO4CulD2x375NKwJRAInar8DVUilHBKUfjEsoHd6EwfLCfdYzLNXJu9POfEf6tqKn3PUe0pY6qZxWLhyVAFHw8Qwb52ZqdI2soCH4S3mrz+OZL+YovdGSUKlXKIkxVroAMTAg3jljLF7gvXaEsB/O3uW+tbkBMwSX2uTOwf+rh3sgOF/zPhKCFgRvZmU1o87Fa96Y/+wOcK+UpfXTCBYVrvQ1wG6ryC7vHMSqgUhYJK4tbSoti0zv4av9pwxh9Iv2MgsMENjyKBK/TuFZxEG3xQCKj1LTMgn2xBEeKcbsKrEW3xDRwsccGI3D7GHnbUlhdI4ujDIRQVKHaZSwhTp+32Cr4EB/Qt8p/xRbdgCde9MUlM4AYIGlfyVoHSkOvTu5c0DIIy1EhowtAkbKIJl49wsYKWvpT/nXFWzeXP3R2qzaclCnIb9qihhrarmwz8l8XH2T+f7AK2uSNiuWLG5Fy2mM/YLmuJPZT9Ciag/mpy7JGImKNQAcFbSAInBdKQjUKT+COzZfrXj5rwil9/HDMtuzIovq8hrQGY+roTfVPheTLWmcjkVCOL8VXAHjPb22vOuqrkMioWT9CQ5O8RgW7RKHrObeKQMp8F7tSjOqkxYDFkPVN1G4nkEBrTlPKK9YtPl+j7IuH9fGcDTwhCTTqP8peuBryW0L11tApvAEi7VkJyBxpVxW');

define('OOREDOO_TOKEN1_FINGERPRINT',    'deb1e6b940af70681feb13e8695e7d098c1c408b862e13615de463ad0eeb956f');
define('OOREDOO_TOKEN1_TIMESTAMP',      '1788192391037');

define('OOREDOO_CP2_FINGERPRINT',       '5e2db32cbb58d13fd03534c8cc334b6ded90061fe95fc5ff3aff43b4777ef086');
define('OOREDOO_CP2_CORRELATION',       'f3179c10-a555-11f1-b11e-b52f92bd0a701788192399441');
define('OOREDOO_CP2_TIMESTAMP',         '1788192399552');
define('OOREDOO_CP2_INTEGRITY',         'JWBTpxXcH7KR/kzaHeUUydDWODuU4i3H4fL9gCgKzyYfyN4qLJJE8keV+zSVHSTeI6aEyC2tbSza/NGmkflQ/UgaNZSN9SKxz7tcdQckHY25n6mOaDZ9PPMQmF4sKZqDwebce/c1SNGNn6qvTULW/L348aJvy7Vii6dyPTu3OSPV9AwKwGwQKNHN61HyK3jx/fa8Os5ux9u3Q654YLZrAJ99QGEm/C9nEBPmlHC98RjovLGBnuJOGx6k7fnyrt/Ogy5fNg+tBgi5HEsoXDAwutX7TOWfx4YKHpKW//TvPRQgp69e2Xqa+CvGJJ+a/WlPMPhRCSsfo0OG04mVvlkc8eXiLzV/GPuJCEmcRf9Z6V+xoDyMtU7OZ3XT5BMhI/D/N68LsRn/9dIBH6X7n0WAW3tzeKZK1zENWFKrd7aLlzEdwFzO/1JTQsEDom5TRie0Kd5yic4BGvS1C1BsA2wJhzyiZ2j7RUE9m8tOAkPEIs929FYIfmH8XcCQKHT0TqT0+N+wCiyfNawPlDhZWcObscFZeEmKgvqiUFCoCj6xI1hvyHK0nu7fnZ/sGc6oNT/SrFjubYWjOyoYFL7gMnlYAhyjZhjuAwN1m1/wjwrOx8DbFr2NW8qb3hv+CIzK1zL3LDryKjqNkTxcOp4aJnmSTa73guCEmWc7pnLkLw5O8RgW7RKHrObeeQ0l9lu4HWXwlhdWRxvVMQDimhtTrGASL6sIuaxyhOkrH4HFImP70SaCoK9+euMxnzkPjg9HqPIFj+I0KSMloVxW');

define('OOREDOO_TOKEN2_FINGERPRINT',    '8fbdad0928c41754cc7cbd9379db399f1fe06fb7dbd6275a7616dfecc19d7246');
define('OOREDOO_TOKEN2_TIMESTAMP',      '1788192400922');

// ════════ إنشاء المجلدات ═════════════════════════════════════════════════
@mkdir(SESSIONS_DIR,   0777, true);
@mkdir(USERS_DIR,      0777, true);
@mkdir(PENDING_DIR,    0777, true);
@mkdir(RATE_LIMIT_DIR, 0777, true);
@mkdir(TG_STATE_DIR,   0777, true);

// ════════════════════════════════════════════════════════════════════════════
// Match Gift Config (يتحكم بها من تلقرام)
// ════════════════════════════════════════════════════════════════════════════
function getMatchGiftConfig(): array
{
    $defaults = [
        'enabled'   => false,
        'qr_code'   => 'https://www.djezzy.dz/scanwin-wd26?1',
        'gift_label'=> '12Go',
        'start_hour'=> 1,
        'end_hour'  => 5,
    ];
    if (!file_exists(MATCH_GIFT_FILE)) return $defaults;
    $d = json_decode(file_get_contents(MATCH_GIFT_FILE), true);
    return is_array($d) ? array_merge($defaults, $d) : $defaults;
}
function saveMatchGiftConfig(array $cfg): void
{
    file_put_contents(MATCH_GIFT_FILE, json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

// ════════════════════════════════════════════════════════════════════════════
// قائمة العروض
// ════════════════════════════════════════════════════════════════════════════
define('OFFERS', [
    'BTL500MBDAY'                => ['name' => '📦 5GB - 90دج - 24h',           'display' => "الإنترنت: 5GB | السعر: 90 دج | المدة: 24 ساعة"],
    'DOVINTSPEEDDAY100MoPRE'     => ['name' => '📦 300Mo - 30دج - 24h',         'display' => "الإنترنت: 300Mo | السعر: 30 دج | المدة: 24 ساعة"],
    'DOVINTSPEEDDAY250MoPRE'     => ['name' => '📦 600Mo - 50دج - 24h',         'display' => "الإنترنت: 600Mo | السعر: 50 دج | المدة: 24 ساعة"],
    'DOVINTSPEEDDAY1GoPRE'       => ['name' => '📦 2Go - 100دج - 24h',          'display' => "الإنترنت: 2Go | السعر: 100 دج | المدة: 24 ساعة"],
    'OFFREJEUNE50'               => ['name' => '📦 1Go - 50دج - 24h',           'display' => "الإنترنت: 1Go | السعر: 50 دج | المدة: 24 ساعة"],
    'BTLINTSPEEDDAY2Go'          => ['name' => '🏷️ 4GB - 70دج - 24h',          'display' => "الإنترنت: 4GB | السعر: 70 دج | المدة: 24 ساعة"],
    'BTL4GBDAY'                  => ['name' => '📦 5GB - 190دج - 24h',          'display' => "الإنترنت: 5GB | السعر: 190 دج | المدة: 24 ساعة"],
    'BTL1GBDAY'                  => ['name' => '📦 4GB - 140دج - 24h',          'display' => "الإنترنت: 4GB | السعر: 140 دج | المدة: 24 ساعة"],
    'DOVINTSPEEDWEEK2GoPRE'      => ['name' => '📦 4Go - 150دج - 7أيام',        'display' => "الإنترنت: 4Go | السعر: 150 دج | المدة: 7 أيام"],
    'DOVINTSPEEDWEEK3GoPRE'      => ['name' => '📦 10Go - 300دج - 7أيام',       'display' => "الإنترنت: 10Go | السعر: 300 دج | المدة: 7 أيام"],
    'BTLDATA2WEEKS'              => ['name' => '📦 4GB - 400دج - 15يوم',        'display' => "الإنترنت: 4GB | السعر: 400 دج | المدة: 15 يوم"],
    '1GBFB3DAYInternet'          => ['name' => '📦 1GB(FB) - 70دج - 3أيام',     'display' => "الإنترنت: 1GB (Facebook) | السعر: 70 دج | المدة: 3 أيام"],
    'DOVINTSPEEDMONTH6GoPRE'     => ['name' => '📦 12Go - 500دج - 30يوم',       'display' => "الإنترنت: 12Go | السعر: 500 دج | المدة: 30 يوم"],
    'DOVINTSPEEDMONTH15GoPRE'    => ['name' => '📦 30Go - 1000دج - 30يوم',      'display' => "الإنترنت: 30Go | السعر: 1000 دج | المدة: 30 يوم"],
    'DOVINTSPEEDMONTH30GoPRE'    => ['name' => '📦 60Go - 1500دج - 30يوم',      'display' => "الإنترنت: 60Go | السعر: 1500 دج | المدة: 30 يوم"],
    '2GBMONTH'                   => ['name' => '📦 3GB - 250دج - 30يوم',        'display' => "الإنترنت: 3GB | السعر: 250 دج | المدة: 30 يوم"],
    'BTL500MBHOUR'               => ['name' => '⚡ 1GB - 40دج - 1ساعة',         'display' => "الإنترنت: 1GB | السعر: 40 دج | المدة: 1 ساعة"],
    'ImtiyazSurpriseData2hfbPRE' => ['name' => '📘 FB غير محدود - 50دج - 4h',  'display' => "الإنترنت: Facebook غير محدود | السعر: 50 دج | المدة: 4 ساعات"],
]);

define('OFFER_SHORTCUTS', [
    '5'  => 'BTL500MBDAY',
    '6'  => 'DOVINTSPEEDDAY100MoPRE',
    '7'  => 'DOVINTSPEEDDAY250MoPRE',
    '8'  => 'DOVINTSPEEDDAY1GoPRE',
    '9'  => 'OFFREJEUNE50',
    '10' => 'BTLINTSPEEDDAY2Go',
    '11' => 'BTL4GBDAY',
    '12' => 'BTL1GBDAY',
    '13' => 'DOVINTSPEEDWEEK2GoPRE',
    '14' => 'DOVINTSPEEDWEEK3GoPRE',
    '15' => 'BTLDATA2WEEKS',
    '16' => '1GBFB3DAYInternet',
    '17' => 'DOVINTSPEEDMONTH6GoPRE',
    '18' => 'DOVINTSPEEDMONTH15GoPRE',
    '19' => 'DOVINTSPEEDMONTH30GoPRE',
    '20' => '2GBMONTH',
    '21' => 'BTL500MBHOUR',
    '22' => 'ImtiyazSurpriseData2hfbPRE',
]);

// ════════════════════════════════════════════════════════════════════════════
// SQLite — Dedup + User Lock
// ════════════════════════════════════════════════════════════════════════════
function getDB(): PDO
{
    static $db = null;
    if ($db !== null) return $db;
    $db = new PDO('sqlite:' . DB_FILE);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("PRAGMA journal_mode=WAL; PRAGMA synchronous=NORMAL;");
    $db->exec("CREATE TABLE IF NOT EXISTS processed_events (event_id TEXT PRIMARY KEY, created_at INTEGER NOT NULL)");
    $db->exec("CREATE TABLE IF NOT EXISTS user_locks (psid TEXT PRIMARY KEY, locked_at INTEGER NOT NULL)");
    $db->exec("DELETE FROM processed_events WHERE created_at < " . (time() - 3600));
    $db->exec("DELETE FROM user_locks WHERE locked_at < " . (time() - 600));
    return $db;
}
function tryMarkEvent(string $id): bool
{
    try {
        $s = getDB()->prepare("INSERT OR IGNORE INTO processed_events (event_id, created_at) VALUES (?,?)");
        $s->execute([$id, time()]);
        return $s->rowCount() > 0;
    } catch (Throwable $e) { return true; }
}
function unmarkEvent(string $id): void
{
    try { getDB()->prepare("DELETE FROM processed_events WHERE event_id=?")->execute([$id]); } catch (Throwable $e) {}
}
function tryLockUser(string $psid): bool
{
    try {
        $s = getDB()->prepare("INSERT OR IGNORE INTO user_locks (psid, locked_at) VALUES (?,?)");
        $s->execute([$psid, time()]);
        return $s->rowCount() > 0;
    } catch (Throwable $e) { return true; }
}
function unlockUser(string $psid): void
{
    try { getDB()->prepare("DELETE FROM user_locks WHERE psid=?")->execute([$psid]); } catch (Throwable $e) {}
}
function dbg(string $m): void
{
    file_put_contents('/tmp/fb_debug.log', date('Y-m-d H:i:s') . " $m\n", FILE_APPEND);
}

// ════════════════════════════════════════════════════════════════════════════
// Rate Limit
// ════════════════════════════════════════════════════════════════════════════
function rateLimitFile(string $psid): string { return RATE_LIMIT_DIR . "/{$psid}.json"; }
function getFinalResultTimestamps(string $psid): array
{
    $f = rateLimitFile($psid);
    if (!file_exists($f)) return [];
    $d = json_decode(@file_get_contents($f), true);
    return is_array($d) ? $d : [];
}
function recordFinalResult(string $psid): void
{
    $list   = getFinalResultTimestamps($psid);
    $list[] = time();
    if (count($list) > 2) $list = array_slice($list, -2);
    @file_put_contents(rateLimitFile($psid), json_encode($list));
}
function checkRateLimit(string $psid): ?int
{
    $list = getFinalResultTimestamps($psid);
    if (count($list) < 2) return null;
    $elapsed = time() - $list[0];
    if ($elapsed < RATE_LIMIT_SECONDS) return RATE_LIMIT_SECONDS - $elapsed;
    return null;
}
function formatRemainingRateLimit(int $secondsLeft): string
{
    $minutes = (int)ceil($secondsLeft / 60);
    if ($minutes <= 1) return "أقل من دقيقة";
    return "{$minutes} دقيقة";
}
function rateLimitMessage(int $secondsLeft): string
{
    return "⏳ أنت ترسل طلبات كثيرة خلال فترة قصيرة.\n\n🔁 يرجى إعادة المحاولة بعد:\n🕐 الوقت المتبقي: " . formatRemainingRateLimit($secondsLeft) . "\n\nيتم تطبيق هذا القيد لضمان استمرارية عمل خدمة البوت بشكل جيد لجميع المستخدمين.";
}

// ════════════════════════════════════════════════════════════════════════════
// New Users Tracking
// ════════════════════════════════════════════════════════════════════════════
function isNewUser(string $psid): bool
{
    $map = file_exists(NEW_USERS_FILE) ? (json_decode(file_get_contents(NEW_USERS_FILE), true) ?? []) : [];
    return !isset($map[$psid]);
}
function markUserAsSeen(string $psid): void
{
    $map = file_exists(NEW_USERS_FILE) ? (json_decode(file_get_contents(NEW_USERS_FILE), true) ?? []) : [];
    if (!isset($map[$psid])) {
        $map[$psid] = ['first_seen' => time(), 'last_active' => time()];
        file_put_contents(NEW_USERS_FILE, json_encode($map));
    } else {
        $map[$psid]['last_active'] = time();
        file_put_contents(NEW_USERS_FILE, json_encode($map));
    }
}
function getAllKnownUsers(): array
{
    if (!file_exists(NEW_USERS_FILE)) return [];
    return array_keys(json_decode(file_get_contents(NEW_USERS_FILE), true) ?? []);
}
function getActiveUsers(int $days = 7): array
{
    if (!file_exists(NEW_USERS_FILE)) return [];
    $map   = json_decode(file_get_contents(NEW_USERS_FILE), true) ?? [];
    $since = time() - ($days * 86400);
    $active = [];
    foreach ($map as $psid => $data) {
        $lastActive = is_array($data) ? ($data['last_active'] ?? 0) : $data;
        if ($lastActive >= $since) $active[] = $psid;
    }
    return $active;
}
function getUserStats(): array
{
    if (!file_exists(NEW_USERS_FILE)) return ['total' => 0, 'active_7d' => 0, 'active_30d' => 0];
    $map   = json_decode(file_get_contents(NEW_USERS_FILE), true) ?? [];
    $total = count($map);
    $now   = time();
    $a7 = 0; $a30 = 0;
    foreach ($map as $psid => $data) {
        $lastActive = is_array($data) ? ($data['last_active'] ?? 0) : $data;
        if ($now - $lastActive <= 7  * 86400) $a7++;
        if ($now - $lastActive <= 30 * 86400) $a30++;
    }
    return ['total' => $total, 'active_7d' => $a7, 'active_30d' => $a30];
}

// ════════════════════════════════════════════════════════════════════════════
// Broadcast Log
// ════════════════════════════════════════════════════════════════════════════
function getBroadcastLog(): array
{
    if (!file_exists(BROADCAST_LOG)) return [];
    return json_decode(file_get_contents(BROADCAST_LOG), true) ?? [];
}
function saveBroadcastLog(array $log): void
{
    file_put_contents(BROADCAST_LOG, json_encode($log, JSON_UNESCAPED_UNICODE));
}
function markBroadcastSent(string $broadcastId, string $psid): void
{
    $log = getBroadcastLog();
    if (!isset($log[$broadcastId])) $log[$broadcastId] = [];
    $log[$broadcastId][$psid] = time();
    saveBroadcastLog($log);
}
function wasBroadcastSent(string $broadcastId, string $psid): bool
{
    $log = getBroadcastLog();
    return isset($log[$broadcastId][$psid]);
}

// ════════════════════════════════════════════════════════════════════════════
// Pending Operations
// ════════════════════════════════════════════════════════════════════════════
function setPending(string $psid, string $op): void
{
    file_put_contents(PENDING_DIR . "/{$psid}.json", json_encode(['op' => $op, 'ts' => time()]));
}
function clearPending(string $psid): void
{
    $f = PENDING_DIR . "/{$psid}.json";
    if (file_exists($f)) @unlink($f);
}
function getPending(string $psid): ?string
{
    $f = PENDING_DIR . "/{$psid}.json";
    if (!file_exists($f)) return null;
    $d = json_decode(@file_get_contents($f), true);
    if (!$d) return null;
    if (time() - ($d['ts'] ?? 0) > 600) { @unlink($f); return null; }
    return $d['op'] ?? null;
}

// ════════════════════════════════════════════════════════════════════════════
// Proxy System
// ════════════════════════════════════════════════════════════════════════════
function loadProxies(): array
{
    if (file_exists(PROXY_LIST_FILE)) {
        $d = json_decode(file_get_contents(PROXY_LIST_FILE), true);
        if (is_array($d) && count($d) > 0) return $d;
    }
    return [
    "http://gate.kookeey.info:1000:7464034-bc00eb59f5:fc54fe8a0a-DZ-32737453-1m",
    "http://gate.kookeey.info:1000:7464034-bc00eb59f5:fc54fe8a0a-DZ-56432587-1m",
    "http://gate.kookeey.info:1000:7464034-bc00eb59f5:fc54fe8a0a-DZ-27039306-1m",
    "http://gate.kookeey.info:1000:7464034-bc00eb59f5:fc54fe8a0a-DZ-81339712-1m",
    "http://gate.kookeey.info:1000:7464034-bc00eb59f5:fc54fe8a0a-DZ-15043776-1m",
    "http://mobile.kookeey.info:1086:7464034-bc00eb59f5:fc54fe8a0a-DZ-83875156-1m",
    "http://mobile.kookeey.info:1086:7464034-bc00eb59f5:fc54fe8a0a-DZ-55676526-1m",
    "http://mobile.kookeey.info:1086:7464034-bc00eb59f5:fc54fe8a0a-DZ-28123568-1m",
    "http://mobile.kookeey.info:1086:7464034-bc00eb59f5:fc54fe8a0a-DZ-23954035-1m",
    "http://mobile.kookeey.info:1086:7464034-bc00eb59f5:fc54fe8a0a-DZ-68203884-1m",
    "http://gate.kookeey.info:1000:6569414-55154c240e:199dff35c9-DZ-43435299-1m",
    "http://gate.kookeey.info:1000:6569414-55154c240e:199dff35c9-DZ-70136297-1m",
    "http://gate.kookeey.info:1000:6569414-55154c240e:199dff35c9-DZ-09690211-1m",
    "http://gate.kookeey.info:1000:6569414-55154c240e:199dff35c9-DZ-54922057-1m",
    "http://gate.kookeey.info:1000:6569414-55154c240e:199dff35c9-DZ-41481427-1m",
    "http://mobile.kookeey.info:1086:6569414-55154c240e:199dff35c9-DZ-79987015-1m",
    "http://mobile.kookeey.info:1086:6569414-55154c240e:199dff35c9-DZ-46036262-1m",
    "http://mobile.kookeey.info:1086:6569414-55154c240e:199dff35c9-DZ-44649406-1m",
    "http://mobile.kookeey.info:1086:6569414-55154c240e:199dff35c9-DZ-11784209-1m",
    "http://mobile.kookeey.info:1086:6569414-55154c240e:199dff35c9-DZ-22681983-1m",
    "http://gate.kookeey.info:1000:1049279-6d55cdb272:b6ce229818-DZ-70127176-1m",
    "http://gate.kookeey.info:1000:1049279-6d55cdb272:b6ce229818-DZ-28888193-1m",
    "http://gate.kookeey.info:1000:1049279-6d55cdb272:b6ce229818-DZ-39691366-1m",
    "http://gate.kookeey.info:1000:1049279-6d55cdb272:b6ce229818-DZ-67583368-1m",
    "http://gate.kookeey.info:1000:1049279-6d55cdb272:b6ce229818-DZ-82483278-1m",
    "http://mobile.kookeey.info:1086:1049279-6d55cdb272:b6ce229818-DZ-65551033-1m",
    "http://mobile.kookeey.info:1086:1049279-6d55cdb272:b6ce229818-DZ-25039549-1m",
    "http://mobile.kookeey.info:1086:1049279-6d55cdb272:b6ce229818-DZ-26317792-1m",
    "http://mobile.kookeey.info:1086:1049279-6d55cdb272:b6ce229818-DZ-81851342-1m",
    "http://mobile.kookeey.info:1086:1049279-6d55cdb272:b6ce229818-DZ-25594447-1m",
    "http://gate.kookeey.info:1000:1518711-520d25c499:094049a358-DZ-88224532-1m",
    "http://gate.kookeey.info:1000:1518711-520d25c499:094049a358-DZ-60208739-1m",
    "http://gate.kookeey.info:1000:1518711-520d25c499:094049a358-DZ-71249293-1m",
    "http://gate.kookeey.info:1000:1518711-520d25c499:094049a358-DZ-41349815-1m",
    "http://gate.kookeey.info:1000:1518711-520d25c499:094049a358-DZ-71859234-1m",
    "http://mobile.kookeey.info:1086:1518711-520d25c499:094049a358-DZ-86736697-1m",
    "http://mobile.kookeey.info:1086:1518711-520d25c499:094049a358-DZ-30893493-1m",
    "http://mobile.kookeey.info:1086:1518711-520d25c499:094049a358-DZ-28800972-1m",
    "http://mobile.kookeey.info:1086:1518711-520d25c499:094049a358-DZ-66006049-1m",
    "http://mobile.kookeey.info:1086:1518711-520d25c499:094049a358-DZ-04398964-1m",
    "http://gate.kookeey.info:1000:8179119-2072bee7e4:35067ea761-DZ-81920635-1m",
    "http://gate.kookeey.info:1000:8179119-2072bee7e4:35067ea761-DZ-61920809-1m",
    "http://gate.kookeey.info:1000:8179119-2072bee7e4:35067ea761-DZ-44451293-1m",
    "http://gate.kookeey.info:1000:8179119-2072bee7e4:35067ea761-DZ-48117989-1m",
    "http://gate.kookeey.info:1000:8179119-2072bee7e4:35067ea761-DZ-60195406-1m",
    "http://mobile.kookeey.info:1086:8179119-2072bee7e4:35067ea761-DZ-91715029-1m",
    "http://mobile.kookeey.info:1086:8179119-2072bee7e4:35067ea761-DZ-33683624-1m",
    "http://mobile.kookeey.info:1086:8179119-2072bee7e4:35067ea761-DZ-89868487-1m",
    "http://mobile.kookeey.info:1086:8179119-2072bee7e4:35067ea761-DZ-53888793-1m",
    "http://mobile.kookeey.info:1086:8179119-2072bee7e4:35067ea761-DZ-95280483-1m"
];
}
function saveProxies(array $proxies): void
{
    file_put_contents(PROXY_LIST_FILE, json_encode($proxies));
}
function refreshProxies(): array
{
    $ch = curl_init(PROXY_API_URL);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 8, CURLOPT_CONNECTTIMEOUT => 4, CURLOPT_SSL_VERIFYPEER => false]);
    $body = curl_exec($ch);
    curl_close($ch);
    $list = json_decode($body, true);
    if (is_array($list) && count($list) > 0) {
        saveProxies($list);
        return $list;
    }
    return loadProxies();
}
function parseProxy(string $proxy): array
{
    $raw = preg_replace('#^https?://#', '', $proxy);
    $p   = explode(':', $raw, 4);
    return ['host' => ($p[0] ?? '') . ':' . ($p[1] ?? ''), 'userpass' => ($p[2] ?? '') . ':' . ($p[3] ?? '')];
}

function getAllProxies(): array
{
    $local     = loadProxies();
    $fromApi   = refreshProxies();
    $combined  = array_unique(array_merge($local, $fromApi));
    return array_values($combined);
}

/**
 * curlWithAllProxies — يجرب جميع البروكسيات واحدة تلو الأخرى
 * يُعيد ['http_code', 'body', 'json'] أو null إذا فشلت الكل
 * 
 * @param bool $allowDirect  هل يسمح بالطلب المباشر (بدون بروكسي) إذا كانت البروكسيات كلها فاشلة؟
 */
function curlWithAllProxies(
    string $url,
    string $method,
    string $payload,
    array  $headers,
    string $logTag,
    int    $timeout = 12,
    string $logFile = '/tmp/proxy_curl.log',
    bool   $allowDirect = true
): ?array {
    $proxies = getAllProxies();
    $totalProxies = count($proxies);

    // إذا لم توجد بروكسيات وسمح بالمباشر
    if ($totalProxies === 0 && $allowDirect) {
        dbg("[{$logTag}] No proxies, trying direct...");
        $result = doCurlRequest($url, $method, $payload, $headers, $timeout);
        if ($result !== null) return $result;
        dbg("[{$logTag}] Direct failed too!");
        tgNotifyAdmin("⚠️ لا توجد بروكسيات والطلب المباشر فشل! [{$logTag}]");
        return null;
    }

    if ($totalProxies === 0) {
        dbg("[{$logTag}] No proxies available!");
        tgNotifyAdmin("⚠️ لا توجد بروكسيات متاحة! [{$logTag}]");
        return null;
    }

    $failedCount = 0;

    foreach ($proxies as $idx => $p) {
        $pp = parseProxy($p);
        $result = doCurlRequest($url, $method, $payload, $headers, $timeout, $pp['host'], $pp['userpass']);
        
        file_put_contents($logFile,
            date('Y-m-d H:i:s') . " [{$logTag}] proxy[{$idx}/{$totalProxies}] " . 
            ($result ? "http={$result['http_code']}" : "FAILED") . "\n",
            FILE_APPEND
        );

        if ($result !== null) {
            $body = $result['body'] ?? '';
            // كشف الـ HTML (IP محظور)
            if (stripos($body, '<html') !== false || stripos($body, '<!DOCTYPE') !== false) {
                $failedCount++;
                continue;
            }
            // كشف الـ 403 مع HTML
            if ($result['http_code'] === 403 && (stripos($body, '<html') !== false || stripos($body, '<!DOCTYPE') !== false)) {
                $failedCount++;
                continue;
            }
            return $result;
        }
        $failedCount++;
    }

    // إذا فشلت كل البروكسيات وسمح بالمباشر — جرب مباشر
    if ($allowDirect) {
        dbg("[{$logTag}] All proxies failed, trying direct...");
        $result = doCurlRequest($url, $method, $payload, $headers, $timeout);
        if ($result !== null) {
            $body = $result['body'] ?? '';
            if (stripos($body, '<html') === false && stripos($body, '<!DOCTYPE') === false) {
                return $result;
            }
        }
    }

    dbg("[{$logTag}] ALL {$totalProxies} proxies failed!");
    tgNotifyAdmin("🚨 تنبيه: جميع البروكسيات ({$totalProxies}) فشلت في [{$logTag}]!\n\n🔄 يرجى إرسال قائمة بروكسيات جديدة باستخدام:\n/setproxies");
    return null;
}

/**
 * doCurlRequest — طلب واحد مع أو بدون بروكسي
 */
function doCurlRequest(string $url, string $method, string $payload, array $headers, int $timeout, string $proxyHost = '', string $proxyAuth = ''): ?array
{
    $ch = curl_init($url);
    $opts = [
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => 'gzip',
        CURLOPT_TIMEOUT        => $timeout,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 5,
    ];

    if ($method === 'POST') {
        $opts[CURLOPT_POST]       = true;
        $opts[CURLOPT_POSTFIELDS] = $payload;
    } else {
        $opts[CURLOPT_HTTPGET] = true;
    }

    if ($proxyHost && $proxyAuth) {
        $opts[CURLOPT_PROXY]        = $proxyHost;
        $opts[CURLOPT_PROXYUSERPWD] = $proxyAuth;
        $opts[CURLOPT_PROXYTYPE]    = CURLPROXY_HTTP;
    }

    curl_setopt_array($ch, $opts);
    $body = curl_exec($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errno = curl_errno($ch);
    $errMsg = curl_error($ch);
    curl_close($ch);

    if ($errno || $body === false || $body === '') {
        return null;
    }

    $json = @json_decode((string)$body, true);
    return ['http_code' => $httpCode, 'body' => (string)$body, 'json' => $json];
}

// ════════════════════════════════════════════════════════════════════════════
// Telegram Notification
// ════════════════════════════════════════════════════════════════════════════
function tgNotifyAdmin(string $text): void
{
    tgSendMessage(TG_ADMIN_ID, $text);
}
function tgSendMessage(string $chatId, string $text, array $keyboard = []): void
{
    $data = ['chat_id' => $chatId, 'text' => $text, 'parse_mode' => 'HTML'];
    if (!empty($keyboard)) {
        $data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard], JSON_UNESCAPED_UNICODE);
    }
    $ch = curl_init(TG_API . '/sendMessage');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $resp = curl_exec($ch);
    curl_close($ch);
    file_put_contents('/tmp/tg_send.log', date('Y-m-d H:i:s') . " chat={$chatId} resp={$resp}\n", FILE_APPEND);
}
function tgAnswerCallback(string $callbackId, string $text = ''): void
{
    $ch = curl_init(TG_API . '/answerCallbackQuery');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode(['callback_query_id' => $callbackId, 'text' => $text]),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    curl_exec($ch);
    curl_close($ch);
}
function tgEditMessage(string $chatId, int $messageId, string $text, array $keyboard = []): void
{
    $data = ['chat_id' => $chatId, 'message_id' => $messageId, 'text' => $text, 'parse_mode' => 'HTML'];
    if (!empty($keyboard)) {
        $data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard], JSON_UNESCAPED_UNICODE);
    }
    $ch = curl_init(TG_API . '/editMessageText');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    curl_exec($ch);
    curl_close($ch);
}

// ════════════════════════════════════════════════════════════════════════════
// Telegram State (للأوامر متعددة الخطوات)
// ════════════════════════════════════════════════════════════════════════════
function getTgState(string $chatId): array
{
    $f = TG_STATE_DIR . "/{$chatId}.json";
    if (!file_exists($f)) return [];
    return json_decode(file_get_contents($f), true) ?? [];
}
function setTgState(string $chatId, array $state): void
{
    file_put_contents(TG_STATE_DIR . "/{$chatId}.json", json_encode($state));
}
function clearTgState(string $chatId): void
{
    $f = TG_STATE_DIR . "/{$chatId}.json";
    if (file_exists($f)) @unlink($f);
}

// ════════════════════════════════════════════════════════════════════════════
// Telegram Webhook Handler
// ════════════════════════════════════════════════════════════════════════════
function handleTelegramUpdate(array $update): void
{
    if (isset($update['callback_query'])) {
        $cb     = $update['callback_query'];
        $cbId   = $cb['id'];
        $chatId = (string)($cb['message']['chat']['id'] ?? '');
        $msgId  = (int)($cb['message']['message_id'] ?? 0);
        $data   = $cb['data'] ?? '';

        if ($chatId !== TG_ADMIN_ID) { tgAnswerCallback($cbId, '⛔ غير مصرح'); return; }
        tgAnswerCallback($cbId);
        handleTgCallback($chatId, $msgId, $data);
        return;
    }

    if (!isset($update['message'])) return;
    $msg    = $update['message'];
    $chatId = (string)($msg['chat']['id'] ?? '');
    $text   = trim($msg['text'] ?? '');

    if ($chatId !== TG_ADMIN_ID) {
        tgSendMessage($chatId, '⛔ أنت لست مصرحاً باستخدام هذا البوت.');
        return;
    }

    $state = getTgState($chatId);

    if (($state['action'] ?? '') === 'awaiting_broadcast') {
        handleTgBroadcastText($chatId, $text, $state);
        return;
    }
    if (($state['action'] ?? '') === 'awaiting_proxies') {
        handleTgProxiesInput($chatId, $text);
        return;
    }
    if (($state['action'] ?? '') === 'awaiting_qr') {
        handleTgQrInput($chatId, $text);
        return;
    }

    switch ($text) {
        case '/start':
        case '/help':
            sendTgMainMenu($chatId);
            break;
        case '/stats':
            handleTgStats($chatId);
            break;
        case '/broadcast':
            handleTgBroadcastStart($chatId);
            break;
        case '/setproxies':
            handleTgSetProxies($chatId);
            break;
        case '/proxies':
            handleTgShowProxies($chatId);
            break;
        case '/matchgift':
            handleTgMatchGift($chatId);
            break;
        case '/cancel':
            clearTgState($chatId);
            tgSendMessage($chatId, '✅ تم إلغاء العملية الحالية.');
            sendTgMainMenu($chatId);
            break;
        default:
            sendTgMainMenu($chatId);
    }
}

function sendTgMainMenu(string $chatId): void
{
    $cfg     = getMatchGiftConfig();
    $status  = $cfg['enabled'] ? '✅ مفعّلة' : '❌ معطلة';
    $qr      = $cfg['qr_code'];
    $label   = $cfg['gift_label'];

    $text = "🤖 <b>لوحة تحكم Tasjil BOT</b>\n\n"
          . "📊 اختر أمراً من القائمة أدناه:\n\n"
          . "━━━━━━━━━━━━━━━━━━━━\n"
          . "🎁 هدية المباراة: <b>{$status}</b>\n"
          . "📦 الهدية: <b>{$label}</b>\n"
          . "🔗 QR: <code>{$qr}</code>\n"
          . "━━━━━━━━━━━━━━━━━━━━";

    $keyboard = [
        [
            ['text' => '📊 إحصائيات المستخدمين', 'callback_data' => 'tg_stats'],
            ['text' => '📢 إرسال إعلان',          'callback_data' => 'tg_broadcast'],
        ],
        [
            ['text' => '🔗 إدارة البروكسيات',      'callback_data' => 'tg_proxies'],
            ['text' => '➕ إضافة بروكسيات',         'callback_data' => 'tg_setproxies'],
        ],
        [
            ['text' => $cfg['enabled'] ? '🔴 إيقاف هدية المباراة' : '🟢 تفعيل هدية المباراة', 'callback_data' => 'tg_toggle_match'],
            ['text' => '✏️ تغيير QR Code',          'callback_data' => 'tg_set_qr'],
        ],
        [
            ['text' => '🔄 تحديث البروكسيات (API)',  'callback_data' => 'tg_refresh_proxies'],
        ],
    ];

    tgSendMessage($chatId, $text, $keyboard);
}

function handleTgCallback(string $chatId, int $msgId, string $data): void
{
    switch ($data) {
        case 'tg_stats':
            handleTgStats($chatId);
            break;
        case 'tg_broadcast':
            handleTgBroadcastStart($chatId);
            break;
        case 'tg_proxies':
            handleTgShowProxies($chatId);
            break;
        case 'tg_setproxies':
            handleTgSetProxies($chatId);
            break;
        case 'tg_toggle_match':
            handleTgToggleMatch($chatId);
            break;
        case 'tg_set_qr':
            handleTgSetQr($chatId);
            break;
        case 'tg_refresh_proxies':
            handleTgRefreshProxies($chatId);
            break;
        case 'tg_broadcast_all':
            setTgState($chatId, ['action' => 'awaiting_broadcast', 'target' => 'all']);
            tgSendMessage($chatId, "📝 أرسل نص الإعلان الذي تريد إرساله لـ <b>جميع المستخدمين</b>:\n\n/cancel للإلغاء");
            break;
        case 'tg_broadcast_active':
            setTgState($chatId, ['action' => 'awaiting_broadcast', 'target' => 'active_7d']);
            tgSendMessage($chatId, "📝 أرسل نص الإعلان الذي تريد إرساله للمستخدمين <b>النشطين (7 أيام)</b>:\n\n/cancel للإلغاء");
            break;
        default:
            if (str_starts_with($data, 'tg_confirm_broadcast_')) {
                $broadcastId = substr($data, strlen('tg_confirm_broadcast_'));
                executeBroadcast($chatId, $broadcastId);
            }
            break;
    }
}

// ─── Stats ───────────────────────────────────────────────────────────────────
function handleTgStats(string $chatId): void
{
    $stats   = getUserStats();
    $proxies = loadProxies();
    $cfg     = getMatchGiftConfig();

    $text = "📊 <b>إحصائيات Tasjil BOT</b>\n\n"
          . "👥 إجمالي المستخدمين: <b>{$stats['total']}</b>\n"
          . "🟢 نشط (7 أيام): <b>{$stats['active_7d']}</b>\n"
          . "🟡 نشط (30 يوم): <b>{$stats['active_30d']}</b>\n\n"
          . "🔗 عدد البروكسيات: <b>" . count($proxies) . "</b>\n"
          . "🎁 هدية المباراة: <b>" . ($cfg['enabled'] ? '✅ مفعّلة' : '❌ معطلة') . "</b>\n\n"
          . "📅 التاريخ: " . date('Y-m-d H:i:s');

    $keyboard = [[['text' => '🔙 رجوع', 'callback_data' => 'tg_stats']]];
    tgSendMessage($chatId, $text, $keyboard);
}

// ─── Broadcast ───────────────────────────────────────────────────────────────
function handleTgBroadcastStart(string $chatId): void
{
    $stats = getUserStats();
    $text  = "📢 <b>إرسال إعلان</b>\n\n"
           . "👥 إجمالي المستخدمين: {$stats['total']}\n"
           . "🟢 النشطين (7 أيام): {$stats['active_7d']}\n\n"
           . "اختر الجمهور المستهدف:";

    $keyboard = [
        [
            ['text' => "📢 الكل ({$stats['total']})",           'callback_data' => 'tg_broadcast_all'],
            ['text' => "✅ النشطين ({$stats['active_7d']})",     'callback_data' => 'tg_broadcast_active'],
        ],
        [['text' => '❌ إلغاء', 'callback_data' => 'tg_stats']],
    ];
    tgSendMessage($chatId, $text, $keyboard);
}

function handleTgBroadcastText(string $chatId, string $text, array $state): void
{
    if (trim($text) === '' || $text === '/cancel') {
        clearTgState($chatId);
        tgSendMessage($chatId, '❌ تم إلغاء الإعلان.');
        return;
    }

    $target      = $state['target'] ?? 'all';
    $broadcastId = 'bc_' . time() . '_' . substr(md5($text), 0, 6);

    setTgState($chatId, [
        'action'       => 'pending_broadcast',
        'target'       => $target,
        'broadcast_id' => $broadcastId,
        'message'      => $text,
    ]);

    $users     = ($target === 'all') ? getAllKnownUsers() : getActiveUsers(7);
    $userCount = count($users);

    $preview = "📢 <b>معاينة الإعلان</b>\n\n"
             . "🎯 المستهدفون: <b>{$userCount} مستخدم</b>\n"
             . "🆔 معرف البث: <code>{$broadcastId}</code>\n\n"
             . "━━━━━━━━━━━━━━\n"
             . htmlspecialchars($text)
             . "\n━━━━━━━━━━━━━━\n\n"
             . "⚠️ هل تريد إرسال هذا الإعلان؟";

    $keyboard = [
        [
            ['text' => "✅ إرسال للـ {$userCount}",         'callback_data' => "tg_confirm_broadcast_{$broadcastId}"],
            ['text' => '❌ إلغاء',                          'callback_data' => 'tg_broadcast'],
        ],
    ];
    tgSendMessage($chatId, $preview, $keyboard);
}

function executeBroadcast(string $chatId, string $broadcastId): void
{
    $state = getTgState($chatId);
    if (($state['broadcast_id'] ?? '') !== $broadcastId) {
        tgSendMessage($chatId, '❌ معرف البث غير متطابق.');
        return;
    }

    $target  = $state['target']  ?? 'all';
    $msgText = $state['message'] ?? '';
    $users   = ($target === 'all') ? getAllKnownUsers() : getActiveUsers(7);

    clearTgState($chatId);

    tgSendMessage($chatId, "🚀 جاري الإرسال لـ <b>" . count($users) . "</b> مستخدم...");

    $sent   = 0;
    $failed = 0;
    $skipped = 0;

    foreach ($users as $uid) {
        if (wasBroadcastSent($broadcastId, $uid)) { $skipped++; continue; }
        $result = sendFbMessage($uid, "📢 إعلان:\n\n" . $msgText);
        if ($result) {
            markBroadcastSent($broadcastId, $uid);
            $sent++;
        } else {
            $failed++;
        }
        usleep(80000);
    }

    $summary = "✅ <b>اكتمل الإعلان</b>\n\n"
             . "📤 مُرسَل: <b>{$sent}</b>\n"
             . "❌ فشل: <b>{$failed}</b>\n"
             . "⏭️ تم تخطيه (سبق الإرسال): <b>{$skipped}</b>\n"
             . "🆔 معرف البث: <code>{$broadcastId}</code>";

    tgSendMessage($chatId, $summary);
    sendTgMainMenu($chatId);
}

// ─── Proxies ─────────────────────────────────────────────────────────────────
function handleTgShowProxies(string $chatId): void
{
    $proxies = loadProxies();
    $count   = count($proxies);

    if ($count === 0) {
        tgSendMessage($chatId, "⚠️ لا توجد بروكسيات محفوظة!\n\nأرسل /setproxies لإضافة بروكسيات جديدة.");
        return;
    }

    $lines = ["🔗 <b>البروكسيات المحفوظة ({$count})</b>\n"];
    foreach ($proxies as $i => $p) {
        $pp     = parseProxy($p);
        $host   = explode(':', $pp['host'])[0] ?? $pp['host'];
        $lines[] = ($i + 1) . ". <code>{$host}</code>";
    }
    $lines[] = "\n/setproxies لإضافة بروكسيات جديدة";

    $keyboard = [
        [
            ['text' => '🔄 تحديث من API',    'callback_data' => 'tg_refresh_proxies'],
            ['text' => '➕ إضافة بروكسيات',  'callback_data' => 'tg_setproxies'],
        ],
        [['text' => '🔙 رجوع', 'callback_data' => 'tg_stats']],
    ];
    tgSendMessage($chatId, implode("\n", $lines), $keyboard);
}

function handleTgSetProxies(string $chatId): void
{
    setTgState($chatId, ['action' => 'awaiting_proxies']);
    tgSendMessage($chatId,
        "📝 <b>إضافة بروكسيات جديدة</b>\n\n"
        . "أرسل قائمة البروكسيات، كل بروكسي في سطر بالصيغة:\n"
        . "<code>https://host:port:user:pass</code>\n\n"
        . "أو JSON array مثل:\n"
        . "<code>[\"https://host:port:user:pass\"]</code>\n\n"
        . "⚠️ ستُستبدل القائمة الحالية بالقائمة الجديدة.\n"
        . "/cancel للإلغاء"
    );
}

function handleTgProxiesInput(string $chatId, string $text): void
{
    if ($text === '/cancel') { clearTgState($chatId); tgSendMessage($chatId, '❌ تم الإلغاء.'); return; }

    $list = @json_decode($text, true);
    if (!is_array($list)) {
        $list = array_filter(array_map('trim', explode("\n", $text)));
        $list = array_values($list);
    }

    if (empty($list)) {
        tgSendMessage($chatId, '❌ لم أتمكن من قراءة البروكسيات، تحقق من الصيغة وأعد المحاولة.');
        return;
    }

    $valid = [];
    foreach ($list as $item) {
        $item = trim($item);
        if (preg_match('#^https?://.+:.+:.+:.+$#', $item)) {
            $valid[] = $item;
        }
    }

    if (empty($valid)) {
        tgSendMessage($chatId, '❌ لا توجد بروكسيات بصيغة صحيحة. الصيغة: https://host:port:user:pass');
        return;
    }

    saveProxies($valid);
    clearTgState($chatId);
    tgSendMessage($chatId, "✅ تم حفظ <b>" . count($valid) . "</b> بروكسي بنجاح!");
    sendTgMainMenu($chatId);
}

function handleTgRefreshProxies(string $chatId): void
{
    tgSendMessage($chatId, '🔄 جاري تحديث البروكسيات من API...');
    $proxies = refreshProxies();
    tgSendMessage($chatId, "✅ تم تحديث البروكسيات: <b>" . count($proxies) . "</b> بروكسي.");
    sendTgMainMenu($chatId);
}

// ─── Match Gift ───────────────────────────────────────────────────────────────
function handleTgToggleMatch(string $chatId): void
{
    $cfg = getMatchGiftConfig();
    $cfg['enabled'] = !$cfg['enabled'];
    saveMatchGiftConfig($cfg);
    $status = $cfg['enabled'] ? '✅ مفعّلة' : '❌ معطلة';
    tgSendMessage($chatId, "🎁 هدية المباراة الآن: <b>{$status}</b>");
    sendTgMainMenu($chatId);
}

function handleTgMatchGift(string $chatId): void
{
    $cfg    = getMatchGiftConfig();
    $status = $cfg['enabled'] ? '✅ مفعّلة' : '❌ معطلة';

    $text = "🎁 <b>إعدادات هدية المباراة</b>\n\n"
          . "الحالة: <b>{$status}</b>\n"
          . "الهدية: <b>{$cfg['gift_label']}</b>\n"
          . "QR Code: <code>{$cfg['qr_code']}</code>\n";

    $keyboard = [
        [
            ['text' => $cfg['enabled'] ? '🔴 إيقاف' : '🟢 تفعيل', 'callback_data' => 'tg_toggle_match'],
            ['text' => '✏️ تغيير QR',                              'callback_data' => 'tg_set_qr'],
        ],
        [['text' => '🔙 رجوع', 'callback_data' => 'tg_stats']],
    ];
    tgSendMessage($chatId, $text, $keyboard);
}

function handleTgSetQr(string $chatId): void
{
    $cfg = getMatchGiftConfig();
    setTgState($chatId, ['action' => 'awaiting_qr']);
    tgSendMessage($chatId,
        "✏️ <b>تغيير QR Code لهدية المباراة</b>\n\n"
        . "QR الحالي:\n<code>{$cfg['qr_code']}</code>\n\n"
        . "أرسل الـ QR Code الجديد:\n"
        . "/cancel للإلغاء"
    );
}

function handleTgQrInput(string $chatId, string $text): void
{
    if ($text === '/cancel') { clearTgState($chatId); tgSendMessage($chatId, '❌ تم الإلغاء.'); return; }

    $cfg = getMatchGiftConfig();
    $cfg['qr_code'] = trim($text);
    saveMatchGiftConfig($cfg);
    clearTgState($chatId);
    tgSendMessage($chatId, "✅ تم تحديث QR Code:\n<code>{$cfg['qr_code']}</code>");
    sendTgMainMenu($chatId);
}

// ════════════════════════════════════════════════════════════════════════════
// Webhook Routing — Facebook & Telegram
// ════════════════════════════════════════════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['hub_mode'], $_GET['hub_verify_token'], $_GET['hub_challenge'])
        && $_GET['hub_mode'] === 'subscribe'
        && $_GET['hub_verify_token'] === VERIFY_TOKEN) {
        http_response_code(200);
        echo $_GET['hub_challenge'];
    } else {
        http_response_code(403);
        echo 'Forbidden';
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawInput = file_get_contents('php://input');
    $data     = json_decode($rawInput, true);
    http_response_code(200);
    header('Content-Type: text/plain');
    echo 'EVENT_RECEIVED';
    if (function_exists('fastcgi_finish_request')) fastcgi_finish_request();

    if (!$data) exit;

    if (isset($data['update_id'])) {
        handleTelegramUpdate($data);
        exit;
    }

    if (($data['object'] ?? '') !== 'page') exit;

    foreach ($data['entry'] as $entry) {
        foreach ($entry['messaging'] ?? [] as $event) {
            $psid = $event['sender']['id'] ?? null;
            if (!$psid) continue;
            $eid = buildEventId($psid, $event);
            if (!tryMarkEvent($eid)) { dbg("[DUP] $psid $eid"); continue; }
            if (!tryLockUser($psid)) { dbg("[LOCK] $psid busy"); unmarkEvent($eid); continue; }
            try { processEvent($psid, $event); }
            catch (Throwable $e) { dbg("[ERR] $psid " . $e->getMessage()); }
            finally { unlockUser($psid); }
        }
    }
    exit;
}

http_response_code(200);
echo 'OK';
exit;

// ════════════════════════════════════════════════════════════════════════════
function buildEventId(string $psid, array $event): string
{
    if (isset($event['message'])) {
        $mid = $event['message']['mid'] ?? '';
        if ($mid !== '') return "msg_{$mid}";
        $ts = (int)($event['timestamp'] ?? time());
        return "msg_{$psid}_" . md5(trim($event['message']['text'] ?? '')) . "_" . (int)($ts / 10);
    }
    if (isset($event['postback'])) {
        $ts = (int)($event['timestamp'] ?? time());
        return "pb_{$psid}_" . md5($event['postback']['payload'] ?? '') . "_" . (int)($ts / 10);
    }
    return "ev_{$psid}_" . md5(json_encode($event));
}

// ════════════════════════════════════════════════════════════════════════════
// Process Facebook Event
// ════════════════════════════════════════════════════════════════════════════
function processEvent(string $psid, array $event): void
{
    $isNew = isNewUser($psid);
    markUserAsSeen($psid);

    if (isset($event['postback'])) { handlePostback($psid, $event['postback']['payload'] ?? ''); return; }
    if (!isset($event['message'])) return;

    $msg = $event['message'];
    if (isset($msg['sticker_id']) && $msg['sticker_id'] == 369239263222822) { sendMessage($psid, '👍'); return; }
    if (isset($msg['attachments']) && empty($msg['text'])) { sendMessage($psid, randomSticker()); return; }
    if (isset($msg['quick_reply']['payload'])) { handlePostback($psid, $msg['quick_reply']['payload']); return; }

    $text   = trim($msg['text'] ?? '');
    $digits = preg_replace('/\D/', '', $text);
    if ($text === '') { if ($isNew) sendWelcomeNew($psid); else sendWelcome($psid); return; }

    if (preg_match('/@#(.+?)@#/su', $text, $adMatch)) { handleAdminBroadcast($psid, trim($adMatch[1])); return; }

    $session = getSession($psid);
    $state   = $session['state'] ?? 'idle';

    if ($state === 'awaiting_otp')          { handleAwaitingOtp($psid, $text, $session); return; }
    if ($state === 'awaiting_offer_otp')    { handleOfferOtp($psid, $text, $session); return; }
    if ($state === 'awaiting_invite_phone') { handleInvitePhoneInput($psid, $text, $session); return; }
    if ($state === 'awaiting_invitee_otp')  { handleInviteeOtp($psid, $text, $session); return; }
    
    // ── Ooredoo States ──────────────────────────────────────────────────────
    if ($state === 'awaiting_ooredoo_otp')     { handleOoredooAwaitingOtp($psid, $text, $session); return; }
    if ($state === 'awaiting_ooredoo_gift_otp') { handleOoredooGiftOtp($psid, $text, $session); return; }

    $pending = getPending($psid);
    if ($pending !== null) { sendMessage($psid, "⏳ انتظر، نحن نقوم بـ {$pending}\nبعدها يمكنك الطلب."); return; }

    // ── رقم Djezzy ──────────────────────────────────────────────────────────
    if (preg_match('/^07\d{8}$/', $digits)) { handleNewPhone($psid, $digits); return; }
    
    // ── رقم Ooredoo ──────────────────────────────────────────────────────────
    if (preg_match('/^05\d{8}$/', $digits)) {
        handleOoredooNewPhone($psid, $digits);
        return;
    }
    
    if (preg_match('/^06\d{8}$/', $digits)) { sendMessage($psid, "❌ لا يوجد تسجيل Mobilis."); return; }

    if ($state === 'menu' || $state === 'offers') {
        if     ($text === '1')  handlePostback($psid, 'MENU_2G');
        elseif ($text === '2')  handlePostback($psid, 'MENU_70DZ');
        elseif ($text === '3')  handlePostback($psid, 'MENU_INVITE');
        elseif ($text === '4')  handlePostback($psid, 'MENU_MORE_OFFERS');
        elseif ($text === '5')  handlePostback($psid, 'MENU_OOREDOO_GIFT');  // زر هدية أوريدو
        elseif ($text === '30') {
            $cfg  = getMatchGiftConfig();
            $sess = getSession($psid);
            $user = getUser($psid);
            if (!$cfg['enabled']) {
                sendMessage($psid, "⏰ هدية المباراة غير متاحة حالياً.\n\n⚡ قناة التلغرام: https://t.me/tasjilbott");
                return;
            }
            if (!$user || empty($user['access_token'])) {
                sendMessage($psid, "⚠️ يجب تسجيل الدخول أولاً، أرسل رقم هاتفك.");
                return;
            }
            if (!empty($sess['msisdn'])) $user['msisdn'] = $sess['msisdn'];
            activateAlgeriaMatchGift($psid, $user);
        }
        elseif (isset(OFFER_SHORTCUTS[$text])) {
            handlePostback($psid, 'ACTIVATE_OFFER_' . OFFER_SHORTCUTS[$text]);
        } else {
            sendMessage($psid,
                "❌ اختيار خاطئ\n\n📌 قم باستخدام الأزرار الموجودة بالأسفل\nإذا لم تظهر لك الأزرار أرسل الرقم المناسب 👇\n\n━━━━━━━━━━━━━━\n\n" .
                "1️⃣ لتفعيل 2G الأسبوعية\n📩 أرسل: 1\n\n" .
                "2️⃣ لتفعيل عرض 4GB بـ 70دج 🏷️\n📩 أرسل: 2\n\n" .
                "3️⃣ لإرسال دعوة 🎁\n📩 أرسل: 3\n\n" .
                "4️⃣ للمزيد من العروض 📦\n📩 أرسل: 4\n\n" .
                "5️⃣ لتفعيل هدية أوريدو اليومية 🎁\n📩 أرسل: 5\n\n" .
                "━━━━━━━━━━━━━━"
            );
        }
        return;
    }
    if ($isNew) sendWelcomeNew($psid); else sendWelcome($psid);
}

// ════════════════════════════════════════════════════════════════════════════
// Admin Broadcast (من فيسبوك)
// ════════════════════════════════════════════════════════════════════════════
function handleAdminBroadcast(string $psid, string $adText): void
{
    $users = getAllKnownUsers();
    $broadcastId = 'fb_bc_' . time();
    $count = 0;
    foreach ($users as $uid) {
        if (wasBroadcastSent($broadcastId, $uid)) continue;
        if (sendFbMessage($uid, "📢 إعلان:\n\n" . $adText)) {
            markBroadcastSent($broadcastId, $uid);
            $count++;
        }
        usleep(100000);
    }
    sendMessage($psid, "✅ تم إرسال الإعلان إلى {$count} مستخدم.");
    dbg("[BROADCAST] from={$psid} users={$count}");
}

// ════════════════════════════════════════════════════════════════════════════
// OTP — تسجيل الدخول Djezzy
// ════════════════════════════════════════════════════════════════════════════
function handleAwaitingOtp(string $psid, string $text, array $session): void
{
    $msisdn       = $session['msisdn'] ?? '';
    $phoneDisplay = '0' . substr($msisdn, 3);
    if (trim($text) === '0') {
        clearSession($psid);
        sendMessage($psid, "✅ تم إلغاء عملية التسجيل.\n\n📱 أرسل رقمك في أي وقت للبدء من جديد.");
        return;
    }
    $digits = preg_replace('/\D/', '', $text);
    if (preg_match('/^07\d{8}$/', $digits)) {
        $newMsisdn = '213' . substr($digits, 1);
        sendMessage($psid, "📲 جاري إعادة إرسال رمز التحقق إلى الرقم {$digits}...");
        sendOTPAndWait($psid, $newMsisdn, $digits);
        return;
    }
    if (!preg_match('/\b(\d{6})\b/', $text, $m)) {
        sendMessage($psid,
            "⚠️ الرجاء إدخال رمز التحقق المكوّن من 6 أرقام.\n\n📱 أو أرسل رقم هاتفك مجدداً لاستقبال رمز جديد\n🔢 الرمز أُرسل إلى: {$phoneDisplay}\n\n❌ لإلغاء العملية أرسل: 0"
        );
        return;
    }
    if (empty($msisdn)) { clearSession($psid); sendMessage($psid, "❌ حدث خطأ في الجلسة، أرسل رقمك مجدداً."); return; }
    $result = verifyOTP($msisdn, $m[1]);
    if ($result === 'wrong_otp') {
        sendMessage($psid, "❌ الرمز المُدخل خاطئ!\n\n🔄 أعد إرسال الرمز الصحيح\n📱 أو أرسل رقم هاتفك مجدداً لاستقبال رمز جديد\n\n❌ لإلغاء العملية أرسل: 0");
    } elseif ($result === false) {
        sendMessage($psid, "❌ حدث خطأ، حاول مجدداً.\n\n📱 يمكنك إرسال رقمك مجدداً لاستقبال رمز جديد\n\n❌ لإلغاء العملية أرسل: 0");
    } else {
        saveUser($psid, ['user_id' => $psid, 'msisdn' => $msisdn, 'access_token' => $result['access_token'], 'refresh_token' => $result['refresh_token'], 'operator' => 'djezzy']);
        savePhoneOwner($msisdn, $psid);
        setSession($psid, ['state' => 'menu', 'msisdn' => $msisdn, 'operator' => 'djezzy']);
        sendMessage($psid, "✅ تم تسجيل الدخول بنجاح!");
        sendMenu($psid);
        sendAlgeriaMatchGiftPromo($psid);
    }
}

// ════════════════════════════════════════════════════════════════════════════
// OTP — تفعيل العرض Djezzy (جديد)
// ════════════════════════════════════════════════════════════════════════════
function handleOfferOtp(string $psid, string $text, array $session): void
{
    $msisdn      = $session['msisdn'] ?? '';
    $packageCode = $session['pending_package'] ?? '';
    $phoneDisplay = '0' . substr($msisdn, 3);

    if (trim($text) === '0') {
        clearSession($psid);
        setSession($psid, ['state' => 'menu', 'msisdn' => $msisdn]);
        sendMessage($psid, "✅ تم إلغاء عملية تفعيل العرض.");
        sendMenu($psid);
        return;
    }
    $digits = preg_replace('/\D/', '', $text);
    if (preg_match('/^07\d{8}$/', $digits)) {
        $newMsisdn = '213' . substr($digits, 1);
        sendMessage($psid, "📲 جاري إعادة إرسال رمز التحقق إلى الرقم {$digits}...");
        sendNewOTPAndWaitForOffer($psid, $newMsisdn, $digits, $packageCode);
        return;
    }
    if (!preg_match('/\b(\d{6})\b/', $text, $m)) {
        sendMessage($psid,
            "⚠️ الرجاء إدخال رمز التحقق المكوّن من 6 أرقام.\n\n📱 أو أرسل رقم هاتفك مجدداً لاستقبال رمز جديد\n🔢 الرمز أُرسل إلى: {$phoneDisplay}\n\n❌ لإلغاء العملية أرسل: 0"
        );
        return;
    }
    if (empty($msisdn) || empty($packageCode)) {
        clearSession($psid);
        sendMessage($psid, "❌ حدث خطأ في الجلسة، أرسل رقمك مجدداً.");
        return;
    }
    $result = verifyOTPNew($msisdn, $m[1]);
    if ($result === 'wrong_otp') {
        sendMessage($psid, "❌ الرمز المُدخل خاطئ!\n\n🔄 أعد إرسال الرمز الصحيح\n📱 أو أرسل رقم هاتفك مجدداً لاستقبال رمز جديد\n\n❌ لإلغاء العملية أرسل: 0");
        return;
    }
    if ($result === false) {
        sendMessage($psid, "❌ حدث خطأ في التحقق، حاول مجدداً.\n\n📱 يمكنك إرسال رقمك مجدداً لاستقبال رمز جديد\n\n❌ لإلغاء العملية أرسل: 0");
        return;
    }
    $userForOffer = array_merge(getUser($psid) ?? [], [
        'msisdn'        => $msisdn,
        'access_token'  => $result['access_token'],
        'refresh_token' => $result['refresh_token'],
        'operator'      => 'djezzy',
    ]);
    setSession($psid, ['state' => 'menu', 'msisdn' => $msisdn, 'operator' => 'djezzy']);
    sendMessage($psid, "✅ تم التحقق بنجاح! جاري تفعيل العرض...");
    activateOfferNew($psid, $userForOffer, $packageCode);
}

// ════════════════════════════════════════════════════════════════════════════
// Phone Handler — Djezzy
// ════════════════════════════════════════════════════════════════════════════
function handleNewPhone(string $psid, string $phone): void
{
    $msisdn = '213' . substr($phone, 1);
    $owner  = getPhoneOwner($msisdn);
    if ($owner === $psid) {
        $user = getUser($psid);
        if ($user && !empty($user['access_token'])) {
            $user['msisdn'] = $msisdn;
            saveUser($psid, $user);
            $refreshed = refreshAccessToken($user['refresh_token'], $msisdn, $psid);
            if ($refreshed === 'expired') return;
            if (is_array($refreshed)) {
                saveUser($psid, array_merge($user, ['msisdn' => $msisdn, 'access_token' => $refreshed['access_token'], 'refresh_token' => $refreshed['refresh_token']]));
                setSession($psid, ['state' => 'menu', 'msisdn' => $msisdn, 'operator' => 'djezzy']);
                sendMessage($psid, "✅ تم التعرف على رقمك بنجاح!");
                sendMenu($psid);
                sendAlgeriaMatchGiftPromo($psid);
                return;
            }
        }
    } elseif ($owner !== null) {
        sendMessage($psid, "🚫 أنت لست صاحب الرقم، يجب إثبات الهوية.\n\n📲 سيتم إرسال رمز تحقق إلى هذا الرقم...");
    }
    sendOTPAndWait($psid, $msisdn, $phone);
}
function sendOTPAndWait(string $psid, string $msisdn, string $phone): void
{
    if (sendDjezzyOTP($msisdn)) {
        setSession($psid, ['state' => 'awaiting_otp', 'msisdn' => $msisdn, 'operator' => 'djezzy']);
        sendMessage($psid,
            "✅ تم إرسال رمز التحقق إلى الرقم {$phone}.\n\n🔢 الرجاء إدخال الرمز المكوّن من 6 أرقام:\n\n📱 أو أرسل رقمك مجدداً لاستقبال رمز جديد\n\n❌ لإلغاء العملية أرسل: 0"
        );
    } else {
        sendMessage($psid, "سيرفر جازي غير متاح حاليا نعمل على اصلاحه 🧑‍🔧 يمكنك التسجيل عبر التطبيق الخاص بنا رابط تحميله https://tasjil-app.lovestoblog.com\nاو من\nhttps://t.me/tasjilbott/130");
    }
}
function sendNewOTPAndWaitForOffer(string $psid, string $msisdn, string $phone, string $packageCode): void
{
    if (sendDjezzyOTPNew($msisdn)) {
        setSession($psid, ['state' => 'awaiting_offer_otp', 'msisdn' => $msisdn, 'pending_package' => $packageCode, 'operator' => 'djezzy']);
        $offerInfo  = OFFERS[$packageCode] ?? null;
        $offerLabel = $offerInfo ? $offerInfo['name'] : $packageCode;
        sendMessage($psid,
            "✅ تم إرسال رمز التحقق إلى الرقم {$phone}.\n\n" .
            "📌 نقوم الآن بتجربة طريقة تفعيل أخرى للعرض.\n\n" .
            "🔢 الرجاء إدخال الرمز المكوّن من 6 أرقام لتفعيل العرض:\n" .
            "📦 العرض: {$offerLabel}\n\n" .
            "📱 أو أرسل رقمك مجدداً لاستقبال رمز جديد\n\n" .
            "❌ لإلغاء العملية أرسل: 0"
        );
    } else {
        sendMessage($psid, "سيرفر جازي غير متاح حاليا نعمل على اصلاحه 🧑‍🔧 يمكنك التسجيل عبر التطبيق الخاص بنا رابط تحميله https://tasjil-app.lovestoblog.com\nاو من\nhttps://t.me/tasjilbott/130");
    }
}

// ════════════════════════════════════════════════════════════════════════════
// ─── OOREDOO INTEGRATION ──────────────────────────────────────────────────
// ════════════════════════════════════════════════════════════════════════════

/**
 * handleOoredooNewPhone — معالجة رقم يبدأ بـ 05
 */
function handleOoredooNewPhone(string $psid, string $phone): void
{
    $msisdn = '213' . substr($phone, 1); // 213 + 5xxxxxxxx
    
    // التحقق من وجود المستخدم مسبقاً
    $owner = getPhoneOwner($msisdn);
    if ($owner === $psid) {
        $user = getUser($psid);
        if ($user && !empty($user['access_token']) && ($user['operator'] ?? '') === 'ooredoo') {
            // جلسة موجودة — نتحقق من صلاحيتها
            $status = ooredooCheckUserStatus($msisdn, $user['access_token']);
            if ($status !== null) {
                setSession($psid, ['state' => 'menu', 'msisdn' => $msisdn, 'operator' => 'ooredoo']);
                sendMessage($psid, "✅ تم التعرف على رقمك بنجاح!");
                sendOoredooMenu($psid, $msisdn, $user['access_token']);
                return;
            }
        }
    }
    
    // بدء عملية تسجيل Ooredoo
    sendOoredooOTPAndWait($psid, $msisdn, $phone);
}

/**
 * sendOoredooOTPAndWait — إرسال OTP وإعداد الجلسة
 */
function sendOoredooOTPAndWait(string $psid, string $msisdn, string $phone): void
{
    $result = ooredooSendOTP($msisdn);
    
    if ($result === true) {
        setSession($psid, ['state' => 'awaiting_ooredoo_otp', 'msisdn' => $msisdn, 'operator' => 'ooredoo']);
        sendMessage($psid,
            "📱 <b>Ooredoo</b>\n\n" .
            "✅ تم إرسال رمز التحقق إلى الرقم {$phone}.\n\n" .
            "🔢 الرجاء إدخال الرمز المكوّن من 6 أرقام:\n\n" .
            "❌ لإلغاء العملية أرسل: 0"
        );
    } else {
        sendMessage($psid, "❌ تعذر إرسال رمز التحقق Ooredoo. يرجى المحاولة لاحقاً.");
    }
}

/**
 * handleOoredooAwaitingOtp — معالجة إدخال OTP من المستخدم
 */
function handleOoredooAwaitingOtp(string $psid, string $text, array $session): void
{
    $msisdn = $session['msisdn'] ?? '';
    $phoneDisplay = '0' . substr($msisdn, 3);
    
    if (trim($text) === '0') {
        clearSession($psid);
        sendMessage($psid, "✅ تم إلغاء عملية التسجيل Ooredoo.");
        return;
    }
    
    // السماح بإعادة إرسال OTP برقم جديد
    $digits = preg_replace('/\D/', '', $text);
    if (preg_match('/^05\d{8}$/', $digits)) {
        $newMsisdn = '213' . substr($digits, 1);
        sendMessage($psid, "📲 جاري إعادة إرسال رمز التحقق إلى الرقم {$digits}...");
        sendOoredooOTPAndWait($psid, $newMsisdn, $digits);
        return;
    }
    
    if (!preg_match('/\b(\d{6})\b/', $text, $m)) {
        sendMessage($psid,
            "⚠️ الرجاء إدخال رمز التحقق المكوّن من 6 أرقام.\n\n" .
            "📱 أو أرسل رقم هاتفك مجدداً لاستقبال رمز جديد\n" .
            "🔢 الرمز أُرسل إلى: {$phoneDisplay}\n\n" .
            "❌ لإلغاء العملية أرسل: 0"
        );
        return;
    }
    
    if (empty($msisdn)) {
        clearSession($psid);
        sendMessage($psid, "❌ حدث خطأ في الجلسة، أرسل رقمك مجدداً.");
        return;
    }
    
    $result = ooredooVerifyOTP($msisdn, $m[1]);
    
    if ($result === 'wrong_otp') {
        sendMessage($psid, "❌ الرمز المُدخل خاطئ!\n\n🔄 أعد إرسال الرمز الصحيح\n📱 أو أرسل رقم هاتفك مجدداً لاستقبال رمز جديد\n\n❌ لإلغاء العملية أرسل: 0");
        return;
    }
    
    if ($result === false) {
        sendMessage($psid, "❌ حدث خطأ، حاول مجدداً.\n\n📱 يمكنك إرسال رقمك مجدداً لاستقبال رمز جديد\n\n❌ لإلغاء العملية أرسل: 0");
        return;
    }
    
    // نجاح التوثيق — حفظ البيانات
    $tokenData = $result;
    saveUser($psid, [
        'user_id'       => $psid,
        'msisdn'        => $msisdn,
        'access_token'  => $tokenData['access_token'],
        'refresh_token' => $tokenData['refresh_token'] ?? '',
        'operator'      => 'ooredoo',
    ]);
    savePhoneOwner($msisdn, $psid);
    setSession($psid, ['state' => 'menu', 'msisdn' => $msisdn, 'operator' => 'ooredoo']);
    
    sendMessage($psid, "✅ تم تسجيل الدخول إلى Ooredoo بنجاح!");
    
    // عرض معلومات الشريحة وحالة الهدية
    sendOoredooMenu($psid, $msisdn, $tokenData['access_token']);
}

/**
 * sendOoredooMenu — عرض قائمة Ooredoo مع حالة الهدية اليومية
 */
function sendOoredooMenu(string $psid, string $msisdn, string $accessToken): void
{
    // 1. جلب معلومات المستخدم
    $userInfo = ooredooGetUserInfo($msisdn, $accessToken);
    $planType = $userInfo['planType'] ?? 'غير معروف';
    
    // 2. جلب حالة الهدية
    $giftStatus = ooredooGetGiftStatus($msisdn, $accessToken);
    $giftMessage = ooredooFormatGiftStatus($giftStatus);
    
    $message = "📱 <b>Ooredoo</b>\n\n" .
               "━━━━━━━━━━━━━━━━━━━━\n" .
               "📌 نوع الشريحة: <b>{$planType}</b>\n" .
               "━━━━━━━━━━━━━━━━━━━━\n\n" .
               "🎁 <b>الهدية اليومية</b>\n" .
               "{$giftMessage}\n\n" .
               "━━━━━━━━━━━━━━━━━━━━\n\n" .
               "📌 اختر العرض المناسب:\n" .
               "1️⃣ لتفعيل هدية Ooredoo اليومية 🎁\n" .
               "📩 أرسل: 1\n\n" .
               "🔙 للرجوع للقائمة الرئيسية أرسل: 0";
    
    // إضافة زر سريع إذا كانت الهدية جاهزة
    $quickReplies = [];
    if ($giftStatus['ready'] ?? false) {
        $quickReplies[] = ['content_type' => 'text', 'title' => '🎁 تفعيل هدية Ooredoo', 'payload' => 'ACTIVATE_OOREDOO_GIFT'];
    }
    $quickReplies[] = ['content_type' => 'text', 'title' => '🔙 رجوع للقائمة', 'payload' => 'BACK_MENU'];
    
    fbApiCall(json_encode([
        'recipient'      => ['id' => $psid],
        'messaging_type' => 'RESPONSE',
        'message'        => [
            'text' => $message,
            'quick_replies' => $quickReplies,
        ],
    ], JSON_UNESCAPED_UNICODE));
    
    // حفظ حالة القائمة
    setSession($psid, array_merge(getSession($psid), ['state' => 'menu', 'ooredoo_gift_status' => $giftStatus]));
}

/**
 * ooredooFormatGiftStatus — تنسيق حالة الهدية إلى نص
 */
function ooredooFormatGiftStatus(?array $status): string
{
    if ($status === null) {
        return "⚠️ تعذر جلب حالة الهدية";
    }
    
    if ($status['ready'] ?? false) {
        return "✅ الهدية جاهزة للتفعيل! 🎉\nأرسل 1 أو اضغط على الزر للتفعيل";
    }
    
    $lastPlayed = $status['lastPlayedTime'] ?? null;
    if ($lastPlayed) {
        try {
            $lastTs = strtotime($lastPlayed);
            $nextAvailable = $lastTs + 86400; // 24 ساعة
            $remaining = $nextAvailable - time();
            if ($remaining > 0) {
                $hours = floor($remaining / 3600);
                $minutes = floor(($remaining % 3600) / 60);
                return "⏳ تم اللعب سابقاً.\nالوقت المتبقي: <b>{$hours} ساعة و {$minutes} دقيقة</b>";
            }
        } catch (Throwable $e) {
            // تجاهل
        }
        return "⏳ الهدية غير متاحة حالياً.\nتأكد من مرور 24 ساعة على آخر لعب.";
    }
    
    return "⚠️ لم يتم تحديد حالة الهدية";
}

// ════════════════════════════════════════════════════════════════════════════
// OOREDOO — API Calls عبر البروكسيات
// ════════════════════════════════════════════════════════════════════════════

/**
 * ooredooSendOTP — إرسال رمز التحقق Ooredoo
 * الخطوات:
 * 1. GET /users/status
 * 2. HEAD /userInfo
 * 3. POST /checkpoint/token → استخراج nonce, chronos
 * 4. POST /openid-connect/token (بدون OTP) → 403 يُطلق SMS
 */
function ooredooSendOTP(string $msisdn): bool
{
    $headers = ooredooBuildHeaders($msisdn, 'status');
    
    // 1. GET /users/status
    $url = OOREDOO_BFF_BASE . '/users/status';
    $result = curlWithAllProxies($url . '?msisdn=' . $msisdn, 'GET', '', $headers, 'OOREDOO_STATUS', 10, '/tmp/ooredoo.log', true);
    if ($result === null || $result['http_code'] !== 200) {
        dbg("[OOREDOO] Status failed: " . ($result['http_code'] ?? 'null'));
        return false;
    }
    
    // 2. HEAD /userInfo
    $url = OOREDOO_BFF_BASE . '/userInfo';
    $headers2 = ooredooBuildHeaders($msisdn, 'userinfo');
    $result2 = doCurlRequest($url . '?msisdn=' . $msisdn, 'GET', '', $headers2, 10);
    if ($result2 === null || $result2['http_code'] !== 204) {
        dbg("[OOREDOO] UserInfo failed: " . ($result2['http_code'] ?? 'null'));
        return false;
    }
    
    // 3. POST /checkpoint/token → استخراج nonce, chronos
    $checkpointResult = ooredooCheckpoint($msisdn, 1);
    if ($checkpointResult === null) {
        dbg("[OOREDOO] Checkpoint 1 failed");
        return false;
    }
    $nonce1 = $checkpointResult['nonce'];
    $chronos1 = $checkpointResult['chronos'];
    
    // 4. POST /openid-connect/token (بدون OTP) → 403 يُطلق SMS
    $tokenResult = ooredooTokenRequest($msisdn, $nonce1, $chronos1, '', 1);
    if ($tokenResult === null) {
        dbg("[OOREDOO] Token request (no OTP) failed");
        return false;
    }
    
    // يجب أن يعيد 403
    if ($tokenResult['http_code'] === 403) {
        dbg("[OOREDOO] SMS sent successfully");
        return true;
    }
    
    dbg("[OOREDOO] Token request unexpected: " . $tokenResult['http_code']);
    return false;
}

/**
 * ooredooVerifyOTP — التحقق من رمز OTP
 * الخطوات:
 * 1. POST /checkpoint/token → استخراج nonce, chronos (جديد)
 * 2. POST /openid-connect/token (مع OTP) → 200 → access_token
 */
function ooredooVerifyOTP(string $msisdn, string $otp): mixed
{
    // 1. POST /checkpoint/token (ثانٍ)
    $checkpointResult = ooredooCheckpoint($msisdn, 2);
    if ($checkpointResult === null) {
        dbg("[OOREDOO] Checkpoint 2 failed");
        return false;
    }
    $nonce2 = $checkpointResult['nonce'];
    $chronos2 = $checkpointResult['chronos'];
    
    // 2. POST /openid-connect/token (مع OTP)
    $tokenResult = ooredooTokenRequest($msisdn, $nonce2, $chronos2, $otp, 2);
    if ($tokenResult === null) {
        dbg("[OOREDOO] Token request with OTP failed");
        return false;
    }
    
    if ($tokenResult['http_code'] === 403) {
        return 'wrong_otp';
    }
    
    if ($tokenResult['http_code'] !== 200) {
        dbg("[OOREDOO] Token request with OTP http=" . $tokenResult['http_code']);
        return false;
    }
    
    $json = $tokenResult['json'];
    if (!is_array($json) || !isset($json['access_token'])) {
        dbg("[OOREDOO] Token response missing access_token");
        return false;
    }
    
    return [
        'access_token'  => $json['access_token'],
        'refresh_token' => $json['refresh_token'] ?? '',
        'expires_in'    => $json['expires_in'] ?? 0,
    ];
}

/**
 * ooredooCheckpoint — إرسال طلب checkpoint/token
 * @param int $type 1 = أول, 2 = ثاني
 */
function ooredooCheckpoint(string $msisdn, int $type): ?array
{
    $headers = ooredooBuildHeaders($msisdn, 'checkpoint_' . $type);
    $url = OOREDOO_BFF_BASE . '/checkpoint/token';
    
    $result = curlWithAllProxies($url, 'POST', '{}', $headers, 'OOREDOO_CP_' . $type, 15, '/tmp/ooredoo.log', true);
    
    if ($result === null) {
        dbg("[OOREDOO] Checkpoint {$type} failed");
        return null;
    }
    
    // استخراج nonce و chronos من الـ headers
    $nonce = '';
    $chronos = '';
    foreach ($headers as $h) {
        if (stripos($h, 'X-Nonce-Id:') !== false) {
            $nonce = trim(substr($h, strlen('X-Nonce-Id:')));
        }
        if (stripos($h, 'X-Chronos-Id:') !== false) {
            $chronos = trim(substr($h, strlen('X-Chronos-Id:')));
        }
    }
    
    // إذا لم نجد في الـ headers المرسلة، نبحث في response headers (لكن cURL لا يعيدهم بسهولة)
    // بدلاً من ذلك، نستخدم طريقة بديلة: نستقبلهم من response body أو نعيد المحاولة
    if (empty($nonce) || empty($chronos)) {
        dbg("[OOREDOO] Checkpoint {$type} missing nonce/chronos in headers");
        return null;
    }
    
    return ['nonce' => $nonce, 'chronos' => $chronos];
}

/**
 * ooredooTokenRequest — طلب token من Ooredoo
 */
function ooredooTokenRequest(string $msisdn, string $nonce, string $chronos, string $otp, int $type): ?array
{
    $headers = ooredooBuildHeaders($msisdn, 'token_' . $type);
    
    // إضافة nonce و chronos
    $headers[] = "X-Nonce-Id: {$nonce}";
    $headers[] = "X-Chronos-Id: {$chronos}";
    
    $payload = [
        'client_id' => OOREDOO_CLIENT_ID,
        'grant_type' => 'password',
        'username' => $msisdn,
    ];
    if (!empty($otp)) {
        $payload['otp'] = $otp;
    }
    
    $url = OOREDOO_AUTH_URL;
    $result = curlWithAllProxies($url, 'POST', http_build_query($payload), $headers, 'OOREDOO_TOKEN_' . $type, 15, '/tmp/ooredoo.log', true);
    
    if ($result === null) {
        dbg("[OOREDOO] Token request failed");
        return null;
    }
    
    return ['http_code' => $result['http_code'], 'json' => $result['json']];
}

/**
 * ooredooGetUserInfo — جلب معلومات المستخدم
 */
function ooredooGetUserInfo(string $msisdn, string $accessToken): ?array
{
    $headers = ooredooBuildHeaders($msisdn, 'userinfo');
    $headers[] = "Authorization: Bearer {$accessToken}";
    
    $url = OOREDOO_BFF_BASE . '/users/validateUser';
    $result = curlWithAllProxies($url . '?msisdn=' . $msisdn, 'GET', '', $headers, 'OOREDOO_USER_INFO', 10, '/tmp/ooredoo.log', true);
    
    if ($result === null || $result['http_code'] !== 200) {
        dbg("[OOREDOO] GetUserInfo failed: " . ($result['http_code'] ?? 'null'));
        return null;
    }
    
    return $result['json'];
}

/**
 * ooredooGetGiftStatus — جلب حالة الهدية اليومية
 */
function ooredooGetGiftStatus(string $msisdn, string $accessToken): ?array
{
    $headers = ooredooBuildHeaders($msisdn, 'gamification');
    $headers[] = "Authorization: Bearer {$accessToken}";
    
    $url = OOREDOO_BFF_BASE . '/gamification/status';
    $result = curlWithAllProxies($url, 'GET', '', $headers, 'OOREDOO_GIFT_STATUS', 10, '/tmp/ooredoo.log', true);
    
    if ($result === null || $result['http_code'] !== 200) {
        dbg("[OOREDOO] GetGiftStatus failed: " . ($result['http_code'] ?? 'null'));
        return null;
    }
    
    $data = $result['json'];
    if (!is_array($data)) return null;
    
    $lastPlayed = $data['lastPlayedTime'] ?? null;
    $played = (bool)($data['played'] ?? false);
    $gameUIEnable = (bool)($data['gameUIEnable'] ?? true);
    
    // تحديد إذا كانت الهدية جاهزة
    $ready = false;
    if (!$played && $gameUIEnable) {
        $ready = true;
    } elseif ($lastPlayed) {
        try {
            $lastTs = strtotime($lastPlayed);
            if ($lastTs === false) {
                $ready = false;
            } else {
                $ready = (time() - $lastTs) >= 86400; // 24 ساعة
            }
        } catch (Throwable $e) {
            $ready = false;
        }
    }
    
    return [
        'lastPlayedTime' => $lastPlayed,
        'played' => $played,
        'gameUIEnable' => $gameUIEnable,
        'ready' => $ready,
    ];
}

/**
 * ooredooActivateGift — تفعيل الهدية اليومية Ooredoo
 * الخطوات:
 * 1. POST /checkpoint/token → nonce, chronos
 * 2. GET /gamification/play مع nonce و chronos
 */
function ooredooActivateGift(string $psid, string $msisdn, string $accessToken): array
{
    $rl = checkRateLimit($psid);
    if ($rl !== null) {
        return ['status' => 'rate_limited', 'message' => rateLimitMessage($rl)];
    }
    
    setPending($psid, 'تفعيل هدية Ooredoo 🎁');
    sendMessage($psid, "🔄 جاري تفعيل هدية Ooredoo اليومية...");
    
    // 1. POST /checkpoint/token
    $checkpointResult = ooredooCheckpoint($msisdn, 2); // نستخدم checkpoint 2 مع المسار الصحيح
    if ($checkpointResult === null) {
        clearPending($psid);
        return ['status' => 'error', 'message' => '❌ تعذر الحصول على بيانات التحقق.'];
    }
    
    $nonce = $checkpointResult['nonce'];
    $chronos = $checkpointResult['chronos'];
    
    // 2. GET /gamification/play
    $headers = ooredooBuildHeaders($msisdn, 'play');
    $headers[] = "Authorization: Bearer {$accessToken}";
    $headers[] = "X-Nonce-Id: {$nonce}";
    $headers[] = "X-Chronos-Id: {$chronos}";
    $headers[] = "x-path: /api/ooredoo-bff/gamification/play";
    $headers[] = "x-method: GET";
    
    $url = OOREDOO_BFF_BASE . '/gamification/play';
    $result = curlWithAllProxies($url, 'GET', '', $headers, 'OOREDOO_PLAY', 15, '/tmp/ooredoo.log', true);
    
    clearPending($psid);
    
    if ($result === null) {
        return ['status' => 'error', 'message' => '❌ فشل الاتصال بالخادم.'];
    }
    
    $httpCode = $result['http_code'];
    $json = $result['json'];
    
    // 500 = سبق اللعب → نعيد التحقق
    if ($httpCode === 500) {
        $giftStatus = ooredooGetGiftStatus($msisdn, $accessToken);
        if ($giftStatus && $giftStatus['ready']) {
            // جاهزة لكن 500 — نحاول مرة أخرى
            return ['status' => 'retry', 'message' => '🔄 جاري إعادة المحاولة...'];
        }
        $giftMsg = ooredooFormatGiftStatus($giftStatus);
        return ['status' => 'already_played', 'message' => "⚠️ لقد لعبت الهدية مسبقاً.\n\n{$giftMsg}"];
    }
    
    if ($httpCode === 200 && is_array($json)) {
        recordFinalResult($psid);
        $giftName = $json['giftName'] ?? 'هدية';
        $validityHour = $json['validityHour'] ?? 0;
        $playedTime = $json['playedTime'] ?? date('Y-m-d H:i:s');
        
        $message = "🎉 <b>تم تفعيل هدية Ooredoo بنجاح!</b>\n\n" .
                   "🎁 الهدية: <b>{$giftName}</b>\n" .
                   "⏰ وقت اللعب: {$playedTime}\n" .
                   "⌛ مدة الصلاحية: {$validityHour} ساعة\n\n" .
                   "⚡ قناة التلغرام: https://t.me/tasjilbott";
        
        return ['status' => 'success', 'message' => $message];
    }
    
    dbg("[OOREDOO] Play failed: http={$httpCode} body=" . substr($result['body'] ?? '', 0, 300));
    return ['status' => 'error', 'message' => "❌ حدث خطأ أثناء التفعيل (HTTP {$httpCode}). يرجى المحاولة مجدداً."];
}

/**
 * ooredooBuildHeaders — بناء headers لطلبات Ooredoo
 */
function ooredooBuildHeaders(string $msisdn, string $type): array
{
    $baseHeaders = [
        "User-Agent: Dart/3.11 (dart:io)",
        "Accept-Encoding: gzip",
        "x-msisdn: {$msisdn}",
        "x-platform-origin: " . OOREDOO_X_PLATFORM_ORIGIN,
        "platform: " . OOREDOO_PLATFORM,
        "x-version: " . OOREDOO_X_VERSION,
        "x-signature: " . OOREDOO_X_SIGNATURE,
        "x-platform-data-signature: " . OOREDOO_PLATFORM_DATA_SIG,
        "x-instance-id: " . OOREDOO_INSTANCE_ID,
        "x-device-fingerprint: " . OOREDOO_DEVICE_ID,
    ];
    
    switch ($type) {
        case 'status':
            $baseHeaders[] = "x-correlation-id: " . OOREDOO_STATUS_CORRELATION;
            $baseHeaders[] = "x-timestamp: " . OOREDOO_STATUS_TIMESTAMP;
            break;
        case 'userinfo':
            $baseHeaders[] = "x-correlation-id: " . OOREDOO_USERINFO_CORRELATION;
            $baseHeaders[] = "x-timestamp: " . OOREDOO_USERINFO_TIMESTAMP;
            break;
        case 'checkpoint_1':
            $baseHeaders[] = "x-correlation-id: " . OOREDOO_CP1_CORRELATION;
            $baseHeaders[] = "x-timestamp: " . OOREDOO_CP1_TIMESTAMP;
            $baseHeaders[] = "x-native-integrity-token: " . OOREDOO_CP1_INTEGRITY;
            $baseHeaders[] = "x-path: /api/auth/realms/myooredoo/protocol/openid-connect/token";
            $baseHeaders[] = "content-type: application/json; charset=utf-8";
            $baseHeaders[] = "x-method: POST";
            break;
        case 'checkpoint_2':
            $baseHeaders[] = "x-correlation-id: " . OOREDOO_CP2_CORRELATION;
            $baseHeaders[] = "x-timestamp: " . OOREDOO_CP2_TIMESTAMP;
            $baseHeaders[] = "x-native-integrity-token: " . OOREDOO_CP2_INTEGRITY;
            $baseHeaders[] = "x-path: /api/auth/realms/myooredoo/protocol/openid-connect/token";
            $baseHeaders[] = "content-type: application/json; charset=utf-8";
            $baseHeaders[] = "x-method: POST";
            break;
        case 'token_1':
            $baseHeaders[] = "x-timestamp: " . OOREDOO_TOKEN1_TIMESTAMP;
            $baseHeaders[] = "x-device-fingerprint: " . OOREDOO_TOKEN1_FINGERPRINT;
            $baseHeaders[] = "x-platform-info: " . OOREDOO_PLATFORM_DATA_SIG;
            $baseHeaders[] = "x-force-update: true";
            break;
        case 'token_2':
            $baseHeaders[] = "x-timestamp: " . OOREDOO_TOKEN2_TIMESTAMP;
            $baseHeaders[] = "x-device-fingerprint: " . OOREDOO_TOKEN2_FINGERPRINT;
            $baseHeaders[] = "x-platform-info: " . OOREDOO_PLATFORM_DATA_SIG;
            $baseHeaders[] = "x-force-update: true";
            break;
        case 'gamification':
        case 'play':
            $baseHeaders[] = "x-correlation-id: " . OOREDOO_CP1_CORRELATION; // نستخدم نفس الـ correlation
            $baseHeaders[] = "x-timestamp: " . OOREDOO_CP1_TIMESTAMP;
            break;
        default:
            $baseHeaders[] = "x-correlation-id: " . OOREDOO_STATUS_CORRELATION;
            $baseHeaders[] = "x-timestamp: " . OOREDOO_STATUS_TIMESTAMP;
    }
    
    return $baseHeaders;
}

// ════════════════════════════════════════════════════════════════════════════
// OOREDOO — Postback Handlers
// ════════════════════════════════════════════════════════════════════════════

function handleOoredooGiftOtp(string $psid, string $text, array $session): void
{
    if (trim($text) === '0') {
        clearSession($psid);
        sendMessage($psid, "✅ تم إلغاء عملية تفعيل هدية Ooredoo.");
        sendMenu($psid);
        return;
    }
    
    // التحقق من الـ OTP
    $msisdn = $session['msisdn'] ?? '';
    if (empty($msisdn)) {
        clearSession($psid);
        sendMessage($psid, "❌ حدث خطأ في الجلسة.");
        return;
    }
    
    if (!preg_match('/\b(\d{6})\b/', $text, $m)) {
        sendMessage($psid,
            "⚠️ الرجاء إدخال رمز التحقق المكوّن من 6 أرقام.\n\n" .
            "❌ لإلغاء العملية أرسل: 0"
        );
        return;
    }
    
    $otp = $m[1];
    $result = ooredooVerifyOTP($msisdn, $otp);
    
    if ($result === 'wrong_otp') {
        sendMessage($psid, "❌ الرمز المُدخل خاطئ! حاول مجدداً.");
        return;
    }
    
    if ($result === false) {
        sendMessage($psid, "❌ حدث خطأ في التحقق، حاول مجدداً.");
        return;
    }
    
    // تحديث التوكن
    $user = getUser($psid);
    if ($user) {
        $user['access_token'] = $result['access_token'];
        $user['refresh_token'] = $result['refresh_token'] ?? '';
        saveUser($psid, $user);
    }
    
    setSession($psid, ['state' => 'menu', 'msisdn' => $msisdn, 'operator' => 'ooredoo']);
    sendMessage($psid, "✅ تم التحقق بنجاح! جاري تفعيل الهدية...");
    
    // تفعيل الهدية
    $activateResult = ooredooActivateGift($psid, $msisdn, $result['access_token']);
    
    if ($activateResult['status'] === 'success') {
        sendMessage($psid, $activateResult['message']);
        sendMessage($psid, "\n\n🥰 اذا كنت تريد دعمنا حتى نطور الخدمة ونستمر 🥰\n\n🔴 ادخل للموقع 👇\n\nhttps://timebucks.com/?refID=227870531\n\n✅ وسجل بحساب جوجل فقط 🥰\n\n🥹 ولا تنسَ متابعة حساب المطور 👇\nhttps://www.facebook.com/profile.php?id=100052854003446\n\nوشكراً ❤️");
        clearSession($psid);
    } elseif ($activateResult['status'] === 'retry') {
        // إعادة محاولة التفعيل
        $retryResult = ooredooActivateGift($psid, $msisdn, $result['access_token']);
        if ($retryResult['status'] === 'success') {
            sendMessage($psid, $retryResult['message']);
            sendMessage($psid, "\n\n🥰 اذا كنت تريد دعمنا حتى نطور الخدمة ونستمر 🥰\n\n🔴 ادخل للموقع 👇\n\nhttps://timebucks.com/?refID=227870531\n\n✅ وسجل بحساب جوجل فقط 🥰\n\n🥹 ولا تنسَ متابعة حساب المطور 👇\nhttps://www.facebook.com/profile.php?id=100052854003446\n\nوشكراً ❤️");
            clearSession($psid);
        } else {
            sendMessage($psid, "❌ تعذر تفعيل الهدية بعد المحاولة الثانية.\n\n⚡ قناة التلغرام: https://t.me/tasjilbott");
            clearSession($psid);
        }
    } else {
        sendMessage($psid, $activateResult['message']);
        clearSession($psid);
    }
}

// ════════════════════════════════════════════════════════════════════════════
// Postback Handler — إضافة Ooredoo
// ════════════════════════════════════════════════════════════════════════════
function handlePostback(string $psid, string $payload): void
{
    if (str_starts_with($payload, 'ACTIVATE_OFFER_')) {
        $packageCode = substr($payload, strlen('ACTIVATE_OFFER_'));
        $sess = getSession($psid); $user = getUser($psid);
        if (!$user || empty($user['access_token'])) { sendMessage($psid, "⚠️ يجب تسجيل الدخول أولاً، أرسل رقم هاتفك."); return; }
        if (!empty($sess['msisdn'])) $user['msisdn'] = $sess['msisdn'];
        setSession($psid, array_merge($sess, ['state' => 'menu']));
        activateOffer($psid, $user, $packageCode);
        return;
    }
    
    // ── Ooredoo Gift ──────────────────────────────────────────────────────
    if ($payload === 'ACTIVATE_OOREDOO_GIFT') {
        $sess = getSession($psid);
        $user = getUser($psid);
        
        if (!$user || empty($user['access_token']) || ($user['operator'] ?? '') !== 'ooredoo') {
            sendMessage($psid, "⚠️ يجب تسجيل الدخول إلى Ooredoo أولاً.\n📱 أرسل رقمك الذي يبدأ بـ 05");
            return;
        }
        
        $msisdn = $sess['msisdn'] ?? $user['msisdn'] ?? '';
        if (empty($msisdn)) {
            sendMessage($psid, "⚠️ لم يتم العثور على رقم هاتفك.");
            return;
        }
        
        // التحقق من حالة الهدية
        $giftStatus = ooredooGetGiftStatus($msisdn, $user['access_token']);
        if ($giftStatus === null) {
            sendMessage($psid, "❌ تعذر جلب حالة الهدية. حاول مجدداً.");
            return;
        }
        
        if (!$giftStatus['ready']) {
            $msg = ooredooFormatGiftStatus($giftStatus);
            sendMessage($psid, "⏳ الهدية غير جاهزة.\n\n{$msg}");
            return;
        }
        
        // تفعيل الهدية
        $result = ooredooActivateGift($psid, $msisdn, $user['access_token']);
        
        if ($result['status'] === 'success') {
            sendMessage($psid, $result['message']);
            sendMessage($psid, "\n\n🥰 اذا كنت تريد دعمنا حتى نطور الخدمة ونستمر 🥰\n\n🔴 ادخل للموقع 👇\n\nhttps://timebucks.com/?refID=227870531\n\n✅ وسجل بحساب جوجل فقط 🥰\n\n🥹 ولا تنسَ متابعة حساب المطور 👇\nhttps://www.facebook.com/profile.php?id=100052854003446\n\nوشكراً ❤️");
            clearSession($psid);
        } elseif ($result['status'] === 'retry') {
            // إعادة المحاولة
            $retryResult = ooredooActivateGift($psid, $msisdn, $user['access_token']);
            if ($retryResult['status'] === 'success') {
                sendMessage($psid, $retryResult['message']);
                sendMessage($psid, "\n\n🥰 اذا كنت تريد دعمنا حتى نطور الخدمة ونستمر 🥰\n\n🔴 ادخل للموقع 👇\n\nhttps://timebucks.com/?refID=227870531\n\n✅ وسجل بحساب جوجل فقط 🥰\n\n🥹 ولا تنسَ متابعة حساب المطور 👇\nhttps://www.facebook.com/profile.php?id=100052854003446\n\nوشكراً ❤️");
                clearSession($psid);
            } else {
                sendMessage($psid, "❌ تعذر تفعيل الهدية بعد المحاولة الثانية.\n\n⚡ قناة التلغرام: https://t.me/tasjilbott");
                clearSession($psid);
            }
        } elseif ($result['status'] === 'already_played') {
            sendMessage($psid, $result['message']);
        } else {
            sendMessage($psid, $result['message']);
        }
        return;
    }
    
    // ── Ooredoo Menu ──────────────────────────────────────────────────────
    if ($payload === 'MENU_OOREDOO_GIFT') {
        $sess = getSession($psid);
        $user = getUser($psid);
        
        if (!$user || empty($user['access_token']) || ($user['operator'] ?? '') !== 'ooredoo') {
            sendMessage($psid, "⚠️ يجب تسجيل الدخول إلى Ooredoo أولاً.\n📱 أرسل رقمك الذي يبدأ بـ 05");
            return;
        }
        
        $msisdn = $sess['msisdn'] ?? $user['msisdn'] ?? '';
        if (empty($msisdn)) {
            sendMessage($psid, "⚠️ لم يتم العثور على رقم هاتفك.");
            return;
        }
        
        sendOoredooMenu($psid, $msisdn, $user['access_token']);
        return;
    }
    
    // ── Existing handlers ──────────────────────────────────────────────────
    switch ($payload) {
        case 'GET_STARTED':
            sendWelcomeNew($psid);
            break;
        case 'ACTIVATE_ALGERIA_MATCH':
            $cfg  = getMatchGiftConfig();
            $sess = getSession($psid);
            $user = getUser($psid);
            if (!$cfg['enabled']) {
                sendMessage($psid, "⏰ هدية المباراة غير متاحة حالياً.\n\n⚡ قناة التلغرام: https://t.me/tasjilbott");
                return;
            }
            if (!$user || empty($user['access_token'])) {
                sendMessage($psid, "⚠️ يجب تسجيل الدخول أولاً، أرسل رقم هاتفك.");
                return;
            }
            if (!empty($sess['msisdn'])) $user['msisdn'] = $sess['msisdn'];
            activateAlgeriaMatchGift($psid, $user);
            break;
        case 'MENU_2G':
            $sess = getSession($psid); $user = getUser($psid);
            if (!$user || empty($user['access_token'])) { sendMessage($psid, "⚠️ يجب تسجيل الدخول أولاً، أرسل رقم هاتفك."); return; }
            if (!empty($sess['msisdn'])) $user['msisdn'] = $sess['msisdn'];
            setSession($psid, array_merge($sess, ['state' => 'menu']));
            activate2G($psid, $user);
            break;
        case 'MENU_70DZ':
            $sess = getSession($psid); $user = getUser($psid);
            if (!$user || empty($user['access_token'])) { sendMessage($psid, "⚠️ يجب تسجيل الدخول أولاً، أرسل رقم هاتفك."); return; }
            if (!empty($sess['msisdn'])) $user['msisdn'] = $sess['msisdn'];
            setSession($psid, array_merge($sess, ['state' => 'menu']));
            activate70DZ($psid, $user);
            break;
        case 'MENU_INVITE':
            $sess = getSession($psid); $user = getUser($psid);
            if (!$user || empty($user['access_token'])) { sendMessage($psid, "⚠️ يجب تسجيل الدخول أولاً، أرسل رقم هاتفك."); return; }
            if (!empty($sess['msisdn'])) $user['msisdn'] = $sess['msisdn'];
            handleInviteStart($psid, $user);
            break;
        case 'MENU_MORE_OFFERS':
            sendMoreOffers($psid);
            break;
        case 'BACK_MENU':
            sendMenu($psid);
            break;
        default:
            sendWelcome($psid);
    }
}

// ════════════════════════════════════════════════════════════════════════════
// بقية الدوال (Djezzy — نفس الكود السابق مع تعديلات بسيطة)
// ════════════════════════════════════════════════════════════════════════════

// ... (يُكمل باقي الدوال الخاصة بـ Djezzy كما هي مع إضافة operator في saveUser)
// ... (activateOffer, activate70DZ, activate2G, MGM, etc.)

// ════════════════════════════════════════════════════════════════════════════
// ملاحظة: باقي الدوال (Djezzy) لم تتغير — تم فقط إضافة operator في saveUser
// ════════════════════════════════════════════════════════════════════════════

?>
