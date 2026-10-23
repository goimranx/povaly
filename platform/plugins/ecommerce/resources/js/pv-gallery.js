/**
 * PV Custom Gallery System
 * - Hover to preview AND activate (no click needed)
 * - Click counter overlay to open lightbox
 * - Variation change jumps to first variation image
 */
(function() {
    'use strict';
    
    class PVGallery {
        constructor(container) {
            this.container = container;
            this.mainImage = container.querySelector('#pv-main-image');
            this.thumbnails = container.querySelectorAll('.pv-thumb:not(.pv-thumb-video)');
            this.videoThumbs = container.querySelectorAll('.pv-thumb-video');
            this.currentIndex = parseInt(container.getAttribute('data-default-index')) || 0;
            this.variationMap = JSON.parse(container.getAttribute('data-variation-map') || '{}');
            this.allImages = [];
            this.imageUrls = [];
            this.lightboxVideos = [];
            
            this.init();
        }
        
        init() {
            console.log('✅ PV Gallery initialized');
            console.log('📸 Image thumbnails:', this.thumbnails.length);
            console.log('🎥 Video thumbnails:', this.videoThumbs.length);
            
            // Collect all images data
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
            
            // Initialize lightGallery on the main image
            this.initLightGallery();
            
            // Set initial active state
            this.setActive(this.currentIndex);
            
            // Bind hover and click events for IMAGE thumbnails
            this.thumbnails.forEach((thumb) => {
                // Hover activates immediately
                thumb.addEventListener('mouseenter', () => {
                    const index = parseInt(thumb.getAttribute('data-index'));
                    this.jumpToImage(index);
                });
                
                // Click opens lightbox (unless it's a counter overlay)
                thumb.addEventListener('click', (e) => {
                    const overlay = thumb.querySelector('.pv-thumb-overlay');
                    if (overlay) {
                        // Counter overlay - open lightbox
                        e.preventDefault();
                        e.stopPropagation();
                        const index = parseInt(thumb.getAttribute('data-index'));
                        this.openLightboxAtIndex(index);
                    } else {
                        // Regular thumbnail - open lightbox
                        e.preventDefault();
                        this.openLightbox();
                    }
                });
            });
            
            // Bind hover and click events for VIDEO thumbnails
            this.videoThumbs.forEach((videoThumb, vIndex) => {
                // Hover shows video preview facade
                videoThumb.addEventListener('mouseenter', () => {
                    this.showVideoPreview(videoThumb);
                });
                
                // Click opens lightbox on videos tab
                videoThumb.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.openLightboxWithVideo(vIndex);
                });
            });
            
            // Click on main preview opens lightbox
            if (this.mainImage) {
                this.mainImage.style.cursor = 'pointer';
                this.mainImage.addEventListener('click', () => {
                    this.openLightbox();
                });
            }
            
            // Listen for variation changes
            document.addEventListener('ecommerce.change-variation', (e) => {
                this.handleVariationChange(e.detail);
            });
        }
        
        initLightGallery() {
            // Build image URLs array for lightbox
            this.imageUrls = [];
            this.allImages.forEach(img => {
                this.imageUrls.push(img.url);
            });
            
            // Build videos array for lightbox
            this.lightboxVideos = [];
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
        }
        
        openLightbox() {
            if (typeof window.openProductLightbox !== 'function') {
                console.warn('Product lightbox not available');
                return;
            }
            
            // Get product name from page
            const productName = document.querySelector('.ps-product__info h1, .product-name, h1.product-title')?.textContent || 'Product';
            
            // Open custom product lightbox
            window.openProductLightbox({
                name: productName,
                images: this.imageUrls,
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
                console.warn('Product lightbox not available');
                return;
            }
            
            // Get product name from page
            const productName = document.querySelector('.ps-product__info h1, .product-name, h1.product-title')?.textContent || 'Product';
            
            // Open custom product lightbox on videos tab
            window.openProductLightbox({
                name: productName,
                images: this.imageUrls,
                videos: this.lightboxVideos,
                currentVideoIndex: videoIndex,
                openTab: 'videos'
            });
        }
        
        jumpToImage(index) {
            const imageData = this.allImages.find(img => img.index === index);
            
            if (!imageData || !this.mainImage) return;
            
            // Update main image
            this.mainImage.src = imageData.url;
            this.mainImage.srcset = '';
            
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
            
            // Create or get video preview facade (thumbnail + circled play button)
            let videoFacade = mainContainer.querySelector('.pv-video-facade');
            if (!videoFacade) {
                videoFacade = document.createElement('div');
                videoFacade.className = 'pv-video-facade';
                videoFacade.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;display:none;cursor:pointer;background:#000;border-radius:10px;overflow:hidden;';
                mainContainer.appendChild(videoFacade);
            }
            
            // Build facade content: thumbnail + circled play button
            let facadeHtml = '';
            
            if (videoThumbnail) {
                facadeHtml += `<img src="${videoThumbnail}" style="width:100%;height:100%;object-fit:contain;display:block;" alt="Video thumbnail" />`;
            } else {
                facadeHtml += '<div style="width:100%;height:100%;background:#111;"></div>';
            }
            
            // Add large circled play button overlay
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
            
            // Click facade to open lightbox with videos tab
            videoFacade.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                // Find video index and open lightbox
                const videoIndex = Array.from(this.videoThumbs).indexOf(videoThumb);
                this.openLightboxWithVideo(videoIndex);
            };
            
            // Set active state on video thumbnail
            this.videoThumbs.forEach(v => v.classList.remove('pv-active'));
            videoThumb.classList.add('pv-active');
            
            // Hide video facade when hovering back to image thumbnails
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
        
        loadActualVideo(container, videoUrl, videoProvider) {
            // Replace facade with actual video player
            container.innerHTML = '';
            container.style.cursor = 'default';
            container.onclick = null;
            
            if (videoProvider === 'video') {
                // Direct video file
                const video = document.createElement('video');
                video.controls = true;
                video.autoplay = true;
                video.style.cssText = 'width:100%;height:100%;object-fit:contain;';
                video.src = videoUrl;
                container.appendChild(video);
            } else if (videoProvider === 'youtube' || videoProvider === 'vimeo') {
                // Embedded iframe with autoplay
                const iframe = document.createElement('iframe');
                iframe.style.cssText = 'width:100%;height:100%;border:0;';
                const separator = videoUrl.includes('?') ? '&' : '?';
                iframe.src = videoUrl + separator + 'autoplay=1';
                iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
                iframe.allowFullscreen = true;
                container.appendChild(iframe);
            }
        }
        
        handleVariationChange(data) {
            const variationId = data.variationId || data.variation_id || data.id || data.productId || data.product_id;
            
            if (!variationId) return;
            
            const targetIndex = this.variationMap[variationId];
            
            if (targetIndex !== undefined) {
                console.log('✅ Variation jump to:', targetIndex);
                this.jumpToImage(targetIndex);
            }
        }
    }
    
    // Initialize
    function initGalleries() {
        document.querySelectorAll('.pv-gallery').forEach(gallery => {
            new PVGallery(gallery);
        });
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGalleries);
    } else {
        initGalleries();
    }
})();
