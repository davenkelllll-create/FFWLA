<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

$action  = $_GET['action'] ?? 'list';
$id      = $_GET['id'] ?? '';
$message = '';
$error   = '';

$uploadDir = UPLOADS_DIR . 'fahrzeuge/';
if (!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);

/** Bild- und Thumbnail-Datei eines Fahrzeugs von der Platte entfernen. */
function fahrzeugBilderLoeschen(array $f): void {
    foreach (['image', 'thumbnail'] as $key) {
        if (empty($f[$key])) continue;
        $path = __DIR__ . '/../' . ltrim($f[$key], '/');
        if (is_file($path)) @unlink($path);
    }
}

// ---- DELETE ----
if ($action === 'delete' && $id) {
    requireCsrf();
    $items = getFahrzeuge();
    foreach ($items as $f) {
        if (($f['id'] ?? '') === $id) { fahrzeugBilderLoeschen($f); break; }
    }
    $items = array_values(array_filter($items, fn($f) => ($f['id'] ?? '') !== $id));
    saveFahrzeuge($items);
    header('Location: /admin/fahrzeuge.php?msg=deleted');
    exit;
}

// ---- MOVE (Reihenfolge) ----
if ($action === 'move' && $id) {
    requireCsrf();
    $dir   = ($_GET['dir'] ?? '') === 'up' ? -1 : 1;
    $items = getFahrzeuge();
    foreach ($items as $i => $f) {
        if (($f['id'] ?? '') !== $id) continue;
        $j = $i + $dir;
        if ($j >= 0 && $j < count($items)) {
            [$items[$i], $items[$j]] = [$items[$j], $items[$i]];
            saveFahrzeuge($items);
        }
        break;
    }
    header('Location: /admin/fahrzeuge.php');
    exit;
}

// ---- SAVE (neu oder bearbeiten) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $postId = trim($_POST['id'] ?? '');
    $name   = trim($_POST['name'] ?? '');

    if ($name === '') {
        $error = 'Bitte eine Bezeichnung eingeben.';
    } else {
        $items = getFahrzeuge();

        // Vorhandenen Datensatz finden (für Bildübernahme beim Bearbeiten)
        $existing = null;
        foreach ($items as $f) {
            if (($f['id'] ?? '') === $postId) { $existing = $f; break; }
        }

        $record = [
            'id'           => $postId ?: (slugify($name) ?: 'fahrzeug-' . count($items)),
            'name'         => $name,
            'kategorie'    => trim($_POST['kategorie'] ?? ''),
            'badge_class'  => $_POST['badge_class'] ?? 'badge-einsatz',
            'baujahr'      => trim($_POST['baujahr'] ?? ''),
            'funkrufname'  => trim($_POST['funkrufname'] ?? ''),
            'besatzung'    => trim($_POST['besatzung'] ?? ''),
            'beschreibung' => trim($_POST['beschreibung'] ?? ''),
            'icon'         => trim($_POST['icon'] ?? '') ?: 'truck-front-fill',
            'image'        => $existing['image']     ?? '',
            'thumbnail'    => $existing['thumbnail'] ?? '',
        ];

        // Bild entfernen
        if (!empty($_POST['remove_image']) && $existing) {
            fahrzeugBilderLoeschen($existing);
            $record['image'] = '';
            $record['thumbnail'] = '';
        }

        // Bild-Upload – Endung IMMER aus dem erkannten MIME-Typ ableiten,
        // niemals aus dem vom Nutzer gelieferten Dateinamen.
        if (!empty($_FILES['bild']['tmp_name']) && $_FILES['bild']['error'] === UPLOAD_ERR_OK) {
            $extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
            $mime   = mime_content_type($_FILES['bild']['tmp_name']);
            if (!isset($extMap[$mime])) {
                $error = 'Nur JPG, PNG, GIF oder WebP sind erlaubt.';
            } elseif ($_FILES['bild']['size'] > 12 * 1024 * 1024) {
                $error = 'Das Bild darf höchstens 12 MB groß sein.';
            } else {
                if ($existing) fahrzeugBilderLoeschen($existing);
                $ext   = $extMap[$mime];
                $base  = uniqid('fz_', true);
                $full  = $base . '.' . $ext;
                $thumb = 'thumb_' . $base . '.' . $ext;
                if (move_uploaded_file($_FILES['bild']['tmp_name'], $uploadDir . $full)) {
                    resizeImage($uploadDir . $full, $uploadDir . $thumb, 800, 600);
                    $record['image']     = '/uploads/fahrzeuge/' . $full;
                    $record['thumbnail'] = is_file($uploadDir . $thumb)
                        ? '/uploads/fahrzeuge/' . $thumb
                        : '/uploads/fahrzeuge/' . $full;
                }
            }
        }

        if (!$error) {
            if ($postId && $existing !== null) {
                foreach ($items as &$f) {
                    if (($f['id'] ?? '') === $postId) { $f = $record; break; }
                }
                unset($f);
                saveFahrzeuge($items);
                header('Location: /admin/fahrzeuge.php?msg=saved');
            } else {
                $items[] = $record;
                saveFahrzeuge($items);
                header('Location: /admin/fahrzeuge.php?msg=created');
            }
            exit;
        }
    }
}

