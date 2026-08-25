<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/functions.php';

$config  = loadConfig();
$fuehrung = getFuehrung();
$message = '';
$error   = '';

// ---- SAVE CONFIG ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_config'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Ungültiges Sicherheitstoken.';
    } else {
        $config['site_name']    = trim($_POST['site_name'] ?? $config['site_name']);
        $config['site_email']   = trim($_POST['site_email'] ?? '');
        $config['site_phone']   = trim($_POST['site_phone'] ?? '');
        $config['site_address'] = trim($_POST['site_address'] ?? '');
        $config['wip_mode']     = !empty($_POST['wip_mode']);
        $config['map_lat']      = trim($_POST['map_lat'] ?? '');
        $config['map_lon']      = trim($_POST['map_lon'] ?? '');
        $config['social_facebook']  = trim($_POST['social_facebook']  ?? '');
        $config['social_instagram'] = trim($_POST['social_instagram'] ?? '');
        $config['social_youtube']   = trim($_POST['social_youtube']   ?? '');

        // Password change only if both fields filled and match
        $pw1 = $_POST['new_password']     ?? '';
        $pw2 = $_POST['new_password_confirm'] ?? '';
        $newHash = null;
        if ($pw1 !== '') {
            if ($pw1 !== $pw2) {
                $error = 'Passwörter stimmen nicht überein.';
            } elseif (strlen($pw1) < 8) {
                $error = 'Passwort muss mindestens 8 Zeichen haben.';
            } else {
                $newHash = password_hash($pw1, PASSWORD_BCRYPT);
            }
        }

        if (!$error) {
            // Credentials never belong in config.json (could leak via a static export).
            unset($config['admin_password_hash'], $config['csrf_secret']);
            saveJson('config.json', $config);
            if ($newHash !== null) {
                $secrets = loadSecrets();
                $secrets['admin_password_hash'] = $newHash;
                saveSecrets($secrets);
            }
            $message = 'Einstellungen gespeichert.';
        }
    }
}

// ---- SAVE FÜHRUNG ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_fuehrung'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Ungültiges Sicherheitstoken.';
    } else {
        // Rebuild arrays from form
        $aktive = [];
        $funk_a  = $_POST['aw_funktion'] ?? [];
        $name_a  = $_POST['aw_name']     ?? [];
        $tel_a   = $_POST['aw_telefon']  ?? [];
        $mail_a  = $_POST['aw_email']    ?? [];
        foreach ($funk_a as $i => $funk) {
            if (trim($name_a[$i] ?? '') === '' && trim($funk) === '') continue;
            $aktive[] = [
                'funktion' => trim($funk),
                'name'     => trim($name_a[$i] ?? ''),
                'telefon'  => trim($tel_a[$i]  ?? ''),
                'email'    => trim($mail_a[$i] ?? ''),
            ];
        }

        $jfw = [];
        $funk_j  = $_POST['jfw_funktion'] ?? [];
        $name_j  = $_POST['jfw_name']     ?? [];
        $tel_j   = $_POST['jfw_telefon']  ?? [];
        $mail_j  = $_POST['jfw_email']    ?? [];
        foreach ($funk_j as $i => $funk) {
            if (trim($name_j[$i] ?? '') === '' && trim($funk) === '') continue;
            $jfw[] = [
                'funktion' => trim($funk),
                'name'     => trim($name_j[$i] ?? ''),
                'telefon'  => trim($tel_j[$i]  ?? ''),
                'email'    => trim($mail_j[$i] ?? ''),
            ];
        }

        $kfw = [];
        $funk_k  = $_POST['kfw_funktion'] ?? [];
        $name_k  = $_POST['kfw_name']     ?? [];
        $tel_k   = $_POST['kfw_telefon']  ?? [];
        $mail_k  = $_POST['kfw_email']    ?? [];
        foreach ($funk_k as $i => $funk) {
            if (trim($name_k[$i] ?? '') === '' && trim($funk) === '') continue;
            $kfw[] = [
                'funktion' => trim($funk),
                'name'     => trim($name_k[$i] ?? ''),
                'telefon'  => trim($tel_k[$i]  ?? ''),
                'email'    => trim($mail_k[$i] ?? ''),
            ];
        }

        saveFuehrung(['aktive_wehr' => $aktive, 'jugendfeuerwehr' => $jfw, 'kinderfeuerwehr' => $kfw]);
        $fuehrung = getFuehrung();
        $message  = 'Ansprechpartner gespeichert.';
    }
}

