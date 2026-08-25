<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

/**
 * Zentrale Beschreibung aller bearbeitbaren Inhaltsblöcke.
 * Rendering UND Speichern lesen dieselbe Definition – so können Formular
 * und gespeicherte Struktur nicht auseinanderlaufen.
 */
$GRUPPEN = [
    'hero_slides' => [
        'tab'    => 'startseite',
        'titel'  => 'Hero-Slides',
        'hinweis'=> 'Die großen Bilder oben auf der Startseite. Ohne Bild wird der Farbverlauf mit Symbol angezeigt.',
        'pflicht'=> 'title',
        'felder' => [
            ['name' => 'eyebrow',    'label' => 'Überzeile',                'col' => 6],
            ['name' => 'title',      'label' => 'Überschrift (HTML: <br>)', 'col' => 6],
            ['name' => 'text',       'label' => 'Text',                     'col' => 12, 'type' => 'textarea'],
            ['name' => 'image',      'label' => 'Bildpfad',                 'col' => 6, 'placeholder' => '/images/hero/slide-1.jpg'],
            ['name' => 'icon',       'label' => 'Symbol (ohne bi-)',        'col' => 3, 'placeholder' => 'fire'],
            ['name' => 'gradient',   'label' => 'Farbverlauf (CSS)',        'col' => 3],
            ['name' => 'btn1_label', 'label' => 'Button 1 – Text',          'col' => 4],
            ['name' => 'btn1_href',  'label' => 'Button 1 – Link',          'col' => 4],
            ['name' => 'btn1_icon',  'label' => 'Button 1 – Symbol',        'col' => 4],
            ['name' => 'btn2_label', 'label' => 'Button 2 – Text',          'col' => 4],
            ['name' => 'btn2_href',  'label' => 'Button 2 – Link',          'col' => 4],
            ['name' => 'btn2_icon',  'label' => 'Button 2 – Symbol',        'col' => 4],
        ],
    ],
    'jfw_stats' => [
        'tab'    => 'jugend',
        'titel'  => 'Zahlen-Leiste',
        'hinweis'=> 'Der schwarze Balken unter dem Bild-Karussell.',
        'pflicht'=> 'wert',
        'felder' => [
            ['name' => 'wert',  'label' => 'Wert',        'col' => 4, 'placeholder' => '12'],
            ['name' => 'label', 'label' => 'Beschriftung','col' => 8, 'placeholder' => 'Jugendliche'],
        ],
    ],
    'jfw_aktivitaeten' => [
        'tab'    => 'jugend',
        'titel'  => 'Was machen wir?',
        'pflicht'=> 'titel',
        'felder' => [
            ['name' => 'titel', 'label' => 'Titel',              'col' => 6],
            ['name' => 'icon',  'label' => 'Symbol (ohne bi-)',  'col' => 3, 'placeholder' => 'fire'],
            ['name' => 'color', 'label' => 'Farbe',              'col' => 3, 'type' => 'color'],
        ],
    ],
    'jfw_mitmachen' => [
        'tab'    => 'jugend',
        'titel'  => 'Wer kann mitmachen?',
        'hinweis'=> 'Im Text sind HTML-Tags wie <strong> erlaubt.',
        'pflicht'=> 'text',
        'felder' => [
            ['name' => 'icon', 'label' => 'Symbol (ohne bi-)', 'col' => 3, 'placeholder' => 'check-circle'],
            ['name' => 'text', 'label' => 'Text',              'col' => 9],
        ],
    ],
    'kfw_karten' => [
        'tab'    => 'jugend',
        'titel'  => 'Kinderfeuerwehr – Karten',
        'pflicht'=> 'titel',
        'felder' => [
            ['name' => 'titel', 'label' => 'Titel',             'col' => 4],
            ['name' => 'icon',  'label' => 'Symbol (ohne bi-)', 'col' => 3, 'placeholder' => 'star-fill'],
            ['name' => 'text',  'label' => 'Text',              'col' => 5],
        ],
    ],
    'buergerecke_fuenf_w' => [
        'tab'    => 'buerger',
        'titel'  => 'Die 5 W (Notruf-Kasten)',
        'pflicht'=> 'wort',
        'felder' => [
            ['name' => 'wort', 'label' => 'Stichwort (fett)', 'col' => 3, 'placeholder' => 'Wo'],
            ['name' => 'text', 'label' => 'Erklärung',        'col' => 9],
        ],
    ],
    'buergerecke_tipps' => [
        'tab'    => 'buerger',
        'titel'  => 'Sicherheitstipps',
        'pflicht'=> 'titel',
        'felder' => [
            ['name' => 'titel', 'label' => 'Titel',             'col' => 6],
            ['name' => 'icon',  'label' => 'Symbol (ohne bi-)', 'col' => 3, 'placeholder' => 'bell-fill'],
            ['name' => 'color', 'label' => 'Farbe',             'col' => 3, 'type' => 'color'],
            ['name' => 'text',  'label' => 'Text',              'col' => 12, 'type' => 'textarea'],
        ],
    ],
    'links_uebergeordnet' => [
        'tab'    => 'links',
        'titel'  => 'Übergeordnete Stellen',
        'pflicht'=> 'titel',
        'felder' => [
            ['name' => 'titel',      'label' => 'Name',                'col' => 6],
            ['name' => 'url',        'label' => 'Link (URL)',          'col' => 6, 'placeholder' => 'https://…'],
            ['name' => 'text',       'label' => 'Beschreibung',        'col' => 8],
            ['name' => 'domain',     'label' => 'Domain-Badge',        'col' => 4, 'placeholder' => 'beispiel.de'],
            ['name' => 'icon',       'label' => 'Symbol (ohne bi-)',   'col' => 4, 'placeholder' => 'shield-fill'],
            ['name' => 'icon_color', 'label' => 'Symbol-Farbe',        'col' => 4],
            ['name' => 'icon_bg',    'label' => 'Symbol-Hintergrund',  'col' => 4],
        ],
    ],
    'links_behoerden' => [
        'tab'    => 'links',
        'titel'  => 'Notfall & Behörden',
        'pflicht'=> 'titel',
        'felder' => [
            ['name' => 'titel',      'label' => 'Name',                'col' => 6],
            ['name' => 'url',        'label' => 'Link (URL)',          'col' => 6, 'placeholder' => 'https://…'],
            ['name' => 'text',       'label' => 'Beschreibung',        'col' => 8],
            ['name' => 'domain',     'label' => 'Domain-Badge',        'col' => 4],
            ['name' => 'icon',       'label' => 'Symbol (ohne bi-)',   'col' => 4, 'placeholder' => 'building-fill'],
            ['name' => 'icon_color', 'label' => 'Symbol-Farbe',        'col' => 4],
            ['name' => 'icon_bg',    'label' => 'Symbol-Hintergrund',  'col' => 4],
        ],
    ],
    'links_notrufnummern' => [
        'tab'    => 'links',
        'titel'  => 'Wichtige Notrufnummern',
        'pflicht'=> 'num',
        'felder' => [
            ['name' => 'num',   'label' => 'Nummer',       'col' => 3, 'placeholder' => '112'],
            ['name' => 'label', 'label' => 'Bezeichnung',  'col' => 6],
            ['name' => 'color', 'label' => 'Farbe',        'col' => 3, 'type' => 'color'],
        ],
    ],
];

