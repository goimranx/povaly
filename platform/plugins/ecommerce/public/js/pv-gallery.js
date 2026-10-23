/**
 * PV Custom Gallery System
 * Handles product gallery with variation image support
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
            
            this.init();
        }
        
        init() {
            console.log('✅ PV Gallery initialized');
            console.log('📸 Total thumbnails:', this.thumbnails.length);
            
            // Collect all images data
            this.thumbnails.forEach((thumb, index) => {
                const imageUrl = thumb.getAttribute('data-image');
                const variationId = thumb.getAttribute('data-variation-id');
                const isVideo = thumb.classList.contains('pv-thumb-video');
                
                this.allImages.push({
                    index,
                    url: imageUrl,
                    variationId,
                    isVideo,
                    thumb
                });
            });
            
            // Bind click events
            this.thumbnails.forEach((thumb, index) => {
                thumb.addEventListener('click', () => this.handleThumbClick(index));
            });
            
            // Listen for variation changes
            document.addEventListener('ecommerce.change-variation', (e) => {
                this.handleVariationChange(e.detail);
            });
        }
        
        handleThumbClick(index) {
            const imageData = this.allImages[index];
            
            if (!imageData || imageData.isVideo) {
                console.log('🎥 Video thumbnail clicked - handle separately');
                return;
            }
            
            this.jumpToImage(index);
        }
        
        jumpToImage(index) {
            const imageData = this.allImages[index];
            
            if (!imageData || !this.mainImage) return;
            
            // Update main image
            this.mainImage.src = imageData.url;
            this.mainImage.srcset = '';
            
            // Update active state
            this.thumbnails.forEach(t => t.classList.remove('pv-active'));
            imageData.thumb.classList.add('pv-active');
            
            this.currentIndex = index;
            
            console.log('🖼️ Jumped to image:', index);
        }
        
        handleVariationChange(data) {
            console.log('🔄 Variation changed:', data);
            
            const variationId = data.variationId || data.variation_id || data.id;
            
            if (!variationId) return;
            
            // Find first image for this variation
            const variationImage = this.allImages.find(img => 
                img.variationId && String(img.variationId) === String(variationId)
            );
            
            if (variationImage) {
                console.log('✅ Found variation image, jumping to index:', variationImage.index);
                this.jumpToImage(variationImage.index);
            } else {
                console.log('⚠️ No specific image found for variation:', variationId);
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
