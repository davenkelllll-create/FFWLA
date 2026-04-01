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
    $items = loadJson('termine.json');
    $items = array_values(array_filter($items, fn($i) => $i['id'] !== $id));
    saveJson('termine.json', $items);
    header('Location: /admin/termine.php?msg=deleted');
    exit;
}

// ---- SAVE ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId      = trim($_POST['id'] ?? '');
    $title       = trim($_POST['title'] ?? '');
    $startDate   = $_POST['start_date'] ?? '';
    $startTime   = $_POST['start_time'] ?? '00:00';
    $endDate     = $_POST['end_date'] ?? $startDate;
    $endTime     = $_POST['end_time'] ?? '';
    $category    = $_POST['category'] ?? 'veranstaltung';
    $location    = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $public      = !empty($_POST['public']);
    $color       = $_POST['color'] ?? '#CC0000';

    if (empty($title) || empty($startDate)) {
        $error = 'Bitte Titel und Startdatum eingeben.';
    } else {
        $start = $startDate . 'T' . $startTime . ':00';
        $end   = $endTime ? ($endDate . 'T' . $endTime . ':00') : null;

        $items = loadJson('termine.json');

        $newItem = [
            'id'          => $postId ?: slugify($title) . '-' . str_replace('-', '', $startDate),
            'title'       => $title,
            'start'       => $start,
            'end'         => $end,
            'color'       => $color,
            'category'    => $category,
            'location'    => $location,
            'description' => $description,
            'public'      => $public,
        ];

        if ($postId) {
            foreach ($items as &$item) {
                if ($item['id'] === $postId) { $item = $newItem; break; }
            }
            unset($item);
        } else {
            $items[] = $newItem;
        }

        saveJson('termine.json', $items);
        header('Location: /admin/termine.php?msg=' . ($postId ? 'saved' : 'created'));
        exit;
    }
}

$msg = $_GET['msg'] ?? '';
if ($msg === 'saved')   $message = 'Termin gespeichert.';
if ($msg === 'created') $message = 'Termin erstellt.';
if ($msg === 'deleted') $message = 'Termin gelöscht.';

$editItem = null;
if (($action === 'edit') && $id) {
    foreach (loadJson('termine.json') as $item) {
        if ($item['id'] === $id) { $editItem = $item; break; }
    }
}

$allItems = getTermine();
usort($allItems, fn($a, $b) => strcmp($b['start'], $a['start']));
$h = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

$colors = [
    '#CC0000' => 'Rot (Übung)',
    '#1a7a3c' => 'Grün (Veranstaltung)',
    '#FF8C00' => 'Orange (Jugendfeuerwehr)',
    '#1a4a8a' => 'Blau (Ausbildung)',
    '#6c757d' => 'Grau (Sonstiges)',
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termine – Admin FF Langensendelbach</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="admin-body">
<div class="d-flex">
    <?php include 'partials/sidebar.php'; ?>
    <main class="admin-main flex-grow-1">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h4 class="fw-bold mb-0">Termine</h4>
            <?php if ($action === 'list'): ?>
            <a href="?action=new" class="btn btn-danger btn-sm"><i class="bi bi-plus me-1"></i>Neuer Termin</a>
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
        <div class="admin-card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="admin-table">Titel</th>
                            <th class="admin-table">Datum</th>
                            <th class="admin-table">Ort</th>
                            <th class="admin-table">Öffentlich</th>
                            <th class="admin-table">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allItems as $item): ?>
                    <tr>
                        <td class="fw-semibold small">
                            <span class="rounded-circle me-2 d-inline-block" style="width:10px;height:10px;background:<?= $h($item['color'] ?? '#CC0000') ?>;"></span>
                            <?= $h($item['title']) ?>
                        </td>
                        <td class="text-muted small"><?= formatDate($item['start'], true) ?></td>
                        <td class="text-muted small"><?= $h($item['location'] ?? '') ?></td>
                        <td><?= !empty($item['public']) ? '<span class="badge bg-success">Ja</span>' : '<span class="badge bg-secondary">Nein</span>' ?></td>
                        <td>
                            <a href="?action=edit&id=<?= urlencode($item['id']) ?>" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-pencil"></i></a>
                            <a href="?action=delete&id=<?= urlencode($item['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Termin wirklich löschen?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($allItems)): ?>
                    <tr><td colspan="5" class="text-muted text-center py-4">Noch keine Termine. <a href="?action=new">Ersten Termin erstellen</a></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php else: ?>
        <?php
        // Parse start/end from editItem
        $startDate = $editItem ? substr($editItem['start'], 0, 10) : date('Y-m-d');
        $startTime = $editItem ? substr($editItem['start'], 11, 5) : '19:00';
        $endDate   = ($editItem && $editItem['end']) ? substr($editItem['end'], 0, 10) : $startDate;
        $endTime   = ($editItem && $editItem['end']) ? substr($editItem['end'], 11, 5) : '';
        ?>
        <div class="admin-card p-4" style="max-width:700px;">
            <h5 class="fw-bold mb-4"><?= $editItem ? 'Termin bearbeiten' : 'Neuer Termin' ?></h5>
            <form method="POST">
                <?php if ($editItem): ?>
                <input type="hidden" name="id" value="<?= $h($editItem['id']) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Titel *</label>
                    <input type="text" class="form-control" name="title" required value="<?= $h($editItem['title'] ?? '') ?>" placeholder="z.B. Monatsübung April">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Startdatum *</label>
                        <input type="date" class="form-control" name="start_date" required value="<?= $h($startDate) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Startzeit</label>
                        <input type="time" class="form-control" name="start_time" value="<?= $h($startTime) ?>">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Enddatum</label>
                        <input type="date" class="form-control" name="end_date" value="<?= $h($endDate) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Endzeit</label>
                        <input type="time" class="form-control" name="end_time" value="<?= $h($endTime) ?>">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategorie</label>
                        <select class="form-select" name="category">
                            <?php foreach (['uebung'=>'Übung','veranstaltung'=>'Veranstaltung','jugend'=>'Jugendfeuerwehr','ausbildung'=>'Ausbildung'] as $v => $l): ?>
                            <option value="<?= $v ?>" <?= ($editItem['category'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kalenderfarbe</label>
                        <select class="form-select" name="color">
                            <?php foreach ($colors as $val => $label): ?>
                            <option value="<?= $val ?>" <?= ($editItem['color'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ort</label>
                    <input type="text" class="form-control" name="location" value="<?= $h($editItem['location'] ?? '') ?>" placeholder="z.B. Gerätehaus Langensendelbach">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Beschreibung</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Kurze Beschreibung des Termins"><?= $h($editItem['description'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="public" name="public" value="1" <?= !empty($editItem['public']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="public">
                            Öffentlich sichtbar (im Kalender und auf der Startseite anzeigen)
                        </label>
                    </div>
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
