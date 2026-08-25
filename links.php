<?php
require_once 'includes/functions.php';
$pageTitle = 'Links & Partner';
$pageDescription = 'Wichtige Links und Partner der Freiwilligen Feuerwehr Langensendelbach – Kreisbrandinspektion, Landesverband und mehr.';

$uebergeordnet  = getInhalte('links_uebergeordnet');
$behoerden      = getInhalte('links_behoerden');
$notruf         = getInhalte('links_notrufnummern');

/** Eine Partner-/Link-Karte ausgeben. */
function partnerKarte(array $l): void { ?>
    <a href="<?= h($l['url'] ?? '#') ?>" target="_blank" rel="noopener" class="fw-partner-card">
        <div class="fw-partner-icon"<?= !empty($l['icon_bg']) ? ' style="background:' . h($l['icon_bg']) . ';"' : '' ?>>
            <i class="bi bi-<?= h($l['icon'] ?? 'shield-fill') ?>"<?= !empty($l['icon_color']) ? ' style="color:' . h($l['icon_color']) . ';"' : '' ?>></i>
        </div>
        <div>
            <strong class="d-block"><?= h($l['titel'] ?? '') ?></strong>
            <span class="text-muted small"><?= h($l['text'] ?? '') ?></span>
            <?php if (!empty($l['domain'])): ?>
            <div class="mt-1">
                <span class="fw-badge badge-secondary"><?= h($l['domain']) ?></span>
            </div>
            <?php endif; ?>
        </div>
        <i class="bi bi-box-arrow-up-right text-muted ms-auto flex-shrink-0"></i>
    </a>
<?php }

include 'includes/header.php';
?>

<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="/">Startseite</a> / Links &amp; Partner
        </nav>
        <h1><i class="bi bi-link-45deg me-2 text-fw-red"></i>Links &amp; Partner</h1>
        <p class="text-muted mb-0">Wichtige Anlaufstellen rund um den Feuerwehr- und Katastrophenschutz</p>
    </div>
</div>

<section class="fw-section">
    <div class="container">
        <div class="row g-5">

            <!-- Übergeordnete Stellen -->
            <div class="col-lg-6">
                <h2 class="fw-section-title mb-4">Übergeordnete Stellen</h2>
                <?php if (empty($uebergeordnet)): ?>
                <p class="text-muted">Noch keine Einträge hinterlegt.</p>
                <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($uebergeordnet as $l) partnerKarte($l); ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Notfall & Behörden -->
            <div class="col-lg-6">
                <h2 class="fw-section-title mb-4">Notfall &amp; Behörden</h2>
                <?php if (!empty($behoerden)): ?>
                <div class="d-flex flex-column gap-3 mb-5">
                    <?php foreach ($behoerden as $l) partnerKarte($l); ?>
                </div>
                <?php endif; ?>

                <!-- Notrufnummern -->
                <?php if (!empty($notruf)): ?>
                <h2 class="fw-section-title mb-4">Wichtige Notrufnummern</h2>
                <div class="d-flex flex-column gap-2">
                    <?php foreach ($notruf as $n): ?>
                    <div class="d-flex align-items-center gap-3 p-3 rounded" style="background:var(--fw-gray-100);">
                        <a href="tel:<?= h(str_replace(' ', '', $n['num'] ?? '')) ?>"
                           class="fw-bold rounded px-3 py-1 text-white text-decoration-none"
                           style="background:<?= h($n['color'] ?? '#CC0000') ?>;font-size:1.1rem;white-space:nowrap;min-width:80px;text-align:center;">
                            <?= h($n['num'] ?? '') ?>
                        </a>
                        <span class="fw-semibold"><?= h($n['label'] ?? '') ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
