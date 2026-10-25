<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

$action  = $_GET['action'] ?? 'list';
$id      = $_GET['id'] ?? '';
$message = '';
$error   = '';

$uploadDir = UPLOADS_DIR . 'nachrichten/';
$uploadUrl = '/uploads/nachrichten/';
if (!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);

/**
 * Speichert ein hochgeladenes Bild samt verkleinerter Fassung.
 * Liefert ['full' => URL, 'thumb' => URL] oder null (Grund in $grund).
 */
function nachrichtBildSpeichern(string $tmp, int $err, int $size, int $maxW, int $maxH, ?string &$grund): ?array {
    global $uploadDir, $uploadUrl;
    if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
        $grund = 'größer als ' . ini_get('upload_max_filesize'); return null;
    }
    if ($err !== UPLOAD_ERR_OK || !is_uploaded_file($tmp)) { $grund = 'Upload fehlgeschlagen'; return null; }
    // Endung aus dem erkannten MIME-Typ, nie aus dem Dateinamen.
    $extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    $mime   = mime_content_type($tmp);
    if (!isset($extMap[$mime]))      { $grund = 'kein JPG/PNG/GIF/WebP'; return null; }
    if ($size > 12 * 1024 * 1024)    { $grund = 'größer als 12 MB'; return null; }

    $base  = uniqid('nb_', true);
    $full  = $base . '.' . $extMap[$mime];
    $thumb = 'thumb_' . $full;
    if (!move_uploaded_file($tmp, $uploadDir . $full)) {
        $grund = 'konnte nicht gespeichert werden (Schreibrechte uploads/nachrichten/ prüfen)'; return null;
    }
    resizeImage($uploadDir . $full, $uploadDir . $thumb, $maxW, $maxH);
    // Ohne GD entsteht keine Vorschau – dann das Original verwenden.
    if (!is_file($uploadDir . $thumb)) $thumb = $full;
    return ['full' => $uploadUrl . $full, 'thumb' => $uploadUrl . $thumb];
}

/** Löscht eine Bilddatei – nur innerhalb von uploads/nachrichten/. */
function nachrichtDateiLoeschen(string $url): void {
    global $uploadDir, $uploadUrl;
    if (!str_starts_with($url, $uploadUrl)) return;
    $path = $uploadDir . basename($url);
    if (is_file($path)) @unlink($path);
}

function nachrichtAlleBilderLoeschen(array $artikel): void {
    foreach (['header_image', 'thumbnail'] as $key) {
        if (!empty($artikel[$key])) nachrichtDateiLoeschen($artikel[$key]);
    }
    foreach ($artikel['images'] ?? [] as $img) {
        nachrichtDateiLoeschen($img['full'] ?? '');
        nachrichtDateiLoeschen($img['thumb'] ?? '');
    }
}

// ---- DELETE ----
if ($action === 'delete' && $id) {
    requireCsrf();
    $items = loadJson('nachrichten.json');
    foreach ($items as $i) {
        if ($i['id'] === $id) { nachrichtAlleBilderLoeschen($i); break; }
    }
    $items = array_values(array_filter($items, fn($i) => $i['id'] !== $id));
    saveJson('nachrichten.json', $items);
    header('Location: /admin/nachrichten.php?msg=deleted');
    exit;
}

