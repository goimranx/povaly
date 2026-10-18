@php
    $hasVideo = !empty($product->video) && is_array($product->video) && count(array_filter($product->video, fn($v) => !empty($v['url']))) > 0;
    $videoList = $hasVideo ? array_values(array_filter($product->video, fn($v) => !empty($v['url']))) : [];
    
    // Ensure we have images - fall back to product images if variation images empty
    $galleryImages = !empty($productImages) ? $productImages : ($product->images ?? []);
@endphp

<div class="qs-product">

    {{-- ── Gallery ───────────────────────────────────────────── --}}
    <div class="qs-gallery" data-has-video="{{ $hasVideo ? 'true' : 'false' }}">

        {{-- Viewport --}}
        <div class="qs-gallery__viewport">

            {{-- Prev arrow (hidden via CSS, kept for JS compatibility) --}}
            <button type="button" class="qs-arrow qs-arrow--prev" aria-label="{{ __('Previous') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>

            {{-- Track --}}
            <div class="qs-track">
                @foreach ($galleryImages as $img)
                    <div class="qs-slide qs-slide--image">
                        <img src="{{ RvMedia::getImageUrl($img) }}" alt="{{ $product->name }}" loading="lazy" />
                    </div>
                @endforeach

                @foreach ($videoList as $video)
                    <div class="qs-slide qs-slide--video" style="display:none;">
                        @if ($video['provider'] === 'video')
                            {{-- Direct uploaded video --}}
                            @php $ext = pathinfo($video['url'], PATHINFO_EXTENSION) ?: 'mp4'; if($ext === 'mov') $ext = 'mp4'; @endphp
                            <video controls preload="metadata"
                                @if(!empty($video['thumbnail'])) poster="{{ $video['thumbnail'] }}" @endif
                                style="width:100%;height:100%;object-fit:contain;display:block;">
                                <source src="{{ $video['url'] }}" type="video/{{ $ext }}">
                            </video>

                        @elseif (in_array($video['provider'], ['youtube', 'vimeo']))
                            {{-- YouTube / Vimeo facade (click to load iframe) --}}
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
                            {{-- Generic iframe (TikTok, Twitter, other) --}}
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

            {{-- Next arrow (hidden via CSS, kept for JS compatibility) --}}
            <button type="button" class="qs-arrow qs-arrow--next" aria-label="{{ __('Next') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>

            {{-- Dot indicators --}}
            <div class="qs-dots"></div>

            {{-- Video click zones (prev/next) — visible only in video tab --}}
            <button type="button" class="qs-video-zone qs-video-zone--prev" aria-label="{{ __('Previous') }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button type="button" class="qs-video-zone qs-video-zone--next" aria-label="{{ __('Next') }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>

            {{-- Images / Videos pill overlaid on gallery --}}
            @if ($hasVideo)
                <div class="qs-pill">
                    <button type="button" class="qs-pill__btn active" data-tab="images">{{ __('Images') }}</button>
                    <button type="button" class="qs-pill__btn" data-tab="videos">{{ __('Videos') }}</button>
                </div>
            @endif
        </div>

    </div>

    {{-- ── Product info ──────────────────────────────────────── --}}
    <div class="qs-info">

        <h2 class="qs-title">{!! BaseHelper::clean($product->name) !!}</h2>

        {!! Theme::partial('product-price', [
            'product'           => $product,
            'priceWrapperTag'   => 'div',
            'priceWrapperClass' => 'qs-price',
        ]) !!}

        @if ($product->has_variation)
            <div class="pr_switch_wrap">
                {!! render_product_swatches($product, [
                    'selected'         => $selectedAttrs,
                    'view'             => Theme::getThemeNamespace() . '::views.ecommerce.attributes.swatches-renderer',
                    'referenceProduct' => $referenceProduct ?? null,
                ]) !!}
            </div>
            <div class="number-items-available" style="display:none;margin-bottom:8px;"></div>
        @endif

        @if ($product->options()->count() > 0 && isset($product->toArray()['options']))
            <div class="pr_switch_wrap" id="product-option">
                {!! render_product_options($product) !!}
            </div>
        @endif

        <form class="add-to-cart-form" method="POST" action="{{ route('public.cart.add-to-cart') }}" data-bb-toggle="product-form">
            @csrf
            <input type="hidden" name="id" class="hidden-product-id"
                   value="{{ ($product->is_variation || !$product->defaultVariation->product_id) ? $product->id : $product->defaultVariation->product_id }}" />

            {{-- Quantity + Stock --}}
            <div class="qs-qty-row">
                <span class="qs-qty-label">{{ __('Quantity') }}</span>
                <div class="qs-qty">
                    <button type="button" class="qs-qty__btn down" aria-label="{{ __('Decrease') }}">−</button>
                    <input type="number" name="qty" class="qs-qty__input qty-input" value="1" min="1" />
                    <button type="button" class="qs-qty__btn up" aria-label="{{ __('Increase') }}">+</button>
                </div>
                @if (!$product->isOutOfStock() && $product->with_storehouse_management && $product->quantity > 0)
                    <span class="qs-stock">{{ __('Only :qty left', ['qty' => $product->quantity]) }}</span>
                @elseif ($product->isOutOfStock())
                    <span class="qs-stock qs-stock--out">{{ __('Out of stock') }}</span>
                @endif
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
