<?php
// ════════════════════════════════════════════════════════════════════════════
// OOREDOO BOT — Facebook Messenger + Telegram Admin (Ooredoo Only)
// ════════════════════════════════════════════════════════════════════════════

// ════════ Facebook Config ════════
define('FB_TOKEN', 'EAAFYLlWaXQkBSTisWMklmmelKnatZAkMVOvvLCIo7bdelwJUeiPdbfCXDjF3YkjA9UZAEZCYZCpYlKHqhCbNLZAKhLMZCtg76MlwzlGFM3anNLm3m1ovwT20n6IOIPZC2ih9xgZCcSp2dVJK9SGE1AogQZCcgEvMelLSlNNJjJdYxZAcomFG7MKVAflJfiRNXZBP7Pbyzm3SuqMwZAAZDZD');
define('VERIFY_TOKEN', 'Yacin');

// ════════ Telegram Config ════════
define('TG_TOKEN', '8236832078:AAFHwmxmpneeV1XYzH73GpJNNewj9sjSi_A');
define('TG_ADMIN_ID', '8499896271');
define('TG_API', 'https://api.telegram.org/bot' . TG_TOKEN);

// ════════ Paths ════════════════════
define('OOR_PROXY_LIST_FILE', '/tmp/oor_proxies.json');
define('SESSIONS_DIR', '/tmp/oor_sessions');
define('USERS_DIR', '/tmp/oor_users');
define('PHONE_MAP_FILE', '/tmp/oor_phone_map.json');
define('DB_FILE', '/tmp/oor_dedup.sqlite');
define('NEW_USERS_FILE', '/tmp/oor_new_users.json');
define('RATE_LIMIT_DIR', '/tmp/oor_rate_limit');
define('TG_STATE_DIR', '/tmp/oor_tg_states');
define('OOR_LOG_FILE', '/tmp/oor_bot.log');

define('RATE_LIMIT_SECONDS', 300);

// ════════ Create Directories ════════
@mkdir(SESSIONS_DIR, 0777, true);
@mkdir(USERS_DIR, 0777, true);
@mkdir(RATE_LIMIT_DIR, 0777, true);
@mkdir(TG_STATE_DIR, 0777, true);

// ════════════════════════════════════════════════════════════════════════════
// OOREDOO Constants
// ════════════════════════════════════════════════════════════════════════════
define('OOR_BFF', 'https://apis.ooredoo.dz/api/ooredoo-bff');
define('OOR_AUTH_URL', 'https://apis.ooredoo.dz/api/auth/realms/myooredoo/protocol/openid-connect/token');
define('OOR_CLIENT_ID', 'myooredoo-app');
define('OOR_X_VER', '1.5.15');
define('OOR_X_SIG', 'f320f896f3da2a5a0284f9af316efb4ab0432b26406413568db116fa9dc60feb');
define('OOR_PLATFORM', 'android');
define('OOR_X_ORIGIN', 'mobile-android');
define('OOR_INSTANCE_ID', '9c416930-a4be-11f1-b35d-ed3f971366c31788127399747');
define('OOR_PLAT_SIG', 'eyJwbGF0Zm9ybSI6ImFuZHJvaWQiLCJpcy1waHlzaWNhbC1kZXZpY2UiOnRydWUsImRldmljZS1pZCI6IjljNDE2OTMwLWE0YmUtMTFmMS1iMzVkLWVkM2Y5NzEzNjZjMzE3ODgxMjczOTk3NDcifQ==');
define('OOR_FP_GENERAL', 'fe0645ef29bfa2fb33fc06793ee5c28efe984e49882e21d16d49411af3a3ef45');
define('OOR_FP_STATUS', '391d1f55cf33097ac656d9c1c8f4ef94fd427a7d6b01d5b17e490886a8e1c5ea');
define('OOR_FP_CHK1', 'd717f779e8ab59712211d85641a7e66fdc77fc21722d5c0b88395202420a0d69');
define('OOR_FP_TOKEN1', 'deb1e6b940af70681feb13e8695e7d098c1c408b862e13615de463ad0eeb956f');
define('OOR_FP_CHK2', '5e2db32cbb58d13fd03534c8cc334b6ded90061fe95fc5ff3aff43b4777ef086');
define('OOR_FP_TOKEN2', '8fbdad0928c41754cc7cbd9379db399f1fe06fb7dbd6275a7616dfecc19d7246');
define('OOR_CHK1_CORR', 'edb2fe90-a555-11f1-b11e-b52f92bd0a701788192390393');
define('OOR_CHK1_TS', '1788192390400');
define('OOR_CHK1_INT', 'JWBTpxXcH7KY9EXYeIRp8NjQMx2dt3vF8ejjoARazyoFzOw6Cu8+wkeV+zSVHSTeI6a12SG/FU+Q/9uBrcN71RlYO4CulD2x375NKwJRAInar8DVUilHBKUfjEsoHd6EwfLCfdYzLNXJu9POfEf6tqKn3PUe0pY6qZxWLhyVAFHw8Qwb52ZqdI2soCH4S3mrz+OZL+YovdGSUKlXKIkxVroAMTAg3jljLF7gvXaEsB/O3uW+tbkBMwSX2uTOwf+rh3sgOF/zPhKCFgRvZmU1o87Fa96Y/+wOcK+UpfXTCBYVrvQ1wG6ryC7vHMSqgUhYJK4tbSoti0zv4av9pwxh9Iv2MgsMENjyKBK/TuFZxEG3xQCKj1LTMgn2xBEeKcbsKrEW3xDRwsccGI3D7GHnbUlhdI4ujDIRQVKHaZSwhTp+32Cr4EB/Qt8p/xRbdgCde9MUlM4AYIGlfyVoHSkOvTu5c0DIIy1EhowtAkbKIJl49wsYKWvpT/nXFWzeXP3R2qzaclCnIb9qihhrarmwz8l8XH2T+f7AK2uSNiuWLG5Fy2mM/YLmuJPZT9Ciag/mpy7JGImKNQAcFbSAInBdKQjUKT+COzZfrXj5rwil9/HDMtuzIovq8hrQGY+roTfVPheTLWmcjkVCOL8VXAHjPb22vOuqrkMioWT9CQ5O8RgW7RKHrObeKQMp8F7tSjOqkxYDFkPVN1G4nkEBrTlPKK9YtPl+j7IuH9fGcDTwhCTTqP8peuBryW0L11tApvAEi7VkJyBxpVxW');
define('OOR_CHK2_CORR', 'f3179c10-a555-11f1-b11e-b52f92bd0a701788192399441');
define('OOR_CHK2_TS', '1788192399552');
define('OOR_CHK2_INT', 'JWBTpxXcH7KR/kzaHeUUydDWODuU4i3H4fL9gCgKzyYfyN4qLJJE8keV+zSVHSTeI6aEyC2tbSza/NGmkflQ/UgaNZSN9SKxz7tcdQckHY25n6mOaDZ9PPMQmF4sKZqDwebce/c1SNGNn6qvTULW/L348aJvy7Vii6dyPTu3OSPV9AwKwGwQKNHN61HyK3jx/fa8Os5ux9u3Q654YLZrAJ99QGEm/C9nEBPmlHC98RjovLGBnuJOGx6k7fnyrt/Ogy5fNg+tBgi5HEsoXDAwutX7TOWfx4YKHpKW//TvPRQgp69e2Xqa+CvGJJ+a/WlPMPhRCSsfo0OG04mVvlkc8eXiLzV/GPuJCEmcRf9Z6V+xoDyMtU7OZ3XT5BMhI/D/N68LsRn/9dIBH6X7n0WAW3tzeKZK1zENWFKrd7aLlzEdwFzO/1JTQsEDom5TRie0Kd5yic4BGvS1C1BsA2wJhzyiZ2j7RUE9m8tOAkPEIs929FYIfmH8XcCQKHT0TqT0+N+wCiyfNawPlDhZWcObscFZeEmKgvqiUFCoCj6xI1hvyHK0nu7fnZ/sGc6oNT/SrFjubYWjOyoYFL7gMnlYAhyjZhjuAwN1m1/wjwrOx8DbFr2NW8qb3hv+CIzK1zL3LDryKjqNkTxcOp4aJnmSTa73guCEmWc7pnLkLw5O8RgW7RKHrObeeQ0l9lu4HWXwlhdWRxvVMQDimhtTrGASL6sIuaxyhOkrH4HFImP70SaCoK9+euMxnzkPjg9HqPIFj+I0KSMloVxW');
define('OOR_TOKEN1_TS', '1788192391037');
define('OOR_TOKEN2_TS', '1788192400922');
define('OOR_STATUS_CORR', 'eb6453a0-a555-11f1-b11e-b52f92bd0a701788192386522');
define('OOR_STATUS_TS', '1788192386564');

// Ooredoo — CVM dynamic bundle codes
define('OOR_CVM_BUNDLES', [
    '5' => 'ATL_24H_DATA_90DZD_5Go',
    '6' => 'ATL_72H_DATA_190DZD_10Go',
    '7' => 'ATL_7D_DATA_490DZD_30Go',
]);

