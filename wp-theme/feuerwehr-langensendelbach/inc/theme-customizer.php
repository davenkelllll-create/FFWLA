<?php
/**
 * Theme Customizer – Kontaktdaten, Social Media, Feuerwehr-Einstellungen
 */
function fw_customizer_register(WP_Customize_Manager $wp_customize): void {

    // ── Panel: Feuerwehr-Einstellungen ────────────────────────────────────────
    $wp_customize->add_panel('fw_settings', [
        'title'    => 'Feuerwehr-Einstellungen',
        'priority' => 30,
    ]);

    // ── Section: Kontakt ──────────────────────────────────────────────────────
    $wp_customize->add_section('fw_contact', [
        'title' => 'Kontaktdaten',
        'panel' => 'fw_settings',
    ]);
    foreach ([
        'fw_phone'   => ['label' => 'Telefonnummer',  'type' => 'text',  'default' => '+49 9126 XXXXX'],
        'fw_email'   => ['label' => 'E-Mail-Adresse', 'type' => 'email', 'default' => 'info@feuerwehr-langensendelbach.de'],
        'fw_address' => ['label' => 'Adresse',        'type' => 'text',  'default' => 'Gerätehaus, Musterstraße 1, 91094 Langensendelbach'],
        'fw_founded' => ['label' => 'Gründungsjahr',  'type' => 'number','default' => '1952'],
        'fw_members' => ['label' => 'Aktive Mitglieder', 'type' => 'number', 'default' => '45'],
    ] as $id => $args) {
        $wp_customize->add_setting($id, ['default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh']);
        $wp_customize->add_control($id, ['label' => $args['label'], 'section' => 'fw_contact', 'type' => $args['type']]);
    }

    // ── Section: Social Media ─────────────────────────────────────────────────
    $wp_customize->add_section('fw_social', [
        'title' => 'Social Media',
        'panel' => 'fw_settings',
    ]);
    foreach ([
        'fw_facebook'  => 'Facebook-URL  (z.B. https://facebook.com/fflgsb)',
        'fw_instagram' => 'Instagram-URL (z.B. https://instagram.com/fflgsb)',
        'fw_youtube'   => 'YouTube-URL',
    ] as $id => $label) {
        $wp_customize->add_setting($id, ['default' => '', 'sanitize_callback' => 'esc_url_raw', 'transport' => 'refresh']);
        $wp_customize->add_control($id, ['label' => $label, 'section' => 'fw_social', 'type' => 'url']);
    }

    // ── Section: Hero-Carousel Texte ──────────────────────────────────────────
    $wp_customize->add_section('fw_hero', [
        'title' => 'Hero-Carousel Texte',
        'panel' => 'fw_settings',
    ]);
    for ($i = 1; $i <= 3; $i++) {
        $wp_customize->add_setting("fw_slide{$i}_title",   ['default' => '', 'sanitize_callback' => 'wp_kses_post',       'transport' => 'refresh']);
        $wp_customize->add_setting("fw_slide{$i}_text",    ['default' => '', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh']);
        $wp_customize->add_control("fw_slide{$i}_title",   ['label' => "Slide {$i} – Titel",    'section' => 'fw_hero', 'type' => 'text']);
        $wp_customize->add_control("fw_slide{$i}_text",    ['label' => "Slide {$i} – Untertitel",'section' => 'fw_hero', 'type' => 'text']);
    }

    // ── Section: KBI / Partner-Links ─────────────────────────────────────────
    $wp_customize->add_section('fw_links', [
        'title' => 'Partner-Links',
        'panel' => 'fw_settings',
    ]);
    $wp_customize->add_setting('fw_kbi_url',  ['default' => 'https://www.erlangen-hoechstadt.de/buergerservice/feuerwehr/', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_setting('fw_kbi_name', ['default' => 'Kreisbrandinspektion Erlangen-Höchstadt', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('fw_kbi_url',  ['label' => 'KBI-Website URL',  'section' => 'fw_links', 'type' => 'url']);
    $wp_customize->add_control('fw_kbi_name', ['label' => 'KBI Name',         'section' => 'fw_links', 'type' => 'text']);
}
add_action('customize_register', 'fw_customizer_register');

// Helfer-Funktion: Theme-Option sicher auslesen
function fw_option(string $key, string $fallback = ''): string {
    return esc_html(get_theme_mod($key, $fallback));
}
function fw_option_url(string $key, string $fallback = ''): string {
    return esc_url(get_theme_mod($key, $fallback));
}
