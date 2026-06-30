<footer class="fw-footer mt-auto">
    <div class="container">
        <div class="row g-4">

            <!-- Brand -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="fw-logo-circle fw-logo-circle--sm">
                        <i class="bi bi-shield-fill-exclamation"></i>
                    </div>
                    <span class="fw-footer-brand"><?php bloginfo('name'); ?></span>
                </div>
                <p class="text-muted small">Im Dienst für die Gemeinschaft – ehrenamtlich und engagiert.</p>
                <div class="mt-3">
                    <a href="tel:112" class="btn btn-danger btn-sm">
                        <i class="bi bi-telephone-fill me-1"></i>Notruf 112
                    </a>
                </div>
                <!-- Social Media -->
                <?php
                $fb  = fw_option_url('fw_facebook');
                $ig  = fw_option_url('fw_instagram');
                $yt  = fw_option_url('fw_youtube');
                if ($fb || $ig || $yt): ?>
                <div class="fw-social-links">
                    <?php if ($fb): ?><a href="<?= $fb ?>" target="_blank" rel="noopener" class="fw-social-btn" title="Facebook"><i class="bi bi-facebook"></i></a><?php endif; ?>
                    <?php if ($ig): ?><a href="<?= $ig ?>" target="_blank" rel="noopener" class="fw-social-btn" title="Instagram"><i class="bi bi-instagram"></i></a><?php endif; ?>
                    <?php if ($yt): ?><a href="<?= $yt ?>" target="_blank" rel="noopener" class="fw-social-btn" title="YouTube"><i class="bi bi-youtube"></i></a><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Schnelllinks -->
            <div class="col-sm-6 col-lg-2">
                <h6 class="fw-footer-heading">Schnelllinks</h6>
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'container'      => false,
                    'menu_class'     => 'list-unstyled fw-footer-links',
                    'depth'          => 1,
                    'fallback_cb'    => '__return_false',
                ]);
                ?>
            </div>

            <!-- Widget-Bereich -->
            <div class="col-sm-6 col-lg-2">
                <?php if (is_active_sidebar('sidebar-footer')): ?>
                    <?php dynamic_sidebar('sidebar-footer'); ?>
                <?php else: ?>
                <h6 class="fw-footer-heading">Informationen</h6>
                <ul class="list-unstyled fw-footer-links">
                    <li><a href="<?= esc_url(home_url('/ueber-uns')) ?>">Über uns</a></li>
                    <li><a href="<?= esc_url(home_url('/kontakt')) ?>">Kontakt</a></li>
                    <li><a href="<?= esc_url(home_url('/links')) ?>">Links &amp; Partner</a></li>
                    <li><a href="<?= esc_url(home_url('/impressum')) ?>">Impressum</a></li>
                    <li><a href="<?= esc_url(home_url('/datenschutz')) ?>">Datenschutz</a></li>
                </ul>
                <?php endif; ?>
            </div>

            <!-- Kontakt -->
            <div class="col-lg-4">
                <h6 class="fw-footer-heading">Kontakt</h6>
                <ul class="list-unstyled fw-footer-contact">
                    <?php if ($addr = fw_option('fw_address')): ?>
                    <li><i class="bi bi-geo-alt-fill me-2"></i><?= $addr ?></li>
                    <?php endif; ?>
                    <?php if ($phone = fw_option('fw_phone')): ?>
                    <li><i class="bi bi-telephone-fill me-2"></i><a href="tel:<?= esc_attr(preg_replace('/\s+/','',$phone)) ?>"><?= $phone ?></a></li>
                    <?php endif; ?>
                    <?php if ($email = get_theme_mod('fw_email')): ?>
                    <li><i class="bi bi-envelope-fill me-2"></i><a href="mailto:<?= esc_attr($email) ?>"><?= esc_html($email) ?></a></li>
                    <?php endif; ?>
                </ul>
                <!-- KBI Verweis -->
                <?php $kbi_url = fw_option_url('fw_kbi_url', 'https://www.landkreis-forchheim.de/Landratsamt/Fachbereiche/Brandschutz-und-Rettungswesen/');
                      $kbi_name = fw_option('fw_kbi_name', 'Kreisbrandinspektion Forchheim'); ?>
                <div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.1);">
                    <p class="text-muted small mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;font-weight:700;">Übergeordnete Stellen</p>
                    <a href="<?= $kbi_url ?>" target="_blank" rel="noopener" class="d-flex align-items-center gap-2 text-muted small" style="text-decoration:none;">
                        <i class="bi bi-shield-fill text-danger"></i><?= $kbi_name ?>
                        <i class="bi bi-box-arrow-up-right ms-auto" style="font-size:.7rem;"></i>
                    </a>
                    <a href="https://www.lfv-bayern.de" target="_blank" rel="noopener" class="d-flex align-items-center gap-2 text-muted small mt-1" style="text-decoration:none;">
                        <i class="bi bi-shield-fill text-danger"></i>Landesfeuerwehrverband Bayern
                        <i class="bi bi-box-arrow-up-right ms-auto" style="font-size:.7rem;"></i>
                    </a>
                </div>
            </div>
        </div>

        <hr class="fw-footer-divider">
        <div class="row align-items-center">
            <div class="col-md-6 text-muted small">
                &copy; <?= date('Y') ?> <?php bloginfo('name'); ?> – Alle Rechte vorbehalten.
            </div>
            <div class="col-md-6 text-md-end text-muted small">
                <a href="<?= esc_url(home_url('/impressum')) ?>" class="text-muted me-3">Impressum</a>
                <a href="<?= esc_url(home_url('/datenschutz')) ?>" class="text-muted">Datenschutzerklärung</a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
