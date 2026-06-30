<?php
/**
 * Single: Galerie-Album (fw_galerie)
 * GLightbox-Fotogalerie. CSS/JS + Init werden in functions.php enqueued
 * (is_singular('fw_galerie')).
 */
get_header();

while (have_posts()): the_post();

// Fotos: alle an das Album angehängten Bilder.
$photos = get_attached_media('image', get_the_ID());
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="<?= esc_url(home_url('/')) ?>">Startseite</a> /
            <a href="<?= esc_url(get_post_type_archive_link('fw_galerie') ?: home_url('/galerie')) ?>">Galerie</a> /
            <?php the_title(); ?>
        </nav>
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mt-1">
            <div>
                <h1><?php the_title(); ?></h1>
                <div class="d-flex align-items-center gap-3 mt-1 text-muted small">
                    <span><i class="bi bi-calendar3 me-1"></i><?= get_the_date('d.m.Y') ?></span>
                    <span><i class="bi bi-images me-1"></i><?= count($photos) ?> Fotos</span>
                    <?php
                    $kats = get_the_terms(get_the_ID(), 'fw_galerie_kat');
                    if (!empty($kats) && !is_wp_error($kats)): ?>
                    <span class="fw-badge badge-secondary"><?= esc_html($kats[0]->name) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <a href="<?= esc_url(get_post_type_archive_link('fw_galerie') ?: home_url('/galerie')) ?>" class="btn btn-outline-secondary btn-sm align-self-start">
                <i class="bi bi-arrow-left me-1"></i>Zurück
            </a>
        </div>
    </div>
</div>

<section class="fw-section">
    <div class="container">

        <?php if (trim(get_the_content()) !== ''): ?>
        <div class="fw-article-body text-muted mb-4" style="line-height:1.8;"><?php the_content(); ?></div>
        <?php endif; ?>

        <?php if (empty($photos)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-images fs-1 d-block mb-3 opacity-25"></i>
            <p>Noch keine Fotos in diesem Album vorhanden.</p>
        </div>
        <?php else: ?>
        <div class="fw-photo-grid" id="photo-grid">
            <?php foreach ($photos as $photo):
                $full    = wp_get_attachment_image_url($photo->ID, 'fw-gallery');
                $thumb   = wp_get_attachment_image_url($photo->ID, 'fw-thumb');
                $caption = wp_get_attachment_caption($photo->ID);
                $alt     = get_post_meta($photo->ID, '_wp_attachment_image_alt', true);
                $title   = $caption ?: ($alt ?: get_the_title());
                if (!$full) continue;
            ?>
            <a href="<?= esc_url($full) ?>"
               class="glightbox fw-photo-item"
               data-gallery="album"
               data-description="<?= esc_attr($caption) ?>"
               title="<?= esc_attr($title) ?>">
                <img src="<?= esc_url($thumb ?: $full) ?>"
                     alt="<?= esc_attr($title) ?>"
                     loading="lazy">
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer();
