<?php get_header(); ?>

<?php while (have_posts()): the_post(); ?>

<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="<?= esc_url(home_url('/')) ?>">Startseite</a> /
            <a href="<?= esc_url(get_post_type_archive_link('post')) ?>">Nachrichten</a> /
            <?php the_title(); ?>
        </nav>
        <h1 class="mt-2"><?php the_title(); ?></h1>
        <div class="d-flex align-items-center gap-3 mt-2 flex-wrap">
            <?= fw_get_type_badge() ?>
            <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i><?= get_the_date('d.m.Y') ?></span>
            <span class="text-muted small"><i class="bi bi-person me-1"></i><?= get_the_author() ?></span>
        </div>
    </div>
</div>

<section class="fw-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('fw-hero', ['class'=>'img-fluid rounded mb-4','style'=>'max-height:400px;width:100%;object-fit:cover;']); ?>
                <?php endif; ?>

                <div class="fw-article-body" style="line-height:1.8;font-size:1.05rem;">
                    <?php the_content(); ?>
                </div>

                <?php
                // Link zur Galerie wenn verknüpft
                $gallery_id = get_post_meta(get_the_ID(), 'fw_related_gallery', true);
                if ($gallery_id):
                    $gallery = get_post((int)$gallery_id);
                    if ($gallery): ?>
                <div class="mt-4 p-3 rounded" style="background:var(--fw-red-pale);border-left:4px solid var(--fw-red);">
                    <i class="bi bi-images me-2 text-danger"></i>
                    <strong>Bildergalerie:</strong>
                    <a href="<?= esc_url(get_permalink($gallery)) ?>" class="ms-2">
                        Fotos zum Einsatz ansehen <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                    <?php endif;
                endif; ?>

                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="<?= esc_url(get_post_type_archive_link('post')) ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Zurück zur Übersicht
                    </a>
                    <span class="text-muted small">Veröffentlicht am <?= get_the_date('d.m.Y') ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer();
