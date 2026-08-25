<?php
require_once 'includes/functions.php';
$pageTitle = 'Über uns';
$pageDescription = 'Die Freiwillige Feuerwehr Langensendelbach – Geschichte seit 1878, Fahrzeuge und Ansprechpartner.';

$fuehrung   = getFuehrung();
$geschichte = getGeschichte();
$fahrzeuge  = getFahrzeuge();
include 'includes/header.php';
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="/">Startseite</a> / Über uns
        </nav>
        <h1><i class="bi bi-people-fill me-2 text-fw-red"></i>Über uns</h1>
        <p class="text-muted mb-0">Geschichte, Fahrzeuge und Ansprechpartner</p>
    </div>
</div>

<!-- Geschichte -->
<section class="fw-section">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-7">
                <h2 class="fw-section-title mb-4">Unsere Geschichte</h2>
                <?php if (empty($geschichte['eintraege'])): ?>
                <p class="text-muted">Noch keine Einträge hinterlegt.</p>
                <?php else: ?>
                <div class="fw-timeline">
                    <?php foreach ($geschichte['eintraege'] as $i => $e):
                        $isLast = $i === array_key_last($geschichte['eintraege']); ?>
                    <div class="fw-timeline-item">
                        <div class="fw-timeline-year"><?= h($e['jahr'] ?? '') ?></div>
                        <div class="fw-timeline-title"><?= h($e['titel'] ?? '') ?></div>
                        <p class="text-muted small<?= $isLast ? ' mb-0' : '' ?>"><?= h($e['text'] ?? '') ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-5">
                <?php $hl = $geschichte['highlight']; ?>
                <?php if (!empty($hl['jahr'])): ?>
                <div class="p-4 rounded text-center" style="background:var(--fw-red);color:#fff;">
                    <div style="font-size:.8rem;text-transform:uppercase;letter-spacing:.1em;opacity:.85;"><?= h($hl['label'] ?? 'Gegründet') ?></div>
                    <div style="font-size:3rem;font-weight:900;line-height:1.1;"><?= h($hl['jahr']) ?></div>
                    <?php if (!empty($hl['text'])): ?>
                    <p class="mb-3 small" style="opacity:.9;"><?= h($hl['text']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($hl['motto'])): ?>
                    <div class="pt-3" style="border-top:1px solid rgba(255,255,255,.25);">
                        <em><?= h($hl['motto']) ?></em>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Ansprechpartner -->
<section class="fw-section fw-section--gray">
    <div class="container">
        <h2 class="fw-section-title mb-4">Ansprechpartner</h2>
        <div class="row">
            <div class="col-lg-8">
                <?php
                $gruppen = [
                    'Vorstandschaft & Wehrführung' => $fuehrung['aktive_wehr'],
                    'Jugendfeuerwehr'              => $fuehrung['jugendfeuerwehr'],
                    'Kinderfeuerwehr'              => $fuehrung['kinderfeuerwehr'],
                ];
                foreach ($gruppen as $titel => $personen):
                    if (empty($personen)) continue; ?>
                <h6 class="fw-bold text-uppercase text-muted mb-2" style="font-size:.78rem;letter-spacing:.06em;"><?= h($titel) ?></h6>
                <ul class="list-unstyled mb-4">
                    <?php foreach ($personen as $p): ?>
                    <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span><strong><?= h($p['funktion']) ?>:</strong> <?= h($p['name']) ?></span>
                        <?php if (!empty($p['email'])): ?>
                        <a href="mailto:<?= h($p['email']) ?>" class="btn btn-sm btn-outline-danger" title="E-Mail an <?= h($p['name']) ?>" aria-label="E-Mail an <?= h($p['name']) ?>">
                            <i class="bi bi-envelope"></i>
                        </a>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Fahrzeuge -->
<section class="fw-section">
    <div class="container">
        <h2 class="fw-section-title mb-4">Unsere Fahrzeuge</h2>
        <?php if (empty($fahrzeuge)): ?>
        <p class="text-muted">Noch keine Fahrzeuge hinterlegt.</p>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($fahrzeuge as $f): ?>
            <div class="col-md-6 col-lg-4">
                <div class="fw-card">
                    <?php if (!empty($f['thumbnail'])): ?>
                    <img src="<?= h($f['thumbnail']) ?>" alt="<?= h($f['name'] ?? '') ?>" class="fw-card__img">
                    <?php else: ?>
                    <div class="fw-card__img--placeholder">
                        <i class="bi bi-<?= h($f['icon'] ?? 'truck-front-fill') ?>"></i>
                    </div>
                    <?php endif; ?>
                    <div class="fw-card__body">
                        <div class="fw-card__meta">
                            <?php if (!empty($f['kategorie'])): ?>
                            <span class="fw-badge <?= h($f['badge_class'] ?? 'badge-einsatz') ?>"><?= h($f['kategorie']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($f['baujahr'])): ?>
                            <span>Baujahr <?= h($f['baujahr']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="fw-card__title"><?= h($f['name'] ?? '') ?></h3>
                        <p class="fw-card__excerpt"><?= h($f['beschreibung'] ?? '') ?></p>
                        <?php if (!empty($f['funkrufname']) || !empty($f['besatzung'])): ?>
                        <div class="fw-card__meta mt-2">
                            <?php if (!empty($f['funkrufname'])): ?>
                            <span><i class="bi bi-broadcast me-1"></i><?= h($f['funkrufname']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($f['besatzung'])): ?>
                            <span><i class="bi bi-people me-1"></i><?= h($f['besatzung']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA: Mitmachen -->
<section class="fw-section fw-section--red">
    <div class="container text-center">
        <h2 class="mb-3">Interesse? Werde Teil der FF Langensendelbach!</h2>
        <p class="mb-4" style="max-width:520px;margin:0 auto 1.5rem;">
            Wir suchen engagierte Menschen jeden Alters – egal ob als aktives Mitglied,
            in der Jugendfeuerwehr oder als Fördermitglied.
        </p>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="/formulare.php" class="btn-fw-outline">
                <i class="bi bi-file-earmark-arrow-down"></i> Mitgliedsantrag herunterladen
            </a>
            <a href="/kontakt.php" class="btn bg-white text-danger fw-bold">
                <i class="bi bi-envelope me-1"></i> Kontakt aufnehmen
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
