<?php
/**
 * Theme Functions – Freiwillige Feuerwehr Langensendelbach
 */

define('FW_THEME_VERSION', '1.0.0');
define('FW_THEME_URI', get_template_directory_uri());

// ── Theme Support ────────────────────────────────────────────────────────────
function fw_theme_setup(): void {
    load_theme_textdomain('feuerwehr-lgsb', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 80,
        'flex-width'  => true,
        'flex-height' => true,
    ]);
    add_theme_support('custom-header', [
        'default-image' => '',
        'width'         => 1920,
        'height'        => 800,
    ]);

    // Image sizes
    add_image_size('fw-hero',    1920, 800,  true);
    add_image_size('fw-card',    600,  400,  true);
    add_image_size('fw-thumb',   400,  300,  true);
    add_image_size('fw-gallery', 1200, 900,  false);

    // Navigation menus
    register_nav_menus([
        'primary'  => __('Hauptnavigation', 'feuerwehr-lgsb'),
        'footer'   => __('Footer-Navigation', 'feuerwehr-lgsb'),
    ]);
}
add_action('after_setup_theme', 'fw_theme_setup');

// ── Enqueue Scripts & Styles ─────────────────────────────────────────────────
function fw_enqueue_assets(): void {
    // Bootstrap 5
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', [], '5.3.3');
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', [], '1.11.3');

    // Theme stylesheet
    wp_enqueue_style('fw-style', FW_THEME_URI . '/assets/css/style.css', ['bootstrap'], FW_THEME_VERSION);

    // GLightbox (gallery pages)
    if (is_page_template('page-templates/template-galerie.php') || is_singular('fw_galerie')) {
        wp_enqueue_style('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css', [], null);
        wp_enqueue_script('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js', [], null, true);
        wp_enqueue_script('fw-galerie', FW_THEME_URI . '/assets/js/galerie.js', ['glightbox'], FW_THEME_VERSION, true);
    }

    // FullCalendar (Kalender page)
    if (is_page_template('page-templates/template-kalender.php')) {
        wp_enqueue_style('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.css', [], '6.1.14');
        wp_enqueue_script('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js', [], '6.1.14', true);
        wp_enqueue_script('fw-kalender', FW_THEME_URI . '/assets/js/kalender.js', ['fullcalendar'], FW_THEME_VERSION, true);

        // Pass events to JS
        wp_localize_script('fw-kalender', 'FW_EVENTS', fw_get_termine_for_calendar());
    }

    // Bootstrap JS
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], '5.3.3', true);

    // Theme JS
    wp_enqueue_script('fw-main', FW_THEME_URI . '/assets/js/main.js', ['bootstrap'], FW_THEME_VERSION, true);
    wp_enqueue_script('fw-cookie', FW_THEME_URI . '/assets/js/cookie-banner.js', [], FW_THEME_VERSION, true);

    // Pass theme data to JS
    wp_localize_script('fw-main', 'FW_DATA', [
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'homeUrl'   => home_url('/'),
        'themeUri'  => FW_THEME_URI,
    ]);
}
add_action('wp_enqueue_scripts', 'fw_enqueue_assets');

// ── Custom Post Types ─────────────────────────────────────────────────────────
require_once get_template_directory() . '/inc/custom-post-types.php';

// ── Theme Customizer ─────────────────────────────────────────────────────────
require_once get_template_directory() . '/inc/theme-customizer.php';

// ── Widget Areas ─────────────────────────────────────────────────────────────
function fw_register_sidebars(): void {
    register_sidebar([
        'name'          => __('Nachrichten-Sidebar', 'feuerwehr-lgsb'),
        'id'            => 'sidebar-news',
        'description'   => __('Widget-Bereich für die Nachrichtenseite (z.B. Termine-Widget)', 'feuerwehr-lgsb'),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h6 class="fw-footer-heading text-dark">',
        'after_title'   => '</h6>',
    ]);
    register_sidebar([
        'name'          => __('Footer Widget-Bereich', 'feuerwehr-lgsb'),
        'id'            => 'sidebar-footer',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h6 class="fw-footer-heading">',
        'after_title'   => '</h6>',
    ]);
}
add_action('widgets_init', 'fw_register_sidebars');

// ── Helper: News type badge ───────────────────────────────────────────────────
function fw_get_type_badge(int $post_id = 0): string {
    $terms = get_the_terms($post_id ?: get_the_ID(), 'fw_nachrichtentyp');
    if (empty($terms) || is_wp_error($terms)) return '';
    $term  = $terms[0];
    $map   = ['einsatz' => 'badge-einsatz', 'uebung' => 'badge-uebung', 'veranstaltung' => 'badge-veranstaltung', 'presse' => 'badge-secondary'];
    $class = $map[$term->slug] ?? 'badge-secondary';
    return '<span class="fw-badge ' . esc_attr($class) . '">' . esc_html($term->name) . '</span>';
}

// ── Helper: Termine für FullCalendar ─────────────────────────────────────────
function fw_get_termine_for_calendar(): array {
    $events = [];
    $query  = new WP_Query([
        'post_type'      => 'fw_termin',
        'posts_per_page' => 100,
        'post_status'    => 'publish',
        'meta_query'     => [['key' => 'fw_termin_public', 'value' => '1']],
    ]);
    foreach ($query->posts as $post) {
        $start    = get_post_meta($post->ID, 'fw_termin_start', true);
        $end      = get_post_meta($post->ID, 'fw_termin_end', true);
        $location = get_post_meta($post->ID, 'fw_termin_location', true);
        $color    = get_post_meta($post->ID, 'fw_termin_color', true) ?: '#CC0000';
        // Stored as MySQL DATETIME ('Y-m-d H:i:s'); FullCalendar expects ISO8601
        // with a 'T' separator.
        $start    = $start ? str_replace(' ', 'T', $start) : '';
        $end      = $end   ? str_replace(' ', 'T', $end)   : '';
        $events[] = [
            'id'            => $post->ID,
            'title'         => get_the_title($post),
            'start'         => $start,
            'end'           => $end ?: null,
            'color'         => $color,
            'extendedProps' => [
                'location'    => $location,
                'description' => wp_strip_all_tags($post->post_content),
            ],
        ];
    }
    wp_reset_postdata();
    return $events;
}

// ── Helper: Nav walker für Bootstrap ─────────────────────────────────────────
require_once get_template_directory() . '/inc/bootstrap-nav-walker.php';

// ── Excerpt length ────────────────────────────────────────────────────────────
add_filter('excerpt_length', fn() => 25);
add_filter('excerpt_more',   fn() => '…');

// ── Remove admin bar for non-admins on frontend ───────────────────────────────
add_filter('show_admin_bar', fn($show) => current_user_can('administrator') ? $show : false);
