<?php
/**
 * Admin authentication helper
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_secure'   => isset($_SERVER['HTTPS']),
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
    ]);
}

require_once __DIR__ . '/functions.php';

function isLoggedIn(): bool {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function attemptLogin(string $password): bool {
    // Hash lives in data/secrets.json; fall back to legacy config.json location.
    $secrets = loadSecrets();
    $hash    = $secrets['admin_password_hash'] ?? (loadConfig()['admin_password_hash'] ?? '');
    if (empty($hash)) return false;
    if (password_verify($password, $hash)) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_login_time'] = time();
        return true;
    }
    return false;
}

function logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
