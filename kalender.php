<?php
require_once 'includes/functions.php';
$pageTitle = 'Veranstaltungskalender';
$pageDescription = 'Übungen, Veranstaltungen und Termine der Freiwilligen Feuerwehr Langensendelbach.';

$termine = loadJson('termine.json');
// Only public events for the calendar
$publicTermine = array_values(array_filter($termine, fn($t) => !empty($t['public'])));

$extraCss = ['https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.css'];
$extraJs  = [
    'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js',
    '/js/kalender.js',
];

include 'includes/header.php';
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="/">Startseite</a> / Veranstaltungskalender
        </nav>
        <h1><i class="bi bi-calendar3 me-2 text-fw-red"></i>Veranstaltungskalender</h1>
        <p class="text-muted mb-0">Alle öffentlichen Termine der FF Langensendelbach</p>
    </div>
</div>

<section class="fw-section">
    <div class="container">

        <!-- Legend -->
        <div class="d-flex flex-wrap gap-3 mb-4">
            <span class="fw-badge" style="background:#ffe8e8;color:#990000;"><span style="background:#CC0000;width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:5px;"></span>Übung</span>
            <span class="fw-badge" style="background:#e8f5ee;color:#1a7a3c;"><span style="background:#1a7a3c;width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:5px;"></span>Veranstaltung</span>
            <span class="fw-badge" style="background:#fff3e0;color:#e07800;"><span style="background:#FF8C00;width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:5px;"></span>Jugendfeuerwehr</span>
        </div>

        <!-- Calendar -->
        <div class="admin-card p-3">
            <div id="fw-calendar"></div>
        </div>

        <!-- Upcoming list -->
        <div class="mt-5">
            <h2 class="fw-section-title mb-4">Nächste Termine</h2>
            <?php
            $upcoming = array_values(array_filter($publicTermine, fn($t) => strtotime($t['start']) >= time()));
            usort($upcoming, fn($a, $b) => strcmp($a['start'], $b['start']));
            $months = ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'];
            if (empty($upcoming)): ?>
            <p class="text-muted">Keine bevorstehenden Termine.</p>
            <?php else: ?>
            <div class="d-flex flex-column gap-3" style="max-width:680px;">
                <?php foreach ($upcoming as $termin):
                    $ts = strtotime($termin['start']); ?>
                <div class="fw-termin">
                    <div class="fw-termin__date" style="background:<?= h($termin['color'] ?? '#CC0000') ?>">
                        <div class="fw-termin__day"><?= date('d', $ts) ?></div>
                        <div class="fw-termin__month"><?= $months[date('n', $ts) - 1] ?></div>
                    </div>
                    <div class="fw-termin__info">
                        <div class="fw-termin__title"><?= h($termin['title']) ?></div>
                        <div class="fw-termin__meta">
                            <span><i class="bi bi-clock me-1"></i><?= date('H:i', $ts) ?> Uhr</span>
                            <?php if (!empty($termin['location'])): ?>
                            <span><i class="bi bi-geo-alt me-1"></i><?= h($termin['location']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($termin['description'])): ?>
                        <p class="mb-0 mt-1 small text-muted"><?= h($termin['description']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Event Detail Modal -->
<div class="modal fade" id="terminModal" tabindex="-1" aria-labelledby="terminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--fw-red);color:#fff;">
                <h5 class="modal-title" id="terminModalLabel">Termin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Schließen"></button>
            </div>
            <div class="modal-body" id="terminModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>

<script>
const FW_EVENTS = <?= json_encode($publicTermine, JSON_UNESCAPED_UNICODE) ?>;
</script>

<?php include 'includes/footer.php'; ?>
