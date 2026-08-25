<?php
require_once __DIR__ . '/functions.php';
$config    = loadConfig();
$siteName  = $config['site_name']  ?? 'Freiwillige Feuerwehr Langensendelbach';
$siteEmail = $config['site_email'] ?? '';
$sitePhone = $config['site_phone'] ?? '';
$siteAddr  = $config['site_address'] ?? '';

// Social Media – Platzhalter, bitte in config.json eintragen
$socialFacebook  = $config['social_facebook']  ?? '';
$socialInstagram = $config['social_instagram'] ?? '';
$socialYoutube   = $config['social_youtube']   ?? '';
?>
</main><!-- /#main-content -->

<footer class="fw-footer mt-auto">
    <div class="container">
        <div class="row g-4">
            <!-- Brand column -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="fw-logo-circle fw-logo-circle--sm">
                        <img src="/images/branding/wappen-voll.png" alt="Wappen FF Langensendelbach">
                    </div>
                    <span class="fw-footer-brand"><?= h($siteName) ?></span>
                </div>
                <p class="text-muted small">Im Dienst für die Gemeinschaft – ehrenamtlich und engagiert.</p>
                <div class="mt-3">
                    <a href="tel:112" class="btn btn-danger btn-sm">
                        <i class="bi bi-telephone-fill me-1"></i>Notruf 112
                    </a>
                </div>
                <!-- Social Media -->
                <?php if ($socialFacebook || $socialInstagram || $socialYoutube): ?>
                <div class="fw-social-links">
                    <?php if ($socialFacebook): ?>
                    <a href="<?= h($socialFacebook) ?>" target="_blank" rel="noopener" class="fw-social-btn" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($socialInstagram): ?>
                    <a href="<?= h($socialInstagram) ?>" target="_blank" rel="noopener" class="fw-social-btn" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($socialYoutube): ?>
                    <a href="<?= h($socialYoutube) ?>" target="_blank" rel="noopener" class="fw-social-btn" title="YouTube">
                        <i class="bi bi-youtube"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Quick links -->
            <div class="col-sm-6 col-lg-2">
                <h6 class="fw-footer-heading">Schnelllinks</h6>
                <ul class="list-unstyled fw-footer-links">
                    <li><a href="/">Startseite</a></li>
                    <li><a href="/nachrichten.php">Nachrichten</a></li>
                    <li><a href="/kalender.php">Kalender</a></li>
                    <li><a href="/formulare.php">Formulare</a></li>
                    <li><a href="/jugendfeuerwehr.php">Jugendfeuerwehr</a></li>
                </ul>
            </div>

            <!-- More links -->
            <div class="col-sm-6 col-lg-2">
                <h6 class="fw-footer-heading">Informationen</h6>
                <ul class="list-unstyled fw-footer-links">
                    <li><a href="/ueber-uns.php">Über uns</a></li>
                    <li><a href="/buergerecke.php">Bürgerecke</a></li>
                    <li><a href="/kontakt.php">Kontakt</a></li>
                    <li><a href="/links.php">Links &amp; Partner</a></li>
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
                <!-- KBI Verweis -->
                <div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.1);">
                    <p class="text-muted small mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;font-weight:700;">Übergeordnete Stellen</p>
                    <a href="https://www.landkreis-forchheim.de/Landratsamt/Fachbereiche/Brandschutz-und-Rettungswesen/" target="_blank" rel="noopener"
                       class="d-flex align-items-center gap-2 text-muted small" style="text-decoration:none;">
                        <i class="bi bi-shield-fill text-danger"></i>
                        Kreisbrandinspektion Forchheim
                        <i class="bi bi-box-arrow-up-right ms-auto" style="font-size:.7rem;"></i>
                    </a>
                    <a href="https://www.lfv-bayern.de" target="_blank" rel="noopener"
                       class="d-flex align-items-center gap-2 text-muted small mt-1" style="text-decoration:none;">
                        <i class="bi bi-shield-fill text-danger"></i>
                        Landesfeuerwehrverband Bayern
                        <i class="bi bi-box-arrow-up-right ms-auto" style="font-size:.7rem;"></i>
                    </a>
                </div>
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

<!-- Back-to-top (alle Seiten) -->
<button id="backToTop" type="button" aria-label="Nach oben scrollen" title="Nach oben">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if (!empty($extraJs)): foreach ($extraJs as $js): ?>
<script src="<?= h($js) ?>"></script>
<?php endforeach; endif; ?>
<script src="/js/main.js"></script>
<script src="/js/cookie-banner.js"></script>
</body>
</html>
