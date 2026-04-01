<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

$action  = $_GET['action'] ?? 'list';
$id      = $_GET['id'] ?? '';
$message = '';
$error   = '';

// ---- DELETE ----
if ($action === 'delete' && $id) {
    $items = loadJson('formulare.json');
    $deleted = null;
    $items = array_values(array_filter($items, function($i) use ($id, &$deleted) {
        if ($i['id'] === $id) { $deleted = $i; return false; }
        return true;
    }));
    if ($deleted) {
        @unlink(UPLOADS_DIR . 'formulare/' . $deleted['datei']);
    }
    saveJson('formulare.json', $items);
    header('Location: /admin/formulare.php?msg=deleted');
    exit;
}

// ---- UPLOAD ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titel       = trim($_POST['titel'] ?? '');
    $beschreibung = trim($_POST['beschreibung'] ?? '');
    $kategorie   = $_POST['kategorie'] ?? 'sonstiges';
    $postId      = trim($_POST['id'] ?? '');

    if (empty($titel)) {
        $error = 'Bitte einen Titel eingeben.';
    } else {
        $items = loadJson('formulare.json');

        // Handle file upload for new formulare
        $dateiName = $postId ? '' : '';
        if (!$postId && !empty($_FILES['datei']['tmp_name'])) {
            $tmpFile  = $_FILES['datei']['tmp_name'];
            $origName = basename($_FILES['datei']['name']);
            $mimeType = mime_content_type($tmpFile);
            if ($mimeType !== 'application/pdf') {
                $error = 'Nur PDF-Dateien sind erlaubt.';
            } else {
                $safeFile = slugify(pathinfo($origName, PATHINFO_FILENAME)) . '.pdf';
                $dest     = UPLOADS_DIR . 'formulare/' . $safeFile;
                move_uploaded_file($tmpFile, $dest);
                $dateiName = $safeFile;
            }
        }

        if (!$error) {
            if ($postId) {
                foreach ($items as &$item) {
                    if ($item['id'] === $postId) {
                        $item['titel']       = $titel;
                        $item['beschreibung'] = $beschreibung;
                        $item['kategorie']   = $kategorie;
                        // Replace file if new one uploaded
                        if (!empty($_FILES['datei']['tmp_name'])) {
                            $tmpFile  = $_FILES['datei']['tmp_name'];
                            $mimeType = mime_content_type($tmpFile);
                            if ($mimeType === 'application/pdf') {
                                $safeFile = slugify(pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME)) . '.pdf';
                                move_uploaded_file($tmpFile, UPLOADS_DIR . 'formulare/' . $safeFile);
                                $item['datei'] = $safeFile;
                            }
                        }
                        break;
                    }
                }
                unset($item);
                saveJson('formulare.json', $items);
                header('Location: /admin/formulare.php?msg=saved');
                exit;
            } elseif ($dateiName) {
                $newId = slugify($titel);
                $items[] = [
                    'id'          => $newId,
                    'titel'       => $titel,
                    'beschreibung' => $beschreibung,
                    'datei'       => $dateiName,
                    'kategorie'   => $kategorie,
                    'version'     => date('Y-m'),
                ];
                saveJson('formulare.json', $items);
                header('Location: /admin/formulare.php?msg=created');
                exit;
            } else {
                $error = 'Bitte eine PDF-Datei auswählen.';
            }
        }
    }
}

$msg = $_GET['msg'] ?? '';
if ($msg === 'saved')   $message = 'Formular gespeichert.';
if ($msg === 'created') $message = 'Formular hochgeladen.';
if ($msg === 'deleted') $message = 'Formular gelöscht.';

$editItem = null;
if (($action === 'edit') && $id) {
    foreach (loadJson('formulare.json') as $f) {
        if ($f['id'] === $id) { $editItem = $f; break; }
    }
}

$allItems = getFormulare();
$h = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulare – Admin FF Langensendelbach</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="admin-body">
<div class="d-flex">
    <?php include 'partials/sidebar.php'; ?>
    <main class="admin-main flex-grow-1">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h4 class="fw-bold mb-0">Formulare</h4>
            <?php if ($action === 'list'): ?>
            <a href="?action=new" class="btn btn-danger btn-sm"><i class="bi bi-plus me-1"></i>Formular hochladen</a>
            <?php else: ?>
            <a href="?" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Zurück</a>
            <?php endif; ?>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible"><i class="bi bi-check-circle me-2"></i><?= $h($message) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible"><i class="bi bi-exclamation-triangle me-2"></i><?= $h($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
        <div class="admin-card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="admin-table">Titel</th>
                            <th class="admin-table">Kategorie</th>
                            <th class="admin-table">Datei</th>
                            <th class="admin-table">Vorhanden</th>
                            <th class="admin-table">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allItems as $item): ?>
                    <tr>
                        <td class="fw-semibold small"><?= $h($item['titel']) ?></td>
                        <td><span class="fw-badge badge-secondary"><?= $h(categoryLabel($item['kategorie'])) ?></span></td>
                        <td class="text-muted small font-monospace"><?= $h($item['datei']) ?></td>
                        <td><?= file_exists(UPLOADS_DIR . 'formulare/' . $item['datei']) ? '<span class="badge bg-success">Ja</span>' : '<span class="badge bg-danger">Fehlt</span>' ?></td>
                        <td>
                            <a href="?action=edit&id=<?= urlencode($item['id']) ?>" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-pencil"></i></a>
                            <a href="?action=delete&id=<?= urlencode($item['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Formular wirklich löschen?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($allItems)): ?>
                    <tr><td colspan="5" class="text-muted text-center py-4">Noch keine Formulare. <a href="?action=new">Erstes Formular hochladen</a></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="admin-card p-4" style="max-width:600px;">
            <h5 class="fw-bold mb-4"><?= $editItem ? 'Formular bearbeiten' : 'Formular hochladen' ?></h5>
            <form method="POST" enctype="multipart/form-data">
                <?php if ($editItem): ?>
                <input type="hidden" name="id" value="<?= $h($editItem['id']) ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Titel *</label>
                    <input type="text" class="form-control" name="titel" required value="<?= $h($editItem['titel'] ?? '') ?>" placeholder="z.B. Mitgliedsantrag">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Beschreibung</label>
                    <textarea class="form-control" name="beschreibung" rows="2"><?= $h($editItem['beschreibung'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kategorie</label>
                    <select class="form-select" name="kategorie">
                        <?php foreach (['mitgliedschaft'=>'Mitgliedschaft','jugend'=>'Jugendfeuerwehr','finanzen'=>'Finanzen','sonstiges'=>'Sonstiges'] as $v => $l): ?>
                        <option value="<?= $v ?>" <?= ($editItem['kategorie'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">PDF-Datei <?= $editItem ? '(leer lassen = unverändert)' : '*' ?></label>
                    <input type="file" class="form-control" name="datei" accept=".pdf" <?= $editItem ? '' : 'required' ?>>
                    <?php if ($editItem): ?>
                    <div class="form-text">Aktuelle Datei: <?= $h($editItem['datei']) ?></div>
                    <?php endif; ?>
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
