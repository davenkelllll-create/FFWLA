<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/functions.php';
$config   = loadConfig();
$siteName = $config['site_name'] ?? 'Freiwillige Feuerwehr Langensendelbach';
$siteShort = $config['site_short'] ?? 'FF Langensendelbach';

// Determine active nav item
$currentPage = basename($_SERVER['PHP_SELF']);
function isActive(string $page): string {
    global $currentPage;
    return $currentPage === $page ? ' active" aria-current="page' : '';
}
function isActiveParent(array $pages): string {
    global $currentPage;
    return in_array($currentPage, $pages) ? ' active' : '';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= h($pageDescription ?? 'Offizielle Website der ' . $siteName . ' – Aktuelles, Einsätze, Galerie und Veranstaltungen.') ?>">
    <meta property="og:title" content="<?= h(($pageTitle ?? '') ? $pageTitle . ' – ' . $siteShort : $siteName) ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="de_DE">
    <title><?= h(isset($pageTitle) ? $pageTitle . ' – ' . $siteShort : $siteName) ?></title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php if (!empty($extraCss)): foreach ($extraCss as $css): ?>
    <link rel="stylesheet" href="<?= h($css) ?>">
    <?php endforeach; endif; ?>
    <!-- Custom styles -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<!-- Emergency Alert Strip (shown when data/alert.json is active) -->
<?php
$alert = loadJson('alert.json');
if (!empty($alert['active']) && !empty($alert['message'])): ?>
<div class="alert-strip">
    <div class="container">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= h($alert['message']) ?>
    </div>
</div>
<?php endif; ?>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg fw-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/">
            <div class="fw-logo-circle">
                <i class="bi bi-shield-fill-exclamation"></i>
            </div>
            <div class="fw-brand-text">
                <span class="fw-brand-name"><?= h($siteShort) ?></span>
                <span class="fw-brand-sub">Freiwillige Feuerwehr</span>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Navigation öffnen">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link<?= isActive('index.php') ?>" href="/">Startseite</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle<?= isActiveParent(['nachrichten.php','nachrichten-detail.php','kalender.php']) ?>"
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Aktuelles
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item<?= isActive('nachrichten.php') ?>" href="/nachrichten.php">
                            <i class="bi bi-newspaper me-2"></i>Nachrichten</a></li>
                        <li><a class="dropdown-item<?= isActive('kalender.php') ?>" href="/kalender.php">
                            <i class="bi bi-calendar3 me-2"></i>Veranstaltungskalender</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= isActive('galerie.php') ?>" href="/galerie.php">Galerie</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= isActive('formulare.php') ?>" href="/formulare.php">Formulare</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= isActive('ueber-uns.php') ?>" href="/ueber-uns.php">Über uns</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?= isActive('kontakt.php') ?>" href="/kontakt.php">Kontakt</a>
                </li>
            </ul>
            <a href="tel:112" class="fw-notruf ms-lg-3">
                <i class="bi bi-telephone-fill"></i> Notruf 112
            </a>
        </div>
    </div>
</nav>
