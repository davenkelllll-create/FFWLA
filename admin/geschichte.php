<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();

    // Zeitleisten-Einträge aus den Formularzeilen neu aufbauen
    $eintraege = [];
    $jahre  = $_POST['jahr']  ?? [];
    $titel  = $_POST['titel'] ?? [];
    $texte  = $_POST['text']  ?? [];
    foreach ($jahre as $i => $jahr) {
        $jahr = trim($jahr);
        $t    = trim($titel[$i] ?? '');
        if ($jahr === '' && $t === '') continue; // leere Zeile überspringen
        $eintraege[] = [
            'jahr'  => $jahr,
            'titel' => $t,
            'text'  => trim($texte[$i] ?? ''),
        ];
    }

    saveGeschichte([
        'highlight' => [
            'label' => trim($_POST['hl_label'] ?? 'Gegründet'),
            'jahr'  => trim($_POST['hl_jahr']  ?? ''),
            'text'  => trim($_POST['hl_text']  ?? ''),
            'motto' => trim($_POST['hl_motto'] ?? ''),
        ],
        'eintraege' => $eintraege,
    ]);
    $message = 'Geschichte gespeichert.';
}

$geschichte = getGeschichte();
$hl = $geschichte['highlight'];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geschichte – Admin FF Langensendelbach</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="admin-body">
<div class="d-flex">
    <?php include 'partials/sidebar.php'; ?>
    <main class="admin-main flex-grow-1">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h4 class="fw-bold mb-0">Geschichte</h4>
            <a href="/ueber-uns.php" target="_blank" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i>Seite ansehen
            </a>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible"><i class="bi bi-check-circle me-2"></i><?= h($message) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible"><i class="bi bi-exclamation-triangle me-2"></i><?= h($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <form method="POST" id="geschichteForm">
            <?= csrfField() ?>

            <!-- Hervorhebung -->
            <div class="admin-card p-4 mb-4" style="max-width:900px;">
                <h6 class="fw-bold mb-3"><i class="bi bi-award me-2"></i>Hervorhebung (roter Kasten)</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Beschriftung</label>
                        <input type="text" class="form-control" name="hl_label" value="<?= h($hl['label'] ?? 'Gegründet') ?>" placeholder="Gegründet">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Jahr</label>
                        <input type="text" class="form-control" name="hl_jahr" value="<?= h($hl['jahr'] ?? '') ?>" placeholder="1878">
                        <div class="form-text">Leer lassen, um den Kasten auszublenden.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Zusatztext</label>
                        <input type="text" class="form-control" name="hl_text" value="<?= h($hl['text'] ?? '') ?>" placeholder="Ältester Verein der Gemeinde">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Wahlspruch / Motto</label>
                        <input type="text" class="form-control" name="hl_motto" value="<?= h($hl['motto'] ?? '') ?>" placeholder="»Gott zur Ehr, dem Nächsten zur Wehr«">
                    </div>
                </div>
            </div>

            <!-- Zeitleiste -->
            <div class="admin-card p-4 mb-4" style="max-width:900px;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Zeitleiste</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="add-eintrag">
                        <i class="bi bi-plus me-1"></i>Eintrag hinzufügen
                    </button>
                </div>

                <div id="eintraege">
                    <?php foreach ($geschichte['eintraege'] as $e): ?>
                    <div class="border rounded p-3 mb-3 eintrag-row">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Jahr</label>
                                <input type="text" class="form-control form-control-sm" name="jahr[]" value="<?= h($e['jahr'] ?? '') ?>" placeholder="1878">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-semibold">Überschrift</label>
                                <input type="text" class="form-control form-control-sm" name="titel[]" value="<?= h($e['titel'] ?? '') ?>" placeholder="Gründung der Freiwilligen Feuerwehr">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-row" title="Entfernen"><i class="bi bi-trash"></i></button>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Text</label>
                                <textarea class="form-control form-control-sm" name="text[]" rows="2" placeholder="Was ist in diesem Jahr passiert?"><?= h($e['text'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <p class="text-muted small mb-0">
                    Die Reihenfolge auf der Seite entspricht der Reihenfolge hier.
                    Leere Zeilen werden beim Speichern automatisch entfernt.
                </p>
            </div>

            <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Speichern</button>
        </form>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('add-eintrag').addEventListener('click', () => {
    const div = document.createElement('div');
    div.className = 'border rounded p-3 mb-3 eintrag-row';
    div.innerHTML = `
        <div class="row g-2">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Jahr</label>
                <input type="text" class="form-control form-control-sm" name="jahr[]" placeholder="1878">
            </div>
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Überschrift</label>
                <input type="text" class="form-control form-control-sm" name="titel[]" placeholder="Überschrift">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-row" title="Entfernen"><i class="bi bi-trash"></i></button>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Text</label>
                <textarea class="form-control form-control-sm" name="text[]" rows="2" placeholder="Beschreibung"></textarea>
            </div>
        </div>`;
    document.getElementById('eintraege').appendChild(div);
});
document.getElementById('geschichteForm').addEventListener('click', e => {
    if (e.target.closest('.remove-row')) e.target.closest('.eintrag-row').remove();
});
</script>
</body>
</html>
