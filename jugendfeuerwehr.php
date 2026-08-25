<?php
require_once 'includes/functions.php';
$fuehrung  = getFuehrung();
$pageTitle = 'Jugendfeuerwehr';
$pageDescription = 'Jugendfeuerwehr Langensendelbach – Für Jugendliche von 12 bis 18 Jahren. Spaß, Teamgeist und verantwortungsvolles Handeln.';
include 'includes/header.php';
?>

<!-- JFW Hero Carousel -->
<?php
$jfwSlides = [
    [
        'image'    => '/images/jfw/slide-1.jpg',
        'gradient' => 'linear-gradient(135deg,#7a3800 0%,#e07800 60%,#ffaa00 100%)',
        'icon'     => 'stars',
        'eyebrow'  => 'Jugendfeuerwehr · Langensendelbach',
        'title'    => 'Feuer &amp; Flamme<br>für die Zukunft.',
        'text'     => 'In der Jugendfeuerwehr lernst du echte Feuerwehrtechnik, übst Teamwork und übernimmst Verantwortung – und das mit einer Menge Spaß!',
        'btn1_href'=> '/formulare.php',  'btn1_label' => 'Anmeldung herunterladen', 'btn1_icon' => 'file-earmark-arrow-down',
        'btn2_href'=> '/kontakt.php',    'btn2_label' => 'Kontakt aufnehmen',       'btn2_icon' => 'envelope',
        'accent'   => '#e07800',
    ],
    [
        'image'    => '/images/jfw/slide-2.jpg',
        'gradient' => 'linear-gradient(135deg,#4a2800 0%,#c05800 60%,#e07800 100%)',
        'icon'     => 'trophy-fill',
        'eyebrow'  => 'Wettbewerbe &amp; Leistungsabzeichen',
        'title'    => 'Trainieren, lernen,<br>gewinnen.',
        'text'     => 'Wir nehmen an Kreisbewerben und überregionalen Wettbewerben teil und bereiten uns mit Spaß und Ehrgeiz vor.',
        'btn1_href'=> '/kalender.php',   'btn1_label' => 'Termine ansehen',         'btn1_icon' => 'calendar3',
        'btn2_href'=> '/nachrichten.php','btn2_label' => 'Aktuelle Berichte',         'btn2_icon' => 'newspaper',
        'accent'   => '#c05800',
    ],
    [
        'image'    => '/images/jfw/slide-3.jpg',
        'gradient' => 'linear-gradient(135deg,#3a2000 0%,#a04000 60%,#d06000 100%)',
        'icon'     => 'people-fill',
        'eyebrow'  => 'Kameradschaft &amp; Gemeinschaft',
        'title'    => 'Neue Freunde,<br>echte Erlebnisse.',
        'text'     => 'Zeltlager, Ausflüge und gemeinsame Aktionen – in der Jugendfeuerwehr entstehen Freundschaften fürs Leben.',
        'btn1_href'=> '#mitmachen',      'btn1_label' => 'Jetzt mitmachen',          'btn1_icon' => 'person-plus-fill',
        'btn2_href'=> '/nachrichten.php','btn2_label' => 'Aktuelle Berichte',        'btn2_icon' => 'newspaper',
        'accent'   => '#a04000',
    ],
];
?>
<div id="jfwCarousel" class="carousel slide fw-hero-carousel" data-bs-ride="carousel" data-bs-interval="5500">
    <div class="carousel-indicators">
        <?php foreach ($jfwSlides as $i => $s): ?>
        <button type="button" data-bs-target="#jfwCarousel" data-bs-slide-to="<?= $i ?>"
                <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>
                aria-label="Slide <?= $i + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($jfwSlides as $i => $s):
            $hasImage = file_exists(__DIR__ . $s['image']); ?>
        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
            <div class="fw-slide-bg"
                 <?= $hasImage ? "style=\"background-image:url('{$s['image']}'), {$s['gradient']};\"" : "style=\"background:{$s['gradient']};\"" ?>>
                <div class="fw-slide-overlay" style="background:linear-gradient(to right,rgba(0,0,0,.7) 0%,rgba(0,0,0,.4) 55%,rgba(0,0,0,.1) 100%);"></div>
                <?php if (!$hasImage): ?>
                <div class="fw-slide-placeholder-icon"><i class="bi bi-<?= $s['icon'] ?>"></i></div>
                <?php endif; ?>
            </div>
            <div class="carousel-caption fw-slide-caption">
                <div class="container">
                    <p class="fw-hero__eyebrow animate-fade">
                        <i class="bi bi-stars me-1"></i><?= $s['eyebrow'] ?>
                    </p>
                    <h1 class="animate-slide"><?= $s['title'] ?></h1>
                    <p class="fw-slide-text animate-fade"><?= h($s['text']) ?></p>
                    <div class="d-flex flex-wrap gap-3 justify-content-start animate-fade">
                        <a href="<?= h($s['btn1_href']) ?>"
                           class="btn fw-bold"
                           style="background:<?= $s['accent'] ?>;color:#fff;border:none;">
                            <i class="bi bi-<?= $s['btn1_icon'] ?> me-1"></i><?= h($s['btn1_label']) ?>
                        </a>
                        <a href="<?= h($s['btn2_href']) ?>" class="btn-fw-outline">
                            <i class="bi bi-<?= $s['btn2_icon'] ?> me-1"></i><?= h($s['btn2_label']) ?>
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
            <?php $jfwStats = getInhalte('jfw_stats');
            $statCol = count($jfwStats) > 0 ? max(1, (int)floor(12 / count($jfwStats))) : 4;
            foreach ($jfwStats as $s): ?>
            <div class="col-<?= $statCol ?>">
                <div style="color:#ff8c00;font-size:2rem;font-weight:900;line-height:1;"><?= h($s['wert'] ?? '') ?></div>
                <div style="color:rgba(255,255,255,.5);font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;margin-top:.3rem;"><?= h($s['label'] ?? '') ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Was machen wir? -->
