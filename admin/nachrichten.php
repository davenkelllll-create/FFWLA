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
    requireCsrf();
    $items = loadJson('nachrichten.json');
    $items = array_values(array_filter($items, fn($i) => $i['id'] !== $id));
    saveJson('nachrichten.json', $items);
    header('Location: /admin/nachrichten.php?msg=deleted');
    exit;
}

// ---- SAVE (new or edit) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $postId    = trim($_POST['id'] ?? '');
    $title     = trim($_POST['title'] ?? '');
    $type      = $_POST['type'] ?? 'veranstaltung';
    $date      = $_POST['date'] ?? date('Y-m-d');
    $author    = trim($_POST['author'] ?? '');
    $excerpt   = trim($_POST['excerpt'] ?? '');
    $bodyHtml  = $_POST['body_html'] ?? '';
    $albumId   = trim($_POST['album_id'] ?? '');

    if (empty($title)) {
        $error = 'Bitte einen Titel eingeben.';
    } else {
        $items = loadJson('nachrichten.json');

        if ($postId) {
            // Edit existing
            foreach ($items as &$item) {
                if ($item['id'] === $postId) {
                    $item['title']     = $title;
                    $item['type']      = $type;
                    $item['date']      = $date;
                    $item['author']    = $author;
                    $item['excerpt']   = $excerpt;
                    $item['body_html'] = $bodyHtml;
                    $item['album_id']  = $albumId;
                    break;
                }
            }
            unset($item);
            saveJson('nachrichten.json', $items);
            header('Location: /admin/nachrichten.php?msg=saved');
            exit;
        } else {
            // New article
            $newId = slugify($title) . '-' . date('Y-m-d');
            $newArticle = [
                'id'        => $newId,
                'type'      => $type,
                'title'     => $title,
                'date'      => $date,
                'author'    => $author,
                'thumbnail' => '',
                'excerpt'   => $excerpt,
                'body_html' => $bodyHtml,
                'album_id'  => $albumId,
            ];
            array_unshift($items, $newArticle);
            saveJson('nachrichten.json', $items);
            header('Location: /admin/nachrichten.php?msg=created');
            exit;
        }
    }
}

// Flash messages
$msg = $_GET['msg'] ?? '';
if ($msg === 'saved')   $message = 'Artikel gespeichert.';
if ($msg === 'created') $message = 'Artikel erstellt.';
if ($msg === 'deleted') $message = 'Artikel gelöscht.';

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
            <form method="POST">
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

                <div class="mb-4">
                    <label class="form-label fw-semibold">Galerie-Album-ID (optional)</label>
                    <input type="text" class="form-control" name="album_id"
                           value="<?= $h($editItem['album_id'] ?? '') ?>"
                           placeholder="z.B. uebung-2024-03 – verlinkt zur Bildergalerie">
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