// Ooredoo — BYOP bundle payloads
define('OOR_BYOP_BUNDLES', [
    '8' => ['validity' => 'Monthly', 'limitedBundleDetails' => [['account' => 'data', 'allocation' => 30]], 'unlimitedBundleDetails' => []],
    '9' => ['validity' => 'Monthly', 'limitedBundleDetails' => [['account' => 'data', 'allocation' => 50]], 'unlimitedBundleDetails' => []],
    '10' => ['validity' => 'Monthly', 'limitedBundleDetails' => [['account' => 'data', 'allocation' => 60]], 'unlimitedBundleDetails' => []],
    '11' => ['validity' => 'Monthly', 'limitedBundleDetails' => [['account' => 'data', 'allocation' => 70]], 'unlimitedBundleDetails' => []],
    '12' => ['validity' => 'Biweekly', 'limitedBundleDetails' => [], 'unlimitedBundleDetails' => ['WA']],
    '13' => ['validity' => 'Weekly', 'limitedBundleDetails' => [], 'unlimitedBundleDetails' => ['FB']],
    '14' => ['validity' => 'Biweekly', 'limitedBundleDetails' => [], 'unlimitedBundleDetails' => ['FB']],
    '15' => ['validity' => 'Biweekly', 'limitedBundleDetails' => [], 'unlimitedBundleDetails' => ['IG']],
    '16' => ['validity' => 'Biweekly', 'limitedBundleDetails' => [], 'unlimitedBundleDetails' => ['YT']],
    '17' => ['validity' => 'Monthly', 'limitedBundleDetails' => [], 'unlimitedBundleDetails' => ['FB']],
    '18' => ['validity' => 'Monthly', 'limitedBundleDetails' => [], 'unlimitedBundleDetails' => ['IG']],
    '19' => ['validity' => 'Monthly', 'limitedBundleDetails' => [], 'unlimitedBundleDetails' => ['YT']],
]);

