<?php
require_once 'includes/functions.php';
$pageTitle = 'Jugendfeuerwehr';
$pageDescription = 'Jugendfeuerwehr Langensendelbach – Für Jugendliche von 12 bis 18 Jahren. Spaß, Teamgeist und verantwortungsvolles Handeln.';
include 'includes/header.php';
?>

<!-- JFW Hero -->
<section class="fw-jfw-hero">
    <div class="container" style="position:relative;z-index:1;">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <p class="fw-hero__eyebrow">
                    <i class="bi bi-stars me-1"></i>
                    Jugendfeuerwehr · Langensendelbach
                </p>
                <h1 style="font-size:clamp(1.8rem,5vw,3rem);font-weight:800;line-height:1.15;margin-bottom:1rem;">
                    Feuer &amp; Flamme<br>für die Zukunft.
                </h1>
                <p style="font-size:1.1rem;opacity:.9;max-width:520px;margin-bottom:2rem;">
                    In der Jugendfeuerwehr lernst du echte Feuerwehrtechnik, übst Teamwork und
                    übernimmst Verantwortung – und das mit einer Menge Spaß!
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="/formulare.php" class="btn bg-white fw-bold" style="color:#e07800;">
                        <i class="bi bi-file-earmark-arrow-down me-1"></i>Anmeldung herunterladen
                    </a>
                    <a href="/kontakt.php" class="btn-fw-outline">
                        <i class="bi bi-envelope me-1"></i>Kontakt aufnehmen
                    </a>
                </div>
            </div>
        </div>
        <i class="bi bi-stars fw-hero-icon" style="color:rgba(255,255,255,.12);font-size:9rem;position:absolute;right:-1rem;top:50%;transform:translateY(-50%);"></i>
    </div>
</section>

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
                ['icon' => 'fire',              'color' => '#CC0000', 'titel' => 'Feuerwehrtechnik',    'text' => 'Löscheinsätze üben, Schläuche kuppeln, Strahlrohre bedienen – echte Feuerwehrarbeit hautnah.'],
                ['icon' => 'tools',             'color' => '#1a4a8a', 'titel' => 'Technische Hilfe',    'text' => 'Erste Hilfe, Verkehrsunfall-Simulation und der Umgang mit Werkzeug und Geräten.'],
                ['icon' => 'people-fill',       'color' => '#1a7a3c', 'titel' => 'Teamgeist',           'text' => 'Gemeinsame Unternehmungen, Ausflüge und die Kameradschaft, die ein Leben lang hält.'],
                ['icon' => 'trophy-fill',       'color' => '#e07800', 'titel' => 'Wettbewerbe',         'text' => 'Jugendfeuerwehr-Leistungsabzeichen, Kreisbewerbe und überregionale Wettkämpfe.'],
                ['icon' => 'map-fill',          'color' => '#6f42c1', 'titel' => 'Ausflüge & Zeltlager','text' => 'Jährliches Zeltlager, Besuche bei Berufsfeuerwehren und gemeinsame Erlebnisse.'],
                ['icon' => 'book-half',         'color' => '#495057', 'titel' => 'Ausbildung',          'text' => 'Erste Hilfe Kurs, Brandschutzerziehung in Schulen und Vorbereitung auf den aktiven Dienst.'],
            ];
            foreach ($aktivitaeten as $a): ?>
            <div class="col-md-6 col-lg-4">
                <div class="d-flex gap-3 p-3 rounded h-100" style="background:var(--fw-gray-100);">
                    <div style="width:48px;height:48px;background:<?= $a['color'] ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-<?= $a['icon'] ?> text-white fs-5"></i>
                    </div>
                    <div>
                        <strong class="d-block mb-1"><?= h($a['titel']) ?></strong>
                        <span class="text-muted small"><?= h($a['text']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Mitmachen -->
<section class="fw-section fw-section--gray">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-6">
                <h2 class="fw-section-title mb-4">Wer kann mitmachen?</h2>
                <ul class="list-unstyled">
                    <?php
                    $infos = [
                        ['icon' => 'person-fill-check', 'text' => 'Alle Jugendlichen zwischen <strong>12 und 18 Jahren</strong> aus Langensendelbach und Umgebung'],
                        ['icon' => 'gender-ambiguous',  'text' => 'Mädchen und Jungen gleichermaßen willkommen'],
                        ['icon' => 'calendar-check',    'text' => 'Treffen alle <strong>zwei Wochen samstags</strong> am Gerätehaus'],
                        ['icon' => 'cash-coin',         'text' => 'Kein Mitgliedsbeitrag für Jugendliche'],
                        ['icon' => 'shield-fill-check', 'text' => 'Unfallversicherung über den Freistaat Bayern'],
                        ['icon' => 'arrow-right-circle-fill', 'text' => 'Mit 18 Jahren nahtloser Übergang in die aktive Wehr möglich'],
                    ];
                    foreach ($infos as $info): ?>
                    <li class="d-flex gap-3 mb-3">
                        <i class="bi bi-<?= $info['icon'] ?> text-warning fs-5 flex-shrink-0 mt-1"></i>
                        <span><?= $info['text'] ?></span>
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
                    <a href="/formulare.php" class="btn btn-warning fw-bold w-100 mb-3">
                        <i class="bi bi-file-earmark-arrow-down me-2"></i>Anmeldeformular herunterladen
                    </a>
                    <p class="text-muted small mb-0 text-center">
                        Fragen? Einfach beim <a href="/kontakt.php">Jugendwart melden</a>.
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
            <div class="col-md-6 col-lg-4">
                <div class="admin-card p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                         style="width:72px;height:72px;background:var(--fw-red-pale);">
                        <i class="bi bi-person-fill text-danger" style="font-size:2rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Maria Schmidt</h6>
                    <p class="text-muted small mb-2">Jugendwartin</p>
                    <a href="/kontakt.php" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-envelope me-1"></i>Kontakt
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="admin-card p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                         style="width:72px;height:72px;background:var(--fw-red-pale);">
                        <i class="bi bi-person-fill text-danger" style="font-size:2rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Thomas Weber</h6>
                    <p class="text-muted small mb-2">Stellv. Jugendwart</p>
                    <a href="/kontakt.php" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-envelope me-1"></i>Kontakt
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="fw-section" style="background:linear-gradient(135deg,#e07800,#ff8c00);color:#fff;">
    <div class="container text-center">
        <i class="bi bi-stars fs-1 mb-3 d-block" style="opacity:.7;"></i>
        <h2 class="text-white mb-3">Neugierig geworden?</h2>
        <p class="mb-4 text-white" style="opacity:.9;max-width:480px;margin:0 auto 1.5rem;">
            Komm einfach zu einem unserer nächsten Treffen vorbei – kein Druck, kein Voranmeldung nötig!
        </p>
        <a href="/kalender.php" class="btn bg-white fw-bold" style="color:#e07800;">
            <i class="bi bi-calendar3 me-2"></i>Nächste Termine ansehen
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
