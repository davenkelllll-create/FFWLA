<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Emergency Alert (Custom Field on Frontpage or Options) -->
<?php if ($alert = get_option('fw_alert_message')): ?>
<div class="alert-strip">
    <div class="container">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= wp_kses_post($alert) ?>
    </div>
</div>
<?php endif; ?>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg fw-navbar sticky-top">
    <div class="container">

        <!-- Logo + Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= esc_url(home_url('/')) ?>">
            <?php if (has_custom_logo()):
                the_custom_logo();
            else: ?>
            <div class="fw-logo-circle">
                <i class="bi bi-shield-fill-exclamation"></i>
            </div>
            <?php endif; ?>
            <div class="fw-brand-text">
                <span class="fw-brand-name"><?php bloginfo('name'); ?></span>
                <span class="fw-brand-sub">Freiwillige Feuerwehr</span>
            </div>
        </a>

        <!-- Hamburger -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false"
                aria-label="<?php esc_attr_e('Navigation öffnen', 'feuerwehr-lgsb'); ?>">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="mainNav">
            <?php
            wp_nav_menu([
                'theme_location'  => 'primary',
                'container'       => false,
                'menu_class'      => 'navbar-nav ms-auto align-items-lg-center',
                'fallback_cb'     => 'fw_fallback_nav',
                'walker'          => new FW_Bootstrap_Nav_Walker(),
            ]);
            ?>
            <a href="tel:112" class="fw-notruf ms-lg-3">
                <i class="bi bi-telephone-fill"></i> Notruf 112
            </a>
        </div>
    </div>
</nav>

<?php
// Fallback nav wenn noch kein Menü zugewiesen
function fw_fallback_nav(): void {
    echo '<ul class="navbar-nav ms-auto align-items-lg-center">';
    $pages = [
        home_url('/')                   => 'Startseite',
        get_post_type_archive_link('post') => 'Nachrichten',
        get_page_link(get_page_by_path('kalender')) => 'Kalender',
        get_page_link(get_page_by_path('galerie'))  => 'Galerie',
        get_page_link(get_page_by_path('kontakt'))  => 'Kontakt',
    ];
    foreach ($pages as $url => $label) {
        if ($url) echo '<li class="nav-item"><a class="nav-link" href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}