// ════════════════════════════════════════════════════════════════════════════
// Database Functions
// ════════════════════════════════════════════════════════════════════════════
function getDB(): PDO {
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

function tryMarkEvent(string $id): bool {
    try {
        $s = getDB()->prepare("INSERT OR IGNORE INTO processed_events (event_id, created_at) VALUES (?,?)");
        $s->execute([$id, time()]);
        return $s->rowCount() > 0;
    } catch (Throwable $e) { return true; }
}

function tryLockUser(string $psid): bool {
    try {
        $s = getDB()->prepare("INSERT OR IGNORE INTO user_locks (psid, locked_at) VALUES (?,?)");
        $s->execute([$psid, time()]);
        return $s->rowCount() > 0;
    } catch (Throwable $e) { return true; }
}

function unlockUser(string $psid): void {
    try { getDB()->prepare("DELETE FROM user_locks WHERE psid=?")->execute([$psid]); } catch (Throwable $e) {}
}

function oorLog(string $msg): void {
    file_put_contents(OOR_LOG_FILE, date('Y-m-d H:i:s') . " " . $msg . "\n", FILE_APPEND);
}

// ════════════════════════════════════════════════════════════════════════════
// Proxy Functions
// ════════════════════════════════════════════════════════════════════════════
function getOorProxies(): array {
    if (file_exists(OOR_PROXY_LIST_FILE)) {
        $d = json_decode(file_get_contents(OOR_PROXY_LIST_FILE), true);
        if (is_array($d) && !empty($d)) return $d;
    }
    return [
        'http://nhtbnigs:3tdfei3ngchg@31.59.20.176:6754',
        'http://nhtbnigs:3tdfei3ngchg@45.38.107.97:6014',
        'http://nhtbnigs:3tdfei3ngchg@198.105.121.200:6462',
        'http://nhtbnigs:3tdfei3ngchg@64.137.96.74:6641',
        'http://nhtbnigs:3tdfei3ngchg@198.23.243.226:6361',
        'http://nhtbnigs:3tdfei3ngchg@38.154.185.97:6370',
        'http://nhtbnigs:3tdfei3ngchg@84.247.60.125:6095',
    ];
}

function saveOorProxies(array $proxies): void {
    file_put_contents(OOR_PROXY_LIST_FILE, json_encode($proxies, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

function parseProxy(string $proxy): array {
    $raw = preg_replace('#^https?://#', '', $proxy);
    if (preg_match('#^(.+?):(.+?)@(.+?):(\d+)$#', $raw, $matches)) {
        return ['host' => $matches[3] . ':' . $matches[4], 'userpass' => $matches[1] . ':' . $matches[2]];
    }
    $p = explode(':', $raw, 4);
    return ['host' => ($p[0] ?? '') . ':' . ($p[1] ?? ''), 'userpass' => ($p[2] ?? '') . ':' . ($p[3] ?? '')];
}

// ════════════════════════════════════════════════════════════════════════════
// OOREDOO Curl with Proxy Support
// ════════════════════════════════════════════════════════════════════════════
function oorCurl(string $url, string $method, string $body, array $headers, string $useProxy = ''): array {
    $proxies = getOorProxies();
    
    // If a specific proxy is requested, try it first
    if (!empty($useProxy)) {
        $proxies = array_merge([$useProxy], $proxies);
    }
    
    $totalProxies = count($proxies);
    if ($totalProxies === 0) {
        return ['http_code' => 0, 'body' => '', 'json' => null, 'resp_headers' => []];
    }

    foreach ($proxies as $idx => $proxy) {
        $pp = parseProxy($proxy);
        $ch = curl_init($url);
        
        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_ENCODING => 'gzip',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER => true,
            CURLOPT_PROXY => $pp['host'],
            CURLOPT_PROXYUSERPWD => $pp['userpass'],
            CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
        ];

        if ($method === 'POST') {
            $opts[CURLOPT_POST] = true;
            $opts[CURLOPT_POSTFIELDS] = $body;
        } else {
            $opts[CURLOPT_HTTPGET] = true;
        }

        curl_setopt_array($ch, $opts);
        $raw = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = (int)curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || $raw === false || $code === 0) {
            oorLog("PROXY_FAIL: {$proxy} | err: {$err}");
            continue;
        }

        $headerStr = substr((string)$raw, 0, $headerSize);
        $bodyStr = substr((string)$raw, $headerSize);
        
        $respH = [];
        foreach (explode("\r\n", $headerStr) as $line) {
            if (strpos($line, ':') !== false) {
                [$k, $v] = explode(':', $line, 2);
                $respH[strtolower(trim($k))] = trim($v);
            }
        }

        // Skip HTML responses
        if (stripos($bodyStr, '<html') !== false || stripos($bodyStr, '<!DOCTYPE') !== false) {
            oorLog("HTML_RESPONSE: {$proxy} | HTTP: {$code}");
            continue;
        }

        $json = null;
        if (!empty($bodyStr)) {
            $d = @json_decode($bodyStr, true);
            if (is_array($d)) $json = $d;
        }

        // Valid responses: 200, 201, 202, 403, 409
        if ($code === 200 || $code === 201 || $code === 202 || $code === 403 || $code === 409) {
            return ['http_code' => $code, 'body' => $bodyStr, 'json' => $json, 'resp_headers' => $respH];
        }

        oorLog("UNEXPECTED_HTTP: {$proxy} | HTTP: {$code}");
    }

    return ['http_code' => 0, 'body' => '', 'json' => null, 'resp_headers' => []];
}

// ════════════════════════════════════════════════════════════════════════════
// OOREDOO Auth Functions
// ════════════════════════════════════════════════════════════════════════════
function oorBaseHeaders(string $msisdn, string $fp, string $corr, string $ts): array {
    return [
        'User-Agent: Dart/3.11 (dart:io)',
        'Accept-Encoding: gzip',
        "x-msisdn: {$msisdn}",
        "x-device-fingerprint: {$fp}",
        'x-platform-origin: ' . OOR_X_ORIGIN,
        "x-correlation-id: {$corr}",
        'platform: ' . OOR_PLATFORM,
        'x-version: ' . OOR_X_VER,
        'x-signature: ' . OOR_X_SIG,
        'x-platform-data-signature: ' . OOR_PLAT_SIG,
        "x-timestamp: {$ts}",
        'x-instance-id: ' . OOR_INSTANCE_ID,
    ];
}

function oorCheckStatus(string $msisdn): ?string {
    $url = OOR_BFF . '/users/status?msisdn=' . urlencode($msisdn);
    $headers = oorBaseHeaders($msisdn, OOR_FP_STATUS, OOR_STATUS_CORR, OOR_STATUS_TS);
    $r = oorCurl($url, 'GET', '', $headers);
    
    if ($r['http_code'] === 500) {
        oorLog("CHECK_STATUS: 500 error for {$msisdn}");
        return '002';
    }
    
    if ($r['http_code'] !== 200 || !is_array($r['json'])) return null;
    return $r['json']['code'] ?? null;
}

function oorCheckpoint(string $msisdn, int $type): array {
    $fp = ($type === 1) ? OOR_FP_CHK1 : OOR_FP_CHK2;
    $corr = ($type === 1) ? OOR_CHK1_CORR : OOR_CHK2_CORR;
    $ts = ($type === 1) ? OOR_CHK1_TS : OOR_CHK2_TS;
    $intg = ($type === 1) ? OOR_CHK1_INT : OOR_CHK2_INT;
    $url = OOR_BFF . '/checkpoint/token';
    $h = array_merge(oorBaseHeaders($msisdn, $fp, $corr, $ts), [
        'x-path: /api/auth/realms/myooredoo/protocol/openid-connect/token',
        'content-type: application/json; charset=utf-8',
        "x-native-integrity-token: {$intg}",
        'x-method: POST',
    ]);
    $r = oorCurl($url, 'POST', '{}', $h);
    return [
        $r['resp_headers']['x-nonce-id'] ?? '',
        $r['resp_headers']['x-chronos-id'] ?? '',
    ];
}

function oorSendSMS(string $msisdn): bool {
    [$nonce, $chronos] = oorCheckpoint($msisdn, 1);
    if (empty($nonce)) return false;
    $payload = http_build_query(['client_id' => OOR_CLIENT_ID, 'grant_type' => 'password', 'username' => $msisdn]);
    $h = [
        'User-Agent: Dart/3.11 (dart:io)', 'Accept-Encoding: gzip',
        "x-msisdn: {$msisdn}", 'x-device-fingerprint: ' . OOR_FP_TOKEN1,
        "x-nonce-id: {$nonce}", 'x-platform-origin: ' . OOR_X_ORIGIN,
        'platform: ' . OOR_PLATFORM, 'x-version: ' . OOR_X_VER, 'x-signature: ' . OOR_X_SIG,
        "x-chronos-id: {$chronos}", 'x-timestamp: ' . OOR_TOKEN1_TS,
        'x-platform-info: ' . OOR_PLAT_SIG, 'x-force-update: true',
        'x-instance-id: ' . OOR_INSTANCE_ID, 'Content-Type: application/x-www-form-urlencoded',
    ];
    $r = oorCurl(OOR_AUTH_URL, 'POST', $payload, $h);
    return $r['http_code'] === 403;
}

function oorVerifyOTP(string $msisdn, string $otp): mixed {
    [$nonce, $chronos] = oorCheckpoint($msisdn, 2);
    if (empty($nonce)) return false;
    $payload = http_build_query(['client_id' => OOR_CLIENT_ID, 'grant_type' => 'password', 'username' => $msisdn, 'otp' => $otp]);
    $h = [
        'User-Agent: Dart/3.11 (dart:io)', 'Accept-Encoding: gzip',
        "x-msisdn: {$msisdn}", 'x-device-fingerprint: ' . OOR_FP_TOKEN2,
        "x-nonce-id: {$nonce}", 'x-platform-origin: ' . OOR_X_ORIGIN,
        'platform: ' . OOR_PLATFORM, 'x-version: ' . OOR_X_VER, 'x-signature: ' . OOR_X_SIG,
        "x-chronos-id: {$chronos}", 'x-timestamp: ' . OOR_TOKEN2_TS,
        'x-platform-info: ' . OOR_PLAT_SIG, 'x-force-update: true',
        'x-instance-id: ' . OOR_INSTANCE_ID, 'Content-Type: application/x-www-form-urlencoded',
    ];
    $r = oorCurl(OOR_AUTH_URL, 'POST', $payload, $h);
    oorLog("VERIFY_OTP: HTTP " . $r['http_code'] . " for " . $msisdn);
    
    if ($r['http_code'] === 403 || $r['http_code'] === 401) return 'wrong_otp';
    if ($r['http_code'] === 200 && isset($r['json']['access_token'])) {
        return [
            'access_token' => $r['json']['access_token'],
            'refresh_token' => $r['json']['refresh_token'] ?? ''
        ];
    }
    return false;
}

function oorRefreshToken(string $msisdn, string $refreshToken): mixed {
    $payload = http_build_query(['client_id' => OOR_CLIENT_ID, 'grant_type' => 'refresh_token', 'refresh_token' => $refreshToken]);
    $h = [
        'User-Agent: Dart/3.11 (dart:io)', 'Accept-Encoding: gzip',
        "x-msisdn: {$msisdn}", 'x-device-fingerprint: ' . OOR_FP_GENERAL,
        'x-platform-origin: ' . OOR_X_ORIGIN, 'platform: ' . OOR_PLATFORM,
        'x-version: ' . OOR_X_VER, 'x-signature: ' . OOR_X_SIG,
        'x-timestamp: ' . OOR_TOKEN2_TS, 'x-platform-info: ' . OOR_PLAT_SIG,
        'x-force-update: true', 'x-instance-id: ' . OOR_INSTANCE_ID,
        'Content-Type: application/x-www-form-urlencoded',
    ];
    $r = oorCurl(OOR_AUTH_URL, 'POST', $payload, $h);
    if ($r['http_code'] === 200 && isset($r['json']['access_token'])) {
        return ['access_token' => $r['json']['access_token'], 'refresh_token' => $r['json']['refresh_token'] ?? $refreshToken];
    }
    return 'expired';
}

// ════════════════════════════════════════════════════════════════════════════
// OOREDOO API Functions
// ════════════════════════════════════════════════════════════════════════════
function oorAuthHeaders(string $msisdn, string $accessToken, string $fp = OOR_FP_GENERAL, string $corr = ''): array {
    if (!$corr) $corr = 'c0f498f0-a578-11f1-b79f-c5cef379ec311788207347712';
    return array_merge(oorBaseHeaders($msisdn, $fp, $corr, OOR_TOKEN2_TS), [
        "authorization: Bearer {$accessToken}",
    ]);
}

function oorValidateUser(string $msisdn, string $at): ?array {
    $url = OOR_BFF . '/users/validateUser?msisdn=' . urlencode($msisdn);
    $h = oorAuthHeaders($msisdn, $at, OOR_FP_GENERAL, '0d7b8060-a58f-11f1-875f-cf31b2591e691788216925030');
    $r = oorCurl($url, 'GET', '', $h);
    return ($r['http_code'] === 200 && is_array($r['json'])) ? $r['json'] : null;
}

function oorGamificationStatus(string $msisdn, string $at): ?array {
    $url = OOR_BFF . '/gamification/status';
    $h = oorAuthHeaders($msisdn, $at);
    $r = oorCurl($url, 'GET', '', $h);
    return is_array($r['json']) ? $r['json'] : null;
}

function oorGamificationPlay(string $msisdn, string $at): array {
    $chkUrl = OOR_BFF . '/checkpoint/token';
    $chkH = array_merge(oorAuthHeaders($msisdn, $at), [
        'x-path: /api/ooredoo-bff/gamification/play',
        'content-type: application/json; charset=utf-8',
        'x-method: GET',
    ]);
    $chkR = oorCurl($chkUrl, 'POST', '{}', $chkH);
    $nonce = $chkR['resp_headers']['x-nonce-id'] ?? '';
    $chronos = $chkR['resp_headers']['x-chronos-id'] ?? '';

    $url = OOR_BFF . '/gamification/play';
    $h = array_merge(oorAuthHeaders($msisdn, $at), ['accept: */*']);
    if ($nonce) $h[] = "x-nonce-id: {$nonce}";
    if ($chronos) $h[] = "x-chronos-id: {$chronos}";
    return oorCurl($url, 'GET', '', $h);
}

function oorActivateDynamicBundle(string $msisdn, string $at, string $code): array {
    $url = OOR_BFF . '/cvm/dynamic-bundle-purchase';
    $h = array_merge(oorAuthHeaders($msisdn, $at, OOR_FP_GENERAL, '038822e0-a556-11f1-b11e-b52f92bd0a701788192427022'),
        ['content-type: application/json; charset=utf-8']);
    return oorCurl($url, 'POST', json_encode(['bundleCode' => $code]), $h);
}

function oorActivateByopBundle(string $msisdn, string $at, array $bundlePayload): array {
    $url = OOR_BFF . '/bundle/purchase/byop';
    $h = array_merge(oorAuthHeaders($msisdn, $at, OOR_FP_GENERAL, '1fcb1940-a604-11f1-8de3-b33fdce6228f1788267206868'),
        ['content-type: application/json; charset=utf-8']);
    return oorCurl($url, 'POST', json_encode($bundlePayload), $h);
}

function oorActivateSnapchat(string $msisdn, string $at): array {
    $url = OOR_BFF . '/snap-chat/apply';
    $h = array_merge(oorAuthHeaders($msisdn, $at, OOR_FP_GENERAL, '2a10ffe4-075b-4489-9841-7a01a32d5761'),
        ['accept: application/json', 'content-type: application/json']);
    return oorCurl($url, 'POST', '', $h);
}

function oorGetActivePackages(string $msisdn, string $at): ?array {
    $url = OOR_BFF . '/bundle/getActivePackages?msisdn=' . urlencode($msisdn);
    $h = oorAuthHeaders($msisdn, $at, OOR_FP_GENERAL, '0b8ceb10-a574-11f1-b7e9-cf786fa3d50d1788205325377');
    $r = oorCurl($url, 'GET', '', $h);
    return is_array($r['json']) ? $r['json'] : null;
}

// ════════════════════════════════════════════════════════════════════════════
// Session / User Functions
// ════════════════════════════════════════════════════════════════════════════
function getSession(string $p): array {
    $f = SESSIONS_DIR . "/$p.json";
    return file_exists($f) ? (json_decode(file_get_contents($f), true) ?? []) : [];
}

function setSession(string $p, array $d): void {
    file_put_contents(SESSIONS_DIR . "/$p.json", json_encode($d));
}

function clearSession(string $p): void {
    $f = SESSIONS_DIR . "/$p.json";
    if (file_exists($f)) unlink($f);
}

function saveUser(string $p, array $d): void {
    file_put_contents(USERS_DIR . "/$p.json", json_encode($d, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

function getUser(string $p): ?array {
    $f = USERS_DIR . "/$p.json";
    return file_exists($f) ? json_decode(file_get_contents($f), true) : null;
}

function savePhoneOwner(string $m, string $p): void {
    $map = file_exists(PHONE_MAP_FILE) ? (json_decode(file_get_contents(PHONE_MAP_FILE), true) ?? []) : [];
    $map[$m] = $p;
    file_put_contents(PHONE_MAP_FILE, json_encode($map));
}

function getPhoneOwner(string $m): ?string {
    if (!file_exists(PHONE_MAP_FILE)) return null;
    return (json_decode(file_get_contents(PHONE_MAP_FILE), true) ?? [])[$m] ?? null;
}

function saveOorTokens(string $psid, string $msisdn, string $at, string $rt): void {
    $u = getUser($psid) ?? [];
    $u['oor_msisdn'] = $msisdn;
    $u['oor_access_token'] = $at;
    $u['oor_refresh_token'] = $rt;
    saveUser($psid, $u);
}

function getOorTokens(string $psid): ?array {
    $u = getUser($psid);
    if (!$u || empty($u['oor_access_token'])) return null;
    return ['msisdn' => $u['oor_msisdn'] ?? '', 'access_token' => $u['oor_access_token'], 'refresh_token' => $u['oor_refresh_token'] ?? ''];
}

function isNewUser(string $psid): bool {
    $map = file_exists(NEW_USERS_FILE) ? (json_decode(file_get_contents(NEW_USERS_FILE), true) ?? []) : [];
    return !isset($map[$psid]);
}

function markUserAsSeen(string $psid): void {
    $map = file_exists(NEW_USERS_FILE) ? (json_decode(file_get_contents(NEW_USERS_FILE), true) ?? []) : [];
    if (!isset($map[$psid])) {
        $map[$psid] = ['first_seen' => time(), 'last_active' => time()];
    } else {
        $map[$psid]['last_active'] = time();
    }
    file_put_contents(NEW_USERS_FILE, json_encode($map));
}

function getAllKnownUsers(): array {
    if (!file_exists(NEW_USERS_FILE)) return [];
    return array_keys(json_decode(file_get_contents(NEW_USERS_FILE), true) ?? []);
}

function getActiveUsers(int $days = 7): array {
    if (!file_exists(NEW_USERS_FILE)) return [];
    $map = json_decode(file_get_contents(NEW_USERS_FILE), true) ?? [];
    $since = time() - ($days * 86400);
    $active = [];
    foreach ($map as $psid => $data) {
        $lastActive = is_array($data) ? ($data['last_active'] ?? 0) : $data;
        if ($lastActive >= $since) $active[] = $psid;
    }
    return $active;
}

function getUserStats(): array {
    if (!file_exists(NEW_USERS_FILE)) return ['total' => 0, 'active_7d' => 0, 'active_30d' => 0];
    $map = json_decode(file_get_contents(NEW_USERS_FILE), true) ?? [];
    $total = count($map);
    $now = time();
    $a7 = 0;
    $a30 = 0;
    foreach ($map as $psid => $data) {
        $lastActive = is_array($data) ? ($data['last_active'] ?? 0) : $data;
        if ($now - $lastActive <= 7 * 86400) $a7++;
        if ($now - $lastActive <= 30 * 86400) $a30++;
    }
    return ['total' => $total, 'active_7d' => $a7, 'active_30d' => $a30];
}

// ════════════════════════════════════════════════════════════════════════════
// Rate Limit
// ════════════════════════════════════════════════════════════════════════════
function rateLimitFile(string $psid): string {
    return RATE_LIMIT_DIR . "/{$psid}.json";
}

function checkRateLimit(string $psid): ?int {
    $f = rateLimitFile($psid);
    if (!file_exists($f)) return null;
    $data = json_decode(file_get_contents($f), true);
    if (!is_array($data)) return null;
    $last = $data['last_request'] ?? 0;
    $elapsed = time() - $last;
    if ($elapsed < RATE_LIMIT_SECONDS) {
        return RATE_LIMIT_SECONDS - $elapsed;
    }
    return null;
}

function updateRateLimit(string $psid): void {
    file_put_contents(rateLimitFile($psid), json_encode(['last_request' => time()]));
}

function rateLimitMessage(int $secondsLeft): string {
    $minutes = (int)ceil($secondsLeft / 60);
    return "⏳ أنت ترسل طلبات كثيرة خلال فترة قصيرة.\n\n🔁 يرجى إعادة المحاولة بعد: " . ($minutes <= 1 ? "أقل من دقيقة" : "{$minutes} دقيقة");
}

// ════════════════════════════════════════════════════════════════════════════
// Facebook Send Functions
// ════════════════════════════════════════════════════════════════════════════
function sendMessage(string $psid, string $text): void {
    $ch = curl_init('https://graph.facebook.com/v19.0/me/messages?access_token=' . FB_TOKEN);
    $payload = json_encode([
        'recipient' => ['id' => $psid],
        'message' => ['text' => $text],
        'messaging_type' => 'RESPONSE'
    ], JSON_UNESCAPED_UNICODE);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    curl_exec($ch);
    curl_close($ch);
}

function sendMessageWithQR(string $psid, string $text, array $quickReplies): void {
    $ch = curl_init('https://graph.facebook.com/v19.0/me/messages?access_token=' . FB_TOKEN);
    $payload = json_encode([
        'recipient' => ['id' => $psid],
        'message' => ['text' => $text, 'quick_replies' => $quickReplies],
        'messaging_type' => 'RESPONSE'
    ], JSON_UNESCAPED_UNICODE);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    curl_exec($ch);
    curl_close($ch);
}

// ════════════════════════════════════════════════════════════════════════════
// Telegram Functions
// ════════════════════════════════════════════════════════════════════════════
function tgSendMessage(string $chatId, string $text, array $keyboard = []): void {
    $data = ['chat_id' => $chatId, 'text' => $text, 'parse_mode' => 'HTML'];
    if (!empty($keyboard)) {
        $data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard], JSON_UNESCAPED_UNICODE);
    }
    $ch = curl_init(TG_API . '/sendMessage');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    curl_exec($ch);
    curl_close($ch);
}

function tgAnswerCallback(string $callbackId, string $text = ''): void {
    $ch = curl_init(TG_API . '/answerCallbackQuery');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(['callback_query_id' => $callbackId, 'text' => $text]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    curl_exec($ch);
    curl_close($ch);
}

function tgEditMessage(string $chatId, int $messageId, string $text, array $keyboard = []): void {
    $data = ['chat_id' => $chatId, 'message_id' => $messageId, 'text' => $text, 'parse_mode' => 'HTML'];
    if (!empty($keyboard)) {
        $data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard], JSON_UNESCAPED_UNICODE);
    }
    $ch = curl_init(TG_API . '/editMessageText');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    curl_exec($ch);
    curl_close($ch);
}

// ════════════════════════════════════════════════════════════════════════════
// Telegram State
// ════════════════════════════════════════════════════════════════════════════
function getTgState(string $chatId): array {
    $f = TG_STATE_DIR . "/{$chatId}.json";
    if (!file_exists($f)) return [];
    return json_decode(file_get_contents($f), true) ?? [];
}

function setTgState(string $chatId, array $state): void {
    file_put_contents(TG_STATE_DIR . "/{$chatId}.json", json_encode($state));
}

function clearTgState(string $chatId): void {
    $f = TG_STATE_DIR . "/{$chatId}.json";
    if (file_exists($f)) @unlink($f);
}

// ════════════════════════════════════════════════════════════════════════════
// Telegram Handlers
// ════════════════════════════════════════════════════════════════════════════
function sendTgMainMenu(string $chatId): void {
    $proxies = getOorProxies();
    $text = "🤖 <b>لوحة تحكم Ooredoo BOT</b>\n\n"
        . "📊 اختر أمراً من القائمة أدناه:\n\n"
        . "━━━━━━━━━━━━━━━━━━━━\n"
        . "🔗 بروكسيات أوريدو: <b>" . count($proxies) . "</b>\n"
        . "━━━━━━━━━━━━━━━━━━━━";

    $keyboard = [
        [
            ['text' => '📊 إحصائيات المستخدمين', 'callback_data' => 'tg_stats'],
            ['text' => '📢 إرسال إعلان', 'callback_data' => 'tg_broadcast'],
        ],
        [
            ['text' => '🔗 بروكسيات أوريدو', 'callback_data' => 'tg_proxies'],
            ['text' => '➕ إضافة بروكسيات', 'callback_data' => 'tg_setproxies'],
        ],
        [
            ['text' => '🔄 تغيير وضع الطلب', 'callback_data' => 'tg_mode'],
        ],
    ];

    tgSendMessage($chatId, $text, $keyboard);
}

function handleTgCallback(string $chatId, int $msgId, string $data): void {
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
        case 'tg_mode':
            handleTgMode($chatId);
            break;
        default:
            if (str_starts_with($data, 'tg_confirm_broadcast_')) {
                $broadcastId = substr($data, strlen('tg_confirm_broadcast_'));
                executeBroadcast($chatId, $broadcastId);
            }
            break;
    }
}

function handleTgStats(string $chatId): void {
    $stats = getUserStats();
    $proxies = getOorProxies();
    $text = "📊 <b>إحصائيات Ooredoo BOT</b>\n\n"
        . "👥 إجمالي المستخدمين: <b>{$stats['total']}</b>\n"
        . "🟢 نشط (7 أيام): <b>{$stats['active_7d']}</b>\n"
        . "🟡 نشط (30 يوم): <b>{$stats['active_30d']}</b>\n\n"
        . "🔗 بروكسيات: <b>" . count($proxies) . "</b>\n\n"
        . "📅 التاريخ: " . date('Y-m-d H:i:s');

    $keyboard = [[['text' => '🔙 رجوع', 'callback_data' => 'tg_stats']]];
    tgSendMessage($chatId, $text, $keyboard);
}

function handleTgBroadcastStart(string $chatId): void {
    $stats = getUserStats();
    $text = "📢 <b>إرسال إعلان</b>\n\n"
        . "👥 إجمالي المستخدمين: {$stats['total']}\n"
        . "🟢 النشطين (7 أيام): {$stats['active_7d']}\n\n"
        . "اختر الجمهور المستهدف:";

    $keyboard = [
        [
            ['text' => "📢 الكل ({$stats['total']})", 'callback_data' => 'tg_broadcast_all'],
            ['text' => "✅ النشطين ({$stats['active_7d']})", 'callback_data' => 'tg_broadcast_active'],
        ],
        [['text' => '❌ إلغاء', 'callback_data' => 'tg_stats']],
    ];
    tgSendMessage($chatId, $text, $keyboard);
}

function handleTgShowProxies(string $chatId): void {
    $proxies = getOorProxies();
    $count = count($proxies);

    if ($count === 0) {
        tgSendMessage($chatId, "⚠️ لا توجد بروكسيات محفوظة!\n\nأرسل /setproxies لإضافة بروكسيات جديدة.");
        return;
    }

    $lines = ["🔗 <b>بروكسيات أوريدو ({$count})</b>\n"];
    foreach ($proxies as $i => $p) {
        $pp = parseProxy($p);
        $host = explode(':', $pp['host'])[0] ?? $pp['host'];
        $lines[] = ($i + 1) . ". <code>{$host}</code>";
    }

    $keyboard = [
        [['text' => '➕ إضافة بروكسيات', 'callback_data' => 'tg_setproxies']],
        [['text' => '🔙 رجوع', 'callback_data' => 'tg_stats']],
    ];
    tgSendMessage($chatId, implode("\n", $lines), $keyboard);
}

function handleTgSetProxies(string $chatId): void {
    setTgState($chatId, ['action' => 'awaiting_proxies']);
    tgSendMessage($chatId,
        "📝 <b>إضافة بروكسيات جديدة</b>\n\n"
        . "أرسل قائمة البروكسيات، كل بروكسي في سطر بالصيغة:\n"
        . "<code>http://user:pass@host:port</code>\n\n"
        . "أو JSON array مثل:\n"
        . "<code>[\"http://user:pass@host:port\"]</code>\n\n"
        . "⚠️ ستُستبدل القائمة الحالية بالقائمة الجديدة.\n"
        . "/cancel للإلغاء"
    );
}

function handleTgProxiesInput(string $chatId, string $text): void {
    if ($text === '/cancel') {
        clearTgState($chatId);
        tgSendMessage($chatId, '❌ تم الإلغاء.');
        return;
    }

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
        if (!preg_match('#^https?://#', $item)) {
            $item = 'http://' . $item;
        }
        if (preg_match('#^https?://.+:.+@.+:.+$#', $item)) {
            $valid[] = $item;
        }
    }

    if (empty($valid)) {
        tgSendMessage($chatId, '❌ لا توجد بروكسيات بصيغة صحيحة. الصيغة: http://user:pass@host:port');
        return;
    }

    saveOorProxies($valid);
    clearTgState($chatId);
    tgSendMessage($chatId, "✅ تم حفظ <b>" . count($valid) . "</b> بروكسي بنجاح!");
    sendTgMainMenu($chatId);
}

