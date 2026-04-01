<?php
http_response_code(404);
require_once 'includes/functions.php';
$pageTitle = 'Seite nicht gefunden';
include 'includes/header.php';
?>

<section class="fw-404-section" style="position:relative;">
    <div class="fw-404-code">404</div>
    <div class="fw-404-content container">
        <div class="mb-4">
            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4"
                 style="width:100px;height:100px;background:var(--fw-red-pale);">
                <i class="bi bi-sign-stop-fill text-danger" style="font-size:2.8rem;"></i>
            </div>
            <h1 class="fw-bold mb-2">Seite nicht gefunden</h1>
            <p class="text-muted mb-4" style="max-width:420px;margin:0 auto 1.5rem;">
                Die gesuchte Seite existiert nicht oder wurde verschoben.
                Vielleicht hilft dir eine der folgenden Optionen weiter.
            </p>
        </div>

        <div class="d-flex justify-content-center flex-wrap gap-3 mb-5">
            <a href="/" class="btn-fw">
                <i class="bi bi-house-fill me-1"></i>Zur Startseite
            </a>
            <a href="/nachrichten.php" class="btn btn-outline-danger">
                <i class="bi bi-newspaper me-1"></i>Nachrichten
            </a>
            <a href="/kontakt.php" class="btn btn-outline-secondary">
                <i class="bi bi-envelope me-1"></i>Kontakt
            </a>
        </div>

        <!-- Quick navigation -->
        <div style="max-width:560px;margin:0 auto;">
            <p class="text-muted small mb-3">Alle Seiten im Überblick:</p>
            <div class="row g-2">
                <?php
                $seitenmap = [
                    ['href' => '/nachrichten.php',      'icon' => 'newspaper',              'label' => 'Nachrichten'],
                    ['href' => '/kalender.php',         'icon' => 'calendar3',              'label' => 'Kalender'],
                    ['href' => '/galerie.php',          'icon' => 'images',                 'label' => 'Galerie'],
                    ['href' => '/jugendfeuerwehr.php',  'icon' => 'stars',                  'label' => 'Jugendfeuerwehr'],
                    ['href' => '/formulare.php',        'icon' => 'file-earmark-pdf',        'label' => 'Formulare'],
                    ['href' => '/ueber-uns.php',        'icon' => 'people-fill',            'label' => 'Über uns'],
                    ['href' => '/links.php',            'icon' => 'link-45deg',             'label' => 'Links & Partner'],
                    ['href' => '/kontakt.php',          'icon' => 'envelope-fill',          'label' => 'Kontakt'],
                ];
                foreach ($seitenmap as $s): ?>
                <div class="col-6 col-sm-3">
                    <a href="<?= $s['href'] ?>" class="d-flex align-items-center gap-2 p-2 rounded text-muted small"
                       style="background:var(--fw-gray-100);text-decoration:none;transition:background .15s;">
                        <i class="bi bi-<?= $s['icon'] ?> text-danger"></i>
                        <?= h($s['label']) ?>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