<section class="fw-section">
    <div class="container">
        <h2 class="fw-section-title mb-4">Was machen wir?</h2>
        <div class="row g-4">
            <?php foreach (getInhalte('jfw_aktivitaeten') as $a): ?>
            <div class="col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3 p-3 rounded h-100" style="background:var(--fw-gray-100);">
                    <div style="width:48px;height:48px;background:<?= h($a['color'] ?? '#CC0000') ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-<?= h($a['icon'] ?? 'fire') ?> text-white fs-5"></i>
                    </div>
                    <strong><?= h($a['titel'] ?? '') ?></strong>
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
                    <?php foreach (getInhalte('jfw_mitmachen') as $info): ?>
                    <li class="d-flex gap-3 mb-3">
                        <i class="bi bi-<?= h($info['icon'] ?? 'check-circle') ?> text-warning fs-5 flex-shrink-0 mt-1"></i>
                        <span><?= $info['text'] ?? '' ?></span>
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

<!-- Kinderfeuerwehr -->
<section class="fw-section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <?php $kfwBadge = getInhaltText('kfw_badge'); ?>
                <?php if ($kfwBadge): ?>
                <span class="fw-badge badge-jugend mb-2 d-inline-block"><?= h($kfwBadge) ?></span>
                <?php endif; ?>
                <h2 class="fw-section-title mb-3">Kinderfeuerwehr</h2>
                <p class="text-muted"><?= getInhaltText('kfw_text1') ?></p>
                <p class="text-muted mb-0"><?= getInhaltText('kfw_text2') ?></p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <?php foreach (getInhalte('kfw_karten') as $k): ?>
                    <div class="col-sm-6">
                        <div class="d-flex gap-3 p-3 rounded h-100" style="background:var(--fw-gray-100);">
                            <div style="width:44px;height:44px;background:#e07800;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-<?= h($k['icon'] ?? 'star-fill') ?> text-white fs-5"></i>
                            </div>
                            <div>
                                <strong class="d-block mb-1"><?= h($k['titel'] ?? '') ?></strong>
                                <span class="text-muted small"><?= h($k['text'] ?? '') ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ansprechpartner -->
<?php
// Wiederverwendbare Karten-Ausgabe für eine Ansprechpartner-Gruppe
function jfwAnsprechpartnerKarten(array $personen): void {
    foreach ($personen as $p): ?>
    <div class="col-md-6 col-lg-4">
        <div class="admin-card p-4 text-center">
            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                 style="width:72px;height:72px;background:var(--fw-red-pale);">
                <i class="bi bi-person-fill text-danger" style="font-size:2rem;"></i>
            </div>
            <h6 class="fw-bold mb-1"><?= h($p['name']) ?></h6>
            <p class="text-muted small mb-2"><?= h($p['funktion']) ?></p>
            <?php if (!empty($p['email'])): ?>
            <a href="mailto:<?= h($p['email']) ?>" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-envelope me-1"></i>E-Mail
            </a>
            <?php else: ?>
            <a href="/kontakt.php" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-envelope me-1"></i>Kontakt
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach;
}
?>
<section class="fw-section">
    <div class="container">
        <h2 class="fw-section-title mb-4">Ansprechpartner Jugendfeuerwehr</h2>
        <div class="row g-4">
            <?php jfwAnsprechpartnerKarten($fuehrung['jugendfeuerwehr']); ?>
            <?php if (empty($fuehrung['jugendfeuerwehr'])): ?>
            <div class="col-12 text-muted">Keine Ansprechpartner hinterlegt.</div>
            <?php endif; ?>
        </div>

        <?php if (!empty($fuehrung['kinderfeuerwehr'])): ?>
        <h2 class="fw-section-title mb-4 mt-5">Ansprechpartner Kinderfeuerwehr</h2>
        <div class="row g-4">
            <?php jfwAnsprechpartnerKarten($fuehrung['kinderfeuerwehr']); ?>
        </div>
        <?php endif; ?>
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
