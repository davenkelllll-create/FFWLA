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
        <p class="text-muted mb-0">Unsere Wehr, Fahrzeuge und Ansprechpartner</p>
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

<!-- Gründungsjahr + Ansprechpartner -->
<section class="fw-section">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-4">
                <div class="p-4 rounded text-center" style="background:var(--fw-red);color:#fff;">
                    <div style="font-size:.8rem;text-transform:uppercase;letter-spacing:.1em;opacity:.85;">Gegründet</div>
                    <div style="font-size:3rem;font-weight:900;line-height:1.1;"><?= esc_html($founded) ?></div>
                    <p class="mb-0 small" style="opacity:.9;">Im Dienst für Langensendelbach und seine Bürgerinnen und Bürger.</p>
                </div>
            </div>
            <div class="col-lg-8">
                <h2 class="fw-section-title mb-4">Ansprechpartner</h2>
                <?php
                $gruppen = [
                    'Vorstandschaft & Wehrführung' => $fuehrung['aktive_wehr'],
                    'Jugendfeuerwehr'              => $fuehrung['jugendfeuerwehr'],
                    'Kinderfeuerwehr'              => $fuehrung['kinderfeuerwehr'],
                ];
                foreach ($gruppen as $titel => $personen):
                    if (empty($personen)) continue; ?>
                <h6 class="fw-bold text-uppercase text-muted mb-2" style="font-size:.78rem;letter-spacing:.06em;"><?= esc_html($titel) ?></h6>
                <ul class="list-unstyled mb-4">
                    <?php foreach ($personen as $p): ?>
                    <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span><strong><?= esc_html($p['funktion']) ?>:</strong> <?= esc_html($p['name']) ?></span>
                        <?php if (!empty($p['email'])): ?>
                        <a href="mailto:<?= esc_attr($p['email']) ?>" class="btn btn-sm btn-outline-danger" title="E-Mail an <?= esc_attr($p['name']) ?>" aria-label="E-Mail an <?= esc_attr($p['name']) ?>">
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
