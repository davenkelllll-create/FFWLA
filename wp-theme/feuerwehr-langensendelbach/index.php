<?php get_header(); ?>

<div class="fw-page-header">
    <div class="container">
        <h1><?php the_archive_title(); ?></h1>
    </div>
</div>

<section class="fw-section">
    <div class="container">
        <?php if (have_posts()): ?>
        <div class="row g-4">
            <?php while (have_posts()): the_post(); ?>
            <div class="col-md-6 col-lg-4">
                <?php get_template_part('template-parts/content', get_post_type()); ?>
            </div>
            <?php endwhile; ?>
        </div>
        <?php the_posts_navigation(); ?>
        <?php else: ?>
        <p class="text-muted">Keine Beiträge gefunden.</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer();