function handleTgMode(string $chatId): void {
    $text = "🔧 <b>وضعية إرسال الطلبات</b>\n\n"
        . "اختر كيفية إرسال الطلبات إلى خوادم أوريدو:\n\n"
        . "1️⃣ <b>مباشر</b> - إرسال الطلبات مباشرة بدون بروكسي (أسرع)\n"
        . "2️⃣ <b>بروكسي تلقائي</b> - استخدام بروكسيات عشوائية\n"
        . "3️⃣ <b>بروكسي محدد</b> - استخدام بروكسي معين";

    $keyboard = [
        [
            ['text' => '🟢 مباشر', 'callback_data' => 'tg_mode_direct'],
            ['text' => '🔄 بروكسي تلقائي', 'callback_data' => 'tg_mode_auto'],
        ],
        [
            ['text' => '🔵 بروكسي محدد', 'callback_data' => 'tg_mode_custom'],
        ],
        [['text' => '🔙 رجوع', 'callback_data' => 'tg_stats']],
    ];
    tgSendMessage($chatId, $text, $keyboard);
}

// ════════════════════════════════════════════════════════════════════════════
// Facebook Event Processing
// ════════════════════════════════════════════════════════════════════════════
function processEvent(string $psid, array $event): void {
    $isNew = isNewUser($psid);
    markUserAsSeen($psid);

    if (isset($event['postback'])) {
        handlePostback($psid, $event['postback']['payload'] ?? '');
        return;
    }
    if (!isset($event['message'])) return;

    $msg = $event['message'];
    if (isset($msg['quick_reply']['payload'])) {
        handlePostback($psid, $msg['quick_reply']['payload']);
        return;
    }

    $text = trim($msg['text'] ?? '');
    if ($text === '') {
        if ($isNew) sendWelcome($psid);
        return;
    }

    $session = getSession($psid);
    $state = $session['state'] ?? 'idle';

    // Handle OTP state
    if ($state === 'oor_awaiting_otp') {
        handleOorAwaitingOtp($psid, $text, $session);
        return;
    }

    if ($state === 'oor_menu' || $state === 'oor_offers') {
        handleOorMenuText($psid, $text, $session);
        return;
    }

    // Check for phone number (05xxxxxxxx)
    $digits = preg_replace('/\D/', '', $text);
    if (preg_match('/^05\d{8}$/', $digits)) {
        handleOorNewPhone($psid, $digits);
        return;
    }

    if ($isNew) {
        sendWelcome($psid);
    } else {
        $oor = getOorTokens($psid);
        if ($oor) {
            oorSendMenu($psid, $oor['msisdn'], $oor['access_token']);
        } else {
            sendWelcome($psid);
        }
    }
}

