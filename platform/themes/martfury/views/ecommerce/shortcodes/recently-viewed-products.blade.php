@php
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
                <button type="button" class="ps-block__clear-history" id="clearRecentlyViewedHistory">
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clearButton = document.getElementById('clearRecentlyViewedHistory');
    const container = document.querySelector('.ps-recently-viewed-products');
    
    // Clear history button handler
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            if (confirm('{{ __('Are you sure you want to clear your recently viewed products history?') }}')) {
                fetch('{{ route('public.ajax.clear-recently-viewed') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error === false) {
                        // Hide the entire section
                        if (container) {
                            container.style.display = 'none';
                        }
                        
                        // Reload page to show updated list
                        window.location.reload();
                    } else {
                        alert(data.message || '{{ __('Failed to clear history. Please try again.') }}');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('{{ __('An error occurred. Please try again.') }}');
                });
            }
        });
    }
});
</script>
@endif