/** Einzelne Textfelder (kein Wiederholungsblock). */
$TEXTE = [
    'kfw_badge' => ['tab' => 'jugend', 'label' => 'Kinderfeuerwehr – Badge',       'type' => 'text'],
    'kfw_text1' => ['tab' => 'jugend', 'label' => 'Kinderfeuerwehr – Absatz 1',    'type' => 'textarea'],
    'kfw_text2' => ['tab' => 'jugend', 'label' => 'Kinderfeuerwehr – Absatz 2',    'type' => 'textarea'],
];

$message = '';

// ---- SPEICHERN ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $daten = getInhalte(); // vorhandene Struktur als Basis

    foreach ($GRUPPEN as $key => $def) {
        $rows    = [];
        $pflicht = $def['pflicht'];
        // Zeilenanzahl über das Pflichtfeld bestimmen
        $count = count($_POST[$key . '_' . $pflicht] ?? []);
        for ($i = 0; $i < $count; $i++) {
            $row = [];
            foreach ($def['felder'] as $feld) {
                $row[$feld['name']] = trim($_POST[$key . '_' . $feld['name']][$i] ?? '');
            }
            if ($row[$pflicht] === '') continue; // leere Zeile verwerfen
            $rows[] = $row;
        }
        $daten[$key] = $rows;
    }

    foreach ($TEXTE as $key => $def) {
        $daten[$key] = trim($_POST[$key] ?? '');
    }

    saveInhalte($daten);
    $message = 'Seiteninhalte gespeichert.';
}