function sendWelcome(string $psid): void {
    sendMessage($psid,
        "👋 أهلاً وسهلاً بك في بوت Ooredoo! 🎉\n\n"
        . "📌 هذا البوت مخصص لخدمة عملاء Ooredoo فقط.\n\n"
        . "━━━━━━━━━━━━━━\n\n"
        . "📱 للبدء، أرسل رقم هاتفك (أوريدو)\n"
        . "🔹 مثال: 0550000000\n\n"
        . "⚡ قناة التلغرام: https://t.me/tasjilbott"
    );
}

function handleOorNewPhone(string $psid, string $phone): void {
    $msisdn = '213' . substr($phone, 1);

    $rl = checkRateLimit($psid);
    if ($rl !== null) {
        sendMessage($psid, rateLimitMessage($rl));
        return;
    }

    $oor = getOorTokens($psid);
    if ($oor && $oor['msisdn'] === $msisdn && !empty($oor['access_token'])) {
        $refreshed = oorRefreshToken($msisdn, $oor['refresh_token']);
        if (is_array($refreshed)) {
            saveOorTokens($psid, $msisdn, $refreshed['access_token'], $refreshed['refresh_token']);
            setSession($psid, ['state' => 'oor_menu', 'oor_msisdn' => $msisdn]);
            sendMessage($psid, "✅ تم التعرف على رقمك بنجاح!");
            oorSendMenu($psid, $msisdn, $refreshed['access_token']);
            return;
        }
    }

    sendMessage($psid, "⏳ جاري التحقق من الرقم {$phone}...");
    $statusCode = oorCheckStatus($msisdn);
    
    if ($statusCode === '002') {
        sendMessage($psid, "❌ هذا الرقم غير موجود في شبكة Ooredoo.");
        return;
    }
    if ($statusCode !== '004') {
        sendMessage($psid, "❌ تعذر التحقق من الرقم، حاول لاحقاً.");
        return;
    }

    sendMessage($psid, "📲 جاري إرسال رمز التحقق إلى {$phone}...");
    if (oorSendSMS($msisdn)) {
        setSession($psid, ['state' => 'oor_awaiting_otp', 'oor_msisdn' => $msisdn]);
        sendMessage($psid,
            "✅ تم إرسال رسالة نصية للرقم {$phone}\n\n"
            . "🔢 أدخل رمز التحقق المكوّن من 6 أرقام\n\n"
            . "📱 أو أرسل رقمك مجدداً لاستقبال رمز جديد\n\n"
            . "❌ لإلغاء العملية أرسل: 0"
        );
    } else {
        sendMessage($psid, "❌ تعذر إرسال رمز التحقق إلى Ooredoo، حاول لاحقاً.");
    }
}

