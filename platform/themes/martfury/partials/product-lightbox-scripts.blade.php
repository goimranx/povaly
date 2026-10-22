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
    // MOBILE VIDEO CONTROLS
    // ═══════════════════════════════════════════════════════════════════
    
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
        
        var videoIndex = currentProduct.currentVideoIndex || 0;
        var video = currentProduct.videos[videoIndex];
        if (!video) return;
        
        var mobileControls = document.getElementById('mobile-video-controls');
        var mobileDrawer = document.getElementById('mobile-video-drawer');
        var lightboxOverlay = document.getElementById('product-lightbox-modal');
        
        // Show mobile controls and drawer
        if (mobileControls) mobileControls.style.display = 'block';
        if (mobileDrawer) mobileDrawer.style.display = 'flex';
        
        // Add video-mode class for black background
        if (lightboxOverlay) lightboxOverlay.classList.add('video-mode');
        
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
            titleExpand.onclick = function() {
                mobileTitle.classList.toggle('expanded');
                titleExpand.classList.toggle('expanded');
            };
        }
        
        // Title click also expands/collapses (only if overflow)
        if (mobileTitle) {
            mobileTitle.onclick = function() {
                var titleExpandBtn = document.getElementById('mobile-video-title-expand');
                // Only allow expand if chevron is visible (meaning there's overflow)
                if (titleExpandBtn && titleExpandBtn.classList.contains('show')) {
                    mobileTitle.classList.toggle('expanded');
                    titleExpandBtn.classList.toggle('expanded');
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
        
        // Initialize close button
        var mobileClose = document.querySelector('.mobile-video-close');
        if (mobileClose) {
            mobileClose.onclick = closeLightbox;
        }
        
        // Initialize drawer
        initializeMobileDrawer();
    }
    
    // Initialize mobile like button
    function initializeMobileLikeButton(video) {
        var mobileLikeBtn = document.getElementById('mobile-btn-like');
        var mobileLikeCount = document.getElementById('mobile-like-count');
        
        if (!mobileLikeBtn || !mobileLikeCount) return;
        
        var videoId = video.id || btoa(video.url);
        
        // Load like state from database
        fetch('/api/video-likes/' + encodeURIComponent(videoId))
            .then(function(response) { return response.json(); })
            .then(function(data) {
                var isLiked = data.user_liked || false;
                var totalLikes = data.total_likes || 0;
                
                mobileLikeCount.textContent = formatNumber(totalLikes);
                mobileLikeBtn.setAttribute('data-liked', isLiked ? 'true' : 'false');
                mobileLikeBtn.setAttribute('data-video-id', videoId);
                
                var icon = mobileLikeBtn.querySelector('i');
                if (isLiked && icon) {
                    icon.className = 'ph-fill ph-thumbs-up';
                } else if (icon) {
                    icon.className = 'ph ph-thumbs-up';
                }
            })
            .catch(function(err) {
                console.error('Failed to load like state:', err);
                mobileLikeCount.textContent = '0';
                mobileLikeBtn.setAttribute('data-liked', 'false');
                mobileLikeBtn.setAttribute('data-video-id', videoId);
            });
        
        // Click handler
        mobileLikeBtn.onclick = function() {
            var isLiked = mobileLikeBtn.getAttribute('data-liked') === 'true';
            var currentCount = parseInt(mobileLikeCount.textContent.replace(/[KM]/g, '')) || 0;
            var videoId = mobileLikeBtn.getAttribute('data-video-id');
            var icon = mobileLikeBtn.querySelector('i');
            
            if (!isLiked) {
                // Like
                mobileLikeBtn.setAttribute('data-liked', 'true');
                if (icon) icon.className = 'ph-fill ph-thumbs-up';
                
                fetch('/api/video-likes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        video_id: videoId,
                        video_url: video.url,
                        product_id: currentProduct?.id || null,
                        action: 'like'
                    })
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.success) {
                        mobileLikeCount.textContent = formatNumber(data.total_likes);
                    }
                })
                .catch(function(err) {
                    console.error('Failed to save like:', err);
                });
            } else {
                // Unlike
                mobileLikeBtn.setAttribute('data-liked', 'false');
                if (icon) icon.className = 'ph ph-thumbs-up';
                
                fetch('/api/video-likes/' + encodeURIComponent(videoId), {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.success) {
                        mobileLikeCount.textContent = formatNumber(data.total_likes);
                    }
                })
                .catch(function(err) {
                    console.error('Failed to remove like:', err);
                });
            }
        };
    }
    
    // Initialize mobile buy button
    function initializeMobileBuyButton() {
        var mobileBuyBtn = document.getElementById('mobile-btn-buy');
        var mobileSoldCount = document.getElementById('mobile-sold-count');
        
        if (!mobileBuyBtn) return;
        
        // Set sold count (get from product data if available)
        if (mobileSoldCount && currentProduct) {
            var soldCount = currentProduct.sold || currentProduct.number_of_sold || 0;
            mobileSoldCount.textContent = formatNumber(soldCount) + ' sold';
        }
        
        // Buy now handler - find and trigger the buy now button on the page
        mobileBuyBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Try to find the buy now button on the product page
            var buyNowBtn = document.querySelector('.btn-buy-now, .buy-now-button, [data-action="buy-now"]');
            
            if (buyNowBtn) {
                // Close lightbox first
                closeLightbox();
                // Trigger the buy now button after a short delay
                setTimeout(function() {
                    buyNowBtn.click();
                }, 300);
            } else {
                // Fallback: close lightbox and scroll to add to cart section
                closeLightbox();
                setTimeout(function() {
                    var addToCartSection = document.querySelector('.product-info-main, .product-details, .box-product-detail');
                    if (addToCartSection) {
                        addToCartSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, 300);
            }
        };
    }
    
    // Initialize mobile cart button
    function initializeMobileCartButton() {
        var mobileCartBtn = document.getElementById('mobile-btn-cart');
        
        if (!mobileCartBtn) return;
        
        mobileCartBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Try to find the add to cart button on the page
            var addToCartBtn = document.querySelector('.btn-add-to-cart, .add-to-cart-button, [data-action="add-to-cart"], .add-to-cart-form button[type="submit"]');
            
            if (addToCartBtn) {
                // Close lightbox first
                closeLightbox();
                // Trigger the add to cart button after a short delay
                setTimeout(function() {
                    addToCartBtn.click();
                }, 300);
            } else {
                // Fallback: close lightbox and scroll to add to cart section
                closeLightbox();
                setTimeout(function() {
                    var addToCartSection = document.querySelector('.product-info-main, .product-details, .box-product-detail');
                    if (addToCartSection) {
                        addToCartSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, 300);
            }
        };
    }
    
    // Initialize mobile share button
    function initializeMobileShareButton(video) {
        var mobileShareBtn = document.getElementById('mobile-btn-share');
        
        if (!mobileShareBtn) return;
        
        mobileShareBtn.onclick = function() {
            if (navigator.share && video.url) {
                navigator.share({
                    title: currentProduct?.name || 'Product Video',
                    text: 'Check out this video!',
                    url: video.url
                }).catch(function(err) {
                    console.log('Share failed:', err);
                });
            } else if (video.url) {
                navigator.clipboard.writeText(video.url).then(function() {
                    // Show feedback
                    var icon = mobileShareBtn.querySelector('i');
                    if (icon) {
                        var originalClass = icon.className;
                        icon.className = 'ph-fill ph-check';
                        setTimeout(function() {
                            icon.className = originalClass;
                        }, 2000);
                    }
                });
            }
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
                    }
                });
            });
        }
        
        // Drawer drag/swipe interaction
        var startY = 0;
        var currentY = 0;
        var isDragging = false;
        
        handle.addEventListener('touchstart', function(e) {
            startY = e.touches[0].clientY;
            isDragging = true;
        });
        
        handle.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            currentY = e.touches[0].clientY;
            var deltaY = currentY - startY;
            
            // Only allow dragging down when drawer is open, or up when closed
            if (drawer.classList.contains('open')) {
                if (deltaY > 0) {
                    e.preventDefault();
                    drawer.style.transform = 'translateY(calc(100% - 50px + ' + deltaY + 'px))';
                }
            } else {
                if (deltaY < 0) {
                    e.preventDefault();
                    drawer.style.transform = 'translateY(calc(100% - 50px + ' + deltaY + 'px))';
                }
            }
        });
        
        handle.addEventListener('touchend', function(e) {
            if (!isDragging) return;
            isDragging = false;
            
            var deltaY = currentY - startY;
            drawer.style.transform = ''; // Reset inline transform
            
            // Toggle drawer based on drag distance
            if (Math.abs(deltaY) > 100) {
                if (deltaY > 0 && drawer.classList.contains('open')) {
                    drawer.classList.remove('open');
                } else if (deltaY < 0 && !drawer.classList.contains('open')) {
                    drawer.classList.add('open');
                }
            }
        });
        
        // Click handle to toggle
        handle.addEventListener('click', function() {
            drawer.classList.toggle('open');
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
    
    // Load videos
    function loadVideos(videos, activeIndex) {
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
        
        // Initialize like button state from database
        var likeBtn = document.getElementById('lightbox-video-like-btn');
        var likeCount = document.getElementById('lightbox-video-like-count');
        
        if (likeBtn && likeCount) {
            // Get video ID (use URL as unique identifier if no ID provided)
            var videoId = video.id || btoa(video.url); // Base64 encode URL as fallback ID
            
            // Load like state and count from database
            fetch('/api/video-likes/' + encodeURIComponent(videoId))
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    // Set initial state from database
                    var isLiked = data.user_liked || false;
                    var totalLikes = data.total_likes || 0;
                    
                    likeCount.textContent = totalLikes;
                    likeBtn.setAttribute('data-liked', isLiked ? 'true' : 'false');
                    likeBtn.setAttribute('data-video-id', videoId);
                    
                    var icon = likeBtn.querySelector('i');
                    if (isLiked) {
                        likeBtn.classList.add('liked');
                        if (icon) icon.className = 'ph-fill ph-thumbs-up';
                    } else {
                        likeBtn.classList.remove('liked');
                        if (icon) icon.className = 'ph ph-thumbs-up';
                    }
                })
                .catch(function(err) {
                    console.error('Failed to load like state:', err);
                    // Set defaults on error
                    likeCount.textContent = video.likes || 0;
                    likeBtn.setAttribute('data-liked', 'false');
                    likeBtn.setAttribute('data-video-id', videoId);
                    var icon = likeBtn.querySelector('i');
                    if (icon) icon.className = 'ph ph-thumbs-up';
                });
            
            // Add click handler - toggle like on/off with database persistence
            likeBtn.onclick = function() {
                var isLiked = likeBtn.getAttribute('data-liked') === 'true';
                var currentCount = parseInt(likeCount.textContent) || 0;
                var videoId = likeBtn.getAttribute('data-video-id');
                var icon = likeBtn.querySelector('i');
                
                if (!isLiked) {
                    // Like the video - save to database
                    likeBtn.classList.add('liked');
                    likeBtn.setAttribute('data-liked', 'true');
                    if (icon) icon.className = 'ph-fill ph-thumbs-up';
                    likeCount.textContent = currentCount + 1;
                    
                    // Save to database
                    fetch('/api/video-likes', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            video_id: videoId,
                            video_url: video.url,
                            product_id: currentProduct?.id || null,
                            action: 'like'
                        })
                    })
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (data.success) {
                            // Update count with actual database count
                            likeCount.textContent = data.total_likes;
                        }
                    })
                    .catch(function(err) {
                        console.error('Failed to save like:', err);
                        // Revert on error
                        likeBtn.classList.remove('liked');
                        likeBtn.setAttribute('data-liked', 'false');
                        if (icon) icon.className = 'ph ph-thumbs-up';
                        likeCount.textContent = currentCount;
                    });
                } else {
                    // Unlike the video - remove from database
                    likeBtn.classList.remove('liked');
                    likeBtn.setAttribute('data-liked', 'false');
                    if (icon) icon.className = 'ph ph-thumbs-up';
                    likeCount.textContent = Math.max(0, currentCount - 1);
                    
                    // Remove from database
                    fetch('/api/video-likes/' + encodeURIComponent(videoId), {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    })
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (data.success) {
                            // Update count with actual database count
                            likeCount.textContent = data.total_likes;
                        }
                    })
                    .catch(function(err) {
                        console.error('Failed to remove like:', err);
                        // Revert on error
                        likeBtn.classList.add('liked');
                        likeBtn.setAttribute('data-liked', 'true');
                        if (icon) icon.className = 'ph-fill ph-thumbs-up';
                        likeCount.textContent = currentCount;
                    });
                }
            };
        }
        
        // Add share button handler
        var shareBtn = document.getElementById('lightbox-video-share-btn');
        if (shareBtn) {
            shareBtn.onclick = function() {
                // Share current video URL
                if (navigator.share && video.url) {
                    navigator.share({
                        title: currentProduct?.name || 'Product Video',
                        text: 'Check out this video!',
                        url: video.url
                    }).catch(function(err) {
                        console.log('Share failed:', err);
                    });
                } else {
                    // Fallback: copy to clipboard
                    if (video.url) {
                        navigator.clipboard.writeText(video.url).then(function() {
                            // Show copied notification with Phosphor icon
                            var originalHTML = shareBtn.innerHTML;
                            shareBtn.innerHTML = '<i class="ph-fill ph-check"></i>';
                            setTimeout(function() {
                                shareBtn.innerHTML = originalHTML;
                            }, 2000);
                        });
                    }
                }
            };
        }
        
        // Store current video index
        currentProduct.currentVideoIndex = activeIndex;
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