// ---- SAVE (new or edit) ----
// Übersteigt die Anfrage post_max_size, verwirft PHP alle Felder – inkl.
// Sicherheits-Token. Vorher abfangen, sonst erscheint ein Sicherheitsfehler.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    $error = 'Die Bilder sind zusammen zu groß (Server-Limit: ' . ini_get('post_max_size')
           . '). Bitte weniger Bilder auf einmal hochladen und danach weitere über „Bearbeiten“ ergänzen.';
    $action = $_GET['action'] ?? 'new';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $postId    = trim($_POST['id'] ?? '');
    $title     = trim($_POST['title'] ?? '');
    $type      = $_POST['type'] ?? 'veranstaltung';
    $date      = $_POST['date'] ?? date('Y-m-d');
    $author    = trim($_POST['author'] ?? '');
    $excerpt   = trim($_POST['excerpt'] ?? '');
    $bodyHtml  = $_POST['body_html'] ?? '';

    if (empty($title)) {
        $error = 'Bitte einen Titel eingeben.';
    } else {
        $items = loadJson('nachrichten.json');

        // Bestehenden Artikel suchen oder neuen anlegen. Felder, die dieses
        // Formular nicht kennt, bleiben beim Bearbeiten unangetastet.
        $index = null;
        if ($postId) {
            foreach ($items as $k => $item) {
                if ($item['id'] === $postId) { $index = $k; break; }
            }
        }
        $isNew   = ($index === null);
        $artikel = $isNew
            ? ['id' => slugify($title) . '-' . date('Y-m-d'), 'thumbnail' => '', 'header_image' => '', 'images' => []]
            : $items[$index];

        $artikel['title']     = $title;
        $artikel['type']      = $type;
        $artikel['date']      = $date;
        $artikel['author']    = $author;
        $artikel['excerpt']   = $excerpt;
        $artikel['body_html'] = $bodyHtml;
        $artikel['images']    = $artikel['images'] ?? [];

        $fehler = [];

        // -- Headerbild: entfernen und/oder ersetzen --
        $neuerHeader = !empty($_FILES['header']['name']) && ($_FILES['header']['error'] ?? 4) !== UPLOAD_ERR_NO_FILE;
        if (!empty($_POST['header_entfernen']) || $neuerHeader) {
            if ($neuerHeader) {
                $bild = nachrichtBildSpeichern($_FILES['header']['tmp_name'], $_FILES['header']['error'],
                                               (int)$_FILES['header']['size'], 800, 600, $grund);
                if ($bild) {
                    nachrichtDateiLoeschen($artikel['header_image'] ?? '');
                    nachrichtDateiLoeschen($artikel['thumbnail'] ?? '');
                    $artikel['header_image'] = $bild['full'];
                    $artikel['thumbnail']    = $bild['thumb'];
                } else {
                    $fehler[] = 'Headerbild: ' . $grund;
                }
            } else {
                nachrichtDateiLoeschen($artikel['header_image'] ?? '');
                nachrichtDateiLoeschen($artikel['thumbnail'] ?? '');
                $artikel['header_image'] = '';
                $artikel['thumbnail']    = '';
            }
        }

        // -- Vorhandene Beitragsbilder: Bildunterschriften / entfernen --
        $captions  = $_POST['captions'] ?? [];
        $entfernen = $_POST['bild_entfernen'] ?? [];
        $behalten  = [];
        foreach ($artikel['images'] as $k => $img) {
            if (!empty($entfernen[$k])) {
                nachrichtDateiLoeschen($img['full'] ?? '');
                nachrichtDateiLoeschen($img['thumb'] ?? '');
                continue;
            }
            if (isset($captions[$k])) $img['caption'] = trim((string)$captions[$k]);
            $behalten[] = $img;
        }
        $artikel['images'] = $behalten;

        // -- Neue Beitragsbilder --
        if (!empty($_FILES['bilder']['name'][0])) {
            foreach ($_FILES['bilder']['tmp_name'] as $k => $tmp) {
                if (($_FILES['bilder']['error'][$k] ?? 4) === UPLOAD_ERR_NO_FILE) continue;
                $bild = nachrichtBildSpeichern($tmp, $_FILES['bilder']['error'][$k],
                                               (int)$_FILES['bilder']['size'][$k], 400, 300, $grund);
                if ($bild) {
                    $artikel['images'][] = $bild + ['caption' => ''];
                } else {
                    $fehler[] = basename($_FILES['bilder']['name'][$k]) . ': ' . $grund;
                }
            }
        }

        if ($isNew) {
            array_unshift($items, $artikel);
        } else {
            $items[$index] = $artikel;
        }
        saveJson('nachrichten.json', $items);

        if ($fehler) {
            // Text ist gespeichert – zurück ins Formular, damit die Hinweise sichtbar sind.
            $_SESSION['nachricht_fehler'] = $fehler;
            header('Location: /admin/nachrichten.php?action=edit&id=' . urlencode($artikel['id']) . '&msg=teilweise');
        } else {
            header('Location: /admin/nachrichten.php?msg=' . ($isNew ? 'created' : 'saved'));
        }
        exit;
    }
}

