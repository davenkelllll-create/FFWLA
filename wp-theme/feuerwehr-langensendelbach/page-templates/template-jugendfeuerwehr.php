<?php
/**
 * Template Name: Jugendfeuerwehr
 */
get_header();

// Ansprechpartner Jugendfeuerwehr – Sample-Inhalte (WordPress hat keine fuehrung.json).
$jugend = [
    ['funktion' => 'Jugendwart',      'name' => 'Maria Schmidt', 'email' => ''],
    ['funktion' => 'Stv. Jugendwart', 'name' => 'Thomas Weber',  'email' => ''],
];

$jfwSlides = [
    [
        'gradient' => 'linear-gradient(135deg,#7a3800 0%,#e07800 60%,#ffaa00 100%)',
        'icon'     => 'stars',
        'eyebrow'  => 'Jugendfeuerwehr · Langensendelbach',
        'title'    => 'Feuer &amp; Flamme<br>für die Zukunft.',
        'text'     => 'In der Jugendfeuerwehr lernst du echte Feuerwehrtechnik, übst Teamwork und übernimmst Verantwortung – und das mit einer Menge Spaß!',
        'btn1_href'=> home_url('/formulare'), 'btn1_label' => 'Anmeldung herunterladen', 'btn1_icon' => 'file-earmark-arrow-down',
        'btn2_href'=> home_url('/kontakt'),   'btn2_label' => 'Kontakt aufnehmen',       'btn2_icon' => 'envelope',
        'accent'   => '#e07800',
    ],
    [
        'gradient' => 'linear-gradient(135deg,#4a2800 0%,#c05800 60%,#e07800 100%)',
        'icon'     => 'trophy-fill',
        'eyebrow'  => 'Wettbewerbe &amp; Leistungsabzeichen',
        'title'    => 'Trainieren, lernen,<br>gewinnen.',
        'text'     => 'Wir nehmen an Kreisbewerben und überregionalen Wettbewerben teil und bereiten uns mit Spaß und Ehrgeiz vor.',
        'btn1_href'=> home_url('/kalender'),  'btn1_label' => 'Termine ansehen',         'btn1_icon' => 'calendar3',
        'btn2_href'=> home_url('/galerie'),   'btn2_label' => 'Bildergalerie',           'btn2_icon' => 'images',
        'accent'   => '#c05800',
    ],
    [
        'gradient' => 'linear-gradient(135deg,#3a2000 0%,#a04000 60%,#d06000 100%)',
        'icon'     => 'people-fill',
        'eyebrow'  => 'Kameradschaft &amp; Gemeinschaft',
        'title'    => 'Neue Freunde,<br>echte Erlebnisse.',
        'text'     => 'Zeltlager, Ausflüge und gemeinsame Aktionen – in der Jugendfeuerwehr entstehen Freundschaften fürs Leben.',
        'btn1_href'=> '#mitmachen',                          'btn1_label' => 'Jetzt mitmachen',   'btn1_icon' => 'person-plus-fill',
        'btn2_href'=> get_post_type_archive_link('post') ?: home_url('/nachrichten'), 'btn2_label' => 'Aktuelle Berichte', 'btn2_icon' => 'newspaper',
        'accent'   => '#a04000',
    ],
];
?>