function handleOorAwaitingOtp(string $psid, string $text, array $session): void {
    $msisdn = $session['oor_msisdn'] ?? '';
    $phoneDisplay = '0' . substr($msisdn, 3);

    if (trim($text) === '0') {
        clearSession($psid);
        sendMessage($psid, "✅ تم إلغاء العملية.\n\n📱 أرسل رقمك في أي وقت للبدء من جديد.");
        return;
    }

    $digits = preg_replace('/\D/', '', $text);

    // If user sends a new phone number
    if (preg_match('/^05\d{8}$/', $digits)) {
        handleOorNewPhone($psid, $digits);
        return;
    }

    if (!preg_match('/\b(\d{6})\b/', $text, $m)) {
        sendMessage($psid,
            "⚠️ أدخل رمز التحقق المكوّن من 6 أرقام.\n\n"
            . "📱 أو أرسل رقمك مجدداً لاستقبال رمز جديد\n"
            . "🔢 الرمز أُرسل إلى: {$phoneDisplay}\n\n"
            . "❌ للإلغاء أرسل: 0"
        );
        return;
    }

    if (empty($msisdn)) {
        clearSession($psid);
        sendMessage($psid, "❌ خطأ في الجلسة، أرسل رقمك مجدداً.");
        return;
    }

    sendMessage($psid, "🔐 جاري التحقق من الرمز...");
    $result = oorVerifyOTP($msisdn, $m[1]);

    if ($result === 'wrong_otp') {
        sendMessage($psid,
            "❌ الرمز خاطئ!\n\n🔄 أعد إرسال الرمز الصحيح\n"
            . "📱 أو أرسل رقمك مجدداً لاستقبال رمز جديد\n\n❌ للإلغاء أرسل: 0"
        );
        return;
    }
    if ($result === false) {
        sendMessage($psid,
            "❌ حدث خطأ، حاول مجدداً.\n\n📱 أرسل رقمك مجدداً لاستقبال رمز جديد\n\n❌ للإلغاء أرسل: 0"
        );
        return;
    }

    saveOorTokens($psid, $msisdn, $result['access_token'], $result['refresh_token']);
    savePhoneOwner($msisdn, $psid);
    setSession($psid, ['state' => 'oor_menu', 'oor_msisdn' => $msisdn]);
    sendMessage($psid, "✅ تم تسجيل الدخول بنجاح!");
    updateRateLimit($psid);
    oorSendMenu($psid, $msisdn, $result['access_token']);
}

function oorSendMenu(string $psid, string $msisdn, string $at): void {
    $phone = '0' . substr($msisdn, 3);
    $userInfo = oorValidateUser($msisdn, $at);
    $gamStatus = oorGamificationStatus($msisdn, $at);

    $planType = $userInfo['planType'] ?? '';
    $planDisplay = !empty($planType) ? "🏷️ نوع الشريحة: {$planType}\n" : '';

    // Check if user is Yooz
    $isYooz = in_array(strtoupper((string)$planType), ['YOOZ', "N'YOOZ", 'NYOOZ']);

    // Check daily gift status
    $played = $gamStatus['played'] ?? false;
    $lastPlayedStr = $gamStatus['lastPlayedTime'] ?? '';
    $giftAvail = !$played;
    $remainStr = '';
    if ($played && $lastPlayedStr) {
        $lastTs = @strtotime($lastPlayedStr);
        $remaining = $lastTs ? max(0, 86400 - (time() - $lastTs)) : 0;
        $h = (int)($remaining / 3600);
        $m2 = (int)(($remaining % 3600) / 60);
        $remainStr = "{$h}h {$m2}m";
    }

    $giftLabel = $giftAvail
        ? "1️⃣ الهدية اليومية ✅ (متاحة الآن)"
        : "1️⃣ الهدية اليومية ⏳ (متاحة بعد {$remainStr})";

    $msg = "📱 الرقم: {$phone}\n";
    if ($planDisplay) $msg .= $planDisplay;
    $msg .= "\nاختر العرض المناسب\n📌 إذا لم تظهر الأزرار أرسل الرقم المناسب 👇\n\n";
    $msg .= "━━━━━━━━━━━━━━\n\n";
    $msg .= "{$giftLabel}\n📩 أرسل: 1\n\n";
    $msg .= "2️⃣ تفعيل العروض\n📩 أرسل: 2\n\n";
    if ($isYooz) $msg .= "3️⃣ تفعيل سناب شات يوز\n📩 أرسل: 3\n\n";
    $msg .= "4️⃣ معلوماتي\n📩 أرسل: 4\n\n━━━━━━━━━━━━━━";

    $qr = [];
    if ($giftAvail) {
        $qr[] = ['content_type' => 'text', 'title' => '🎁 الهدية اليومية', 'payload' => 'OOR_DAILY_GIFT'];
    } else {
        $qr[] = ['content_type' => 'text', 'title' => "⏳ بعد {$remainStr}", 'payload' => 'OOR_DAILY_GIFT_WAIT'];
    }
    $qr[] = ['content_type' => 'text', 'title' => '📦 العروض', 'payload' => 'OOR_OFFERS'];
    if ($isYooz) $qr[] = ['content_type' => 'text', 'title' => '👻 سناب يوز', 'payload' => 'OOR_SNAPCHAT'];
    $qr[] = ['content_type' => 'text', 'title' => '📊 معلوماتي', 'payload' => 'OOR_INFO'];

    setSession($psid, [
        'state' => 'oor_menu',
        'oor_msisdn' => $msisdn,
        'oor_plan' => $planType,
        'oor_played' => $played,
        'oor_last_played' => $lastPlayedStr,
    ]);

    sendMessageWithQR($psid, $msg, $qr);
}

function handleOorMenuText(string $psid, string $text, array $session): void {
    $text = trim($text);
    $oor = getOorTokens($psid);
    if (!$oor) {
        clearSession($psid);
        sendMessage($psid, "❌ انتهت الجلسة، أرسل رقمك مجدداً.");
        return;
    }

    // Handle phone number input from menu
    $digits = preg_replace('/\D/', '', $text);
    if (preg_match('/^05\d{8}$/', $digits)) {
        handleOorNewPhone($psid, $digits);
        return;
    }

    $map = [
        '1' => 'OOR_DAILY_GIFT',
        '2' => 'OOR_OFFERS',
        '3' => 'OOR_SNAPCHAT',
        '4' => 'OOR_INFO',
        '0' => 'OOR_BACK_MENU',
    ];

    if (isset($map[$text])) {
        handlePostback($psid, $map[$text]);
        return;
    }
    if (isset(OOR_CVM_BUNDLES[$text])) {
        handlePostback($psid, 'OOR_CVM_' . $text);
        return;
    }
    if (isset(OOR_BYOP_BUNDLES[$text])) {
        handlePostback($psid, 'OOR_BYOP_' . $text);
        return;
    }

    sendMessage($psid, "❌ اختيار غير صحيح، استخدم الأزرار أو أرسل الرقم المناسب.");
    oorSendMenu($psid, $oor['msisdn'], $oor['access_token']);
}

function handlePostback(string $psid, string $payload): void {
    switch ($payload) {
        case 'GET_STARTED':
            sendWelcome($psid);
            break;
        case 'OOR_DAILY_GIFT':
            $sess = getSession($psid);
            $oor = getOorTokens($psid);
            if (!$oor) {
                sendMessage($psid, "⚠️ يجب تسجيل الدخول أولاً، أرسل رقم Ooredoo.");
                return;
            }
            handleOorDailyGift($psid, $oor['msisdn'], $oor['access_token'], $oor['refresh_token'], $sess);
            break;
        case 'OOR_DAILY_GIFT_WAIT':
            $sess = getSession($psid);
            $lastPlayed = $sess['oor_last_played'] ?? '';
            if ($lastPlayed) {
                $remaining = max(0, 86400 - (time() - strtotime($lastPlayed)));
                $h = (int)($remaining / 3600);
                $m2 = (int)(($remaining % 3600) / 60);
                sendMessage($psid, "⏳ الهدية اليومية غير متاحة بعد.\n\n⏰ تتاح بعد: {$h} ساعة و{$m2} دقيقة");
            } else {
                $oor = getOorTokens($psid);
                if ($oor) handleOorDailyGift($psid, $oor['msisdn'], $oor['access_token'], $oor['refresh_token'], $sess);
            }
            break;
        case 'OOR_OFFERS':
            $sess = getSession($psid);
            sendOorOffersMenu($psid, $sess['oor_plan'] ?? '');
            break;
        case 'OOR_SNAPCHAT':
            $oor = getOorTokens($psid);
            if (!$oor) {
                sendMessage($psid, "⚠️ يجب تسجيل الدخول أولاً.");
                return;
            }
            $sess = getSession($psid);
            $planType = $sess['oor_plan'] ?? '';
            if (!in_array(strtoupper($planType), ['YOOZ', "N'YOOZ", 'NYOOZ'])) {
                sendMessage($psid, "❌ هذا الخيار متاح فقط لمشتركي YOOZ.");
                return;
            }
            handleOorSnapchat($psid, $oor['msisdn'], $oor['access_token'], $oor['refresh_token']);
            break;
        case 'OOR_INFO':
            $oor = getOorTokens($psid);
            if (!$oor) {
                sendMessage($psid, "⚠️ يجب تسجيل الدخول أولاً.");
                return;
            }
            $sess = getSession($psid);
            handleOorInfo($psid, $oor['msisdn'], $oor['access_token'], $sess['oor_plan'] ?? '', $oor['refresh_token']);
            break;
        case 'OOR_BACK_MENU':
            $oor = getOorTokens($psid);
            if ($oor) {
                $sess = getSession($psid);
                setSession($psid, array_merge($sess, ['state' => 'oor_menu']));
                oorSendMenu($psid, $oor['msisdn'], $oor['access_token']);
            }
            break;
        default:
            if (str_starts_with($payload, 'OOR_CVM_')) {
                $offerNum = substr($payload, 8);
                $oor = getOorTokens($psid);
                if (!$oor) {
                    sendMessage($psid, "⚠️ يجب تسجيل الدخول أولاً.");
                    return;
                }
                handleOorCvmBundle($psid, $oor['msisdn'], $oor['access_token'], $oor['refresh_token'], $offerNum);
                return;
            }
            if (str_starts_with($payload, 'OOR_BYOP_')) {
                $offerNum = substr($payload, 9);
                $oor = getOorTokens($psid);
                if (!$oor) {
                    sendMessage($psid, "⚠️ يجب تسجيل الدخول أولاً.");
                    return;
                }
                handleOorByopBundle($psid, $oor['msisdn'], $oor['access_token'], $oor['refresh_token'], $offerNum);
                return;
            }
            sendWelcome($psid);
    }
}

