<?php
/**
 * Template Name: Über uns
 */
get_header();

// Führung – Sample-Inhalte (WordPress hat keine fuehrung.json).
// Bei Bedarf später über ein eigenes CPT / ACF pflegbar.
$fuehrung = [
    'aktive_wehr' => [
        ['funktion' => '1. Vorstand',   'name' => 'Sebastian Baumgärtel', 'email' => ''],
        ['funktion' => '2. Vorstand',   'name' => 'Alexander Güthlein',   'email' => ''],
        ['funktion' => '1. Kommandant', 'name' => 'Martin Hofmann',       'email' => ''],
        ['funktion' => 'Schriftführer', 'name' => 'Florian Eger',         'email' => ''],
        ['funktion' => 'Kassier',       'name' => 'Maximilian Erlwein',   'email' => ''],
    ],
    'jugendfeuerwehr' => [
        ['funktion' => 'Jugendwart',                 'name' => 'Steffen Kupfer',   'email' => ''],
        ['funktion' => 'Betreuer Jugendfeuerwehr',   'name' => 'Laurin Reichel',   'email' => ''],
        ['funktion' => 'Betreuerin Jugendfeuerwehr', 'name' => 'Elena Baumgärtel', 'email' => ''],
    ],
    'kinderfeuerwehr' => [
        ['funktion' => 'Leiterin Kinderfeuerwehr',   'name' => 'Christina Langguth', 'email' => ''],
        ['funktion' => 'Betreuerin Kinderfeuerwehr', 'name' => 'Lara Arold',         'email' => ''],
    ],
];
$founded = fw_option('fw_founded', '1952');
$members = fw_option('fw_members', '45');
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="<?= esc_url(home_url('/')) ?>">Startseite</a> / Über uns
        </nav>
        <h1><i class="bi bi-people-fill me-2 text-fw-red"></i>Über uns</h1>
        <p class="text-muted mb-0">Geschichte, Mannschaft und Ausstattung der FF Langensendelbach</p>
    </div>
</div>

<!-- Optionaler Editor-Inhalt -->
<?php if (have_posts()): while (have_posts()): the_post();
    if (trim(get_the_content()) !== ''): ?>
<section class="fw-section pb-0">
    <div class="container">
        <div class="fw-article-body" style="line-height:1.8;"><?php the_content(); ?></div>
    </div>
</section>
<?php endif; endwhile; endif; ?>

<!-- Geschichte -->
<section class="fw-section">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-7">
                <h2 class="fw-section-title mb-4">Unsere Geschichte</h2>
                <div class="fw-timeline">
                    <div class="fw-timeline-item">
                        <div class="fw-timeline-year">1952</div>
                        <div class="fw-timeline-title">Gründung der Freiwilligen Feuerwehr Langensendelbach</div>
                        <p class="text-muted small">Mit 24 Gründungsmitgliedern wurde die Freiwillige Feuerwehr Langensendelbach ins Leben gerufen. Die ersten Übungen fanden auf dem Sportplatz statt.</p>
                    </div>
                    <div class="fw-timeline-item">
                        <div class="fw-timeline-year">1968</div>
                        <div class="fw-timeline-title">Bezug des neuen Gerätehauses</div>
                        <p class="text-muted small">Das erste eigene Gerätehaus wurde gebaut und bezogen. Damit verbesserten sich die Ausrückzeiten erheblich.</p>
                    </div>
                    <div class="fw-timeline-item">
                        <div class="fw-timeline-year">1985</div>
                        <div class="fw-timeline-title">Gründung der Jugendfeuerwehr</div>
                        <p class="text-muted small">Die Jugendfeuerwehr wurde gegründet, um Nachwuchs für die aktive Wehr zu gewinnen und Jugendlichen eine sinnvolle Freizeitgestaltung zu bieten.</p>
                    </div>
                    <div class="fw-timeline-item">
                        <div class="fw-timeline-year">2003</div>
                        <div class="fw-timeline-title">Erweiterung und Modernisierung des Gerätehauses</div>
                        <p class="text-muted small">Das Gerätehaus wurde erweitert und mit moderner Ausstattung versehen. Neue Fahrzeuge wurden in Dienst gestellt.</p>
                    </div>
                    <div class="fw-timeline-item">
                        <div class="fw-timeline-year">Heute</div>
                        <div class="fw-timeline-title">Moderne Feuerwehr für die Zukunft</div>
                        <p class="text-muted small mb-0">Mit über 45 aktiven Mitgliedern und moderner Technik sind wir für alle Herausforderungen gerüstet.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="p-4 rounded" style="background:var(--fw-red);color:#fff;">
                    <h4 class="text-white mb-3"><i class="bi bi-info-circle me-2"></i>Auf einen Blick</h4>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between border-bottom border-white border-opacity-25 py-2">
                            <span>Gegründet</span><strong><?= esc_html($founded) ?></strong>
                        </li>
                        <li class="d-flex justify-content-between border-bottom border-white border-opacity-25 py-2">
                            <span>Aktive Mitglieder</span><strong><?= esc_html($members) ?>+</strong>
                        </li>
                        <li class="d-flex justify-content-between border-bottom border-white border-opacity-25 py-2">
                            <span>Jugendliche</span><strong>12</strong>
                        </li>
                        <li class="d-flex justify-content-between border-bottom border-white border-opacity-25 py-2">
                            <span>Fahrzeuge</span><strong>3</strong>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span>Einsätze 2023</span><strong>47</strong>
                        </li>
                    </ul>
                </div>
                <div class="mt-3 p-4 rounded" style="background:var(--fw-gray-100);">
                    <h6 class="fw-bold mb-2"><i class="bi bi-person-badge me-2 text-danger"></i>Führung</h6>
                    <ul class="list-unstyled mb-0 small">
                        <?php
                        $alle = array_merge($fuehrung['aktive_wehr'], $fuehrung['jugendfeuerwehr'], $fuehrung['kinderfeuerwehr']);
                        foreach ($alle as $idx => $p):
                            $last = $idx === count($alle) - 1;
                        ?>
                        <li class="py-1<?= $last ? '' : ' border-bottom' ?>">
                            <strong><?= esc_html($p['funktion']) ?>:</strong> <?= esc_html($p['name']) ?>
                            <?php if (!empty($p['email'])): ?>
                                <a href="mailto:<?= esc_attr($p['email']) ?>" class="text-muted ms-1"><i class="bi bi-envelope-fill"></i></a>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Fahrzeuge -->
