@php
    $hasVideo  = !empty($product->video) && is_array($product->video)
                 && count(array_filter($product->video, fn($v) => !empty($v['url']))) > 0;
    $videoList = $hasVideo ? array_values(array_filter($product->video, fn($v) => !empty($v['url']))) : [];

    // ── Build the unified image list ──────────────────────────────────
    // Collect ALL images: product gallery + all variation images, deduplicated
    $allImages = collect();

    // 1. Product gallery images
    $galleryBase = !empty($product->images) ? $product->images : [];
    foreach ($galleryBase as $img) {
        if ($img) $allImages->push(['src' => $img, 'type' => 'gallery', 'variationId' => null]);
    }

    // 2. Featured image (if not already in gallery)
    if (!empty($product->image) && !$allImages->contains('src', $product->image)) {
        $allImages->prepend(['src' => $product->image, 'type' => 'featured', 'variationId' => null]);
    }

    // 3. All variation images (featured + gallery for each variation), deduplicated
    $variationImageMap = []; // product_id → first index in $allImages
    if ($product->has_variation && isset($productVariations) && $productVariations) {
        foreach ($productVariations as $pv) {
            if (!$pv->product) continue;
            $varImages = [];
            // Featured image first
            if (!empty($pv->product->image)) $varImages[] = $pv->product->image;
            // Gallery images
            if (!empty($pv->product->images) && is_array($pv->product->images)) {
                foreach ($pv->product->images as $vi) {
                    if ($vi && !in_array($vi, $varImages)) $varImages[] = $vi;
                }
            }
            foreach ($varImages as $img) {
                if (!$allImages->contains('src', $img)) {
                    $idx = $allImages->count();
                    $allImages->push(['src' => $img, 'type' => 'variation', 'variationId' => $pv->product_id]);
                    if (!isset($variationImageMap[$pv->product_id])) {
                        $variationImageMap[$pv->product_id] = $idx;
                    }
                } elseif (!isset($variationImageMap[$pv->product_id])) {
                    $variationImageMap[$pv->product_id] = $allImages->search(fn($i) => $i['src'] === $img);
                }
            }
        }
    }

    // 4. Fallback placeholder if nothing
    if ($allImages->isEmpty()) {
        $allImages->push(['src' => RvMedia::getDefaultImage(), 'type' => 'placeholder', 'variationId' => null]);
    }

    // ── Determine the default jump index ────────────────────────────
    $defaultJumpIndex = 0;
    $displayProduct   = $product;

    if ($product->has_variation && $productVariation && $productVariation->id !== $product->id) {
        $displayProduct = $productVariation;

        // Strategy 1: use variationImageMap by product_id
        if (isset($variationImageMap[$productVariation->id])) {
            $defaultJumpIndex = $variationImageMap[$productVariation->id];
        }
        // Strategy 2: match by image src regardless of type
        elseif (!empty($productVariation->image)) {
            $found = $allImages->search(fn($i) => $i['src'] === $productVariation->image);
            if ($found !== false) $defaultJumpIndex = $found;
        }
        // Strategy 3: use $productImages (controller-provided variation images)
        elseif (!empty($productImages)) {
            foreach ($productImages as $img) {
                $found = $allImages->search(fn($i) => $i['src'] === $img);
                if ($found !== false) { $defaultJumpIndex = $found; break; }
            }
        }
    } elseif (!empty($productImages) && $productImages !== ($product->images ?? [])) {
        // Product has no variation but controller passed specific images — find first
        foreach ($productImages as $img) {
            $found = $allImages->search(fn($i) => $i['src'] === $img);
            if ($found !== false) { $defaultJumpIndex = $found; break; }
        }
    }

    // Stock text
    $stockText = ''; $stockClass = '';
    if ($displayProduct->isOutOfStock()) {
        $stockText = __('Stock not available'); $stockClass = 'qs-stock--out';
    } elseif ($displayProduct->with_storehouse_management && $displayProduct->quantity > 0) {
        $stockText = __('Out of :qty units', ['qty' => number_format($displayProduct->quantity)]);
    } elseif (!$displayProduct->with_storehouse_management) {
        $stockText = __('Out of available stocks');
    }

    $showPill = $hasVideo;
    $totalImages = $allImages->count();
@endphp

