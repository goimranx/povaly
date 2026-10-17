/**
 * product-card-enhanced.js
 * Loaded via config.php after main.js — handles ec-card button interactions.
 */
(function ($) {
    'use strict';

    // ── Floating capsule toggle (touch devices) ──────────────────────────
    $(document).on('click', '.ec-card__float-toggle', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).closest('.ec-card__float-capsule').toggleClass('active');
    });

    // Close capsule when clicking outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.ec-card__float').length) {
            $('.ec-card__float-capsule').removeClass('active');
        }
    });

    // Prevent card overlay link firing when clicking interactive elements
    $(document).on('click', '.ec-card__float-btn, .ec-card__actions, .ec-card__btn, .ec-card__rating, .ec-card__title, .ec-card__seller', function (e) {
        e.stopPropagation();
    });

    // ── Cart update after add-to-cart ─────────────────────────────────────
    // front-ecommerce.js handles the AJAX and fires ecommerce.cart.added
    // but does NOT call loadCart() to refresh the mini-cart HTML.
    // main.js exposes window.loadCart — call it here.
    document.addEventListener('ecommerce.cart.added', function (e) {
        if (typeof window.loadCart === 'function') {
            window.loadCart();
        }
    });

})(jQuery);
