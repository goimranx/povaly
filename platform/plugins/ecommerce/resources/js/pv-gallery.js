/**
 * PV Custom Gallery System
 * Handles product gallery with variation image support
 * Shows first 4 images visible (with +N counter on 4th if more exist)
 * Shows first 2 videos visible (with +N counter on 2nd if more exist)
 */
(function() {
    'use strict';
    
    class PVGallery {
        constructor(container) {
            this.container = container;
            this.mainImage = container.querySelector('#pv-main-image');
            this.thumbnails = container.querySelectorAll('.pv-thumb');
            this.currentIndex = 0;
            this.allImages = [];
            this.variationMap = {}; // Maps variation ID to first image index
            
            this.init();
        }
        
        init() {
            console.log('✅ PV Gallery initialized');
            console.log('📸 Total thumbnails:', this.thumbnails.length);
            
            // Collect all images data
            this.thumbnails.forEach((thumb, index) => {
                if (thumb.classList.contains('pv-thumb-video')) {
                    return; // Skip videos for image data
                }
                
                const imageUrl = thumb.getAttribute('data-image');
                const variationId = thumb.getAttribute('data-variation-id');
                
                this.allImages.push({
                    index,
                    url: imageUrl,
                    variationId,
                    thumb
                });
                
                // Map variation ID to first occurrence
                if (variationId && !this.variationMap[variationId]) {
                    this.variationMap[variationId] = index;
                }
            });
            
            console.log('🗺️ Variation map:', this.variationMap);
            
            // Bind click events to ALL thumbnails (including hidden ones)
            this.thumbnails.forEach((thumb, index) => {
                thumb.addEventListener('click', () => this.handleThumbClick(index));
            });
            
            // Listen for variation changes
            document.addEventListener('ecommerce.change-variation', (e) => {
                console.log('🔄 Variation change event:', e.detail);
                this.handleVariationChange(e.detail);
            });
        }
        
        handleThumbClick(index) {
            const thumb = this.thumbnails[index];
            
            if (!thumb) return;
            
            // Handle video thumbnails differently
            if (thumb.classList.contains('pv-thumb-video')) {
                console.log('🎥 Video thumbnail clicked');
                // Future: handle video modal/lightbox
                return;
            }
            
            this.jumpToImage(index);
        }
        
        jumpToImage(index) {
            const imageData = this.allImages.find(img => img.index === index);
            
            if (!imageData || !this.mainImage) {
                console.warn('⚠️ Image not found for index:', index);
                return;
            }
            
            // Update main image
            this.mainImage.src = imageData.url;
            this.mainImage.srcset = '';
            
            // Update active state - remove from all, add to clicked
            this.thumbnails.forEach(t => t.classList.remove('pv-active'));
            imageData.thumb.classList.add('pv-active');
            
            this.currentIndex = index;
            
            console.log('🖼️ Jumped to image index:', index);
        }
        
        handleVariationChange(data) {
            // Try different property names that might contain the variation ID
            const variationId = data.variationId 
                              || data.variation_id 
                              || data.id 
                              || data.productId 
                              || data.product_id;
            
            if (!variationId) {
                console.warn('⚠️ No variation ID in event data:', data);
                return;
            }
            
            console.log('🔍 Looking for variation ID:', variationId);
            
            // Find first image for this variation
            const targetIndex = this.variationMap[variationId];
            
            if (targetIndex !== undefined) {
                console.log('✅ Found variation image at index:', targetIndex);
                this.jumpToImage(targetIndex);
            } else {
                console.log('⚠️ No specific image found for variation:', variationId);
                // Optionally jump to first image as fallback
                // this.jumpToImage(0);
            }
        }
    }
    
    // Initialize when DOM is ready
    function initGalleries() {
        const galleries = document.querySelectorAll('.pv-gallery');
        
        galleries.forEach(gallery => {
            new PVGallery(gallery);
        });
        
        console.log('📦 PV Gallery System loaded, initialized', galleries.length, 'galleries');
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGalleries);
    } else {
        initGalleries();
    }
})();
