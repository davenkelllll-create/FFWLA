<?php
require_once __DIR__ . '/functions.php';
$config    = loadConfig();
$siteName  = $config['site_name']  ?? 'Freiwillige Feuerwehr Langensendelbach';
$siteEmail = $config['site_email'] ?? '';
$sitePhone = $config['site_phone'] ?? '';
$siteAddr  = $config['site_address'] ?? '';
?>
<footer class="fw-footer mt-auto">
    <div class="container">
        <div class="row g-4">
            <!-- Brand column -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="fw-logo-circle fw-logo-circle--sm">
                        <i class="bi bi-shield-fill-exclamation"></i>
                    </div>
                    <span class="fw-footer-brand"><?= h($siteName) ?></span>
                </div>
                <p class="text-muted small">Im Dienst für die Gemeinschaft – ehrenamtlich und engagiert.</p>
                <div class="mt-3">
                    <a href="tel:112" class="btn btn-danger btn-sm me-2">
                        <i class="bi bi-telephone-fill me-1"></i>Notruf 112
                    </a>
                </div>
            </div>

            <!-- Quick links -->
            <div class="col-sm-6 col-lg-2">
                <h6 class="fw-footer-heading">Schnelllinks</h6>
                <ul class="list-unstyled fw-footer-links">
                    <li><a href="/">Startseite</a></li>
                    <li><a href="/nachrichten.php">Nachrichten</a></li>
                    <li><a href="/kalender.php">Kalender</a></li>
                    <li><a href="/galerie.php">Galerie</a></li>
                    <li><a href="/formulare.php">Formulare</a></li>
                </ul>
            </div>

            <!-- More links -->
            <div class="col-sm-6 col-lg-2">
                <h6 class="fw-footer-heading">Informationen</h6>
                <ul class="list-unstyled fw-footer-links">
                    <li><a href="/ueber-uns.php">Über uns</a></li>
                    <li><a href="/kontakt.php">Kontakt</a></li>
                    <li><a href="/impressum.php">Impressum</a></li>
                    <li><a href="/datenschutz.php">Datenschutz</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-4">
                <h6 class="fw-footer-heading">Kontakt</h6>
                <ul class="list-unstyled fw-footer-contact">
                    <?php if ($siteAddr): ?>
                    <li>
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        <?= h($siteAddr) ?>
                    </li>
                    <?php endif; ?>
                    <?php if ($sitePhone): ?>
                    <li>
                        <i class="bi bi-telephone-fill me-2"></i>
                        <a href="tel:<?= h(preg_replace('/\s+/', '', $sitePhone)) ?>"><?= h($sitePhone) ?></a>
                    </li>
                    <?php endif; ?>
                    <?php if ($siteEmail): ?>
                    <li>
                        <i class="bi bi-envelope-fill me-2"></i>
                        <a href="mailto:<?= h($siteEmail) ?>"><?= h($siteEmail) ?></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <hr class="fw-footer-divider">

        <div class="row align-items-center">
            <div class="col-md-6 text-muted small">
                &copy; <?= date('Y') ?> <?= h($siteName) ?> – Alle Rechte vorbehalten.
            </div>
            <div class="col-md-6 text-md-end text-muted small">
                <a href="/impressum.php" class="text-muted me-3">Impressum</a>
                <a href="/datenschutz.php" class="text-muted">Datenschutzerklärung</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if (!empty($extraJs)): foreach ($extraJs as $js): ?>
<script src="<?= h($js) ?>"></script>
<?php endforeach; endif; ?>
<script src="/js/main.js"></script>
</body>
</html>
