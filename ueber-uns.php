<?php
require_once 'includes/functions.php';
$pageTitle = 'Über uns';
$pageDescription = 'Die Freiwillige Feuerwehr Langensendelbach – Geschichte seit 1878, Fahrzeuge und Ansprechpartner.';

$fuehrung = getFuehrung();
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
                <div class="fw-timeline">
                    <div class="fw-timeline-item">
                        <div class="fw-timeline-year">1840</div>
                        <div class="fw-timeline-title">Die erste Feuerspritze</div>
                        <p class="text-muted small">Langensendelbach erhält seine erste Feuerspritze; das Spritzenhaus entsteht an der Hauptstraße.</p>
                    </div>
                    <div class="fw-timeline-item">
                        <div class="fw-timeline-year">1878</div>
                        <div class="fw-timeline-title">Gründung der Freiwilligen Feuerwehr</div>
                        <p class="text-muted small">Am 9.&nbsp;März 1878 gründen 48 Männer den Verein „Freiwillige Feuerwehr Langensendelbach" unter dem Leitspruch »Gott zur Ehr, dem Nächsten zur Wehr«. Zum ersten Kommandanten wird Joachim Müller gewählt – bis heute der <strong>älteste Verein der Gemeinde</strong>.</p>
                    </div>
                    <div class="fw-timeline-item">
                        <div class="fw-timeline-year">2005</div>
                        <div class="fw-timeline-title">Erweiterung des Gerätehauses</div>
                        <p class="text-muted small">Nach dem Spatenstich im November 2004 wird das erweiterte und sanierte Gerätehaus am 1.&nbsp;Oktober 2005 feierlich eingeweiht und gesegnet.</p>
                    </div>
                    <div class="fw-timeline-item">
                        <div class="fw-timeline-year">2010</div>
                        <div class="fw-timeline-title">Gründung der Kinderfeuerwehr</div>
                        <p class="text-muted small">Seit dem Frühjahr 2010 gibt es die Kinderfeuerwehr für Kinder von 6 bis 12 Jahren.</p>
                    </div>
                    <div class="fw-timeline-item">
                        <div class="fw-timeline-year">Heute</div>
                        <div class="fw-timeline-title">Moderne Wehr mit Tradition</div>
                        <p class="text-muted small mb-0">Mit rund 90 aktiven Feuerwehrfrauen und -männern sowie Jugend- und Kinderfeuerwehr sind wir bestens für die Zukunft aufgestellt.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="p-4 rounded text-center" style="background:var(--fw-red);color:#fff;">
                    <div style="font-size:.8rem;text-transform:uppercase;letter-spacing:.1em;opacity:.85;">Gegründet</div>
                    <div style="font-size:3rem;font-weight:900;line-height:1.1;">1878</div>
                    <p class="mb-3 small" style="opacity:.9;">Ältester Verein der Gemeinde Langensendelbach.</p>
                    <div class="pt-3" style="border-top:1px solid rgba(255,255,255,.25);">
                        <em>»Gott zur Ehr, dem Nächsten zur Wehr«</em>
                    </div>
                </div>
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
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="fw-card">
                    <div class="fw-card__img--placeholder">
                        <i class="bi bi-truck-front-fill"></i>
                    </div>
                    <div class="fw-card__body">
                        <div class="fw-card__meta">
                            <span class="fw-badge badge-einsatz">Löschfahrzeug</span>
                            <span>Baujahr 2018</span>
                        </div>
                        <h3 class="fw-card__title">LF 10 – Löschgruppenfahrzeug</h3>
                        <p class="fw-card__excerpt">Modernes Löschgruppenfahrzeug mit 1.000 Liter Löschwassertank, Schnellangriff und umfangreicher technischer Hilfeleistungsausrüstung.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="fw-card">
                    <div class="fw-card__img--placeholder">
                        <i class="bi bi-truck-front"></i>
                    </div>
                    <div class="fw-card__body">
                        <div class="fw-card__meta">
                            <span class="fw-badge badge-einsatz">Tanklöschfahrzeug</span>
                            <span>Baujahr 2005</span>
                        </div>
                        <h3 class="fw-card__title">TLF 3000 – Tanklöschfahrzeug</h3>
                        <p class="fw-card__excerpt">Tanklöschfahrzeug mit 3.000 Liter Wassertank für Einsätze mit eingeschränkter Wasserversorgung (z.B. Waldbrände, Außenbereiche).</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="fw-card">
                    <div class="fw-card__img--placeholder">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                    <div class="fw-card__body">
                        <div class="fw-card__meta">
                            <span class="fw-badge badge-uebung">Mannschaftstransport</span>
                            <span>Baujahr 2015</span>
                        </div>
                        <h3 class="fw-card__title">MTF – Mannschaftstransportfahrzeug</h3>
                        <p class="fw-card__excerpt">Mannschaftstransportfahrzeug für den Transport von bis zu 9 Einsatzkräften sowie für Führungsaufgaben und Erkundung.</p>
                    </div>
                </div>
            </div>
        </div>
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
