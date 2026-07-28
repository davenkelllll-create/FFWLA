<?php
$adminPage = basename($_SERVER['PHP_SELF']);
function adminActive(string $page): string {
    global $adminPage;
    return $adminPage === $page ? ' active' : '';
}
?>
<aside class="admin-sidebar" style="min-width:220px;max-width:220px;">
    <div class="admin-logo">
        <div class="fw-logo-circle fw-logo-circle--sm">
            <img src="/images/branding/wappen-schild.png" alt="Wappen FF Langensendelbach">
        </div>
        <span>FF Langensendelbach</span>
    </div>

    <nav>
        <a href="/admin/" class="admin-nav-link<?= adminActive('index.php') ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="/admin/nachrichten.php" class="admin-nav-link<?= adminActive('nachrichten.php') ?>">
            <i class="bi bi-newspaper"></i> Nachrichten
        </a>
        <a href="/admin/termine.php" class="admin-nav-link<?= adminActive('termine.php') ?>">
            <i class="bi bi-calendar3"></i> Termine
        </a>
        <a href="/admin/galerie.php" class="admin-nav-link<?= adminActive('galerie.php') ?>">
            <i class="bi bi-images"></i> Galerie
        </a>
        <a href="/admin/formulare.php" class="admin-nav-link<?= adminActive('formulare.php') ?>">
            <i class="bi bi-file-earmark-pdf"></i> Formulare
        </a>
        <a href="/admin/einstellungen.php" class="admin-nav-link<?= adminActive('einstellungen.php') ?>">
            <i class="bi bi-gear-fill"></i> Einstellungen
        </a>
        <hr style="border-color:rgba(255,255,255,.1);margin:.75rem 0;">
        <a href="/" target="_blank" class="admin-nav-link">
            <i class="bi bi-box-arrow-up-right"></i> Website
        </a>
        <a href="/admin/logout.php" class="admin-nav-link">
            <i class="bi bi-box-arrow-right"></i> Abmelden
        </a>
    </nav>
</aside>
