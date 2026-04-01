<?php
require_once 'includes/functions.php';
$pageTitle = 'Formulare';
$pageDescription = 'Anmeldeformulare und Anträge der Freiwilligen Feuerwehr Langensendelbach zum Download.';

$formulare = getFormulare();
$kategorien = array_unique(array_column($formulare, 'kategorie'));

include 'includes/header.php';
?>

<!-- Page Header -->
<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="/">Startseite</a> / Formulare
        </nav>
        <h1><i class="bi bi-file-earmark-arrow-down me-2 text-fw-red"></i>Formulare &amp; Anträge</h1>
        <p class="text-muted mb-0">Formulare herunterladen, ausdrucken, ausfüllen, unterschreiben und per Post oder E-Mail einsenden</p>
    </div>
</div>

<section class="fw-section">
    <div class="container">

        <!-- Info box -->
        <div class="d-flex gap-3 p-3 mb-4 rounded" style="background:var(--fw-red-pale);border-left:4px solid var(--fw-red);">
            <i class="bi bi-info-circle-fill text-danger fs-5 flex-shrink-0 mt-1"></i>
            <div>
                <strong>Hinweis:</strong> Alle Formulare müssen ausgedruckt, handschriftlich ausgefüllt und
                <strong>unterschrieben</strong> werden. Das ausgefüllte Formular bitte per Post oder E-Mail
                an uns einsenden. Bei Fragen stehen wir gern zur Verfügung.
            </div>
        </div>

        <!-- Category filter -->
        <?php if (!empty($kategorien)): ?>
        <div class="d-flex flex-wrap gap-2 mb-4">
            <button class="btn btn-danger btn-sm active" data-filter-cat="alle">Alle</button>
            <?php foreach ($kategorien as $kat): ?>
            <button class="btn btn-outline-secondary btn-sm" data-filter-cat="<?= h($kat) ?>">
                <?= h(categoryLabel($kat)) ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($formulare)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-file-earmark-x fs-1 d-block mb-3 opacity-25"></i>
            <p>Noch keine Formulare vorhanden.</p>
        </div>
        <?php else: ?>
        <div class="d-flex flex-column gap-3">
            <?php foreach ($formulare as $formular):
                $pdfPath = '/uploads/formulare/' . $formular['datei'];
                $fileExists = file_exists(__DIR__ . '/uploads/formulare/' . $formular['datei']);
            ?>
            <div class="fw-formular-row" data-cat="<?= h($formular['kategorie']) ?>">
                <div class="fw-formular-icon">
                    <i class="bi bi-file-earmark-pdf"></i>
                </div>
                <div class="fw-formular-info">
                    <div class="fw-formular-title"><?= h($formular['titel']) ?></div>
                    <div class="fw-formular-desc"><?= h($formular['beschreibung']) ?></div>
                    <div class="mt-1">
                        <span class="fw-badge badge-secondary"><?= h(categoryLabel($formular['kategorie'])) ?></span>
                        <?php if (!empty($formular['version'])): ?>
                        <span class="text-muted small ms-2">Stand: <?= h($formular['version']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <?php if ($fileExists): ?>
                    <a href="<?= h($pdfPath) ?>" download class="btn btn-danger btn-sm">
                        <i class="bi bi-download me-1"></i>Herunterladen
                    </a>
                    <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled title="Datei noch nicht verfügbar">
                        <i class="bi bi-download me-1"></i>Bald verfügbar
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Contact box -->
        <div class="mt-5 p-4 rounded" style="background:var(--fw-gray-100);">
            <h5 class="mb-2"><i class="bi bi-envelope me-2 text-danger"></i>Formular einsenden</h5>
            <p class="mb-2 text-muted">
                Ausgefüllte und unterschriebene Formulare senden Sie bitte an:
            </p>
            <?php $config = loadConfig(); ?>
            <ul class="list-unstyled mb-0">
                <?php if (!empty($config['site_address'])): ?>
                <li class="mb-1"><i class="bi bi-geo-alt me-2 text-danger"></i><?= h($config['site_address']) ?></li>
                <?php endif; ?>
                <?php if (!empty($config['site_email'])): ?>
                <li><i class="bi bi-envelope me-2 text-danger"></i>
                    <a href="mailto:<?= h($config['site_email']) ?>"><?= h($config['site_email']) ?></a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
