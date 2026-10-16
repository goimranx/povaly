<div class="ps-promotional-hero-grid">
    <div class="ps-container">
        <div class="ps-hero-grid-wrapper">
            {{-- Left Column (2fr) - Large Banner --}}
            <div class="ps-hero-left">
                @if($shortCode->left_image)
                    <a href="{{ $shortCode->left_link ?? '#' }}" class="ps-hero-banner ps-hero-banner-large">
                        {!! RvMedia::image($shortCode->left_image, 'Hero Banner') !!}
                        <div class="ps-hero-content ps-hero-content-topleft">
                            @if($shortCode->left_subtitle)
                                <div class="ps-hero-subtitle">{{ $shortCode->left_subtitle }}</div>
                            @endif
                            @if($shortCode->left_title)
                                <h2 class="ps-hero-title">{{ $shortCode->left_title }}</h2>
                            @endif
                            @if($shortCode->left_button_text)
                                <span class="ps-hero-button ps-hero-button-filled">{{ $shortCode->left_button_text }}</span>
                            @endif
                        </div>
                    </a>
                @endif
            </div>

            {{-- Middle Column (2fr) - Top Vertical + Bottom Split --}}
            <div class="ps-hero-middle">
                {{-- Top Vertical Banner --}}
                @if($shortCode->right_top_image)
                    <a href="{{ $shortCode->right_top_link ?? '#' }}" class="ps-hero-banner ps-hero-banner-vertical">
                        {!! RvMedia::image($shortCode->right_top_image, 'Vertical Banner') !!}
                        <div class="ps-hero-content ps-hero-content-topleft ps-hero-content-small">
                            @if($shortCode->right_top_subtitle)
                                <div class="ps-hero-subtitle">{{ $shortCode->right_top_subtitle }}</div>
                            @endif
                            @if($shortCode->right_top_title)
                                <h3 class="ps-hero-title">{{ $shortCode->right_top_title }}</h3>
                            @endif
                            @if($shortCode->right_top_button_text)
                                <span class="ps-hero-button ps-hero-button-text">{{ $shortCode->right_top_button_text }}</span>
                            @endif
                        </div>
                    </a>
                @endif

                {{-- Bottom Split (1fr + 1fr) --}}
                <div class="ps-hero-split">
                    @if($shortCode->right_middle_left_image)
                        <a href="{{ $shortCode->right_middle_left_link ?? '#' }}" class="ps-hero-banner ps-hero-banner-small">
                            {!! RvMedia::image($shortCode->right_middle_left_image, 'Small Banner') !!}
                            <div class="ps-hero-content ps-hero-content-topleft ps-hero-content-small ps-hero-content-reverse">
                                @if($shortCode->right_middle_left_title)
                                    <h4 class="ps-hero-title">{{ $shortCode->right_middle_left_title }}</h4>
                                @endif
                                @if($shortCode->right_middle_left_subtitle)
                                    <div class="ps-hero-subtitle">{{ $shortCode->right_middle_left_subtitle }}</div>
                                @endif
                                @if($shortCode->right_middle_left_button_text)
                                    <span class="ps-hero-button ps-hero-button-text">{{ $shortCode->right_middle_left_button_text }}</span>
                                @endif
                            </div>
                        </a>
                    @endif

                    @if($shortCode->right_middle_right_image)
                        <a href="{{ $shortCode->right_middle_right_link ?? '#' }}" class="ps-hero-banner ps-hero-banner-small">
                            {!! RvMedia::image($shortCode->right_middle_right_image, 'Small Banner') !!}
                            <div class="ps-hero-content ps-hero-content-topleft ps-hero-content-small ps-hero-content-reverse">
                                @if($shortCode->right_middle_right_title)
                                    <h4 class="ps-hero-title">{{ $shortCode->right_middle_right_title }}</h4>
                                @endif
                                @if($shortCode->right_middle_right_subtitle)
                                    <div class="ps-hero-subtitle">{{ $shortCode->right_middle_right_subtitle }}</div>
                                @endif
                                @if($shortCode->right_middle_right_button_text)
                                    <span class="ps-hero-button ps-hero-button-text">{{ $shortCode->right_middle_right_button_text }}</span>
                                @endif
                            </div>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Right Column (1fr) - Single Large Banner --}}
            <div class="ps-hero-right">
                @if($shortCode->right_bottom_image)
                    <a href="{{ $shortCode->right_bottom_link ?? '#' }}" class="ps-hero-banner ps-hero-banner-tall">
                        {!! RvMedia::image($shortCode->right_bottom_image, 'Tall Banner') !!}
                        <div class="ps-hero-content ps-hero-content-topleft ps-hero-content-small">
                            @if($shortCode->right_bottom_subtitle)
                                <div class="ps-hero-subtitle">{{ $shortCode->right_bottom_subtitle }}</div>
                            @endif
                            @if($shortCode->right_bottom_title)
                                <h3 class="ps-hero-title">{{ $shortCode->right_bottom_title }}</h3>
                            @endif
                            @if($shortCode->right_bottom_button_text)
                                <span class="ps-hero-button ps-hero-button-text">{{ $shortCode->right_bottom_button_text }}</span>
                            @endif
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Container */
    .ps-promotional-hero-grid {
        padding: 10px 0;
        background: var(--global-color-site-bg);
        overflow: hidden;
    }

    .ps-hero-grid-wrapper {
        display: grid;
        grid-template-columns: 2.2fr 1.8fr 1.2fr;
        gap: 7px;
        width: 100%;
        max-width: 100%;
    }

    /* Common Banner Styles */
    .ps-hero-banner {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        display: block;
        width: 100%;
    }

    .ps-hero-banner img {
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

    /* Content Overlay */
    .ps-hero-content {
        position: absolute;
        z-index: 10;
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .ps-hero-content-topleft {
        top: 15px;
        left: 15px;
        max-width: 70%;
    }

    .ps-hero-content-small {
        top: 10px;
        left: 10px;
        gap: 1px;
    }

    .ps-hero-content-reverse {
        flex-direction: column;
    }

    /* Typography */
    .ps-hero-subtitle {
        font-size: 16px;
        font-weight: 400;
        color: var(--global-color-text-on-dark);
        text-transform: capitalize;
        letter-spacing: 0;
        line-height: 1.4;
    }

    .ps-hero-content-small .ps-hero-subtitle {
        font-size: 14px;
        color: var(--global-color-text-main);
    }

    .ps-hero-title {
        font-size: 48px;
        font-weight: 700;
        color: var(--global-color-text-on-dark);
        line-height: 1.1;
        margin: 0;
        text-transform: capitalize;
    }

    .ps-hero-content-small .ps-hero-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--global-color-text-main);
        line-height: 1.2;
    }

    /* Buttons */
    .ps-hero-button {
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .ps-hero-button-filled {
        background: var(--global-color-site-bg);
        color: var(--global-color-text-main);
        padding: 4px 10px;
        margin-top:15px;
        border-radius: 50px;
        width:fit-content;
        border: 2px solid transparent;
    }

    .ps-hero-button-filled:hover {
        border-color: var(--global-color-text-main);
    }

    .ps-hero-button-text {
        color: var(--global-color-text-main);
        text-decoration: underline;
        text-underline-offset: 3px;
        font-size: 14px;
    }

    .ps-hero-button-text:hover {
        color: var(--global-color-primary);
    }

    .ps-hero-content-small .ps-hero-button {
        font-size: 13px;
        padding: 0;
        margin-top: 10px;
    }

    .ps-hero-content-small .ps-hero-button-filled {
        padding: 4px 10px;
        font-size: 13px;
    }

    .ps-hero-content-small .ps-hero-button-text:hover {
        color: var(--global-color-text-main);
        text-decoration: none;
    }

    /* Left Column - Large Banner */
    .ps-hero-left {
        width: 100%;
    }
    
    .ps-hero-banner-large {
        width: 100%;
        /* Canva Ratio: 2.2:1.8 (or 11:9) */
        padding-bottom: 81.82%;
    }

    /* Middle Column - Flexible container */
    .ps-hero-middle {
        display: flex;
        flex-direction: column;
        gap: 7px;
        width: 100%;
    }

    .ps-hero-banner-vertical {
        width: 100%;
        /* Canva Ratio: 1.8:1.08 (or 5:3) */
        padding-bottom: 60%;
    }

    .ps-hero-split {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 7px;
    }

    .ps-hero-banner-small {
        width: 100%;
        /* Canva Ratio: 0.9:0.702 (or 5:4) */
        padding-bottom: 78%;
    }

    /* Right Column - Tall Banner */
    .ps-hero-right {
        width: 100%;
    }
    
    .ps-hero-banner-tall {
        width: 100%;
        /* Canva Ratio: 1.2:1.8 (or 2:3) */
        padding-bottom: 150%;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .ps-hero-title {
            font-size: 36px;
        }

        .ps-hero-content-small .ps-hero-title {
            font-size: 18px;
        }
        
        .ps-hero-subtitle {
            font-size: 14px;
        }

        .ps-hero-content-small .ps-hero-subtitle {
            font-size: 13px;
        }

        .ps-hero-content-topleft {
            top: 15px;
            left: 15px;
        }

        .ps-hero-content-small {
            top: 10px;
            left: 10px;
        }

        .ps-hero-button-filled {
            padding: 4px 10px;
        }

        .ps-hero-content-small .ps-hero-button-filled {
            padding: 4px 10px;
        }
    }

    @media (max-width: 768px) {
        .ps-hero-grid-wrapper {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-snap-type: x mandatory;
            gap: 10px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 5px;
        }
        
        .ps-hero-grid-wrapper::-webkit-scrollbar {
            display: none;
        }

        .ps-hero-left,
        .ps-hero-middle,
        .ps-hero-right {
            flex: 0 0 85%;
            min-width: 85%;
            scroll-snap-align: start;
        }
        
        /* All sections have same width (85%), so use same base aspect for equal heights */
        
        .ps-hero-banner-large {
            /* Canva Ratio (Mobile): 1.22:1 (or 6:5) */
            padding-bottom: 81.97%;
        }
        
        .ps-hero-banner-tall {
            /* Canva Ratio (Mobile): 1.22:1 (or 6:5) */
            padding-bottom: 81.97%;
        }

        /* Middle column - combined height should equal left/right */
        .ps-hero-middle {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .ps-hero-banner-vertical {
            /* Canva Ratio (Mobile): 1.22:0.585 (or 1200x585px) */
            padding-bottom: 47.95%;
        }

        .ps-hero-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .ps-hero-banner-small {
            /* Canva Ratio (Mobile): 1.22:0.8 (or 1200x800px) */
            padding-bottom: 65.57%;
        }

        .ps-hero-title {
            font-size: 28px;
        }

        .ps-hero-content-small .ps-hero-title {
            font-size: 16px;
        }
        
        .ps-hero-subtitle {
            font-size: 13px;
        }

        .ps-hero-content-small .ps-hero-subtitle {
            font-size: 12px;
        }

        .ps-hero-button-filled {
            padding: 4px 10px;
            font-size: 13px;
        }

        .ps-hero-content-small .ps-hero-button-filled {
            padding: 4px 10px;
            font-size: 12px;
        }

        .ps-hero-button-text {
            font-size: 13px;
        }
        
        .ps-hero-content-topleft {
            top: 10px;
            left: 10px;
        }

        .ps-hero-content-small {
            top: 10px;
            left: 10px;
        }
    }

    @media (max-width: 480px) {
        .ps-hero-left,
        .ps-hero-middle,
        .ps-hero-right {
            flex: 0 0 90%;
            min-width: 90%;
        }

        .ps-hero-title {
            font-size: 22px;
        }

        .ps-hero-content-small .ps-hero-title {
            font-size: 14px;
        }

        .ps-hero-subtitle {
            font-size: 12px;
        }

        .ps-hero-content-small .ps-hero-subtitle {
            font-size: 11px;
        }

        .ps-hero-button-filled {
            padding: 4px 10px;
            font-size: 11px;
        }
        
        .ps-hero-button-text {
            font-size: 11px;
        }

        .ps-hero-content-small .ps-hero-button-filled {
            padding: 4px 10px;
            font-size: 11px;
        }

        .ps-hero-content-topleft {
            top: 10px;
            left: 10px;
            max-width: 80%;
        }

        .ps-hero-button-filled {
            padding: 3px 10px;
            font-size: 11px;
        }
        
        .ps-hero-button-text {
            font-size: 11px;
        }
    }
</style>