<div class="qs-product">

    {{-- ── Gallery ─────────────────────────────────────────── --}}
    <div class="qs-gallery" data-has-video="{{ $hasVideo ? 'true' : 'false' }}" data-jump="{{ $defaultJumpIndex }}">

        {{-- Mobile header --}}
        <div class="qs-mobile-header">
            <button type="button" class="qs-mobile-header__btn qs-mobile-close" data-bs-dismiss="offcanvas" aria-label="{{ __('Close') }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            @if ($showPill)
                <div class="qs-pill qs-pill--mobile-top">
                    <button type="button" class="qs-pill__btn active" data-tab="images">{{ __('Images') }}</button>
                    <button type="button" class="qs-pill__btn" data-tab="videos">{{ __('Videos') }}</button>
                </div>
            @endif
            <button type="button" class="qs-mobile-header__btn qs-share-btn" aria-label="{{ __('Share') }}" data-url="{{ $product->url }}" data-title="{{ $product->name }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </button>
        </div>

        <div class="qs-gallery__viewport">
            <button type="button" class="qs-arrow qs-arrow--prev" aria-label="{{ __('Previous') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>

            <div class="qs-track">
                {{-- ALL images in one track --}}
                @foreach ($allImages as $i => $imgData)
                    <div class="qs-slide qs-slide--image" data-slide-index="{{ $i }}" data-src="{{ $imgData['src'] }}">
                        <img src="{{ RvMedia::getImageUrl($imgData['src']) }}" alt="{{ $product->name }}" loading="lazy" />
                    </div>
                @endforeach

                {{-- Videos after all images --}}
                @foreach ($videoList as $video)
                    <div class="qs-slide qs-slide--video">
                        @if ($video['provider'] === 'video')
                            @php $ext = pathinfo($video['url'], PATHINFO_EXTENSION) ?: 'mp4'; if($ext === 'mov') $ext = 'mp4'; @endphp
                            <video controls preload="metadata" @if(!empty($video['thumbnail'])) poster="{{ $video['thumbnail'] }}" @endif style="width:100%;height:100%;object-fit:contain;display:block;">
                                <source src="{{ $video['url'] }}" type="video/{{ $ext }}">
                            </video>
                        @elseif (in_array($video['provider'], ['youtube', 'vimeo']))
                            <div class="qs-video-facade" data-provider="{{ $video['provider'] }}" data-src="{{ $video['url'] }}{{ str_contains($video['url'], '?') ? '&' : '?' }}autoplay=1">
                                @if(!empty($video['thumbnail']))
                                    <img src="{{ $video['thumbnail'] }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;display:block;" />
                                @else
                                    <div style="width:100%;height:100%;background:#111;"></div>
                                @endif
                                <span class="qs-play-btn"><svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg></span>
                            </div>
                        @else
                            <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;background:#000;">
                                <iframe src="{{ $video['url'] }}" frameborder="0" allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;"></iframe>
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
            @if ($showPill)
                <div class="qs-pill qs-pill--desktop">
                    <button type="button" class="qs-pill__btn active" data-tab="images">{{ __('Images') }}</button>
                    <button type="button" class="qs-pill__btn" data-tab="videos">{{ __('Videos') }}</button>
                </div>
            @endif
        </div>

        {{-- Thumbnail strip — ALL images + divider + video thumbs --}}
        <div class="qs-thumbs">
            @foreach ($allImages as $i => $imgData)
                <div class="qs-thumb qs-thumb--image {{ $i === $defaultJumpIndex ? 'active' : '' }}"
                     data-slide-index="{{ $i }}" data-src="{{ $imgData['src'] }}"
                     data-index="{{ $i }}" data-tab="images">
                    <img src="{{ RvMedia::getImageUrl($imgData['src'], 'thumb') }}" alt="{{ $product->name }}" loading="lazy" />
                </div>
            @endforeach

            @if ($hasVideo)
                <div class="qs-thumbs-divider"></div>
                @foreach ($videoList as $i => $video)
                    <div class="qs-thumb qs-thumb--video" data-index="{{ $i }}" data-tab="videos"
                         style="{{ !empty($video['thumbnail']) ? 'background-image:url(' . $video['thumbnail'] . ');background-size:cover;background-position:center;' : '' }}">
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- ── Product info ──────────────────────────────────────── --}}
    <div class="qs-info bb-product-detail">
        {{-- "View full details" above title, aligned right --}}
        <div class="qs-above-title">
            <a href="{{ $product->url }}" class="qs-view-link">{{ __('View full details') }}</a>
        </div>

        <div class="qs-title-wrap">
            <h3 class="qs-title qs-title--clamped">{!! BaseHelper::clean($product->name) !!}</h3>
            <button type="button" class="qs-title-expand" aria-label="{{ __('Expand title') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
        </div>

        {!! Theme::partial('product-price', ['product' => $displayProduct, 'priceWrapperTag' => 'div', 'priceWrapperClass' => 'qs-price']) !!}

        @if ($product->has_variation)
            <div class="pr_switch_wrap">
                {!! render_product_swatches($product, [
                    'selected'         => $selectedAttrs,
                    'view'             => Theme::getThemeNamespace() . '::views.ecommerce.attributes.swatches-renderer',
                    'referenceProduct' => $referenceProduct ?? null,
                ]) !!}
            </div>
            <div class="number-items-available" style="display:none !important;"></div>
        @endif

        @if ($product->options()->count() > 0 && isset($product->toArray()['options']))
            <div class="pr_switch_wrap" id="product-option">{!! render_product_options($product) !!}</div>
        @endif

        <form class="add-to-cart-form" method="POST" action="{{ route('public.cart.add-to-cart') }}" data-bb-toggle="product-form">
            @csrf
            <input type="hidden" name="id" class="hidden-product-id" value="{{ $displayProduct->id }}" />
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
                    <button class="qs-btn qs-btn--cart" type="submit" name="add-to-cart" @disabled($product->isOutOfStock())>{{ __('Add to cart') }}</button>
                    @if (EcommerceHelper::isQuickBuyButtonEnabled())
                        <button class="qs-btn qs-btn--buy" type="submit" name="checkout" @disabled($product->isOutOfStock())>{{ __('Buy Now') }}</button>
                    @endif
                </div>
            @endif
        </form>
    </div>
</div>
