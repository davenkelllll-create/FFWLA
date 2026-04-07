<?php get_header(); ?>

<?php while (have_posts()): the_post(); ?>

<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="<?= esc_url(home_url('/')) ?>">Startseite</a> / <?php the_title(); ?>
        </nav>
        <h1><?php the_title(); ?></h1>
    </div>
</div>

<section class="fw-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('fw-hero', ['class'=>'img-fluid rounded mb-4']); ?>
                <?php endif; ?>
                <div style="line-height:1.8;">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer();
