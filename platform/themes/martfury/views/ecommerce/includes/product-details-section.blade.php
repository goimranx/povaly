@php
    $sectionId = $sectionId ?? 'desktop';
    $idSuffix = $sectionId === 'mobile' ? '-mobile' : '';
    $sectionClass = 'pd-section pd-section-' . $sectionId;
@endphp

<div class="{{ $sectionClass }}">
    <div class="pd-section-header">
        <h2 class="pd-section-title">{{ __('Product Details') }}</h2>
        <div class="pd-section-separator"></div>
    </div>
    
    <div class="pd-accordion" id="pd-accordion{{ $idSuffix }}">

    {{-- 8a. About this item --}}
    @if ($product->description)
        <div class="pd-accordion-item">
            <button class="pd-accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#pd-about{{ $idSuffix }}" aria-expanded="true">
                <span>{{ __('About this item') }}</span>
                <i class="ph ph-caret-down pd-accordion-icon"></i>
            </button>
            <div id="pd-about{{ $idSuffix }}" class="collapse show" data-bs-parent="#pd-accordion{{ $idSuffix }}">
                <div class="pd-accordion-body ps-list--dot">
                    {!! apply_filters('ecommerce_before_product_description', null, $product) !!}
                    <div class="pd-description-content pd-description-collapsed" id="pd-description-content{{ $idSuffix }}">
                        {!! BaseHelper::clean($product->description) !!}
                    </div><button type="button" class="pd-read-more-btn" id="pd-read-more-btn{{ $idSuffix }}" onclick="toggleDescription{{ $idSuffix === '-mobile' ? 'Mobile' : '' }}()">{{ __('Read more') }}</button>
                    {!! apply_filters('ecommerce_after_product_description', null, $product) !!}
                    <a href="#tab-description" class="pd-see-details-btn">{{ __('See full details') }}</a>
                </div>
            </div>
        </div>
    @endif

    {{-- 8b. Item details --}}
    @php
        $hasDetails = $product->brand_id
            || $product->categories->isNotEmpty()
            || $product->tags->isNotEmpty()
            || $product->productCollections->isNotEmpty()
            || $product->sku;
    @endphp
    @if ($hasDetails)
        <div class="pd-accordion-item">
            <button class="pd-accordion-btn collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pd-details{{ $idSuffix }}" aria-expanded="false">
                <span>{{ __('Item details') }}</span>
                <i class="ph ph-caret-down pd-accordion-icon"></i>
            </button>
            <div id="pd-details{{ $idSuffix }}" class="collapse" data-bs-parent="#pd-accordion{{ $idSuffix }}">
                <div class="pd-accordion-body pd-item-details">
                    @if ($product->sku)
                        <div class="pd-detail-row">
                            <span class="pd-detail-label">{{ __('SKU') }}</span>
                            <span class="pd-detail-value" data-bb-value="product-sku">{{ $product->sku }}</span>
                        </div>
                    @endif
                    @if ($product->brand_id && $product->brand->name)
                        <div class="pd-detail-row">
                            <span class="pd-detail-label">{{ __('Brand') }}</span>
                            <span class="pd-detail-value"><a href="{{ $product->brand->url }}">{{ $product->brand->name }}</a></span>
                        </div>
                    @endif
                    @if ($product->categories->isNotEmpty())
                        <div class="pd-detail-row">
                            <span class="pd-detail-label">{{ __('Categories') }}</span>
                            <span class="pd-detail-value">
                                @foreach($product->categories as $category)
                                    <a href="{{ $category->url }}">{!! BaseHelper::clean($category->name) !!}</a>@if (!$loop->last), @endif
                                @endforeach
                            </span>
                        </div>
                    @endif
                    @if ($product->tags->isNotEmpty())
                        <div class="pd-detail-row">
                            <span class="pd-detail-label">{{ __('Tags') }}</span>
                            <span class="pd-detail-value">
                                @foreach($product->tags as $tag)
                                    <a href="{{ $tag->url }}">{{ $tag->name }}</a>@if (!$loop->last), @endif
                                @endforeach
                            </span>
                        </div>
                    @endif
                    @if ($product->productCollections->isNotEmpty())
                        <div class="pd-detail-row">
                            <span class="pd-detail-label">{{ __('Collections') }}</span>
                            <span class="pd-detail-value">
                                @foreach($product->productCollections as $col)
                                    {{ $col->name }}@if (!$loop->last), @endif
                                @endforeach
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- 8c. Return policy --}}
    <div class="pd-accordion-item">
        <button class="pd-accordion-btn collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pd-return{{ $idSuffix }}" aria-expanded="false">
            <span>{{ __('Return policy') }}</span>
            <i class="ph ph-caret-down pd-accordion-icon"></i>
        </button>
        <div id="pd-return{{ $idSuffix }}" class="collapse" data-bs-parent="#pd-accordion{{ $idSuffix }}">
            <div class="pd-accordion-body pd-return-policy">
                <div class="pd-return-text">
                    <p><strong>{{ __('7 day returns') }}</strong></p>
                    <p>{{ __('Return within 7 days of receiving your order. We cover full refund minus the delivery charge. Simply contact us and we\'ll handle the rest.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 8d. Review highlights --}}
    @if (EcommerceHelper::isReviewEnabled() && $product->reviews_count > 0)
        @php
            $topReviews = $product->reviews()
                ->where('star', 5)
                ->with('user')
                ->latest()
                ->limit(4)
                ->get();
        @endphp
        <div class="pd-accordion-item">
            <button class="pd-accordion-btn collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pd-reviews{{ $idSuffix }}" aria-expanded="false">
                <span>{{ __('Review highlights') }}</span>
                <span class="pd-accordion-rating-inline">
                    <span class="pd-rating-avg">{{ number_format($product->reviews_avg, 1) }}</span>
                    <span class="pd-rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($product->reviews_avg))
                                <i class="ph-fill ph-star"></i>
                            @elseif ($i - $product->reviews_avg < 1)
                                <i class="ph-fill ph-star-half"></i>
                            @else
                                <i class="ph ph-star"></i>
                            @endif
                        @endfor
                    </span>
                    <span class="pd-rating-count">({{ number_format($product->reviews_count) }})</span>
                </span>
                <i class="ph ph-caret-down pd-accordion-icon"></i>
            </button>
            <div id="pd-reviews{{ $idSuffix }}" class="collapse" data-bs-parent="#pd-accordion{{ $idSuffix }}">
                <div class="pd-accordion-body">
                    @if ($topReviews->isNotEmpty())
                        <div class="pd-review-list">
                            @foreach($topReviews as $review)
                                <div class="pd-review-item">
                                    <div class="pd-review-header">
                                        <span class="pd-review-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="{{ $i <= $review->star ? 'ph-fill' : 'ph' }} ph-star"></i>
                                            @endfor
                                        </span>
                                        <span class="pd-review-author">{{ $review->user_name ?: __('Customer') }}</span>
                                        <span class="pd-review-date">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                    @if ($review->comment)
                                        <p class="pd-review-comment">{{ Str::limit($review->comment, 150) }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <a href="#tab-reviews" class="pd-read-reviews-btn">{{ __('Read all reviews') }}</a>
                </div>
            </div>
        </div>
    @endif

    </div>
    {{-- END: pd-accordion --}}

    {{-- 9. FAQs --}}
    @if (is_plugin_active('faq') && count($product->faq_items) > 0)
        <div class="pd-faqs" id="pd-faq-accordion{{ $idSuffix }}">
            <div class="pd-faqs-title">{{ __('Frequently Asked Questions') }}</div>
            @foreach($product->faq_items as $faq)
                <div class="pd-accordion-item">
                    <button class="pd-accordion-btn {{ $loop->first ? '' : 'collapsed' }}" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#pd-faq{{ $idSuffix }}-{{ $loop->index }}"
                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                        <span>{!! BaseHelper::clean($faq[0]['value']) !!}</span>
                        <i class="ph ph-caret-down pd-accordion-icon"></i>
                    </button>
                    <div id="pd-faq{{ $idSuffix }}-{{ $loop->index }}" class="collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#pd-faq-accordion{{ $idSuffix }}">
                        <div class="pd-accordion-body">
                            {!! BaseHelper::clean($faq[1]['value']) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
