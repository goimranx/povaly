{{-- Quick Shop Offcanvas --}}
<div class="offcanvas offcanvas-end qs-offcanvas" id="quick-shop-modal" tabindex="-1" data-bs-scroll="false" data-bs-backdrop="true">
    {{-- Close button floats outside the panel on desktop, inside on mobile --}}
    <button type="button" class="qs-close-btn" data-bs-dismiss="offcanvas" aria-label="{{ __('Close') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <div class="offcanvas-body qs-body">
        <div class="quick-shop-content">
            {{-- Filled by JS --}}
        </div>
    </div>
</div>
