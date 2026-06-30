<?php
/**
 * Core utility functions for FF Langensendelbach Website
 */

define('DATA_DIR', __DIR__ . '/../data/');
define('UPLOADS_DIR', __DIR__ . '/../uploads/');

function loadJson(string $file): array {
    $path = DATA_DIR . $file;
    if (!file_exists($path)) return [];
    $content = file_get_contents($path);
    return json_decode($content, true) ?? [];
}

function saveJson(string $file, array $data): bool {
    $path = DATA_DIR . $file;
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($path, $json, LOCK_EX) !== false;
}

function loadConfig(): array {
    return loadJson('config.json');
}

/**
 * Credentials are kept in a SEPARATE file (secrets.json) so they can never be
 * swept into a public static export of data/. The production .htaccess also
 * denies direct browser access to *.json.
 */
function loadSecrets(): array {
    return loadJson('secrets.json');
}

function saveSecrets(array $data): bool {
    return saveJson('secrets.json', $data);
}

function slugify(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $map = ['ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss'];
    $text = strtr($text, $map);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', trim($text));
    return substr($text, 0, 80);
}

function formatDate(string $dateStr, bool $withTime = false): string {
    $ts = strtotime($dateStr);
    if ($ts === false) return $dateStr;
    // "Uhr" must be appended as a literal – U, h and r are date() format chars.
    return $withTime
        ? date('d.m.Y, H:i', $ts) . ' Uhr'
        : date('d.m.Y', $ts);
}

function formatDateLong(string $dateStr): string {
    $ts = strtotime($dateStr);
    if ($ts === false) return $dateStr;
    $days   = ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'];
    $months = ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'];
    return $days[date('w', $ts)] . ', ' . date('j', $ts) . '. ' . $months[date('n', $ts) - 1] . ' ' . date('Y', $ts);
}

function h(?string $str): string {
    // Null-safe: a missing array key (?? not always present at call sites)
    // should render as an empty string, never throw a TypeError.
    return htmlspecialchars((string)($str ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function generateCsrfToken(): string {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(string $token): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/** Hidden form field carrying the CSRF token. */
function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . h(generateCsrfToken()) . '">';
}

/** Current CSRF token, for appending to action links (?...&token=). */
function csrfToken(): string {
    return generateCsrfToken();
}

/**
 * Guard for state-changing admin actions. Accepts the token from a POST body
 * (forms) or a GET parameter (delete links). Aborts with 403 on mismatch.
 */
function requireCsrf(): void {
    $token = $_POST['csrf_token'] ?? $_GET['token'] ?? '';
    if (!is_string($token) || !verifyCsrfToken($token)) {
        http_response_code(403);
        exit('Sicherheitsfehler: Ungültiges oder fehlendes Sicherheits-Token. Bitte gehen Sie zurück und versuchen Sie es erneut.');
    }
}

function resizeImage(string $src, string $dst, int $maxW = 400, int $maxH = 300): bool {
    if (!function_exists('imagecreatefromjpeg')) return false;
    $info = getimagesize($src);
    if (!$info) return false;

    [$w, $h, $type] = [$info[0], $info[1], $info[2]];

    // Never upscale – clamp the ratio to 1.0 so small uploads stay original size.
    $ratio = min($maxW / $w, $maxH / $h, 1);
    $newW  = max(1, (int)($w * $ratio));
    $newH  = max(1, (int)($h * $ratio));

    $source = match($type) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($src),
        IMAGETYPE_PNG  => imagecreatefrompng($src),
        IMAGETYPE_GIF  => imagecreatefromgif($src),
        IMAGETYPE_WEBP => imagecreatefromwebp($src),
        default        => false,
    };
    if (!$source) return false;

    $thumb = imagecreatetruecolor($newW, $newH);
    // Preserve transparency for PNG / GIF / WebP thumbnails.
    if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP], true)) {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
        imagefilledrectangle($thumb, 0, 0, $newW, $newH, $transparent);
    }
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newW, $newH, $w, $h);

    $result = match($type) {
        IMAGETYPE_JPEG => imagejpeg($thumb, $dst, 85),
        IMAGETYPE_PNG  => imagepng($thumb, $dst),
        IMAGETYPE_GIF  => imagegif($thumb, $dst),
        IMAGETYPE_WEBP => imagewebp($thumb, $dst, 85),
        default        => false,
    };

    imagedestroy($source);
    imagedestroy($thumb);
    return $result;
}

function getNachrichten(int $limit = 0, string $type = ''): array {
    $items = loadJson('nachrichten.json');
    usort($items, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
    if ($type) {
        $items = array_values(array_filter($items, fn($i) => ($i['type'] ?? '') === $type));
    }
    return $limit > 0 ? array_slice($items, 0, $limit) : $items;
}

function getTermine(int $limit = 0, bool $upcoming = false): array {
    $items = loadJson('termine.json');
    if ($upcoming) {
        $now   = time();
        $items = array_values(array_filter($items, fn($i) => strtotime($i['start'] ?? '') >= $now));
    }
    usort($items, fn($a, $b) => strcmp($a['start'] ?? '', $b['start'] ?? ''));
    return $limit > 0 ? array_slice($items, 0, $limit) : $items;
}

function getGalerien(int $limit = 0): array {
    $items = loadJson('galerien.json');
    usort($items, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
    return $limit > 0 ? array_slice($items, 0, $limit) : $items;
}

function getFormulare(): array {
    return loadJson('formulare.json');
}

function getFuehrung(): array {
    $data = loadJson('fuehrung.json');
    return [
        'aktive_wehr'      => $data['aktive_wehr']      ?? [],
        'jugendfeuerwehr'  => $data['jugendfeuerwehr']  ?? [],
        'kinderfeuerwehr'  => $data['kinderfeuerwehr']  ?? [],
    ];
}

function saveFuehrung(array $data): bool {
    return saveJson('fuehrung.json', $data);
}

function getTypLabel(string $type): string {
    return match($type) {
        'einsatz'     => 'Einsatz',
        'uebung'      => 'Übung',
        'veranstaltung' => 'Veranstaltung',
        'presse'      => 'Presse',
        default       => ucfirst($type),
    };
}

function getTypBadgeClass(string $type): string {
    return match($type) {
        'einsatz'     => 'badge-einsatz',
        'uebung'      => 'badge-uebung',
        'veranstaltung' => 'badge-veranstaltung',
        'presse'      => 'badge-presse',
        default       => 'badge-secondary',
    };
}

function categoryLabel(string $cat): string {
    return match($cat) {
        'uebung'      => 'Übung',
        'einsatz'     => 'Einsatz',
        'veranstaltung' => 'Veranstaltung',
        'jugend'      => 'Jugendfeuerwehr',
        'mitgliedschaft' => 'Mitgliedschaft',
        'finanzen'    => 'Finanzen',
        default       => ucfirst($cat),
    };
}
