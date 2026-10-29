/**
 * PV Custom Gallery System
 * - Hover to preview AND activate (no click needed)
 * - Click counter overlay to open lightbox
 * - Variation change jumps to first variation image
 * - Amazon-style image lens zoom on hover
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
                
                // Click opens lightbox only for counter overlay (+N), not regular thumbnails
                thumb.addEventListener('click', (e) => {
                    const overlay = thumb.querySelector('.pv-thumb-overlay');
                    if (overlay) {
                        // Counter overlay - open lightbox
                        e.preventDefault();
                        e.stopPropagation();
                        const index = parseInt(thumb.getAttribute('data-index'));
                        this.openLightboxAtIndex(index);
                    }
                    // Regular thumbnail click - do nothing (just hover to preview)
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
            
            // Always use .pv-gallery-main as the container for the facade
            // On mobile, mainImage is inside .pv-slide-track which has no height after animation
            const rawParent = this.mainImage.parentElement;
            const mainContainer = (rawParent && rawParent.classList.contains('pv-slide-track'))
                ? rawParent.parentElement
                : rawParent;
            if (!mainContainer) return;
            
            // Create or get video preview facade (thumbnail + circled play button)
            let videoFacade = mainContainer.querySelector('.pv-video-facade');
            if (!videoFacade) {
                videoFacade = document.createElement('div');
                videoFacade.className = 'pv-video-facade';
                mainContainer.appendChild(videoFacade);
            }
            videoFacade.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;display:none;cursor:pointer;background:#000;border-radius:10px;overflow:hidden;z-index:5;';
            
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
            
            // Find all images for this variation
            const variationImages = this.allImages.filter(img => img.variationId == variationId);
            
            if (variationImages.length > 0) {
                // Reorganize thumbnails: show variation images first, then others
                this.reorderThumbnails(variationId);
                
                // Jump to first image of this variation
                const firstVariationImage = variationImages[0];
                this.jumpToImage(firstVariationImage.index);
                this.scrollThumbnailsToShow(firstVariationImage.index);
            } else {
                // If variationMap has the index, use it
                let targetIndex = this.variationMap[variationId];
                
                if (targetIndex !== undefined) {
                    this.jumpToImage(targetIndex);
                    this.scrollThumbnailsToShow(targetIndex);
                    return;
                }
                
                // Try to find any image with this variation ID
                const firstVariationImage = this.allImages.find(img => img.variationId == variationId);
                if (firstVariationImage) {
                    this.jumpToImage(firstVariationImage.index);
                    this.scrollThumbnailsToShow(firstVariationImage.index);
                }
            }
        }
        
        reorderThumbnails(variationId) {
            // Get container of thumbnails
            const thumbContainer = this.container.querySelector('.pv-thumb-grid');
            if (!thumbContainer) return;
            
            // Separate variation thumbnails from others
            const variationThumbs = [];
            const otherThumbs = [];
            
            this.thumbnails.forEach(thumb => {
                const thumbVariationId = thumb.getAttribute('data-variation-id');
                if (thumbVariationId == variationId) {
                    variationThumbs.push(thumb);
                } else {
                    otherThumbs.push(thumb);
                }
            });
            
            // Re-append: variation thumbnails first, then others
            variationThumbs.forEach(thumb => thumbContainer.appendChild(thumb));
            otherThumbs.forEach(thumb => thumbContainer.appendChild(thumb));
            
            // Update the thumbnails NodeList reference
            this.thumbnails = thumbContainer.querySelectorAll('.pv-thumb:not(.pv-thumb-video)');
        }
        
        scrollThumbnailsToShow(index) {
            // Scroll the thumbnail grid to make the target thumbnail visible
            const imageData = this.allImages.find(img => img.index === index);
            if (!imageData || !imageData.thumb) return;
            
            const thumb = imageData.thumb;
            const thumbContainer = thumb.parentElement;
            
            if (!thumbContainer) return;
            
            // Smooth scroll the thumbnail into view
            thumb.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });
        }

        // ─── Lens Zoom ────────────────────────────────────────────────────────
        // ─── Mobile Swipe + Dots ─────────────────────────────────────────────
        initMobile() {
            this._mainContainer = this.mainImage ? this.mainImage.parentElement : null;
            if (!this._mainContainer) return;

            // Build flat slide list: images then videos
            this._mobileSlides = [];
            this.allImages.forEach(img => this._mobileSlides.push({ type: 'image', data: img }));
            this.videoThumbs.forEach((vt, i) => this._mobileSlides.push({ type: 'video', index: i, el: vt }));
            this._mobileIndex = this.currentIndex;

            // Dots container
            this._dotsEl = document.createElement('div');
            this._dotsEl.className = 'pv-mobile-dots';
            this._mainContainer.parentElement.insertBefore(this._dotsEl, this._mainContainer.nextSibling);
            this._updateDots();

            // Lock container height immediately and make mainImage fill it absolutely
            const mc = this._mainContainer;
            mc.style.height = mc.offsetHeight + 'px';
            mc._heightLocked = true;
            if (this.mainImage) {
                this.mainImage.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;object-fit:contain;display:block;cursor:crosshair;';
            }

            // Touch
            let startX = 0, startY = 0, isDragging = false, liveEl = null, nextEl = null, prevEl = null;
            const SENSITIVITY = 1.2; // Increase to make swipes more sensitive

            this._mainContainer.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                isDragging = true;
                liveEl = this._getVisibleEl();
                
                // Pre-create next and previous elements for smooth dragging
                const total = this._mobileSlides.length;
                const nextIndex = (this._mobileIndex + 1) % total;
                const prevIndex = (this._mobileIndex - 1 + total) % total;
                
                // Create next element (right)
                nextEl = this._createSlideElement(this._mobileSlides[nextIndex]);
                nextEl.style.left = '100%';
                nextEl.dataset.preloaded = 'true';
                mc.appendChild(nextEl);
                
                // Create prev element (left)
                prevEl = this._createSlideElement(this._mobileSlides[prevIndex]);
                prevEl.style.left = '-100%';
                prevEl.dataset.preloaded = 'true';
                mc.appendChild(prevEl);
            }, { passive: true });

            this._mainContainer.addEventListener('touchmove', (e) => {
                if (!isDragging || !liveEl) return;
                const dx = (e.touches[0].clientX - startX) * SENSITIVITY;
                const dy = e.touches[0].clientY - startY;
                if (Math.abs(dy) > Math.abs(dx / SENSITIVITY)) return;
                
                // Move all three elements together with sensitivity multiplier
                liveEl.style.transform = `translateX(${dx}px)`;
                liveEl.style.transition = 'none';
                
                if (nextEl) {
                    nextEl.style.transform = `translateX(${dx}px)`;
                    nextEl.style.transition = 'none';
                }
                
                if (prevEl) {
                    prevEl.style.transform = `translateX(${dx}px)`;
                    prevEl.style.transition = 'none';
                }
            }, { passive: true });

            this._mainContainer.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                isDragging = false;
                
                const dx = (e.changedTouches[0].clientX - startX) * SENSITIVITY;
                const dy = e.changedTouches[0].clientY - startY;
                
                if (Math.abs(dx) < 30 || Math.abs(dy) > Math.abs(dx / SENSITIVITY)) {
                    // Not a valid swipe - reset everything
                    if (liveEl) { liveEl.style.transform = ''; liveEl.style.transition = ''; }
                    if (nextEl && mc.contains(nextEl)) mc.removeChild(nextEl);
                    if (prevEl && mc.contains(prevEl)) mc.removeChild(prevEl);
                    nextEl = null;
                    prevEl = null;
                    return;
                }
                
                const total = this._mobileSlides.length;
                if (dx < 0) {
                    // Swiping left (next)
                    this._mobileGoTo((this._mobileIndex + 1) % total, 'left', { outEl: liveEl, inEl: nextEl, dx: dx });
                    if (prevEl && mc.contains(prevEl)) mc.removeChild(prevEl);
                } else {
                    // Swiping right (prev)
                    this._mobileGoTo((this._mobileIndex - 1 + total) % total, 'right', { outEl: liveEl, inEl: prevEl, dx: dx });
                    if (nextEl && mc.contains(nextEl)) mc.removeChild(nextEl);
                }
                nextEl = null;
                prevEl = null;
            }, { passive: true });
        }

        // Helper to create a slide element
        _createSlideElement(slide) {
            let el;
            if (slide.type === 'image') {
                el = document.createElement('img');
                el.src = slide.data.url;
                el.alt = this.mainImage ? this.mainImage.alt : '';
                el.style.cssText = 'position:absolute;top:0;width:100%;height:100%;object-fit:contain;display:block;cursor:crosshair;';
            } else {
                const vt = slide.el;
                const thumb = vt.getAttribute('data-video-thumbnail');
                el = document.createElement('div');
                el.className = 'pv-video-facade';
                el.style.cssText = 'position:absolute;top:0;width:100%;height:100%;background:#000;border-radius:10px;overflow:hidden;cursor:pointer;z-index:5;';
                el.innerHTML = thumb
                    ? `<img src="${thumb}" style="width:100%;height:100%;object-fit:contain;display:block;" />`
                    : `<div style="width:100%;height:100%;background:#111;"></div>`;
                el.innerHTML += `<div class="pv-play-button-large"><svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M6 4v16a1 1 0 0 0 1.524 .852l13 -8a1 1 0 0 0 0 -1.704l-13 -8a1 1 0 0 0 -1.524 .852z"/></svg></div>`;
                const vi = Array.from(this.videoThumbs).indexOf(vt);
                el.onclick = (e) => { e.preventDefault(); e.stopPropagation(); this.openLightboxWithVideo(vi); };
            }
            return el;
        }

        // Returns the currently visible element in the main container
        _getVisibleEl() {
            const mc = this._mainContainer;
            if (!mc) return this.mainImage;
            const facade = mc.querySelector('.pv-video-facade');
            if (facade && facade.style.display !== 'none') return facade;
            return this.mainImage;
        }

        _mobileGoTo(index, direction, preloaded = null) {
            const total = this._mobileSlides.length;
            if (total === 0) return;
            index = ((index % total) + total) % total;
            if (index === this._mobileIndex) return;
            // Prevent overlapping animations
            if (this._mobileAnimating) {
                return;
            }
            this._mobileAnimating = true;

            // Store direction for _updateDots to determine animation
            this._swipeDirection = direction;
            
            // Update index immediately so dots reflect new state right away
            this._mobileIndex = index;
            this._updateDots();

            const slide = this._mobileSlides[index];
            const mc    = this._mainContainer;
            if (!mc) { this._mobileAnimating = false; return; }

            // Use preloaded elements if available, otherwise create new ones
            let outEl, inEl;
            
            if (preloaded && preloaded.outEl && preloaded.inEl) {
                // Use pre-created elements from touch gesture
                outEl = preloaded.outEl;
                inEl = preloaded.inEl;
                
                // Clear transforms and prepare for final animation
                outEl.style.transform = '';
                inEl.style.transform = '';
                outEl.style.transition = 'left 0.28s ease';
                inEl.style.transition = 'left 0.28s ease';
                
                // Animate to final positions
                requestAnimationFrame(() => {
                    const fromRight = direction === 'left';
                    inEl.style.left = '0';
                    outEl.style.left = fromRight ? '-100%' : '100%';
                });
            } else {
                // Original behavior - create new elements
                outEl = this._getVisibleEl();

                // Build incoming element
                inEl = this._createSlideElement(slide);

                // Lock container height
                if (!mc._heightLocked) {
                    mc.style.height = mc.offsetHeight + 'px';
                    mc._heightLocked = true;
                }

                // Place inEl off-screen
                const fromRight = direction === 'left';
                inEl.style.left = fromRight ? '100%' : '-100%';
                mc.appendChild(inEl);

                // Pin outEl
                if (outEl) {
                    outEl.style.position = 'absolute';
                    outEl.style.top = '0';
                    outEl.style.left = '0';
                    outEl.style.width = '100%';
                    outEl.style.height = '100%';
                    outEl.style.transition = 'left 0.28s ease';
                }
                inEl.style.transition = 'left 0.28s ease';

                // Animate
                requestAnimationFrame(() => requestAnimationFrame(() => {
                    inEl.style.left = '0';
                    if (outEl) outEl.style.left = fromRight ? '-100%' : '100%';
                }));
            }

            setTimeout(() => {
                // Remove outgoing
                if (outEl && mc.contains(outEl)) mc.removeChild(outEl);

                // Just clear transition — keep position:absolute filling container
                inEl.style.transition = 'none';
                inEl.style.left = '0';

                if (slide.type === 'image') {
                    this.mainImage = inEl;
                    this.mainImage.addEventListener('click', () => this.openLightbox());
                    this.jumpToImage(slide.data.index);
                }
                // For video: inEl is already the facade, leave it

                // Release animation lock
                this._mobileAnimating = false;
            }, 300);
        }

        _updateDots() {
            if (!this._dotsEl) return;
            const total = this._mobileSlides.length;
            if (total <= 1) { this._dotsEl.innerHTML = ''; return; }

            const active = this._mobileIndex;
            const MAX = 5;
            
            // Active dot centered at position 2 (index 2), window shifts to keep it there
            let start = Math.max(0, active - 2);
            let end   = Math.min(total - 1, start + MAX - 1);
            if (end - start < MAX - 1) start = Math.max(0, end - MAX + 1);
            
            // Detect if we're in middle range and should animate
            const prevStart = this._prevStart || 0;
            const isInMiddleRange = (active >= 2) && (active < total - 2);
            const didShift = (start !== prevStart) && isInMiddleRange;
            const direction = this._swipeDirection || 'left';
            this._prevStart = start;

            // For animation: temporarily shift the start window to show active at off-center position
            let tempStart = start;
            let tempEnd = end;
            if (didShift) {
                if (direction === 'left') {
                    // Forward: shift window left so active appears at position 3 (index 3 from tempStart)
                    tempStart = active - 3;
                    tempEnd = tempStart + MAX - 1;
                } else {
                    // Backward: shift window right so active appears at position 1 (index 1 from tempStart)  
                    tempStart = active - 1;
                    tempEnd = tempStart + MAX - 1;
                }
                // Clamp to valid range
                tempStart = Math.max(0, tempStart);
                tempEnd = Math.min(total - 1, tempEnd);
            }

            // Build initial dots (with active at off-center position if animating)
            let html = '';
            for (let i = (didShift ? tempStart : start); i <= (didShift ? tempEnd : end); i++) {
                const dist = Math.abs(i - active);
                const size    = dist === 0 ? 10 : dist === 1 ? 7 : 5;
                const opacity = dist === 0 ? 1   : dist === 1 ? 0.6 : 0.35;
                html += `<span class="pv-dot${i === active ? ' pv-dot--active' : ''}"
                    style="width:${size}px;height:${size}px;opacity:${opacity}"
                    data-index="${i}"
                    data-slide-index="${i}"></span>`;
            }
            
            this._dotsEl.innerHTML = html;
            
            // Animate to final position if needed
            if (didShift) {
                // Build final dots (with active centered at position 2)
                let finalHtml = '';
                for (let i = start; i <= end; i++) {
                    const dist = Math.abs(i - active);
                    const size    = dist === 0 ? 10 : dist === 1 ? 7 : 5;
                    const opacity = dist === 0 ? 1   : dist === 1 ? 0.6 : 0.35;
                    finalHtml += `<span class="pv-dot${i === active ? ' pv-dot--active' : ''}"
                        style="width:${size}px;height:${size}px;opacity:${opacity}"
                        data-index="${i}"
                        data-slide-index="${i}"></span>`;
                }
                
                // Wait a bit so user can SEE the initial state, then transition to final state
                setTimeout(() => {
                    // Add smooth transition class before changing content
                    this._dotsEl.classList.add('pv-dots-transitioning');
                    this._dotsEl.classList.add(direction === 'left' ? 'pv-dots-slide-forward' : 'pv-dots-slide-backward');
                    this._dotsEl.innerHTML = finalHtml;
                    
                    setTimeout(() => {
                        this._dotsEl.classList.remove('pv-dots-slide-forward', 'pv-dots-slide-backward', 'pv-dots-transitioning');
                    }, 400);
                }, 50); // Reduced pause to 50ms for smoother feel
            }
            
            // Re-attach click handlers after final render
            setTimeout(() => {
                this._dotsEl.querySelectorAll('.pv-dot').forEach(dot => {
                    dot.addEventListener('click', () => {
                        const to = parseInt(dot.dataset.index);
                        this._mobileGoTo(to, to > this._mobileIndex ? 'left' : 'right');
                    });
                });
            }, didShift ? 460 : 0);
        }
        // ─────────────────────────────────────────────────────────────────────

        initLens() {
            const mainContainer = this.mainImage ? this.mainImage.parentElement : null;
            if (!mainContainer || !this.mainImage) return;

            // Create lens element
            this.lensEl = document.createElement('div');
            this.lensEl.className = 'pv-lens';
            mainContainer.appendChild(this.lensEl);

            // Create zoom result panel
            this.zoomEl = document.createElement('div');
            this.zoomEl.className = 'pv-zoom-result';
            document.body.appendChild(this.zoomEl);

            this._lensMouseMove = this._onLensMouseMove.bind(this);
            this._lensMouseLeave = this._onLensMouseLeave.bind(this);

            mainContainer.addEventListener('mousemove', this._lensMouseMove);
            mainContainer.addEventListener('mouseleave', this._lensMouseLeave);

            // Crosshair cursor on the whole container
            mainContainer.style.cursor = 'crosshair';

            // Container click opens lightbox (same as image click)
            // Exclude action buttons (share/wishlist/compare) — they handle their own clicks
            mainContainer.addEventListener('click', (e) => {
                if (e.target === this.mainImage) return;
                if (e.target.closest('.pv-gallery-actions, .pv-action-btn')) return;
                this.openLightbox();
            });
        }

        _onLensMouseMove(e) {
            const img = this.mainImage;
            if (!img || img.style.display === 'none') {
                this._onLensMouseLeave();
                return;
            }

            const imgRect = img.getBoundingClientRect();
            const containerRect = img.parentElement.getBoundingClientRect();

            // Cursor position relative to the image
            let x = e.clientX - imgRect.left;
            let y = e.clientY - imgRect.top;

            // If cursor is outside the container entirely, hide and bail
            const cX = e.clientX - containerRect.left;
            const cY = e.clientY - containerRect.top;
            if (cX < 0 || cY < 0 || cX > containerRect.width || cY > containerRect.height) {
                this._onLensMouseLeave();
                return;
            }

            // Clamp x/y to image bounds — lens never leaves the image area
            // but follows cursor even when pointer is in container padding outside img
            x = Math.max(0, Math.min(x, imgRect.width));
            y = Math.max(0, Math.min(y, imgRect.height));

            // ─── Position and size the zoom panel ───────────────────────────────
            // Always 10px from top and bottom of viewport — full height minus gaps
            const GAP = 10;
            const zoomPanelTop    = GAP;
            const zoomPanelHeight = window.innerHeight - GAP * 2;

            // Info-wrapper: sibling of gallery-wrapper inside ps-product__header
            const galleryWrapper = document.querySelector('.ps-product__gallery-wrapper');
            const infoWrapper    = document.querySelector('.ps-product__info-wrapper');

            let zoomLeft, zoomTop, zoomWidth, zoomHeight;

            if (infoWrapper && galleryWrapper) {
                const infoRect = infoWrapper.getBoundingClientRect();
                zoomLeft   = infoRect.left;
                zoomTop    = zoomPanelTop;
                zoomWidth  = infoRect.width;
                zoomHeight = zoomPanelHeight;
            } else {
                // Fallback: position right of the main container
                zoomLeft   = containerRect.right + 10;
                zoomTop    = zoomPanelTop;
                zoomWidth  = Math.min(500, window.innerWidth - zoomLeft - 10);
                zoomHeight = zoomPanelHeight;
            }

            // ─── Lens size: match zoom panel aspect ratio ────────────────────────
            // So the lens area maps 1:1 to what's shown in the zoom panel
            // Base lens width on a comfortable size, derive height from zoom ratio
            const zoomRatio = zoomWidth / zoomHeight; // zoom panel aspect ratio
            // Start with a max lens width and derive height to match ratio
            let lensW = Math.min(200, imgRect.width * 0.45);
            let lensH = lensW / zoomRatio;
            // If height exceeds image, clamp and recompute width
            if (lensH > imgRect.height * 0.45) {
                lensH = imgRect.height * 0.45;
                lensW = lensH * zoomRatio;
            }

            // Clamp lens so it doesn't go outside the image
            let lensLeft = x - lensW / 2;
            let lensTop  = y - lensH / 2;
            lensLeft = Math.max(0, Math.min(lensLeft, imgRect.width  - lensW));
            lensTop  = Math.max(0, Math.min(lensTop,  imgRect.height - lensH));

            // Position lens relative to container (absolute positioning)
            const offsetLeft = imgRect.left - containerRect.left + lensLeft;
            const offsetTop  = imgRect.top  - containerRect.top  + lensTop;

            this.lensEl.style.left    = offsetLeft + 'px';
            this.lensEl.style.top     = offsetTop  + 'px';
            this.lensEl.style.width   = lensW + 'px';
            this.lensEl.style.height  = lensH + 'px';
            this.lensEl.style.display = 'block';

            this.zoomEl.style.left    = zoomLeft   + 'px';
            this.zoomEl.style.top     = zoomTop    + 'px';
            this.zoomEl.style.width   = zoomWidth  + 'px';
            this.zoomEl.style.height  = zoomHeight + 'px';
            this.zoomEl.style.display = 'block';

            // ─── Compute background-size and background-position for zoom ───────
            // Zoom ratio: how much bigger the result is vs. the lens
            const ratioX = zoomWidth  / lensW;
            const ratioY = zoomHeight / lensH;

            const bgW = imgRect.width  * ratioX;
            const bgH = imgRect.height * ratioY;

            // The lens top-left in image coords maps to background offset
            const bgX = lensLeft * ratioX;
            const bgY = lensTop  * ratioY;

            this.zoomEl.style.backgroundImage    = 'url(' + img.src + ')';
            this.zoomEl.style.backgroundSize     = bgW + 'px ' + bgH + 'px';
            this.zoomEl.style.backgroundPosition = '-' + bgX + 'px -' + bgY + 'px';
        }

        _onLensMouseLeave() {
            if (this.lensEl) this.lensEl.style.display = 'none';
            if (this.zoomEl) this.zoomEl.style.display = 'none';
        }

        // Destroy lens when gallery is re-initialized (e.g. variation switch)
        destroyLens() {
            const mainContainer = this.mainImage ? this.mainImage.parentElement : null;
            if (mainContainer) {
                mainContainer.removeEventListener('mousemove', this._lensMouseMove);
                mainContainer.removeEventListener('mouseleave', this._lensMouseLeave);
            }
            if (this.lensEl)   this.lensEl.remove();
            if (this.zoomEl)   this.zoomEl.remove();
        }
        // ─────────────────────────────────────────────────────────────────────
    }
    
    // Initialize
    function initGalleries() {
        document.querySelectorAll('.pv-gallery').forEach(gallery => {
            new PVGallery(gallery);
        });
        
        // Initialize share button
        initShareButton();
        
        // Initialize wishlist/compare button handlers
        initActionButtons();
    }
    
    // Share button handler
    function initShareButton() {
        document.querySelectorAll('.pv-share-btn').forEach(btn => {
            if (btn._pvShareBound) return;
            btn._pvShareBound = true;
            
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const url = this.getAttribute('data-url');
                const title = this.getAttribute('data-title');
                
                // Use native share API if available
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        url: url
                    }).catch((error) => console.log('Error sharing:', error));
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(url).then(() => {
                        // Show success notification
                        if (typeof Theme !== 'undefined' && typeof Theme.showSuccess === 'function') {
                            Theme.showSuccess('Link copied to clipboard!');
                        } else {
                            alert('Link copied to clipboard!');
                        }
                    });
                }
            });
        });
    }
    
    // Wishlist and Compare button handlers
    function initActionButtons() {
        // Use the EXACT same optimistic update approach as flyout buttons (product-card-enhanced.js)
        
        /**
         * Update icon state (same function as product-card-enhanced.js)
         */
        function updateIconState($button, isFilled) {
            var $icon = $button.find('i.ph');
            
            if (isFilled) {
                // Add ph-fill class for filled state
                if (!$icon.hasClass('ph-fill')) {
                    $icon.addClass('ph-fill');
                }
            } else {
                // Remove ph-fill class for outline state
                $icon.removeClass('ph-fill');
            }
        }
        
        // Intercept wishlist add - update icon immediately (optimistic update)
        $(document).on('click', '.pv-action-btn.js-add-to-wishlist-button', function(e) {
            var $btn = $(this);
            var productId = $btn.data('product-id');
            
            // Optimistically update icon (instant visual feedback)
            updateIconState($btn, true);
            
            // Update ALL wishlist buttons for this product (including flyout buttons)
            $('.ec-card__float-btn--wishlist[data-product-id="' + productId + '"], .pv-action-btn.js-add-to-wishlist-button[data-product-id="' + productId + '"], .pv-action-btn.js-remove-from-wishlist-button[data-product-id="' + productId + '"]').each(function() {
                updateIconState($(this), true);
            });
        });
        
        // Intercept wishlist remove - update icon immediately (optimistic update)
        $(document).on('click', '.pv-action-btn.js-remove-from-wishlist-button', function(e) {
            var $btn = $(this);
            var productId = $btn.data('product-id');
            
            // Optimistically update icon (instant visual feedback)
            updateIconState($btn, false);
            
            // Update ALL wishlist buttons for this product (including flyout buttons)
            $('.ec-card__float-btn--wishlist[data-product-id="' + productId + '"], .pv-action-btn.js-add-to-wishlist-button[data-product-id="' + productId + '"], .pv-action-btn.js-remove-from-wishlist-button[data-product-id="' + productId + '"]').each(function() {
                updateIconState($(this), false);
            });
        });
        
        // Intercept compare add - update icon immediately (optimistic update)
        $(document).on('click', '.pv-action-btn.js-add-to-compare-button', function(e) {
            var $btn = $(this);
            var productId = $btn.data('product-id');
            
            // Optimistically update icon (instant visual feedback)
            updateIconState($btn, true);
            
            // Update ALL compare buttons for this product (including flyout buttons)
            $('.ec-card__float-btn--compare[data-product-id="' + productId + '"], .pv-action-btn.js-add-to-compare-button[data-product-id="' + productId + '"], .pv-action-btn.js-remove-from-compare-button[data-product-id="' + productId + '"]').each(function() {
                updateIconState($(this), true);
            });
        });
        
        // Intercept compare remove - update icon immediately (optimistic update)
        $(document).on('click', '.pv-action-btn.js-remove-from-compare-button', function(e) {
            var $btn = $(this);
            var productId = $btn.data('product-id');
            
            // Optimistically update icon (instant visual feedback)
            updateIconState($btn, false);
            
            // Update ALL compare buttons for this product (including flyout buttons)
            $('.ec-card__float-btn--compare[data-product-id="' + productId + '"], .pv-action-btn.js-add-to-compare-button[data-product-id="' + productId + '"], .pv-action-btn.js-remove-from-compare-button[data-product-id="' + productId + '"]').each(function() {
                updateIconState($(this), false);
            });
        });
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGalleries);
    } else {
        initGalleries();
    }
})();
