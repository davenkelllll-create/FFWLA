<?php
/**
 * Template Name: Bürgerecke
 */
get_header();
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="<?= esc_url(home_url('/')) ?>">Startseite</a> / Bürgerecke
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
                        <?php
                        $w = [
                            ['Wo', 'ist es passiert? (Ort, Straße, Hausnummer)'],
                            ['Was', 'ist passiert? (Brand, Unfall, Person in Gefahr …)'],
                            ['Wie viele', 'Personen sind betroffen oder verletzt?'],
                            ['Welche', 'Verletzungen / Besonderheiten liegen vor?'],
                            ['Warten', 'auf Rückfragen – nicht sofort auflegen!'],
                        ];
                        foreach ($w as $item): ?>
                        <div class="col-12">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong><?= esc_html($item[0]) ?></strong> <span style="opacity:.9;"><?= esc_html($item[1]) ?></span>
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
            <?php
            $tipps = [
                ['icon' => 'bell-fill',            'color' => '#CC0000', 'titel' => 'Rauchmelder retten Leben', 'text' => 'In Bayern sind Rauchmelder in Schlaf- und Kinderzimmern sowie Fluren Pflicht. Prüfen Sie regelmäßig die Batterie – ein Piepen darf nie ignoriert werden.'],
                ['icon' => 'fire',                 'color' => '#e07800', 'titel' => 'Verhalten im Brandfall', 'text' => 'Ruhe bewahren, alle Personen warnen, das Gebäude über gekennzeichnete Wege verlassen, Türen schließen (nicht abschließen) und den Notruf 112 wählen. Kein Aufzug!'],
                ['icon' => 'droplet-fill',         'color' => '#1a4a8a', 'titel' => 'Fettbrand niemals mit Wasser löschen', 'text' => 'Brennendes Fett niemals mit Wasser löschen (Explosionsgefahr!). Topf mit einem Deckel oder einer Löschdecke abdecken und die Herdplatte ausschalten.'],
                ['icon' => 'signpost-2-fill',      'color' => '#1a7a3c', 'titel' => 'Zufahrten freihalten', 'text' => 'Bitte Feuerwehrzufahrten, Hydranten und Rettungswege stets frei halten. Im Ernstfall zählt jede Sekunde – falsch geparkte Fahrzeuge kosten wertvolle Zeit.'],
                ['icon' => 'tree-fill',            'color' => '#1a7a3c', 'titel' => 'Offenes Feuer & Grillen', 'text' => 'Bei Trockenheit besondere Vorsicht: kein offenes Feuer im Freien, Grill nie unbeaufsichtigt lassen und Asche erst nach vollständigem Erkalten entsorgen.'],
                ['icon' => 'car-front-fill',       'color' => '#495057', 'titel' => 'Rettungsgasse bilden', 'text' => 'Bei stockendem Verkehr sofort eine Rettungsgasse bilden – zwischen der linken und den übrigen Spuren. So kommen Feuerwehr und Rettungsdienst schneller ans Ziel.'],
            ];
            foreach ($tipps as $t): ?>
            <div class="col-md-6 col-lg-4">
                <div class="admin-card p-4 h-100">
                    <div class="mb-3" style="width:48px;height:48px;background:<?= esc_attr($t['color']) ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-<?= esc_attr($t['icon']) ?> text-white fs-5"></i>
                    </div>
                    <h3 class="fw-card__title mb-2" style="font-size:1.05rem;"><?= esc_html($t['titel']) ?></h3>
                    <p class="text-muted small mb-0"><?= esc_html($t['text']) ?></p>
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
                    <a href="<?= esc_url(home_url('/links')) ?>">Bundesamt für Bevölkerungsschutz und unseren Partnern</a>.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= esc_url(home_url('/kontakt')) ?>" class="btn btn-danger">
                    <i class="bi bi-envelope me-1"></i>Kontakt aufnehmen
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer();
