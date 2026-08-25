<?php
/**
 * system-check.php – Prüft, ob der Server alle Voraussetzungen erfüllt.
 *
 * Aufruf nach dem Upload:  https://<domain>/system-check.php
 * Erfordert eine Anmeldung im Adminbereich (damit die Serverdetails nicht
 * öffentlich einsehbar sind).
 *
 * Diese Datei nach erfolgreicher Prüfung LÖSCHEN – sie wird im laufenden
 * Betrieb nicht gebraucht.
 */
require_once __DIR__ . '/includes/auth.php';

if (!isLoggedIn()) {
    http_response_code(403);
    echo '<!DOCTYPE html><html lang="de"><meta charset="UTF-8">'
       . '<body style="font-family:system-ui;padding:2rem;max-width:640px;margin:auto;">'
       . '<h1 style="font-size:1.2rem;">Anmeldung erforderlich</h1>'
       . '<p>Bitte zuerst im <a href="/admin/">Adminbereich</a> anmelden und diese Seite erneut aufrufen.</p>'
       . '</body></html>';
    exit;
}

$root = __DIR__;

/** Ein Prüfergebnis. $status: ok | warn | fail */
function pruefung(string $titel, string $status, string $detail, string $hinweis = ''): array {
    return compact('titel', 'status', 'detail', 'hinweis');
}

$checks = [];

// ---- PHP-Version ----
$phpOk = version_compare(PHP_VERSION, '8.0.0', '>=');
$checks[] = pruefung(
    'PHP-Version',
    $phpOk ? 'ok' : 'fail',
    PHP_VERSION,
    $phpOk ? '' : 'Mindestens PHP 8.0 nötig – bitte beim Hoster/Administrator umstellen lassen.'
);

// ---- Erweiterungen ----
foreach (['gd' => 'Bild-Vorschauen (Fahrzeuge, Galerie)', 'fileinfo' => 'Upload-Prüfung (MIME-Erkennung)'] as $ext => $wofuer) {
    $has = extension_loaded($ext);
    $checks[] = pruefung(
        'PHP-Erweiterung: ' . $ext,
        $has ? 'ok' : 'fail',
        $has ? 'vorhanden' : 'FEHLT',
        $has ? $wofuer : "Wird gebraucht für: $wofuer. Bitte aktivieren lassen."
    );
}

// ---- Webserver ----
$serverSoft = $_SERVER['SERVER_SOFTWARE'] ?? 'unbekannt';
$istApache  = stripos($serverSoft, 'apache') !== false;
$istNginx   = stripos($serverSoft, 'nginx')  !== false;
$checks[] = pruefung(
    'Webserver',
    $istApache ? 'ok' : 'warn',
    $serverSoft,
    $istApache
        ? 'Apache – .htaccess-Dateien werden ausgewertet.'
        : ($istNginx
            ? 'ACHTUNG nginx: .htaccess wird NICHT ausgewertet! Der Schutz von data/ muss in der nginx-Konfiguration eingerichtet werden – siehe Test unten.'
            : 'Servertyp unklar – bitte den Test unten unbedingt durchführen.')
);

// ---- .htaccess wirksam? (nur zuverlässig per Browser-Test) ----
$scheme  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host    = $_SERVER['HTTP_HOST'] ?? 'localhost';
$testUrl = $scheme . '://' . $host . '/data/secrets.json';

// ---- Schreibrechte ----
foreach ([
    'data'              => 'Inhalte speichern',
    'uploads'           => 'Datei-Uploads',
    'uploads/galerie'   => 'Fotos zu Nachrichten',
    'uploads/formulare' => 'PDF-Formulare',
    'uploads/fahrzeuge' => 'Fahrzeugfotos',
] as $dir => $wofuer) {
    $pfad = $root . '/' . $dir;
    if (!is_dir($pfad)) {
        $checks[] = pruefung("Ordner: $dir", 'warn', 'nicht vorhanden', "Wird beim ersten Upload angelegt. ($wofuer)");
        continue;
    }
    $ok = is_writable($pfad);
    $checks[] = pruefung(
        "Schreibrechte: $dir",
        $ok ? 'ok' : 'fail',
        $ok ? 'beschreibbar' : 'NICHT beschreibbar',
        $ok ? $wofuer : "Per FTP auf 755 (notfalls 775) setzen. Wird gebraucht für: $wofuer"
    );
}

// ---- Datendateien vorhanden? ----
$fehlend = [];
foreach (['config.json','secrets.json','nachrichten.json','termine.json','fuehrung.json','fahrzeuge.json','geschichte.json','inhalte.json','formulare.json','galerien.json'] as $f) {
    if (!file_exists($root . '/data/' . $f)) $fehlend[] = $f;
}
$checks[] = pruefung(
    'Datendateien in data/',
    empty($fehlend) ? 'ok' : 'fail',
    empty($fehlend) ? 'alle vorhanden' : 'fehlt: ' . implode(', ', $fehlend),
    empty($fehlend) ? '' : 'Diese Dateien nachträglich hochladen – ohne sie bleiben Seitenbereiche leer.'
);

// ---- Admin-Passwort gesetzt? ----
$secrets = loadSecrets();
$hatHash = !empty($secrets['admin_password_hash']);
$checks[] = pruefung(
    'Admin-Passwort',
    $hatHash ? 'ok' : 'fail',
    $hatHash ? 'gesetzt' : 'nicht gesetzt',
    $hatHash ? 'Nach dem Umzug unbedingt ändern (Einstellungen → Admin-Passwort).' : 'data/secrets.json fehlt oder ist leer.'
);

