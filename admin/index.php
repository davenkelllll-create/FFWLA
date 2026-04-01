<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

$nachrichten = getNachrichten();
$termine     = getTermine();
$galerien    = getGalerien();
$formulare   = getFormulare();

$stats = [
    'nachrichten' => count($nachrichten),
    'termine'     => count($termine),
    'galerien'    => count($galerien),
    'formulare'   => count($formulare),
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – FF Langensendelbach</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="admin-body">
<div class="d-flex">

    <!-- Sidebar -->
    <?php include 'partials/sidebar.php'; ?>

    <!-- Main -->
    <main class="admin-main flex-grow-1">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold mb-0">Dashboard</h4>
                <p class="text-muted small mb-0">Übersicht aller Inhalte</p>
            </div>
            <a href="/" class="btn btn-outline-secondary btn-sm" target="_blank">
                <i class="bi bi-box-arrow-up-right me-1"></i>Website öffnen
            </a>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <?php
            $statItems = [
                ['label' => 'Nachrichten', 'count' => $stats['nachrichten'], 'icon' => 'newspaper', 'link' => 'nachrichten.php', 'color' => 'danger'],
                ['label' => 'Termine',      'count' => $stats['termine'],     'icon' => 'calendar3',  'link' => 'termine.php',     'color' => 'warning'],
                ['label' => 'Galerien',     'count' => $stats['galerien'],    'icon' => 'images',     'link' => 'galerie.php',     'color' => 'info'],
                ['label' => 'Formulare',    'count' => $stats['formulare'],   'icon' => 'file-earmark-pdf', 'link' => 'formulare.php', 'color' => 'success'],
            ];
            foreach ($statItems as $s): ?>
            <div class="col-sm-6 col-xl-3">
                <div class="admin-card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold"><?= $s['label'] ?></div>
                            <div class="fs-2 fw-bold"><?= $s['count'] ?></div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-<?= $s['color'] ?> bg-opacity-10"
                             style="width:52px;height:52px;">
                            <i class="bi bi-<?= $s['icon'] ?> text-<?= $s['color'] ?> fs-4"></i>
                        </div>
                    </div>
                    <a href="<?= $s['link'] ?>" class="btn btn-outline-<?= $s['color'] ?> btn-sm mt-2 w-100">
                        Verwalten
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Recent news -->
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <h5>Neueste Nachrichten</h5>
                <a href="nachrichten.php?action=new" class="btn btn-danger btn-sm">
                    <i class="bi bi-plus me-1"></i>Neu
                </a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="admin-table">Titel</th>
                            <th class="admin-table">Typ</th>
                            <th class="admin-table">Datum</th>
                            <th class="admin-table">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($nachrichten, 0, 5) as $n): ?>
                        <tr>
                            <td class="fw-semibold small"><?= htmlspecialchars($n['title'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><span class="fw-badge <?= getTypBadgeClass($n['type']) ?>"><?= getTypLabel($n['type']) ?></span></td>
                            <td class="text-muted small"><?= formatDate($n['date']) ?></td>
                            <td>
                                <a href="nachrichten.php?action=edit&id=<?= urlencode($n['id']) ?>" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="nachrichten.php?action=delete&id=<?= urlencode($n['id']) ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Artikel wirklich löschen?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($nachrichten)): ?>
                        <tr><td colspan="4" class="text-muted text-center py-3">Noch keine Nachrichten</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Upcoming events -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>Nächste Termine</h5>
                <a href="termine.php?action=new" class="btn btn-danger btn-sm">
                    <i class="bi bi-plus me-1"></i>Neu
                </a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="admin-table">Titel</th>
                            <th class="admin-table">Datum</th>
                            <th class="admin-table">Ort</th>
                            <th class="admin-table">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $upcoming = array_filter($termine, fn($t) => strtotime($t['start']) >= time());
                        usort($upcoming, fn($a, $b) => strcmp($a['start'], $b['start']));
                        foreach (array_slice(array_values($upcoming), 0, 5) as $t):
                        ?>
                        <tr>
                            <td class="fw-semibold small"><?= htmlspecialchars($t['title'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-muted small"><?= formatDate($t['start'], true) ?></td>
                            <td class="text-muted small"><?= htmlspecialchars($t['location'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <a href="termine.php?action=edit&id=<?= urlencode($t['id']) ?>" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="termine.php?action=delete&id=<?= urlencode($t['id']) ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Termin wirklich löschen?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($upcoming)): ?>
                        <tr><td colspan="4" class="text-muted text-center py-3">Keine bevorstehenden Termine</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
