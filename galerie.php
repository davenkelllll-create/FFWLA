<?php
require_once 'includes/functions.php';
$pageTitle = 'Galerie';
$pageDescription = 'Bildergalerien der Freiwilligen Feuerwehr Langensendelbach – Einsätze, Übungen und Veranstaltungen.';

$galerien = getGalerien();
$kategorien = array_unique(array_column($galerien, 'category'));

include 'includes/header.php';
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="/">Startseite</a> / Galerie
        </nav>
        <h1><i class="bi bi-images me-2 text-fw-red"></i>Galerie</h1>
        <p class="text-muted mb-0">Eindrücke aus Einsätzen, Übungen und Veranstaltungen</p>
    </div>
</div>

<section class="fw-section">
    <div class="container">

        <!-- Filter -->
        <?php if (!empty($kategorien)): ?>
        <div class="d-flex flex-wrap gap-2 mb-4">
            <button class="btn btn-danger btn-sm active" data-filter-cat="alle">Alle</button>
            <?php foreach ($kategorien as $kat): ?>
            <button class="btn btn-outline-secondary btn-sm" data-filter-cat="<?= h($kat) ?>">
                <?= h(categoryLabel($kat)) ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($galerien)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-images fs-1 d-block mb-3 opacity-25"></i>
            <p>Noch keine Galerien vorhanden.</p>
        </div>
        <?php else: ?>
        <div class="fw-gallery-grid">
            <?php foreach ($galerien as $album): ?>
            <div data-cat="<?= h($album['category'] ?? '') ?>">
                <a href="/galerie-detail.php?id=<?= h($album['id']) ?>"
                   class="fw-album-card text-decoration-none d-block"
                   title="<?= h($album['title']) ?>">
                    <?php if (!empty($album['cover_thumb'])): ?>
                    <img src="<?= h($album['cover_thumb']) ?>" alt="<?= h($album['title']) ?>">
                    <?php else: ?>
                    <div class="fw-album-card__placeholder"><i class="bi bi-images"></i></div>
                    <?php endif; ?>
                    <?php if (!empty($album['photo_count'])): ?>
                    <span class="fw-album-card__count">
                        <i class="bi bi-images me-1"></i><?= (int)$album['photo_count'] ?>
                    </span>
                    <?php endif; ?>
                    <div class="fw-album-card__overlay">
                        <span class="fw-badge mb-1" style="background:rgba(0,0,0,.4);color:#fff;font-size:.65rem;">
                            <?= h(categoryLabel($album['category'] ?? '')) ?>
                        </span>
                        <div class="fw-album-card__title"><?= h($album['title']) ?></div>
                        <div class="fw-album-card__meta">
                            <i class="bi bi-calendar3 me-1"></i><?= formatDate($album['date']) ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
