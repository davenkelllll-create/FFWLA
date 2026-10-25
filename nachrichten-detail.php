<?php
require_once 'includes/functions.php';

$id = $_GET['id'] ?? '';
if (empty($id)) {
    header('Location: /nachrichten.php');
    exit;
}

$alleNachrichten = loadJson('nachrichten.json');
$artikel = null;
foreach ($alleNachrichten as $item) {
    if ($item['id'] === $id) {
        $artikel = $item;
        break;
    }
}
if (!$artikel) {
    header('HTTP/1.0 404 Not Found');
    $pageTitle = 'Artikel nicht gefunden';
    include 'includes/header.php';
    echo '<div class="container fw-section"><div class="text-center py-5"><i class="bi bi-search fs-1 text-muted d-block mb-3"></i><h2>Artikel nicht gefunden</h2><a href="/nachrichten.php" class="btn btn-danger mt-3">Zurück zur Übersicht</a></div></div>';
    include 'includes/footer.php';
    exit;
}

$pageTitle = $artikel['title'];
$pageDescription = $artikel['excerpt'] ?? '';
$headerBild = ($artikel['header_image'] ?? '') ?: ($artikel['thumbnail'] ?? '');
$bilder     = $artikel['images'] ?? [];
if ($bilder) {
    $extraCss = ['https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css'];
    $extraJs  = ['https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js', '/js/galerie.js'];
}
include 'includes/header.php';
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="/">Startseite</a> / <a href="/nachrichten.php">Nachrichten</a> / <?= h($artikel['title']) ?>
        </nav>
        <h1 class="mt-2"><?= h($artikel['title']) ?></h1>
        <div class="d-flex align-items-center gap-3 mt-2 flex-wrap">
            <span class="fw-badge <?= h(getTypBadgeClass($artikel['type'])) ?>">
                <?= h(getTypLabel($artikel['type'])) ?>
            </span>
            <span class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i><?= formatDateLong($artikel['date']) ?>
            </span>
            <?php if (!empty($artikel['author'])): ?>
            <span class="text-muted small">
                <i class="bi bi-person me-1"></i><?= h($artikel['author']) ?>
            </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<section class="fw-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <?php if ($headerBild): ?>
                <img src="<?= h($headerBild) ?>" alt="<?= h($artikel['title']) ?>"
                     class="img-fluid rounded mb-4" style="width:100%;max-height:400px;object-fit:cover;">
                <?php endif; ?>

                <!-- Article body -->
                <div class="fw-article-body" style="line-height:1.8;font-size:1.05rem;">
                    <?= $artikel['body_html'] ?? '<p>Kein Inhalt vorhanden.</p>' ?>
                </div>

                <?php if ($bilder): ?>
                <!-- Beitragsbilder -->
                <div class="row g-2 mt-4">
                    <?php foreach ($bilder as $img): ?>
                    <div class="col-6 col-md-4">
                        <a href="<?= h($img['full'] ?? '') ?>" class="glightbox d-block" data-gallery="artikel"
                           <?php if (!empty($img['caption'])): ?>data-description="<?= h($img['caption']) ?>"<?php endif; ?>>
                            <img src="<?= h($img['thumb'] ?? $img['full'] ?? '') ?>" alt="<?= h($img['caption'] ?? $artikel['title']) ?>"
                                 loading="lazy" class="img-fluid rounded" style="width:100%;aspect-ratio:4/3;object-fit:cover;">
                        </a>
                        <?php if (!empty($img['caption'])): ?>
                        <div class="small text-muted mt-1"><?= h($img['caption']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="/nachrichten.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Zurück zur Übersicht
                    </a>
                    <div class="text-muted small">
                        Veröffentlicht am <?= formatDate($artikel['date']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