<section class="fw-section fw-section--gray">
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

<!-- Ausrüstung -->
<section class="fw-section">
    <div class="container">
        <h2 class="fw-section-title mb-4">Ausrüstung &amp; Technik</h2>
        <div class="row g-4">
            <?php
            $ausruestung = [
                ['icon' => 'wind', 'titel' => 'Atemschutz', 'text' => '10 Atemschutzgeräte für den sicheren Einsatz in verrauchten Bereichen'],
                ['icon' => 'droplet-fill', 'titel' => 'Brandbekämpfung', 'text' => 'Pumpen, Schläuche, Strahlrohre für verschiedene Einsatzszenarien'],
                ['icon' => 'tools', 'titel' => 'Technische Hilfe', 'text' => 'Hydraulische Rettungsgeräte (Spreizer, Schere) für Verkehrsunfälle'],
                ['icon' => 'lightning-charge-fill', 'titel' => 'Stromversorgung', 'text' => 'Stromerzeuger und Beleuchtungsgeräte für den Nachtbetrieb'],
                ['icon' => 'water', 'titel' => 'Wassertechnik', 'text' => 'Tragkraftspritzen und Pumpen zur Wasserförderung über lange Strecken'],
                ['icon' => 'heart-pulse-fill', 'titel' => 'Erste Hilfe', 'text' => 'Sanitätstasche und Defibrillatoren für die medizinische Erstversorgung'],
            ];
            foreach ($ausruestung as $a): ?>
            <div class="col-md-6 col-lg-4">
                <div class="d-flex gap-3 p-3 rounded" style="background:var(--fw-gray-100);">
                    <div style="width:44px;height:44px;background:var(--fw-red);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-<?= esc_attr($a['icon']) ?> text-white fs-5"></i>
                    </div>
                    <div>
                        <strong class="d-block mb-1"><?= esc_html($a['titel']) ?></strong>
                        <span class="text-muted small"><?= esc_html($a['text']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
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
            <a href="<?= esc_url(home_url('/formulare')) ?>" class="btn-fw-outline">
                <i class="bi bi-file-earmark-arrow-down"></i> Mitgliedsantrag herunterladen
            </a>
            <a href="<?= esc_url(home_url('/kontakt')) ?>" class="btn bg-white text-danger fw-bold">
                <i class="bi bi-envelope me-1"></i> Kontakt aufnehmen
            </a>
        </div>
    </div>
</section>

<?php get_footer();
