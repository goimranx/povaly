<div class="qs-product">
    {{-- Image Gallery with arrows + Video toggle --}}
    @php
        $hasVideo = !empty($product->video) && is_array($product->video);
    @endphp
    <div class="qs-gallery">
        <div class="qs-gallery__viewport">
            <button type="button" class="qs-gallery__arrow qs-gallery__arrow--prev" aria-label="Previous image">
                <i class="icon-chevron-left"></i>
            </button>
            <div class="qs-gallery__track">
                @foreach ($productImages as $img)
                    <div class="qs-gallery__slide qs-slide--image">
                        <img src="{{ RvMedia::getImageUrl($img) }}" alt="{{ $product->name }}" />
                    </div>
                @endforeach
                @if ($hasVideo)
                    @foreach ($product->video as $video)
                        @if (!empty($video['url']))
                            <div class="qs-gallery__slide qs-slide--video">
                                @if ($video['provider'] === 'video')
                                    <video controls preload="metadata" poster="{{ $video['thumbnail'] ?? '' }}" style="width:100%;height:100%;object-fit:contain;">
                                        <source src="{{ $video['url'] }}" type="video/mp4">
                                    </video>
                                @else
                                    <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;background:#000;border-radius:8px;">
                                        <button type="button" class="bb-product-video-facade" data-provider="{{ $video['provider'] }}" data-src="{{ $video['url'] }}{{ Str::contains($video['url'], '?') ? '&' : '?' }}autoplay=1" data-title="{{ $product->name }} Video" aria-label="Play video" style="position:absolute;top:0;left:0;width:100%;height:100%;padding:0;border:0;cursor:pointer;background:#000;">
                                            <img src="{{ $video['thumbnail'] ?? '' }}" alt="{{ $product->name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;" />
                                            <span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:60px;height:60px;background:rgba(255,255,255,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(0,0,0,.3);">
                                                <i class="icon-control-play" style="font-size:24px;margin-left:4px;"></i>
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <button type="button" class="qs-gallery__arrow qs-gallery__arrow--next" aria-label="Next image">
                <i class="icon-chevron-right"></i>
            </button>
        </div>
        @if ($hasVideo)
            <div class="qs-gallery__pill">
                <button type="button" class="qs-gallery__pill-btn active" data-tab="images">{{ __('Images') }}</button>
                <button type="button" class="qs-gallery__pill-btn" data-tab="videos">{{ __('Videos') }}</button>
            </div>
        @endif
    </div>

    {{-- Product Info --}}
    <div class="qs-info">
        <h1 class="qs-title">{!! BaseHelper::clean($product->name) !!}</h1>
        {!! Theme::partial('product-price', [
            'product' => $product,
            'priceWrapperTag' => 'h4',
            'priceWrapperClass' => 'ps-product__price',
        ]) !!}

        @if ($product->has_variation)
            <div class="pr_switch_wrap">
                {!! render_product_swatches($product, [
                    'selected' => $selectedAttrs,
                    'view'     => Theme::getThemeNamespace() . '::views.ecommerce.attributes.swatches-renderer',
                    'referenceProduct' => $referenceProduct ?? null,
                ]) !!}
            </div>
            <div class="number-items-available" style="display: none; margin-bottom: 10px;"></div>
        @endif

        @if ($product->options()->count() > 0 && isset($product->toArray()['options']))
            <div class="pr_switch_wrap" id="product-option">
                {!! render_product_options($product) !!}
            </div>
        @endif

        <form class="add-to-cart-form" method="POST" action="{{ route('public.cart.add-to-cart') }}" data-bb-toggle="product-form">
            @csrf
            <div class="ps-product__shopping d-block">
                <input type="hidden" name="id" class="hidden-product-id" value="{{ ($product->is_variation || !$product->defaultVariation->product_id) ? $product->id : $product->defaultVariation->product_id }}"/>
                <figure>
                    <figcaption>{{ __('Quantity') }}</figcaption>
                    <div class="form-group--number product__qty">
                        <button class="up" type="button" aria-label="{{ __('Increase quantity') }}"><i class="icon-plus" aria-hidden="true"></i></button>
                        <button class="down" type="button" aria-label="{{ __('Decrease quantity') }}"><i class="icon-minus" aria-hidden="true"></i></button>
                        <input class="form-control qty-input" type="number" name="qty" value="1" placeholder="1" min="1">
                    </div>
                </figure>
                @if (EcommerceHelper::isCartEnabled())
                    <div class="mt-4 ps-product__actions">
                        <button class="ps-btn ps-btn--black" type="submit" name="add-to-cart" @disabled($product->isOutOfStock())>{{ __('Add to cart') }}</button>
                        @if (EcommerceHelper::isQuickBuyButtonEnabled())
                            <button class="ps-btn" type="submit" name="checkout" @disabled($product->isOutOfStock())>{{ __('Buy Now') }}</button>
                        @endif
                    </div>
                @endif
            </div>
        </form>

        <div class="ps-product__specification">
            <a href="{{ $product->url }}" class="report">{{ __('View full details') }}</a>
        </div>
    </div>
</div>
