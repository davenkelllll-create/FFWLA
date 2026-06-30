<?php
require_once 'includes/functions.php';
$pageTitle = 'Impressum';
$config = loadConfig();
include 'includes/header.php';
?>

<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1"><a href="/">Startseite</a> / Impressum</nav>
        <h1>Impressum</h1>
    </div>
</div>

<section class="fw-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php
                $fuehrung   = getFuehrung();
                $kommandant = null;
                foreach ($fuehrung['aktive_wehr'] as $p) {
                    if (stripos($p['funktion'] ?? '', 'Kommandant') !== false && stripos($p['funktion'], 'Stv') === false) {
                        $kommandant = $p; break;
                    }
                }
                $adresse = $config['site_address'] ?? 'Am Weiher, 91094 Langensendelbach';
                ?>
                <h2>Angaben gemäß § 5 TMG</h2>
                <p>
                    <?= h($config['site_name'] ?? '') ?><br>
                    <?= nl2br(h($adresse)) ?>
                </p>
                <h3>Kontakt</h3>
                <p>
                    <?php if (!empty($config['site_phone'])): ?>
                    Telefon: <?= h($config['site_phone']) ?><br>
                    <?php endif; ?>
                    E-Mail: <a href="mailto:<?= h($config['site_email'] ?? '') ?>"><?= h($config['site_email'] ?? '') ?></a>
                </p>
                <h3>Verantwortlich für den Inhalt (§ 55 Abs. 2 RStV)</h3>
                <p>
                    <?= $kommandant ? h($kommandant['funktion'] . ' ' . $kommandant['name']) : 'Der/die Kommandant/in' ?><br>
                    <?= nl2br(h($adresse)) ?>
                </p>
                <h3>Haftungsausschluss</h3>
                <p>Die Inhalte dieser Website wurden mit größtmöglicher Sorgfalt erstellt. Für die Richtigkeit,
                Vollständigkeit und Aktualität der Inhalte können wir jedoch keine Gewähr übernehmen.</p>
                <h3>Urheberrecht</h3>
                <p>Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem
                deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung
                außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors
                bzw. Erstellers.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
