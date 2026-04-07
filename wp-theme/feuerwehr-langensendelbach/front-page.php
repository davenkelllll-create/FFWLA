<?php
/**
 * Template: Startseite (front-page.php)
 * Wird geladen wenn Einstellungen > Lesen > Startseite auf "Statische Seite" gesetzt ist.
 */
get_header();

// Slide-Konfiguration – Titel/Text über Customizer änderbar
$slides = [
    [
        'meta_key'  => 'fw_hero_slide_1',
        'default_title' => get_theme_mod('fw_slide1_title', 'Für Euch da –<br>rund um die Uhr.'),
        'default_text'  => get_theme_mod('fw_slide1_text',  'Wir schützen Leben und Eigentum, helfen in Not und halten unsere Gemeinschaft zusammen.'),
        'gradient'  => 'linear-gradient(135deg,#8b0000 0%,#cc0000 60%,#e60000 100%)',
        'icon'      => 'fire',
        'eyebrow'   => 'Freiwillige Feuerwehr · ' . get_bloginfo('name'),
        'btn1'      => ['href' => get_post_type_archive_link('post') ?: home_url('/nachrichten'), 'label' => 'Aktuelle Meldungen',   'icon' => 'newspaper'],
        'btn2'      => ['href' => home_url('/formulare'),  'label' => 'Jetzt Mitglied werden', 'icon' => 'person-plus-fill'],
    ],
    [
        'meta_key'  => 'fw_hero_slide_2',
        'default_title' => get_theme_mod('fw_slide2_title', 'Bestens ausgebildet –<br>für jeden Einsatz.'),
        'default_text'  => get_theme_mod('fw_slide2_text',  'Regelmäßige Übungen und Fortbildungen sorgen dafür, dass unsere Mannschaft immer einsatzbereit ist.'),
        'gradient'  => 'linear-gradient(135deg,#1a1a1a 0%,#3a0000 50%,#cc0000 100%)',
        'icon'      => 'gear-wide-connected',
        'eyebrow'   => 'Ausbildung &amp; Übungen',
        'btn1'      => ['href' => home_url('/kalender'),   'label' => 'Termine ansehen',   'icon' => 'calendar3'],
        'btn2'      => ['href' => home_url('/galerie'),    'label' => 'Bildergalerie',      'icon' => 'images'],
    ],
    [
        'meta_key'  => 'fw_hero_slide_3',
        'default_title' => get_theme_mod('fw_slide3_title', 'Werde Teil unserer<br>Feuerwehrfamilie.'),
        'default_text'  => get_theme_mod('fw_slide3_text',  'Ob aktives Mitglied, Jugendfeuerwehr oder Fördermitglied – bei uns ist jeder willkommen!'),
        'gradient'  => 'linear-gradient(135deg,#0d2a0d 0%,#1a4a1a 50%,#1a7a3c 100%)',
        'icon'      => 'people-fill',
        'eyebrow'   => 'Gemeinschaft &amp; Nachwuchs',
        'btn1'      => ['href' => home_url('/jugendfeuerwehr'), 'label' => 'Jugendfeuerwehr', 'icon' => 'stars'],
        'btn2'      => ['href' => home_url('/ueber-uns'),       'label' => 'Über uns',         'icon' => 'info-circle'],
    ],
];
?>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide fw-hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        <?php foreach ($slides as $i => $s): ?>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>"
                <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($slides as $i => $slide):
            // Bild aus WP Media Library (Custom Field auf der Frontpage) oder Gradient
            $img_id  = get_post_meta(get_the_ID(), $slide['meta_key'], true);
            $img_url = $img_id ? wp_get_attachment_image_url((int)$img_id, 'fw-hero') : '';
        ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
            <div class="fw-slide-bg"
                 style="<?= $img_url ? "background-image:url('" . esc_url($img_url) . "'), " : '' ?><?= $slide['gradient'] ?>;">
                <div class="fw-slide-overlay"></div>
                <?php if (!$img_url): ?>
                <div class="fw-slide-placeholder-icon"><i class="bi bi-<?= $slide['icon'] ?>"></i></div>
                <?php endif; ?>
            </div>
            <div class="carousel-caption fw-slide-caption">
                <div class="container">
                    <p class="fw-hero__eyebrow animate-fade">
                        <i class="bi bi-shield-fill-exclamation me-1"></i><?= $slide['eyebrow'] ?>
                    </p>
                    <h1 class="animate-slide"><?= wp_kses_post($slide['default_title']) ?></h1>
                    <p class="fw-slide-text animate-fade"><?= esc_html($slide['default_text']) ?></p>
                    <div class="d-flex flex-wrap gap-3 justify-content-start animate-fade">
                        <a href="<?= esc_url($slide['btn1']['href']) ?>" class="btn-fw">
                            <i class="bi bi-<?= $slide['btn1']['icon'] ?>"></i> <?= esc_html($slide['btn1']['label']) ?>
                        </a>
                        <a href="<?= esc_url($slide['btn2']['href']) ?>" class="btn-fw-outline">
                            <i class="bi bi-<?= $slide['btn2']['icon'] ?>"></i> <?= esc_html($slide['btn2']['label']) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev fw-carousel-ctrl" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Zurück</span>
    </button>
    <button class="carousel-control-next fw-carousel-ctrl" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Weiter</span>
    </button>
</div>

