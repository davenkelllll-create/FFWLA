<?php
require_once 'includes/functions.php';
$config    = loadConfig();
$siteName  = $config['site_name']  ?? 'Freiwillige Feuerwehr Langensendelbach';
$siteShort = $config['site_short'] ?? 'FF Langensendelbach';
$siteEmail = $config['site_email'] ?? '';
$sitePhone = $config['site_phone'] ?? '';
$siteAddr  = $config['site_address'] ?? '';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Die neue Website der <?= h($siteName) ?> wird gerade aufgebaut. Bald sind wir online!">
    <meta name="theme-color" content="#CC0000">
    <link rel="icon" href="/images/branding/favicon-192.png" sizes="192x192" type="image/png">
    <link rel="icon" href="/images/branding/favicon-32.png" sizes="32x32" type="image/png">
    <title><?= h($siteName) ?> – Bald online!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { height: 100%; }
        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a1a1a 0%, #3a0000 40%, #CC0000 100%);
            color: #fff;
            text-align: center;
            padding: 2rem;
        }
        .wip-container {
            max-width: 560px;
            width: 100%;
        }
        .wip-logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 4px 30px rgba(0,0,0,.3);
        }
        .wip-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: .5rem;
        }
        .wip-subtitle {
            font-size: 1rem;
            opacity: .85;
            margin-bottom: 2.5rem;
            line-height: 1.5;
        }
        .wip-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(255,255,255,.15);
            border-radius: 20px;
            padding: .35rem 1rem;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(4px);
        }
        .wip-contact {
            background: rgba(255,255,255,.1);
            border-radius: 12px;
            padding: 1.5rem;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.15);
        }
        .wip-contact h2 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            opacity: .9;
        }
        .wip-contact-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .5rem 0;
            font-size: .95rem;
        }
        .wip-contact-item + .wip-contact-item {
            border-top: 1px solid rgba(255,255,255,.1);
        }
        .wip-contact-item i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }
        .wip-contact-item a {
            color: #fff;
            text-decoration: none;
        }
        .wip-contact-item a:hover {
            text-decoration: underline;
        }
        .wip-notruf {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            margin-top: 2rem;
            background: #fff;
            color: #CC0000;
            font-weight: 700;
            font-size: .9rem;
            padding: .5rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            transition: transform 200ms ease;
        }
        .wip-notruf:hover { transform: scale(1.05); }
        .wip-footer {
            margin-top: 3rem;
            font-size: .75rem;
            opacity: .5;
        }
    </style>
</head>
<body>
    <div class="wip-container">
        <div class="wip-logo">
            <img src="/images/branding/wappen-schild.png" alt="Wappen <?= h($siteShort) ?>">
        </div>

        <div class="wip-badge">
            <i class="bi bi-gear-wide-connected"></i> Website im Aufbau
        </div>

        <h1><?= h($siteName) ?></h1>
        <p class="wip-subtitle">
            Unsere neue Website wird gerade aufgebaut.<br>
            Bald finden Sie hier alle Informationen rund um unsere Feuerwehr.
        </p>

        <div class="wip-contact">
            <h2><i class="bi bi-info-circle me-1"></i> Kontakt &amp; Erreichbarkeit</h2>

            <?php if ($siteAddr): ?>
            <div class="wip-contact-item">
                <i class="bi bi-geo-alt-fill"></i>
                <span><?= h($siteAddr) ?></span>
            </div>
            <?php endif; ?>

            <?php if ($siteEmail): ?>
            <div class="wip-contact-item">
                <i class="bi bi-envelope-fill"></i>
                <a href="mailto:<?= h($siteEmail) ?>"><?= h($siteEmail) ?></a>
            </div>
            <?php endif; ?>

            <?php if ($sitePhone): ?>
            <div class="wip-contact-item">
                <i class="bi bi-telephone-fill"></i>
                <a href="tel:<?= h(preg_replace('/\s+/', '', $sitePhone)) ?>"><?= h($sitePhone) ?></a>
            </div>
            <?php endif; ?>
        </div>

        <a href="tel:112" class="wip-notruf">
            <i class="bi bi-telephone-fill"></i> Notruf 112
        </a>

        <div class="wip-footer">
            &copy; <?= date('Y') ?> <?= h($siteName) ?>
        </div>
    </div>
</body>
</html>
