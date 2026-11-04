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
        <div class="review-sidebar-column @auth('customer') d-flex flex-column gap-3 @else d-flex flex-row gap-3 @endauth" style="flex: 0 0 25%;">
            @if ($showAvgRating)
                <div class="review-sidebar-wrapper">
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

                        <div class="product-review-progress">
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
                <div class="review-guest-wrapper" style="flex: 0 0 75%;">
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
                <div class="review-logged-in-wrapper">
                    <div class="review-logged-in-container">
                        @if(isset($checkReview) && $checkReview['error'])
                            {{-- User has already reviewed --}}
                            <h3 class="review-section-title">{{ __('Thanks for your review') }}</h3>
                            <p class="review-section-subtitle">{{ __('You have already reviewed this product') }}</p>
                            <a href="{{ route('customer.product-reviews') }}" class="btn-write-review-inline">
                                <svg class="icon svg-icon-ti-ti-star" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873l-6.158 -3.245"></path>
                                </svg>
                                {{ __('View Your Reviews') }}
                            </a>
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
                <div class="position-relative review-list-container" style="flex: 0 0 75%;" data-ajax-url="{{ route('public.ajax.reviews', $product->id) }}">
                    <h4 class="mb-3">{{ trans('plugins/ecommerce::review.total_reviews_for_product', ['total' => number_format($product->reviews_count), 'product' => $product->name]) }}</h4>

                    <div class="d-flex align-items-center justify-content-between mb-4 product-review-controls">
                        <div class="d-flex gap-2 review-control-buttons">
                            <button type="button" class="btn review-control-btn" data-bb-toggle="review-search-toggle" title="{{ trans('plugins/ecommerce::review.search_reviews') }}">
                                <x-core::icon name="ti ti-search" class="me-1" />
                                {{ trans('plugins/ecommerce::ecommerce.search') }}
                            </button>
                            <button type="button" class="btn review-control-btn" data-bb-toggle="review-filter-toggle" title="{{ trans('plugins/ecommerce::review.filter_by_stars') }}">
                                <x-core::icon name="ti ti-filter" class="me-1" />
                                {{ trans('plugins/ecommerce::ecommerce.filter') }}
                            </button>
                            <button type="button" class="btn review-control-btn" data-bb-toggle="review-sort-toggle" title="{{ trans('plugins/ecommerce::review.sort_reviews') }}">
                                <x-core::icon name="ti ti-sort-ascending" class="me-1" />
                                {{ trans('plugins/ecommerce::ecommerce.sort') }}
                            </button>
                        </div>
                        <button type="button" class="btn review-clear-btn d-none" data-bb-toggle="review-clear-filters" title="{{ trans('plugins/ecommerce::ecommerce.clear_all_filters') }}">
                            <x-core::icon name="ti ti-x" class="me-1" />
                            {{ trans('plugins/ecommerce::ecommerce.clear') }}
                        </button>
                    </div>

                    <div class="review-search-container d-none mb-3">
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

                    <div class="review-filter-container d-none mb-3">
                        <select class="form-select review-star-filter" data-bb-toggle="review-star-filter">
                            <option value="">{{ trans('plugins/ecommerce::review.all_stars') }}</option>
                            <option value="5">{{ trans('plugins/ecommerce::review.5_stars') }}</option>
                            <option value="4">{{ trans('plugins/ecommerce::review.4_stars') }}</option>
                            <option value="3">{{ trans('plugins/ecommerce::review.3_stars') }}</option>
                            <option value="2">{{ trans('plugins/ecommerce::review.2_stars') }}</option>
                            <option value="1">{{ trans('plugins/ecommerce::review.1_star') }}</option>
                        </select>
                    </div>

                    <div class="review-sort-container d-none mb-3">
                        <select class="form-select review-sort-select" data-bb-toggle="review-sort">
                            <option value="newest">{{ trans('plugins/ecommerce::ecommerce.newest') }}</option>
                            <option value="oldest">{{ trans('plugins/ecommerce::ecommerce.oldest') }}</option>
                            <option value="highest_rating">{{ trans('plugins/ecommerce::review.highest_rating') }}</option>
                            <option value="lowest_rating">{{ trans('plugins/ecommerce::review.lowest_rating') }}</option>
                        </select>
                    </div>

                    <div class="review-list"></div>
                </div>
            @else
                <p class="text-muted text-center">{{ trans('plugins/ecommerce::review.looks_like_there_are_no_reviews_yet') }} </p>
            @endif
        @endauth
    </div>

    {{-- Review Modal --}}
    @auth('customer')
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewModalLabel">{{ trans('plugins/ecommerce::review.write_a_review') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @include($reviewFormView ?? EcommerceHelper::viewPath('includes.review-form'))
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endif