// ---- HTTPS ----
$httpsOk = $scheme === 'https';
$checks[] = pruefung(
    'HTTPS',
    $httpsOk ? 'ok' : 'fail',
    $httpsOk ? 'aktiv' : 'nicht aktiv',
    $httpsOk ? '' : 'SSL-Zertifikat aktivieren lassen – sonst wird das Admin-Passwort unverschlüsselt übertragen!'
);

// ---- Aufbaumodus ----
$cfg = loadConfig();
$checks[] = pruefung(
    'Aufbaumodus',
    !empty($cfg['wip_mode']) ? 'warn' : 'ok',
    !empty($cfg['wip_mode']) ? 'aktiv – Besucher sehen die Baustellen-Seite' : 'aus – Website ist öffentlich',
    !empty($cfg['wip_mode']) ? 'Zum Start unter Einstellungen → Aufbaumodus ausschalten.' : ''
);

$anzFail = count(array_filter($checks, fn($c) => $c['status'] === 'fail'));
$anzWarn = count(array_filter($checks, fn($c) => $c['status'] === 'warn'));
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>System-Check – FF Langensendelbach</title>
<style>
    body { font-family: system-ui, -apple-system, 'Segoe UI', Arial, sans-serif;
           max-width: 860px; margin: 0 auto; padding: 2rem 1rem; color: #222; line-height: 1.5; }
    h1 { font-size: 1.5rem; margin-bottom: .25rem; }
    .sub { color: #666; font-size: .9rem; margin-bottom: 1.5rem; }
    .summary { padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600; }
    .s-ok   { background: #e6f4ea; color: #14532d; }
    .s-fail { background: #fdeaea; color: #7f1d1d; }
    .s-warn { background: #fff7e6; color: #7c4a03; }
    table { width: 100%; border-collapse: collapse; }
    th, td { text-align: left; padding: .6rem .5rem; border-bottom: 1px solid #eee; vertical-align: top; }
    th { font-size: .78rem; text-transform: uppercase; letter-spacing: .05em; color: #666; }
    .badge { display: inline-block; padding: .15rem .55rem; border-radius: 20px;
             font-size: .75rem; font-weight: 700; white-space: nowrap; }
    .b-ok   { background: #16a34a; color: #fff; }
    .b-fail { background: #dc2626; color: #fff; }
    .b-warn { background: #f59e0b; color: #fff; }
    .hint { color: #666; font-size: .85rem; display: block; margin-top: .2rem; }
    .testbox { border: 2px solid #dc2626; border-radius: 8px; padding: 1.25rem; margin: 2rem 0; }
    .testbox h2 { font-size: 1.05rem; margin: 0 0 .5rem; color: #7f1d1d; }
    code { background: #f4f4f4; padding: .1rem .35rem; border-radius: 3px; font-size: .9em; }
    .footer { margin-top: 2.5rem; padding-top: 1rem; border-top: 1px solid #eee; font-size: .85rem; color: #666; }
</style>
</head>
<body>

<h1>System-Check</h1>
<p class="sub">Prüft, ob dieser Server alle Voraussetzungen für die Website erfüllt.</p>

<?php if ($anzFail > 0): ?>
<div class="summary s-fail"><?= $anzFail ?> Problem(e) gefunden – bitte unten die rot markierten Punkte beheben.</div>
<?php elseif ($anzWarn > 0): ?>
<div class="summary s-warn">Keine Fehler, aber <?= $anzWarn ?> Hinweis(e) – bitte kurz durchsehen.</div>
<?php else: ?>
<div class="summary s-ok">Alles in Ordnung – der Server erfüllt alle Voraussetzungen.</div>
<?php endif; ?>

<table>
    <thead><tr><th>Prüfung</th><th>Status</th><th>Ergebnis</th></tr></thead>
    <tbody>
    <?php foreach ($checks as $c): ?>
    <tr>
        <td><strong><?= h($c['titel']) ?></strong></td>
        <td><span class="badge b-<?= h($c['status']) ?>"><?= strtoupper(h($c['status'])) ?></span></td>
        <td>
            <?= h($c['detail']) ?>
            <?php if ($c['hinweis']): ?><span class="hint"><?= h($c['hinweis']) ?></span><?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="testbox">
    <h2>Wichtigster Test: Ist <code>data/</code> vor Zugriff geschützt?</h2>
    <p>
        Dieser Test lässt sich nur im Browser durchführen. Öffnen Sie diesen Link
        <strong>in einem privaten Fenster</strong> (also abgemeldet):
    </p>
    <p><a href="<?= h($testUrl) ?>" target="_blank" rel="noopener"><?= h($testUrl) ?></a></p>
    <p>
        <strong>Richtig:</strong> Fehlermeldung „403 Forbidden" oder „404 Not Found".<br>
        <strong>Gefährlich:</strong> Sie sehen JSON-Text mit <code>admin_password_hash</code>.
        Dann werten der Webserver die <code>.htaccess</code>-Dateien nicht aus – der Administrator
        muss den Ordner <code>data/</code> serverseitig sperren, <em>bevor</em> die Seite öffentlich geht.
    </p>
</div>

<div class="footer">
    <strong>Diese Datei nach der Prüfung löschen:</strong> <code>system-check.php</code> wird im
    laufenden Betrieb nicht gebraucht.<br>
    Server: <?= h($serverSoft) ?> · PHP <?= h(PHP_VERSION) ?> · <a href="/admin/">Zum Adminbereich</a>
</div>

</body>
</html>
