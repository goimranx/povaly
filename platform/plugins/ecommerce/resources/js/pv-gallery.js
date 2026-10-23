/**
 * PV Custom Gallery System
 * Same logic as quick-shop offcanvas gallery
 * - Hover to preview
 * - Click to select/activate
 * - Variation change jumps to first variation image
 */
(function() {
    'use strict';
    
    class PVGallery {
        constructor(container) {
            this.container = container;
            this.mainImage = container.querySelector('#pv-main-image');
            this.thumbnails = container.querySelectorAll('.pv-thumb:not(.pv-thumb-video)');
            this.currentIndex = parseInt(container.getAttribute('data-default-index')) || 0;
            this.variationMap = JSON.parse(container.getAttribute('data-variation-map') || '{}');
            this.allImages = [];
            
            this.init();
        }
        
        init() {
            console.log('✅ PV Gallery initialized');
            console.log('📸 Total image thumbnails:', this.thumbnails.length);
            console.log('🗺️ Variation map:', this.variationMap);
            
            // Collect all images data
            this.thumbnails.forEach((thumb, index) => {
                const imageUrl = thumb.getAttribute('data-image');
                const variationId = thumb.getAttribute('data-variation-id');
                const dataIndex = parseInt(thumb.getAttribute('data-index'));
                
                this.allImages.push({
                    index: dataIndex,
                    url: imageUrl,
                    variationId,
                    thumb,
                    domIndex: index
                });
            });
            
            // Set initial active state
            this.setActive(this.currentIndex);
            
            // Bind hover events for preview
            this.thumbnails.forEach((thumb) => {
                thumb.addEventListener('mouseenter', () => {
                    const index = parseInt(thumb.getAttribute('data-index'));
                    this.previewImage(index);
                });
                
                // On click, make it the active/current image
                thumb.addEventListener('click', () => {
                    const index = parseInt(thumb.getAttribute('data-index'));
                    this.jumpToImage(index);
                });
            });
            
            // Listen for variation changes (same event as quick-shop)
            document.addEventListener('ecommerce.change-variation', (e) => {
                console.log('🔄 Variation change event:', e.detail);
                this.handleVariationChange(e.detail);
            });
            
            console.log('✅ PV Gallery ready with', this.allImages.length, 'images');
        }
        
        previewImage(index) {
            // Show image on hover (temporary preview)
            const imageData = this.allImages.find(img => img.index === index);
            if (!imageData || !this.mainImage) return;
            
            this.mainImage.src = imageData.url;
        }
        
        jumpToImage(index) {
            // Permanently switch to this image (on click or variation change)
            const imageData = this.allImages.find(img => img.index === index);
            
            if (!imageData || !this.mainImage) {
                console.warn('⚠️ Image not found for index:', index);
                return;
            }
            
            // Update main image
            this.mainImage.src = imageData.url;
            this.mainImage.srcset = '';
            
            // Update active state
            this.setActive(index);
            
            this.currentIndex = index;
            
            console.log('🖼️ Jumped to image index:', index);
        }
        
        setActive(index) {
            // Remove active from all
            this.thumbnails.forEach(t => t.classList.remove('pv-active'));
            
            // Add active to target
            const imageData = this.allImages.find(img => img.index === index);
            if (imageData && imageData.thumb) {
                imageData.thumb.classList.add('pv-active');
            }
        }
        
        handleVariationChange(data) {
            // Extract variation ID from event (multiple possible property names)
            const variationId = data.variationId 
                              || data.variation_id 
                              || data.id 
                              || data.productId 
                              || data.product_id;
            
            if (!variationId) {
                console.warn('⚠️ No variation ID in event:', data);
                return;
            }
            
            console.log('🔍 Looking for variation:', variationId);
            
            // Use variation map to find first image for this variation
            const targetIndex = this.variationMap[variationId];
            
            if (targetIndex !== undefined) {
                console.log('✅ Found variation image at index:', targetIndex);
                this.jumpToImage(targetIndex);
            } else {
                console.log('⚠️ No specific image for variation:', variationId, '- keeping current image');
            }
        }
    }
    
    // Initialize when DOM is ready
    function initGalleries() {
        const galleries = document.querySelectorAll('.pv-gallery');
        
        galleries.forEach(gallery => {
            new PVGallery(gallery);
        });
        
        console.log('📦 PV Gallery System loaded:', galleries.length, 'galleries');
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGalleries);
    } else {
        initGalleries();
    }
})();
