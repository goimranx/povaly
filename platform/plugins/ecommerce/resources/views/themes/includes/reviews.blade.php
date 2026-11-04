@if (EcommerceHelper::isReviewEnabled())
    @php
        $version = EcommerceHelper::getAssetVersion();

        Theme::asset()->add('lightgallery-css', 'vendor/core/plugins/ecommerce/libraries/lightgallery/css/lightgallery.min.css', version: $version);
        Theme::asset()->add('front-ecommerce-css', 'vendor/core/plugins/ecommerce/css/front-ecommerce.css', version: $version);
        Theme::asset()->add('front-review-css', 'vendor/core/plugins/ecommerce/css/front-review.css', version: $version);
        Theme::asset()->container('footer')->add('lightgallery-js', 'vendor/core/plugins/ecommerce/libraries/lightgallery/js/lightgallery.min.js', ['jquery'], version: $version);
        Theme::asset()->container('footer')->add('lg-thumbnail-js', 'vendor/core/plugins/ecommerce/libraries/lightgallery/plugins/lg-thumbnail.min.js', ['lightgallery-js'], version: $version);
        Theme::asset()->container('footer')->add('review-js', 'vendor/core/plugins/ecommerce/js/front-review.js', ['lightgallery-js', 'lg-thumbnail-js'], version: $version);

        $showAvgRating ??= $product->reviews->isNotEmpty();
    @endphp

    <div class="product-review-container d-flex flex-row gap-5">
        {{-- Mobile: Rating Summary Header with Offcanvas Trigger --}}
        <div class="review-mobile-header d-lg-none" id="review-mobile-header" data-bs-toggle="offcanvas" data-bs-target="#reviewFiltersOffcanvas" role="button">
            <div class="review-mobile-summary">
                <div class="review-mobile-summary-left">
                    <h2 class="product-review-number-title">{{ trans('plugins/ecommerce::review.customer_reviews') }}</h2>
                    <div class="product-review-summary-rating">
                        <div class="rating-stars-custom">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($product->reviews_avg))
                                    <i class="ph-fill ph-star filled"></i>
                                @elseif ($i - 0.5 <= $product->reviews_avg)
                                    <i class="ph-fill ph-star-half half-filled"></i>
                                @else
                                    <i class="ph ph-star"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="rating-text">
                            {{ number_format($product->reviews_avg ?: 0, 1) }} out of 5
                        </p>
                    </div>
                    <div class="review-mobile-count">
                        {{ number_format($product->reviews_count) }} {{ __('global public ratings') }}
                    </div>
                </div>
                <div class="btn-review-offcanvas-toggle">
                    <i class="ph ph-caret-right"></i>
                </div>
            </div>
        </div>

        {{-- Desktop: Sidebar Column --}}
        <div class="review-sidebar-column d-none d-lg-flex @auth('customer') flex-column gap-3 @else flex-row gap-3 review-sidebar-column-guest @endauth" style="width: 15.5%; flex-grow: 1;">
            @if ($showAvgRating)
                <div class="review-sidebar-wrapper" id="review-rating-summary">
                    <div class="product-review-number review-sidebar-sticky">
                        <h2 class="product-review-number-title">{{ trans('plugins/ecommerce::review.customer_reviews') }}</h2>

                        <div class="product-review-summary">
                            <div class="product-review-summary-rating">
                                <div class="rating-stars-custom">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($product->reviews_avg))
                                            <i class="ph-fill ph-star filled"></i>
                                        @elseif ($i - 0.5 <= $product->reviews_avg)
                                            <i class="ph-fill ph-star-half half-filled"></i>
                                        @else
                                            <i class="ph ph-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="rating-text">
                                    {{ number_format($product->reviews_avg ?: 0, 1) }} out of 5
                                </p>
                            </div>
                        </div>

                        <div class="product-review-progress" id="review-progress-bars">
                            @foreach (EcommerceHelper::getReviewsGroupedByProductId($product->id, $product->reviews_count) as $item)
                                <div @class(['product-review-progress-bar', 'disabled' => ! $item['count'], 'clickable' => $item['count']])
                                     @if($item['count'])
                                        data-bb-toggle="review-star-filter-bar"
                                        data-star="{{ $item['star'] }}"
                                        role="button"
                                        tabindex="0"
                                        title="{{ trans('plugins/ecommerce::review.filter_by_star_reviews', ['star' => $item['star']]) }}"
                                     @endif>
                                    <span class="product-review-progress-bar-title">
                                        @if($item['star'] == 1)
                                            {{ trans('plugins/ecommerce::review.number_star_singular', ['number' => $item['star']]) }}
                                        @else
                                            {{ trans('plugins/ecommerce::review.number_stars_plural', ['number' => $item['star']]) }}
                                        @endif
                                    </span>
                                    <div class="progress product-review-progress-bar-value">
                                        <div
                                            class="progress-bar"
                                            role="progressbar"
                                            aria-valuenow="{{ $item['percent'] }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: {{ $item['percent'] }}%"
                                        ></div>
                                    </div>
                                    <span class="product-review-progress-bar-percent">
                                        {{ $item['percent'] }}%
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @guest('customer')
                <div class="review-guest-wrapper" style="width: 60%; flex-grow: 1;" id="review-guest-section">
                    <div class="review-guest-container">
                        <h3 class="review-guest-title">{{ __('Customer Say') }}</h3>
                        
                        @if($product->reviews->isNotEmpty())
                            <p class="review-guest-summary">
                                {{ __('Customers find this product to be of good quality and appreciate its features. Many highlight the excellent value for money and performance. Some mention positive experiences with durability and ease of use.') }}
                            </p>
                            
                            <div class="review-guest-signin-box">
                                <i class="ph ph-info"></i>
                                <a href="{{ route('customer.login') }}" class="review-signin-link">{{ __('Sign in') }}</a>
                                <span>{{ __('to see customer reviews.') }}</span>
                            </div>
                        @else
                            <p class="review-guest-no-reviews">{{ __('No customer reviews yet. Be the first to share your thoughts!') }}</p>
                            
                            <a href="{{ route('customer.login') }}" class="btn-review-signin">
                                {{ trans('plugins/ecommerce::ecommerce.sign_in') }}
                            </a>
                            
                            <p class="review-guest-register">
                                {{ trans('plugins/ecommerce::ecommerce.new_customer') }}
                                <a href="{{ route('customer.register') }}" class="review-register-link">{{ trans('plugins/ecommerce::ecommerce.start_here') }}</a>
                            </p>
                        @endif
                    </div>
                </div>
            @else
                <div class="review-logged-in-wrapper" id="review-write-section">
                    <div class="review-logged-in-container">
                        @if(isset($checkReview) && $checkReview['error'])
                            @if($checkReview['type'] === 'already_reviewed')
                                {{-- User has already reviewed --}}
                                <h3 class="review-section-title">{{ __('Thanks for your review') }}</h3>
                                <p class="review-section-subtitle">{{ __('You have already reviewed this product') }}</p>
                                <a href="{{ route('customer.product-reviews') }}" class="btn-write-review-inline">
                                    <svg class="icon svg-icon-ti-ti-star" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873l-6.158 -3.245"></path>
                                    </svg>
                                    {{ __('View Your Reviews') }}
                                </a>
                            @elseif($checkReview['type'] === 'purchase_required')
                                {{-- User hasn't purchased the product --}}
                                <h3 class="review-section-title">{{ __('We see you have not met the criteria for write a review') }}</h3>
                                <p class="review-section-subtitle">{{ __('Purchase this product first to share your experience') }}</p>
                                <a href="{{ route('public.products') }}" class="btn-write-review-inline" style="background: none; border: 1px solid currentColor; padding: 10px 20px;">
                                    {{ __('Shop the Povaly first') }}
                                </a>
                            @endif
                        @else
                            {{-- User hasn't reviewed yet --}}
                            <h3 class="review-section-title">{{ __('Review the product') }}</h3>
                            <p class="review-section-subtitle">{{ __('Share your thoughts with other customers') }}</p>
                            <button type="button" class="btn-write-review-inline" id="btnWriteReview" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                {{ trans('plugins/ecommerce::review.write_a_review') }}
                            </button>
                        @endif
                    </div>
                    
                    {{-- Link to Visual Gallery --}}
                    @if($product->reviews->isNotEmpty())
                        <div class="review-visual-gallery-link">
                            <a href="#ps-customer-photos-gallery" class="link-visual-gallery">
                                {{ __('Visual what customer uploads for this product') }}
                            </a>
                        </div>
                    @endif
                </div>
            @endguest
        </div>

        @auth('customer')
            @if ($product->reviews->isNotEmpty())
                <div class="position-relative review-list-container" id="review-list-section" style="width: 65.5%; flex-grow: 1;" data-ajax-url="{{ route('public.ajax.reviews', $product->id) }}">
                    <h4 class="mb-3 d-none">{{ trans('plugins/ecommerce::review.total_reviews_for_product', ['total' => number_format($product->reviews_count), 'product' => $product->name]) }}</h4>

                    <div class="product-review-controls mb-4" id="review-controls-section">
                        <div class="review-global-ratings-text">
                            {{ number_format($product->reviews_count) }} {{ __('global public ratings') }}
                        </div>

                        <div class="d-flex align-items-center gap-3 review-inline-controls">
                            <div class="review-search-box">
                                <div class="position-relative">
                                    <input
                                        type="search"
                                        class="form-control review-search-input"
                                        placeholder="{{ trans('plugins/ecommerce::review.search_reviews_1') }}"
                                        data-bb-toggle="review-search"
                                    >
                                    <div class="position-absolute top-50 end-0 translate-middle-y pe-3">
                                        <x-core::icon name="ti ti-search" class="text-muted" />
                                    </div>
                                </div>
                            </div>

                            <div class="review-filter-box d-flex align-items-center gap-2">
                                <label class="review-filter-label mb-0">{{ __('Rating:') }}</label>
                                <select class="form-select review-star-filter" data-bb-toggle="review-star-filter">
                                    <option value="">{{ trans('plugins/ecommerce::review.all_stars') }}</option>
                                    <option value="5">{{ trans('plugins/ecommerce::review.5_stars') }}</option>
                                    <option value="4">{{ trans('plugins/ecommerce::review.4_stars') }}</option>
                                    <option value="3">{{ trans('plugins/ecommerce::review.3_stars') }}</option>
                                    <option value="2">{{ trans('plugins/ecommerce::review.2_stars') }}</option>
                                    <option value="1">{{ trans('plugins/ecommerce::review.1_star') }}</option>
                                </select>
                            </div>

                            <div class="review-sort-box d-flex align-items-center gap-2">
                                <label class="review-sort-label mb-0">{{ __('Sort by:') }}</label>
                                <select class="form-select review-sort-select" data-bb-toggle="review-sort">
                                    <option value="newest">{{ trans('plugins/ecommerce::ecommerce.newest') }}</option>
                                    <option value="oldest">{{ trans('plugins/ecommerce::ecommerce.oldest') }}</option>
                                    <option value="highest_rating">{{ trans('plugins/ecommerce::review.highest_rating') }}</option>
                                    <option value="lowest_rating">{{ trans('plugins/ecommerce::review.lowest_rating') }}</option>
                                </select>
                            </div>

                            <button type="button" class="btn review-clear-btn d-none" data-bb-toggle="review-clear-filters" title="{{ trans('plugins/ecommerce::ecommerce.clear_all_filters') }}">
                                <x-core::icon name="ti ti-x" class="me-1" />
                                {{ trans('plugins/ecommerce::ecommerce.clear') }}
                            </button>
                        </div>
                    </div>

                    <div class="review-list"></div>
                </div>
            @else
                <p class="text-muted text-center">{{ trans('plugins/ecommerce::review.looks_like_there_are_no_reviews_yet') }} </p>
            @endif
        @endauth
    </div>

    {{-- Mobile Offcanvas: Review Filters & Options --}}
    @auth('customer')
        <div class="offcanvas offcanvas-end" tabindex="-1" id="reviewFiltersOffcanvas" aria-labelledby="reviewFiltersOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="reviewFiltersOffcanvasLabel">{{ __('Review Options') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                {{-- Rating Summary Section --}}
                <div class="offcanvas-section" id="offcanvas-rating-summary">
                    <h3 class="offcanvas-section-title">{{ trans('plugins/ecommerce::review.customer_reviews') }}</h3>
                    <div class="product-review-summary">
                        <div class="product-review-summary-rating">
                            <div class="rating-stars-custom">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($product->reviews_avg))
                                        <i class="ph-fill ph-star filled"></i>
                                    @elseif ($i - 0.5 <= $product->reviews_avg)
                                        <i class="ph-fill ph-star-half half-filled"></i>
                                    @else
                                        <i class="ph ph-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="rating-text">
                                {{ number_format($product->reviews_avg ?: 0, 1) }} out of 5
                            </p>
                        </div>
                    </div>

                    {{-- Progress Bars --}}
                    <div class="product-review-progress mt-3" id="offcanvas-progress-bars">
                        @foreach (EcommerceHelper::getReviewsGroupedByProductId($product->id, $product->reviews_count) as $item)
                            <div @class(['product-review-progress-bar', 'disabled' => ! $item['count'], 'clickable' => $item['count']])
                                 @if($item['count'])
                                    data-bb-toggle="review-star-filter-bar"
                                    data-star="{{ $item['star'] }}"
                                    role="button"
                                    tabindex="0"
                                    title="{{ trans('plugins/ecommerce::review.filter_by_star_reviews', ['star' => $item['star']]) }}"
                                    data-bs-dismiss="offcanvas"
                                 @endif>
                                <span class="product-review-progress-bar-title">
                                    @if($item['star'] == 1)
                                        {{ trans('plugins/ecommerce::review.number_star_singular', ['number' => $item['star']]) }}
                                    @else
                                        {{ trans('plugins/ecommerce::review.number_stars_plural', ['number' => $item['star']]) }}
                                    @endif
                                </span>
                                <div class="progress product-review-progress-bar-value">
                                    <div
                                        class="progress-bar"
                                        role="progressbar"
                                        aria-valuenow="{{ $item['percent'] }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        style="width: {{ $item['percent'] }}%"
                                    ></div>
                                </div>
                                <span class="product-review-progress-bar-percent">
                                    {{ $item['percent'] }}%
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="my-4">

                {{-- Write Review Section --}}
                <div class="offcanvas-section" id="offcanvas-write-review">
                    @if(isset($checkReview) && $checkReview['error'])
                        @if($checkReview['type'] === 'already_reviewed')
                            <h3 class="offcanvas-section-title">{{ __('Thanks for your review') }}</h3>
                            <p class="text-muted small">{{ __('You have already reviewed this product') }}</p>
                            <a href="{{ route('customer.product-reviews') }}" class="btn btn-sm btn-outline-primary w-100" data-bs-dismiss="offcanvas">
                                <i class="ph ph-star me-1"></i>
                                {{ __('View Your Reviews') }}
                            </a>
                        @elseif($checkReview['type'] === 'purchase_required')
                            <h3 class="offcanvas-section-title">{{ __('Purchase Required') }}</h3>
                            <p class="text-muted small">{{ __('Purchase this product first to share your experience') }}</p>
                            <a href="{{ route('public.products') }}" class="btn btn-sm btn-outline-primary w-100" data-bs-dismiss="offcanvas">
                                {{ __('Shop the Povaly first') }}
                            </a>
                        @endif
                    @else
                        <h3 class="offcanvas-section-title">{{ __('Review the product') }}</h3>
                        <p class="text-muted small">{{ __('Share your thoughts with other customers') }}</p>
                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#reviewModal" data-bs-dismiss="offcanvas">
                            <i class="ph ph-pencil-simple me-1"></i>
                            {{ trans('plugins/ecommerce::review.write_a_review') }}
                        </button>
                    @endif
                </div>

                <hr class="my-4">

                {{-- Search & Filter Section --}}
                <div class="offcanvas-section" id="offcanvas-search-filter">
                    <h3 class="offcanvas-section-title">{{ __('Search & Filter') }}</h3>
                    
                    {{-- Search --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">{{ __('Search reviews') }}</label>
                        <div class="position-relative">
                            <input
                                type="search"
                                class="form-control review-search-input"
                                placeholder="{{ trans('plugins/ecommerce::review.search_reviews_1') }}"
                                data-bb-toggle="review-search"
                            >
                            <div class="position-absolute top-50 end-0 translate-middle-y pe-3">
                                <i class="ph ph-magnifying-glass text-muted"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Filter by Rating --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">{{ __('Rating:') }}</label>
                        <select class="form-select review-star-filter" data-bb-toggle="review-star-filter">
                            <option value="">{{ trans('plugins/ecommerce::review.all_stars') }}</option>
                            <option value="5">{{ trans('plugins/ecommerce::review.5_stars') }}</option>
                            <option value="4">{{ trans('plugins/ecommerce::review.4_stars') }}</option>
                            <option value="3">{{ trans('plugins/ecommerce::review.3_stars') }}</option>
                            <option value="2">{{ trans('plugins/ecommerce::review.2_stars') }}</option>
                            <option value="1">{{ trans('plugins/ecommerce::review.1_star') }}</option>
                        </select>
                    </div>

                    {{-- Sort --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">{{ __('Sort by:') }}</label>
                        <select class="form-select review-sort-select" data-bb-toggle="review-sort">
                            <option value="newest">{{ trans('plugins/ecommerce::ecommerce.newest') }}</option>
                            <option value="oldest">{{ trans('plugins/ecommerce::ecommerce.oldest') }}</option>
                            <option value="highest_rating">{{ trans('plugins/ecommerce::review.highest_rating') }}</option>
                            <option value="lowest_rating">{{ trans('plugins/ecommerce::review.lowest_rating') }}</option>
                        </select>
                    </div>

                    {{-- Apply Filters Button --}}
                    <button type="button" class="btn btn-primary w-100 mb-2" data-bs-dismiss="offcanvas">
                        <i class="ph ph-funnel me-1"></i>
                        {{ __('Apply Filters') }}
                    </button>

                    {{-- Clear Filters --}}
                    <button type="button" class="btn btn-outline-secondary w-100 d-none" data-bb-toggle="review-clear-filters" data-bs-dismiss="offcanvas">
                        <i class="ph ph-x me-1"></i>
                        {{ trans('plugins/ecommerce::ecommerce.clear') }}
                    </button>
                </div>

                @if($product->reviews->isNotEmpty())
                    <hr class="my-4">
                    
                    {{-- Visual Gallery Link --}}
                    <div class="offcanvas-section" id="offcanvas-visual-gallery">
                        <a href="#ps-customer-photos-gallery" class="btn btn-outline-primary w-100" data-bs-dismiss="offcanvas">
                            <i class="ph ph-images me-1"></i>
                            {{ __('Visual what customer uploads for this product') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endauth
    {{-- End Mobile Offcanvas --}}

    {{-- Review Modal --}}
    @auth('customer')
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content review-modal-content">
                    <div class="modal-header review-modal-header">
                        <h5 class="modal-title review-modal-title" id="reviewModalLabel">{{ trans('plugins/ecommerce::review.write_a_review') }}</h5>
                        <button type="button" class="btn-close review-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body review-modal-body">
                        @include($reviewFormView ?? EcommerceHelper::viewPath('includes.review-form'))
                    </div>
                    <div class="modal-footer review-modal-footer">
                        <button type="submit" form="review-form" class="btn btn-primary ps-btn">
                            {{ trans('plugins/ecommerce::ecommerce.submit_review') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endif
