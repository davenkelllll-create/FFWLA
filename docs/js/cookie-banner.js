/**
 * FF Langensendelbach – DSGVO Cookie-Banner
 * Speichert Zustimmung in localStorage, kein Tracking ohne Einwilligung.
 */
(function () {
    const KEY = 'fw_cookie_consent';

    function getConsent() {
        try { return localStorage.getItem(KEY); } catch(e) { return null; }
    }
    function setConsent(val) {
        try { localStorage.setItem(KEY, val); } catch(e) {}
    }

    function removeBanner() {
        const el = document.getElementById('fw-cookie-banner');
        if (el) { el.classList.add('fw-cookie--hiding'); setTimeout(() => el.remove(), 300); }
    }

    function createBanner() {
        const banner = document.createElement('div');
        banner.id = 'fw-cookie-banner';
        banner.setAttribute('role', 'dialog');
        banner.setAttribute('aria-label', 'Cookie-Einstellungen');
        banner.innerHTML = `
<div class="fw-cookie__inner">
    <div class="fw-cookie__icon"><i class="bi bi-shield-check"></i></div>
    <div class="fw-cookie__text">
        <strong>Datenschutzhinweis</strong>
        <p>Diese Website lädt Ressourcen von externen CDN-Diensten (Bootstrap, jsDelivr) sowie eine Karte von OpenStreetMap. Dabei werden technisch bedingt IP-Adressen übertragen. Wir verwenden keine Werbe- oder Tracking-Cookies. Details in der <a href="datenschutz.html">Datenschutzerklärung</a>.</p>
    </div>
    <div class="fw-cookie__actions">
        <button id="fw-cookie-accept" class="fw-cookie__btn fw-cookie__btn--accept">
            <i class="bi bi-check-lg me-1"></i>Verstanden &amp; Akzeptieren
        </button>
        <button id="fw-cookie-decline" class="fw-cookie__btn fw-cookie__btn--decline">
            Ablehnen
        </button>
    </div>
</div>`;
        document.body.appendChild(banner);

        // Small delay so CSS transition plays on load
        requestAnimationFrame(() => { requestAnimationFrame(() => { banner.classList.add('fw-cookie--visible'); }); });

        document.getElementById('fw-cookie-accept').addEventListener('click', function () {
            setConsent('accepted');
            removeBanner();
        });
        document.getElementById('fw-cookie-decline').addEventListener('click', function () {
            setConsent('declined');
            removeBanner();
        });
    }

    // Show banner only if no decision yet
    if (!getConsent()) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', createBanner);
        } else {
            createBanner();
        }
    }
})();
