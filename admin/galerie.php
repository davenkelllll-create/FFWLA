<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

$action  = $_GET['action'] ?? 'list';
$id      = $_GET['id'] ?? '';
$message = '';
$error   = '';

$allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

// ---- DELETE ALBUM ----
if ($action === 'delete' && $id) {
    requireCsrf();
    $items = loadJson('galerien.json');
    $items = array_values(array_filter($items, fn($i) => $i['id'] !== $id));
    saveJson('galerien.json', $items);
    // Remove the album directory and all photos so nothing is orphaned on disk.
    $albumDir = UPLOADS_DIR . 'galerie/' . basename($id) . '/';
    if (is_dir($albumDir)) {
        foreach (glob($albumDir . '*') ?: [] as $f) { @unlink($f); }
        @rmdir($albumDir);
    }
    header('Location: /admin/galerie.php?msg=deleted');
    exit;
}

// ---- DELETE PHOTO ----
if ($action === 'deletephoto' && $id) {
    requireCsrf();
    // basename() neutralises any ../ traversal in the album/file parameters.
    $albumId   = basename($_GET['album'] ?? '');
    $photoFile = basename($_GET['file'] ?? '');
    if ($albumId && $photoFile) {
        $albumDir = UPLOADS_DIR . 'galerie/' . $albumId . '/';
        $indexFile = $albumDir . 'index.json';
        $photos = file_exists($indexFile) ? (json_decode(file_get_contents($indexFile), true) ?? []) : [];
        $thumbToDelete = null;
        foreach ($photos as $p) {
            if (($p['full'] ?? '') === $photoFile) { $thumbToDelete = basename($p['thumb'] ?? ''); break; }
        }
        $photos = array_values(array_filter($photos, fn($p) => ($p['full'] ?? '') !== $photoFile && ($p['thumb'] ?? '') !== $photoFile));
        file_put_contents($indexFile, json_encode($photos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        @unlink($albumDir . $photoFile);
        if ($thumbToDelete) @unlink($albumDir . $thumbToDelete);

        // Keep galerien.json in sync: photo count and cover image.
        $galerien = loadJson('galerien.json');
        foreach ($galerien as &$g) {
            if ($g['id'] === $albumId) {
                $g['photo_count'] = count($photos);
                $g['cover_thumb'] = !empty($photos[0]['thumb'])
                    ? '/uploads/galerie/' . $albumId . '/' . $photos[0]['thumb']
                    : '';
                break;
            }
        }
        unset($g);
        saveJson('galerien.json', $galerien);
    }
    header('Location: /admin/galerie.php?action=photos&id=' . urlencode($albumId) . '&msg=photodelete');
    exit;
}

// ---- UPLOAD PHOTOS ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'upload') {
    requireCsrf();
    $albumId = basename($_POST['album_id'] ?? '');
    $albumDir = UPLOADS_DIR . 'galerie/' . $albumId . '/';

    if (!$albumId || !is_dir($albumDir)) {
        $error = 'Album nicht gefunden.';
    } elseif (empty($_FILES['photos']['name'][0])) {
        $error = 'Keine Dateien ausgewählt.';
    } else {
        $indexFile = $albumDir . 'index.json';
        $photos = file_exists($indexFile) ? (json_decode(file_get_contents($indexFile), true) ?? []) : [];
        $uploaded = 0;

        // Derive the extension from the DETECTED mime type, never from the
        // user-supplied filename – otherwise a polyglot image named *.php could
        // land in this web-served directory.
        $extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
        $skipped = 0;
        foreach ($_FILES['photos']['tmp_name'] as $i => $tmpName) {
            if ($_FILES['photos']['error'][$i] !== UPLOAD_ERR_OK) { $skipped++; continue; }
            $mimeType = mime_content_type($tmpName);
            if (!isset($extMap[$mimeType])) { $skipped++; continue; }
            if (($_FILES['photos']['size'][$i] ?? 0) > 12 * 1024 * 1024) { $skipped++; continue; } // max 12 MB

            $ext  = $extMap[$mimeType];
            $base = uniqid('img_', true);
            $fullFile  = $base . '.' . $ext;
            $thumbFile = 'thumb_' . $base . '.' . $ext;

            move_uploaded_file($tmpName, $albumDir . $fullFile);
            resizeImage($albumDir . $fullFile, $albumDir . $thumbFile, 400, 300);

            $caption = $_POST['captions'][$i] ?? '';
            $photos[] = ['thumb' => $thumbFile, 'full' => $fullFile, 'caption' => $caption];
            $uploaded++;
        }

        file_put_contents($indexFile, json_encode($photos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Update photo count in galerien.json
        $galerien = loadJson('galerien.json');
        foreach ($galerien as &$g) {
            if ($g['id'] === $albumId) {
                $g['photo_count'] = count($photos);
                if (empty($g['cover_thumb']) && !empty($photos[0]['thumb'])) {
                    $g['cover_thumb'] = '/uploads/galerie/' . $albumId . '/' . $photos[0]['thumb'];
                }
                break;
            }
        }
        unset($g);
        saveJson('galerien.json', $galerien);

        header('Location: /admin/galerie.php?action=photos&id=' . urlencode($albumId) . '&msg=' . $uploaded . 'uploaded&skipped=' . $skipped);
        exit;
    }
}

// ---- SAVE ALBUM (new/edit) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action !== 'upload') {
    requireCsrf();
    $postId      = trim($_POST['id'] ?? '');
    $title       = trim($_POST['title'] ?? '');
    $date        = $_POST['date'] ?? date('Y-m-d');
    $category    = $_POST['category'] ?? 'veranstaltung';
    $description = trim($_POST['description'] ?? '');

    if (empty($title)) {
        $error = 'Bitte einen Titel eingeben.';
    } else {
        $items = loadJson('galerien.json');
        if ($postId) {
            foreach ($items as &$item) {
                if ($item['id'] === $postId) {
                    $item['title'] = $title;
                    $item['date']  = $date;
                    $item['category'] = $category;
                    $item['description'] = $description;
                    break;
                }
            }
            unset($item);
            saveJson('galerien.json', $items);
            header('Location: /admin/galerie.php?msg=saved');
            exit;
        } else {
            $newId  = slugify($title) . '-' . str_replace('-', '', $date);
            $newDir = UPLOADS_DIR . 'galerie/' . $newId . '/';
            if (!is_dir($newDir)) mkdir($newDir, 0755, true);
            file_put_contents($newDir . 'index.json', '[]');

            $items[] = [
                'id'          => $newId,
                'title'       => $title,
                'date'        => $date,
                'category'    => $category,
                'cover_thumb' => '',
                'photo_count' => 0,
                'description' => $description,
            ];
            saveJson('galerien.json', $items);
            header('Location: /admin/galerie.php?action=photos&id=' . urlencode($newId) . '&msg=created');
            exit;
        }
    }
}

$msg = $_GET['msg'] ?? '';
if ($msg === 'saved')   $message = 'Album gespeichert.';
if ($msg === 'created') $message = 'Album erstellt. Jetzt Fotos hochladen!';
if ($msg === 'deleted') $message = 'Album gelöscht.';
if ($msg === 'photodelete') $message = 'Foto gelöscht.';
if (str_ends_with($msg, 'uploaded')) {
    $message = (int)$msg . ' Foto(s) hochgeladen.';
    $skip = (int)($_GET['skipped'] ?? 0);
    if ($skip > 0) $message .= " $skip Datei(en) übersprungen (kein Bild, zu groß oder fehlerhaft).";
}

$editItem = null;
if (($action === 'edit') && $id) {
    foreach (loadJson('galerien.json') as $g) {
        if ($g['id'] === $id) { $editItem = $g; break; }
    }
}

$allItems = getGalerien();
$h = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

// Photos for photos view
$albumPhotos = [];
$currentAlbum = null;
if ($action === 'photos' && $id) {
    foreach ($allItems as $g) { if ($g['id'] === $id) { $currentAlbum = $g; break; } }
    $indexFile = UPLOADS_DIR . 'galerie/' . $id . '/index.json';
    if (file_exists($indexFile)) $albumPhotos = json_decode(file_get_contents($indexFile), true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie – Admin FF Langensendelbach</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="admin-body">
<div class="d-flex">
    <?php include 'partials/sidebar.php'; ?>
    <main class="admin-main flex-grow-1">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h4 class="fw-bold mb-0">Galerie</h4>
            <?php if ($action === 'list'): ?>
            <a href="?action=new" class="btn btn-danger btn-sm"><i class="bi bi-plus me-1"></i>Neues Album</a>
            <?php else: ?>
            <a href="?" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Zurück zur Liste</a>
            <?php endif; ?>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible"><i class="bi bi-check-circle me-2"></i><?= $h($message) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible"><i class="bi bi-exclamation-triangle me-2"></i><?= $h($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
        <!-- Album list -->
        <div class="admin-card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="admin-table">Album</th>
                            <th class="admin-table">Kategorie</th>
                            <th class="admin-table">Datum</th>
                            <th class="admin-table">Fotos</th>
                            <th class="admin-table">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allItems as $item): ?>
                    <tr>
                        <td class="fw-semibold small"><?= $h($item['title']) ?></td>
                        <td><span class="fw-badge badge-<?= $h($item['category'] ?? '') ?>"><?= $h(categoryLabel($item['category'] ?? '')) ?></span></td>
                        <td class="text-muted small"><?= formatDate($item['date']) ?></td>
                        <td class="text-muted small"><?= (int)($item['photo_count'] ?? 0) ?></td>
                        <td>
                            <a href="?action=photos&id=<?= urlencode($item['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="Fotos" aria-label="Fotos verwalten"><i class="bi bi-images"></i></a>
                            <a href="?action=edit&id=<?= urlencode($item['id']) ?>" class="btn btn-sm btn-outline-secondary me-1" title="Bearbeiten" aria-label="Album bearbeiten"><i class="bi bi-pencil"></i></a>
                            <a href="?action=delete&id=<?= urlencode($item['id']) ?>&token=<?= csrfToken() ?>" class="btn btn-sm btn-outline-danger" title="Löschen" aria-label="Album löschen" onclick="return confirm('Album wirklich löschen?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($allItems)): ?>
                    <tr><td colspan="5" class="text-muted text-center py-4">Noch keine Alben. <a href="?action=new">Erstes Album erstellen</a></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php elseif ($action === 'new' || $action === 'edit'): ?>
        <!-- Album form -->
        <div class="admin-card p-4" style="max-width:600px;">
            <h5 class="fw-bold mb-4"><?= $editItem ? 'Album bearbeiten' : 'Neues Album' ?></h5>
            <form method="POST">
                <?= csrfField() ?>
                <?php if ($editItem): ?>
                <input type="hidden" name="id" value="<?= $h($editItem['id']) ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Albumtitel *</label>
                    <input type="text" class="form-control" name="title" required value="<?= $h($editItem['title'] ?? '') ?>" placeholder="z.B. Übung März 2024">
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Datum</label>
                        <input type="date" class="form-control" name="date" value="<?= $h($editItem['date'] ?? date('Y-m-d')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategorie</label>
                        <select class="form-select" name="category">
                            <?php foreach (['einsatz'=>'Einsatz','uebung'=>'Übung','veranstaltung'=>'Veranstaltung','jugend'=>'Jugendfeuerwehr'] as $v => $l): ?>
                            <option value="<?= $v ?>" <?= ($editItem['category'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Beschreibung</label>
                    <textarea class="form-control" name="description" rows="2"><?= $h($editItem['description'] ?? '') ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Speichern</button>
                    <a href="?" class="btn btn-outline-secondary">Abbrechen</a>
                </div>
            </form>
        </div>

        <?php elseif ($action === 'photos' && $currentAlbum): ?>
        <!-- Photos management -->
        <div class="mb-3">
            <h5 class="fw-bold"><?= $h($currentAlbum['title']) ?></h5>
            <span class="text-muted small"><?= count($albumPhotos) ?> Foto(s)</span>
        </div>

        <!-- Upload form -->
        <div class="admin-card p-4 mb-4" style="max-width:700px;">
            <h6 class="fw-bold mb-3"><i class="bi bi-upload me-2 text-danger"></i>Fotos hochladen</h6>
            <form method="POST" action="?action=upload" enctype="multipart/form-data">
                <?= csrfField() ?>
                <input type="hidden" name="album_id" value="<?= $h($currentAlbum['id']) ?>">
                <div class="mb-3">
                    <input type="file" class="form-control" name="photos[]" multiple accept="image/*" required>
                    <div class="form-text">JPG, PNG, WebP – mehrere Dateien möglich</div>
                </div>
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-upload me-1"></i>Hochladen
                </button>
            </form>
        </div>

        <!-- Photo grid -->
        <?php if (empty($albumPhotos)): ?>
        <p class="text-muted">Noch keine Fotos. Lade oben Fotos hoch.</p>
        <?php else: ?>
        <div class="row g-3">
            <?php foreach ($albumPhotos as $photo): ?>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="position-relative">
                    <img src="/uploads/galerie/<?= $h($currentAlbum['id']) ?>/<?= $h($photo['thumb']) ?>"
                         alt="<?= $h($photo['caption'] ?? '') ?>"
                         class="img-fluid rounded" style="aspect-ratio:4/3;object-fit:cover;width:100%;">
                    <a href="?action=deletephoto&id=<?= urlencode($currentAlbum['id']) ?>&album=<?= urlencode($currentAlbum['id']) ?>&file=<?= urlencode($photo['full']) ?>&token=<?= csrfToken() ?>"
                       class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-1"
                       style="line-height:1;"
                       onclick="return confirm('Foto löschen?')"
                       title="Löschen"
                       aria-label="Foto löschen">
                        <i class="bi bi-x"></i>
                    </a>
                    <?php if (!empty($photo['caption'])): ?>
                    <div class="text-muted" style="font-size:.7rem;margin-top:.25rem;"><?= $h($photo['caption']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>

    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
