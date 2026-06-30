<?php
/**
 * Custom Post Types & Taxonomies
 * - fw_termin:       Veranstaltungen / Termine (für FullCalendar)
 * - fw_galerie:      Galerie-Alben
 * - fw_nachrichtentyp: Taxonomy für Posts (Einsatz / Übung / Veranstaltung / Presse)
 */

// ── Taxonomy: Nachrichtentyp (für Standard-Posts) ────────────────────────────
function fw_register_nachrichtentyp(): void {
    register_taxonomy('fw_nachrichtentyp', 'post', [
        'labels' => [
            'name'          => 'Nachrichtentypen',
            'singular_name' => 'Nachrichtentyp',
            'add_new_item'  => 'Neuen Typ hinzufügen',
            'edit_item'     => 'Typ bearbeiten',
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'nachrichtentyp'],
    ]);
}
add_action('init', 'fw_register_nachrichtentyp');

// ── CPT: Termin ───────────────────────────────────────────────────────────────
function fw_register_termin_cpt(): void {
    register_post_type('fw_termin', [
        'labels' => [
            'name'               => 'Termine',
            'singular_name'      => 'Termin',
            'add_new'            => 'Neuer Termin',
            'add_new_item'       => 'Neuen Termin hinzufügen',
            'edit_item'          => 'Termin bearbeiten',
            'menu_name'          => 'Termine',
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'menu_icon'         => 'dashicons-calendar-alt',
        'menu_position'     => 5,
        'supports'          => ['title', 'editor', 'custom-fields'],
        'rewrite'           => ['slug' => 'termine'],
        'has_archive'       => true,
    ]);
}
add_action('init', 'fw_register_termin_cpt');

// Meta Box: Termin-Details
function fw_termin_meta_box(): void {
    add_meta_box('fw_termin_details', 'Termin-Details', 'fw_termin_meta_box_html', 'fw_termin', 'normal', 'high');
}
add_action('add_meta_boxes', 'fw_termin_meta_box');

function fw_termin_meta_box_html(WP_Post $post): void {
    wp_nonce_field('fw_termin_save', 'fw_termin_nonce');
    $start    = get_post_meta($post->ID, 'fw_termin_start',    true);
    $end      = get_post_meta($post->ID, 'fw_termin_end',      true);
    $location = get_post_meta($post->ID, 'fw_termin_location', true);
    $color    = get_post_meta($post->ID, 'fw_termin_color',    true) ?: '#CC0000';
    $public   = get_post_meta($post->ID, 'fw_termin_public',   true) ?: '1';
    $cat      = get_post_meta($post->ID, 'fw_termin_category', true);

    // Stored as MySQL DATETIME ('Y-m-d H:i:s'); the HTML5 datetime-local control
    // expects an ISO 'Y-m-d\TH:i' value, so convert the space back to a 'T'.
    $start_input = $start ? str_replace(' ', 'T', $start) : '';
    $end_input   = $end   ? str_replace(' ', 'T', $end)   : '';
    ?>
    <table class="form-table" style="width:100%">
        <tr>
            <th><label>Start *</label></th>
            <td><input type="datetime-local" name="fw_termin_start" value="<?= esc_attr($start_input) ?>" style="width:260px" required></td>
        </tr>
        <tr>
            <th><label>Ende</label></th>
            <td><input type="datetime-local" name="fw_termin_end" value="<?= esc_attr($end_input) ?>" style="width:260px"></td>
        </tr>
        <tr>
            <th><label>Ort</label></th>
            <td><input type="text" name="fw_termin_location" value="<?= esc_attr($location) ?>" style="width:100%" placeholder="z.B. Gerätehaus Langensendelbach"></td>
        </tr>
        <tr>
            <th><label>Kategorie</label></th>
            <td>
                <select name="fw_termin_category" style="width:260px">
                    <?php foreach (['uebung'=>'Übung','veranstaltung'=>'Veranstaltung','jugend'=>'Jugendfeuerwehr','ausbildung'=>'Ausbildung'] as $v=>$l): ?>
                    <option value="<?= $v ?>" <?= selected($cat, $v, false) ?>><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label>Kalenderfarbe</label></th>
            <td>
                <select name="fw_termin_color" style="width:260px">
                    <?php foreach (['#CC0000'=>'Rot (Übung)','#1a7a3c'=>'Grün (Veranstaltung)','#FF8C00'=>'Orange (Jugend)','#1a4a8a'=>'Blau (Ausbildung)','#6c757d'=>'Grau'] as $v=>$l): ?>
                    <option value="<?= $v ?>" <?= selected($color, $v, false) ?>><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label>Öffentlich</label></th>
            <td><input type="checkbox" name="fw_termin_public" value="1" <?= checked($public, '1', false) ?>> Im Kalender anzeigen</td>
        </tr>
    </table>
    <?php
}

/**
 * Normalize a datetime-local value ("Y-m-d\TH:i") to MySQL DATETIME ("Y-m-d H:i:s").
 * Returns an empty string for an empty input so optional fields stay empty.
 */
function fw_normalize_datetime(string $val): string {
    $val = trim($val);
    if ($val === '') return '';
    $val = str_replace('T', ' ', $val);
    // Append seconds if the control only supplied "Y-m-d H:i".
    if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $val)) {
        $val .= ':00';
    }
    return $val;
}

function fw_termin_save_meta(int $post_id): void {
    if (!isset($_POST['fw_termin_nonce']) || !wp_verify_nonce($_POST['fw_termin_nonce'], 'fw_termin_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    foreach (['fw_termin_location','fw_termin_color','fw_termin_category'] as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Datetime fields: the datetime-local control yields "YYYY-MM-DDTHH:MM".
    // Store as MySQL DATETIME ("Y-m-d H:i:s") so meta_query CAST(... AS DATETIME)
    // works reliably (the 'T' separator is not reliably cast by MySQL).
    foreach (['fw_termin_start','fw_termin_end'] as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, fw_normalize_datetime(sanitize_text_field($_POST[$field])));
        }
    }
    update_post_meta($post_id, 'fw_termin_public', isset($_POST['fw_termin_public']) ? '1' : '0');
}
add_action('save_post_fw_termin', 'fw_termin_save_meta');

// ── CPT: Galerie-Album ────────────────────────────────────────────────────────
function fw_register_galerie_cpt(): void {
    register_post_type('fw_galerie', [
        'labels' => [
            'name'          => 'Galerie-Alben',
            'singular_name' => 'Galerie-Album',
            'add_new'       => 'Neues Album',
            'add_new_item'  => 'Neues Album hinzufügen',
            'edit_item'     => 'Album bearbeiten',
            'menu_name'     => 'Galerie',
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-images-alt2',
        'menu_position' => 6,
        'supports'      => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'rewrite'       => ['slug' => 'galerie'],
        'has_archive'   => true,
    ]);
}
add_action('init', 'fw_register_galerie_cpt');

// Taxonomy: Galerie-Kategorie
function fw_register_galerie_kat(): void {
    register_taxonomy('fw_galerie_kat', 'fw_galerie', [
        'labels'       => ['name' => 'Album-Kategorien', 'singular_name' => 'Album-Kategorie'],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'galerie-kategorie'],
    ]);
}
add_action('init', 'fw_register_galerie_kat');
