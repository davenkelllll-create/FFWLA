<?php
/**
 * Template Name: Kontakt
 */
get_header();

$address = fw_option('fw_address');
$phone   = fw_option('fw_phone');
$email   = get_theme_mod('fw_email', '');
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="<?= esc_url(home_url('/')) ?>">Startseite</a> / Kontakt
        </nav>
        <h1><i class="bi bi-envelope me-2 text-fw-red"></i>Kontakt</h1>
        <p class="text-muted mb-0">So erreichen Sie uns</p>
    </div>
</div>

<section class="fw-section">
    <div class="container">
        <div class="row g-5">

            <!-- Contact info -->
            <div class="col-lg-5">
                <h2 class="fw-section-title mb-4">Kontaktdaten</h2>

                <div class="d-flex gap-3 mb-3 p-3 rounded" style="background:var(--fw-red-pale);">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4 flex-shrink-0"></i>
                    <div>
                        <strong>Im Notfall: Notruf 112</strong><br>
                        <span class="text-muted small">Bitte ausschließlich im Notfall anrufen</span>
                    </div>
                </div>

                <ul class="list-unstyled">
                    <?php if ($address): ?>
                    <li class="d-flex gap-3 mb-3">
                        <div style="width:40px;height:40px;background:var(--fw-red);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-geo-alt-fill text-white"></i>
                        </div>
                        <div>
                            <strong class="d-block">Adresse</strong>
                            <span class="text-muted"><?= nl2br($address) ?></span>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if ($phone): ?>
                    <li class="d-flex gap-3 mb-3">
                        <div style="width:40px;height:40px;background:var(--fw-red);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-telephone-fill text-white"></i>
                        </div>
                        <div>
                            <strong class="d-block">Telefon</strong>
                            <a href="tel:<?= esc_attr(preg_replace('/\s+/', '', $phone)) ?>" class="text-muted">
                                <?= $phone ?>
                            </a>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if ($email): ?>
                    <li class="d-flex gap-3 mb-3">
                        <div style="width:40px;height:40px;background:var(--fw-red);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-envelope-fill text-white"></i>
                        </div>
                        <div>
                            <strong class="d-block">E-Mail</strong>
                            <a href="mailto:<?= esc_attr($email) ?>" class="text-muted">
                                <?= esc_html($email) ?>
                            </a>
                        </div>
                    </li>
                    <?php endif; ?>

                    <li class="d-flex gap-3">
                        <div style="width:40px;height:40px;background:var(--fw-red);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-clock-fill text-white"></i>
                        </div>
                        <div>
                            <strong class="d-block">Erreichbarkeit</strong>
                            <span class="text-muted small">
                                Monatliche Übungen: siehe <a href="<?= esc_url(home_url('/kalender')) ?>">Kalender</a><br>
                                Im Notfall: Notruf 112 (24/7)
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Map -->
            <div class="col-lg-7">
                <h2 class="fw-section-title mb-4">Anfahrt</h2>
                <div class="rounded overflow-hidden" style="height:400px;border:1px solid var(--fw-gray-200);">
                    <!-- OpenStreetMap embed – no API key needed -->
                    <iframe
                        title="Karte: Gerätehaus FF Langensendelbach"
                        width="100%"
                        height="100%"
                        frameborder="0"
                        loading="lazy"
                        src="https://www.openstreetmap.org/export/embed.html?bbox=11.030,49.638,11.071,49.660&amp;layer=mapnik&amp;marker=49.6489,11.0506"
                        style="border:none;">
                    </iframe>
                </div>
                <p class="text-muted small mt-2">
                    <a href="https://www.openstreetmap.org/?mlat=49.6489&amp;mlon=11.0506#map=16/49.6489/11.0506" target="_blank" rel="noopener">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Größere Karte öffnen
                    </a>
                </p>
            </div>

        </div>
    </div>
</section>

<?php get_footer();