// Flash messages
$msg = $_GET['msg'] ?? '';
if ($msg === 'saved')   $message = 'Artikel gespeichert.';
if ($msg === 'created') $message = 'Artikel erstellt.';
if ($msg === 'deleted') $message = 'Artikel gelöscht.';
if ($msg === 'teilweise') {
    $message = 'Artikel gespeichert – einige Bilder konnten aber nicht übernommen werden:';
    $error   = implode(' · ', $_SESSION['nachricht_fehler'] ?? []);
    unset($_SESSION['nachricht_fehler']);
}

// Load data for form
$editItem = null;
if (($action === 'edit') && $id) {
    $items = loadJson('nachrichten.json');
    foreach ($items as $item) {
        if ($item['id'] === $id) { $editItem = $item; break; }
    }
}

$allItems = getNachrichten();
$h = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nachrichten – Admin FF Langensendelbach</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="admin-body">
<div class="d-flex">
    <?php include 'partials/sidebar.php'; ?>
    <main class="admin-main flex-grow-1">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h4 class="fw-bold mb-0">Nachrichten</h4>
            <?php if ($action === 'list'): ?>
            <a href="?action=new" class="btn btn-danger btn-sm">
                <i class="bi bi-plus me-1"></i>Neuer Artikel
            </a>
            <?php else: ?>
            <a href="?" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Zurück zur Liste
            </a>
            <?php endif; ?>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible"><i class="bi bi-check-circle me-2"></i><?= $h($message) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible"><i class="bi bi-exclamation-triangle me-2"></i><?= $h($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
        <!-- List -->
        <div class="admin-card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="admin-table">Titel</th>
                            <th class="admin-table">Typ</th>
                            <th class="admin-table">Datum</th>
                            <th class="admin-table">Autor</th>
                            <th class="admin-table">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allItems as $item): ?>
                    <tr>
                        <td class="fw-semibold small"><?= $h($item['title']) ?></td>
                        <td><span class="fw-badge <?= getTypBadgeClass($item['type']) ?>"><?= getTypLabel($item['type']) ?></span></td>
                        <td class="text-muted small"><?= formatDate($item['date']) ?></td>
                        <td class="text-muted small"><?= $h($item['author']) ?></td>
                        <td>
                            <a href="?action=edit&id=<?= urlencode($item['id']) ?>" class="btn btn-sm btn-outline-secondary me-1" title="Bearbeiten" aria-label="Artikel bearbeiten"><i class="bi bi-pencil"></i></a>
                            <a href="?action=delete&id=<?= urlencode($item['id']) ?>&token=<?= csrfToken() ?>" class="btn btn-sm btn-outline-danger" title="Löschen" aria-label="Artikel löschen" onclick="return confirm('Artikel wirklich löschen?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($allItems)): ?>
                    <tr><td colspan="5" class="text-muted text-center py-4">Noch keine Artikel vorhanden. <a href="?action=new">Ersten Artikel erstellen</a></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php else: ?>
        <!-- Form (new/edit) -->
        <div class="admin-card p-4" style="max-width:800px;">
            <h5 class="fw-bold mb-4"><?= $editItem ? 'Artikel bearbeiten' : 'Neuer Artikel' ?></h5>
            <form method="POST" enctype="multipart/form-data">
                <?= csrfField() ?>
                <?php if ($editItem): ?>
                <input type="hidden" name="id" value="<?= $h($editItem['id']) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Titel *</label>
                    <input type="text" class="form-control" name="title" required
                           value="<?= $h($editItem['title'] ?? '') ?>" placeholder="Titel des Artikels">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Typ</label>
                        <select class="form-select" name="type">
                            <?php foreach (['einsatz'=>'Einsatz','uebung'=>'Übung','veranstaltung'=>'Veranstaltung','presse'=>'Presse'] as $val => $label): ?>
                            <option value="<?= $val ?>" <?= ($editItem['type'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Datum</label>
                        <input type="date" class="form-control" name="date"
                               value="<?= $h($editItem['date'] ?? date('Y-m-d')) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Autor</label>
                        <input type="text" class="form-control" name="author"
                               value="<?= $h($editItem['author'] ?? '') ?>" placeholder="z.B. Pressewart">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kurztext (Vorschau)</label>
                    <textarea class="form-control" name="excerpt" rows="2"
                              placeholder="Kurze Zusammenfassung für die Vorschaukarte"><?= $h($editItem['excerpt'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Artikel-Text (HTML)</label>
                    <textarea class="form-control font-monospace" name="body_html" rows="10"
                              placeholder="<p>Vollständiger Artikeltext als HTML...</p>"><?= $h($editItem['body_html'] ?? '') ?></textarea>
                    <div class="form-text">HTML-Tags wie &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;, &lt;li&gt; sind erlaubt.</div>
                </div>

                <!-- Headerbild -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Headerbild</label>
                    <?php $headerBild = $editItem['header_image'] ?? '' ?: ($editItem['thumbnail'] ?? ''); ?>
                    <?php if ($headerBild): ?>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="<?= $h($headerBild) ?>" alt="" style="width:160px;height:90px;object-fit:cover;border-radius:6px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="header_entfernen" value="1" id="header_entfernen">
                            <label class="form-check-label small" for="header_entfernen">Headerbild entfernen</label>
                        </div>
                    </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="header" accept="image/jpeg,image/png,image/gif,image/webp">
                    <div class="form-text">Großes Bild oben im Artikel, gleichzeitig Vorschaubild in der Übersicht.<?= $headerBild ? ' Ein neues Bild ersetzt das bisherige.' : '' ?></div>
                </div>

                <!-- Beitragsbilder -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Weitere Bilder zum Beitrag</label>
                    <?php if (!empty($editItem['images'])): ?>
                    <div class="row g-2 mb-2">
                        <?php foreach ($editItem['images'] as $k => $img): ?>
                        <div class="col-6 col-md-4">
                            <div class="border rounded p-2 h-100">
                                <img src="<?= $h($img['thumb'] ?? $img['full'] ?? '') ?>" alt=""
                                     style="width:100%;height:110px;object-fit:cover;border-radius:4px;">
                                <input type="text" class="form-control form-control-sm mt-2" name="captions[<?= $k ?>]"
                                       value="<?= $h($img['caption'] ?? '') ?>" placeholder="Bildunterschrift (optional)">
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" name="bild_entfernen[<?= $k ?>]" value="1" id="bild_entfernen_<?= $k ?>">
                                    <label class="form-check-label small" for="bild_entfernen_<?= $k ?>">entfernen</label>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="bilder[]" multiple accept="image/jpeg,image/png,image/gif,image/webp">
                    <div class="form-text">Mehrere Bilder auf einmal auswählen (Strg/⌘ gedrückt halten). Sie erscheinen unter dem Text und lassen sich groß anklicken. Max. 12 MB je Bild, zusammen höchstens <?= $h(ini_get('post_max_size')) ?>. Bildunterschriften lassen sich nach dem Speichern ergänzen.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check-lg me-1"></i>Speichern
                    </button>
                    <a href="?" class="btn btn-outline-secondary">Abbrechen</a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
