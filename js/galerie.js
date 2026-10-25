/**
 * FF Langensendelbach – Bild-Lightbox für Beitragsbilder (GLightbox)
 */
document.addEventListener('DOMContentLoaded', function () {
    if (typeof GLightbox !== 'undefined') {
        GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: true,
            descPosition: 'bottom',
        });
    }
});
