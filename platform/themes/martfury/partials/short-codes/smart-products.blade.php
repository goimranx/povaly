<div class="ps-smart-products">
    <div class="ps-container">
        <div class="ps-block--smart-products">
            <div class="ps-block__header">
                <h3>{{ $title }}</h3>
            </div>
            <div class="ps-block__content">
                <div class="ps-grid--smart-products js-smart-products-grid"
                     data-category-id="{{ $categoryId ?? '' }}"
                     data-load-more-type="{{ $loadMoreType }}"
                     data-items-per-page="{{ $itemsPerPage }}"
                     data-current-page="1"
                     data-total-shown="{{ $products->count() }}"
                     data-total-items="{{ $totalItems }}">
                    @foreach($products as $product)
                        @include('theme.martfury::partials.product-card-enhanced', [
                            'product' => $product,
                            'layout' => 'flyout'
                        ])
                    @endforeach
                </div>

                @if($loadMoreType !== 'none' && $hasMore)
                    <div class="ps-block__footer">
                        @if($loadMoreType === 'button')
                            <button type="button" class="ps-btn ps-btn--outline js-load-more-smart-products">
                                <span class="btn-text">{{ __('Load More Products') }}</span>
                                <span class="btn-loading d-none">
                                    <i class="fa fa-spinner fa-spin"></i> {{ __('Loading...') }}
                                </span>
                            </button>
                        @elseif($loadMoreType === 'auto')
                            <div class="js-smart-products-sentinel" style="height: 1px;"></div>
                            <div class="ps-loading-indicator d-none">
                                <i class="fa fa-spinner fa-spin"></i> {{ __('Loading more products...') }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Smart Products Section */
    .ps-smart-products {
        margin: 10px 0 !important;
        background: var(--global-color-site-bg);
    }

    .ps-block--smart-products {
        border: none;
        border-radius: 0;
        padding: 0;
    }

    .ps-block--smart-products .ps-block__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0;
        padding: 0 0 10px 0;
    }

    .ps-block--smart-products .ps-block__header h3 {
        font-size: 1.65rem;
        font-weight: 600;
        color: var(--global-color-text-main);
        margin: 0;
    }

    .ps-block--smart-products .ps-block__content {
        margin: 0;
    }

    /* Grid Layout - Desktop: 6 items */
    .ps-grid--smart-products {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
    }

    /* Responsive Grid */
    @media (max-width: 1680px) {
        .ps-grid--smart-products {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    @media (max-width: 1366px) {
        .ps-grid--smart-products {
            grid-template-columns: repeat(5, 1fr);
        }
    }

    @media (max-width: 1199px) {
        .ps-grid--smart-products {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 991px) {
        .ps-grid--smart-products {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 767px) {
        .ps-grid--smart-products {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 575px) {
        .ps-grid--smart-products {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Mobile (≤768px) */
    @media (max-width: 768px) {
        .ps-smart-products .ps-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .ps-block--smart-products {
            padding: 0 !important;
            margin-bottom: 0 !important;
            border: none !important;
        }

        .ps-block--smart-products .ps-block__header {
            padding: 0 0 10px 0 !important;
            margin-bottom: 0 !important;
        }

        .ps-grid--smart-products {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 10px !important;
        }
    }

    /* Tablet (481-767px) */
    @media (min-width: 481px) and (max-width: 767px) {
        .ps-smart-products .ps-container {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        .ps-block--smart-products .ps-block__header {
            padding: 0 0 10px 0 !important;
        }
    }

    /* Mobile (≤480px) */
    @media (max-width: 480px) {
        .ps-smart-products .ps-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .ps-block--smart-products .ps-block__header {
            padding: 0 10px 10px 10px !important;
        }

        .ps-grid--smart-products {
            grid-template-columns: repeat(1, 1fr) !important;
        }
    }

    /* Load More Button */
    .ps-block__footer {
        text-align: center;
        padding: 20px 0;
    }

    .ps-btn {
        display: inline-block;
        padding: 12px 32px;
        font-size: 14px;
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .ps-btn--outline {
        background: transparent;
        border: 2px solid var(--global-color-link);
        color: var(--global-color-link);
    }

    .ps-btn--outline:hover {
        background: var(--global-color-link);
        color: white;
    }

    .ps-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .ps-loading-indicator {
        text-align: center;
        padding: 20px;
        color: var(--global-color-link);
    }

    .ps-loading-indicator i {
        margin-right: 8px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grids = document.querySelectorAll('.js-smart-products-grid');
    
    grids.forEach(grid => {
        const loadMoreType = grid.dataset.loadMoreType;
        const button = grid.closest('.ps-block--smart-products').querySelector('.js-load-more-smart-products');
        const sentinel = grid.closest('.ps-block--smart-products').querySelector('.js-smart-products-sentinel');
        const loadingIndicator = grid.closest('.ps-block--smart-products').querySelector('.ps-loading-indicator');
        
        if (loadMoreType === 'button' && button) {
            button.addEventListener('click', function() {
                loadMoreProducts(grid, button);
            });
        } else if (loadMoreType === 'auto' && sentinel) {
            setupInfiniteScroll(grid, sentinel, loadingIndicator);
        }
    });
    
    function loadMoreProducts(grid, button) {
        const categoryId = grid.dataset.categoryId || '';
        const perPage = parseInt(grid.dataset.itemsPerPage);
        const currentPage = parseInt(grid.dataset.currentPage);
        const totalShown = parseInt(grid.dataset.totalShown);
        const totalItems = parseInt(grid.dataset.totalItems);
        const nextPage = currentPage + 1;
        
        // Disable button and show loading
        button.disabled = true;
        button.querySelector('.btn-text').classList.add('d-none');
        button.querySelector('.btn-loading').classList.remove('d-none');
        
        const url = new URL('{{ route('public.ajax.smart-products') }}', window.location.origin);
        url.searchParams.set('page', nextPage);
        url.searchParams.set('per_page', perPage);
        if (categoryId) {
            url.searchParams.set('category_id', categoryId);
        }
        
        console.log('Loading more products from:', url.toString());
        
        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success && data.data.html) {
                    // Append products
                    grid.insertAdjacentHTML('beforeend', data.data.html);
                    
                    // Update data attributes
                    grid.dataset.currentPage = nextPage;
                    grid.dataset.totalShown = data.data.totalShown;
                    
                    // Hide button if no more products
                    if (!data.data.hasMore || data.data.totalShown >= totalItems) {
                        button.closest('.ps-block__footer').style.display = 'none';
                    }
                }
                
                // Re-enable button
                button.disabled = false;
                button.querySelector('.btn-text').classList.remove('d-none');
                button.querySelector('.btn-loading').classList.add('d-none');
            })
            .catch(error => {
                console.error('Error loading products:', error);
                console.error('Error details:', error.message, error.stack);
                alert('{{ __('Failed to load more products. Please try again.') }}');
                
                // Re-enable button
                button.disabled = false;
                button.querySelector('.btn-text').classList.remove('d-none');
                button.querySelector('.btn-loading').classList.add('d-none');
            });
    }
    
    function setupInfiniteScroll(grid, sentinel, loadingIndicator) {
        let isLoading = false;
        
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !isLoading) {
                    const totalShown = parseInt(grid.dataset.totalShown);
                    const totalItems = parseInt(grid.dataset.totalItems);
                    
                    if (totalShown >= totalItems) {
                        observer.disconnect();
                        return;
                    }
                    
                    isLoading = true;
                    loadingIndicator.classList.remove('d-none');
                    
                    const categoryId = grid.dataset.categoryId || '';
                    const perPage = parseInt(grid.dataset.itemsPerPage);
                    const currentPage = parseInt(grid.dataset.currentPage);
                    const nextPage = currentPage + 1;
                    
                    const url = new URL('{{ route('public.ajax.smart-products') }}', window.location.origin);
                    url.searchParams.set('page', nextPage);
                    url.searchParams.set('per_page', perPage);
                    if (categoryId) {
                        url.searchParams.set('category_id', categoryId);
                    }
                    
                    console.log('Auto-loading products from:', url.toString());
                    
                    fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            console.log('Auto-load response status:', response.status);
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Auto-load data:', data);
                            if (data.success && data.data.html) {
                                grid.insertAdjacentHTML('beforeend', data.data.html);
                                grid.dataset.currentPage = nextPage;
                                grid.dataset.totalShown = data.data.totalShown;
                                
                                if (!data.data.hasMore || data.data.totalShown >= totalItems) {
                                    observer.disconnect();
                                    loadingIndicator.style.display = 'none';
                                }
                            }
                            
                            isLoading = false;
                            loadingIndicator.classList.add('d-none');
                        })
                        .catch(error => {
                            console.error('Error loading products:', error);
                            console.error('Error details:', error.message, error.stack);
                            isLoading = false;
                            loadingIndicator.classList.add('d-none');
                        });
                }
            });
        }, {
            rootMargin: '100px'
        });
        
        observer.observe(sentinel);
    }
});
</script>