<!-- Stats Bar -->
<div class="fw-stats">
    <div class="container">
        <div class="row g-0">
            <div class="col-6 col-md-3"><div class="fw-stat"><div class="fw-stat__number"><?= fw_option('fw_members','45') ?>+</div><div class="fw-stat__label">Aktive Mitglieder</div></div></div>
            <div class="col-6 col-md-3"><div class="fw-stat"><div class="fw-stat__number">50+</div><div class="fw-stat__label">Einsätze pro Jahr</div></div></div>
            <div class="col-6 col-md-3"><div class="fw-stat"><div class="fw-stat__number">24/7</div><div class="fw-stat__label">Einsatzbereit</div></div></div>
            <div class="col-6 col-md-3"><div class="fw-stat"><div class="fw-stat__number"><?= fw_option('fw_founded','1952') ?></div><div class="fw-stat__label">Gegründet</div></div></div>
        </div>
    </div>
</div>

<!-- Neueste Nachrichten -->
<section class="fw-section">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-section-title">Aktuelles</h2>
                <p class="text-muted mt-2 mb-0">Die neuesten Meldungen aus unserem Bereich</p>
            </div>
            <a href="<?= esc_url(get_post_type_archive_link('post') ?: home_url('/nachrichten')) ?>" class="btn btn-outline-danger btn-sm">
                Alle Nachrichten <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <?php
        $news = new WP_Query(['post_type'=>'post','posts_per_page'=>3,'post_status'=>'publish']);
        if ($news->have_posts()): ?>
        <div class="row g-4">
            <?php while ($news->have_posts()): $news->the_post(); ?>
            <div class="col-md-6 col-lg-4">
                <?php get_template_part('template-parts/content', 'post'); ?>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php else: ?>
        <p class="text-muted">Noch keine Nachrichten vorhanden.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Nächste Termine -->
<section class="fw-section fw-section--gray">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-section-title">Nächste Termine</h2>
                <p class="text-muted mt-2 mb-0">Veranstaltungen und Übungen der FF</p>
            </div>
            <a href="<?= esc_url(home_url('/kalender')) ?>" class="btn btn-outline-danger btn-sm">
                Alle Termine <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <?php
        $termine = new WP_Query([
            'post_type'      => 'fw_termin',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'meta_query'     => [
                ['key' => 'fw_termin_public', 'value' => '1'],
                ['key' => 'fw_termin_start',  'value' => current_time('Y-m-d\TH:i'), 'compare' => '>=', 'type' => 'DATETIME'],
            ],
            'meta_key'  => 'fw_termin_start',
            'orderby'   => 'meta_value',
            'order'     => 'ASC',
        ]);
        $months_de = ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'];
        if ($termine->have_posts()): ?>
        <div class="d-flex flex-column gap-3" style="max-width:680px;">
            <?php while ($termine->have_posts()): $termine->the_post();
                $start = get_post_meta(get_the_ID(), 'fw_termin_start', true);
                $ts    = strtotime($start);
                $loc   = get_post_meta(get_the_ID(), 'fw_termin_location', true);
                $color = get_post_meta(get_the_ID(), 'fw_termin_color', true) ?: '#CC0000';
            ?>
            <div class="fw-termin">
                <div class="fw-termin__date" style="background:<?= esc_attr($color) ?>">
                    <div class="fw-termin__day"><?= date('d', $ts) ?></div>
                    <div class="fw-termin__month"><?= $months_de[date('n',$ts)-1] ?></div>
                </div>
                <div class="fw-termin__info">
                    <div class="fw-termin__title"><?php the_title(); ?></div>
                    <div class="fw-termin__meta">
                        <span><i class="bi bi-clock me-1"></i><?= date('H:i', $ts) ?> Uhr</span>
                        <?php if ($loc): ?><span><i class="bi bi-geo-alt me-1"></i><?= esc_html($loc) ?></span><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php else: ?>
        <p class="text-muted">Keine bevorstehenden Termine.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Galerie Vorschau -->
<?php
$galerien = new WP_Query(['post_type'=>'fw_galerie','posts_per_page'=>6,'post_status'=>'publish']);
if ($galerien->have_posts()): ?>
<section class="fw-section">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-section-title">Galerie</h2>
                <p class="text-muted mt-2 mb-0">Eindrücke aus Einsätzen und Veranstaltungen</p>
            </div>
            <a href="<?= esc_url(get_post_type_archive_link('fw_galerie') ?: home_url('/galerie')) ?>" class="btn btn-outline-danger btn-sm">
                Alle Alben <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="fw-gallery-grid">
            <?php while ($galerien->have_posts()): $galerien->the_post(); ?>
            <a href="<?= esc_url(get_permalink()) ?>" class="fw-album-card text-decoration-none d-block">
                <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('fw-thumb', ['style'=>'width:100%;height:100%;object-fit:cover;']); ?>
                <?php else: ?>
                <div class="fw-album-card__placeholder"><i class="bi bi-images"></i></div>
                <?php endif; ?>
                <div class="fw-album-card__overlay">
                    <div class="fw-album-card__title"><?php the_title(); ?></div>
                    <div class="fw-album-card__meta"><?= get_the_date('d.m.Y') ?></div>
                </div>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA: Mitmachen -->
<section class="fw-section fw-section--red">
    <div class="container text-center">
        <i class="bi bi-people-fill fs-1 mb-3 d-block opacity-75"></i>
        <h2 class="mb-3">Werde Teil unserer Gemeinschaft</h2>
        <p class="mb-4" style="max-width:520px;margin:0 auto 1.5rem;">
            Ob aktive Feuerwehrfrau / Feuerwehrmann, Fördermitglied oder Jugendfeuerwehr – bei uns ist jeder willkommen!
        </p>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="<?= esc_url(home_url('/formulare')) ?>" class="btn-fw-outline">
                <i class="bi bi-file-earmark-arrow-down"></i> Formulare herunterladen
            </a>
            <a href="<?= esc_url(home_url('/kontakt')) ?>" class="btn bg-white text-danger fw-bold">
                <i class="bi bi-envelope me-1"></i> Kontakt aufnehmen
            </a>
        </div>
    </div>
</section>

<?php get_footer();
