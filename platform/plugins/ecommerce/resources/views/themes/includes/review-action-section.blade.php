{{-- Review Action Section (Write Review / Thanks Message) --}}
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
