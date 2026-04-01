<?php
require_once 'includes/functions.php';

$id = $_GET['id'] ?? '';
if (empty($id)) {
    header('Location: /galerie.php');
    exit;
}

$galerien = loadJson('galerien.json');
$album = null;
foreach ($galerien as $g) {
    if ($g['id'] === $id) { $album = $g; break; }
}
if (!$album) {
    header('HTTP/1.0 404 Not Found');
    $pageTitle = 'Galerie nicht gefunden';
    include 'includes/header.php';
    echo '<div class="container fw-section text-center py-5"><i class="bi bi-images fs-1 text-muted d-block mb-3"></i><h2>Galerie nicht gefunden</h2><a href="/galerie.php" class="btn btn-danger mt-3">Zurück zur Galerie</a></div>';
    include 'includes/footer.php';
    exit;
}

// Load photos from album index file
$albumDir  = __DIR__ . '/uploads/galerie/' . $album['id'] . '/';
$indexFile = $albumDir . 'index.json';
$photos    = [];
if (file_exists($indexFile)) {
    $photos = json_decode(file_get_contents($indexFile), true) ?? [];
}

$pageTitle = $album['title'];
$pageDescription = $album['description'] ?? 'Bilder aus ' . $album['title'];

$extraCss = ['https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css'];
$extraJs  = ['https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js', '/js/galerie.js'];

include 'includes/header.php';
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="/">Startseite</a> / <a href="/galerie.php">Galerie</a> / <?= h($album['title']) ?>
        </nav>
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mt-1">
            <div>
                <h1><?= h($album['title']) ?></h1>
                <div class="d-flex align-items-center gap-3 mt-1 text-muted small">
                    <span><i class="bi bi-calendar3 me-1"></i><?= formatDateLong($album['date']) ?></span>
                    <span><i class="bi bi-images me-1"></i><?= count($photos) ?> Fotos</span>
                    <span class="fw-badge <?= !empty($album['category']) ? 'badge-' . h($album['category']) : 'badge-secondary' ?>">
                        <?= h(categoryLabel($album['category'] ?? '')) ?>
                    </span>
                </div>
            </div>
            <a href="/galerie.php" class="btn btn-outline-secondary btn-sm align-self-start">
                <i class="bi bi-arrow-left me-1"></i>Zurück
            </a>
        </div>
    </div>
</div>

<section class="fw-section">
    <div class="container">

        <?php if (!empty($album['description'])): ?>
        <p class="text-muted mb-4"><?= h($album['description']) ?></p>
        <?php endif; ?>

        <?php if (empty($photos)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-images fs-1 d-block mb-3 opacity-25"></i>
            <p>Noch keine Fotos in diesem Album vorhanden.</p>
        </div>
        <?php else: ?>
        <div class="fw-photo-grid" id="photo-grid">
            <?php
            $baseUrl = '/uploads/galerie/' . h($album['id']) . '/';
            foreach ($photos as $photo): ?>
            <a href="<?= $baseUrl . h($photo['full']) ?>"
               class="glightbox fw-photo-item"
               data-gallery="album"
               data-description="<?= h($photo['caption'] ?? '') ?>"
               title="<?= h($photo['caption'] ?? $album['title']) ?>">
                <img src="<?= $baseUrl . h($photo['thumb']) ?>"
                     alt="<?= h($photo['caption'] ?? $album['title']) ?>"
                     loading="lazy">
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