$inhalte = getInhalte();
$aktiverTab = $_GET['tab'] ?? 'startseite';

$TABS = [
    'startseite' => ['label' => 'Startseite',      'icon' => 'house-fill'],
    'jugend'     => ['label' => 'Jugendfeuerwehr', 'icon' => 'stars'],
    'buerger'    => ['label' => 'Bürgerecke',      'icon' => 'info-square'],
    'links'      => ['label' => 'Links & Partner', 'icon' => 'link-45deg'],
];

/** Ein einzelnes Eingabefeld einer Wiederholungszeile ausgeben. */
function inhalteFeld(string $gruppe, array $feld, string $wert): string {
    $name = h($gruppe . '_' . $feld['name'] . '[]');
    $ph   = h($feld['placeholder'] ?? '');
    $type = $feld['type'] ?? 'text';
    $col  = (int)($feld['col'] ?? 6);

    if ($type === 'textarea') {
        $input = '<textarea class="form-control form-control-sm" name="' . $name . '" rows="2" placeholder="' . $ph . '">' . h($wert) . '</textarea>';
    } elseif ($type === 'color') {
        $input = '<input type="text" class="form-control form-control-sm" name="' . $name . '" value="' . h($wert) . '" placeholder="#CC0000">';
    } else {
        $input = '<input type="text" class="form-control form-control-sm" name="' . $name . '" value="' . h($wert) . '" placeholder="' . $ph . '">';
    }

    return '<div class="col-md-' . $col . '">'
         . '<label class="form-label small fw-semibold mb-1">' . h($feld['label']) . '</label>'
         . $input . '</div>';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seiteninhalte – Admin FF Langensendelbach</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="admin-body">
<div class="d-flex">
    <?php include 'partials/sidebar.php'; ?>
    <main class="admin-main flex-grow-1">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h4 class="fw-bold mb-0">Seiteninhalte</h4>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible"><i class="bi bi-check-circle me-2"></i><?= h($message) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <p class="text-muted small mb-3" style="max-width:900px;">
            Hier bearbeiten Sie die festen Texte der einzelnen Seiten. Nachrichten, Termine und
            Ansprechpartner haben eigene Menüpunkte. Leere Zeilen werden beim Speichern entfernt.
        </p>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4">
            <?php foreach ($TABS as $key => $tab): ?>
            <li class="nav-item">
                <button type="button" class="nav-link<?= $aktiverTab === $key ? ' active' : '' ?>" data-tab="<?= h($key) ?>">
                    <i class="bi bi-<?= h($tab['icon']) ?> me-1"></i><?= h($tab['label']) ?>
                </button>
            </li>
            <?php endforeach; ?>
        </ul>

        <form method="POST" id="inhalteForm">
            <?= csrfField() ?>

            <?php foreach ($TABS as $tabKey => $tab): ?>
            <div class="tab-pane-custom" data-pane="<?= h($tabKey) ?>" <?= $aktiverTab === $tabKey ? '' : 'style="display:none;"' ?>>

                <?php // Einzelne Textfelder dieses Tabs
                $tabTexte = array_filter($TEXTE, fn($d) => $d['tab'] === $tabKey);
                if ($tabTexte): ?>
                <div class="admin-card p-4 mb-4" style="max-width:1000px;">
                    <h6 class="fw-bold mb-3"><i class="bi bi-fonts me-2"></i>Texte</h6>
                    <div class="row g-3">
                        <?php foreach ($tabTexte as $key => $def): ?>
                        <div class="col-12">
                            <label class="form-label small fw-semibold mb-1"><?= h($def['label']) ?></label>
                            <?php if ($def['type'] === 'textarea'): ?>
                            <textarea class="form-control form-control-sm" name="<?= h($key) ?>" rows="3"><?= h($inhalte[$key] ?? '') ?></textarea>
                            <?php else: ?>
                            <input type="text" class="form-control form-control-sm" name="<?= h($key) ?>" value="<?= h($inhalte[$key] ?? '') ?>">
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-text mt-2">HTML-Tags wie &lt;strong&gt; sind hier erlaubt.</div>
                </div>
                <?php endif; ?>

                <?php // Wiederholungsblöcke dieses Tabs
                foreach ($GRUPPEN as $key => $def):
                    if ($def['tab'] !== $tabKey) continue;
                    $rows = $inhalte[$key] ?? [];
                ?>
                <div class="admin-card p-4 mb-4" style="max-width:1000px;">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <h6 class="fw-bold mb-0"><?= h($def['titel']) ?></h6>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-add="<?= h($key) ?>">
                            <i class="bi bi-plus me-1"></i>Hinzufügen
                        </button>
                    </div>
                    <?php if (!empty($def['hinweis'])): ?>
                    <p class="text-muted small mb-3"><?= h($def['hinweis']) ?></p>
                    <?php else: ?>
                    <div class="mb-3"></div>
                    <?php endif; ?>

                    <div data-rows="<?= h($key) ?>">
                        <?php foreach ($rows as $row): ?>
                        <div class="border rounded p-3 mb-2 inhalt-row">
                            <div class="row g-2">
                                <?php foreach ($def['felder'] as $feld): ?>
                                <?= inhalteFeld($key, $feld, (string)($row[$feld['name']] ?? '')) ?>
                                <?php endforeach; ?>
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                                        <i class="bi bi-trash me-1"></i>Entfernen
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Vorlage für neue Zeilen -->
                    <template data-template="<?= h($key) ?>">
                        <div class="border rounded p-3 mb-2 inhalt-row">
                            <div class="row g-2">
                                <?php foreach ($def['felder'] as $feld): ?>
                                <?= inhalteFeld($key, $feld, '') ?>
                                <?php endforeach; ?>
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                                        <i class="bi bi-trash me-1"></i>Entfernen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>

            <div class="position-sticky bottom-0 py-3" style="background:var(--fw-gray-100, #f0f0f0);">
                <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Alle Seiteninhalte speichern</button>
            </div>
        </form>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Tabs umschalten (alle Panes bleiben im selben Formular, damit einmal Speichern reicht)
document.querySelectorAll('[data-tab]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('[data-tab]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const target = btn.dataset.tab;
        document.querySelectorAll('[data-pane]').forEach(p => {
            p.style.display = p.dataset.pane === target ? '' : 'none';
        });
    });
});

// Zeile hinzufügen – klont die serverseitig gerenderte Vorlage
document.querySelectorAll('[data-add]').forEach(btn => {
    btn.addEventListener('click', () => {
        const key  = btn.dataset.add;
        const tpl  = document.querySelector(`[data-template="${key}"]`);
        const list = document.querySelector(`[data-rows="${key}"]`);
        list.appendChild(tpl.content.cloneNode(true));
    });
});

// Zeile entfernen
document.getElementById('inhalteForm').addEventListener('click', e => {
    if (e.target.closest('.remove-row')) e.target.closest('.inhalt-row').remove();
});
</script>
</body>
</html>
