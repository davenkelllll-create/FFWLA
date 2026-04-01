<?php
require_once 'includes/functions.php';
$pageTitle = 'Nachrichten';
$pageDescription = 'Aktuelle Nachrichten, Einsatzberichte und Pressemitteilungen der Freiwilligen Feuerwehr Langensendelbach.';

$alleNachrichten = getNachrichten();

include 'includes/header.php';
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="/">Startseite</a> / Nachrichten
        </nav>
        <h1><i class="bi bi-newspaper me-2 text-fw-red"></i>Nachrichten</h1>
        <p class="text-muted mb-0">Einsatzberichte, Übungen und Veranstaltungen</p>
    </div>
</div>

<section class="fw-section">
    <div class="container">

        <!-- Filter -->
        <div class="d-flex flex-wrap gap-2 mb-4">
            <button class="btn btn-danger btn-sm active" data-filter-type="alle">Alle</button>
            <button class="btn btn-outline-secondary btn-sm" data-filter-type="einsatz">
                <i class="bi bi-fire me-1"></i>Einsätze
            </button>
            <button class="btn btn-outline-secondary btn-sm" data-filter-type="uebung">
                <i class="bi bi-gear-wide-connected me-1"></i>Übungen
            </button>
            <button class="btn btn-outline-secondary btn-sm" data-filter-type="veranstaltung">
                <i class="bi bi-calendar-event me-1"></i>Veranstaltungen
            </button>
            <button class="btn btn-outline-secondary btn-sm" data-filter-type="presse">
                <i class="bi bi-megaphone me-1"></i>Presse
            </button>
        </div>

        <?php if (empty($alleNachrichten)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-newspaper fs-1 d-block mb-3 opacity-25"></i>
            <p>Noch keine Nachrichten vorhanden.</p>
        </div>
        <?php else: ?>
        <div class="row g-4" id="news-grid">
            <?php foreach ($alleNachrichten as $artikel): ?>
            <div class="col-md-6 col-lg-4" data-news-type="<?= h($artikel['type']) ?>">
                <div class="fw-card">
                    <?php if (!empty($artikel['thumbnail'])): ?>
                    <img src="<?= h($artikel['thumbnail']) ?>" alt="<?= h($artikel['title']) ?>" class="fw-card__img">
                    <?php else: ?>
                    <div class="fw-card__img--placeholder">
                        <i class="bi bi-<?= $artikel['type'] === 'einsatz' ? 'fire' : ($artikel['type'] === 'uebung' ? 'gear-wide-connected' : 'calendar-event') ?>"></i>
                    </div>
                    <?php endif; ?>
                    <div class="fw-card__body">
                        <div class="fw-card__meta">
                            <span class="fw-badge <?= h(getTypBadgeClass($artikel['type'])) ?>">
                                <?= h(getTypLabel($artikel['type'])) ?>
                            </span>
                            <span><i class="bi bi-calendar3 me-1"></i><?= formatDate($artikel['date']) ?></span>
                        </div>
                        <h3 class="fw-card__title">
                            <a href="/nachrichten-detail.php?id=<?= h($artikel['id']) ?>"><?= h($artikel['title']) ?></a>
                        </h3>
                        <p class="fw-card__excerpt"><?= h($artikel['excerpt']) ?></p>
                    </div>
                    <div class="fw-card__footer d-flex align-items-center justify-content-between">
                        <a href="/nachrichten-detail.php?id=<?= h($artikel['id']) ?>" class="btn btn-link btn-sm text-danger p-0">
                            Weiterlesen <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        <?php if (!empty($artikel['author'])): ?>
                        <small class="text-muted"><i class="bi bi-person me-1"></i><?= h($artikel['author']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
