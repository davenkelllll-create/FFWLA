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
                <h2>Angaben gemäß § 5 TMG</h2>
                <p>
                    <?= h($config['site_name']) ?><br>
                    Musterstraße 1<br>
                    91094 Langensendelbach
                </p>
                <h3>Kontakt</h3>
                <p>
                    Telefon: <?= h($config['site_phone'] ?? '') ?><br>
                    E-Mail: <a href="mailto:<?= h($config['site_email'] ?? '') ?>"><?= h($config['site_email'] ?? '') ?></a>
                </p>
                <h3>Verantwortlich für den Inhalt (§ 55 Abs. 2 RStV)</h3>
                <p>
                    Kommandant Stefan Müller<br>
                    Musterstraße 1<br>
                    91094 Langensendelbach
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
