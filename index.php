<?php
require_once 'includes/functions.php';
$pageTitle = null; // Homepage uses site name only
$pageDescription = 'Offizielle Website der Freiwilligen Feuerwehr Langensendelbach – Aktuelles, Einsätze, Veranstaltungen und mehr.';

$latestNews = getNachrichten(3);
$upcomingEvents = getTermine(3, true);
$latestGalerien = getGalerien(6);

include 'includes/header.php';
?>

<!-- Hero Carousel -->
<?php
// Slide-Konfiguration – Texte hier anpassen, Bilder unter /images/hero/ ablegen
$heroSlides = [
    [
        'image'    => '/images/hero/slide-1.jpg',
        'gradient' => 'linear-gradient(135deg,#8b0000 0%,#cc0000 60%,#e60000 100%)',
        'icon'     => 'fire',
        'eyebrow'  => 'Freiwillige Feuerwehr · Langensendelbach',
        'title'    => 'Für Euch da –<br>rund um die Uhr.',
        'text'     => 'Wir schützen Leben und Eigentum, helfen in Not und halten unsere Gemeinschaft zusammen.',
        'btn1_href'=> '/nachrichten.php', 'btn1_label' => 'Aktuelle Meldungen', 'btn1_icon' => 'newspaper',
        'btn2_href'=> '/formulare.php',   'btn2_label' => 'Jetzt Mitglied werden','btn2_icon'=> 'person-plus-fill',
    ],
    [
        'image'    => '/images/hero/slide-2.jpg',
        'gradient' => 'linear-gradient(135deg,#1a1a1a 0%,#3a0000 50%,#cc0000 100%)',
        'icon'     => 'gear-wide-connected',
        'eyebrow'  => 'Ausbildung &amp; Übungen',
        'title'    => 'Bestens ausgebildet –<br>für jeden Einsatz.',
        'text'     => 'Regelmäßige Übungen und Fortbildungen sorgen dafür, dass unsere Mannschaft immer einsatzbereit ist.',
        'btn1_href'=> '/kalender.php',    'btn1_label' => 'Termine ansehen',     'btn1_icon' => 'calendar3',
        'btn2_href'=> '/galerie.php',     'btn2_label' => 'Bildergalerie',        'btn2_icon' => 'images',
    ],
    [
        'image'    => '/images/hero/slide-3.jpg',
        'gradient' => 'linear-gradient(135deg,#0d2a0d 0%,#1a4a1a 50%,#1a7a3c 100%)',
        'icon'     => 'people-fill',
        'eyebrow'  => 'Gemeinschaft &amp; Nachwuchs',
        'title'    => 'Werde Teil unserer<br>Feuerwehrfamilie.',
        'text'     => 'Ob aktives Mitglied, Jugendfeuerwehr oder Fördermitglied – bei uns ist jeder willkommen!',
        'btn1_href'=> '/jugendfeuerwehr.php','btn1_label'=> 'Jugendfeuerwehr',   'btn1_icon' => 'stars',
        'btn2_href'=> '/ueber-uns.php',   'btn2_label' => 'Über uns',            'btn2_icon' => 'info-circle',
    ],
];
?>
<div id="heroCarousel" class="carousel slide fw-hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">

    <!-- Indicators -->
    <div class="carousel-indicators">
        <?php foreach ($heroSlides as $i => $slide): ?>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>"
                <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>
                aria-label="Slide <?= $i + 1 ?>"></button>
        <?php endforeach; ?>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
        <?php foreach ($heroSlides as $i => $slide):
            $hasImage = file_exists(__DIR__ . $slide['image']);
        ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">

            <!-- Background: real image or gradient placeholder -->
            <div class="fw-slide-bg"
                 <?php if ($hasImage): ?>
                 style="background-image:url('<?= h($slide['image']) ?>'), <?= $slide['gradient'] ?>;"
                 <?php else: ?>
                 style="background:<?= $slide['gradient'] ?>;"
                 <?php endif; ?>>

                <!-- Overlay -->
                <div class="fw-slide-overlay"></div>

                <!-- Placeholder icon (hidden when real image is loaded) -->
                <?php if (!$hasImage): ?>
                <div class="fw-slide-placeholder-icon">
                    <i class="bi bi-<?= $slide['icon'] ?>"></i>
                </div>
                <?php endif; ?>
            </div>

            <!-- Caption -->
            <div class="carousel-caption fw-slide-caption">
                <div class="container">
                    <p class="fw-hero__eyebrow animate-fade">
                        <i class="bi bi-shield-fill-exclamation me-1"></i>
                        <?= $slide['eyebrow'] ?>
                    </p>
                    <h1 class="animate-slide"><?= $slide['title'] ?></h1>
                    <p class="fw-slide-text animate-fade"><?= h($slide['text']) ?></p>
                    <div class="d-flex flex-wrap gap-3 justify-content-start animate-fade">
                        <a href="<?= h($slide['btn1_href']) ?>" class="btn-fw">
                            <i class="bi bi-<?= $slide['btn1_icon'] ?>"></i>
                            <?= h($slide['btn1_label']) ?>
                        </a>
                        <a href="<?= h($slide['btn2_href']) ?>" class="btn-fw-outline">
                            <i class="bi bi-<?= $slide['btn2_icon'] ?>"></i>
                            <?= h($slide['btn2_label']) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Prev / Next controls -->
    <button class="carousel-control-prev fw-carousel-ctrl" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Zurück</span>
    </button>
    <button class="carousel-control-next fw-carousel-ctrl" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Weiter</span>
    </button>
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

<?php include 'includes/footer.php'; ?>
