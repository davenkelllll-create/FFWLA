<?php get_header(); ?>

<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="<?= esc_url(home_url('/')) ?>">Startseite</a> / Nachrichten
        </nav>
        <h1><i class="bi bi-newspaper me-2 text-fw-red"></i>Nachrichten</h1>
        <p class="text-muted mb-0">Einsatzberichte, Übungen und Veranstaltungen</p>
    </div>
</div>

<section class="fw-section">
    <div class="container">

        <!-- Typ-Filter -->
        <div class="d-flex flex-wrap gap-2 mb-4">
            <button class="btn btn-danger btn-sm active" data-filter-type="alle">Alle</button>
            <?php
            $typen = get_terms(['taxonomy'=>'fw_nachrichtentyp','hide_empty'=>true]);
            foreach ((array)$typen as $term): ?>
            <button class="btn btn-outline-secondary btn-sm" data-filter-type="<?= esc_attr($term->slug) ?>">
                <?= esc_html($term->name) ?>
            </button>
            <?php endforeach; ?>
        </div>

        <?php if (have_posts()): ?>
        <div class="row g-4" id="news-grid">
            <?php while (have_posts()): the_post();
                $terms  = get_the_terms(get_the_ID(), 'fw_nachrichtentyp');
                $slug   = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->slug : 'allgemein';
            ?>
            <div class="col-md-6 col-lg-4" data-news-type="<?= esc_attr($slug) ?>">
                <?php get_template_part('template-parts/content', 'post'); ?>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="mt-4">
            <?php the_posts_pagination(['prev_text'=>'&larr; Ältere Beiträge','next_text'=>'Neuere Beiträge &rarr;']); ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-newspaper fs-1 d-block mb-3 opacity-25"></i>
            <p>Noch keine Nachrichten vorhanden.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer();