// ════════════════════════════════════════════════════════════════════════════
// OOREDOO Action Handlers
// ════════════════════════════════════════════════════════════════════════════
function handleOorDailyGift(string $psid, string $msisdn, string $at, string $rt, array $sess): void {
    $played = $sess['oor_played'] ?? false;
    $lastPlayed = $sess['oor_last_played'] ?? '';
    if ($played && $lastPlayed) {
        $remaining = max(0, 86400 - (time() - (int)@strtotime($lastPlayed)));
        if ($remaining > 0) {
            $h = (int)($remaining / 3600);
            $m2 = (int)(($remaining % 3600) / 60);
            sendMessage($psid, "⏳ الهدية اليومية غير متاحة بعد.\n\n⏰ تتاح بعد: {$h} ساعة و{$m2} دقيقة");
            return;
        }
    }

    $rl = checkRateLimit($psid);
    if ($rl !== null) {
        sendMessage($psid, rateLimitMessage($rl));
        return;
    }

    sendMessage($psid, "🎮 جاري تفعيل الهدية اليومية...");

    $r = oorGamificationPlay($msisdn, $at);

    if ($r['http_code'] === 401 || $r['http_code'] === 0) {
        $refreshed = oorRefreshToken($msisdn, $rt);
        if (is_array($refreshed)) {
            saveOorTokens($psid, $msisdn, $refreshed['access_token'], $refreshed['refresh_token']);
            $r = oorGamificationPlay($msisdn, $refreshed['access_token']);
        }
    }

    if ($r['http_code'] === 200 && is_array($r['json'])) {
        $gift = $r['json']['giftName'] ?? '0Mo';
        $vh = $r['json']['validityHour'] ?? 0;
        if ($gift === '0Mo' || $gift === '0') {
            sendMessage($psid, "☹️ حظ سيئ! لم تربح شيئاً.");
        } else {
            sendMessage($psid, "🎉 تهانينا! لقد ربحت {$gift} صالحة لمدة {$vh} ساعة");
        }
        $sess2 = getSession($psid);
        setSession($psid, array_merge($sess2, ['oor_played' => true, 'oor_last_played' => date('c')]));
        updateRateLimit($psid);
    } else {
        sendMessage($psid, "❌ خطأ في السيرفر، حاول لاحقاً.");
    }
}

function handleOorCvmBundle(string $psid, string $msisdn, string $at, string $rt, string $offerNum): void {
    $code = OOR_CVM_BUNDLES[$offerNum] ?? null;
    if (!$code) return;

    $rl = checkRateLimit($psid);
    if ($rl !== null) {
        sendMessage($psid, rateLimitMessage($rl));
        return;
    }

    sendMessage($psid, "🔄 جاري تفعيل العرض...");
    $r = oorActivateDynamicBundle($msisdn, $at, $code);

    if ($r['http_code'] === 401 || $r['http_code'] === 0) {
        $refreshed = oorRefreshToken($msisdn, $rt);
        if (is_array($refreshed)) {
            saveOorTokens($psid, $msisdn, $refreshed['access_token'], $refreshed['refresh_token']);
            $r = oorActivateDynamicBundle($msisdn, $refreshed['access_token'], $code);
        }
    }

    $c = $r['http_code'];
    $j = $r['json'];

    if ($c === 200) {
        sendMessage($psid, "✅ تم تفعيل العرض بنجاح! 🎉");
        updateRateLimit($psid);
    } elseif ($c === 409 && str_contains($j['code'] ?? $j['message'] ?? '', 'CREDIT_LIMIT')) {
        sendMessage($psid, "❌ رصيدك غير كافٍ لتفعيل هذا العرض 💰");
    } elseif ($c === 503) {
        sendMessage($psid, "⚠️ هناك ضغط على خدمة أوريدو حالياً، حاول مجدداً بعد قليل.");
    } else {
        sendMessage($psid, "❌ خطأ في السيرفر، حاول لاحقاً. (HTTP {$c})");
    }
}

function handleOorByopBundle(string $psid, string $msisdn, string $at, string $rt, string $offerNum): void {
    $payload = OOR_BYOP_BUNDLES[$offerNum] ?? null;
    if (!$payload) return;

    $rl = checkRateLimit($psid);
    if ($rl !== null) {
        sendMessage($psid, rateLimitMessage($rl));
        return;
    }

    sendMessage($psid, "🔄 جاري تفعيل العرض...");
    $r = oorActivateByopBundle($msisdn, $at, $payload);

    if ($r['http_code'] === 401 || $r['http_code'] === 0) {
        $refreshed = oorRefreshToken($msisdn, $rt);
        if (is_array($refreshed)) {
            saveOorTokens($psid, $msisdn, $refreshed['access_token'], $refreshed['refresh_token']);
            $r = oorActivateByopBundle($msisdn, $refreshed['access_token'], $payload);
        }
    }

    $c = $r['http_code'];
    $j = $r['json'];

    if ($c === 200) {
        sendMessage($psid, "✅ تم تفعيل العرض بنجاح! 🎉");
        updateRateLimit($psid);
    } elseif ($c === 409 && str_contains($j['message'] ?? '', 'CREDIT_LIMIT')) {
        sendMessage($psid, "❌ رصيدك غير كافٍ لتفعيل هذا العرض 💰");
    } elseif ($c === 503) {
        sendMessage($psid, "⚠️ هناك ضغط على خدمة أوريدو حالياً، حاول مجدداً بعد قليل.");
    } else {
        sendMessage($psid, "❌ خطأ في السيرفر، حاول لاحقاً. (HTTP {$c})");
    }
}

function handleOorSnapchat(string $psid, string $msisdn, string $at, string $rt): void {
    $rl = checkRateLimit($psid);
    if ($rl !== null) {
        sendMessage($psid, rateLimitMessage($rl));
        return;
    }

    sendMessage($psid, "🔄 جاري تفعيل سناب شات يوز...");
    $r = oorActivateSnapchat($msisdn, $at);

    if ($r['http_code'] === 401 || $r['http_code'] === 0) {
        $refreshed = oorRefreshToken($msisdn, $rt);
        if (is_array($refreshed)) {
            saveOorTokens($psid, $msisdn, $refreshed['access_token'], $refreshed['refresh_token']);
            $r = oorActivateSnapchat($msisdn, $refreshed['access_token']);
        }
    }

    if ($r['http_code'] === 200) {
        sendMessage($psid, "✅ تم تفعيل سناب شات يوز بنجاح! 🎉");
        updateRateLimit($psid);
    } elseif ($r['http_code'] === 500) {
        sendMessage($psid, "⚠️ العرض مُفعَّل مسبقاً أو لم تمر 24 ساعة بعد.");
    } elseif ($r['http_code'] === 503) {
        sendMessage($psid, "⚠️ هناك ضغط على خدمة أوريدو حالياً، حاول مجدداً بعد قليل.");
    } else {
        sendMessage($psid, "❌ خطأ في السيرفر، حاول لاحقاً.");
    }
}

function handleOorInfo(string $psid, string $msisdn, string $at, string $planType, string $rt): void {
    sendMessage($psid, "📊 جاري جلب معلوماتك...");
    $data = oorGetActivePackages($msisdn, $at);

    if ($data === null) {
        $refreshed = oorRefreshToken($msisdn, $rt);
        if (is_array($refreshed)) {
            saveOorTokens($psid, $msisdn, $refreshed['access_token'], $refreshed['refresh_token']);
            $data = oorGetActivePackages($msisdn, $refreshed['access_token']);
        }
        if ($data === null) {
            sendMessage($psid, "❌ تعذر جلب المعلومات، حاول لاحقاً.");
            return;
        }
    }

    $phone = '0' . substr($msisdn, 3);
    $msg = "📱 الرقم: {$phone}\n";
    if (!empty($planType)) $msg .= "🏷️ نوع الشريحة: {$planType}\n";

    $balance = $data['accountBalance'] ?? null;
    if ($balance !== null) $msg .= "💰 الرصيد: {$balance} دج\n";

    $planName = $data['planName'] ?? null;
    if ($planName) $msg .= "📋 الاشتراك: {$planName}\n";

    $remaining = $data['remainigDataAllocation'] ?? null;
    if ($remaining !== null && $remaining !== '') $msg .= "🌐 البيانات المتبقية: {$remaining}\n";

    $snap = $data['snapchatAllocation'] ?? [];
    if (!empty($snap['account'])) $msg .= "👻 سناب شات: {$snap['allocationName']} — {$snap['account']}\n";

    $cvmBundle = $data['dynamicBundlesPackage']['dynamicBundle'] ?? null;
    if ($cvmBundle) $msg .= "📦 الباقة النشطة (CVM): " . ($cvmBundle['name'] ?? 'نشطة') . "\n";

    $byobBundle = $data['byobBundlesPackage']['dynamicBundle'] ?? null;
    if ($byobBundle) $msg .= "📦 الباقة النشطة (BYOP): " . ($byobBundle['name'] ?? 'نشطة') . "\n";

    $daily = $data['dailyBundlePurchases']['dailyBundles'] ?? [];
    if (!empty($daily)) {
        $msg .= "\n📅 الباقات اليومية:\n";
        foreach ($daily as $b) {
            $n = $b['bundleName'] ?? $b['name'] ?? '';
            if ($n) $msg .= "  • {$n}\n";
        }
    }

    $monthlyData = array_merge($data['monthlyDataSmartBundlePurchases']['smartBundles'] ?? [], $data['monthlyDataSmartBundlePurchases']['dataBundles'] ?? []);
    if (!empty($monthlyData)) {
        $msg .= "\n🗓️ الباقات الشهرية:\n";
        foreach ($monthlyData as $b) {
            $n = $b['bundleName'] ?? $b['name'] ?? '';
            if ($n) $msg .= "  • {$n}\n";
        }
    }

    $weeklyData = array_merge($data['weeklyBundlePurchases']['dataBundles'] ?? [], $data['weeklyBundlePurchases']['weeklyExclusiveBundles'] ?? []);
    if (!empty($weeklyData)) {
        $msg .= "\n📆 الباقات الأسبوعية:\n";
        foreach ($weeklyData as $b) {
            $n = $b['bundleName'] ?? $b['name'] ?? '';
            if ($n) $msg .= "  • {$n}\n";
        }
    }

    $gamAllocs = $data['gamificationAllocations'] ?? [];
    if (!empty($gamAllocs)) {
        $msg .= "\n🎮 مكافآت اليومية:\n";
        foreach ($gamAllocs as $a) {
            if (!empty($a)) $msg .= "  • {$a}\n";
        }
    }

    sendMessage($psid, $msg ?: "لا توجد معلومات متاحة.");
}

