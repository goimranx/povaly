{{-- Quick Shop Offcanvas (replaces modal) --}}
<div class="offcanvas offcanvas-end" id="quick-shop-modal" tabindex="-1" aria-labelledby="quick-shop-title" data-bs-scroll="false" data-bs-backdrop="true">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="quick-shop-title">{{ __('Select Options') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ __('Close') }}"></button>
    </div>
    <div class="offcanvas-body">
        <article class="ps-product--detail ps-product--fullwidth ps-product--quickshop">
            <div class="modal-body"></div>
        </article>
    </div>
</div>
