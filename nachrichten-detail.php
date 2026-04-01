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

                <?php if (!empty($artikel['thumbnail'])): ?>
                <img src="<?= h($artikel['thumbnail']) ?>" alt="<?= h($artikel['title']) ?>"
                     class="img-fluid rounded mb-4" style="width:100%;max-height:400px;object-fit:cover;">
                <?php endif; ?>

                <!-- Article body -->
                <div class="fw-article-body" style="line-height:1.8;font-size:1.05rem;">
                    <?= $artikel['body_html'] ?? '<p>Kein Inhalt vorhanden.</p>' ?>
                </div>

                <?php if (!empty($artikel['album_id'])): ?>
                <div class="mt-4 p-3 rounded" style="background:var(--fw-red-pale);border-left:4px solid var(--fw-red);">
                    <i class="bi bi-images me-2 text-danger"></i>
                    <strong>Bildergalerie:</strong>
                    <a href="/galerie-detail.php?id=<?= h($artikel['album_id']) ?>" class="ms-2">
                        Fotos zum Einsatz ansehen <i class="bi bi-arrow-right ms-1"></i>
                    </a>
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