function sendOorOffersMenu(string $psid, string $planType): void {
    $sess = getSession($psid);
    setSession($psid, array_merge($sess, ['state' => 'oor_offers', 'oor_plan' => $planType]));
    $isYooz = in_array(strtoupper((string)$planType), ['YOOZ', "N'YOOZ", 'NYOOZ']);

    $t = "📦 قائمة عروض الإنترنت المتوفرة 📦\n\n";
    $t .= "━━━━━━━━━━━ 📅 العروض اليومية ━━━━━━━━━━━\n\n";
    $t .= "5️⃣ 5GB 🔥\n🌐 الإنترنت: 5GB\n💰 السعر: 90 دج\n⏳ المدة: 24 ساعة\n📩 للتفعيل أرسل: 5\n\n";
    $t .= "6️⃣ 10GB 🔥\n🌐 الإنترنت: 10GB\n💰 السعر: 190 دج\n⏳ المدة: 3 أيام\n📩 للتفعيل أرسل: 6\n\n";
    $t .= "━━━━━━━━━━━ 📆 العروض الأسبوعية ━━━━━━━━━━━\n\n";
    $t .= "7️⃣ 30GB 🔥\n🌐 الإنترنت: 30GB\n💰 السعر: 490 دج\n⏳ المدة: 7 أيام\n📩 للتفعيل أرسل: 7\n\n";
    $t .= "━━━━━━━━━━━ 🗓️ العروض الشهرية ━━━━━━━━━━━\n\n";
    $t .= "8️⃣ 30GB 🌐\n🌐 الإنترنت: 30GB\n💰 السعر: 950 دج\n⏳ المدة: شهر\n📩 للتفعيل أرسل: 8\n\n";
    $t .= "9️⃣ 50GB 🌐\n🌐 الإنترنت: 50GB\n💰 السعر: 1250 دج\n⏳ المدة: شهر\n📩 للتفعيل أرسل: 9\n\n";
    $t .= "🔟 60GB 🌐\n🌐 الإنترنت: 60GB\n💰 السعر: 1350 دج\n⏳ المدة: شهر\n📩 للتفعيل أرسل: 10\n\n";
    $t .= "1️⃣1️⃣ 70GB 🌐\n🌐 الإنترنت: 70GB\n💰 السعر: 1475 دج\n⏳ المدة: شهر\n📩 للتفعيل أرسل: 11\n\n";
    $t .= "━━━━━━━━━━━ 🏷️ العروض الخاصة ━━━━━━━━━━━\n\n";
    $t .= "1️⃣2️⃣ WhatsApp 💬\n🌐 واتساب\n💰 السعر: 50 دج\n⏳ المدة: 15 يوم\n📩 للتفعيل أرسل: 12\n\n";
    $t .= "1️⃣3️⃣ Facebook 📘\n🌐 فيسبوك\n💰 السعر: 80 دج\n⏳ المدة: أسبوع\n📩 للتفعيل أرسل: 13\n\n";
    $t .= "1️⃣4️⃣ Facebook 📘\n🌐 فيسبوك\n💰 السعر: 100 دج\n⏳ المدة: 15 يوم\n📩 للتفعيل أرسل: 14\n\n";
    $t .= "1️⃣5️⃣ Instagram 📸\n🌐 إنستاغرام\n💰 السعر: 100 دج\n⏳ المدة: 15 يوم\n📩 للتفعيل أرسل: 15\n\n";
    $t .= "1️⃣6️⃣ YouTube ▶️\n🌐 يوتيوب\n💰 السعر: 100 دج\n⏳ المدة: 15 يوم\n📩 للتفعيل أرسل: 16\n\n";
    $t .= "1️⃣7️⃣ Facebook 📘\n🌐 فيسبوك\n💰 السعر: 200 دج\n⏳ المدة: شهر\n📩 للتفعيل أرسل: 17\n\n";
    $t .= "1️⃣8️⃣ Instagram 📸\n🌐 إنستاغرام\n💰 السعر: 200 دج\n⏳ المدة: شهر\n📩 للتفعيل أرسل: 18\n\n";
    $t .= "1️⃣9️⃣ YouTube ▶️\n🌐 يوتيوب\n💰 السعر: 200 دج\n⏳ المدة: شهر\n📩 للتفعيل أرسل: 19\n\n";
    if ($isYooz) {
        $t .= "━━━━━━━━━━━ 👻 يوز ━━━━━━━━━━━\n\n3️⃣ سناب شات يوز\n📩 للتفعيل أرسل: 3\n\n";
    }
    $t .= "━━━━━━━━━━━━━━━━━━━━━━\n📨 أرسل رقم العرض فقط لتفعيله مباشرة";

    $qr = [
        ['content_type' => 'text', 'title' => '5 - 5GB 90دج 🔥', 'payload' => 'OOR_CVM_5'],
        ['content_type' => 'text', 'title' => '6 - 10GB 190دج 🔥', 'payload' => 'OOR_CVM_6'],
        ['content_type' => 'text', 'title' => '7 - 30GB 490دج', 'payload' => 'OOR_CVM_7'],
        ['content_type' => 'text', 'title' => '8 - 30GB شهري', 'payload' => 'OOR_BYOP_8'],
        ['content_type' => 'text', 'title' => '9 - 50GB شهري', 'payload' => 'OOR_BYOP_9'],
        ['content_type' => 'text', 'title' => '10 - 60GB شهري', 'payload' => 'OOR_BYOP_10'],
        ['content_type' => 'text', 'title' => '11 - 70GB شهري', 'payload' => 'OOR_BYOP_11'],
        ['content_type' => 'text', 'title' => '12 - WA 15يوم', 'payload' => 'OOR_BYOP_12'],
        ['content_type' => 'text', 'title' => '13 - FB أسبوع', 'payload' => 'OOR_BYOP_13'],
        ['content_type' => 'text', 'title' => '17 - FB شهري', 'payload' => 'OOR_BYOP_17'],
        ['content_type' => 'text', 'title' => '🔙 القائمة', 'payload' => 'OOR_BACK_MENU'],
    ];
    if ($isYooz) $qr[] = ['content_type' => 'text', 'title' => '👻 سناب شات يوز', 'payload' => 'OOR_SNAPCHAT'];

    sendMessageWithQR($psid, $t, $qr);
}

// ════════════════════════════════════════════════════════════════════════════
// Telegram Webhook Handler
// ════════════════════════════════════════════════════════════════════════════
function handleTelegramUpdate(array $update): void {
    if (isset($update['callback_query'])) {
        $cb = $update['callback_query'];
        $cbId = $cb['id'];
        $chatId = (string)($cb['message']['chat']['id'] ?? '');
        $data = $cb['data'] ?? '';

        if ($chatId !== TG_ADMIN_ID) {
            tgAnswerCallback($cbId, '⛔ غير مصرح');
            return;
        }
        tgAnswerCallback($cbId);
        handleTgCallback($chatId, 0, $data);
        return;
    }

    if (!isset($update['message'])) return;
    $msg = $update['message'];
    $chatId = (string)($msg['chat']['id'] ?? '');
    $text = trim($msg['text'] ?? '');

    if ($chatId !== TG_ADMIN_ID) {
        tgSendMessage($chatId, '⛔ أنت لست مصرحاً باستخدام هذا البوت.');
        return;
    }

    $state = getTgState($chatId);

    if (($state['action'] ?? '') === 'awaiting_proxies') {
        handleTgProxiesInput($chatId, $text);
        return;
    }

    switch ($text) {
        case '/start':
        case '/help':
            sendTgMainMenu($chatId);
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

// ════════════════════════════════════════════════════════════════════════════
// MAIN WEBHOOK HANDLER
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
    $data = json_decode($rawInput, true);
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
            $eid = 'oor_' . $psid . '_' . md5(json_encode($event));
            if (!tryMarkEvent($eid)) continue;
            if (!tryLockUser($psid)) { tryMarkEvent($eid); continue; }
            try {
                processEvent($psid, $event);
            } catch (Throwable $e) {
                oorLog("ERROR: " . $e->getMessage());
            }
            unlockUser($psid);
        }
    }
    exit;
}

http_response_code(200);
echo 'OK';
exit;