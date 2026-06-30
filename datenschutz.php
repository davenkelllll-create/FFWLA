<?php
require_once 'includes/functions.php';
$pageTitle = 'Datenschutzerklärung';
$config = loadConfig();
include 'includes/header.php';
?>

<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1"><a href="/">Startseite</a> / Datenschutz</nav>
        <h1>Datenschutzerklärung</h1>
    </div>
</div>

<section class="fw-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2>1. Datenschutz auf einen Blick</h2>
                <h3>Allgemeine Hinweise</h3>
                <p>Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren personenbezogenen
                Daten passiert, wenn Sie diese Website besuchen. Personenbezogene Daten sind alle Daten, mit denen
                Sie persönlich identifiziert werden können.</p>

                <h2>2. Allgemeine Hinweise und Pflichtinformationen</h2>
                <h3>Datenschutz</h3>
                <p>Die Betreiber dieser Seiten nehmen den Schutz Ihrer persönlichen Daten sehr ernst. Wir behandeln
                Ihre personenbezogenen Daten vertraulich und entsprechend der gesetzlichen Datenschutzvorschriften
                sowie dieser Datenschutzerklärung.</p>

                <h3>Verantwortliche Stelle</h3>
                <p>
                    <?= h($config['site_name'] ?? '') ?><br>
                    <?= h($config['site_address'] ?? 'Zum Berg 7, 91094 Langensendelbach') ?><br>
                    E-Mail: <a href="mailto:<?= h($config['site_email'] ?? '') ?>"><?= h($config['site_email'] ?? '') ?></a>
                </p>

                <h2>3. Datenerfassung auf dieser Website</h2>
                <h3>Server-Log-Dateien</h3>
                <p>Der Provider der Seiten erhebt und speichert automatisch Informationen in so genannten
                Server-Log-Dateien, die Ihr Browser automatisch an uns übermittelt. Dies sind:</p>
                <ul>
                    <li>Browsertyp und Browserversion</li>
                    <li>verwendetes Betriebssystem</li>
                    <li>Referrer URL</li>
                    <li>Hostname des zugreifenden Rechners</li>
                    <li>Uhrzeit der Serveranfrage</li>
                    <li>IP-Adresse</li>
                </ul>
                <p>Eine Zusammenführung dieser Daten mit anderen Datenquellen wird nicht vorgenommen. Die Erfassung
                dieser Daten erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO.</p>

                <h3>Externe Ressourcen (CDN)</h3>
                <p>Diese Website lädt Ressourcen (CSS, JavaScript) von jsDelivr (CDN). Dabei wird Ihre IP-Adresse
                an den CDN-Anbieter übertragen. Dies erfolgt auf Grundlage unseres berechtigten Interesses an einer
                technisch fehlerfreien Darstellung der Website (Art. 6 Abs. 1 lit. f DSGVO).</p>

                <h3>Karteneinbindung (OpenStreetMap)</h3>
                <p>Auf der Kontaktseite wird eine Karte von OpenStreetMap eingebunden. Bei Nutzung der Karte werden
                Daten an OpenStreetMap übermittelt. Weitere Informationen finden Sie unter
                <a href="https://www.openstreetmap.org/privacy" target="_blank" rel="noopener">openstreetmap.org/privacy</a>.</p>

                <h2>4. Ihre Rechte</h2>
                <p>Sie haben jederzeit das Recht auf unentgeltliche Auskunft über Ihre gespeicherten personenbezogenen
                Daten, deren Herkunft und Empfänger und den Zweck der Datenverarbeitung sowie ein Recht auf
                Berichtigung oder Löschung dieser Daten. Hierzu sowie zu weiteren Fragen zum Thema Datenschutz
                können Sie sich jederzeit an uns wenden.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
