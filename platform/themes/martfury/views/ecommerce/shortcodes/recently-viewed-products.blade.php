@php
    // Handle clear request
    if (request()->has('clear_recently_viewed') && request()->get('clear_recently_viewed') == '1') {
        if (auth('customer')->check()) {
            auth('customer')->user()->viewedProducts()->detach();
        } else {
            Cart::instance('recently_viewed')->destroy();
        }
        
        // Redirect to remove the query parameter
        if (request()->ajax()) {
            echo '<script>window.location.href = window.location.pathname;</script>';
            exit;
        } else {
            header('Location: ' . url()->current());
            exit;
        }
    }
    
    // Get recently viewed products directly
    $recentlyViewedProducts = collect();
    
    if (EcommerceHelper::isEnabledCustomerRecentlyViewedProducts()) {
        $productRepository = app(\Botble\Ecommerce\Repositories\Interfaces\ProductInterface::class);
        
        $queryParams = [
            'paginate' => [
                'per_page' => 12,
                'current_paged' => 1,
            ],
            'with' => EcommerceHelper::withProductEagerLoadingRelations(),
        ];

        if (auth('customer')->check()) {
            $recentlyViewedProducts = $productRepository->getProductsRecentlyViewed(auth('customer')->id(), $queryParams);
        } else {
            $itemIds = collect(Cart::instance('recently_viewed')->content())
                ->sortBy([['updated_at', 'desc']])
                ->pluck('id')
                ->all();

            if ($itemIds) {
                $recentlyViewedProducts = $productRepository->getProductsByIds($itemIds, $queryParams);
            }
        }
    }
@endphp

@if($recentlyViewedProducts && $recentlyViewedProducts->isNotEmpty())
<div class="ps-recently-viewed-products">
    <div class="ps-container">
        <div class="ps-block--recently-viewed">
            <div class="ps-block__header">
                <h3>{{ $shortcode->title ?: __('Recently Viewed') }}</h3>
                <button type="button" class="ps-block__clear-history" data-bs-toggle="modal" data-bs-target="#clearHistoryModal">
                    {{ __('Clear History') }}
                </button>
            </div>
            <div class="ps-block__content">
                <div class="ps-grid--recently-viewed">
                    @foreach($recentlyViewedProducts as $product)
                        @include('theme.martfury::partials.product-card-enhanced', [
                            'product' => $product,
                            'layout' => 'flyout'
                        ])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clear History Confirmation Modal -->
<div class="modal fade" id="clearHistoryModal" tabindex="-1" aria-labelledby="clearHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearHistoryModalLabel">{{ __('Clear Browsing History') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to clear your recently viewed products history?') }}</p>
                <p class="text-muted small mb-0">{{ __('This action cannot be undone.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmClearHistory">{{ __('Clear History') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmButton = document.getElementById('confirmClearHistory');
    
    if (confirmButton) {
        confirmButton.addEventListener('click', function() {
            // Disable button
            confirmButton.disabled = true;
            confirmButton.textContent = '{{ __('Clearing...') }}';
            
            // Redirect to same page with clear parameter
            window.location.href = window.location.pathname + '?clear_recently_viewed=1';
        });
    }
});
</script>

<style>
    /* Recently Viewed Products Section */
    .ps-recently-viewed-products {
        margin: 10px 0 !important;
        background: var(--global-color-site-bg);
    }

    /* Recently Viewed Block */
    .ps-block--recently-viewed {
        border: none;
        border-radius: 0;
        padding: 0;
        height: 100%;
    }

    .ps-block--recently-viewed .ps-block__header {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: 0;
        padding: 0 0 10px 0;
        flex-wrap: nowrap;
        gap: 15px;
    }

    .ps-block--recently-viewed .ps-block__header h3 {
        font-size: 1.65rem;
        font-weight: 600;
        color: var(--global-color-text-main);
        margin: 0;
        flex-shrink: 0;
    }

    .ps-block__clear-history {
        font-size: 14px;
        color: var(--global-color-alert);
        text-decoration: none;
        background: transparent;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: color 0.3s ease;
        flex-shrink: 0;
        margin-right: auto;
    }

    .ps-block__clear-history:hover {
        color: var(--global-color-alert-text);
        text-decoration: underline;
    }

    .ps-block--recently-viewed .ps-block__content {
        margin: 0;
        position: relative;
    }

    /* Grid Layout - Desktop: 6 items */
    .ps-grid--recently-viewed {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
    }

    /* Responsive Grid */
    @media (max-width: 1680px) {
        .ps-grid--recently-viewed {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    @media (max-width: 1366px) {
        .ps-grid--recently-viewed {
            grid-template-columns: repeat(5, 1fr);
        }
    }

    @media (max-width: 1199px) {
        .ps-grid--recently-viewed {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 991px) {
        .ps-grid--recently-viewed {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 767px) {
        .ps-grid--recently-viewed {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 575px) {
        .ps-grid--recently-viewed {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Mobile (≤768px) */
    @media (max-width: 768px) {
        .ps-recently-viewed-products .ps-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .ps-block--recently-viewed {
            padding: 0 !important;
            margin-bottom: 0 !important;
            border: none !important;
            border-radius: 0 !important;
        }

        .ps-block--recently-viewed .ps-block__header {
            padding: 0 0 10px 0 !important;
            margin-bottom: 0 !important;
        }

        .ps-block--recently-viewed .ps-block__content {
            padding: 0 !important;
        }

        .ps-grid--recently-viewed {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 10px !important;
        }
    }

    /* Tablet (481-767px): Add ps-container padding */
    @media (min-width: 481px) and (max-width: 767px) {
        .ps-recently-viewed-products .ps-container {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        .ps-block--recently-viewed .ps-block__header {
            padding: 0 0 10px 0 !important;
        }
    }

    /* Mobile (≤480px): Single column + header gets horizontal padding */
    @media (max-width: 480px) {
        .ps-recently-viewed-products .ps-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .ps-block--recently-viewed .ps-block__header {
            padding: 0 10px 10px 10px !important;
        }

        .ps-grid--recently-viewed {
            grid-template-columns: repeat(1, 1fr) !important;
        }
    }

    /* Clear History Modal Styles */
    #clearHistoryModal .modal-content {
        border-radius: 8px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    #clearHistoryModal .modal-header {
        border-bottom: 1px solid #eee;
        padding: 20px 24px;
    }

    #clearHistoryModal .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--global-color-text-main);
    }

    #clearHistoryModal .modal-body {
        padding: 24px;
    }

    #clearHistoryModal .modal-body p {
        margin-bottom: 12px;
        color: var(--global-color-text-main);
    }

    #clearHistoryModal .modal-footer {
        border-top: 1px solid #eee;
        padding: 16px 24px;
        gap: 12px;
    }

    #clearHistoryModal .btn {
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    #clearHistoryModal .btn-secondary {
        background: #f5f5f5;
        border: 1px solid #ddd;
        color: #666;
    }

    #clearHistoryModal .btn-secondary:hover {
        background: #e8e8e8;
        border-color: #ccc;
    }

    #clearHistoryModal .btn-danger {
        background: var(--global-color-alert);
        border: none;
        color: white;
    }

    #clearHistoryModal .btn-danger:hover {
        background: var(--global-color-alert-text);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    #clearHistoryModal .btn-danger:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    #clearHistoryModal .btn-close {
        padding: 0.5rem;
        opacity: 0.5;
    }

    #clearHistoryModal .btn-close:hover {
        opacity: 1;
    }
</style>
@endif
