{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- PRODUCT LIGHTBOX MODAL - Standalone Component                   --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- Features: Image gallery, video player, mobile controls          --}}
{{-- Dependencies: Phosphor Icons, Theme CSS variables                --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}

<div id="product-lightbox-modal" class="product-lightbox-overlay" style="display: none;">
    <div class="product-lightbox-container">
        {{-- Close button - positioned at container level --}}
        <button class="product-lightbox-close" aria-label="Close">
            <i class="icon-cross"></i>
        </button>
        
        {{-- Header --}}
        <div class="product-lightbox-header">
            <div class="product-lightbox-tabs">
                <button class="product-lightbox-tab active" data-tab="images">IMAGES</button>
                <button class="product-lightbox-tab" data-tab="videos">VIDEOS</button>
            </div>
        </div>
        
        {{-- Body --}}
        <div class="product-lightbox-body">
            {{-- Left: Main Display (64%) --}}
            <div class="product-lightbox-main">
                {{-- Double tap hint message (mobile only) --}}
                <div class="product-lightbox-hint" id="lightbox-hint-message">
                    Double tap to zoom
                </div>
                
                <div class="product-lightbox-display" id="lightbox-images-content">
                    <div class="product-lightbox-image-wrapper">
                        <img src="" alt="" class="product-lightbox-main-image" id="lightbox-main-image">
                    </div>
                </div>
                <div class="product-lightbox-display" id="lightbox-videos-content" style="display: none;">
                    <div class="product-lightbox-video-wrapper">
                        <div class="product-lightbox-video-player" id="lightbox-video-player">
                            {{-- Video iframe will be inserted here --}}
                        </div>
                        
                        <div class="product-lightbox-video-interactions">
                            <div class="video-useful-section">
                                <span class="useful-text">Useful?</span>
                                <button class="btn-video-like" id="lightbox-video-like-btn" data-liked="false">
                                    <i class="ph ph-thumbs-up"></i>
                                </button>
                                <span class="like-count" id="lightbox-video-like-count">0</span>
                            </div>
                            <button class="btn-video-share" id="lightbox-video-share-btn">
                                <i class="ph ph-share-fat"></i>
                                <span class="share-count">0</span>
                            </button>
                        </div>
                        
                        <div class="product-lightbox-video-info">
                            <h4 class="video-product-title" id="lightbox-video-product-title"></h4>
                            <div class="video-uploader-info" id="lightbox-video-uploader">
                                <i class="fa fa-user-circle"></i>
                                <span class="uploader-name"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Right: Info & Thumbnails (35%) --}}
            <div class="product-lightbox-sidebar">
                <div class="product-lightbox-info">
                    <h3 class="product-lightbox-title" id="lightbox-product-title">Product Title</h3>
                    <div class="product-lightbox-variations" id="lightbox-product-variations">
                        {{-- Variations will be inserted here --}}
                    </div>
                </div>
                
                <div class="product-lightbox-thumbnails" id="lightbox-thumbnails">
                    {{-- Thumbnails will be inserted here --}}
                </div>
                
                <div class="product-lightbox-video-lists" id="lightbox-video-lists" style="display: none;">
                    {{-- Video lists section - shown only on Videos tab --}}
                    
                    {{-- This Product Videos --}}
                    <div class="video-list-section" id="product-videos-section">
                        <div class="video-list-header">
                            <h4 class="video-list-title">This Product Videos</h4>
                        </div>
                        <div class="video-list-items" id="product-videos-list">
                            {{-- Product video items will be inserted here --}}
                        </div>
                    </div>
                    
                    {{-- Related Videos --}}
                    <div class="video-list-section" id="related-videos-section" style="display: none;">
                        <div class="video-list-header">
                            <h4 class="video-list-title">Related This Product Videos</h4>
                        </div>
                        <div class="video-list-items" id="related-videos-list">
                            {{-- Related video items will be inserted here --}}
                        </div>
                    </div>
                    
                    {{-- Customer Review Videos --}}
                    <div class="video-list-section" id="review-videos-section" style="display: none;">
                        <div class="video-list-header">
                            <h4 class="video-list-title">Customer Review Videos</h4>
                        </div>
                        <div class="video-list-items" id="review-videos-list">
                            {{-- Review video items will be inserted here --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Mobile Video Controls Overlay (Only for Videos on Mobile) --}}
        <div class="mobile-video-controls" id="mobile-video-controls" style="display: none;">
            {{-- Top Left: Product Title with inline chevron --}}
            <div class="mobile-video-title-container" id="mobile-video-title-container">
                <h3 class="mobile-video-title" id="mobile-video-title"></h3>
                <button class="mobile-video-title-expand" id="mobile-video-title-expand">
                    <i class="icon-chevron-down"></i>
                </button>
            </div>
            
            {{-- Top Right: Close Button (Always visible) --}}
            <div class="mobile-video-header">
                <button class="mobile-video-close" aria-label="Close">
                    <i class="icon-cross"></i>
                </button>
            </div>
            
            {{-- Bottom Left: Uploader Info --}}
            <div class="mobile-video-uploader">
                <div class="mobile-uploader-avatar" id="mobile-uploader-avatar">
                    <i class="icon-user"></i>
                </div>
                <span class="mobile-uploader-name" id="mobile-uploader-name"></span>
            </div>
            
            {{-- Bottom Right: Action Buttons --}}
            <div class="mobile-video-actions">
                <button class="mobile-action-btn mobile-btn-like" id="mobile-btn-like" data-liked="false">
                    <i class="ph ph-thumbs-up"></i>
                    <span class="mobile-action-count" id="mobile-like-count">0</span>
                </button>
                <button class="mobile-action-btn mobile-btn-buy" id="mobile-btn-buy">
                    <i class="icon-bag2"></i>
                    <span class="mobile-action-count mobile-sold-count" id="mobile-sold-count">1.2K sold</span>
                </button>
                <button class="mobile-action-btn mobile-btn-cart" id="mobile-btn-cart">
                    <i class="icon-cart"></i>
                    <span class="mobile-action-count" id="mobile-cart-count">0</span>
                </button>
                <button class="mobile-action-btn mobile-btn-share" id="mobile-btn-share">
                    <i class="ph ph-share-fat"></i>
                    <span class="mobile-action-count" id="mobile-share-count">0</span>
                </button>
            </div>
            
            {{-- Bottom Center: Minimal Controls (shown when main controls are hidden) --}}
            <div class="mobile-video-minimal-controls" id="mobile-video-minimal-controls" style="display: none;">
                <button class="minimal-control-btn" id="minimal-share-btn">
                    <i class="ph ph-share-fat"></i>
                    <span>Share</span>
                </button>
                <button class="minimal-control-btn" id="minimal-more-btn">
                    <i class="ph ph-video"></i>
                    <span>More videos</span>
                </button>
            </div>
        </div>
        
        {{-- Mobile Video Drawer (Pull up for more videos) --}}
        <div class="mobile-video-drawer" id="mobile-video-drawer" style="display: none;">
            <div class="mobile-drawer-handle">
                <div class="mobile-drawer-grabber"></div>
            </div>
            <div class="mobile-drawer-content">
                <div class="mobile-drawer-videos" id="mobile-drawer-videos">
                    {{-- Video list will be inserted here --}}
                </div>
            </div>
        </div>
    </div>
</div>