// Flash-Meldungen
$msg = $_GET['msg'] ?? '';
if ($msg === 'saved')   $message = 'Fahrzeug gespeichert.';
if ($msg === 'created') $message = 'Fahrzeug angelegt.';
if ($msg === 'deleted') $message = 'Fahrzeug gelöscht.';

$editItem = null;
if ($action === 'edit' && $id) {
    foreach (getFahrzeuge() as $f) {
        if (($f['id'] ?? '') === $id) { $editItem = $f; break; }
    }
}

$allItems = getFahrzeuge();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fahrzeuge – Admin FF Langensendelbach</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="admin-body">
<div class="d-flex">
    <?php include 'partials/sidebar.php'; ?>
    <main class="admin-main flex-grow-1">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h4 class="fw-bold mb-0">Fahrzeuge</h4>
            <?php if ($action === 'list'): ?>
            <a href="?action=new" class="btn btn-danger btn-sm"><i class="bi bi-plus me-1"></i>Neues Fahrzeug</a>
            <?php else: ?>
            <a href="?" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Zurück zur Liste</a>
            <?php endif; ?>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible"><i class="bi bi-check-circle me-2"></i><?= h($message) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible"><i class="bi bi-exclamation-triangle me-2"></i><?= h($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
        <div class="admin-card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="admin-table" style="width:80px;">Bild</th>
                            <th class="admin-table">Bezeichnung</th>
                            <th class="admin-table">Kategorie</th>
                            <th class="admin-table">Baujahr</th>
                            <th class="admin-table" style="width:150px;">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allItems as $i => $f): ?>
                    <tr>
                        <td>
                            <?php if (!empty($f['thumbnail'])): ?>
                            <img src="<?= h($f['thumbnail']) ?>" alt="" style="width:64px;height:44px;object-fit:cover;border-radius:4px;">
                            <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center text-muted"
                                 style="width:64px;height:44px;background:var(--fw-gray-100);border-radius:4px;">
                                <i class="bi bi-<?= h($f['icon'] ?? 'truck-front-fill') ?>"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-semibold small"><?= h($f['name'] ?? '') ?></td>
                        <td><span class="fw-badge <?= h($f['badge_class'] ?? 'badge-einsatz') ?>"><?= h($f['kategorie'] ?? '') ?></span></td>
                        <td class="text-muted small"><?= h($f['baujahr'] ?? '') ?></td>
                        <td class="text-nowrap">
                            <?php if ($i > 0): ?>
                            <a href="?action=move&dir=up&id=<?= urlencode($f['id']) ?>&token=<?= csrfToken() ?>" class="btn btn-sm btn-outline-secondary" title="Nach oben"><i class="bi bi-arrow-up"></i></a>
                            <?php endif; ?>
                            <?php if ($i < count($allItems) - 1): ?>
                            <a href="?action=move&dir=down&id=<?= urlencode($f['id']) ?>&token=<?= csrfToken() ?>" class="btn btn-sm btn-outline-secondary" title="Nach unten"><i class="bi bi-arrow-down"></i></a>
                            <?php endif; ?>
                            <a href="?action=edit&id=<?= urlencode($f['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Bearbeiten"><i class="bi bi-pencil"></i></a>
                            <a href="?action=delete&id=<?= urlencode($f['id']) ?>&token=<?= csrfToken() ?>" class="btn btn-sm btn-outline-danger" title="Löschen"
                               onclick="return confirm('Fahrzeug wirklich löschen?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($allItems)): ?>
                    <tr><td colspan="5" class="text-muted text-center py-4">Noch keine Fahrzeuge hinterlegt. <a href="?action=new">Erstes Fahrzeug anlegen</a></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php else: ?>
        <div class="admin-card p-4" style="max-width:800px;">
            <h5 class="fw-bold mb-4"><?= $editItem ? 'Fahrzeug bearbeiten' : 'Neues Fahrzeug' ?></h5>
            <form method="POST" enctype="multipart/form-data">
                <?= csrfField() ?>
                <?php if ($editItem): ?>
                <input type="hidden" name="id" value="<?= h($editItem['id']) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Bezeichnung *</label>
                    <input type="text" class="form-control" name="name" required
                           value="<?= h($editItem['name'] ?? '') ?>" placeholder="z.B. LF 10 – Löschgruppenfahrzeug">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kategorie (Badge)</label>
                        <input type="text" class="form-control" name="kategorie"
                               value="<?= h($editItem['kategorie'] ?? '') ?>" placeholder="z.B. Löschfahrzeug">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Badge-Farbe</label>
                        <select class="form-select" name="badge_class">
                            <?php foreach (['badge-einsatz' => 'Rot (Einsatz)', 'badge-uebung' => 'Blau (Übung)', 'badge-veranstaltung' => 'Grün (Veranstaltung)', 'badge-jugend' => 'Orange (Jugend)'] as $val => $label): ?>
                            <option value="<?= $val ?>" <?= ($editItem['badge_class'] ?? 'badge-einsatz') === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Baujahr</label>
                        <input type="text" class="form-control" name="baujahr"
                               value="<?= h($editItem['baujahr'] ?? '') ?>" placeholder="z.B. 2018">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Funkrufname</label>
                        <input type="text" class="form-control" name="funkrufname"
                               value="<?= h($editItem['funkrufname'] ?? '') ?>" placeholder="z.B. Florian Langensendelbach 40/1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Besatzung</label>
                        <input type="text" class="form-control" name="besatzung"
                               value="<?= h($editItem['besatzung'] ?? '') ?>" placeholder="z.B. 1/8">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Beschreibung</label>
                    <textarea class="form-control" name="beschreibung" rows="4"
                              placeholder="Ausstattung, Einsatzzweck, Besonderheiten …"><?= h($editItem['beschreibung'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Symbol (falls kein Foto)</label>
                    <input type="text" class="form-control" name="icon"
                           value="<?= h($editItem['icon'] ?? 'truck-front-fill') ?>" placeholder="truck-front-fill">
                    <div class="form-text">
                        Name eines Bootstrap-Icons ohne <code>bi-</code>, z.B. <code>truck-front-fill</code>,
                        <code>truck-front</code>, <code>car-front-fill</code>. Übersicht:
                        <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener">icons.getbootstrap.com</a>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Foto</label>
                    <?php if (!empty($editItem['thumbnail'])): ?>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="<?= h($editItem['thumbnail']) ?>" alt="" style="width:120px;height:80px;object-fit:cover;border-radius:6px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="removeImg">
                            <label class="form-check-label small" for="removeImg">Foto entfernen</label>
                        </div>
                    </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="bild" accept="image/jpeg,image/png,image/gif,image/webp">
                    <div class="form-text">JPG, PNG, GIF oder WebP, max. 12 MB. Ein neues Foto ersetzt das bisherige.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Speichern</button>
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
