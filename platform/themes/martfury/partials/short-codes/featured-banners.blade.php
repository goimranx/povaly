<div class="ps-custom-banner-grid">
    <div class="ps-container">
        <div class="ps-banner-grid-wrapper">
            {{-- Top Row: Left and Right Top Images --}}
            <div class="ps-banner-top-row">
                {{-- Left Top Large Image with Button --}}
                @if($shortCode->left_top_image)
                    <div class="ps-banner-item ps-banner-large">
                        <a href="{{ $shortCode->left_top_link ?? '#' }}" class="ps-banner-link">
                            {!! RvMedia::image($shortCode->left_top_image, 'Left Top Banner') !!}
                            @if($shortCode->left_top_button_text)
                                <span class="ps-banner-button">
                                    <span class="ps-banner-button-text">{{ $shortCode->left_top_button_text }}</span>
                                    <i class="ps-banner-button-icon icon-arrow-right"></i>
                                </span>
                            @endif
                        </a>
                    </div>
                @endif

                {{-- Right Top Image with Button --}}
                @if($shortCode->right_top_image)
                    <div class="ps-banner-item ps-banner-medium">
                        <a href="{{ $shortCode->right_top_link ?? '#' }}" class="ps-banner-link">
                            {!! RvMedia::image($shortCode->right_top_image, 'Right Top Banner') !!}
                            @if($shortCode->right_top_button_text)
                                <span class="ps-banner-button">
                                    <span class="ps-banner-button-text">{{ $shortCode->right_top_button_text }}</span>
                                    <i class="ps-banner-button-icon icon-arrow-right"></i>
                                </span>
                            @endif
                        </a>
                    </div>
                @endif
            </div>

            {{-- Bottom Row: Left Bottom Section and Right Bottom Section --}}
            <div class="ps-banner-bottom-row">
                {{-- Left Bottom Section (Split 50/50) --}}
                <div class="ps-banner-bottom-split">
                    {{-- Left Box with 2 Images Side by Side --}}
                    <div class="ps-banner-box ps-banner-box-images">
                        @if($shortCode->left_bottom_left_title)
                            <div class="ps-banner-box-title">{{ $shortCode->left_bottom_left_title }}</div>
                        @endif
                        <div class="ps-banner-images-grid">
                            @if($shortCode->left_bottom_left_image1)
                                <div class="ps-banner-image-item">
                                    <a href="{{ $shortCode->left_bottom_left_image1_link ?? '#' }}">
                                        {!! RvMedia::image($shortCode->left_bottom_left_image1, 'Image 1') !!}
                                        @if($shortCode->left_bottom_left_image1_text)
                                            <span class="ps-banner-image-text">{{ $shortCode->left_bottom_left_image1_text }}</span>
                                        @endif
                                    </a>
                                </div>
                            @endif
                            @if($shortCode->left_bottom_left_image2)
                                <div class="ps-banner-image-item">
                                    <a href="{{ $shortCode->left_bottom_left_image2_link ?? '#' }}">
                                        {!! RvMedia::image($shortCode->left_bottom_left_image2, 'Image 2') !!}
                                        @if($shortCode->left_bottom_left_image2_text)
                                            <span class="ps-banner-image-text">{{ $shortCode->left_bottom_left_image2_text }}</span>
                                        @endif
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Right Box with Header and 4 Square Images --}}
                    <div class="ps-banner-box ps-banner-box-grid">
                        @if($shortCode->left_bottom_right_header_text)
                            <a href="{{ $shortCode->left_bottom_right_header_link ?? '#' }}" class="ps-banner-box-header">
                                <span>{{ $shortCode->left_bottom_right_header_text }}</span>
                                <i class="icon-chevron-right"></i>
                            </a>
                        @endif
                        <div class="ps-banner-grid-2x2">
                            @if($shortCode->left_bottom_right_image1)
                                <div class="ps-banner-grid-item">
                                    <a href="{{ $shortCode->left_bottom_right_image1_link ?? '#' }}">
                                        {!! RvMedia::image($shortCode->left_bottom_right_image1, 'Grid Image 1') !!}
                                    </a>
                                </div>
                            @endif
                            @if($shortCode->left_bottom_right_image2)
                                <div class="ps-banner-grid-item">
                                    <a href="{{ $shortCode->left_bottom_right_image2_link ?? '#' }}">
                                        {!! RvMedia::image($shortCode->left_bottom_right_image2, 'Grid Image 2') !!}
                                    </a>
                                </div>
                            @endif
                            @if($shortCode->left_bottom_right_image3)
                                <div class="ps-banner-grid-item">
                                    <a href="{{ $shortCode->left_bottom_right_image3_link ?? '#' }}">
                                        {!! RvMedia::image($shortCode->left_bottom_right_image3, 'Grid Image 3') !!}
                                    </a>
                                </div>
                            @endif
                            @if($shortCode->left_bottom_right_image4)
                                <div class="ps-banner-grid-item">
                                    <a href="{{ $shortCode->left_bottom_right_image4_link ?? '#' }}">
                                        {!! RvMedia::image($shortCode->left_bottom_right_image4, 'Grid Image 4') !!}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Bottom: 2 Images Stacked with Header --}}
                <div class="ps-banner-box ps-banner-stack">
                    @if($shortCode->right_bottom_header_text)
                        <a href="{{ $shortCode->right_bottom_header_link ?? '#' }}" class="ps-banner-box-header">
                            <span>{{ $shortCode->right_bottom_header_text }}</span>
                            <i class="icon-chevron-right"></i>
                        </a>
                    @endif
                    <div class="ps-banner-stack-images">
                        @if($shortCode->right_bottom_image1)
                            <div class="ps-banner-item ps-banner-small">
                                <a href="{{ $shortCode->right_bottom_image1_link ?? '#' }}">
                                    {!! RvMedia::image($shortCode->right_bottom_image1, 'Right Bottom 1') !!}
                                </a>
                            </div>
                        @endif
                        @if($shortCode->right_bottom_image2)
                            <div class="ps-banner-item ps-banner-small">
                                <a href="{{ $shortCode->right_bottom_image2_link ?? '#' }}">
                                    {!! RvMedia::image($shortCode->right_bottom_image2, 'Right Bottom 2') !!}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Container */
    .ps-custom-banner-grid {
        padding: 10px 0;
        background: var(--global-color-site-bg);
    }

    .ps-banner-grid-wrapper {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    /* Top Row - Contains both top banners */
    .ps-banner-top-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 10px;
    }
    
    /* Bottom Row - Contains left bottom split and right bottom stack */
    .ps-banner-bottom-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 10px;
    }

    /* Common Styles */
    .ps-banner-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        width: 100%;
    }

    .ps-banner-item img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        border-radius: 8px;
    }

    .ps-banner-link {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* Button Overlay */
    .ps-banner-button {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: var(--global-color-secondary-mid);
        color: var(--global-color-text-on-dark);
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .ps-banner-button-icon {
        display: none;
        font-size: 16px;
    }

    .ps-banner-link:hover .ps-banner-button {
        background: var(--global-color-secondary);
        color: var(--global-color-text-on-dark);
    }

    /* Left Section */
    .ps-banner-large {
        width: 100%;
        /* Canva Ratio: 21:9 or 2.33:1 */
        padding-bottom: 42.86%;
    }
    
    .ps-banner-medium {
        width: 100%;
        /* Canva Ratio: 4:3.42 or 1.17:1 */
        padding-bottom: 85.5%;
    }

    .ps-banner-bottom-split {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 10px;
    }

    /* Bordered Box */
    .ps-banner-box-grid {
        border: 1px solid var(--global-color-primary-border);
        border-radius: 8px;
        padding: 12px;
        background: var(--global-color-site-bg);
    }
    
    .ps-banner-box-images {
        display: flex;
        flex-direction: column;
    }
    
    .ps-banner-box-grid {
        display: flex;
        flex-direction: column;
    }

    .ps-banner-box-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--global-color-text-main);
        margin-bottom: 15px;
        padding: 0 5px;
    }

    /* Left Bottom Left - 2 Images Side by Side */
    .ps-banner-images-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        flex: 1;
    }

    .ps-banner-image-item {
        position: relative;
        overflow: hidden;
        border-radius: 6px;
        width: 100%;
        /* Canva Ratio: 3:3.42 (or 9:16) */
        padding-bottom: 114%;
    }

    .ps-banner-image-item a {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .ps-banner-image-item img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        border-radius: 6px;
    }

    .ps-banner-image-text {
        position: absolute;
        top: 10px;
        left: 10px;
        color: var(--global-color-text-main);
        padding: 5px;
        border-radius: 4px;
        font-size: 1.7rem;
        line-height:1.2;
        font-weight: 600;
        z-index: 10;
    }

    /* Left Bottom Right - Header + 2x2 Grid */
    .ps-banner-box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        margin: -15px -15px 0 -15px;
        border-radius: 8px 8px 0 0;
        text-decoration: none;
        transition: background 0.3s ease;
    }

    .ps-banner-box-header span {
        font-size:1.85rem;
        line-height:1.2;
        font-weight:600;
        color: var(--global-color-text-main);
    }

    .ps-banner-box-header i {
        font-size: 18px;
        font-weight:700;
        color: var(--global-color-text-body);
    }

    .ps-banner-grid-2x2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        flex: 1;
        align-content: end;
    }

    .ps-banner-grid-item {
        position: relative;
        overflow: hidden;
        border-radius: 6px;
        width: 100%;
        /* Canva Ratio: 1:1 (square) */
        padding-bottom: 100%;
    }

    .ps-banner-grid-item a {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .ps-banner-grid-item img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        border-radius: 6px;
    }

    /* Right Section */
    .ps-banner-stack {
        border: 1px solid var(--global-color-primary-border);
        border-radius: 8px;
        padding: 12px;
        background: var(--global-color-site-bg);
        display: flex;
        flex-direction: column;
    }
    
    .ps-banner-stack-images {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        flex: 1;
    }

    .ps-banner-small {
        position: relative;
        width: 100%;
        /* Canva Ratio: 3:3.9 (or 9/16) */
        padding-bottom: 130%;
    }
    
    .ps-banner-small a {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    .ps-banner-small img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        border-radius: 8px;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .ps-banner-top-row,
        .ps-banner-bottom-row {
            grid-template-columns: 2fr 1fr;
        }
        .ps-banner-image-text, .ps-banner-box-header span{
            font-size:1.35rem;
        }
    }

    @media (max-width: 768px) {

        .ps-banner-box-grid {
            padding:0;
            border:none;
        }
        .ps-banner-bottom-row {
            grid-template-columns:1fr;
            gap: 8px;
        }

        .ps-banner-top-row {
            grid-template-columns:2fr 1fr;
            gap: 8px;
        }

        .ps-banner-bottom-split {
            grid-template-columns: 2fr 1fr;
            gap: 8px;
        }

        .ps-banner-stack-images {
            gap:8px;
        }
        
        .ps-banner-button {
            padding: 6px 10px;
            font-size: 12px;
            bottom: 10px;
            right: 10px;
            border-radius: 3px;
            justify-content: center;
        }
        
        .ps-banner-button-text {
            display: none;
        }
        
        .ps-banner-button-icon {
            display: block !important;
        }

        .ps-custom-banner-grid {
            padding: 0;
        }
    }

    @media (max-width: 480px) {
        .ps-banner-bottom-split {
            grid-template-columns: 1fr;
        }

        .ps-banner-grid-2x2 {
            gap: 8px;
        }

        .ps-banner-button {
            bottom:7px;
            right:7px;
            padding: 4px 8px;
            font-size: 14px;
        }

        .ps-banner-stack{
            padding:0;
            border: none !important;
        }

        .ps-banner-box-header{
            padding:20px 0 10px 0;
            margin:0;
        }
        .ps-banner-image-text, .ps-banner-box-header span{
            font-size:1.35rem;

        }
    }
</style>
