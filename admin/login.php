<?php
require_once '../includes/auth.php';

if (isLoggedIn()) {
    header('Location: /admin/');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if (attemptLogin($password)) {
        header('Location: /admin/');
        exit;
    }
    $error = 'Falsches Passwort. Bitte versuchen Sie es erneut.';
    sleep(1); // Brute-force delay
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Login – FF Langensendelbach</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <style>
        body { background: #f0f0f0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-card { width: 100%; max-width: 400px; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="fw-logo-circle mx-auto mb-3" style="width:64px;height:64px;">
                <i class="bi bi-shield-fill-exclamation" style="font-size:2rem;color:var(--fw-red);"></i>
            </div>
            <h1 class="h4 fw-bold">FF Langensendelbach</h1>
            <p class="text-muted small">Admin-Bereich</p>
        </div>

        <div class="admin-card p-4">
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Passwort</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                               required autofocus autocomplete="current-password"
                               placeholder="Admin-Passwort eingeben">
                    </div>
                </div>
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Anmelden
                </button>
            </form>
        </div>

        <div class="text-center mt-3">
            <a href="/" class="text-muted small"><i class="bi bi-arrow-left me-1"></i>Zurück zur Website</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
