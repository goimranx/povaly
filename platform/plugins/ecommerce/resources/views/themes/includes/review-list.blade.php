@php
    $currentCustomer = auth('customer')->user();
    $isVendorOfProduct = $currentCustomer
        && $currentCustomer->is_vendor
        && isset($product)
        && $currentCustomer->store
        && $product->store_id == $currentCustomer->store->id;
@endphp

@foreach ($reviews as $review)
    @continue(! $review->is_approved && auth('customer')->id() != $review->customer_id)

    @php
        $isCurrentCustomerReview = auth('customer')->check() && auth('customer')->id() == $review->customer_id;
        $canReply = $isVendorOfProduct && ! $review->reply;
        $canDeleteReply = $isVendorOfProduct && $review->reply && $review->reply->customer_id == $currentCustomer?->id;
    @endphp

    <div @class([
        'review-item',
        'border-bottom' => ! $loop->last,
        'opacity-50' => ! $review->is_approved,
        'current-customer-review' => $isCurrentCustomerReview
    ])>
        <div class="review-item__main">
            {{-- Review Info Section --}}
            <div class="review-item__info">
                <div class="review-author">
                    <div class="review-author__avatar">
                        <img src="{{ $review->customer_avatar_url }}" alt="{{ $review->display_name }}">
                    </div>
                    <div class="review-author__details">
                        <div class="review-author__name">{{ $review->display_name }}</div>
                        <div class="review-author__rating">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->star)
                                    <i class="ph-fill ph-star"></i>
                                @else
                                    <i class="ph ph-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="review-author__comment" data-full-comment="{{ $review->comment }}">
                            {{ $review->comment }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Review Images Section --}}
            @if (EcommerceHelper::isCustomerReviewImageUploadEnabled() && $review->images)
                <div class="review-item__images">
                    <div class="review-images">
                        @foreach ($review->images as $image)
                            <a href="{{ RvMedia::getImageUrl($image) }}" class="review-image-thumb">
                                <img src="{{ RvMedia::getImageUrl($image, 'thumb') }}" alt="{{ $review->comment }}">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Review Actions Section --}}
            <div class="review-item__actions">
                @if ($isCurrentCustomerReview)
                    <div class="review-action-badge">
                        <span class="badge-your-review">{{ __('Your review!') }}</span>
                        <button
                            type="button"
                            class="btn-delete-review delete-review-btn"
                            data-review-id="{{ $review->id }}"
                            data-confirm-message="{{ trans('plugins/ecommerce::review.are_you_sure_you_want_to_delete_your_review') }}"
                            title="{{ trans('plugins/ecommerce::review.delete_your_review') }}"
                        >
                            <i class="ph ph-trash"></i>
                        </button>
                    </div>
                @else
                    <div class="review-action-helpful">
                        <span class="helpful-label">{{ __('Helpful?') }}</span>
                        <button type="button" class="btn-helpful" data-review-id="{{ $review->id }}">
                            <i class="ph ph-thumbs-up"></i>
                            <span class="helpful-count">0</span>
                        </button>
                    </div>
                    <div class="review-action-share">
                        <button type="button" class="btn-share" data-review-id="{{ $review->id }}" title="{{ __('Share this review') }}">
                            <i class="ph ph-share-network"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Review Meta Section --}}
        <div class="review-item__meta">
            <div class="review-meta__left">
                @php
                    $badgeType = $review->badge_type?->getValue() ?? \Botble\Ecommerce\Enums\ReviewBadgeEnum::AUTO;
                @endphp
                @if ($badgeType === \Botble\Ecommerce\Enums\ReviewBadgeEnum::AUTO && $review->order_created_at)
                    <div class="meta-purchased-date">{{ __('Purchased date:') }} {{ $review->order_created_at->format('M d, Y') }}</div>
                @elseif ($badgeType === \Botble\Ecommerce\Enums\ReviewBadgeEnum::PURCHASED)
                    <div class="meta-purchased-date">{{ __('Purchased date:') }} {{ $review->created_at->subHours(12)->format('M d, Y') }}</div>
                @endif
                
                @if ($review->meta && isset($review->meta['variations']) && !empty($review->meta['variations']))
                    <div class="meta-variations">
                        @foreach ($review->meta['variations'] as $key => $value)
                            <span class="variation-item">{{ $key }}: {{ $value }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="review-meta__right">
                <time class="review-date" datetime="{{ $review->created_at->translatedFormat('Y-m-d\TH:i:sP') }}">
                    {{ $review->created_at->format('M d, Y') }}
                </time>
            </div>
        </div>

        {{-- Vendor Reply Section --}}
        @if ($review->reply)
            <div class="review-item__reply">
                <div class="vendor-reply">
                    <div class="vendor-reply__avatar">
                        <img src="{{ $review->reply->responder_avatar_url }}" alt="{{ $review->reply->responder_name }}">
                    </div>
                    <div class="vendor-reply__content">
                        <div class="vendor-reply__header">
                            <span class="vendor-reply__title">{{ trans('plugins/ecommerce::review.seller_response') }}</span>
                            @if ($canDeleteReply)
                                <button
                                    type="button"
                                    class="vendor-delete-reply-btn"
                                    data-review-id="{{ $review->id }}"
                                    data-url="{{ route('public.reviews.reply.destroy', $review->id) }}"
                                    data-confirm-message="{{ trans('plugins/ecommerce::review.confirm_delete_reply') }}"
                                    title="{{ trans('plugins/ecommerce::review.delete_reply') }}"
                                >
                                    <i class="ph ph-trash"></i>
                                </button>
                            @endif
                        </div>
                        <div class="vendor-reply__message">{{ $review->reply->message }}</div>
                    </div>
                </div>
            </div>
        @elseif ($canReply)
            <div class="review-item__reply-form">
                <button
                    type="button"
                    class="vendor-reply-toggle-btn"
                    data-review-id="{{ $review->id }}"
                >
                    <i class="ph ph-message"></i>
                    {{ trans('plugins/ecommerce::review.reply') }}
                </button>

                <div
                    class="vendor-reply-form d-none"
                    data-review-id="{{ $review->id }}"
                    data-url="{{ route('public.reviews.reply', $review->id) }}"
                >
                    <textarea
                        class="form-control vendor-reply-message"
                        rows="2"
                        placeholder="{{ trans('plugins/ecommerce::review.write_your_reply') }}"
                    ></textarea>
                    <div class="vendor-reply-actions">
                        <button type="button" class="vendor-reply-submit-btn btn btn-primary" data-review-id="{{ $review->id }}">
                            {{ trans('plugins/ecommerce::review.submit_reply') }}
                        </button>
                        <button type="button" class="vendor-reply-cancel-btn btn btn-secondary" data-review-id="{{ $review->id }}">
                            {{ trans('plugins/ecommerce::review.cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endforeach

<div class="tp-pagination">
    {{ $reviews->links() }}
</div>
