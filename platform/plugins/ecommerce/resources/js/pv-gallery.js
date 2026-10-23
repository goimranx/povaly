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
            
            // Set initial active state
            this.setActive(this.currentIndex);
            
            // Bind hover and click events for IMAGE thumbnails
            this.thumbnails.forEach((thumb) => {
                // Hover activates immediately
                thumb.addEventListener('mouseenter', () => {
                    const index = parseInt(thumb.getAttribute('data-index'));
                    this.jumpToImage(index);
                });
                
                // Click on thumbnail with overlay opens lightbox
                thumb.addEventListener('click', (e) => {
                    const overlay = thumb.querySelector('.pv-thumb-overlay');
                    if (overlay) {
                        e.preventDefault();
                        e.stopPropagation();
                        // Click the counter opens lightbox at that index
                        this.openLightboxAtIndex(parseInt(thumb.getAttribute('data-index')));
                    }
                });
            });
            
            // Bind hover and click events for VIDEO thumbnails
            this.videoThumbs.forEach((videoThumb) => {
                // Hover shows video preview in main area
                videoThumb.addEventListener('mouseenter', () => {
                    this.showVideoPreview(videoThumb);
                });
                
                // Click on video thumbnail with overlay opens lightbox
                videoThumb.addEventListener('click', (e) => {
                    const overlay = videoThumb.querySelector('.pv-thumb-overlay');
                    if (overlay) {
                        e.preventDefault();
                        e.stopPropagation();
                        // For videos, open the lightbox (videos are after all images)
                        const videoIndex = parseInt(videoThumb.getAttribute('data-index').replace('video-', ''));
                        const imageCount = this.allImages.length;
                        this.openLightboxAtIndex(imageCount + videoIndex);
                    }
                });
            });
            
            // Listen for variation changes
            document.addEventListener('ecommerce.change-variation', (e) => {
                this.handleVariationChange(e.detail);
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
        
        openLightbox() {
            // Trigger main image click to open existing lightbox
            if (this.mainImage) {
                this.mainImage.click();
            }
        }
        
        openLightboxAtIndex(index) {
            // Find the image/video at this index and click it to open lightbox
            const imageThumb = this.allImages.find(img => img.index === index);
            if (imageThumb && imageThumb.thumb) {
                // Click the thumbnail's img to trigger lightbox
                const img = imageThumb.thumb.querySelector('img');
                if (img) {
                    img.click();
                    return;
                }
            }
            
            // Fallback: just open lightbox at current position
            this.openLightbox();
        }
        
        showVideoPreview(videoThumb) {
            const videoUrl = videoThumb.getAttribute('data-video-url');
            const videoProvider = videoThumb.getAttribute('data-video-provider');
            
            if (!videoUrl || !this.mainImage) return;
            
            const mainContainer = this.mainImage.parentElement;
            if (!mainContainer) return;
            
            // Create or get video preview container
            let videoPreview = mainContainer.querySelector('.pv-video-preview');
            if (!videoPreview) {
                videoPreview = document.createElement('div');
                videoPreview.className = 'pv-video-preview';
                videoPreview.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;background:#000;display:none;border-radius:10px;overflow:hidden;';
                mainContainer.appendChild(videoPreview);
            }
            
            // Clear previous content
            videoPreview.innerHTML = '';
            
            // Create appropriate video element based on provider
            if (videoProvider === 'video') {
                // Direct video file
                const video = document.createElement('video');
                video.controls = true;
                video.style.cssText = 'width:100%;height:100%;object-fit:contain;';
                video.src = videoUrl;
                videoPreview.appendChild(video);
            } else if (videoProvider === 'youtube' || videoProvider === 'vimeo') {
                // Embedded iframe
                const iframe = document.createElement('iframe');
                iframe.style.cssText = 'width:100%;height:100%;border:0;';
                iframe.src = videoUrl;
                iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
                iframe.allowFullscreen = true;
                videoPreview.appendChild(iframe);
            }
            
            // Hide main image, show video preview
            this.mainImage.style.display = 'none';
            videoPreview.style.display = 'block';
            
            // Set active state on video thumbnail
            this.videoThumbs.forEach(v => v.classList.remove('pv-active'));
            videoThumb.classList.add('pv-active');
            
            // Hide video preview when hovering back to image thumbnails
            const resetVideo = () => {
                if (videoPreview) videoPreview.style.display = 'none';
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
