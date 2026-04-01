<?php
require_once 'includes/functions.php';
$pageTitle = null; // Homepage uses site name only
$pageDescription = 'Offizielle Website der Freiwilligen Feuerwehr Langensendelbach – Aktuelles, Einsätze, Veranstaltungen und mehr.';

$latestNews = getNachrichten(3);
$upcomingEvents = getTermine(3, true);
$latestGalerien = getGalerien(6);

include 'includes/header.php';
?>

<!-- Hero -->
<section class="fw-hero">
    <div class="container fw-hero__content">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <p class="fw-hero__eyebrow">
                    <i class="bi bi-shield-fill-exclamation me-1"></i>
                    Freiwillige Feuerwehr · Langensendelbach
                </p>
                <h1>Für Euch da – rund um die Uhr.</h1>
                <p>
                    Wir schützen Leben und Eigentum, helfen in Not und halten unsere Gemeinschaft zusammen.
                    Ehrenamtlich. Entschlossen. Engagiert.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="/nachrichten.php" class="btn-fw">
                        <i class="bi bi-newspaper"></i> Aktuelle Meldungen
                    </a>
                    <a href="/formulare.php" class="btn-fw-outline">
                        <i class="bi bi-person-plus-fill"></i> Jetzt Mitglied werden
                    </a>
                </div>
            </div>
        </div>
        <i class="bi bi-fire fw-hero-icon"></i>
    </div>
</section>

<!-- Stats Bar -->
<div class="fw-stats">
    <div class="container">
        <div class="row g-0">
            <div class="col-6 col-md-3">
                <div class="fw-stat">
                    <div class="fw-stat__number">45+</div>
                    <div class="fw-stat__label">Aktive Mitglieder</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-stat">
                    <div class="fw-stat__number">50+</div>
                    <div class="fw-stat__label">Einsätze pro Jahr</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-stat">
                    <div class="fw-stat__number">24/7</div>
                    <div class="fw-stat__label">Einsatzbereit</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-stat">
                    <div class="fw-stat__number">1952</div>
                    <div class="fw-stat__label">Gegründet</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest News -->
<section class="fw-section">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-section-title">Aktuelles</h2>
                <p class="text-muted mt-2 mb-0">Die neuesten Meldungen aus unserem Bereich</p>
            </div>
            <a href="/nachrichten.php" class="btn btn-outline-danger btn-sm">
                Alle Nachrichten <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <?php if (empty($latestNews)): ?>
        <p class="text-muted">Noch keine Nachrichten vorhanden.</p>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($latestNews as $artikel): ?>
            <div class="col-md-6 col-lg-4">
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
                    <div class="fw-card__footer">
                        <a href="/nachrichten-detail.php?id=<?= h($artikel['id']) ?>" class="btn btn-link btn-sm text-danger p-0">
                            Weiterlesen <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Upcoming Events -->
<?php if (!empty($upcomingEvents)): ?>
<section class="fw-section fw-section--gray">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-section-title">Nächste Termine</h2>
                <p class="text-muted mt-2 mb-0">Veranstaltungen und Übungen der FF Langensendelbach</p>
            </div>
            <a href="/kalender.php" class="btn btn-outline-danger btn-sm">
                Alle Termine <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="d-flex flex-column gap-3" style="max-width: 680px;">
            <?php foreach ($upcomingEvents as $termin):
                $ts = strtotime($termin['start']);
                $months = ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'];
            ?>
            <div class="fw-termin">
                <div class="fw-termin__date">
                    <div class="fw-termin__day"><?= date('d', $ts) ?></div>
                    <div class="fw-termin__month"><?= $months[date('n', $ts) - 1] ?></div>
                </div>
                <div class="fw-termin__info">
                    <div class="fw-termin__title"><?= h($termin['title']) ?></div>
                    <div class="fw-termin__meta">
                        <span><i class="bi bi-clock me-1"></i><?= date('H:i', $ts) ?> Uhr</span>
                        <?php if (!empty($termin['location'])): ?>
                        <span><i class="bi bi-geo-alt me-1"></i><?= h($termin['location']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Gallery Preview -->
<?php if (!empty($latestGalerien)): ?>
<section class="fw-section">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-section-title">Galerie</h2>
                <p class="text-muted mt-2 mb-0">Eindrücke aus Einsätzen und Veranstaltungen</p>
            </div>
            <a href="/galerie.php" class="btn btn-outline-danger btn-sm">
                Alle Alben <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="fw-gallery-grid">
            <?php foreach ($latestGalerien as $album): ?>
            <a href="/galerie-detail.php?id=<?= h($album['id']) ?>" class="fw-album-card text-decoration-none" title="<?= h($album['title']) ?>">
                <?php if (!empty($album['cover_thumb'])): ?>
                <img src="<?= h($album['cover_thumb']) ?>" alt="<?= h($album['title']) ?>">
                <?php else: ?>
                <div class="fw-album-card__placeholder"><i class="bi bi-images"></i></div>
                <?php endif; ?>
                <?php if (!empty($album['photo_count'])): ?>
                <span class="fw-album-card__count"><i class="bi bi-images me-1"></i><?= (int)$album['photo_count'] ?></span>
                <?php endif; ?>
                <div class="fw-album-card__overlay">
                    <div class="fw-album-card__title"><?= h($album['title']) ?></div>
                    <div class="fw-album-card__meta"><?= formatDate($album['date']) ?></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA: Mitmachen -->
<section class="fw-section fw-section--red">
    <div class="container text-center">
        <i class="bi bi-people-fill fs-1 mb-3 d-block opacity-75"></i>
        <h2 class="mb-3">Werde Teil unserer Gemeinschaft</h2>
        <p class="mb-4" style="max-width:520px; margin:0 auto 1.5rem;">
            Ob aktive Feuerwehrfrau / Feuerwehrmann, Fördermitglied oder Jugendfeuerwehr –
            bei uns ist jeder willkommen!
        </p>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="/formulare.php" class="btn-fw-outline">
                <i class="bi bi-file-earmark-arrow-down"></i> Formulare herunterladen
            </a>
            <a href="/kontakt.php" class="btn bg-white text-danger fw-bold">
                <i class="bi bi-envelope me-1"></i> Kontakt aufnehmen
            </a>
        </div>
    </div>
</section>

<!-- Back to top -->
<button id="backToTop" class="btn btn-danger btn-sm rounded-circle"
        style="position:fixed;bottom:1.5rem;right:1.5rem;width:44px;height:44px;display:none;z-index:999;align-items:center;justify-content:center;"
        title="Nach oben">
    <i class="bi bi-arrow-up"></i>
</button>
<style>#backToTop.show{display:flex!important;}</style>

<?php include 'includes/footer.php'; ?>
