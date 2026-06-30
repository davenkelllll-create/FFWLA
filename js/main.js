/**
 * FF Langensendelbach – Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

    // ---- Navbar scroll shadow ----
    const navbar = document.querySelector('.fw-navbar');
    if (navbar) {
        window.addEventListener('scroll', function () {
            navbar.style.boxShadow = window.scrollY > 10
                ? '0 4px 16px rgba(0,0,0,.25)'
                : '0 2px 12px rgba(0,0,0,.12)';
        }, { passive: true });
    }

    // ---- Active nav link (exact match or path prefix) ----
    const currentPath = window.location.pathname;
    document.querySelectorAll('.fw-navbar .nav-link, .fw-navbar .dropdown-item').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            // Also activate parent dropdown toggle
            const parent = link.closest('.dropdown');
            if (parent) {
                parent.querySelector('.dropdown-toggle')?.classList.add('active');
            }
        }
    });

    // ---- Back-to-top button ----
    const btt = document.getElementById('backToTop');
    if (btt) {
        window.addEventListener('scroll', function () {
            btt.classList.toggle('show', window.scrollY > 400);
        }, { passive: true });
        btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    // ---- Flash messages auto-dismiss ----
    document.querySelectorAll('.alert-dismissible').forEach(function (el) {
        setTimeout(() => {
            const btn = el.querySelector('.btn-close');
            if (btn) btn.click();
        }, 5000);
    });

    // ---- Nachrichten filter (nachrichten.php) ----
    const filterBtns = document.querySelectorAll('[data-filter-type]');
    const newsCards   = document.querySelectorAll('[data-news-type]');

    if (filterBtns.length && newsCards.length) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const type = this.dataset.filterType;
                filterBtns.forEach(b => b.classList.remove('active', 'btn-danger'));
                filterBtns.forEach(b => b.classList.add('btn-outline-secondary'));
                this.classList.add('active', 'btn-danger');
                this.classList.remove('btn-outline-secondary');

                newsCards.forEach(card => {
                    const show = type === 'alle' || card.dataset.newsType === type;
                    // The [data-news-type] element IS the Bootstrap column wrapper,
                    // so toggle it directly (it has no plain `.col` ancestor).
                    card.classList.toggle('d-none', !show);
                });
            });
        });
    }

    // ---- Category filter (galerie.php, formulare.php) ----
    const catBtns  = document.querySelectorAll('[data-filter-cat]');
    const catItems = document.querySelectorAll('[data-cat]');

    if (catBtns.length && catItems.length) {
        catBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const cat = this.dataset.filterCat;
                catBtns.forEach(b => {
                    b.classList.remove('active', 'btn-danger');
                    b.classList.add('btn-outline-secondary');
                });
                this.classList.add('active', 'btn-danger');
                this.classList.remove('btn-outline-secondary');

                catItems.forEach(item => {
                    const show = cat === 'alle' || item.dataset.cat === cat;
                    item.classList.toggle('d-none', !show);
                });
            });
        });
    }

});