<!-- JFW Hero Carousel -->
<div id="jfwCarousel" class="carousel slide fw-hero-carousel" data-bs-ride="carousel" data-bs-interval="5500">
    <div class="carousel-indicators">
        <?php foreach ($jfwSlides as $i => $s): ?>
        <button type="button" data-bs-target="#jfwCarousel" data-bs-slide-to="<?= $i ?>"
                <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>
                aria-label="Slide <?= $i + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($jfwSlides as $i => $s): ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
            <div class="fw-slide-bg" style="background:<?= $s['gradient'] ?>;">
                <div class="fw-slide-overlay" style="background:linear-gradient(to right,rgba(0,0,0,.7) 0%,rgba(0,0,0,.4) 55%,rgba(0,0,0,.1) 100%);"></div>
                <div class="fw-slide-placeholder-icon"><i class="bi bi-<?= esc_attr($s['icon']) ?>"></i></div>
            </div>
            <div class="carousel-caption fw-slide-caption">
                <div class="container">
                    <p class="fw-hero__eyebrow animate-fade">
                        <i class="bi bi-stars me-1"></i><?= $s['eyebrow'] ?>
                    </p>
                    <h1 class="animate-slide"><?= wp_kses_post($s['title']) ?></h1>
                    <p class="fw-slide-text animate-fade"><?= esc_html($s['text']) ?></p>
                    <div class="d-flex flex-wrap gap-3 justify-content-start animate-fade">
                        <a href="<?= esc_url($s['btn1_href']) ?>"
                           class="btn fw-bold"
                           style="background:<?= esc_attr($s['accent']) ?>;color:#fff;border:none;">
                            <i class="bi bi-<?= esc_attr($s['btn1_icon']) ?> me-1"></i><?= esc_html($s['btn1_label']) ?>
                        </a>
                        <a href="<?= esc_url($s['btn2_href']) ?>" class="btn-fw-outline">
                            <i class="bi bi-<?= esc_attr($s['btn2_icon']) ?> me-1"></i><?= esc_html($s['btn2_label']) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev fw-carousel-ctrl" type="button" data-bs-target="#jfwCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Zurück</span>
    </button>
    <button class="carousel-control-next fw-carousel-ctrl" type="button" data-bs-target="#jfwCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Weiter</span>
    </button>
</div>

<!-- Stats -->
<div style="background:#1a1a1a;padding:1.5rem 0;">
    <div class="container">
        <div class="row g-0 text-center">
            <div class="col-4">
                <div style="color:#ff8c00;font-size:2rem;font-weight:900;line-height:1;">12</div>
                <div style="color:rgba(255,255,255,.5);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;margin-top:.3rem;">Jugendliche</div>
            </div>
            <div class="col-4">
                <div style="color:#ff8c00;font-size:2rem;font-weight:900;line-height:1;">12–18</div>
                <div style="color:rgba(255,255,255,.5);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;margin-top:.3rem;">Jahre</div>
            </div>
            <div class="col-4">
                <div style="color:#ff8c00;font-size:2rem;font-weight:900;line-height:1;">2×/Mo</div>
                <div style="color:rgba(255,255,255,.5);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;margin-top:.3rem;">Treffen</div>
            </div>
        </div>
    </div>
</div>

<!-- Was machen wir? -->
<section class="fw-section">
    <div class="container">
        <h2 class="fw-section-title mb-2">Was machen wir?</h2>
        <p class="text-muted mb-4">Bei unseren regelmäßigen Treffen ist immer etwas los:</p>
        <div class="row g-4">
            <?php
            $aktivitaeten = [
                ['icon' => 'fire',        'color' => '#CC0000', 'titel' => 'Feuerwehrtechnik',     'text' => 'Löscheinsätze üben, Schläuche kuppeln, Strahlrohre bedienen – echte Feuerwehrarbeit hautnah.'],
                ['icon' => 'tools',       'color' => '#1a4a8a', 'titel' => 'Technische Hilfe',     'text' => 'Erste Hilfe, Verkehrsunfall-Simulation und der Umgang mit Werkzeug und Geräten.'],
                ['icon' => 'people-fill', 'color' => '#1a7a3c', 'titel' => 'Teamgeist',            'text' => 'Gemeinsame Unternehmungen, Ausflüge und die Kameradschaft, die ein Leben lang hält.'],
                ['icon' => 'trophy-fill', 'color' => '#e07800', 'titel' => 'Wettbewerbe',          'text' => 'Jugendfeuerwehr-Leistungsabzeichen, Kreisbewerbe und überregionale Wettkämpfe.'],
                ['icon' => 'map-fill',    'color' => '#6f42c1', 'titel' => 'Ausflüge & Zeltlager', 'text' => 'Jährliches Zeltlager, Besuche bei Berufsfeuerwehren und gemeinsame Erlebnisse.'],
                ['icon' => 'book-half',   'color' => '#495057', 'titel' => 'Ausbildung',           'text' => 'Erste Hilfe Kurs, Brandschutzerziehung in Schulen und Vorbereitung auf den aktiven Dienst.'],
            ];
            foreach ($aktivitaeten as $a): ?>
            <div class="col-md-6 col-lg-4">
                <div class="d-flex gap-3 p-3 rounded h-100" style="background:var(--fw-gray-100);">
                    <div style="width:48px;height:48px;background:<?= esc_attr($a['color']) ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
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

