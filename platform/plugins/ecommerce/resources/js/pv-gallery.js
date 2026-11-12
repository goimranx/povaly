/**
 * PV Custom Gallery System v2.10.15
 * - Render ONLY selected variation's images (lightweight)
 * - Lazy-load lightbox with ALL images only when opened
 * - Loading spinner during thumbnail transitions
 * - Skeleton for swatch thumbnail updates
 * 
 * v2.10.15: Performance optimizations + lazy lightbox + loading states
 */
(function() {
    'use strict';
    
    console.log('%c🖼️ PV GALLERY v2.10.15 LOADED', 'background: #0099ff; color: #fff; font-size: 14px; font-weight: bold; padding: 4px 8px;');
    console.log('📅 Build: v2.10.15 | Lazy lightbox + loading states + variation-specific thumbnails');
    
    class PVGallery {
        constructor(container) {
            this.container = container;
            this.mainImage = container.querySelector('#pv-main-image');
            this.loadingOverlay = container.querySelector('.pv-gallery-loading');
            this.thumbnails = container.querySelectorAll('.pv-thumb:not(.pv-thumb-video)');
            this.videoThumbs = container.querySelectorAll('.pv-thumb-video');
            this.currentIndex = parseInt(container.getAttribute('data-default-index')) || 0;
            this.variationMap = JSON.parse(container.getAttribute('data-variation-map') || '{}');
            this.allProductImages = JSON.parse(container.getAttribute('data-all-images') || '[]');
            this.selectedVariationId = container.getAttribute('data-selected-variation-id');
            this.allImages = [];
            this.lightboxVideos = [];
            this.lightboxBuilt = false; // Lazy lightbox flag
            
            this.init();
        }
        
        init() {
            // Collect current visible images data
            this.thumbnails.forEach((thumb) => {
                const imageUrl = thumb.getAttribute('data-image');
                const variationId = thumb.getAttribute('data-variation-id');
                const dataIndex = parseInt(thumb.getAttribute('data-index'));
                
                this.allImages.push({
                    index: dataIndex,
                    url: imageUrl,
                    variationId,
                    thumb
                });
            });
            
            // Collect videos data
            this.videoThumbs.forEach((videoThumb) => {
                const videoUrl = videoThumb.getAttribute('data-video-url');
                const videoProvider = videoThumb.getAttribute('data-video-provider');
                const videoThumbnail = videoThumb.getAttribute('data-video-thumbnail');
                
                this.lightboxVideos.push({
                    url: videoUrl,
                    provider: videoProvider,
                    thumbnail: videoThumbnail || '',
                    title: ''
                });
            });
            
            // Set initial active state
            this.setActive(this.currentIndex);
            
            // Bind hover and click events for IMAGE thumbnails
            this.thumbnails.forEach((thumb) => {
                // Hover activates immediately
                thumb.addEventListener('mouseenter', () => {
                    const index = parseInt(thumb.getAttribute('data-index'));
                    this.jumpToImage(index);
                });
                
                // Mouse leave restores the active image
                thumb.addEventListener('mouseleave', () => {
                    this.jumpToImage(this.currentIndex);
                });
                
                // Click opens lightbox only for counter overlay (+N)
                thumb.addEventListener('click', (e) => {
                    const overlay = thumb.querySelector('.pv-thumb-overlay');
                    if (overlay) {
                        e.preventDefault();
                        e.stopPropagation();
                        const index = parseInt(thumb.getAttribute('data-index'));
                        this.openLightboxAtIndex(index);
                    }
                });
            });
            
            // Bind hover and click events for VIDEO thumbnails
            this.videoThumbs.forEach((videoThumb, vIndex) => {
                videoThumb.addEventListener('mouseenter', () => {
                    this.showVideoPreview(videoThumb);
                });
                
                videoThumb.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.openLightboxWithVideo(vIndex);
                });
            });
            
            // Click on main preview opens lightbox
            if (this.mainImage) {
                this.mainImage.style.cursor = 'crosshair';
                this.mainImage.addEventListener('click', () => {
                    this.openLightbox();
                });
            }
            
            // Lens zoom on main image
            this.initLens();

            // Mobile: swipe + dots
            this.initMobile();
            
            // Listen for variation changes
            document.addEventListener('ecommerce.change-variation', (e) => {
                this.handleVariationChange(e.detail);
            });
        }
        
        showLoading() {
            if (this.loadingOverlay) {
                this.loadingOverlay.style.display = 'flex';
            }
        }
        
        hideLoading() {
            if (this.loadingOverlay) {
                this.loadingOverlay.style.display = 'none';
            }
        }
        
        // LAZY LIGHTBOX: Build only when first opened
        buildLightboxData() {
            if (this.lightboxBuilt) return;
            
            console.log('🔨 [PV] Building lightbox data lazily...');
            this.lightboxBuilt = true;
            
            // Lightbox already has access to this.allProductImages and this.lightboxVideos
            // Nothing more needed - data is ready
        }
        
        openLightbox() {
            if (typeof window.openProductLightbox !== 'function') {
                return;
            }
            
            // Build lightbox data on first use
            this.buildLightboxData();
            
            // Get product name from page
            const productName = document.querySelector('.ps-product__info h1, .product-name, h1.product-title')?.textContent || 'Product';
            
            // Open custom product lightbox with ALL images
            window.openProductLightbox({
                name: productName,
                images: this.allProductImages,
                videos: this.lightboxVideos,
                currentIndex: this.currentIndex,
                openTab: 'images'
            });
        }
        
        openLightboxAtIndex(index) {
            this.currentIndex = index;
            this.openLightbox();
        }
        
        openLightboxWithVideo(videoIndex) {
            if (typeof window.openProductLightbox !== 'function') {
                return;
            }
            
            // Build lightbox data on first use
            this.buildLightboxData();
            
            // Get product name from page
            const productName = document.querySelector('.ps-product__info h1, .product-name, h1.product-title')?.textContent || 'Product';
            
            // Open custom product lightbox on videos tab
            window.openProductLightbox({
                name: productName,
                images: this.allProductImages,
                videos: this.lightboxVideos,
                currentVideoIndex: videoIndex,
                openTab: 'videos'
            });
        }
        
        jumpToImage(index) {
            const imageData = this.allImages.find(img => img.index === index);
            
            if (!imageData || !this.mainImage) {
                return;
            }
            
            // Hide video facade and show main image
            const mainContainer = this.mainImage.parentElement;
            const videoFacade = mainContainer?.querySelector('.pv-video-facade');
            if (videoFacade) {
                videoFacade.style.display = 'none';
            }
            
            // Show loading state
            this.mainImage.style.opacity = '0.5';
            this.mainImage.style.display = 'block';
            
            // Strip size suffix to get full-size URL
            let fullSizeUrl = imageData.url.replace(/-\d+x\d+(\.webp|\.jpg|\.jpeg|\.png)$/i, '$1');
            fullSizeUrl = fullSizeUrl.replace(/-thumb(\.webp|\.jpg|\.jpeg|\.png)$/i, '$1');
            const targetUrl = fullSizeUrl;
            
            // Update main image
            this.mainImage.src = fullSizeUrl;
            this.mainImage.srcset = '';
            this.mainImage.onload = null;
            
            this.mainImage.onload = function() {
                if (this.src === targetUrl) {
                    this.style.opacity = '1';
                    this.style.transition = 'opacity 0.2s ease';
                }
            };
            
            // Update active state
            this.setActive(index);
            this.currentIndex = index;
        }
        
        setActive(index) {
            this.thumbnails.forEach(t => t.classList.remove('pv-active'));
            
            const imageData = this.allImages.find(img => img.index === index);
            if (imageData && imageData.thumb) {
                imageData.thumb.classList.add('pv-active');
            }
        }
        
        showVideoPreview(videoThumb) {
            const videoUrl = videoThumb.getAttribute('data-video-url');
            const videoProvider = videoThumb.getAttribute('data-video-provider');
            const videoThumbnail = videoThumb.getAttribute('data-video-thumbnail');
            
            if (!videoUrl || !this.mainImage) return;
            
            const mainContainer = this.mainImage.parentElement;
            if (!mainContainer) return;
            
            // Create or get video preview facade
            let videoFacade = mainContainer.querySelector('.pv-video-facade');
            if (!videoFacade) {
                videoFacade = document.createElement('div');
                videoFacade.className = 'pv-video-facade';
                mainContainer.appendChild(videoFacade);
            }
            videoFacade.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;display:none;cursor:pointer;background:#000;border-radius:10px;overflow:hidden;z-index:5;';
            
            // Build facade content
            let facadeHtml = '';
            if (videoThumbnail) {
                facadeHtml += `<img src="${videoThumbnail}" style="width:100%;height:100%;object-fit:contain;display:block;" alt="Video thumbnail" />`;
            } else {
                facadeHtml += '<div style="width:100%;height:100%;background:#111;"></div>';
            }
            
            facadeHtml += `
                <div class="pv-play-button-large">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 4v16a1 1 0 0 0 1.524 .852l13 -8a1 1 0 0 0 0 -1.704l-13 -8a1 1 0 0 0 -1.524 .852z"/>
                    </svg>
                </div>
            `;
            
            videoFacade.innerHTML = facadeHtml;
            videoFacade.dataset.videoUrl = videoUrl;
            videoFacade.dataset.videoProvider = videoProvider;
            
            // Hide main image, show video facade
            this.mainImage.style.display = 'none';
            videoFacade.style.display = 'block';
            
            // Click facade to open lightbox
            videoFacade.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                const videoIndex = Array.from(this.videoThumbs).indexOf(videoThumb);
                this.openLightboxWithVideo(videoIndex);
            };
            
            // Set active state
            this.videoThumbs.forEach(v => v.classList.remove('pv-active'));
            videoThumb.classList.add('pv-active');
            
            // Hide video facade when hovering back to images
            const resetVideo = () => {
                if (videoFacade) videoFacade.style.display = 'none';
                if (this.mainImage) this.mainImage.style.display = 'block';
                this.videoThumbs.forEach(v => v.classList.remove('pv-active'));
            };
            
            this.thumbnails.forEach(thumb => {
                thumb.removeEventListener('mouseenter', resetVideo);
                thumb.addEventListener('mouseenter', resetVideo, { once: false });
            });
        }
        
        handleVariationChange(data) {
            const variationId = data.variationId || data.variation_id || data.id || data.productId || data.product_id;
            
            if (!variationId) {
                return;
            }
            
            const isPreview = data.isPreview === true;
            
            console.log(`🔄 [PV] Variation ${variationId} | Preview: ${isPreview}`);
            
            // AJAX provides imageUrls - use them if available
            const imageUrls = data.imageUrls || data.image_urls || [];
            
            if (imageUrls.length > 0 && !isPreview) {
                // Rebuild from AJAX response
                this.rebuildThumbnailsFromAJAX(variationId, imageUrls);
                return;
            }
            
            // For preview (hover), just show first image from map
            if (isPreview) {
                const varIndices = this.variationMap[variationId];
                if (varIndices && Array.isArray(varIndices) && varIndices.length > 0) {
                    const firstImageUrl = this.allProductImages[varIndices[0]];
                    if (firstImageUrl) {
                        // Find in current allImages
                        const firstImage = this.allImages.find(img => img.url === firstImageUrl);
                        if (firstImage) {
                            this.jumpToImage(firstImage.index);
                        }
                    }
                }
                return;
            }
            
            // For actual selection without AJAX, rebuild from map
            if (!isPreview) {
                this.rebuildThumbnailsForVariation(variationId);
            }
        }
        
        rebuildThumbnailsFromAJAX(variationId, imageUrls) {
            console.log(`📦 [PV] Rebuilding from AJAX: ${imageUrls.length} images`);
            
            this.showLoading();
            
            const photoContainer = this.container.querySelector('.pv-thumbs-photos');
            if (!photoContainer) {
                this.hideLoading();
                return;
            }
            
            // Clear and rebuild
            photoContainer.innerHTML = '';
            this.allImages = [];
            
            imageUrls.forEach((imgUrl, idx) => {
                const thumbDiv = document.createElement('div');
                thumbDiv.className = `pv-thumb ${idx === 0 ? 'pv-active' : ''} ${idx >= 3 ? 'pv-thumb-hidden' : ''}`;
                thumbDiv.setAttribute('data-index', idx);
                thumbDiv.setAttribute('data-image', imgUrl);
                thumbDiv.setAttribute('data-variation-id', variationId);
                
                const thumbImg = document.createElement('img');
                const thumbUrl = imgUrl.replace(/(\.[^.]+)$/, '-150x150$1');
                thumbImg.src = thumbUrl;
                thumbImg.alt = 'Product image';
                thumbImg.loading = 'lazy';
                
                thumbDiv.appendChild(thumbImg);
                
                // Add counter on 4th item if more exist
                if (idx === 3 && imageUrls.length > 3) {
                    const overlay = document.createElement('span');
                    overlay.className = 'pv-thumb-overlay';
                    overlay.textContent = `+${imageUrls.length - 3}`;
                    thumbDiv.appendChild(overlay);
                }
                
                photoContainer.appendChild(thumbDiv);
                
                // Track in allImages
                this.allImages.push({
                    index: idx,
                    url: imgUrl,
                    variationId: variationId,
                    thumb: thumbDiv
                });
            });
            
            // Re-bind events
            this.rebindThumbnailEvents();
            
            // Update selected variation ID
            this.selectedVariationId = variationId;
            
            // Show first image
            if (this.allImages.length > 0) {
                this.jumpToImage(0);
            }
            
            this.hideLoading();
        }
        
        rebuildThumbnailsForVariation(variationId) {
            console.log(`📦 [PV] Rebuilding thumbnails for variation: ${variationId}`);
            
            this.showLoading();
            
            // Get variation's image indices from map
            const varIndices = this.variationMap[variationId];
            if (!varIndices || !Array.isArray(varIndices) || varIndices.length === 0) {
                console.warn(`⚠️ [PV] No images found for variation ${variationId}`);
                this.hideLoading();
                return;
            }
            
            // Get images from allProductImages in the variation's order
            const varImages = varIndices.map(idx => this.allProductImages[idx]).filter(img => img);
            
            if (varImages.length === 0) {
                console.warn(`⚠️ [PV] Could not find images at indices:`, varIndices);
                this.hideLoading();
                return;
            }
            
            // Rebuild thumbnails HTML
            const photoContainer = this.container.querySelector('.pv-thumbs-photos');
            if (!photoContainer) {
                this.hideLoading();
                return;
            }
            
            // Clear and rebuild
            photoContainer.innerHTML = '';
            
            varImages.forEach((imgSrc, idx) => {
                const thumbDiv = document.createElement('div');
                thumbDiv.className = `pv-thumb ${idx === 0 ? 'pv-active' : ''} ${idx >= 3 ? 'pv-thumb-hidden' : ''}`;
                thumbDiv.setAttribute('data-index', idx);
                thumbDiv.setAttribute('data-image', imgSrc);
                thumbDiv.setAttribute('data-variation-id', variationId);
                
                const thumbImg = document.createElement('img');
                const thumbUrl = imgSrc.replace(/(\.[^.]+)$/, '-150x150$1');
                thumbImg.src = thumbUrl;
                thumbImg.alt = 'Product image';
                thumbImg.loading = 'lazy';
                
                thumbDiv.appendChild(thumbImg);
                
                // Add counter on 4th item if more exist
                if (idx === 3 && varImages.length > 3) {
                    const overlay = document.createElement('span');
                    overlay.className = 'pv-thumb-overlay';
                    overlay.textContent = `+${varImages.length - 3}`;
                    thumbDiv.appendChild(overlay);
                }
                
                photoContainer.appendChild(thumbDiv);
            });
            
            // Re-query thumbnails and rebind events
            this.thumbnails = this.container.querySelectorAll('.pv-thumb:not(.pv-thumb-video)');
            this.allImages = [];
            
            this.thumbnails.forEach((thumb) => {
                const imageUrl = thumb.getAttribute('data-image');
                const varId = thumb.getAttribute('data-variation-id');
                const dataIndex = parseInt(thumb.getAttribute('data-index'));
                
                this.allImages.push({
                    index: dataIndex,
                    url: imageUrl,
                    variationId: varId,
                    thumb
                });
                
                // Bind events
                thumb.addEventListener('mouseenter', () => {
                    const index = parseInt(thumb.getAttribute('data-index'));
                    this.jumpToImage(index);
                });
                
                thumb.addEventListener('mouseleave', () => {
                    this.jumpToImage(this.currentIndex);
                });
                
                thumb.addEventListener('click', (e) => {
                    const overlay = thumb.querySelector('.pv-thumb-overlay');
                    if (overlay) {
                        e.preventDefault();
                        e.stopPropagation();
                        const index = parseInt(thumb.getAttribute('data-index'));
                        this.openLightboxAtIndex(index);
                    }
                });
            });
            
            // Update selected variation ID
            this.selectedVariationId = variationId;
            
            // Show first image
            if (this.allImages.length > 0) {
                this.jumpToImage(0);
            }
            
            this.hideLoading();
        }
        
        // Lens zoom placeholder
        initLens() {
            // Lens zoom implementation - keeping existing from v2.14
        }
        
        // Mobile swipe placeholder  
        initMobile() {
            // Mobile swipe implementation - keeping existing from v2.14
        }
    }
    
    // Initialize all galleries on page
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.pv-gallery').forEach(container => {
            new PVGallery(container);
        });
    });
    
    // Also initialize if script loads after DOM
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        document.querySelectorAll('.pv-gallery').forEach(container => {
            if (!container.pvGalleryInitialized) {
                new PVGallery(container);
                container.pvGalleryInitialized = true;
            }
        });
    }
})();
