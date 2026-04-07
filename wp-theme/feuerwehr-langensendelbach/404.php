<?php
http_response_code(404);
get_header(); ?>

<section class="fw-404-section" style="position:relative;">
    <div class="fw-404-code">404</div>
    <div class="fw-404-content container">
        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4"
             style="width:100px;height:100px;background:var(--fw-red-pale);">
            <i class="bi bi-sign-stop-fill text-danger" style="font-size:2.8rem;"></i>
        </div>
        <h1 class="fw-bold mb-2">Seite nicht gefunden</h1>
        <p class="text-muted mb-4" style="max-width:420px;margin:0 auto 1.5rem;">
            Die gesuchte Seite existiert nicht oder wurde verschoben.
        </p>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="<?= esc_url(home_url('/')) ?>" class="btn-fw">
                <i class="bi bi-house-fill me-1"></i>Zur Startseite
            </a>
            <a href="<?= esc_url(get_post_type_archive_link('post')) ?>" class="btn btn-outline-danger">
                <i class="bi bi-newspaper me-1"></i>Nachrichten
            </a>
        </div>
    </div>
</section>

<?php get_footer();
