{{-- Product Lightbox Scripts - Standalone Component --}}
<script>
// PRODUCT LIGHTBOX MODAL
// ═══════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {
    var lightbox = document.getElementById('product-lightbox-modal');
    if (!lightbox) {
        console.warn('Lightbox: #product-lightbox-modal not found in DOM');
        return;
    }
    
    console.log('Lightbox: Initializing lightbox modal...');
    
    var mainImage = document.getElementById('lightbox-main-image');
    var imageWrapper = mainImage.parentElement;
    var thumbnailsContainer = document.getElementById('lightbox-thumbnails');
    var productTitle = document.getElementById('lightbox-product-title');
    var closeBtn = lightbox.querySelector('.product-lightbox-close');
    var tabs = lightbox.querySelectorAll('.product-lightbox-tab');
    var imagesContent = document.getElementById('lightbox-images-content');
    var videosContent = document.getElementById('lightbox-videos-content');
    
    var currentProduct = null;
    var isZoomed = false;
    var zoomLevel = 2;
    
    // Cache for video like/share data to prevent delays on video switches
    var videoDataCache = {
        likes: {}, // videoId: {user_liked, total_likes, timestamp}
        shares: {} // videoId: {total_shares, timestamp}
    };
    
    // Open lightbox
    window.openProductLightbox = function(productData) {
        currentProduct = productData;
        productTitle.textContent = productData.name || 'Product';
        
        // Determine which tab to open
        var defaultTab = productData.openTab || 'images';
        
        // Handle Images tab content
        if (productData.images && productData.images.length > 0) {
            var startIndex = productData.currentIndex || 0;
            mainImage.src = productData.images[startIndex];
            loadThumbnails(productData.images, startIndex);
        }
        
        // Handle Videos tab content
        if (productData.videos && productData.videos.length > 0) {
            var videoIndex = productData.currentVideoIndex || 0;
            loadVideos(productData.videos, videoIndex);
            
            // Load video lists in sidebar
            loadVideoLists(
                productData.videos || [],
                productData.relatedVideos || [],
                productData.reviewVideos || [],
                videoIndex
            );
        }
        
        // Set variations if provided
        var variationsContainer = document.getElementById('lightbox-product-variations');
        if (productData.variations && variationsContainer) {
            variationsContainer.innerHTML = productData.variations;
        } else if (variationsContainer) {
            variationsContainer.innerHTML = '';
        }
        
        // Switch to the appropriate tab
        switchTab(defaultTab);
        
        // Show lightbox
        lightbox.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Show hint message on mobile every time lightbox opens (only on images tab)
        if (window.innerWidth <= 767) {
            if (defaultTab === 'images') {
                showHintMessage();
            } else if (defaultTab === 'videos') {
                // Initialize mobile video controls when opening directly on video
                initializeMobileVideoControls();
            }
        }
    };
    
    // Show hint message - Mobile only
    function showHintMessage() {
        var hintMessage = document.getElementById('lightbox-hint-message');
        if (hintMessage) {
            hintMessage.style.opacity = '1';
            // Hide after 3 seconds
            setTimeout(function() {
                hintMessage.style.opacity = '0';
            }, 3000);
        }
    }
    
    // Stop all videos (YouTube, Vimeo, direct video)
    function stopAllVideos() {
        var videoPlayer = document.getElementById('lightbox-video-player');
        if (!videoPlayer) return;
        
        // Stop YouTube videos
        var youtubeIframes = videoPlayer.querySelectorAll('iframe[src*="youtube.com"]');
        youtubeIframes.forEach(function(iframe) {
            var src = iframe.src;
            iframe.src = ''; // Clear source to stop
            iframe.src = src.replace('autoplay=1', 'autoplay=0'); // Reset autoplay
        });
        
        // Stop Vimeo videos
        var vimeoIframes = videoPlayer.querySelectorAll('iframe[src*="vimeo.com"]');
        vimeoIframes.forEach(function(iframe) {
            var src = iframe.src;
            iframe.src = '';
            iframe.src = src.replace('autoplay=1', 'autoplay=0');
        });
        
        // Stop direct video elements
        var videoElements = videoPlayer.querySelectorAll('video');
        videoElements.forEach(function(video) {
            video.pause();
            video.currentTime = 0;
        });
    }
    
    // ═══════════════════════════════════════════════════════════════════
    // MOBILE VIDEO UI ELEMENTS (title, uploader, actions, drawer)
    // ═══════════════════════════════════════════════════════════════════
    
    // Auto-hide UI timer
    var uiHideTimer = null;
    var UI_HIDE_DELAY = 5000; // 5 seconds
    
    // Show UI elements (title, uploader, actions, drawer)
    function showMobileUI() {
        var mobileControls = document.getElementById('mobile-video-controls');
        var drawer = document.getElementById('mobile-video-drawer');
        
        if (mobileControls) {
            mobileControls.classList.remove('controls-hidden'); // 'controls-hidden' hides UI elements
            resetUIHideTimer();
        }
        
        // Show drawer handle when UI is visible (unless drawer is open)
        if (drawer) {
            if (!drawer.classList.contains('open')) {
                // Show the handle
                drawer.style.display = 'flex';
                drawer.style.visibility = 'visible';
            }
        }
    }
    
    // Hide UI elements (title, uploader, actions, drawer)
    function hideMobileUI() {
        var mobileControls = document.getElementById('mobile-video-controls');
        var drawer = document.getElementById('mobile-video-drawer');
        
        if (mobileControls) {
            mobileControls.classList.add('controls-hidden'); // Hides UI, shows minimal buttons
        }
        
        // Hide drawer completely when UI is hidden (unless it's open)
        if (drawer) {
            if (!drawer.classList.contains('open')) {
                // Force hide the drawer
                drawer.style.display = 'none';
                drawer.style.visibility = 'hidden';
            }
        }
    }
    
    // Reset auto-hide timer
    function resetUIHideTimer() {
        if (uiHideTimer) {
            clearTimeout(uiHideTimer);
        }
        uiHideTimer = setTimeout(function() {
            hideMobileUI();
        }, UI_HIDE_DELAY);
    }
    
    // Toggle UI on tap
    function toggleMobileUI() {
        var mobileControls = document.getElementById('mobile-video-controls');
        if (mobileControls) {
            if (mobileControls.classList.contains('controls-hidden')) {
                // UI is hidden, show it
                console.log('📱 [TOGGLE UI] Showing UI');
                showMobileUI();
            } else {
                // UI is visible, hide it immediately (no timer)
                console.log('📱 [TOGGLE UI] Hiding UI');
                hideMobileUI();
                if (uiHideTimer) {
                    clearTimeout(uiHideTimer);
                }
            }
        } else {
            console.log('❌ [TOGGLE UI] Mobile controls element not found');
        }
    }
    
    // Format numbers (e.g., 1500 -> 1.5K, 1000000 -> 1M)
    function formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
        }
        if (num >= 1000) {
            return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'K';
        }
        return num.toString();
    }
    
    // Initialize mobile video controls (called when video tab is active on mobile)
    function initializeMobileVideoControls() {
        if (window.innerWidth > 767) return; // Desktop only
        if (!currentProduct || !currentProduct.videos || currentProduct.videos.length === 0) return;
        
        console.log('✅ [MOBILE VIDEO] Initializing mobile video controls');
        
        var videoIndex = currentProduct.currentVideoIndex || 0;
        var video = currentProduct.videos[videoIndex];
        if (!video) return;
        
        var mobileControls = document.getElementById('mobile-video-controls');
        var mobileDrawer = document.getElementById('mobile-video-drawer');
        var lightboxOverlay = document.getElementById('product-lightbox-modal');
        
        // Show mobile controls and drawer
        if (mobileControls) {
            mobileControls.style.display = 'block';
            mobileControls.classList.remove('controls-hidden'); // Start visible
        }
        if (mobileDrawer) {
            mobileDrawer.style.display = 'flex';
            mobileDrawer.classList.remove('open'); // Start closed
        }
        
        // Add video-mode class for black background
        if (lightboxOverlay) lightboxOverlay.classList.add('video-mode');
        
        // Set up tap-to-toggle on the ENTIRE mobile controls overlay (not just video wrapper)
        // This covers the whole screen except buttons
        // ALWAYS reattach to ensure it works (removeEventListener handles cleanup)
        console.log('👆 [TAP-TO-TOGGLE] Setting up tap handler on mobile controls overlay');
        
        // Remove old handler if exists (cleanup)
        if (mobileControls._tapHandler) {
            console.log('🧹 [TAP-TO-TOGGLE] Removing old handler');
            mobileControls.removeEventListener('click', mobileControls._tapHandler);
        }
        
        // Create new handler
        var mobileControlsHandler = function(e) {
            var target = e.target;
            
            console.log('👆 [TAP-TO-TOGGLE] Click detected on:', {
                tag: target.tagName,
                class: target.className,
                id: target.id,
                text: target.textContent ? target.textContent.substring(0, 30) : ''
            });
            
            // DON'T toggle if clicking on buttons or interactive elements
            if (target.closest('button') ||
                target.closest('.mobile-video-header') ||
                target.closest('.mobile-video-actions') ||
                target.closest('.mobile-video-minimal-controls') ||
                target.closest('.mobile-video-drawer') ||
                target.closest('.product-lightbox-video-interactions') ||
                target.closest('.btn-video-like') ||
                target.closest('.btn-video-share') ||
                target.closest('a') ||
                target.closest('[onclick]')) {
                console.log('⏭️ [TAP-TO-TOGGLE] Skipping - click on interactive element');
                return;
            }
            
            // Toggle UI for any other click (including video, title, uploader, empty space)
            console.log('✅ [TAP-TO-TOGGLE] Calling toggleMobileUI()');
            e.preventDefault(); // Prevent any default behavior
            e.stopPropagation(); // Stop event bubbling
            toggleMobileUI();
        };
        
        // Store handler reference for cleanup
        mobileControls._tapHandler = mobileControlsHandler;
        
        // Attach handler
        console.log('👆 [TAP-TO-TOGGLE] Attaching click handler to:', mobileControls.id);
        mobileControls.addEventListener('click', mobileControlsHandler, true); // Use capture phase
        
        console.log('✅ [TAP-TO-TOGGLE] Handler attached successfully');
        
        // Show controls initially and start auto-hide timer
        showMobileUI();
        
        // Load video lists in drawer (CRITICAL - was missing after tab switch)
        console.log('📹 [MOBILE VIDEO] Loading video lists into drawer');
        loadVideoLists(
            currentProduct.videos || [],
            currentProduct.relatedVideos || [],
            currentProduct.reviewVideos || [],
            videoIndex
        );
        
        // Set product title
        var mobileTitle = document.getElementById('mobile-video-title');
        if (mobileTitle && currentProduct) {
            mobileTitle.textContent = currentProduct.name || 'Product Video';
            mobileTitle.classList.remove('expanded');
            
            // Check if title overflows 2 lines and show chevron only if it does
            setTimeout(function() {
                var titleExpandBtn = document.getElementById('mobile-video-title-expand');
                if (titleExpandBtn) {
                    // Check if text is truncated (scrollHeight > clientHeight means overflow)
                    var isOverflowing = mobileTitle.scrollHeight > mobileTitle.clientHeight;
                    
                    if (isOverflowing) {
                        titleExpandBtn.classList.add('show'); // Show chevron when overflow
                    } else {
                        titleExpandBtn.classList.remove('show'); // Hide chevron when no overflow
                    }
                    titleExpandBtn.classList.remove('expanded');
                }
            }, 100);
        }
        
        // Title expand/collapse
        var titleExpand = document.getElementById('mobile-video-title-expand');
        if (titleExpand && mobileTitle) {
            titleExpand.onclick = function(e) {
                e.stopPropagation(); // Prevent triggering toggle
                mobileTitle.classList.toggle('expanded');
                titleExpand.classList.toggle('expanded');
                resetUIHideTimer(); // Reset timer when interacting
            };
        }
        
        // Title click also expands/collapses (only if overflow)
        if (mobileTitle) {
            mobileTitle.onclick = function(e) {
                e.stopPropagation(); // Prevent triggering toggle
                var titleExpandBtn = document.getElementById('mobile-video-title-expand');
                // Only allow expand if chevron is visible (meaning there's overflow)
                if (titleExpandBtn && titleExpandBtn.classList.contains('show')) {
                    mobileTitle.classList.toggle('expanded');
                    titleExpandBtn.classList.toggle('expanded');
                    resetUIHideTimer(); // Reset timer when interacting
                }
            };
        }
        
        // Set uploader info
        var mobileUploaderName = document.getElementById('mobile-uploader-name');
        var mobileUploaderAvatar = document.getElementById('mobile-uploader-avatar');
        if (mobileUploaderName && mobileUploaderAvatar) {
            var uploaderName = video.store_name || video.uploader || 'Povaly Product Visuals';
            
            // Check if it's "Povaly Product Visuals" - use site logo (SEO graph image)
            if (uploaderName === 'Povaly Product Visuals') {
                // Get the site logo/SEO image from meta tags or theme settings
                var siteLogo = document.querySelector('meta[property="og:image"]')?.content || 
                               document.querySelector('link[rel="icon"]')?.href ||
                               '/themes/martfury/images/logo.png'; // Fallback to theme logo
                
                mobileUploaderAvatar.innerHTML = '<img src="' + siteLogo + '" alt="Povaly" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
            } else if (video.store_logo) {
                // Use store logo if available
                mobileUploaderAvatar.innerHTML = '<img src="' + video.store_logo + '" alt="' + uploaderName + '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
            } else {
                // Fallback to icon
                mobileUploaderAvatar.innerHTML = '<i class="icon-user"></i>';
            }
            
            // Limit to 16 characters with ellipsis
            if (uploaderName.length > 16) {
                uploaderName = uploaderName.substring(0, 16) + '...';
            }
            mobileUploaderName.textContent = uploaderName;
        }
        
        // Initialize action buttons
        initializeMobileLikeButton(video);
        initializeMobileBuyButton();
        initializeMobileCartButton();
        initializeMobileShareButton(video);
        
        // Initialize minimal controls (Share and More videos)
        initializeMinimalControls(video);
        
        // Initialize close button
        var mobileClose = document.querySelector('.mobile-video-close');
        if (mobileClose) {
            mobileClose.onclick = function(e) {
                e.stopPropagation(); // Prevent triggering toggle
                closeLightbox();
            };
        }
        
        // Initialize drawer
        initializeMobileDrawer();
    }
    
    // Initialize minimal controls (Share and More videos buttons)
    function initializeMinimalControls(video) {
        var minimalShareBtn = document.getElementById('minimal-share-btn');
        var minimalMoreBtn = document.getElementById('minimal-more-btn');
        var drawer = document.getElementById('mobile-video-drawer');
        
        if (minimalShareBtn) {
            // Get video ID - CRITICAL: Use proper video ID format (same as mobile share button)
            var videoId;
            if (video.id) {
                videoId = video.id;
            } else if (video.provider === 'youtube') {
                var youtubeMatch = video.url.match(/embed\/([^?]+)/);
                videoId = youtubeMatch ? 'youtube_' + youtubeMatch[1] : btoa(video.url);
            } else if (video.provider === 'vimeo') {
                var vimeoMatch = video.url.match(/video\/(\d+)/);
                videoId = vimeoMatch ? 'vimeo_' + vimeoMatch[1] : btoa(video.url);
            } else {
                var cleanUrl = video.url.split('?')[0];
                videoId = btoa(cleanUrl);
            }
            var videoUrl = video.url;
            
            minimalShareBtn.onclick = function(e) {
                e.stopPropagation(); // Prevent triggering toggle
                
                // Get current share count from cache or mobile button
                var mobileShareCount = document.getElementById('mobile-share-count');
                var currentCount = videoDataCache.shares[videoId] ? 
                    videoDataCache.shares[videoId].total_shares : 
                    (mobileShareCount ? parseInt(mobileShareCount.textContent) || 0 : 0);
                
                // Optimistically increment share count IMMEDIATELY
                var newCount = currentCount + 1;
                
                // Update cache optimistically
                videoDataCache.shares[videoId] = {
                    total_shares: newCount,
                    timestamp: Date.now(),
                    optimistic: true
                };
                
                // Update mobile share count if visible
                if (mobileShareCount) {
                    mobileShareCount.textContent = newCount;
                }
                
                console.log('🔗 [MINIMAL SHARE] Optimistic increment:', currentCount, '→', newCount);
                
                // Track share with server
                var sharePromise;
                
                if (navigator.share && videoUrl) {
                    // Native share API
                    sharePromise = navigator.share({
                        title: currentProduct?.name || 'Product Video',
                        text: 'Check out this video!',
                        url: videoUrl
                    })
                    .then(function() {
                        console.log('✅ [MINIMAL SHARE] Native share completed');
                        
                        // Send to server
                        return fetch('/api/video-shares', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({
                                video_id: videoId,
                                video_url: videoUrl,
                                product_id: currentProduct?.id || null
                            })
                        });
                    })
                    .catch(function(err) {
                        // User cancelled share - rollback
                        console.log('❌ [MINIMAL SHARE] Share cancelled, rolling back');
                        
                        videoDataCache.shares[videoId] = {
                            total_shares: currentCount,
                            timestamp: Date.now(),
                            optimistic: false
                        };
                        
                        if (mobileShareCount) {
                            mobileShareCount.textContent = currentCount;
                        }
                        
                        throw err; // Re-throw to skip .then
                    });
                } else if (videoUrl) {
                    // Clipboard fallback
                    sharePromise = navigator.clipboard.writeText(videoUrl).then(function() {
                        console.log('✅ [MINIMAL SHARE] Copied to clipboard');
                        
                        // Show feedback
                        var btnText = minimalShareBtn.querySelector('span');
                        var originalText = btnText.textContent;
                        btnText.textContent = 'Copied!';
                        setTimeout(function() {
                            btnText.textContent = originalText;
                        }, 2000);
                        
                        // Send to server
                        return fetch('/api/video-shares', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({
                                video_id: videoId,
                                video_url: videoUrl,
                                product_id: currentProduct?.id || null
                            })
                        });
                    });
                }
                
                // Handle server response
                if (sharePromise) {
                    sharePromise
                        .then(function(response) {
                            if (response) return response.json();
                        })
                        .then(function(data) {
                            if (data) {
                                console.log('🔄 [MINIMAL SHARE] Server response:', data);
                                
                                // Update cache with server's authoritative value
                                videoDataCache.shares[videoId] = {
                                    total_shares: data.total_shares || newCount,
                                    timestamp: Date.now(),
                                    optimistic: false
                                };
                                
                                // Update UI if different from optimistic value
                                if (mobileShareCount && data.total_shares !== newCount) {
                                    mobileShareCount.textContent = data.total_shares;
                                    console.log('🔄 [MINIMAL SHARE] Reconciled:', newCount, '→', data.total_shares);
                                }
                            }
                        })
                        .catch(function(err) {
                            if (err.name !== 'AbortError') {
                                console.error('❌ [MINIMAL SHARE] Server error:', err);
                            }
                        });
                }
            };
        }
        
        if (minimalMoreBtn && drawer) {
            minimalMoreBtn.onclick = function(e) {
                e.stopPropagation(); // Prevent triggering toggle
                
                // Show all UI
                showMobileUI();
                
                // Open drawer
                drawer.classList.add('open');
                drawer.style.display = 'flex';
            };
        }
    }
    
    // Initialize mobile like button with proper handler cleanup
    function initializeMobileLikeButton(video) {
        var mobileLikeBtn = document.getElementById('mobile-btn-like');
        var mobileLikeCount = document.getElementById('mobile-like-count');
        
        if (!mobileLikeBtn || !mobileLikeCount) return;
        
        // Get video ID - CRITICAL: Use proper video ID, not embed URL
        var videoId;
        if (video.id) {
            videoId = video.id;
        } else if (video.provider === 'youtube') {
            var youtubeMatch = video.url.match(/embed\/([^?]+)/);
            videoId = youtubeMatch ? 'youtube_' + youtubeMatch[1] : btoa(video.url);
        } else if (video.provider === 'vimeo') {
            var vimeoMatch = video.url.match(/video\/(\d+)/);
            videoId = vimeoMatch ? 'vimeo_' + vimeoMatch[1] : btoa(video.url);
        } else {
            var cleanUrl = video.url.split('?')[0];
            videoId = btoa(cleanUrl);
        }
        var videoUrl = video.url;
        
        console.log('📱 [MOBILE LIKE] Loading video:', { videoId, videoUrl, provider: video.provider });
        
        // Check if this is the same video - if so, skip reinitialization
        var currentVideoId = mobileLikeBtn.getAttribute('data-video-id');
        if (currentVideoId === videoId) {
            console.log('⏭️ [MOBILE LIKE] Same video, skipping reinitialization');
            return;
        }
        
        // Always update the video ID and URL - THIS IS CRITICAL
        mobileLikeBtn.setAttribute('data-video-id', videoId);
        mobileLikeBtn.setAttribute('data-video-url', videoUrl);
        
        // Check cache first for instant display (same as desktop)
        if (videoDataCache.likes[videoId]) {
            console.log('💾 [MOBILE LIKE] Using cached data for instant display');
            var cachedData = videoDataCache.likes[videoId];
            
            // Update button state INSTANTLY from cache
            mobileLikeCount.textContent = cachedData.total_likes;
            mobileLikeBtn.setAttribute('data-liked', cachedData.user_liked ? 'true' : 'false');
            
            var icon = mobileLikeBtn.querySelector('i');
            if (cachedData.user_liked) {
                if (icon) icon.className = 'ph-fill ph-thumbs-up';
            } else {
                if (icon) icon.className = 'ph ph-thumbs-up';
            }
            
            // Still fetch in background to refresh cache
            fetch('/api/video-likes/' + encodeURIComponent(videoId))
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    // Update cache
                    videoDataCache.likes[videoId] = {
                        user_liked: data.user_liked || false,
                        total_likes: data.total_likes || 0,
                        timestamp: Date.now(),
                        optimistic: false
                    };
                    
                    // Update UI if different from cache
                    var currentBtn = document.getElementById('mobile-btn-like');
                    var currentCount = document.getElementById('mobile-like-count');
                    if (currentBtn && currentBtn.getAttribute('data-video-id') === videoId && currentCount) {
                        if (currentCount.textContent != data.total_likes) {
                            currentCount.textContent = data.total_likes;
                        }
                    }
                })
                .catch(function(err) {
                    console.error('❌ [MOBILE LIKE] Background refresh error:', err);
                });
        } else {
            // No cache - fetch and wait
            console.log('🔄 [MOBILE LIKE] Fetching from:', '/api/video-likes/' + videoId);
            
            fetch('/api/video-likes/' + encodeURIComponent(videoId))
                .then(function(response) { 
                    console.log('📡 [MOBILE LIKE] Response:', response.status, response.ok);
                    return response.json(); 
                })
                .then(function(data) {
                    console.log('✅ [MOBILE LIKE] Data received:', data);
                    
                    var isLiked = data.user_liked || false;
                    var totalLikes = data.total_likes || 0;
                    
                    // Save to cache
                    videoDataCache.likes[videoId] = {
                        user_liked: isLiked,
                        total_likes: totalLikes,
                        timestamp: Date.now(),
                        optimistic: false
                    };
                    
                    console.log('👤 [MOBILE LIKE] Setting state - Liked:', isLiked, 'Count:', totalLikes);
                    
                    mobileLikeCount.textContent = totalLikes;
                    mobileLikeBtn.setAttribute('data-liked', isLiked ? 'true' : 'false');
                    
                    var icon = mobileLikeBtn.querySelector('i');
                    if (isLiked && icon) {
                        icon.className = 'ph-fill ph-thumbs-up';
                        console.log('❤️ [MOBILE LIKE] User HAS liked this video');
                    } else if (icon) {
                        icon.className = 'ph ph-thumbs-up';
                        console.log('🤍 [MOBILE LIKE] User has NOT liked this video');
                    }
                })
                .catch(function(err) {
                    console.error('❌ [MOBILE LIKE] API Error:', err);
                    mobileLikeCount.textContent = video.likes || 0;
                    mobileLikeBtn.setAttribute('data-liked', 'false');
                });
        }
        
        console.log('🔧 [MOBILE LIKE] Cloning button to remove old handlers');
        
        // CLONE AND REPLACE to remove ALL previous handlers (best practice for cleanup)
        var newMobileLikeBtn = mobileLikeBtn.cloneNode(true);
        mobileLikeBtn.parentNode.replaceChild(newMobileLikeBtn, mobileLikeBtn);
        mobileLikeBtn = newMobileLikeBtn; // Update reference
        
        // CRITICAL: Update mobileLikeCount reference after cloning
        mobileLikeCount = document.getElementById('mobile-like-count');
        
        console.log('🔧 [MOBILE LIKE] Button cloned, new count ref:', mobileLikeCount ? 'OK' : 'MISSING');
        
        // Attach fresh click handler with cache updates
        mobileLikeBtn.onclick = function(e) {
            e.stopPropagation(); // Prevent triggering toggle
            resetUIHideTimer(); // Reset timer on interaction
            
            console.log('👆 [MOBILE OPTIMISTIC LIKE] Click detected');
            
            // READ CURRENT values from data attributes (not from closure)
            var currentVideoId = this.getAttribute('data-video-id');
            var currentVideoUrl = this.getAttribute('data-video-url');
            var isLiked = this.getAttribute('data-liked') === 'true';
            var currentCount = parseInt(mobileLikeCount.textContent) || 0;
            var icon = this.querySelector('i');
            var newCount = isLiked ? Math.max(0, currentCount - 1) : currentCount + 1;
            
            console.log('⚡ [MOBILE OPTIMISTIC LIKE] INSTANT UPDATE - Current:', isLiked, 'Count:', currentCount, '→', newCount);
            
            // ═══ INSTANT UI UPDATE (OPTIMISTIC) ═══
            this.setAttribute('data-liked', !isLiked ? 'true' : 'false');
            mobileLikeCount.textContent = newCount;
            
            if (!isLiked) {
                if (icon) icon.className = 'ph-fill ph-thumbs-up';
            } else {
                if (icon) icon.className = 'ph ph-thumbs-up';
            }
            
            // Optimistic cache update
            videoDataCache.likes[currentVideoId] = {
                user_liked: !isLiked,
                total_likes: newCount,
                timestamp: Date.now(),
                optimistic: true
            };
            
            // ═══ BACKGROUND SERVER SYNC ═══
            var apiCall = !isLiked 
                ? fetch('/api/video-likes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        video_id: currentVideoId,
                        video_url: currentVideoUrl,
                        product_id: currentProduct?.id || null,
                        action: 'like'
                    })
                })
                : fetch('/api/video-likes/' + encodeURIComponent(currentVideoId), {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
            
            apiCall
                .then(function(response) { 
                    if (!response.ok) throw new Error('Server error');
                    return response.json(); 
                })
                .then(function(data) {
                    console.log('✅ [MOBILE OPTIMISTIC LIKE] Server confirmed:', data);
                    
                    if (data.success) {
                        // Update cache with server truth
                        videoDataCache.likes[currentVideoId] = {
                            user_liked: data.user_liked !== undefined ? data.user_liked : !isLiked,
                            total_likes: data.total_likes !== undefined ? data.total_likes : newCount,
                            timestamp: Date.now(),
                            optimistic: false
                        };
                        
                        // Reconcile count
                        var latestCount = document.getElementById('mobile-like-count');
                        var latestBtn = document.getElementById('mobile-btn-like');
                        if (latestCount && latestBtn && latestBtn.getAttribute('data-video-id') === currentVideoId) {
                            if (data.total_likes !== undefined) {
                                latestCount.textContent = data.total_likes;
                                console.log('🔄 [MOBILE OPTIMISTIC LIKE] Reconciled:', newCount, '→', data.total_likes);
                            }
                        }
                    }
                })
                .catch(function(err) {
                    console.error('❌ [MOBILE OPTIMISTIC LIKE] Failed, ROLLING BACK:', err);
                    
                    // Rollback
                    var rollbackBtn = document.getElementById('mobile-btn-like');
                    var rollbackCount = document.getElementById('mobile-like-count');
                    if (rollbackBtn && rollbackBtn.getAttribute('data-video-id') === currentVideoId) {
                        rollbackBtn.setAttribute('data-liked', isLiked ? 'true' : 'false');
                        if (rollbackCount) rollbackCount.textContent = currentCount;
                        
                        var rollbackIcon = rollbackBtn.querySelector('i');
                        if (isLiked) {
                            if (rollbackIcon) rollbackIcon.className = 'ph-fill ph-thumbs-up';
                        } else {
                            if (rollbackIcon) rollbackIcon.className = 'ph ph-thumbs-up';
                        }
                        
                        // Revert cache
                        videoDataCache.likes[currentVideoId] = {
                            user_liked: isLiked,
                            total_likes: currentCount,
                            timestamp: Date.now(),
                            optimistic: false
                        };
                    }
                });
        };
    }
    
    // Initialize mobile buy button
    function initializeMobileBuyButton() {
        var mobileBuyBtn = document.getElementById('mobile-btn-buy');
        var mobileSoldCount = document.getElementById('mobile-sold-count');
        
        if (!mobileBuyBtn) return;
        
        // Set sold count (get from product data if available)
        if (mobileSoldCount && currentProduct) {
            var soldCount = currentProduct.sold || currentProduct.number_of_sold || currentProduct.sales || 0;
            console.log('📦 [BUY BUTTON] Sold count:', soldCount, 'Product data:', {
                sold: currentProduct.sold,
                number_of_sold: currentProduct.number_of_sold,
                sales: currentProduct.sales
            });
            
            // Always show the sold count if available, otherwise show "Order"
            if (soldCount > 0) {
                mobileSoldCount.textContent = formatNumber(soldCount);
            } else {
                mobileSoldCount.textContent = 'Order';
            }
        }
        
        // Only attach handler if it doesn't exist
        if (!mobileBuyBtn.onclick) {
            // Buy now handler - trigger actual buy button with default selections
            mobileBuyBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                resetUIHideTimer(); // Reset timer on interaction
                
                console.log('🛒 [BUY BUTTON] Clicked - Product:', currentProduct);
                
                if (!currentProduct) {
                    console.error('❌ [BUY BUTTON] No product data available');
                    return;
                }
                
                // Extract product ID from various possible locations
                var productId = currentProduct.id || currentProduct.product_id || currentProduct.productId;
                console.log('✅ [BUY BUTTON] Product ID:', productId, 'Name:', currentProduct.name);
                
                // Close lightbox first
                closeLightbox();
                
                // After closing, set defaults and trigger buy
                setTimeout(function() {
                    console.log('🔍 [BUY BUTTON] Looking for buy now button...');
                    
                    // Set quantity to 1
                    var qtyInput = document.querySelector('input[name="qty"], input[name="quantity"], .qty-input, .quantity-input');
                    if (qtyInput) {
                        qtyInput.value = 1;
                        console.log('✅ [BUY BUTTON] Set quantity to 1');
                    } else {
                        console.log('⚠️ [BUY BUTTON] Quantity input not found');
                    }
                    
                    // Select first/default variation if exists
                    var variationSelects = document.querySelectorAll('select[name^="attribute"]');
                    if (variationSelects.length > 0) {
                        console.log('🔍 [BUY BUTTON] Found', variationSelects.length, 'variation selects');
                        variationSelects.forEach(function(select, index) {
                            if (select.options.length > 0) {
                                select.selectedIndex = select.options[0].value ? 0 : 1; // Skip placeholder if exists
                                select.dispatchEvent(new Event('change', { bubbles: true }));
                                console.log('✅ [BUY BUTTON] Selected variation', index);
                            }
                        });
                    } else {
                        console.log('ℹ️ [BUY BUTTON] No variations found');
                    }
                    
                    // Try to find and click the buy now button (correct selectors for current theme)
                    var buyNowBtn = document.querySelector(
                        'button[name="checkout"],' +
                        '.ps-btn[name="checkout"], ' +
                        'button[data-bb-toggle="buy-now"], ' +
                        '.btn-buy-now, ' +
                        '.buy-now-button'
                    );
                    
                    if (buyNowBtn) {
                        console.log('✅ [BUY BUTTON] Found buy now button:', buyNowBtn.outerHTML.substring(0, 100));
                        buyNowBtn.click();
                        console.log('✅ [BUY BUTTON] Clicked buy now button');
                    } else {
                        console.log('⚠️ [BUY BUTTON] Buy now button not found, trying add to cart fallback...');
                        
                        // Fallback: add to cart then go to checkout
                        var addToCartBtn = document.querySelector(
                            'button[name="add_to_cart"], ' +
                            '.add-to-cart-button, ' +
                            '.ps-btn[name="add_to_cart"], ' +
                            'button[data-bb-toggle="add-to-cart-in-form"], ' +
                            '.add-to-cart-form button[type="submit"]'
                        );
                        
                        if (addToCartBtn) {
                            console.log('✅ [BUY BUTTON] Found add to cart button:', addToCartBtn.outerHTML.substring(0, 100));
                            addToCartBtn.click();
                            console.log('✅ [BUY BUTTON] Clicked add to cart, redirecting to checkout...');
                            // Wait for cart update then redirect
                            setTimeout(function() {
                                window.location.href = '/checkout';
                            }, 800);
                        } else {
                            console.log('❌ [BUY BUTTON] No buttons found, scrolling to product section...');
                            
                            // Last resort: scroll to product section
                            var productSection = document.querySelector(
                                '.ps-product__info, ' +
                                '.bb-product-detail, ' +
                                '.ps-product--detail, ' +
                                '#product-detail-page, ' +
                                '.product-info, ' +
                                '.product-detail'
                            );
                            
                            if (productSection) {
                                console.log('✅ [BUY BUTTON] Found product section, scrolling...');
                                productSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            } else {
                                console.log('❌ [BUY BUTTON] Product section not found');
                            }
                        }
                    }
                }, 300);
            };
        }
    }
    
    // Initialize mobile cart button
    function initializeMobileCartButton() {
        var mobileCartBtn = document.getElementById('mobile-btn-cart');
        var mobileCartCount = document.getElementById('mobile-cart-count');
        
        if (!mobileCartBtn) return;
        
        console.log('🛒 [CART BUTTON INIT] Current product:', currentProduct);
        
        // Check if product is in cart and update icon
        if (currentProduct && (currentProduct.id || currentProduct.product_id || currentProduct.productId)) {
            var productId = currentProduct.id || currentProduct.product_id || currentProduct.productId;
            
            console.log('🛒 [CART BUTTON INIT] Checking cart for product:', productId);
            
            fetch('/api/cart/check/' + productId)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    console.log('🛒 [CART BUTTON INIT] Cart check response:', data);
                    
                    var inCart = data.in_cart || false;
                    var quantity = data.quantity || 0;
                    
                    // Update icon based on cart state
                    var icon = mobileCartBtn.querySelector('i');
                    if (icon) {
                        if (inCart) {
                            icon.className = 'ph-fill ph-shopping-cart'; // Filled icon when in cart
                        } else {
                            icon.className = 'ph ph-shopping-cart'; // Outline icon when not in cart
                        }
                    }
                    
                    // Update count display
                    if (mobileCartCount) {
                        if (inCart && quantity > 0) {
                            // Show THIS product's cart quantity (formatted)
                            mobileCartCount.textContent = formatNumber(quantity);
                            console.log('✅ [CART BUTTON INIT] Product in cart, quantity:', quantity);
                        } else {
                            // Show global cart total from page header
                            var cartCounter = document.querySelector('.cart-counter, .mini-cart-count, .cart-count, [data-cart-count], .header-cart-count');
                            var globalCount = cartCounter ? parseInt(cartCounter.textContent) || 0 : 0;
                            
                            if (globalCount > 0) {
                                mobileCartCount.textContent = formatNumber(globalCount);
                                console.log('✅ [CART BUTTON INIT] Showing global cart count:', globalCount);
                            } else {
                                mobileCartCount.textContent = 'Cart';
                                console.log('✅ [CART BUTTON INIT] Cart empty, showing "Cart"');
                            }
                        }
                    }
                    
                    // Store cart state
                    mobileCartBtn.setAttribute('data-in-cart', inCart ? 'true' : 'false');
                    mobileCartBtn.setAttribute('data-quantity', quantity);
                })
                .catch(function(err) {
                    console.error('❌ [CART BUTTON INIT] Failed to check cart state:', err);
                    // Fallback: get global cart count from page
                    if (mobileCartCount) {
                        var cartCounter = document.querySelector('.cart-counter, .mini-cart-count, .cart-count, [data-cart-count], .header-cart-count');
                        if (cartCounter) {
                            var count = parseInt(cartCounter.textContent) || 0;
                            mobileCartCount.textContent = count > 0 ? formatNumber(count) : 'Cart';
                            console.log('⚠️ [CART BUTTON INIT] Fallback to global count:', count);
                        } else {
                            mobileCartCount.textContent = 'Cart';
                            console.log('⚠️ [CART BUTTON INIT] No cart counter found, showing "Cart"');
                        }
                    }
                    mobileCartBtn.setAttribute('data-in-cart', 'false');
                });
        } else {
            console.warn('⚠️ [CART BUTTON INIT] No product ID available');
        }
        
        // Only attach handler if it doesn't exist
        if (!mobileCartBtn.onclick) {
            mobileCartBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                resetUIHideTimer(); // Reset timer on interaction
                
                console.log('🛒 [CART BUTTON] Clicked - Product:', currentProduct);
                
                if (!currentProduct) {
                    console.error('❌ [CART BUTTON] No product data available');
                    return;
                }
                
                // Extract product ID from various possible locations
                var productId = currentProduct.id || currentProduct.product_id || currentProduct.productId;
                console.log('✅ [CART BUTTON] Product ID:', productId, 'Name:', currentProduct.name);
                
                // Close lightbox
                closeLightbox();
                
                // After closing, set defaults and trigger add to cart
                setTimeout(function() {
                    console.log('🔍 [CART BUTTON] Looking for add to cart button...');
                    
                    // Set quantity to 1
                    var qtyInput = document.querySelector('input[name="qty"], input[name="quantity"], .qty-input, .quantity-input');
                    if (qtyInput) {
                        qtyInput.value = 1;
                        console.log('✅ [CART BUTTON] Set quantity to 1');
                    } else {
                        console.log('⚠️ [CART BUTTON] Quantity input not found');
                    }
                    
                    // Select first/default variation if exists
                    var variationSelects = document.querySelectorAll('select[name^="attribute"]');
                    if (variationSelects.length > 0) {
                        console.log('🔍 [CART BUTTON] Found', variationSelects.length, 'variation selects');
                        variationSelects.forEach(function(select, index) {
                            if (select.options.length > 0) {
                                select.selectedIndex = select.options[0].value ? 0 : 1; // Skip placeholder if exists
                                // Trigger change event for variation logic
                                select.dispatchEvent(new Event('change', { bubbles: true }));
                                console.log('✅ [CART BUTTON] Selected variation', index);
                            }
                        });
                    } else {
                        console.log('ℹ️ [CART BUTTON] No variations found');
                    }
                    
                    // Find and click add to cart button (correct selectors for current theme)
                    var addToCartBtn = document.querySelector(
                        'button[name="add-to-cart"], ' +
                        '.bb-product-details-add-to-cart-btn, ' +
                        'button[data-bb-toggle="add-to-cart-in-form"], ' +
                        '.add-to-cart-button, ' +
                        '.add-to-cart-form button[type="submit"], ' +
                        '.btn-add-to-cart'
                    );
                    
                    if (addToCartBtn) {
                        console.log('✅ [CART BUTTON] Found add to cart button:', addToCartBtn.outerHTML.substring(0, 100));
                        addToCartBtn.click();
                        console.log('✅ [CART BUTTON] Clicked add to cart button');
                        
                        // Update mobile cart state after a delay (wait for cart to update)
                        setTimeout(function() {
                            console.log('🔄 [CART BUTTON] Updating cart state after add...');
                            
                            // Re-check cart state
                            if (currentProduct && productId) {
                                fetch('/api/cart/check/' + productId)
                                    .then(function(response) { return response.json(); })
                                    .then(function(data) {
                                        console.log('🔄 [CART BUTTON] Updated cart state:', data);
                                        
                                        var inCart = data.in_cart || false;
                                        var quantity = data.quantity || 0;
                                        
                                        // Update icon
                                        var icon = mobileCartBtn.querySelector('i');
                                        if (icon && inCart) {
                                            icon.className = 'ph-fill ph-shopping-cart';
                                        }
                                        
                                        // Update count - show THIS product's quantity
                                        if (mobileCartCount && inCart && quantity > 0) {
                                            mobileCartCount.textContent = formatNumber(quantity);
                                            console.log('✅ [CART BUTTON] Updated count to:', quantity);
                                        } else if (mobileCartCount) {
                                            // Get global count as fallback
                                            var cartCounter = document.querySelector('.cart-counter, .mini-cart-count, .cart-count, [data-cart-count], .header-cart-count');
                                            var globalCount = cartCounter ? parseInt(cartCounter.textContent) || 0 : 0;
                                            mobileCartCount.textContent = globalCount > 0 ? formatNumber(globalCount) : 'Cart';
                                            console.log('✅ [CART BUTTON] Updated to global count:', globalCount);
                                        }
                                        
                                        mobileCartBtn.setAttribute('data-in-cart', inCart ? 'true' : 'false');
                                        mobileCartBtn.setAttribute('data-quantity', quantity);
                                    })
                                    .catch(function(err) {
                                        console.error('❌ [CART BUTTON] Failed to update state:', err);
                                    });
                            }
                        }, 1000);
                    } else {
                        // Fallback: scroll to product section
                        var productSection = document.querySelector(
                            '.ps-product__info, ' +
                            '.bb-product-detail, ' +
                            '.ps-product--detail, ' +
                            '#product-detail-page, ' +
                            '.product-info, ' +
                            '.product-detail'
                        );
                        if (productSection) {
                            productSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                }, 300);
            };
        }
    }
    
    // Initialize mobile share button with proper handler cleanup
    function initializeMobileShareButton(video) {
        var mobileShareBtn = document.getElementById('mobile-btn-share');
        var mobileShareCount = document.getElementById('mobile-share-count');
        
        if (!mobileShareBtn) return;
        
        // Get video ID - CRITICAL: Use proper video ID, not embed URL
        var videoId;
        if (video.id) {
            // Use provided video ID if available
            videoId = video.id;
        } else if (video.provider === 'youtube') {
            // Extract YouTube video ID from URL (e.g., YuITHVKtSx8 from embed URL)
            var youtubeMatch = video.url.match(/embed\/([^?]+)/);
            videoId = youtubeMatch ? 'youtube_' + youtubeMatch[1] : btoa(video.url);
        } else if (video.provider === 'vimeo') {
            // Extract Vimeo video ID from URL
            var vimeoMatch = video.url.match(/video\/(\d+)/);
            videoId = vimeoMatch ? 'vimeo_' + vimeoMatch[1] : btoa(video.url);
        } else {
            // For direct videos, use base URL without query params
            var cleanUrl = video.url.split('?')[0];
            videoId = btoa(cleanUrl);
        }
        var videoUrl = video.url;
        
        console.log('🎬 [MOBILE SHARE] Loading video:', { 
            videoId, 
            videoUrl, 
            provider: video.provider
        });
        
        // Check if this is the same video - if so, skip EVERYTHING
        var currentVideoId = mobileShareBtn.getAttribute('data-video-id');
        if (currentVideoId === videoId) {
            console.log('⏭️ [MOBILE SHARE] Same video, skipping ALL initialization');
            // Don't do ANYTHING - button already set up for this video
            return;
        }
        
        console.log('🆕 [MOBILE SHARE] New video, reinitializing');
        
        // Set video ID IMMEDIATELY before fetch to prevent duplicate initializations
        mobileShareBtn.setAttribute('data-video-id', videoId);
        mobileShareBtn.setAttribute('data-video-url', videoUrl);
        
        // CLONE AND REPLACE button FIRST to remove old handlers
        var newMobileShareBtn = mobileShareBtn.cloneNode(true);
        mobileShareBtn.parentNode.replaceChild(newMobileShareBtn, mobileShareBtn);
        mobileShareBtn = newMobileShareBtn; // Update reference
        
        // Get mobileShareCount from new cloned element
        mobileShareCount = document.getElementById('mobile-share-count');
        
        // Check cache first for instant display (same as desktop)
        if (videoDataCache.shares[videoId]) {
            console.log('💾 [MOBILE SHARE] Using cached data for instant display');
            var cachedData = videoDataCache.shares[videoId];
            
            if (mobileShareCount) {
                mobileShareCount.textContent = cachedData.total_shares;
            }
            
            // Still fetch in background to refresh cache
            fetch('/api/video-shares/' + encodeURIComponent(videoId))
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    // Update cache
                    videoDataCache.shares[videoId] = {
                        total_shares: data.total_shares || 0,
                        timestamp: Date.now(),
                        optimistic: false
                    };
                    
                    // Update UI if different from cache
                    var currentCount = document.getElementById('mobile-share-count');
                    if (currentCount && data.total_shares !== undefined) {
                        currentCount.textContent = data.total_shares;
                    }
                })
                .catch(function(err) {
                    console.error('❌ [MOBILE SHARE] Background refresh error:', err);
                });
        } else {
            // No cache - fetch and wait
            console.log('🔄 [MOBILE SHARE] Fetching from API');
            
            fetch('/api/video-shares/' + encodeURIComponent(videoId))
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    var totalShares = data.total_shares || 0;
                    
                    // Save to cache
                    videoDataCache.shares[videoId] = {
                        total_shares: totalShares,
                        timestamp: Date.now(),
                        optimistic: false
                    };
                    
                    // Update UI (re-query to ensure we have current element)
                    var currentCount = document.getElementById('mobile-share-count');
                    if (currentCount) {
                        currentCount.textContent = totalShares;
                    }
                })
                .catch(function(err) {
                    console.error('❌ [MOBILE SHARE] Failed to load share count:', err);
                    var currentCount = document.getElementById('mobile-share-count');
                    if (currentCount) {
                        currentCount.textContent = video.shares || 0;
                    }
                });
        }
        
        // Attach fresh click handler with optimistic UI (same as desktop)
        mobileShareBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent triggering toggle
            resetUIHideTimer(); // Reset timer on interaction
            
            console.log('👆 [MOBILE OPTIMISTIC SHARE] Click detected');
            
            // Get current state
            var currentVideoId = this.getAttribute('data-video-id');
            var currentVideoUrl = this.getAttribute('data-video-url');
            var currentCount = mobileShareCount ? parseInt(mobileShareCount.textContent) || 0 : 0;
            var newCount = currentCount + 1;
            
            console.log('⚡ [MOBILE OPTIMISTIC SHARE] INSTANT UPDATE - Count:', currentCount, '→', newCount);
            
            // ═══ STEP 1: INSTANT UI UPDATE (OPTIMISTIC) ═══
            if (mobileShareCount) {
                mobileShareCount.textContent = newCount;
            }
            
            // Optimistic cache update
            videoDataCache.shares[currentVideoId] = {
                total_shares: newCount,
                timestamp: Date.now(),
                optimistic: true
            };
            
            // ═══ STEP 2: TRIGGER SHARE ACTION ═══
            var sharePromise = null;
            
            if (navigator.share && currentVideoUrl) {
                // Native share API
                sharePromise = navigator.share({
                    title: currentProduct?.name || 'Product Video',
                    text: 'Check out this video!',
                    url: currentVideoUrl
                });
            } else if (currentVideoUrl) {
                // Fallback: copy to clipboard
                sharePromise = navigator.clipboard.writeText(currentVideoUrl).then(function() {
                    // Show copied notification
                    var icon = document.getElementById('mobile-btn-share')?.querySelector('i');
                    if (icon) {
                        var originalClass = icon.className;
                        icon.className = 'ph-fill ph-check';
                        setTimeout(function() {
                            icon.className = originalClass;
                        }, 2000);
                    }
                });
            }
            
            if (!sharePromise) return;
            
            // ═══ STEP 3: BACKGROUND SERVER SYNC ═══
            sharePromise
                .then(function() {
                    console.log('✅ [MOBILE OPTIMISTIC SHARE] User completed share action');
                    
                    // Track in database
                    return fetch('/api/video-shares', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            video_id: currentVideoId,
                            video_url: currentVideoUrl,
                            product_id: currentProduct?.id || null
                        })
                    });
                })
                .then(function(response) {
                    if (!response || !response.ok) {
                        throw new Error('Server error');
                    }
                    return response.json();
                })
                .then(function(data) {
                    console.log('✅ [MOBILE OPTIMISTIC SHARE] Server confirmed:', data);
                    
                    if (data.success) {
                        // ═══ STEP 4: RECONCILE WITH SERVER TRUTH ═══
                        videoDataCache.shares[currentVideoId] = {
                            total_shares: data.total_shares !== undefined ? data.total_shares : newCount,
                            timestamp: Date.now(),
                            optimistic: false
                        };
                        
                        // Update with server's authoritative value
                        var latestMobileShareBtn = document.getElementById('mobile-btn-share');
                        if (latestMobileShareBtn && latestMobileShareBtn.getAttribute('data-video-id') === currentVideoId) {
                            var latestMobileShareCount = document.getElementById('mobile-share-count');
                            if (latestMobileShareCount && data.total_shares !== undefined) {
                                latestMobileShareCount.textContent = data.total_shares;
                                console.log('🔄 [MOBILE OPTIMISTIC SHARE] Reconciled count:', newCount, '→', data.total_shares);
                            }
                        }
                    }
                })
                .catch(function(err) {
                    console.error('❌ [MOBILE OPTIMISTIC SHARE] Failed, ROLLING BACK:', err);
                    
                    // ═══ STEP 5: ROLLBACK ON FAILURE ═══
                    var rollbackBtn = document.getElementById('mobile-btn-share');
                    if (rollbackBtn && rollbackBtn.getAttribute('data-video-id') === currentVideoId) {
                        var rollbackCount = document.getElementById('mobile-share-count');
                        if (rollbackCount) {
                            rollbackCount.textContent = currentCount;
                            console.log('🔙 [MOBILE OPTIMISTIC SHARE] Rolled back to:', currentCount);
                        }
                    }
                    
                    // Revert cache
                    videoDataCache.shares[currentVideoId] = {
                        total_shares: currentCount,
                        timestamp: Date.now(),
                        optimistic: false
                    };
                });
        };
    }
    
    // Initialize mobile drawer
    function initializeMobileDrawer() {
        var drawer = document.getElementById('mobile-video-drawer');
        var handle = drawer?.querySelector('.mobile-drawer-handle');
        var drawerVideos = document.getElementById('mobile-drawer-videos');
        
        if (!drawer || !handle || !drawerVideos) return;
        
        // Populate drawer with video lists (reuse existing video list sections)
        var videoListsContainer = document.getElementById('lightbox-video-lists');
        if (videoListsContainer) {
            drawerVideos.innerHTML = videoListsContainer.innerHTML;
            
            // Add click handlers to all video items in the drawer
            var videoItems = drawerVideos.querySelectorAll('.video-list-item');
            videoItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    var videoIndex = parseInt(item.getAttribute('data-video-index'));
                    if (!isNaN(videoIndex) && currentProduct && currentProduct.videos) {
                        // Play the clicked video
                        loadVideos(currentProduct.videos, videoIndex);
                        updateActiveVideoListItem(videoIndex);
                        
                        // Update mobile controls
                        initializeMobileVideoControls();
                        
                        // Close drawer after selection
                        drawer.classList.remove('open');
                        
                        // Show controls and restart auto-hide timer
                        showMobileUI();
                    }
                });
            });
        }
        
        // Drawer drag/swipe interaction - make entire drawer draggable, not just handle
        var startY = 0;
        var currentY = 0;
        var isDragging = false;
        var initialDrawerTransform = 0;
        
        // Add touch events to entire drawer for easier pulling
        drawer.addEventListener('touchstart', function(e) {
            // Don't interfere with scrolling inside drawer content
            var target = e.target;
            if (target.closest('.video-list-item') || target.closest('a') || target.closest('button')) {
                return; // Allow clicks on video items
            }
            
            startY = e.touches[0].clientY;
            isDragging = true;
            initialDrawerTransform = drawer.classList.contains('open') ? 0 : (drawer.offsetHeight - 50);
        });
        
        drawer.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            currentY = e.touches[0].clientY;
            var deltaY = currentY - startY;
            
            // Allow dragging in both directions
            if (drawer.classList.contains('open')) {
                // When open, allow dragging down to close
                if (deltaY > 0) {
                    e.preventDefault();
                    var newTransform = Math.min(drawer.offsetHeight - 50, deltaY);
                    drawer.style.transform = 'translateY(' + newTransform + 'px)';
                } else if (deltaY < 0) {
                    // Allow slight overscroll up
                    e.preventDefault();
                    var newTransform = Math.max(-50, deltaY);
                    drawer.style.transform = 'translateY(' + newTransform + 'px)';
                }
            } else {
                // When closed, allow dragging up to open
                if (deltaY < 0) {
                    e.preventDefault();
                    var baseTransform = drawer.offsetHeight - 50;
                    var newTransform = baseTransform + deltaY;
                    drawer.style.transform = 'translateY(' + Math.max(0, newTransform) + 'px)';
                }
            }
        });
        
        drawer.addEventListener('touchend', function(e) {
            if (!isDragging) return;
            isDragging = false;
            
            var deltaY = currentY - startY;
            drawer.style.transform = ''; // Reset inline transform
            
            // Toggle drawer based on drag distance (reduced threshold for easier interaction)
            if (Math.abs(deltaY) > 80) {
                if (deltaY > 0 && drawer.classList.contains('open')) {
                    drawer.classList.remove('open');
                } else if (deltaY < 0 && !drawer.classList.contains('open')) {
                    drawer.classList.add('open');
                }
            }
        });
        
        // Click handle to toggle
        handle.addEventListener('click', function() {
            if (drawer.classList.contains('open')) {
                // Closing drawer
                drawer.classList.remove('open');
                // Don't hide drawer completely, just close it to show handle
            } else {
                // Opening drawer
                drawer.classList.add('open');
                drawer.style.display = 'flex';
                showMobileUI(); // Show UI when opening drawer
            }
        });
        
        // When drawer closes, check if controls should be hidden
        drawer.addEventListener('transitionend', function(e) {
            if (e.propertyName === 'transform' && !drawer.classList.contains('open')) {
                // Drawer just closed, restart auto-hide timer for UI
                var mobileControls = document.getElementById('mobile-video-controls');
                if (mobileControls && !mobileControls.classList.contains('controls-hidden')) {
                    resetUIHideTimer();
                }
            }
        });
    }
    
    // Hide mobile video controls (when switching to images)
    function hideMobileVideoControls() {
        var mobileControls = document.getElementById('mobile-video-controls');
        var mobileDrawer = document.getElementById('mobile-video-drawer');
        var lightboxOverlay = document.getElementById('product-lightbox-modal');
        
        if (mobileControls) mobileControls.style.display = 'none';
        if (mobileDrawer) mobileDrawer.style.display = 'none';
        if (lightboxOverlay) lightboxOverlay.classList.remove('video-mode');
        
        // Close drawer if open
        if (mobileDrawer) mobileDrawer.classList.remove('open');
    }
    
    // Close lightbox
    function closeLightbox() {
        stopAllVideos(); // Stop videos before closing
        lightbox.style.display = 'none';
        document.body.style.overflow = '';
        resetZoom();
    }
    
    closeBtn.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });
    
    // Tab switching function
    function switchTab(tabName) {
        // Update active state
        tabs.forEach(function(t) { 
            if (t.getAttribute('data-tab') === tabName) {
                t.classList.add('active');
            } else {
                t.classList.remove('active');
            }
        });
        
        var thumbnailsContainer = document.getElementById('lightbox-thumbnails');
        var videoListsContainer = document.getElementById('lightbox-video-lists');
        var productInfo = document.querySelector('.product-lightbox-info');
        
        // Show/hide content - both main display AND sidebar
        if (tabName === 'images') {
            stopAllVideos(); // Stop videos when switching to images tab
            hideMobileVideoControls(); // Hide mobile video UI
            imagesContent.style.display = 'flex';
            videosContent.style.display = 'none';
            
            // Show thumbnails, hide video lists, show product info
            if (thumbnailsContainer) thumbnailsContainer.style.display = 'grid';
            if (videoListsContainer) videoListsContainer.style.display = 'none';
            if (productInfo) productInfo.style.display = 'block';
        } else {
            imagesContent.style.display = 'none';
            videosContent.style.display = 'flex';
            
            // Hide thumbnails, show video lists, hide product info
            if (thumbnailsContainer) thumbnailsContainer.style.display = 'none';
            if (videoListsContainer) videoListsContainer.style.display = 'block';
            if (productInfo) productInfo.style.display = 'none';
            
            // Auto-play video when switching to Videos tab
            if (currentProduct && currentProduct.videos && currentProduct.videos.length > 0) {
                var videoPlayer = document.getElementById('lightbox-video-player');
                var hasVideoContent = videoPlayer && videoPlayer.children.length > 0;
                
                // Always reload video to ensure autoplay works
                var videoIndex = currentProduct.currentVideoIndex || 0;
                loadVideos(currentProduct.videos, videoIndex);
                
                // Initialize mobile video controls if on mobile
                if (window.innerWidth <= 767) {
                    initializeMobileVideoControls();
                }
            }
        }
    }
    
    // Tab switching on click
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            var tabName = this.getAttribute('data-tab');
            switchTab(tabName);
        });
    });
    
    // ═══════════════════════════════════════════════════════════════════
    // OPTIMISTIC UI HANDLERS - Facebook/YouTube Pattern
    // Update UI instantly, reconcile with server in background, rollback on error
    // ═══════════════════════════════════════════════════════════════════
    
    // Attach like button click handler with optimistic UI
    function attachLikeButtonHandler(btn, videoId, videoUrl) {
        btn.onclick = function(e) {
            e.preventDefault();
            console.log('👆 [OPTIMISTIC LIKE] Click detected');
            
            // Get current state BEFORE any changes
            var isCurrentlyLiked = this.getAttribute('data-liked') === 'true';
            var countEl = document.getElementById('lightbox-video-like-count');
            if (!countEl) return;
            
            var currentCount = parseInt(countEl.textContent) || 0;
            var icon = this.querySelector('i');
            var newCount = isCurrentlyLiked ? Math.max(0, currentCount - 1) : currentCount + 1;
            
            console.log('⚡ [OPTIMISTIC LIKE] INSTANT UPDATE - Current:', isCurrentlyLiked, 'Count:', currentCount, '→', newCount);
            
            // ═══ STEP 1: INSTANT UI UPDATE (OPTIMISTIC) ═══
            this.setAttribute('data-liked', !isCurrentlyLiked ? 'true' : 'false');
            countEl.textContent = newCount;
            
            if (!isCurrentlyLiked) {
                // Liking
                this.classList.add('liked');
                if (icon) icon.className = 'ph-fill ph-thumbs-up';
            } else {
                // Unliking  
                this.classList.remove('liked');
                if (icon) icon.className = 'ph ph-thumbs-up';
            }
            
            // Optimistic cache update
            videoDataCache.likes[videoId] = {
                user_liked: !isCurrentlyLiked,
                total_likes: newCount,
                timestamp: Date.now(),
                optimistic: true // Mark as not yet confirmed by server
            };
            
            // ═══ STEP 2: BACKGROUND SERVER SYNC ═══
            var apiCall = !isCurrentlyLiked 
                ? fetch('/api/video-likes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        video_id: videoId,
                        video_url: videoUrl,
                        product_id: currentProduct?.id || null,
                        action: 'like'
                    })
                })
                : fetch('/api/video-likes/' + encodeURIComponent(videoId), {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
            
            apiCall
                .then(function(response) { 
                    if (!response.ok) throw new Error('Server error: ' + response.status);
                    return response.json(); 
                })
                .then(function(data) {
                    console.log('✅ [OPTIMISTIC LIKE] Server confirmed:', data);
                    
                    if (data.success) {
                        // ═══ STEP 3: RECONCILE WITH SERVER TRUTH ═══
                        videoDataCache.likes[videoId] = {
                            user_liked: data.user_liked !== undefined ? data.user_liked : !isCurrentlyLiked,
                            total_likes: data.total_likes !== undefined ? data.total_likes : newCount,
                            timestamp: Date.now(),
                            optimistic: false // Confirmed by server
                        };
                        
                        // Update count with server's authoritative value
                        var latestCountEl = document.getElementById('lightbox-video-like-count');
                        var latestBtn = document.getElementById('lightbox-video-like-btn');
                        
                        if (latestCountEl && latestBtn && latestBtn.getAttribute('data-video-id') === videoId) {
                            // Only update if still on same video
                            if (data.total_likes !== undefined) {
                                latestCountEl.textContent = data.total_likes;
                                console.log('🔄 [OPTIMISTIC LIKE] Reconciled count:', newCount, '→', data.total_likes);
                            }
                        }
                    }
                })
                .catch(function(err) {
                    console.error('❌ [OPTIMISTIC LIKE] Server failed, ROLLING BACK:', err);
                    
                    // ═══ STEP 4: ROLLBACK ON FAILURE ═══
                    var rollbackBtn = document.getElementById('lightbox-video-like-btn');
                    var rollbackCount = document.getElementById('lightbox-video-like-count');
                    
                    if (rollbackBtn && rollbackCount && rollbackBtn.getAttribute('data-video-id') === videoId) {
                        // Revert to original state
                        rollbackBtn.setAttribute('data-liked', isCurrentlyLiked ? 'true' : 'false');
                        rollbackCount.textContent = currentCount;
                        
                        var rollbackIcon = rollbackBtn.querySelector('i');
                        if (isCurrentlyLiked) {
                            rollbackBtn.classList.add('liked');
                            if (rollbackIcon) rollbackIcon.className = 'ph-fill ph-thumbs-up';
                        } else {
                            rollbackBtn.classList.remove('liked');
                            if (rollbackIcon) rollbackIcon.className = 'ph ph-thumbs-up';
                        }
                        
                        // Revert cache
                        videoDataCache.likes[videoId] = {
                            user_liked: isCurrentlyLiked,
                            total_likes: currentCount,
                            timestamp: Date.now(),
                            optimistic: false
                        };
                        
                        // Show user-friendly error
                        console.log('🔙 [OPTIMISTIC LIKE] Rolled back to:', isCurrentlyLiked, currentCount);
                    }
                });
        };
    }
    
    // Load videos
    function loadVideos(videos, activeIndex) {
        console.log('📹 [LOAD VIDEOS] Called with activeIndex:', activeIndex, 'Stack:', new Error().stack.split('\n')[2]);
        
        if (typeof activeIndex === 'undefined') activeIndex = 0;
        
        var videoPlayer = document.getElementById('lightbox-video-player');
        if (!videoPlayer) {
            console.error('Video player element not found');
            return;
        }
        
        // Clear existing content
        videoPlayer.innerHTML = '';
        
        // Get the active video
        var video = videos[activeIndex];
        if (!video) return;
        
        // Set aspect ratio for video player (default 16:9, but handle portrait)
        var aspectRatio = video.aspectRatio || '16:9';
        if (video.portrait || aspectRatio === '9:16') {
            // Portrait video - max width with height based on ratio
            videoPlayer.style.paddingTop = '0';
            videoPlayer.style.maxWidth = '56.25%'; // 9/16 ratio
            videoPlayer.style.aspectRatio = '9 / 16';
            videoPlayer.style.margin = '0 auto';
        } else {
            // Landscape video - standard 16:9
            videoPlayer.style.paddingTop = '56.25%'; // 16:9 ratio
            videoPlayer.style.maxWidth = '100%';
            videoPlayer.style.aspectRatio = '';
            videoPlayer.style.margin = '0';
        }
        
        // Determine video type and create appropriate player
        if (video.provider === 'youtube') {
            createYouTubePlayer(videoPlayer, video);
        } else if (video.provider === 'vimeo') {
            createVimeoPlayer(videoPlayer, video);
        } else if (video.url) {
            // Direct video file (MP4, WebM, etc.)
            createDirectVideoPlayer(videoPlayer, video);
        }
        
        // Update video info section
        var videoTitle = document.getElementById('lightbox-video-product-title');
        var videoUploader = document.getElementById('lightbox-video-uploader');
        
        if (videoTitle && currentProduct) {
            videoTitle.textContent = currentProduct.name || 'Product Video';
        }
        
        if (videoUploader) {
            var uploaderName = video.store_name || video.uploader || 'Povaly Product Visuals';
            videoUploader.querySelector('.uploader-name').textContent = uploaderName;
        }
        
        // Initialize desktop like button with proper handler cleanup
        var likeBtn = document.getElementById('lightbox-video-like-btn');
        var likeCount = document.getElementById('lightbox-video-like-count');
        
        if (likeBtn && likeCount) {
            // Get video ID - CRITICAL: Use proper video ID, not embed URL
            var videoId;
            if (video.id) {
                // Use provided video ID if available
                videoId = video.id;
            } else if (video.provider === 'youtube') {
                // Extract YouTube video ID from URL (e.g., YuITHVKtSx8 from embed URL)
                var youtubeMatch = video.url.match(/embed\/([^?]+)/);
                videoId = youtubeMatch ? 'youtube_' + youtubeMatch[1] : btoa(video.url);
            } else if (video.provider === 'vimeo') {
                // Extract Vimeo video ID from URL
                var vimeoMatch = video.url.match(/video\/(\d+)/);
                videoId = vimeoMatch ? 'vimeo_' + vimeoMatch[1] : btoa(video.url);
            } else {
                // For direct videos, use base URL without query params
                var cleanUrl = video.url.split('?')[0];
                videoId = btoa(cleanUrl);
            }
            var videoUrl = video.url;
            
            console.log('🎬 [DESKTOP LIKE] Loading video:', { 
                videoId, 
                videoUrl, 
                provider: video.provider,
                index: activeIndex 
            });
            
            // Check if this is the same video - if so, skip EVERYTHING
            var currentVideoId = likeBtn.getAttribute('data-video-id');
            if (currentVideoId === videoId) {
                console.log('⏭️ [DESKTOP LIKE] Same video, skipping ALL initialization');
                // Don't do ANYTHING - button already set up for this video
            } else {
                // NEW VIDEO - need to reinitialize
                console.log('🆕 [DESKTOP LIKE] New video, reinitializing');
                
                // Set video ID IMMEDIATELY before fetch to prevent duplicate initializations
                likeBtn.setAttribute('data-video-id', videoId);
                likeBtn.setAttribute('data-video-url', videoUrl);
                
                // Check cache first for instant display
                if (videoDataCache.likes[videoId]) {
                    console.log('💾 [DESKTOP LIKE] Using cached data for instant display');
                    var cachedData = videoDataCache.likes[videoId];
                    
                    // Update button state INSTANTLY from cache
                    likeCount.textContent = cachedData.total_likes;
                    likeBtn.setAttribute('data-liked', cachedData.user_liked ? 'true' : 'false');
                    
                    var icon = likeBtn.querySelector('i');
                    if (cachedData.user_liked) {
                        likeBtn.classList.add('liked');
                        if (icon) icon.className = 'ph-fill ph-thumbs-up';
                    } else {
                        likeBtn.classList.remove('liked');
                        if (icon) icon.className = 'ph ph-thumbs-up';
                    }
                    
                    // Clone button to remove old handlers
                    var newLikeBtn = likeBtn.cloneNode(true);
                    if (likeBtn.parentNode) {
                        likeBtn.parentNode.replaceChild(newLikeBtn, likeBtn);
                    }
                    
                    // Attach click handler (same as below)
                    attachLikeButtonHandler(newLikeBtn, videoId, videoUrl);
                    
                    // Still fetch in background to refresh cache
                    fetch('/api/video-likes/' + encodeURIComponent(videoId))
                        .then(function(response) { return response.json(); })
                        .then(function(data) {
                            // Update cache
                            videoDataCache.likes[videoId] = {
                                user_liked: data.user_liked || false,
                                total_likes: data.total_likes || 0,
                                timestamp: Date.now()
                            };
                            
                            // Update UI if different from cache
                            var currentBtn = document.getElementById('lightbox-video-like-btn');
                            var currentCount = document.getElementById('lightbox-video-like-count');
                            if (currentBtn && currentBtn.getAttribute('data-video-id') === videoId && currentCount) {
                                if (currentCount.textContent != data.total_likes) {
                                    currentCount.textContent = data.total_likes;
                                }
                            }
                        })
                        .catch(function(err) {
                            console.error('❌ [DESKTOP LIKE] Background refresh error:', err);
                        });
                } else {
                    // No cache - fetch and wait
                    console.log('🔄 [DESKTOP LIKE] Fetching from:', '/api/video-likes/' + videoId);
                    
                    fetch('/api/video-likes/' + encodeURIComponent(videoId))
                    .then(function(response) { 
                        console.log('📡 [DESKTOP LIKE] Response:', response.status, response.ok); 
                        return response.json(); 
                    })
                    .then(function(data) {
                        console.log('✅ [DESKTOP LIKE] Data received:', data);
                        
                        // Save to cache FIRST
                        videoDataCache.likes[videoId] = {
                            user_liked: data.user_liked || false,
                            total_likes: data.total_likes || 0,
                            timestamp: Date.now()
                        };
                        
                        // Re-query the button and check if it still exists and has the correct video ID
                        var currentLikeBtn = document.getElementById('lightbox-video-like-btn');
                        var currentLikeCount = document.getElementById('lightbox-video-like-count');
                        
                        if (!currentLikeBtn || !currentLikeBtn.parentNode) {
                            console.log('⚠️ [DESKTOP LIKE] Button removed from DOM, skipping update');
                            return;
                        }
                        
                        // Check if video has changed while we were fetching
                        if (currentLikeBtn.getAttribute('data-video-id') !== videoId) {
                            console.log('⚠️ [DESKTOP LIKE] Video changed during fetch, skipping update');
                            return;
                        }
                        
                        // NOW clone the button with the data ready
                        console.log('🔧 [DESKTOP LIKE] Cloning button to remove old handlers');
                        var newLikeBtn = currentLikeBtn.cloneNode(true);
                        currentLikeBtn.parentNode.replaceChild(newLikeBtn, currentLikeBtn);
                        
                        // CRITICAL: Update likeCount reference after cloning
                        var newLikeCount = document.getElementById('lightbox-video-like-count');
                        console.log('🔧 [DESKTOP LIKE] Button cloned for video:', videoId, 'New count ref:', newLikeCount ? 'OK' : 'MISSING');
                        
                        if (!newLikeCount) {
                            console.error('❌ [DESKTOP LIKE] Count element not found after cloning');
                            return;
                        }
                        
                        // Set state from database IMMEDIATELY (no delay)
                        var isLiked = data.user_liked || false;
                        var totalLikes = data.total_likes || 0;
                        
                        console.log('👤 [DESKTOP LIKE] Setting state - Liked:', isLiked, 'Count:', totalLikes);
                        
                        newLikeCount.textContent = totalLikes;
                        newLikeBtn.setAttribute('data-liked', isLiked ? 'true' : 'false');
                        
                        var icon = newLikeBtn.querySelector('i');
                        if (isLiked) {
                            newLikeBtn.classList.add('liked');
                            if (icon) icon.className = 'ph-fill ph-thumbs-up';
                            console.log('❤️ [DESKTOP LIKE] User HAS liked this video');
                        } else {
                            newLikeBtn.classList.remove('liked');
                            if (icon) icon.className = 'ph ph-thumbs-up';
                            console.log('🤍 [DESKTOP LIKE] User has NOT liked this video');
                        }
                        
                        // Attach fresh click handler
                        attachLikeButtonHandler(newLikeBtn, videoId, videoUrl);
                    })
                    .catch(function(err) {
                        console.error('❌ [DESKTOP LIKE] Fetch error:', err);
                        // Set defaults on error - re-query elements
                        var errorBtn = document.getElementById('lightbox-video-like-btn');
                        var errorCount = document.getElementById('lightbox-video-like-count');
                        if (errorCount) errorCount.textContent = video.likes || 0;
                        if (errorBtn) errorBtn.setAttribute('data-liked', 'false');
                    });
                } // Close else block for cache check
            } // Close else block for NEW VIDEO
        } // Close if (likeBtn && likeCount)
        
        // Add share button handler with database tracking and proper cleanup
        var shareBtn = document.getElementById('lightbox-video-share-btn');
        if (shareBtn) {
            var shareCount = shareBtn.querySelector('.share-count');
            
            // CRITICAL: Use same video ID extraction logic as like button
            var shareVideoId;
            if (video.id) {
                shareVideoId = video.id;
            } else if (video.provider === 'youtube') {
                var youtubeMatch = video.url.match(/embed\/([^?]+)/);
                shareVideoId = youtubeMatch ? 'youtube_' + youtubeMatch[1] : btoa(video.url);
            } else if (video.provider === 'vimeo') {
                var vimeoMatch = video.url.match(/video\/(\d+)/);
                shareVideoId = vimeoMatch ? 'vimeo_' + vimeoMatch[1] : btoa(video.url);
            } else {
                var cleanUrl = video.url.split('?')[0];
                shareVideoId = btoa(cleanUrl);
            }
            var videoUrl = video.url;
            
            console.log('🔗 [DESKTOP SHARE] Initializing for video:', shareVideoId);
            
            // Check if same video - skip if yes
            var currentShareVideoId = shareBtn.getAttribute('data-video-id');
            if (currentShareVideoId === shareVideoId) {
                console.log('⏭️ [DESKTOP SHARE] Same video, skipping');
                // DON'T return - just skip this section, continue to rest of loadVideos
            } else {
            
            // Always update the video ID and URL - THIS IS CRITICAL
            shareBtn.setAttribute('data-video-id', shareVideoId);
            shareBtn.setAttribute('data-video-url', videoUrl);
            
            console.log('🔄 [DESKTOP SHARE] Fetching from:', '/api/video-shares/' + shareVideoId);
            
            // Load share count from database
            if (shareCount) {
                fetch('/api/video-shares/' + encodeURIComponent(shareVideoId))
                    .then(function(response) { 
                        console.log('📡 [DESKTOP SHARE] Response:', response.status);
                        return response.json(); 
                    })
                    .then(function(data) {
                        console.log('✅ [DESKTOP SHARE] Data:', data);
                        var totalShares = data.total_shares || 0;
                        shareCount.textContent = totalShares;
                    })
                    .catch(function(err) {
                        console.error('❌ [DESKTOP SHARE] Error:', err);
                        shareCount.textContent = video.shares || 0;
                    });
            }
            
            console.log('🔧 [DESKTOP SHARE] Cloning button');
            
            // CLONE AND REPLACE to remove ALL previous handlers
            var newShareBtn = shareBtn.cloneNode(true);
            shareBtn.parentNode.replaceChild(newShareBtn, shareBtn);
            shareBtn = newShareBtn; // Update reference
            
            // Get shareCount from new cloned element
            shareCount = shareBtn.querySelector('.share-count');
            
            console.log('🔧 [DESKTOP SHARE] Button cloned, count ref:', shareCount ? 'OK' : 'MISSING');
            
            // Attach fresh click handler with optimistic UI
            shareBtn.onclick = function(e) {
                e.preventDefault();
                console.log('👆 [OPTIMISTIC SHARE] Click detected');
                
                // Get current state
                var currentVideoId = this.getAttribute('data-video-id');
                var currentVideoUrl = this.getAttribute('data-video-url');
                var currentCount = shareCount ? parseInt(shareCount.textContent) || 0 : 0;
                var newCount = currentCount + 1;
                
                console.log('⚡ [OPTIMISTIC SHARE] INSTANT UPDATE - Count:', currentCount, '→', newCount);
                
                // ═══ STEP 1: INSTANT UI UPDATE (OPTIMISTIC) ═══
                if (shareCount) {
                    shareCount.textContent = newCount;
                }
                
                // Optimistic cache update
                videoDataCache.shares[currentVideoId] = {
                    total_shares: newCount,
                    timestamp: Date.now(),
                    optimistic: true
                };
                
                // ═══ STEP 2: TRIGGER SHARE ACTION ═══
                var sharePromise = null;
                
                if (navigator.share && currentVideoUrl) {
                    // Native share API
                    sharePromise = navigator.share({
                        title: currentProduct?.name || 'Product Video',
                        text: 'Check out this video!',
                        url: currentVideoUrl
                    });
                } else if (currentVideoUrl) {
                    // Fallback: copy to clipboard
                    sharePromise = navigator.clipboard.writeText(currentVideoUrl).then(function() {
                        // Show copied notification
                        var icon = document.getElementById('lightbox-video-share-btn')?.querySelector('i');
                        if (icon) {
                            var originalClass = icon.className;
                            icon.className = 'ph-fill ph-check';
                            setTimeout(function() {
                                icon.className = originalClass;
                            }, 2000);
                        }
                    });
                }
                
                if (!sharePromise) return;
                
                // ═══ STEP 3: BACKGROUND SERVER SYNC ═══
                sharePromise
                    .then(function() {
                        console.log('✅ [OPTIMISTIC SHARE] User completed share action');
                        
                        // Track in database
                        return fetch('/api/video-shares', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({
                                video_id: currentVideoId,
                                video_url: currentVideoUrl,
                                product_id: currentProduct?.id || null
                            })
                        });
                    })
                    .then(function(response) {
                        if (!response || !response.ok) {
                            throw new Error('Server error');
                        }
                        return response.json();
                    })
                    .then(function(data) {
                        console.log('✅ [OPTIMISTIC SHARE] Server confirmed:', data);
                        
                        if (data.success) {
                            // ═══ STEP 4: RECONCILE WITH SERVER TRUTH ═══
                            videoDataCache.shares[currentVideoId] = {
                                total_shares: data.total_shares !== undefined ? data.total_shares : newCount,
                                timestamp: Date.now(),
                                optimistic: false
                            };
                            
                            // Update with server's authoritative value
                            var latestShareBtn = document.getElementById('lightbox-video-share-btn');
                            if (latestShareBtn && latestShareBtn.getAttribute('data-video-id') === currentVideoId) {
                                var latestShareCount = latestShareBtn.querySelector('.share-count');
                                if (latestShareCount && data.total_shares !== undefined) {
                                    latestShareCount.textContent = data.total_shares;
                                    console.log('🔄 [OPTIMISTIC SHARE] Reconciled count:', newCount, '→', data.total_shares);
                                }
                            }
                        }
                    })
                    .catch(function(err) {
                        console.error('❌ [OPTIMISTIC SHARE] Failed, ROLLING BACK:', err);
                        
                        // ═══ STEP 5: ROLLBACK ON FAILURE ═══
                        var rollbackBtn = document.getElementById('lightbox-video-share-btn');
                        if (rollbackBtn && rollbackBtn.getAttribute('data-video-id') === currentVideoId) {
                            var rollbackCount = rollbackBtn.querySelector('.share-count');
                            if (rollbackCount) {
                                rollbackCount.textContent = currentCount;
                                console.log('🔙 [OPTIMISTIC SHARE] Rolled back to:', currentCount);
                            }
                        }
                        
                        // Revert cache
                        videoDataCache.shares[currentVideoId] = {
                            total_shares: currentCount,
                            timestamp: Date.now(),
                            optimistic: false
                        };
                    });
            };
            } // Close else block for NEW VIDEO
        }
        
        // Store current video index
        currentProduct.currentVideoIndex = activeIndex;
        
        // Initialize mobile video controls if on mobile (after desktop like button is initialized)
        if (window.innerWidth <= 767) {
            initializeMobileVideoControls();
        }
    }
    
    // Create YouTube player
    function createYouTubePlayer(container, video) {
        // Determine aspect ratio
        var aspectRatio = video.aspectRatio || (video.portrait ? '9:16' : '16:9');
        var isPortrait = aspectRatio === '9:16' || video.portrait;
        
        // Set aspect ratio attribute on container
        container.setAttribute('data-aspect', aspectRatio);
        if (isPortrait) {
            container.classList.add('portrait');
        } else {
            container.classList.add('landscape');
        }
        
        var src = video.url;
        // Add autoplay and other parameters
        var sep = src.indexOf('?') !== -1 ? '&' : '?';
        if (src.indexOf('autoplay') === -1) src += sep + 'autoplay=1';
        if (src.indexOf('enablejsapi') === -1) src += (src.indexOf('?') !== -1 ? '&' : '?') + 'enablejsapi=1';
        
        var iframe = document.createElement('iframe');
        iframe.src = src;
        iframe.frameBorder = '0';
        iframe.allow = 'accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture';
        iframe.allowFullscreen = true;
        iframe.style.cssText = 'width:100%;height:100%;border:0;display:block;object-fit:' + (isPortrait ? 'cover' : 'contain') + ';';
        iframe.id = 'lightbox-youtube-player';
        
        container.appendChild(iframe);
        
        // Listen for YouTube player state changes
        window.addEventListener('message', function(event) {
            if (event.origin !== 'https://www.youtube.com') return;
            try {
                var data = JSON.parse(event.data);
                // YouTube sends playerState 0 when video ends
                if (data.event === 'onStateChange' && data.info === 0) {
                    handleVideoEnded();
                }
            } catch (e) {}
        });
    }
    
    // Create Vimeo player
    function createVimeoPlayer(container, video) {
        // Determine aspect ratio
        var aspectRatio = video.aspectRatio || (video.portrait ? '9:16' : '16:9');
        var isPortrait = aspectRatio === '9:16' || video.portrait;
        
        // Set aspect ratio attribute on container
        container.setAttribute('data-aspect', aspectRatio);
        if (isPortrait) {
            container.classList.add('portrait');
        } else {
            container.classList.add('landscape');
        }
        
        var src = video.url;
        // Add autoplay and other parameters
        var sep = src.indexOf('?') !== -1 ? '&' : '?';
        if (src.indexOf('autoplay') === -1) src += sep + 'autoplay=1';
        if (src.indexOf('api') === -1) src += (src.indexOf('?') !== -1 ? '&' : '?') + 'api=1';
        
        var iframe = document.createElement('iframe');
        iframe.src = src;
        iframe.frameBorder = '0';
        iframe.allow = 'accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture';
        iframe.allowFullscreen = true;
        iframe.style.cssText = 'width:100%;height:100%;border:0;display:block;object-fit:' + (isPortrait ? 'cover' : 'contain') + ';';
        iframe.id = 'lightbox-vimeo-player';
        
        container.appendChild(iframe);
        
        // Listen for Vimeo player events
        window.addEventListener('message', function(event) {
            if (event.origin !== 'https://player.vimeo.com') return;
            try {
                var data = JSON.parse(event.data);
                // Vimeo sends 'ended' event when video finishes
                if (data.event === 'ended') {
                    handleVideoEnded();
                }
            } catch (e) {}
        });
    }
    
    // Create direct video player
    function createDirectVideoPlayer(container, video) {
        // Determine aspect ratio from video metadata or settings
        var aspectRatio = video.aspectRatio || (video.portrait ? '9:16' : '16:9');
        var isPortrait = aspectRatio === '9:16' || video.portrait;
        
        // Set aspect ratio attribute on container
        container.setAttribute('data-aspect', aspectRatio);
        if (isPortrait) {
            container.classList.add('portrait');
        } else {
            container.classList.add('landscape');
        }
        
        var videoEl = document.createElement('video');
        videoEl.src = video.url;
        videoEl.id = 'lightbox-direct-video';
        videoEl.controls = true; // Always show native controls
        videoEl.autoplay = true;
        videoEl.playsInline = true;
        videoEl.preload = 'metadata';
        
        // Set proper object-fit based on aspect ratio
        if (isPortrait) {
            videoEl.style.cssText = 'width:100%;height:100%;object-fit:cover;'; // Portrait: cover full height
        } else {
            videoEl.style.cssText = 'width:100%;height:100%;object-fit:contain;'; // Landscape: maintain aspect
        }
        
        // Handle poster image if available
        if (video.poster) {
            videoEl.poster = video.poster;
        }
        
        // Add ended event listener
        videoEl.addEventListener('ended', function() {
            handleVideoEnded();
        });
        
        container.appendChild(videoEl);
    }
    
    // Handle video ended - play next or restart from beginning
    function handleVideoEnded() {
        if (!currentProduct || !currentProduct.videos) return;
        
        var currentIndex = currentProduct.currentVideoIndex || 0;
        var totalVideos = currentProduct.videos.length;
        
        if (currentIndex < totalVideos - 1) {
            // Play next video
            setTimeout(function() {
                loadVideos(currentProduct.videos, currentIndex + 1);
                updateActiveVideoListItem(currentIndex + 1);
            }, 1000); // 1 second delay before next video
        } else {
            // Last video - restart from beginning automatically
            setTimeout(function() {
                loadVideos(currentProduct.videos, 0);
                updateActiveVideoListItem(0);
            }, 1000);
        }
    }
    
    // Load video lists in sidebar
    function loadVideoLists(productVideos, relatedVideos, reviewVideos, activeIndex) {
        if (typeof activeIndex === 'undefined') activeIndex = 0;
        
        // Product videos list
        var productVideosList = document.getElementById('product-videos-list');
        var productVideosSection = document.getElementById('product-videos-section');
        if (productVideosList && productVideos && productVideos.length > 0) {
            productVideosList.innerHTML = '';
            productVideos.forEach(function(video, index) {
                var item = createVideoListItem(video, index, index === activeIndex);
                item.addEventListener('click', function() {
                    loadVideos(productVideos, index);
                    updateActiveVideoListItem(index);
                });
                productVideosList.appendChild(item);
            });
            productVideosSection.style.display = 'block';
        } else {
            productVideosSection.style.display = 'none';
        }
        
        // Related videos list
        var relatedVideosList = document.getElementById('related-videos-list');
        var relatedVideosSection = document.getElementById('related-videos-section');
        if (relatedVideosList && relatedVideos && relatedVideos.length > 0) {
            relatedVideosList.innerHTML = '';
            relatedVideos.forEach(function(video, index) {
                var item = createVideoListItem(video, index, false);
                item.addEventListener('click', function() {
                    loadVideos(relatedVideos, index);
                    updateActiveVideoListItem(index, 'related');
                });
                relatedVideosList.appendChild(item);
            });
            relatedVideosSection.style.display = 'block';
        } else {
            relatedVideosSection.style.display = 'none';
        }
        
        // Review videos list
        var reviewVideosList = document.getElementById('review-videos-list');
        var reviewVideosSection = document.getElementById('review-videos-section');
        if (reviewVideosList && reviewVideos && reviewVideos.length > 0) {
            reviewVideosList.innerHTML = '';
            reviewVideos.forEach(function(video, index) {
                var item = createVideoListItem(video, index, false);
                item.addEventListener('click', function() {
                    loadVideos(reviewVideos, index);
                    updateActiveVideoListItem(index, 'review');
                });
                reviewVideosList.appendChild(item);
            });
            reviewVideosSection.style.display = 'block';
        } else {
            reviewVideosSection.style.display = 'none';
        }
    }
    
    // Create a video list item element
    function createVideoListItem(video, index, isActive) {
        var item = document.createElement('div');
        item.className = 'video-list-item' + (isActive ? ' active' : '');
        item.setAttribute('data-video-index', index);
        
        // Thumbnail
        var thumbnail = document.createElement('div');
        thumbnail.className = 'video-list-item-thumbnail';
        var thumbImg = document.createElement('img');
        thumbImg.src = video.thumbnail || video.poster || '/path/to/default-video-thumb.jpg';
        thumbImg.alt = video.title || 'Video thumbnail';
        thumbnail.appendChild(thumbImg);
        
        // Info
        var info = document.createElement('div');
        info.className = 'video-list-item-info';
        
        var title = document.createElement('div');
        title.className = 'video-list-item-title';
        title.textContent = video.title || 'Product Video ' + (index + 1);
        
        var uploader = document.createElement('div');
        uploader.className = 'video-list-item-uploader';
        uploader.innerHTML = '<i class="fa fa-user-circle"></i><span>' + 
            (video.store_name || video.uploader || 'Povaly Product Visuals') + '</span>';
        
        info.appendChild(title);
        info.appendChild(uploader);
        
        item.appendChild(thumbnail);
        item.appendChild(info);
        
        return item;
    }
    
    // Update active video list item
    function updateActiveVideoListItem(index, listType) {
        // Remove active from all items
        var allItems = document.querySelectorAll('.video-list-item');
        allItems.forEach(function(item) {
            item.classList.remove('active');
        });
        
        // Add active to the clicked item
        var listId = listType === 'related' ? 'related-videos-list' : 
                     listType === 'review' ? 'review-videos-list' : 'product-videos-list';
        var list = document.getElementById(listId);
        if (list) {
            var items = list.querySelectorAll('.video-list-item');
            if (items[index]) {
                items[index].classList.add('active');
            }
        }
    }
    
    // Load thumbnails
    function loadThumbnails(images, activeIndex) {
        if (typeof activeIndex === 'undefined') activeIndex = 0;
        
        thumbnailsContainer.innerHTML = '';
        images.forEach(function(img, index) {
            var thumb = document.createElement('div');
            thumb.className = 'product-lightbox-thumbnail' + (index === activeIndex ? ' active' : '');
            thumb.innerHTML = '<img src="' + img + '" alt="Thumbnail ' + (index + 1) + '">';
            thumb.addEventListener('click', function() {
                selectThumbnail(index);
            });
            thumbnailsContainer.appendChild(thumb);
        });
        
        // Initialize thumbnail swipe on mobile
        if (window.innerWidth <= 767) {
            initThumbnailSwipe();
        }
    }
    
    // Initialize thumbnail strip swipe on mobile - EXACTLY like offcanvas
    function initThumbnailSwipe() {
        // Get the sidebar wrapper (parent of thumbnails container)
        var sidebarWrapper = document.querySelector('.product-lightbox-sidebar');
        if (!sidebarWrapper || !thumbnailsContainer) return;
        
        var dragStartX = 0;
        var dragStartY = 0;
        var isDragging = false;
        var dragMoved = false;
        var scrollStart = 0;
        var MOVE_THRESHOLD = 5;
        
        // Attach to the entire sidebar wrapper so it works anywhere
        sidebarWrapper.addEventListener('touchstart', function(e) {
            // Don't hijack if touching a thumbnail directly (allow click)
            dragStartX = e.touches[0].clientX;
            dragStartY = e.touches[0].clientY;
            scrollStart = thumbnailsContainer.scrollLeft;
            isDragging = false;
            dragMoved = false;
        }, { passive: true });
        
        sidebarWrapper.addEventListener('touchmove', function(e) {
            if (dragStartX === 0) return;
            
            var currentX = e.touches[0].clientX;
            var currentY = e.touches[0].clientY;
            var deltaX = currentX - dragStartX;
            var deltaY = Math.abs(currentY - dragStartY);
            
            // Only start dragging if horizontal movement is dominant
            if (!isDragging && Math.abs(deltaX) > MOVE_THRESHOLD && Math.abs(deltaX) > deltaY) {
                isDragging = true;
                dragMoved = true;
            }
            
            if (isDragging) {
                e.preventDefault(); // Prevent browser back gesture
                // Apply smooth scroll (negate deltaX because scrollLeft increases when scrolling right)
                thumbnailsContainer.scrollLeft = scrollStart - deltaX;
            }
        }, { passive: false }); // passive: false to allow preventDefault
        
        sidebarWrapper.addEventListener('touchend', function(e) {
            isDragging = false;
            dragStartX = 0;
            
            // Reset dragMoved after a short delay to allow click handlers to check it
            setTimeout(function() {
                dragMoved = false;
            }, 50);
        }, { passive: true });
        
        // Prevent clicks on thumbnails if user was swiping
        thumbnailsContainer.addEventListener('click', function(e) {
            if (dragMoved) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);
    }
    
    // Select thumbnail with proper swipe animation
    function selectThumbnail(index, direction) {
        if (!currentProduct || !currentProduct.images) return;
        
        // Always use slide animation on mobile (even for thumbnail clicks)
        if (window.innerWidth <= 767) {
            var wrapper = imageWrapper;
            var currentImg = mainImage;
            
            // If no direction provided (thumbnail click), determine direction from index
            if (!direction) {
                var thumbs = thumbnailsContainer.querySelectorAll('.product-lightbox-thumbnail');
                var currentIndex = Array.from(thumbs).findIndex(function(t) {
                    return t.classList.contains('active');
                });
                direction = index > currentIndex ? 1 : -1;
            }
            
            // Reset any existing transforms on current image - completely reset to natural state
            currentImg.style.transition = 'none';
            currentImg.style.transform = '';
            currentImg.style.transformOrigin = '';
            currentImg.style.position = '';
            currentImg.style.top = '';
            currentImg.style.left = '';
            // Force reflow
            currentImg.offsetHeight;
            
            // Create new image for incoming
            var newImg = document.createElement('img');
            newImg.src = currentProduct.images[index];
            newImg.className = 'product-lightbox-main-image';
            newImg.style.position = 'absolute';
            newImg.style.top = '0';
            newImg.style.left = '0';
            newImg.style.width = '100%';
            newImg.style.height = '100%';
            newImg.style.objectFit = 'contain';
            newImg.style.transition = 'none';
            
            // Position images side by side for horizontal slide (like offcanvas)
            // Set current image to relative position in wrapper
            currentImg.style.position = 'absolute';
            currentImg.style.top = '0';
            currentImg.style.left = '0';
            currentImg.style.width = '100%';
            currentImg.style.height = '100%';
            currentImg.style.objectFit = 'contain';
            currentImg.style.transform = 'translateX(0)';
            
            // Position new image off-screen based on direction
            // ONLY use translateX - no Y transforms at all
            if (direction < 0) {
                newImg.style.transform = 'translateX(-100%)'; // Prev starts from left
            } else {
                newImg.style.transform = 'translateX(100%)'; // Next starts from right
            }
            
            wrapper.appendChild(newImg);
            
            // Animate: slide both images ONLY HORIZONTALLY
            setTimeout(function() {
                currentImg.style.transition = 'transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                newImg.style.transition = 'transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                
                // Slide out current image (only X)
                if (direction < 0) {
                    currentImg.style.transform = 'translateX(100%)'; // Current exits right
                } else {
                    currentImg.style.transform = 'translateX(-100%)'; // Current exits left
                }
                
                // Slide in new image to center (X = 0)
                newImg.style.transform = 'translateX(0)';
            }, 10);
            
            // Clean up after animation
            setTimeout(function() {
                mainImage.src = currentProduct.images[index];
                mainImage.style.position = '';
                mainImage.style.top = '';
                mainImage.style.left = '';
                mainImage.style.transformOrigin = '';
                mainImage.style.transition = 'none'; // Fully disable transition
                mainImage.style.transform = '';
                // Force reflow to apply 'none', then restore CSS transitions
                mainImage.offsetHeight;
                mainImage.style.transition = '';
                wrapper.removeChild(newImg);
            }, 350);
        } else {
            // Desktop: instant change
            mainImage.src = currentProduct.images[index];
        }
        
        resetZoom();
        
        // Update active state
        var thumbs = thumbnailsContainer.querySelectorAll('.product-lightbox-thumbnail');
        thumbs.forEach(function(t) { t.classList.remove('active'); });
        if (thumbs[index]) {
            thumbs[index].classList.add('active');
            
            // Scroll active thumbnail into view on mobile
            if (window.innerWidth <= 767) {
                thumbs[index].scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }
        }
    }
    
    // Image zoom functionality - Desktop: click ANYWHERE in area, zoom based on IMAGE position
    imageWrapper.addEventListener('click', function(e) {
        // Only on desktop
        if (window.innerWidth > 767) {
            if (!isZoomed) {
                // Zoom in first
                isZoomed = true;
                imageWrapper.classList.add('zoomed');
                
                // Enable mouse move for panning
                imageWrapper.addEventListener('mousemove', handleZoomPan);
                
                // Immediately call handleZoomPan with the click event
                // This ensures the clicked position is calculated the SAME WAY as mousemove
                handleZoomPan(e);
            } else {
                resetZoom();
            }
        }
    });
    
    // Touch swipe and double-tap functionality for main image - MOBILE ONLY
    var touchStartX = 0;
    var touchStartY = 0;
    var touchEndX = 0;
    var touchEndY = 0;
    var minSwipeDistance = 50;
    var lastTapTime = 0;
    var doubleTapDelay = 300;
    var touchMoveX = 0;
    var touchMoveY = 0;
    var isPanning = false;
    var hasMoved = false;
    var moveThreshold = 10;
    
    // Drag variables for swipe visual feedback
    var dragStartX = 0;
    var dragDelta = 0;
    var isDragging = false;
    var DRAG_THRESHOLD = 50;
    
    imageWrapper.addEventListener('touchstart', function(e) {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
        dragStartX = touchStartX;
        hasMoved = false;
        
        // Check for double tap
        var currentTime = new Date().getTime();
        var tapLength = currentTime - lastTapTime;
        
        if (tapLength < doubleTapDelay && tapLength > 0) {
            // Double tap detected
            e.preventDefault();
            
            if (isZoomed) {
                resetZoom();
            } else {
                // Zoom to the tapped position (pass tap coordinates)
                zoomIn(touchStartX, touchStartY);
            }
            lastTapTime = 0;
            return; // Exit early, no drag
        } else {
            lastTapTime = currentTime;
        }
        
        // Store initial position for panning when zoomed
        if (isZoomed) {
            isPanning = true;
            touchMoveX = touchStartX;
            touchMoveY = touchStartY;
        } else {
            // Start drag mode for swipe navigation
            isDragging = true;
        }
    }, { passive: false });
    
    imageWrapper.addEventListener('touchmove', function(e) {
        var currentX = e.touches[0].clientX;
        var currentY = e.touches[0].clientY;
        
        // Check if user has moved beyond threshold
        var deltaX = Math.abs(currentX - touchStartX);
        var deltaY = Math.abs(currentY - touchStartY);
        
        if (deltaX > moveThreshold || deltaY > moveThreshold) {
            hasMoved = true;
        }
        
        if (isZoomed && isPanning) {
            // Panning zoomed image
            e.preventDefault();
            
            // Calculate delta from last position
            var moveX = currentX - touchMoveX;
            var moveY = currentY - touchMoveY;
            
            // Update position
            touchMoveX = currentX;
            touchMoveY = currentY;
            
            // Apply panning (3x amplified for faster response - matches desktop feel)
            handleTouchPan(moveX * 3, moveY * 3);
        } else if (isDragging && !isZoomed) {
            // Dragging for swipe navigation
            dragDelta = currentX - dragStartX;
            
            // Only apply transform if horizontal swipe is dominant
            if (Math.abs(dragDelta) > Math.abs(currentY - touchStartY)) {
                e.preventDefault();
                // Apply visual feedback: slide image ONLY HORIZONTALLY (no diagonal movement)
                mainImage.style.transition = 'none';
                mainImage.style.transform = 'translateX(' + dragDelta + 'px)';
            }
        }
    }, { passive: false });
    
    imageWrapper.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].clientX;
        touchEndY = e.changedTouches[0].clientY;
        
        if (isPanning) {
            isPanning = false;
        } else if (isDragging && hasMoved && !isZoomed) {
            // Handle swipe with drag feedback
            isDragging = false;
            
            var deltaX = touchEndX - touchStartX;
            var deltaY = touchEndY - touchStartY;
            
            // Check if horizontal swipe is dominant and exceeds minimum distance
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(dragDelta) > DRAG_THRESHOLD) {
                if (dragDelta > 0) {
                    // Swipe right - go to previous image
                    navigateThumbnail(-1);
                } else {
                    // Swipe left - go to next image
                    navigateThumbnail(1);
                }
            } else {
                // Not enough swipe, snap back
                mainImage.style.transition = 'transform 0.3s ease';
                mainImage.style.transform = '';
            }
            
            dragDelta = 0;
        }
        
        // Reset drag mode
        isDragging = false;
        
        // Reset movement flag after a delay
        setTimeout(function() {
            hasMoved = false;
        }, 100);
    }, { passive: true });
    
    // Handle touch-based panning when zoomed
    var currentPanX = 0;
    var currentPanY = 0;
    var isFirstTouchMove = false;
    
    function handleTouchPan(deltaX, deltaY) {
        if (!isZoomed) return;
        
        // Only disable transition after first move (not during initial zoom)
        if (isFirstTouchMove) {
            // Disable transition during active panning for instant response
            mainImage.style.transition = 'none';
        } else {
            isFirstTouchMove = true;
        }
        
        // Get container and image dimensions
        var container = imageWrapper.getBoundingClientRect();
        var img = mainImage.getBoundingClientRect();
        
        // Calculate boundaries
        var overflowX = Math.max(0, img.width - container.width);
        var overflowY = Math.max(0, img.height - container.height);
        var maxMoveX = overflowX / 2;
        var maxMoveY = overflowY / 2;
        
        // Update pan position
        currentPanX += deltaX;
        currentPanY += deltaY;
        
        // Clamp to boundaries
        currentPanX = Math.max(-maxMoveX, Math.min(maxMoveX, currentPanX));
        currentPanY = Math.max(-maxMoveY, Math.min(maxMoveY, currentPanY));
        
        // Apply transform
        mainImage.style.transform = 'translate(calc(-50% + ' + currentPanX + 'px), calc(-50% + ' + currentPanY + 'px))';
    }
    
    // Zoom in to tapped area - MOBILE (double-tap)
    function zoomIn(tapX, tapY) {
        isZoomed = true;
        imageWrapper.classList.add('zoomed');
        
        // If no tap coordinates provided, zoom to center (fallback)
        if (typeof tapX === 'undefined' || typeof tapY === 'undefined') {
            currentPanX = 0;
            currentPanY = 0;
            mainImage.style.transform = 'translate(-50%, -50%)';
            return;
        }
        
        // Get wrapper dimensions
        var wrapperRect = imageWrapper.getBoundingClientRect();
        
        // Calculate tap position relative to wrapper
        var tapRelativeX = tapX - wrapperRect.left;
        var tapRelativeY = tapY - wrapperRect.top;
        
        // Calculate tap position from wrapper center (-0.5 to 0.5)
        var centerX = (tapRelativeX / wrapperRect.width) - 0.5;
        var centerY = (tapRelativeY / wrapperRect.height) - 0.5;
        
        // Amplify the movement - adjust for mobile zoom level
        var amplificationFactor = 2.2;
        var amplifiedX = centerX * amplificationFactor;
        var amplifiedY = centerY * amplificationFactor;
        
        // Get actual image dimensions AFTER zoom is applied
        requestAnimationFrame(function() {
            var img = mainImage.getBoundingClientRect();
            
            // Calculate how much image extends beyond container
            var overflowX = Math.max(0, img.width - wrapperRect.width);
            var overflowY = Math.max(0, img.height - wrapperRect.height);
            
            // Calculate pan offset
            var moveX = -amplifiedX * (overflowX / 2);
            var moveY = -amplifiedY * (overflowY / 2);
            
            // Clamp to boundaries
            var maxMoveX = overflowX / 2;
            var maxMoveY = overflowY / 2;
            
            currentPanX = Math.max(-maxMoveX, Math.min(maxMoveX, moveX));
            currentPanY = Math.max(-maxMoveY, Math.min(maxMoveY, moveY));
            
            // Apply transform with pan offset
            mainImage.style.transform = 'translate(calc(-50% + ' + currentPanX + 'px), calc(-50% + ' + currentPanY + 'px))';
        });
    }
    
    // Reset zoom - DESKTOP & MOBILE
    function resetZoom() {
        isZoomed = false;
        imageWrapper.classList.remove('zoomed');
        mainImage.style.transform = '';
        currentPanX = 0;
        currentPanY = 0;
        lastMouseX = null;
        lastMouseY = null;
        isFirstMove = false;
        isFirstTouchMove = false;
        imageWrapper.removeEventListener('mousemove', handleZoomPan);
    }
    
    // Handle zoom panning - Amazon style: amplified movement from center
    var lastMouseX = null;
    var lastMouseY = null;
    var isFirstMove = false;
    
    function handleZoomPan(e) {
        if (!isZoomed) return;
        
        // Only disable transition after first move (not during initial zoom)
        if (!isFirstMove) {
            isFirstMove = true;
            // Keep transition for smooth initial zoom
        } else {
            // Disable transition for subsequent panning for instant response
            mainImage.style.transition = 'none';
        }
        
        // Get container dimensions and current mouse position
        var container = imageWrapper.getBoundingClientRect();
        var mouseX = e.clientX - container.left;
        var mouseY = e.clientY - container.top;
        
        // Calculate mouse position from center (-0.5 to 0.5)
        var centerX = (mouseX / container.width) - 0.5;
        var centerY = (mouseY / container.height) - 0.5;
        
        // Amplify the movement (3x faster response)
        var amplifiedX = centerX * 3;
        var amplifiedY = centerY * 3;
        
        // Get actual image dimensions when zoomed
        var img = mainImage.getBoundingClientRect();
        
        // Calculate how much image extends beyond container
        var overflowX = Math.max(0, img.width - container.width);
        var overflowY = Math.max(0, img.height - container.height);
        
        // Calculate desired movement based on mouse position from center
        var moveX = -amplifiedX * (overflowX / 2);
        var moveY = -amplifiedY * (overflowY / 2);
        
        // Clamp to boundaries
        var maxMoveX = overflowX / 2;
        var maxMoveY = overflowY / 2;
        
        moveX = Math.max(-maxMoveX, Math.min(maxMoveX, moveX));
        moveY = Math.max(-maxMoveY, Math.min(maxMoveY, moveY));
        
        // Update current pan position
        currentPanX = moveX;
        currentPanY = moveY;
        
        // Apply transform
        mainImage.style.transform = 'translate(calc(-50% + ' + currentPanX + 'px), calc(-50% + ' + currentPanY + 'px))';
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (lightbox.style.display === 'none') return;
        
        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            navigateThumbnail(-1);
        } else if (e.key === 'ArrowRight') {
            navigateThumbnail(1);
        }
    });
    
    // Navigate thumbnails with keyboard or swipe
    function navigateThumbnail(direction) {
        if (!currentProduct || !currentProduct.images) return;
        
        var thumbs = thumbnailsContainer.querySelectorAll('.product-lightbox-thumbnail');
        var currentIndex = Array.from(thumbs).findIndex(function(t) {
            return t.classList.contains('active');
        });
        
        var newIndex = currentIndex + direction;
        if (newIndex >= 0 && newIndex < currentProduct.images.length) {
            // Reset any drag transform before animation
            mainImage.style.transition = '';
            mainImage.style.transform = '';
            
            selectThumbnail(newIndex, direction); // Pass direction for swipe animation
        } else {
            // Out of bounds - snap back with animation
            mainImage.style.transition = 'transform 0.3s ease';
            mainImage.style.transform = '';
        }
    }
});
</script>
