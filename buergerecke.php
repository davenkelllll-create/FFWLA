<?php
require_once 'includes/functions.php';
$pageTitle = 'Bürgerecke';
$pageDescription = 'Tipps rund um Brandschutz und Sicherheit für die Bürgerinnen und Bürger von Langensendelbach – Notruf 112, Rauchmelder, Verhalten im Brandfall.';
include 'includes/header.php';
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="/">Startseite</a> / Bürgerecke
        </nav>
        <h1><i class="bi bi-info-square me-2 text-fw-red"></i>Bürgerecke</h1>
        <p class="text-muted mb-0">Tipps rund um Brandschutz und Sicherheit für unsere Gemeinde</p>
    </div>
</div>

<!-- Notruf 112 -->
<section class="fw-section">
    <div class="container">
        <div class="p-4 p-md-5 rounded" style="background:var(--fw-red);color:#fff;">
            <div class="row align-items-center g-4">
                <div class="col-md-3 text-center">
                    <div style="font-size:3.5rem;font-weight:900;line-height:1;">112</div>
                    <div style="text-transform:uppercase;letter-spacing:.1em;font-size:.8rem;opacity:.85;">Notruf Feuerwehr</div>
                </div>
                <div class="col-md-9">
                    <h2 class="text-white mb-3">Im Notfall richtig anrufen – die 5 W</h2>
                    <div class="row g-2">
                        <?php foreach (getInhalte('buergerecke_fuenf_w') as $item): ?>
                        <div class="col-12">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong><?= h($item['wort'] ?? '') ?></strong> <span style="opacity:.9;"><?= h($item['text'] ?? '') ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tipps -->
<section class="fw-section fw-section--gray">
    <div class="container">
        <h2 class="fw-section-title mb-4">Tipps für Ihre Sicherheit</h2>
        <div class="row g-4">
            <?php foreach (getInhalte('buergerecke_tipps') as $t): ?>
            <div class="col-md-6 col-lg-4">
                <div class="admin-card p-4 h-100">
                    <div class="mb-3" style="width:48px;height:48px;background:<?= h($t['color'] ?? '#CC0000') ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-<?= h($t['icon'] ?? 'info-circle-fill') ?> text-white fs-5"></i>
                    </div>
                    <h3 class="fw-card__title mb-2" style="font-size:1.05rem;"><?= h($t['titel'] ?? '') ?></h3>
                    <p class="text-muted small mb-0"><?= h($t['text'] ?? '') ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Weiterführende Links -->
<section class="fw-section">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-section-title mb-3">Noch Fragen?</h2>
                <p class="text-muted mb-0">
                    Wir beraten Sie gerne zu Brandschutz und Sicherheit – etwa bei Festen, in Vereinen
                    oder bei Fragen zu Rauchmeldern. Sprechen Sie uns einfach an.
                    Weitere Informationen und Warnungen finden Sie auch beim
                    <a href="/links.php">Bundesamt für Bevölkerungsschutz und unseren Partnern</a>.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="/kontakt.php" class="btn btn-danger">
                    <i class="bi bi-envelope me-1"></i>Kontakt aufnehmen
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
