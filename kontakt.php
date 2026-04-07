<?php
require_once 'includes/functions.php';
$pageTitle = 'Kontakt';
$pageDescription = 'Kontaktdaten der Freiwilligen Feuerwehr Langensendelbach – Adresse, Telefon und Anfahrt.';

$config = loadConfig();
include 'includes/header.php';
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="/">Startseite</a> / Kontakt
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
                    <?php if (!empty($config['site_address'])): ?>
                    <li class="d-flex gap-3 mb-3">
                        <div style="width:40px;height:40px;background:var(--fw-red);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-geo-alt-fill text-white"></i>
                        </div>
                        <div>
                            <strong class="d-block">Adresse</strong>
                            <span class="text-muted"><?= nl2br(h($config['site_address'])) ?></span>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($config['site_phone'])): ?>
                    <li class="d-flex gap-3 mb-3">
                        <div style="width:40px;height:40px;background:var(--fw-red);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-telephone-fill text-white"></i>
                        </div>
                        <div>
                            <strong class="d-block">Telefon</strong>
                            <a href="tel:<?= h(preg_replace('/\s+/', '', $config['site_phone'])) ?>" class="text-muted">
                                <?= h($config['site_phone']) ?>
                            </a>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($config['site_email'])): ?>
                    <li class="d-flex gap-3 mb-3">
                        <div style="width:40px;height:40px;background:var(--fw-red);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-envelope-fill text-white"></i>
                        </div>
                        <div>
                            <strong class="d-block">E-Mail</strong>
                            <a href="mailto:<?= h($config['site_email']) ?>" class="text-muted">
                                <?= h($config['site_email']) ?>
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
                                Monatliche Übungen: siehe <a href="/kalender.php">Kalender</a><br>
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
                        src="https://www.openstreetmap.org/export/embed.html?bbox=10.985,49.569,11.065,49.609&amp;layer=mapnik&amp;marker=49.589,11.025"
                        style="border:none;">
                    </iframe>
                </div>
                <p class="text-muted small mt-2">
                    <a href="https://www.openstreetmap.org/?mlat=49.589&mlon=11.025#map=16/49.589/11.025" target="_blank" rel="noopener">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Größere Karte öffnen
                    </a>
                </p>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