// ---- SAVE NOTFALL-HINWEIS (Banner auf allen Seiten) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_alert'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Ungültiges Sicherheitstoken.';
    } else {
        saveJson('alert.json', [
            'active'  => !empty($_POST['alert_active']),
            'message' => trim($_POST['alert_message'] ?? ''),
        ]);
        $message = 'Notfall-Hinweis gespeichert.';
    }
}

$alert = loadJson('alert.json');
$csrf  = generateCsrfToken();

$pageTitle = 'Einstellungen';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($pageTitle) ?> – Admin FF Langensendelbach</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="admin-body">

<div class="admin-layout">
    <?php include 'partials/sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-topbar">
            <h1 class="admin-topbar-title">
                <i class="bi bi-gear-fill me-2"></i>Einstellungen
            </h1>
        </div>
        <div class="container-fluid p-4">

            <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i><?= h($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i><?= h($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- ======================== ALLGEMEIN ======================== -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <i class="bi bi-info-circle me-2"></i>Allgemeine Einstellungen
                </div>
                <div class="p-4">
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
                        <input type="hidden" name="save_config" value="1">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Name der Wehr</label>
                                <input type="text" class="form-control" name="site_name"
                                       value="<?= h($config['site_name'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">E-Mail-Adresse</label>
                                <input type="email" class="form-control" name="site_email"
                                       value="<?= h($config['site_email'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Telefon</label>
                                <input type="text" class="form-control" name="site_phone"
                                       placeholder="z.B. +49 9133 1234"
                                       value="<?= h($config['site_phone'] ?? '') ?>">
                                <div class="form-text">Leer lassen, wenn keine öffentliche Nummer angezeigt werden soll.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Adresse Gerätehaus</label>
                                <input type="text" class="form-control" name="site_address"
                                       placeholder="Zum Berg 7, 91094 Langensendelbach"
                                       value="<?= h($config['site_address'] ?? '') ?>">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-1"><i class="bi bi-cone-striped me-2"></i>Aufbaumodus</h6>
                        <p class="text-muted small mb-2">
                            Solange der Aufbaumodus aktiv ist, werden alle Besucher auf die
                            Baustellen-Seite mit den Kontaktdaten geleitet. Sie selbst sehen die
                            Website weiterhin normal, solange Sie hier angemeldet sind – so können
                            Sie in Ruhe Inhalte pflegen und alles vorab prüfen.
                        </p>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="wip_mode" value="1" id="wipMode"
                                   <?= !empty($config['wip_mode']) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold" for="wipMode">
                                Website im Aufbau – Besucher sehen die Baustellen-Seite
                            </label>
                        </div>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Zum Start der Seite diesen Schalter ausschalten. Danach ist die
                            Website öffentlich erreichbar.
                        </p>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-1"><i class="bi bi-geo-alt me-2"></i>Karte (Standort Gerätehaus)</h6>
                        <p class="text-muted small mb-3">
                            Exakten Punkt finden: auf
                            <a href="https://www.openstreetmap.org" target="_blank" rel="noopener">openstreetmap.org</a>
                            den Standort suchen, mit der <strong>rechten Maustaste</strong> aufs Gerätehaus klicken →
                            „Adresse anzeigen" / „Hier zentrieren". Die beiden Zahlen (Breite, Länge) hier eintragen.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label" for="map_lat">Breitengrad (lat)</label>
                                <input type="text" class="form-control" id="map_lat" name="map_lat"
                                       placeholder="49.6470" value="<?= h($config['map_lat'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="map_lon">Längengrad (lon)</label>
                                <input type="text" class="form-control" id="map_lon" name="map_lon"
                                       placeholder="11.0690" value="<?= h($config['map_lon'] ?? '') ?>">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-share me-2"></i>Social Media</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-facebook text-primary me-1"></i>Facebook-URL
                                </label>
                                <input type="url" class="form-control" name="social_facebook"
                                       placeholder="https://www.facebook.com/..."
                                       value="<?= h($config['social_facebook'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-instagram text-danger me-1"></i>Instagram-URL
                                </label>
                                <input type="url" class="form-control" name="social_instagram"
                                       placeholder="https://www.instagram.com/..."
                                       value="<?= h($config['social_instagram'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-youtube text-danger me-1"></i>YouTube-URL
                                </label>
                                <input type="url" class="form-control" name="social_youtube"
                                       placeholder="https://www.youtube.com/@..."
                                       value="<?= h($config['social_youtube'] ?? '') ?>">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-lock me-2"></i>Admin-Passwort ändern</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Neues Passwort</label>
                                <input type="password" class="form-control" name="new_password"
                                       placeholder="Leer lassen = unverändert">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Passwort bestätigen</label>
                                <input type="password" class="form-control" name="new_password_confirm">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-save me-1"></i>Einstellungen speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ======================== NOTFALL-HINWEIS ======================== -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <i class="bi bi-exclamation-triangle me-2"></i>Notfall-Hinweis (Banner)
                </div>
                <div class="p-4">
                    <p class="text-muted small">Ein aktivierter Hinweis erscheint als rotes Band ganz oben auf jeder Seite – z.&nbsp;B. bei Unwetterwarnungen oder kurzfristigen Absagen.</p>
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
                        <input type="hidden" name="save_alert" value="1">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="alert_active" name="alert_active" value="1" <?= !empty($alert['active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="alert_active">Hinweis aktiv anzeigen</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="alert_message">Hinweistext</label>
                            <input type="text" class="form-control" id="alert_message" name="alert_message"
                                   maxlength="200" placeholder="z.B. Achtung: Tag der offenen Tür wegen Unwetter verschoben."
                                   value="<?= h($alert['message'] ?? '') ?>">
                        </div>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-save me-1"></i>Hinweis speichern
                        </button>
                    </form>
                </div>
            </div>

            <!-- ======================== FÜHRUNG ======================== -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <i class="bi bi-person-badge me-2"></i>Ansprechpartner &amp; Führung
                </div>
                <div class="p-4">
                    <form method="post" id="fuehrungForm">
                        <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
                        <input type="hidden" name="save_fuehrung" value="1">

                        <!-- Vorstandschaft & Wehrführung -->
                        <h6 class="fw-bold mb-3">Vorstandschaft &amp; Wehrführung</h6>
                        <div class="table-responsive mb-2">
                            <table class="table table-sm align-middle" id="aw-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Funktion</th>
                                        <th>Name</th>
                                        <th>Telefon</th>
                                        <th>E-Mail</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="aw-tbody">
                                    <?php foreach ($fuehrung['aktive_wehr'] as $p): ?>
                                    <tr class="aw-row">
                                        <td><input type="text" class="form-control form-control-sm" name="aw_funktion[]" value="<?= h($p['funktion']) ?>" placeholder="Kommandant"></td>
                                        <td><input type="text" class="form-control form-control-sm" name="aw_name[]"     value="<?= h($p['name'])     ?>" placeholder="Vorname Nachname"></td>
                                        <td><input type="text" class="form-control form-control-sm" name="aw_telefon[]"  value="<?= h($p['telefon'])  ?>" placeholder="+49 ..."></td>
                                        <td><input type="email" class="form-control form-control-sm" name="aw_email[]"   value="<?= h($p['email'])    ?>" placeholder="name@example.de"></td>
                                        <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mb-4" id="add-aw">
                            <i class="bi bi-plus-circle me-1"></i>Zeile hinzufügen
                        </button>

                        <!-- Jugendfeuerwehr -->
                        <h6 class="fw-bold mb-3">Jugendfeuerwehr</h6>
                        <div class="table-responsive mb-2">
                            <table class="table table-sm align-middle" id="jfw-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Funktion</th>
                                        <th>Name</th>
                                        <th>Telefon</th>
                                        <th>E-Mail</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="jfw-tbody">
                                    <?php foreach ($fuehrung['jugendfeuerwehr'] as $p): ?>
                                    <tr class="jfw-row">
                                        <td><input type="text" class="form-control form-control-sm" name="jfw_funktion[]" value="<?= h($p['funktion']) ?>" placeholder="Jugendwart"></td>
                                        <td><input type="text" class="form-control form-control-sm" name="jfw_name[]"     value="<?= h($p['name'])     ?>" placeholder="Vorname Nachname"></td>
                                        <td><input type="text" class="form-control form-control-sm" name="jfw_telefon[]"  value="<?= h($p['telefon'])  ?>" placeholder="+49 ..."></td>
                                        <td><input type="email" class="form-control form-control-sm" name="jfw_email[]"   value="<?= h($p['email'])    ?>" placeholder="name@example.de"></td>
                                        <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mb-4" id="add-jfw">
                            <i class="bi bi-plus-circle me-1"></i>Zeile hinzufügen
                        </button>

                        <!-- Kinderfeuerwehr -->
                        <h6 class="fw-bold mb-3">Kinderfeuerwehr</h6>
                        <div class="table-responsive mb-2">
                            <table class="table table-sm align-middle" id="kfw-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Funktion</th>
                                        <th>Name</th>
                                        <th>Telefon</th>
                                        <th>E-Mail</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="kfw-tbody">
                                    <?php foreach ($fuehrung['kinderfeuerwehr'] as $p): ?>
                                    <tr class="kfw-row">
                                        <td><input type="text" class="form-control form-control-sm" name="kfw_funktion[]" value="<?= h($p['funktion']) ?>" placeholder="Leiterin Kinderfeuerwehr"></td>
                                        <td><input type="text" class="form-control form-control-sm" name="kfw_name[]"     value="<?= h($p['name'])     ?>" placeholder="Vorname Nachname"></td>
                                        <td><input type="text" class="form-control form-control-sm" name="kfw_telefon[]"  value="<?= h($p['telefon'])  ?>" placeholder="+49 ..."></td>
                                        <td><input type="email" class="form-control form-control-sm" name="kfw_email[]"   value="<?= h($p['email'])    ?>" placeholder="name@example.de"></td>
                                        <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mb-4" id="add-kfw">
                            <i class="bi bi-plus-circle me-1"></i>Zeile hinzufügen
                        </button>

                        <div>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-save me-1"></i>Ansprechpartner speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div><!-- /container -->
    </main>
</div><!-- /layout -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function makeRow(prefix) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text"  class="form-control form-control-sm" name="${prefix}_funktion[]" placeholder="Funktion"></td>
        <td><input type="text"  class="form-control form-control-sm" name="${prefix}_name[]"     placeholder="Vorname Nachname"></td>
        <td><input type="text"  class="form-control form-control-sm" name="${prefix}_telefon[]"  placeholder="+49 ..."></td>
        <td><input type="email" class="form-control form-control-sm" name="${prefix}_email[]"    placeholder="name@example.de"></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>`;
    return tr;
}
document.getElementById('add-aw').addEventListener('click', () =>
    document.getElementById('aw-tbody').appendChild(makeRow('aw')));
document.getElementById('add-jfw').addEventListener('click', () =>
    document.getElementById('jfw-tbody').appendChild(makeRow('jfw')));
document.getElementById('add-kfw').addEventListener('click', () =>
    document.getElementById('kfw-tbody').appendChild(makeRow('kfw')));
document.getElementById('fuehrungForm').addEventListener('click', e => {
    if (e.target.closest('.remove-row')) e.target.closest('tr').remove();
});
</script>
</body>
</html>
