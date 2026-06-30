<?php
/**
 * Template Name: Formulare
 *
 * Die zum Download angebotenen PDFs werden über die WordPress-Mediathek
 * verwaltet: Jede hochgeladene PDF-Datei (MIME application/pdf) erscheint
 * automatisch in der Liste. Titel = Mediathek-Titel, Beschreibung = Caption.
 */
get_header();

$pdfs = new WP_Query([
    'post_type'      => 'attachment',
    'post_status'    => 'inherit',
    'post_mime_type' => 'application/pdf',
    'posts_per_page' => 100,
    'orderby'        => 'title',
    'order'          => 'ASC',
]);
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="<?= esc_url(home_url('/')) ?>">Startseite</a> / Formulare
        </nav>
        <h1><i class="bi bi-file-earmark-arrow-down me-2 text-fw-red"></i>Formulare &amp; Anträge</h1>
        <p class="text-muted mb-0">Formulare herunterladen, ausdrucken, ausfüllen, unterschreiben und per Post oder E-Mail einsenden</p>
    </div>
</div>

<section class="fw-section">
    <div class="container">

        <!-- Info box: handschriftliche Unterschrift erforderlich -->
        <div class="d-flex gap-3 p-3 mb-4 rounded" style="background:var(--fw-red-pale);border-left:4px solid var(--fw-red);">
            <i class="bi bi-info-circle-fill text-danger fs-5 flex-shrink-0 mt-1"></i>
            <div>
                <strong>Hinweis:</strong> Alle Formulare müssen ausgedruckt, handschriftlich ausgefüllt und
                <strong>unterschrieben</strong> werden. Das ausgefüllte Formular bitte per Post oder E-Mail
                an uns einsenden. Bei Fragen stehen wir gern zur Verfügung.
            </div>
        </div>

        <?php if (!$pdfs->have_posts()): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-file-earmark-x fs-1 d-block mb-3 opacity-25"></i>
            <p>Noch keine Formulare vorhanden.</p>
            <p class="small">Formulare (PDF) werden über die <strong>Mediathek</strong> verwaltet und erscheinen hier automatisch.</p>
        </div>
        <?php else: ?>
        <div class="d-flex flex-column gap-3">
            <?php while ($pdfs->have_posts()): $pdfs->the_post();
                $url   = wp_get_attachment_url(get_the_ID());
                $title = get_the_title() ?: basename((string)$url);
                $desc  = get_the_excerpt(); // Caption / Beschreibung
                $date  = get_the_date('Y-m');
            ?>
            <div class="fw-formular-row">
                <div class="fw-formular-icon">
                    <i class="bi bi-file-earmark-pdf"></i>
                </div>
                <div class="fw-formular-info">
                    <div class="fw-formular-title"><?= esc_html($title) ?></div>
                    <?php if ($desc): ?>
                    <div class="fw-formular-desc"><?= esc_html($desc) ?></div>
                    <?php endif; ?>
                    <div class="mt-1">
                        <span class="fw-badge badge-secondary">PDF</span>
                        <?php if ($date): ?>
                        <span class="text-muted small ms-2">Stand: <?= esc_html($date) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <?php if ($url): ?>
                    <a href="<?= esc_url($url) ?>" download class="btn btn-danger btn-sm">
                        <i class="bi bi-download me-1"></i>Herunterladen
                    </a>
                    <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled title="Datei noch nicht verfügbar">
                        <i class="bi bi-download me-1"></i>Bald verfügbar
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php endif; ?>

        <!-- Contact box -->
        <div class="mt-5 p-4 rounded" style="background:var(--fw-gray-100);">
            <h5 class="mb-2"><i class="bi bi-envelope me-2 text-danger"></i>Formular einsenden</h5>
            <p class="mb-2 text-muted">
                Ausgefüllte und unterschriebene Formulare senden Sie bitte an:
            </p>
            <ul class="list-unstyled mb-0">
                <?php if ($addr = fw_option('fw_address')): ?>
                <li class="mb-1"><i class="bi bi-geo-alt me-2 text-danger"></i><?= $addr ?></li>
                <?php endif; ?>
                <?php if ($email = get_theme_mod('fw_email')): ?>
                <li><i class="bi bi-envelope me-2 text-danger"></i>
                    <a href="mailto:<?= esc_attr($email) ?>"><?= esc_html($email) ?></a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</section>

<?php get_footer();
