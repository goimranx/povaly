@php
    $hasVideo = !empty($product->video) && is_array($product->video) && count(array_filter($product->video, fn($v) => !empty($v['url']))) > 0;
    $videoList = $hasVideo ? array_values(array_filter($product->video, fn($v) => !empty($v['url']))) : [];
    $galleryImages = !empty($productImages) ? $productImages : ($product->images ?? []);

    // Use default variation for initial price/stock if product has variations
    $displayProduct = ($product->has_variation && $productVariation && $productVariation->id !== $product->id)
        ? $productVariation
        : $product;

    // Stock text for initial render
    $stockText  = '';
    $stockClass = '';
    if ($displayProduct->isOutOfStock()) {
        $stockText  = __('Stock not available');
        $stockClass = 'qs-stock--out';
    } elseif ($displayProduct->with_storehouse_management && $displayProduct->quantity > 0) {
        $stockText  = __('Out of :qty units', ['qty' => number_format($displayProduct->quantity)]);
        $stockClass = '';
    } elseif (!$displayProduct->with_storehouse_management) {
        $stockText  = __('Out of available stocks');
        $stockClass = '';
    }
@endphp

<div class="qs-product">

    {{-- ── Gallery ───────────────────────────────────────────── --}}
    <div class="qs-gallery" data-has-video="{{ $hasVideo ? 'true' : 'false' }}">
        {{-- Mobile header: back (close) + share --}}
        <div class="qs-mobile-header">
            <button type="button" class="qs-mobile-header__btn qs-mobile-close" data-bs-dismiss="offcanvas" aria-label="{{ __('Close') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button type="button" class="qs-mobile-header__btn qs-share-btn" aria-label="{{ __('Share') }}" data-url="{{ $product->url }}" data-title="{{ $product->name }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </button>
        </div>

        <div class="qs-gallery__viewport">
            <button type="button" class="qs-arrow qs-arrow--prev" aria-label="{{ __('Previous') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <div class="qs-track">
                @foreach ($galleryImages as $img)
                    <div class="qs-slide qs-slide--image">
                        <img src="{{ RvMedia::getImageUrl($img) }}" alt="{{ $product->name }}" loading="lazy" />
                    </div>
                @endforeach
                @foreach ($videoList as $video)
                    <div class="qs-slide qs-slide--video" style="display:none;">
                        @if ($video['provider'] === 'video')
                            @php $ext = pathinfo($video['url'], PATHINFO_EXTENSION) ?: 'mp4'; if($ext === 'mov') $ext = 'mp4'; @endphp
                            <video controls preload="metadata"
                                @if(!empty($video['thumbnail'])) poster="{{ $video['thumbnail'] }}" @endif
                                style="width:100%;height:100%;object-fit:contain;display:block;">
                                <source src="{{ $video['url'] }}" type="video/{{ $ext }}">
                            </video>
                        @elseif (in_array($video['provider'], ['youtube', 'vimeo']))
                            <div class="qs-video-facade"
                                 data-provider="{{ $video['provider'] }}"
                                 data-src="{{ $video['url'] }}{{ str_contains($video['url'], '?') ? '&' : '?' }}autoplay=1">
                                @if(!empty($video['thumbnail']))
                                    <img src="{{ $video['thumbnail'] }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;display:block;" />
                                @else
                                    <div style="width:100%;height:100%;background:#111;"></div>
                                @endif
                                <span class="qs-play-btn">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                </span>
                            </div>
                        @else
                            <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;background:#000;">
                                <iframe src="{{ $video['url'] }}" frameborder="0"
                                        allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture"
                                        allowfullscreen
                                        style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;"></iframe>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <button type="button" class="qs-arrow qs-arrow--next" aria-label="{{ __('Next') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
            <div class="qs-dots"></div>
            <button type="button" class="qs-video-zone qs-video-zone--prev" aria-label="{{ __('Previous') }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button type="button" class="qs-video-zone qs-video-zone--next" aria-label="{{ __('Next') }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
            @if ($hasVideo)
                <div class="qs-pill">
                    <button type="button" class="qs-pill__btn active" data-tab="images">{{ __('Images') }}</button>
                    <button type="button" class="qs-pill__btn" data-tab="videos">{{ __('Videos') }}</button>
                </div>
            @endif
        </div>
    </div>

    {{-- Thumbnail strip --}}
    <div class="qs-thumbs">
        @foreach ($galleryImages as $i => $img)
            <div class="qs-thumb {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}" data-tab="images">
                <img src="{{ RvMedia::getImageUrl($img, 'thumb') }}" alt="{{ $product->name }}" loading="lazy" />
            </div>
        @endforeach
        @foreach ($videoList as $i => $video)
            <div class="qs-thumb qs-thumb--video" data-index="{{ $i }}" data-tab="videos"
                 style="{{ !empty($video['thumbnail']) ? 'background-image:url(' . $video['thumbnail'] . ');background-size:cover;background-position:center;' : '' }}">
            </div>
        @endforeach
    </div>

    {{-- ── Product info ──────────────────────────────────────── --}}
    <div class="qs-info bb-product-detail">

        {{-- Title — h3 for SEO (h1 is the product page title) --}}
        <h3 class="qs-title">{!! BaseHelper::clean($product->name) !!}</h3>

        {{-- Price — uses default variation price on first load --}}
        {!! Theme::partial('product-price', [
            'product'           => $displayProduct,
            'priceWrapperTag'   => 'div',
            'priceWrapperClass' => 'qs-price',
        ]) !!}

        {{-- Variations --}}
        @if ($product->has_variation)
            <div class="pr_switch_wrap">
                {!! render_product_swatches($product, [
                    'selected'         => $selectedAttrs,
                    'view'             => Theme::getThemeNamespace() . '::views.ecommerce.attributes.swatches-renderer',
                    'referenceProduct' => $referenceProduct ?? null,
                ]) !!}
            </div>
            {{-- Hide the default number-items-available — we use .js-qs-stock instead --}}
            <div class="number-items-available" style="display:none !important;"></div>
        @endif

        @if ($product->options()->count() > 0 && isset($product->toArray()['options']))
            <div class="pr_switch_wrap" id="product-option">
                {!! render_product_options($product) !!}
            </div>
        @endif

        <form class="add-to-cart-form" method="POST" action="{{ route('public.cart.add-to-cart') }}" data-bb-toggle="product-form">
            @csrf
            <input type="hidden" name="id" class="hidden-product-id"
                   value="{{ $displayProduct->id }}" />

            {{-- Quantity + Stock --}}
            <div class="qs-qty-row">
                <span class="qs-qty-label">{{ __('Quantity') }}</span>
                <div class="qs-qty">
                    <button type="button" class="qs-qty__btn down" aria-label="{{ __('Decrease') }}">−</button>
                    <input type="number" name="qty" class="qs-qty__input qty-input" value="1" min="1" />
                    <button type="button" class="qs-qty__btn up" aria-label="{{ __('Increase') }}">+</button>
                </div>
                <span class="qs-stock js-qs-stock {{ $stockClass }}">{{ $stockText }}</span>
            </div>

            @if (EcommerceHelper::isCartEnabled())
                <div class="qs-actions">
                    <button class="qs-btn qs-btn--cart" type="submit" name="add-to-cart" @disabled($product->isOutOfStock())>
                        {{ __('Add to cart') }}
                    </button>
                    @if (EcommerceHelper::isQuickBuyButtonEnabled())
                        <button class="qs-btn qs-btn--buy" type="submit" name="checkout" @disabled($product->isOutOfStock())>
                            {{ __('Buy Now') }}
                        </button>
                    @endif
                </div>
            @endif
        </form>

        <a href="{{ $product->url }}" class="qs-view-link">{{ __('View full details') }}</a>

    </div>
</div>