<!-- Mitmachen -->
<section class="fw-section fw-section--gray" id="mitmachen">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-6">
                <h2 class="fw-section-title mb-4">Wer kann mitmachen?</h2>
                <ul class="list-unstyled">
                    <?php
                    $infos = [
                        ['icon' => 'person-fill-check',       'text' => 'Alle Jugendlichen zwischen <strong>12 und 18 Jahren</strong> aus Langensendelbach und Umgebung'],
                        ['icon' => 'gender-ambiguous',        'text' => 'Mädchen und Jungen gleichermaßen willkommen'],
                        ['icon' => 'calendar-check',          'text' => 'Treffen alle <strong>zwei Wochen samstags</strong> am Gerätehaus'],
                        ['icon' => 'cash-coin',               'text' => 'Kein Mitgliedsbeitrag für Jugendliche'],
                        ['icon' => 'shield-fill-check',       'text' => 'Unfallversicherung über den Freistaat Bayern'],
                        ['icon' => 'arrow-right-circle-fill', 'text' => 'Mit 18 Jahren nahtloser Übergang in die aktive Wehr möglich'],
                    ];
                    foreach ($infos as $info): ?>
                    <li class="d-flex gap-3 mb-3">
                        <i class="bi bi-<?= esc_attr($info['icon']) ?> text-warning fs-5 flex-shrink-0 mt-1"></i>
                        <span><?= wp_kses_post($info['text']) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-lg-6">
                <h2 class="fw-section-title mb-4">Anmeldung</h2>
                <div class="admin-card p-4">
                    <ol class="mb-4" style="line-height:2;">
                        <li>Anmeldeformular herunterladen</li>
                        <li>Gemeinsam mit einem Erziehungsberechtigten ausfüllen</li>
                        <li>Unterschreiben (Elternteil <strong>und</strong> Kind)</li>
                        <li>Per Post oder E-Mail einsenden <strong>oder</strong> beim nächsten Treffen vorbeibringen</li>
                    </ol>
                    <a href="<?= esc_url(home_url('/formulare')) ?>" class="btn btn-warning fw-bold w-100 mb-3">
                        <i class="bi bi-file-earmark-arrow-down me-2"></i>Anmeldeformular herunterladen
                    </a>
                    <p class="text-muted small mb-0 text-center">
                        Fragen? Einfach beim <a href="<?= esc_url(home_url('/kontakt')) ?>">Jugendwart melden</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ansprechpartner -->
<section class="fw-section">
    <div class="container">
        <h2 class="fw-section-title mb-4">Ansprechpartner</h2>
        <div class="row g-4">
            <?php foreach ($jugend as $p): ?>
            <div class="col-md-6 col-lg-4">
                <div class="admin-card p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                         style="width:72px;height:72px;background:var(--fw-red-pale);">
                        <i class="bi bi-person-fill text-danger" style="font-size:2rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-1"><?= esc_html($p['name']) ?></h6>
                    <p class="text-muted small mb-2"><?= esc_html($p['funktion']) ?></p>
                    <?php if (!empty($p['email'])): ?>
                    <a href="mailto:<?= esc_attr($p['email']) ?>" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-envelope me-1"></i>E-Mail
                    </a>
                    <?php else: ?>
                    <a href="<?= esc_url(home_url('/kontakt')) ?>" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-envelope me-1"></i>Kontakt
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($jugend)): ?>
            <div class="col-12 text-muted">Keine Ansprechpartner hinterlegt.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="fw-section" style="background:linear-gradient(135deg,#e07800,#ff8c00);color:#fff;">
    <div class="container text-center">
        <i class="bi bi-stars fs-1 mb-3 d-block" style="opacity:.7;"></i>
        <h2 class="text-white mb-3">Neugierig geworden?</h2>
        <p class="mb-4 text-white" style="opacity:.9;max-width:480px;margin:0 auto 1.5rem;">
            Komm einfach zu einem unserer nächsten Treffen vorbei – kein Druck, keine Voranmeldung nötig!
        </p>
        <a href="<?= esc_url(home_url('/kalender')) ?>" class="btn bg-white fw-bold" style="color:#e07800;">
            <i class="bi bi-calendar3 me-2"></i>Nächste Termine ansehen
        </a>
    </div>
</section>

<?php get_footer();
