{{-- Product Lightbox Styles - Standalone Component --}}
<style>
/*            PRODUCT LIGHTBOX MODAL                               */
        /* ═══════════════════════════════════════════════════════════════ */
        
        .product-lightbox-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(13, 27, 42, 0.4); /* Opacity 0.4 */
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        
        .product-lightbox-container {
            width: 95%;
            height: 85vh;
            max-width: 1600px;
            background: var(--global-color-site-bg, #F4F9FD);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            padding: 20px;
            position: relative; /* For absolute positioning of close button */
        }
        
        /* Close button - positioned at container level, not inside header */
        .product-lightbox-close {
            background: none;
            border: none;
            color: var(--global-color-text-body, #2E3D52);
            cursor: pointer;
            padding: 0;
            line-height: 1;
            position: absolute;
            top: 4px;
            right: 4px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
            z-index: 10;
        }
        
        .product-lightbox-close i,
        .product-lightbox-close .icon-cross {
            font-size: 1.25rem;
            font-weight: 700;
        }
        
        .product-lightbox-close:hover {
            color: var(--global-color-link-hover, #1dbff5);
        }
        
        /* Header */
        .product-lightbox-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: relative;
            border-bottom: 2px solid var(--global-color-primary-border, #FFB399);
        }
        
        .product-lightbox-tabs {
            display: flex;
            gap: 0;
            padding: 0;
            width: 100%;
        }
        
        .product-lightbox-tab {
            background: none;
            border: none;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
            color: var(--global-color-text-muted, #7A8FA6);
            cursor: pointer;
            position: relative;
            transition: color 0.3s ease;
        }
        
        .product-lightbox-tab:hover {
            color: var(--global-color-text-main, #0D1B2A);
        }
        
        .product-lightbox-tab.active {
            color: var(--global-color-text-main, #0D1B2A);
            font-weight: 500;
        }
        
        .product-lightbox-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--global-color-link, #13acdf);
            z-index: 1;
        }
        
        /* Body */
        .product-lightbox-body {
            flex: 1;
            display: flex;
            overflow: hidden;
            padding: 0;
        }
        
        /* Main Display - 64% */
        .product-lightbox-main {
            width: 64%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            background: transparent;
            overflow: hidden;
        }
        
        .product-lightbox-display {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            overflow: hidden;
            position: relative;
        }
        
        /* Hide video content by default - show only on VIDEOS tab */
        #lightbox-videos-content {
            display: none;
        }
        
        /* Video Player Styles */
        .product-lightbox-video-wrapper {
            width: 85%; /* Narrower than full width */
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 0; /* No gap */
            padding: 20px;
            overflow-y: auto;
        }
        
        .product-lightbox-video-player {
            position: relative;
            width: 100%;
            background: #000;
            border-radius: 0; /* No radius */
            overflow: hidden;
        }
        
        /* Default padding-top will be set by JS based on aspect ratio */
        .product-lightbox-video-player:not([style*="padding-top: 0"]) {
            padding-top: 56.25%; /* Default 16:9 */
        }
        
        .product-lightbox-video-player iframe,
        .product-lightbox-video-player video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        /* Video Interactions Section */
        .product-lightbox-video-interactions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px; /* Updated padding */
            background: var(--global-color-card-bg, #f8f9fa); /* Card background */
            border: none; /* No border */
        }
        
        .video-useful-section {
            display: flex;
            align-items: center;
            gap: 6px; /* Reduced gap */
        }
        
        .useful-text {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }
        
        .btn-video-like {
            background: transparent;
            border: none; /* No border */
            padding: 0; /* No padding */
            width: auto; /* Auto width */
            height: auto; /* Auto height */
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #666;
            font-size: 20px; /* Icon size */
            margin-left: 6px; /* Always 6px margin */
        }
        
        .btn-video-like:hover {
            color: var(--global-color-primary, #0D6EFD);
        }
        
        .btn-video-like.liked {
            color: var(--global-color-primary, #0D6EFD); /* Primary color when liked */
        }
        
        .like-count {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            min-width: 30px;
        }
        
        .btn-video-share {
            background: transparent; /* No background */
            border: none; /* No border */
            padding: 0; /* No padding */
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: color 0.3s ease; /* Only color transition, no transform */
            font-size: 20px; /* Icon size */
            color: #666;
        }
        
        .btn-video-share:hover {
            color: var(--global-color-primary, #0D6EFD); /* Primary color on hover */
        }
        
        /* Video Info Section */
        .product-lightbox-video-info {
            padding-top: 10px;
        }
        
        .video-product-title {
            font-size: 18px;
            font-weight: 400; /* Weight 400 */
            color: #333;
            margin-bottom: 3px; /* Just 3px margin bottom */
            line-height: 1.4;
        }
        
        .video-uploader-info {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 14px;
        }
        
        .video-uploader-info i {
            display: none; /* Hide icon */
        }
        
        .uploader-name {
            font-weight: 300; /* Weight 300 */
        }
        
        /* Video Lists Sidebar */
        .product-lightbox-video-lists {
            display: none;
            padding: 20px 0;
            overflow-y: auto;
            max-height: 100%;
        }
        
        /* Hide product info when on videos tab */
        .product-lightbox-video-lists ~ .product-lightbox-info,
        #lightbox-video-lists:not([style*="display: none"]) ~ .product-lightbox-info {
            display: none;
        }
        
        .video-list-section {
            margin-bottom: 30px;
        }
        
        .video-list-section:last-child {
            margin-bottom: 0;
        }
        
        .video-list-header {
            background: var(--global-color-card-bg, #f8f9fa);
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 12px;
            position: sticky; /* Sticky header */
            top: 0;
            z-index: 10;
        }
        
        .video-list-title {
            font-size: 15px;
            font-weight: 400; /* Weight 400 */
            color: #333;
            margin: 0;
            text-transform: none; /* Normal case, not uppercase */
            letter-spacing: 0;
        }
        
        .video-list-items {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .video-list-item {
            display: flex;
            gap: 12px;
            padding: 0 10px; /* Horizontal padding only */
            background: #fff;
            border: none; /* No border */
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .video-list-item:hover {
            background: #f8f9fa;
            transform: none; /* No transform */
        }
        
        .video-list-item.active {
            background: #fff;
            border: none; /* No border on container */
        }
        
        .video-list-item-thumbnail {
            flex-shrink: 0;
            width: 80px;
            height: 60px; /* 4:3 ratio (80 x 3/4 = 60) */
            border-radius: 4px;
            overflow: hidden;
            position: relative;
            background: #000;
            outline: 2px solid transparent; /* Use outline instead of border */
            outline-offset: -2px; /* Inside the element */
            transition: outline-color 0.2s ease;
        }
        
        /* Active item thumbnail gets primary outline */
        .video-list-item.active .video-list-item-thumbnail {
            outline-color: var(--global-color-primary, #0D6EFD); /* Primary color outline */
        }
        
        .video-list-item-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .video-list-item-thumbnail::after {
            content: '▶';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: 20px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        
        .video-list-item-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Align to top */
            gap: 4px; /* Reduced gap */
            min-width: 0;
        }
        
        .video-list-item-title {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .video-list-item-uploader {
            font-size: 12px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .video-list-item-uploader i {
            display: none; /* Hide icon */
        }
        
        .product-lightbox-image-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            cursor: zoom-in;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            touch-action: none; /* Prevent default touch behaviors */
        }
        
        .product-lightbox-image-wrapper.zoomed {
            cursor: zoom-out;
        }
        
        .product-lightbox-main-image {
            display: block;
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            margin: auto;
            /* No transitions - instant zoom */
        }
        
        .product-lightbox-image-wrapper.zoomed .product-lightbox-main-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: none;
            max-height: none;
            min-width: 200%;
            min-height: 200%;
            width: auto;
            height: auto;
            margin: 0;
        }
        
        /* Mobile: Use 150% zoom instead of 200% to avoid white areas */
        @media (max-width: 767px) {
            .product-lightbox-image-wrapper.zoomed .product-lightbox-main-image {
                min-width: 150%;
                min-height: 150%;
            }
        }
        
        /* Sidebar - 35% */
        .product-lightbox-sidebar {
            width: 35%;
            display: flex;
            flex-direction: column;
            background: var(--global-color-site-bg, #F4F9FD);
            border: none; /* No border */
        }
        
        .product-lightbox-info {
            padding: 20px 30px;
            border-bottom: 1px solid var(--global-color-primary-border, #FFB399);
        }
        
        .product-lightbox-title {
            font-size: 1.05rem;
            font-weight: 400;
            color: var(--global-color-text-main, #0D1B2A);
            margin: 0 0 15px 0;
            line-height: 1.4;
        }
        
        .product-lightbox-variations {
            font-size: 14px;
            color: var(--global-color-text-body, #2E3D52);
        }
        
        /* Thumbnails */
        .product-lightbox-thumbnails {
            flex: 1;
            padding: 0 20px;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            align-content: start;
        }
        
        .product-lightbox-thumbnail {
            aspect-ratio: 3 / 3.5;
            border: 2px solid var(--global-color-border-light, #F0D8CB);
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--global-color-card-bg, #FFFFFF);
        }
        
        .product-lightbox-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-lightbox-thumbnail:hover {
            border-color: var(--global-color-link-hover, #1dbff5);
        }
        
        .product-lightbox-thumbnail.active {
            border-color: var(--global-color-warning, #FBB13C);
            box-shadow: none;
        }
        
        .product-lightbox-thumbnail.active img {
            transform: scale(1.02);
        }
        
        /* Double tap hint message - HIDE on desktop, show only mobile */
        .product-lightbox-hint {
            display: none !important;
        }
        
        /* ══════════ MOBILE LAYOUT (below 768px) ══════════ */
        @media (max-width: 767px) {
            /* Full screen overlay - no spacing */
            .product-lightbox-overlay {
                padding: 0 !important;
            }
            
            .product-lightbox-container {
                width: 100% !important;
                height: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                border-radius: 0 !important;
            }
            
            /* Hide header completely on mobile */
            .product-lightbox-header {
                display: none !important;
            }
            
            /* Close button becomes back button - overlay on top left */
            .product-lightbox-close {
                position: fixed !important;
                top: 20px !important;
                left: 20px !important;
                right: auto !important;
                width: auto !important;
                height: auto !important;
                background: rgba(255, 255, 255, 0.6) !important;
                border: 1px solid rgba(13, 27, 42, 0.15) !important;
                box-shadow: none !important;
                border-radius: 20px !important;
                padding: 8px 16px !important;
                display: flex !important;
                align-items: center !important;
                z-index: 1000 !important;
            }
            
            /* Hide back button in video mode - we use mobile-video-close instead */
            .video-mode .product-lightbox-close {
                display: none !important;
            }
            
            /* Hide icon, show only text */
            .product-lightbox-close i {
                display: none !important;
            }
            
            .product-lightbox-close::after {
                content: 'Back' !important;
                font-size: 14px !important;
                font-weight: 500 !important;
                color: var(--global-color-text-main, #0D1B2A) !important;
            }
            
            /* Full screen layout */
            .product-lightbox-body {
                flex-direction: column !important;
                padding: 0 !important;
                height: 100% !important;
            }
            
            /* Main image takes full screen */
            .product-lightbox-main {
                width: 100% !important;
                height: 100% !important;
                padding: 0 !important;
                position: relative !important;
            }
            
            /* Double tap hint message - above thumbnails - MOBILE ONLY */
            .product-lightbox-hint {
                display: block !important; /* Show on mobile */
                position: fixed !important;
                bottom: 90px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                background: rgba(255, 255, 255, 0.9) !important;
                color: var(--global-color-link, #0D6EFD) !important;
                padding: 8px 16px !important;
                border-radius: 20px !important;
                font-size: 13px !important;
                font-weight: 500 !important;
                z-index: 1000 !important;
                pointer-events: none !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
                animation: fadeInOut 3s ease-in-out !important;
                opacity: 0 !important; /* Start hidden, JS will show it */
            }
            
            @keyframes fadeInOut {
                0% { opacity: 0; }
                10% { opacity: 1; }
                90% { opacity: 1; }
                100% { opacity: 0; }
            }
            
            /* Keep sidebar for thumbnails but hide info */
            .product-lightbox-sidebar {
                display: block !important;
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                height: auto !important;
                padding: 0 !important;
                border: none !important;
                background: transparent !important;
                z-index: 999 !important;
            }
            
            .product-lightbox-info {
                display: none !important;
            }
            
            /* Thumbnails as bottom strip overlay */
            .product-lightbox-thumbnails {
                display: flex !important;
                position: relative !important;
                width: 100% !important;
                height: auto !important;
                padding: 3px 15px !important;
                background: var(--global-color-site-bg, #FFFFFF) !important;
                flex-direction: row !important;
                overflow-x: auto !important; /* Changed from hidden to auto for swipe */
                overflow-y: hidden !important;
                gap: 2px !important;
                align-items: center !important;
                scroll-behavior: smooth !important;
                -webkit-overflow-scrolling: touch !important;
                /* Hide scrollbar but keep functionality */
                scrollbar-width: none !important; /* Firefox */
                -ms-overflow-style: none !important; /* IE/Edge */
            }
            
            /* Hide scrollbar for Chrome/Safari */
            .product-lightbox-thumbnails::-webkit-scrollbar {
                display: none !important;
            }
            
            /* Each thumbnail - 3:4 ratio, show 7 full + peek of next */
            .product-lightbox-thumbnail {
                flex: 0 0 calc((100vw - 30px - 12px) / 7.2) !important;
                width: calc((100vw - 30px - 12px) / 7.2) !important;
                aspect-ratio: 3 / 3.5 !important;
                height: auto !important;
                display: block !important;
                border: none !important;
            }
            
            /* Active thumbnail gets warning border on mobile */
            .product-lightbox-thumbnail.active {
                border: 2px solid var(--global-color-warning, #FBB13C) !important;
            }
        }
        
        /* ══════════ SMALLER MOBILE (480px - 768px) - Show 12 thumbnails ══════════ */
        @media (max-width: 767px) and (min-width: 480px) {
            /* Show 12 thumbnails + peek */
            .product-lightbox-thumbnail {
                flex: 0 0 calc((100vw - 30px - 22px) / 12.2) !important;
                width: calc((100vw - 30px - 22px) / 12.2) !important;
            }
        }
        
        /* Continue with rest of mobile styles */
        @media (max-width: 767px) {
            
            /* ═══════════════════════════════════════════════════════════════ */
            /* Mobile Video-Specific Styles */
            /* ═══════════════════════════════════════════════════════════════ */
            
            /* Black background for video mode */
            .product-lightbox-overlay.video-mode {
                background: #000 !important;
            }
            
            /* Hide desktop video elements on mobile */
            .product-lightbox-video-interactions,
            .product-lightbox-video-info {
                display: none !important;
            }
            
            /* Video player centered, full width */
            .product-lightbox-video-wrapper {
                padding: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                height: 100% !important;
                width: 100% !important; /* Full width */
                background: #000 !important; /* Black background */
                gap: 0 !important;
            }
            
            .product-lightbox-video-player {
                width: 100% !important;
                height: 100% !important; /* Full height */
                max-height: 100vh !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                position: relative !important;
            }
            
            /* Video element/iframe styling for proper aspect ratios */
            .product-lightbox-video-player iframe,
            .product-lightbox-video-player video {
                position: absolute !important;
                max-width: 100% !important;
                max-height: calc(100% - 200px) !important; /* Reserve space at bottom for controls + UI */
                width: auto !important;
                height: auto !important;
                border: none !important;
                object-fit: contain !important;
                bottom: 200px !important; /* Push video up to make room for controls and UI */
                top: auto !important;
            }
            
            /* Portrait videos (9:16 reels) - cover full height */
            .product-lightbox-video-player[data-aspect="9:16"] iframe,
            .product-lightbox-video-player[data-aspect="9:16"] video,
            .product-lightbox-video-player.portrait iframe,
            .product-lightbox-video-player.portrait video {
                width: auto !important;
                height: 100% !important;
                object-fit: cover !important; /* Cover for portrait to fill height */
            }
            
            /* Landscape videos (16:9) - maintain aspect ratio */
            .product-lightbox-video-player[data-aspect="16:9"] iframe,
            .product-lightbox-video-player[data-aspect="16:9"] video,
            .product-lightbox-video-player.landscape iframe,
            .product-lightbox-video-player.landscape video {
                width: 100% !important;
                height: auto !important;
                object-fit: contain !important;
            }
            
            /* Native video controls positioning - above drawer and uploader/actions */
            .product-lightbox-video-player video::-webkit-media-controls-panel {
                bottom: 160px !important; /* Position above drawer (50px) + uploader/actions area (110px) */
            }
            
            .product-lightbox-video-player video::-webkit-media-controls-enclosure {
                bottom: 160px !important;
            }
            
            /* For better visibility, add padding to video container bottom */
            .video-mode .product-lightbox-video-player {
                padding-bottom: 180px !important; /* Space for controls + UI elements */
            }
            
            /* Hide sidebar on video mode */
            .video-mode .product-lightbox-sidebar {
                display: none !important;
            }
            
            /* Mobile Video Controls Overlay */
            .mobile-video-controls {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                pointer-events: none !important;
                z-index: 1000 !important;
            }
            
            /* Hide mobile video controls when viewing images */
            .product-lightbox-overlay:not(.video-mode) .mobile-video-controls {
                display: none !important;
            }
            
            .mobile-video-controls > * {
                pointer-events: auto !important;
            }
            
            /* Top Left: Fullscreen Button */
            .mobile-video-fullscreen {
                position: absolute !important;
                top: 20px !important;
                left: 20px !important;
                width: auto !important;
                height: auto !important;
                background: none !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                cursor: pointer !important;
                z-index: 10 !important;
            }
            
            .mobile-video-fullscreen i {
                font-size: 24px !important;
                color: #fff !important;
            }
            
            /* Product Title Container - below fullscreen button */
            .mobile-video-title-container {
                position: absolute !important;
                top: 55px !important; /* Below fullscreen button */
                left: 20px !important;
                max-width: calc(100vw - 80px) !important; /* Leave space for close button */
                display: flex !important;
                flex-direction: column !important;
                gap: 4px !important;
            }
            
            .mobile-video-title {
                color: #fff !important;
                font-size: 14px !important;
                font-weight: 500 !important;
                line-height: 1.4 !important;
                margin: 0 !important;
                display: -webkit-box !important;
                -webkit-line-clamp: 2 !important;
                -webkit-box-orient: vertical !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                text-align: left !important;
                transition: -webkit-line-clamp 0.3s ease !important;
                word-break: break-word !important;
            }
            
            .mobile-video-title.expanded {
                -webkit-line-clamp: unset !important;
            }
            
            .mobile-video-title-expand {
                background: none !important;
                border: none !important;
                padding: 0 !important;
                cursor: pointer !important;
                display: flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                width: auto !important;
                height: auto !important;
                color: #fff !important;
                transition: transform 0.3s ease !important;
                align-self: flex-start !important;
            }
            
            .mobile-video-title-expand.expanded {
                transform: rotate(180deg) !important;
            }
            
            .mobile-video-title-expand i {
                font-size: 12px !important; /* Smaller chevron */
            }
            
            /* Fullscreen mode: hide everything except video and its native controls */
            .product-lightbox-overlay:fullscreen .mobile-video-header,
            .product-lightbox-overlay:fullscreen .mobile-video-title-container,
            .product-lightbox-overlay:fullscreen .mobile-video-uploader,
            .product-lightbox-overlay:fullscreen .mobile-video-actions,
            .product-lightbox-overlay:fullscreen .mobile-video-drawer,
            .product-lightbox-overlay:-webkit-full-screen .mobile-video-header,
            .product-lightbox-overlay:-webkit-full-screen .mobile-video-title-container,
            .product-lightbox-overlay:-webkit-full-screen .mobile-video-uploader,
            .product-lightbox-overlay:-webkit-full-screen .mobile-video-actions,
            .product-lightbox-overlay:-webkit-full-screen .mobile-video-drawer,
            .product-lightbox-overlay:-moz-full-screen .mobile-video-header,
            .product-lightbox-overlay:-moz-full-screen .mobile-video-title-container,
            .product-lightbox-overlay:-moz-full-screen .mobile-video-uploader,
            .product-lightbox-overlay:-moz-full-screen .mobile-video-actions,
            .product-lightbox-overlay:-moz-full-screen .mobile-video-drawer {
                display: none !important;
            }
            
            /* Keep fullscreen button visible in fullscreen */
            .product-lightbox-overlay:fullscreen .mobile-video-fullscreen,
            .product-lightbox-overlay:-webkit-full-screen .mobile-video-fullscreen,
            .product-lightbox-overlay:-moz-full-screen .mobile-video-fullscreen {
                display: flex !important;
            }
            
            /* Top Right: Close Button Only */
            .mobile-video-header {
                position: absolute !important;
                top: 20px !important;
                right: 20px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: flex-end !important;
            }
            
            .mobile-video-close {
                width: auto !important;
                height: auto !important;
                border-radius: 0 !important;
                background: none !important;
                border: none !important;
                box-shadow: none !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                cursor: pointer !important;
                padding: 0 !important;
            }
            
            .mobile-video-close i {
                font-size: 24px !important;
                color: #fff !important;
            }
            
            /* Bottom Left: Uploader Info */
            .mobile-video-uploader {
                position: absolute !important;
                bottom: 120px !important; /* Above drawer */
                left: 20px !important;
                display: flex !important;
                align-items: center !important;
                gap: 10px !important;
                background: none !important;
                backdrop-filter: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                max-width: calc(100vw - 150px) !important;
            }
            
            .mobile-uploader-avatar {
                width: 32px !important;
                height: 32px !important;
                border-radius: 50% !important;
                background: rgba(255, 255, 255, 0.2) !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                color: #fff !important;
                font-size: 16px !important;
                flex-shrink: 0 !important;
            }
            
            .mobile-uploader-name {
                color: #fff !important;
                font-size: 14px !important;
                font-weight: 500 !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                max-width: 16ch !important;
            }
            
            /* Bottom Right: Action Buttons */
            .mobile-video-actions {
                position: absolute !important;
                bottom: 120px !important; /* Above drawer */
                right: 20px !important;
                display: flex !important;
                flex-direction: column !important;
                gap: 20px !important;
                align-items: center !important;
            }
            
            .mobile-action-btn {
                width: auto !important;
                height: auto !important;
                border-radius: 0 !important;
                background: none !important;
                border: none !important;
                box-shadow: none !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                cursor: pointer !important;
                position: relative !important;
                transition: none !important;
                padding: 0 !important;
            }
            
            .mobile-action-btn i {
                font-size: 28px !important;
                color: #fff !important;
            }
            
            .mobile-action-btn.mobile-btn-like[data-liked="true"] i {
                color: #fff !important;
            }
            
            .mobile-action-count {
                position: absolute !important;
                bottom: -20px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                color: #fff !important;
                font-size: 12px !important;
                font-weight: 600 !important;
                white-space: nowrap !important;
                text-shadow: none !important;
            }
            
            .mobile-sold-count {
                font-size: 11px !important;
            }
            
            /* Mobile Video Drawer */
            .mobile-video-drawer {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                background: var(--global-color-secondary-mid, #E8F1F8) !important; /* Secondary mid color */
                border-radius: 20px 20px 0 0 !important;
                box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.2) !important;
                transform: translateY(calc(100% - 50px)) !important;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                z-index: 1001 !important;
                max-height: 80vh !important;
                display: flex !important;
                flex-direction: column !important;
            }
            
            .mobile-video-drawer.open {
                transform: translateY(0) !important;
            }
            
            /* Hide drawer when viewing images (not in video mode) */
            .product-lightbox-overlay:not(.video-mode) .mobile-video-drawer {
                display: none !important;
            }
            
            .mobile-drawer-handle {
                padding: 12px 0 !important;
                display: flex !important;
                justify-content: center !important;
                cursor: grab !important;
            }
            
            .mobile-drawer-handle:active {
                cursor: grabbing !important;
            }
            
            .mobile-drawer-grabber {
                width: 40px !important;
                height: 4px !important;
                background: rgba(0, 0, 0, 0.3) !important;
                border-radius: 2px !important;
            }
            
            .mobile-drawer-content {
                flex: 1 !important;
                overflow-y: auto !important;
                padding: 0 15px 20px !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            .mobile-drawer-videos {
                display: flex !important;
                flex-direction: column !important;
                gap: 0 !important;
            }
            
            /* Reuse existing video list styles for drawer */
            .mobile-drawer-videos .video-list-section {
                margin-bottom: 20px !important;
            }
        }
</style>
