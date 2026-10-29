(function() {
	//#region platform/plugins/ecommerce/resources/js/pv-gallery.js
	/**
	* PV Custom Gallery System
	* - Hover to preview AND activate (no click needed)
	* - Click counter overlay to open lightbox
	* - Variation change jumps to first variation image
	* - Amazon-style image lens zoom on hover
	*/
	(function() {
		"use strict";
		class PVGallery {
			constructor(container) {
				console.log("[PV GALLERY] Constructor called with container:", container);
				this.container = container;
				this.mainImage = container.querySelector("#pv-main-image");
				this.thumbnails = container.querySelectorAll(".pv-thumb:not(.pv-thumb-video)");
				this.videoThumbs = container.querySelectorAll(".pv-thumb-video");
				this.currentIndex = parseInt(container.getAttribute("data-default-index")) || 0;
				this.variationMap = JSON.parse(container.getAttribute("data-variation-map") || "{}");
				this.allImages = [];
				this.imageUrls = [];
				this.lightboxVideos = [];
				console.log("[PV GALLERY] Initialized with:", {
					mainImage: this.mainImage,
					thumbnails: this.thumbnails.length,
					videoThumbs: this.videoThumbs.length,
					variationMap: this.variationMap
				});
				this.init();
			}
			init() {
				this.thumbnails.forEach((thumb) => {
					const imageUrl = thumb.getAttribute("data-image");
					const variationId = thumb.getAttribute("data-variation-id");
					const dataIndex = parseInt(thumb.getAttribute("data-index"));
					this.allImages.push({
						index: dataIndex,
						url: imageUrl,
						variationId,
						thumb
					});
				});
				this.initLightGallery();
				this.setActive(this.currentIndex);
				this.thumbnails.forEach((thumb) => {
					thumb.addEventListener("mouseenter", () => {
						const index = parseInt(thumb.getAttribute("data-index"));
						this.jumpToImage(index);
					});
					thumb.addEventListener("click", (e) => {
						if (thumb.querySelector(".pv-thumb-overlay")) {
							e.preventDefault();
							e.stopPropagation();
							const index = parseInt(thumb.getAttribute("data-index"));
							this.openLightboxAtIndex(index);
						}
					});
				});
				this.videoThumbs.forEach((videoThumb, vIndex) => {
					videoThumb.addEventListener("mouseenter", () => {
						this.showVideoPreview(videoThumb);
					});
					videoThumb.addEventListener("click", (e) => {
						e.preventDefault();
						e.stopPropagation();
						this.openLightboxWithVideo(vIndex);
					});
				});
				if (this.mainImage) {
					this.mainImage.style.cursor = "crosshair";
					this.mainImage.addEventListener("click", () => {
						this.openLightbox();
					});
				}
				this.initLens();
				this.initMobile();
				document.addEventListener("ecommerce.change-variation", (e) => {
					console.log("[PV GALLERY] Received ecommerce.change-variation event", e.detail);
					this.handleVariationChange(e.detail);
				});
			}
			initLightGallery() {
				this.imageUrls = [];
				this.allImages.forEach((img) => {
					this.imageUrls.push(img.url);
				});
				this.lightboxVideos = [];
				this.videoThumbs.forEach((videoThumb) => {
					const videoUrl = videoThumb.getAttribute("data-video-url");
					const videoProvider = videoThumb.getAttribute("data-video-provider");
					const videoThumbnail = videoThumb.getAttribute("data-video-thumbnail");
					this.lightboxVideos.push({
						url: videoUrl,
						provider: videoProvider,
						thumbnail: videoThumbnail || "",
						title: ""
					});
				});
			}
			openLightbox() {
				var _document$querySelect;
				if (typeof window.openProductLightbox !== "function") {
					console.warn("Product lightbox not available");
					return;
				}
				const productName = ((_document$querySelect = document.querySelector(".ps-product__info h1, .product-name, h1.product-title")) === null || _document$querySelect === void 0 ? void 0 : _document$querySelect.textContent) || "Product";
				window.openProductLightbox({
					name: productName,
					images: this.imageUrls,
					videos: this.lightboxVideos,
					currentIndex: this.currentIndex,
					openTab: "images"
				});
			}
			openLightboxAtIndex(index) {
				this.currentIndex = index;
				this.openLightbox();
			}
			openLightboxWithVideo(videoIndex) {
				var _document$querySelect2;
				if (typeof window.openProductLightbox !== "function") {
					console.warn("Product lightbox not available");
					return;
				}
				const productName = ((_document$querySelect2 = document.querySelector(".ps-product__info h1, .product-name, h1.product-title")) === null || _document$querySelect2 === void 0 ? void 0 : _document$querySelect2.textContent) || "Product";
				window.openProductLightbox({
					name: productName,
					images: this.imageUrls,
					videos: this.lightboxVideos,
					currentVideoIndex: videoIndex,
					openTab: "videos"
				});
			}
			jumpToImage(index) {
				const imageData = this.allImages.find((img) => img.index === index);
				if (!imageData || !this.mainImage) return;
				const mainContainer = this.mainImage.parentElement.classList.contains("pv-slide-track") ? this.mainImage.parentElement.parentElement : this.mainImage.parentElement;
				const videoFacade = mainContainer === null || mainContainer === void 0 ? void 0 : mainContainer.querySelector(".pv-video-facade");
				if (videoFacade) videoFacade.style.display = "none";
				this.mainImage.style.opacity = "0.5";
				this.mainImage.style.display = "block";
				this.mainImage.src = imageData.url;
				this.mainImage.srcset = "";
				this.mainImage.onload = () => {
					this.mainImage.style.opacity = "1";
					this.mainImage.style.transition = "opacity 0.2s ease";
				};
				this.setActive(index);
				this.currentIndex = index;
			}
			setActive(index) {
				this.thumbnails.forEach((t) => t.classList.remove("pv-active"));
				const imageData = this.allImages.find((img) => img.index === index);
				if (imageData && imageData.thumb) imageData.thumb.classList.add("pv-active");
			}
			showVideoPreview(videoThumb) {
				const videoUrl = videoThumb.getAttribute("data-video-url");
				const videoProvider = videoThumb.getAttribute("data-video-provider");
				const videoThumbnail = videoThumb.getAttribute("data-video-thumbnail");
				if (!videoUrl || !this.mainImage) return;
				const rawParent = this.mainImage.parentElement;
				const mainContainer = rawParent && rawParent.classList.contains("pv-slide-track") ? rawParent.parentElement : rawParent;
				if (!mainContainer) return;
				let videoFacade = mainContainer.querySelector(".pv-video-facade");
				if (!videoFacade) {
					videoFacade = document.createElement("div");
					videoFacade.className = "pv-video-facade";
					mainContainer.appendChild(videoFacade);
				}
				videoFacade.style.cssText = "position:absolute;top:0;left:0;width:100%;height:100%;display:none;cursor:pointer;background:#000;border-radius:10px;overflow:hidden;z-index:5;";
				let facadeHtml = "";
				if (videoThumbnail) facadeHtml += `<img src="${videoThumbnail}" style="width:100%;height:100%;object-fit:contain;display:block;" alt="Video thumbnail" />`;
				else facadeHtml += "<div style=\"width:100%;height:100%;background:#111;\"></div>";
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
				this.mainImage.style.display = "none";
				videoFacade.style.display = "block";
				videoFacade.onclick = (e) => {
					e.preventDefault();
					e.stopPropagation();
					const videoIndex = Array.from(this.videoThumbs).indexOf(videoThumb);
					this.openLightboxWithVideo(videoIndex);
				};
				this.videoThumbs.forEach((v) => v.classList.remove("pv-active"));
				videoThumb.classList.add("pv-active");
				const resetVideo = () => {
					if (videoFacade) videoFacade.style.display = "none";
					if (this.mainImage) this.mainImage.style.display = "block";
					this.videoThumbs.forEach((v) => v.classList.remove("pv-active"));
				};
				this.thumbnails.forEach((thumb) => {
					thumb.removeEventListener("mouseenter", resetVideo);
					thumb.addEventListener("mouseenter", resetVideo, { once: false });
				});
			}
			loadActualVideo(container, videoUrl, videoProvider) {
				container.innerHTML = "";
				container.style.cursor = "default";
				container.onclick = null;
				if (videoProvider === "video") {
					const video = document.createElement("video");
					video.controls = true;
					video.autoplay = true;
					video.style.cssText = "width:100%;height:100%;object-fit:contain;";
					video.src = videoUrl;
					container.appendChild(video);
				} else if (videoProvider === "youtube" || videoProvider === "vimeo") {
					const iframe = document.createElement("iframe");
					iframe.style.cssText = "width:100%;height:100%;border:0;";
					iframe.src = videoUrl + (videoUrl.includes("?") ? "&" : "?") + "autoplay=1";
					iframe.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
					iframe.allowFullscreen = true;
					container.appendChild(iframe);
				}
			}
			handleVariationChange(data) {
				console.log("[PV GALLERY] handleVariationChange called", data);
				const variationId = data.variationId || data.variation_id || data.id || data.productId || data.product_id;
				console.log("[PV GALLERY] Extracted variationId:", variationId);
				if (!variationId) {
					console.log("[PV GALLERY] No variationId found, exiting");
					return;
				}
				const variationImages = this.allImages.filter((img) => img.variationId == variationId);
				console.log("[PV GALLERY] Found variation images:", variationImages.length, variationImages);
				if (variationImages.length > 0) {
					console.log("[PV GALLERY] Calling reorderThumbnails with variationId:", variationId);
					this.reorderThumbnails(variationId);
					const firstVariationImage = variationImages[0];
					console.log("[PV GALLERY] Jumping to first variation image index:", firstVariationImage.index);
					this.jumpToImage(firstVariationImage.index);
					this.scrollThumbnailsToShow(firstVariationImage.index);
				} else {
					console.log("[PV GALLERY] No variation images found, checking variationMap");
					let targetIndex = this.variationMap[variationId];
					console.log("[PV GALLERY] VariationMap lookup:", {
						variationId,
						targetIndex,
						fullMap: this.variationMap
					});
					if (targetIndex !== void 0) {
						console.log("[PV GALLERY] Found in variationMap, jumping to index:", targetIndex);
						this.jumpToImage(targetIndex);
						this.scrollThumbnailsToShow(targetIndex);
						return;
					}
					const firstVariationImage = this.allImages.find((img) => img.variationId == variationId);
					if (firstVariationImage) {
						console.log("[PV GALLERY] Found variation image by ID match:", firstVariationImage.index);
						this.jumpToImage(firstVariationImage.index);
						this.scrollThumbnailsToShow(firstVariationImage.index);
					} else console.log("[PV GALLERY] No variation image found at all");
				}
			}
			reorderThumbnails(variationId) {
				console.log("[PV GALLERY] reorderThumbnails called with variationId:", variationId);
				const thumbContainer = this.container.querySelector(".pv-gallery-thumbnails");
				if (!thumbContainer) {
					console.log("[PV GALLERY] No .pv-gallery-thumbnails found");
					return;
				}
				console.log("[PV GALLERY] Found thumb container, current thumbnails:", this.thumbnails.length);
				const videoThumbs = Array.from(thumbContainer.querySelectorAll(".pv-thumb-video"));
				const allThumbs = Array.from(this.thumbnails);
				const variationThumbs = [];
				const otherThumbs = [];
				allThumbs.forEach((thumb) => {
					if (thumb.getAttribute("data-variation-id") == variationId) variationThumbs.push(thumb);
					else otherThumbs.push(thumb);
				});
				console.log("[PV GALLERY] Separated thumbnails:", {
					variation: variationThumbs.length,
					other: otherThumbs.length,
					videos: videoThumbs.length
				});
				const firstItem = videoThumbs[0] || allThumbs[0];
				if (!firstItem) {
					console.log("[PV GALLERY] No thumbnails found to reorder");
					return;
				}
				const insertAfter = videoThumbs.length > 0 ? videoThumbs[videoThumbs.length - 1] : null;
				variationThumbs.forEach((thumb, idx) => {
					console.log("[PV GALLERY] Moving variation thumb to top:", thumb.getAttribute("data-index"));
					if (idx === 0) {
						if (insertAfter) thumbContainer.insertBefore(thumb, insertAfter.nextSibling);
						else thumbContainer.insertBefore(thumb, firstItem);
					} else thumbContainer.insertBefore(thumb, variationThumbs[idx - 1].nextSibling);
					if (idx < 3) {
						thumb.classList.remove("pv-thumb-hidden");
						const overlay = thumb.querySelector(".pv-thumb-overlay");
						if (overlay) overlay.remove();
					} else if (idx === 3) {
						thumb.classList.add("pv-thumb-hidden");
						const remainingCount = variationThumbs.length - 3;
						if (remainingCount > 0 && variationThumbs[2]) {
							let overlay = variationThumbs[2].querySelector(".pv-thumb-overlay");
							if (!overlay) {
								overlay = document.createElement("span");
								overlay.className = "pv-thumb-overlay";
								variationThumbs[2].appendChild(overlay);
							}
							overlay.textContent = `+${remainingCount}`;
						}
					} else thumb.classList.add("pv-thumb-hidden");
				});
				this.thumbnails = thumbContainer.querySelectorAll(".pv-thumb:not(.pv-thumb-video)");
				console.log("[PV GALLERY] Reordering complete, new thumbnail count:", this.thumbnails.length);
			}
			scrollThumbnailsToShow(index) {
				const imageData = this.allImages.find((img) => img.index === index);
				if (!imageData || !imageData.thumb) return;
				const thumb = imageData.thumb;
				if (!thumb.parentElement) return;
				thumb.scrollIntoView({
					behavior: "smooth",
					block: "nearest",
					inline: "center"
				});
			}
			initMobile() {
				this._mainContainer = this.mainImage ? this.mainImage.parentElement : null;
				if (!this._mainContainer) return;
				this._mobileSlides = [];
				this.allImages.forEach((img) => this._mobileSlides.push({
					type: "image",
					data: img
				}));
				this.videoThumbs.forEach((vt, i) => this._mobileSlides.push({
					type: "video",
					index: i,
					el: vt
				}));
				this._mobileIndex = this.currentIndex;
				this._dotsEl = document.createElement("div");
				this._dotsEl.className = "pv-mobile-dots";
				this._mainContainer.parentElement.insertBefore(this._dotsEl, this._mainContainer.nextSibling);
				this._updateDots();
				const mc = this._mainContainer;
				mc.style.height = mc.offsetHeight + "px";
				mc._heightLocked = true;
				if (this.mainImage) this.mainImage.style.cssText = "position:absolute;top:0;left:0;width:100%;height:100%;object-fit:contain;display:block;cursor:crosshair;";
				let startX = 0, startY = 0, isDragging = false, liveEl = null, nextEl = null, prevEl = null;
				const SENSITIVITY = 1.2;
				this._mainContainer.addEventListener("touchstart", (e) => {
					startX = e.touches[0].clientX;
					startY = e.touches[0].clientY;
					isDragging = true;
					liveEl = this._getVisibleEl();
					const total = this._mobileSlides.length;
					const nextIndex = (this._mobileIndex + 1) % total;
					const prevIndex = (this._mobileIndex - 1 + total) % total;
					nextEl = this._createSlideElement(this._mobileSlides[nextIndex]);
					nextEl.style.left = "100%";
					nextEl.dataset.preloaded = "true";
					mc.appendChild(nextEl);
					prevEl = this._createSlideElement(this._mobileSlides[prevIndex]);
					prevEl.style.left = "-100%";
					prevEl.dataset.preloaded = "true";
					mc.appendChild(prevEl);
				}, { passive: true });
				this._mainContainer.addEventListener("touchmove", (e) => {
					if (!isDragging || !liveEl) return;
					const dx = (e.touches[0].clientX - startX) * SENSITIVITY;
					const dy = e.touches[0].clientY - startY;
					if (Math.abs(dy) > Math.abs(dx / SENSITIVITY)) return;
					liveEl.style.transform = `translateX(${dx}px)`;
					liveEl.style.transition = "none";
					if (nextEl) {
						nextEl.style.transform = `translateX(${dx}px)`;
						nextEl.style.transition = "none";
					}
					if (prevEl) {
						prevEl.style.transform = `translateX(${dx}px)`;
						prevEl.style.transition = "none";
					}
				}, { passive: true });
				this._mainContainer.addEventListener("touchend", (e) => {
					if (!isDragging) return;
					isDragging = false;
					const dx = (e.changedTouches[0].clientX - startX) * SENSITIVITY;
					const dy = e.changedTouches[0].clientY - startY;
					if (Math.abs(dx) < 30 || Math.abs(dy) > Math.abs(dx / SENSITIVITY)) {
						if (liveEl) {
							liveEl.style.transform = "";
							liveEl.style.transition = "";
						}
						if (nextEl && mc.contains(nextEl)) mc.removeChild(nextEl);
						if (prevEl && mc.contains(prevEl)) mc.removeChild(prevEl);
						nextEl = null;
						prevEl = null;
						return;
					}
					const total = this._mobileSlides.length;
					if (dx < 0) {
						this._mobileGoTo((this._mobileIndex + 1) % total, "left", {
							outEl: liveEl,
							inEl: nextEl,
							dx
						});
						if (prevEl && mc.contains(prevEl)) mc.removeChild(prevEl);
					} else {
						this._mobileGoTo((this._mobileIndex - 1 + total) % total, "right", {
							outEl: liveEl,
							inEl: prevEl,
							dx
						});
						if (nextEl && mc.contains(nextEl)) mc.removeChild(nextEl);
					}
					nextEl = null;
					prevEl = null;
				}, { passive: true });
			}
			_createSlideElement(slide) {
				let el;
				if (slide.type === "image") {
					el = document.createElement("img");
					el.src = slide.data.url;
					el.alt = this.mainImage ? this.mainImage.alt : "";
					el.style.cssText = "position:absolute;top:0;width:100%;height:100%;object-fit:contain;display:block;cursor:crosshair;";
				} else {
					const vt = slide.el;
					const thumb = vt.getAttribute("data-video-thumbnail");
					el = document.createElement("div");
					el.className = "pv-video-facade";
					el.style.cssText = "position:absolute;top:0;width:100%;height:100%;background:#000;border-radius:10px;overflow:hidden;cursor:pointer;z-index:5;";
					el.innerHTML = thumb ? `<img src="${thumb}" style="width:100%;height:100%;object-fit:contain;display:block;" />` : `<div style="width:100%;height:100%;background:#111;"></div>`;
					el.innerHTML += `<div class="pv-play-button-large"><svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M6 4v16a1 1 0 0 0 1.524 .852l13 -8a1 1 0 0 0 0 -1.704l-13 -8a1 1 0 0 0 -1.524 .852z"/></svg></div>`;
					const vi = Array.from(this.videoThumbs).indexOf(vt);
					el.onclick = (e) => {
						e.preventDefault();
						e.stopPropagation();
						this.openLightboxWithVideo(vi);
					};
				}
				return el;
			}
			_getVisibleEl() {
				const mc = this._mainContainer;
				if (!mc) return this.mainImage;
				const facade = mc.querySelector(".pv-video-facade");
				if (facade && facade.style.display !== "none") return facade;
				return this.mainImage;
			}
			_mobileGoTo(index, direction, preloaded = null) {
				const total = this._mobileSlides.length;
				if (total === 0) return;
				index = (index % total + total) % total;
				if (index === this._mobileIndex) return;
				if (this._mobileAnimating) return;
				this._mobileAnimating = true;
				this._swipeDirection = direction;
				this._mobileIndex = index;
				this._updateDots();
				const slide = this._mobileSlides[index];
				const mc = this._mainContainer;
				if (!mc) {
					this._mobileAnimating = false;
					return;
				}
				let outEl, inEl;
				if (preloaded && preloaded.outEl && preloaded.inEl) {
					outEl = preloaded.outEl;
					inEl = preloaded.inEl;
					outEl.style.transform = "";
					inEl.style.transform = "";
					outEl.style.transition = "left 0.28s ease";
					inEl.style.transition = "left 0.28s ease";
					requestAnimationFrame(() => {
						const fromRight = direction === "left";
						inEl.style.left = "0";
						outEl.style.left = fromRight ? "-100%" : "100%";
					});
				} else {
					outEl = this._getVisibleEl();
					inEl = this._createSlideElement(slide);
					if (!mc._heightLocked) {
						mc.style.height = mc.offsetHeight + "px";
						mc._heightLocked = true;
					}
					const fromRight = direction === "left";
					inEl.style.left = fromRight ? "100%" : "-100%";
					mc.appendChild(inEl);
					if (outEl) {
						outEl.style.position = "absolute";
						outEl.style.top = "0";
						outEl.style.left = "0";
						outEl.style.width = "100%";
						outEl.style.height = "100%";
						outEl.style.transition = "left 0.28s ease";
					}
					inEl.style.transition = "left 0.28s ease";
					requestAnimationFrame(() => requestAnimationFrame(() => {
						inEl.style.left = "0";
						if (outEl) outEl.style.left = fromRight ? "-100%" : "100%";
					}));
				}
				setTimeout(() => {
					if (outEl && mc.contains(outEl)) mc.removeChild(outEl);
					inEl.style.transition = "none";
					inEl.style.left = "0";
					if (slide.type === "image") {
						this.mainImage = inEl;
						this.mainImage.addEventListener("click", () => this.openLightbox());
						this.jumpToImage(slide.data.index);
					}
					this._mobileAnimating = false;
				}, 300);
			}
			_updateDots() {
				if (!this._dotsEl) return;
				const total = this._mobileSlides.length;
				if (total <= 1) {
					this._dotsEl.innerHTML = "";
					return;
				}
				const active = this._mobileIndex;
				const MAX = 5;
				let start = Math.max(0, active - 2);
				let end = Math.min(total - 1, start + MAX - 1);
				if (end - start < 4) start = Math.max(0, end - MAX + 1);
				const prevStart = this._prevStart || 0;
				const isInMiddleRange = active >= 2 && active < total - 2;
				const didShift = start !== prevStart && isInMiddleRange;
				const direction = this._swipeDirection || "left";
				this._prevStart = start;
				let tempStart = start;
				let tempEnd = end;
				if (didShift) {
					if (direction === "left") {
						tempStart = active - 3;
						tempEnd = tempStart + MAX - 1;
					} else {
						tempStart = active - 1;
						tempEnd = tempStart + MAX - 1;
					}
					tempStart = Math.max(0, tempStart);
					tempEnd = Math.min(total - 1, tempEnd);
				}
				let html = "";
				for (let i = didShift ? tempStart : start; i <= (didShift ? tempEnd : end); i++) {
					const dist = Math.abs(i - active);
					const size = dist === 0 ? 10 : dist === 1 ? 7 : 5;
					html += `<span class="pv-dot${i === active ? " pv-dot--active" : ""}"
                    style="width:${size}px;height:${size}px;opacity:${dist === 0 ? 1 : dist === 1 ? .6 : .35}"
                    data-index="${i}"
                    data-slide-index="${i}"></span>`;
				}
				this._dotsEl.innerHTML = html;
				if (didShift) {
					let finalHtml = "";
					for (let i = start; i <= end; i++) {
						const dist = Math.abs(i - active);
						const size = dist === 0 ? 10 : dist === 1 ? 7 : 5;
						finalHtml += `<span class="pv-dot${i === active ? " pv-dot--active" : ""}"
                        style="width:${size}px;height:${size}px;opacity:${dist === 0 ? 1 : dist === 1 ? .6 : .35}"
                        data-index="${i}"
                        data-slide-index="${i}"></span>`;
					}
					setTimeout(() => {
						this._dotsEl.classList.add("pv-dots-transitioning");
						this._dotsEl.classList.add(direction === "left" ? "pv-dots-slide-forward" : "pv-dots-slide-backward");
						this._dotsEl.innerHTML = finalHtml;
						setTimeout(() => {
							this._dotsEl.classList.remove("pv-dots-slide-forward", "pv-dots-slide-backward", "pv-dots-transitioning");
						}, 400);
					}, 50);
				}
				setTimeout(() => {
					this._dotsEl.querySelectorAll(".pv-dot").forEach((dot) => {
						dot.addEventListener("click", () => {
							const to = parseInt(dot.dataset.index);
							this._mobileGoTo(to, to > this._mobileIndex ? "left" : "right");
						});
					});
				}, didShift ? 460 : 0);
			}
			initLens() {
				const mainContainer = this.mainImage ? this.mainImage.parentElement : null;
				if (!mainContainer || !this.mainImage) return;
				this.lensEl = document.createElement("div");
				this.lensEl.className = "pv-lens";
				mainContainer.appendChild(this.lensEl);
				this.zoomEl = document.createElement("div");
				this.zoomEl.className = "pv-zoom-result";
				document.body.appendChild(this.zoomEl);
				this._lensMouseMove = this._onLensMouseMove.bind(this);
				this._lensMouseLeave = this._onLensMouseLeave.bind(this);
				mainContainer.addEventListener("mousemove", this._lensMouseMove);
				mainContainer.addEventListener("mouseleave", this._lensMouseLeave);
				mainContainer.style.cursor = "crosshair";
				mainContainer.addEventListener("click", (e) => {
					if (e.target === this.mainImage) return;
					if (e.target.closest(".pv-gallery-actions, .pv-action-btn")) return;
					this.openLightbox();
				});
			}
			_onLensMouseMove(e) {
				const img = this.mainImage;
				if (!img || img.style.display === "none") {
					this._onLensMouseLeave();
					return;
				}
				const imgRect = img.getBoundingClientRect();
				const containerRect = img.parentElement.getBoundingClientRect();
				let x = e.clientX - imgRect.left;
				let y = e.clientY - imgRect.top;
				const cX = e.clientX - containerRect.left;
				const cY = e.clientY - containerRect.top;
				if (cX < 0 || cY < 0 || cX > containerRect.width || cY > containerRect.height) {
					this._onLensMouseLeave();
					return;
				}
				x = Math.max(0, Math.min(x, imgRect.width));
				y = Math.max(0, Math.min(y, imgRect.height));
				const zoomPanelTop = 10;
				const zoomPanelHeight = window.innerHeight - 20;
				const galleryWrapper = document.querySelector(".ps-product__gallery-wrapper");
				const infoWrapper = document.querySelector(".ps-product__info-wrapper");
				let zoomLeft, zoomTop, zoomWidth, zoomHeight;
				if (infoWrapper && galleryWrapper) {
					const infoRect = infoWrapper.getBoundingClientRect();
					zoomLeft = infoRect.left;
					zoomTop = zoomPanelTop;
					zoomWidth = infoRect.width;
					zoomHeight = zoomPanelHeight;
				} else {
					zoomLeft = containerRect.right + 10;
					zoomTop = zoomPanelTop;
					zoomWidth = Math.min(500, window.innerWidth - zoomLeft - 10);
					zoomHeight = zoomPanelHeight;
				}
				const zoomRatio = zoomWidth / zoomHeight;
				let lensW = Math.min(200, imgRect.width * .45);
				let lensH = lensW / zoomRatio;
				if (lensH > imgRect.height * .45) {
					lensH = imgRect.height * .45;
					lensW = lensH * zoomRatio;
				}
				let lensLeft = x - lensW / 2;
				let lensTop = y - lensH / 2;
				lensLeft = Math.max(0, Math.min(lensLeft, imgRect.width - lensW));
				lensTop = Math.max(0, Math.min(lensTop, imgRect.height - lensH));
				const offsetLeft = imgRect.left - containerRect.left + lensLeft;
				const offsetTop = imgRect.top - containerRect.top + lensTop;
				this.lensEl.style.left = offsetLeft + "px";
				this.lensEl.style.top = offsetTop + "px";
				this.lensEl.style.width = lensW + "px";
				this.lensEl.style.height = lensH + "px";
				this.lensEl.style.display = "block";
				this.zoomEl.style.left = zoomLeft + "px";
				this.zoomEl.style.top = zoomTop + "px";
				this.zoomEl.style.width = zoomWidth + "px";
				this.zoomEl.style.height = zoomHeight + "px";
				this.zoomEl.style.display = "block";
				const ratioX = zoomWidth / lensW;
				const ratioY = zoomHeight / lensH;
				const bgW = imgRect.width * ratioX;
				const bgH = imgRect.height * ratioY;
				const bgX = lensLeft * ratioX;
				const bgY = lensTop * ratioY;
				this.zoomEl.style.backgroundImage = "url(" + img.src + ")";
				this.zoomEl.style.backgroundSize = bgW + "px " + bgH + "px";
				this.zoomEl.style.backgroundPosition = "-" + bgX + "px -" + bgY + "px";
			}
			_onLensMouseLeave() {
				if (this.lensEl) this.lensEl.style.display = "none";
				if (this.zoomEl) this.zoomEl.style.display = "none";
			}
			destroyLens() {
				const mainContainer = this.mainImage ? this.mainImage.parentElement : null;
				if (mainContainer) {
					mainContainer.removeEventListener("mousemove", this._lensMouseMove);
					mainContainer.removeEventListener("mouseleave", this._lensMouseLeave);
				}
				if (this.lensEl) this.lensEl.remove();
				if (this.zoomEl) this.zoomEl.remove();
			}
		}
		function initGalleries() {
			const galleries = document.querySelectorAll(".pv-gallery");
			console.log("[PV GALLERY INIT] Found galleries:", galleries.length, galleries);
			galleries.forEach((gallery) => {
				console.log("[PV GALLERY INIT] Initializing gallery:", gallery);
				new PVGallery(gallery);
			});
			initShareButton();
			initActionButtons();
		}
		function initShareButton() {
			document.querySelectorAll(".pv-share-btn").forEach((btn) => {
				if (btn._pvShareBound) return;
				btn._pvShareBound = true;
				btn.addEventListener("click", function(e) {
					e.preventDefault();
					e.stopPropagation();
					const url = this.getAttribute("data-url");
					const title = this.getAttribute("data-title");
					if (navigator.share) navigator.share({
						title,
						url
					}).catch((error) => console.log("Error sharing:", error));
					else navigator.clipboard.writeText(url).then(() => {
						if (typeof Theme !== "undefined" && typeof Theme.showSuccess === "function") Theme.showSuccess("Link copied to clipboard!");
						else alert("Link copied to clipboard!");
					});
				});
			});
		}
		function initActionButtons() {
			/**
			* Update icon state (same function as product-card-enhanced.js)
			*/
			function updateIconState($button, isFilled) {
				var $icon = $button.find("i.ph");
				if (isFilled) {
					if (!$icon.hasClass("ph-fill")) $icon.addClass("ph-fill");
				} else $icon.removeClass("ph-fill");
			}
			$(document).on("click", ".pv-action-btn.js-add-to-wishlist-button", function(e) {
				var $btn = $(this);
				var productId = $btn.data("product-id");
				updateIconState($btn, true);
				$(".ec-card__float-btn--wishlist[data-product-id=\"" + productId + "\"], .pv-action-btn.js-add-to-wishlist-button[data-product-id=\"" + productId + "\"], .pv-action-btn.js-remove-from-wishlist-button[data-product-id=\"" + productId + "\"]").each(function() {
					updateIconState($(this), true);
				});
			});
			$(document).on("click", ".pv-action-btn.js-remove-from-wishlist-button", function(e) {
				var $btn = $(this);
				var productId = $btn.data("product-id");
				updateIconState($btn, false);
				$(".ec-card__float-btn--wishlist[data-product-id=\"" + productId + "\"], .pv-action-btn.js-add-to-wishlist-button[data-product-id=\"" + productId + "\"], .pv-action-btn.js-remove-from-wishlist-button[data-product-id=\"" + productId + "\"]").each(function() {
					updateIconState($(this), false);
				});
			});
			$(document).on("click", ".pv-action-btn.js-add-to-compare-button", function(e) {
				var $btn = $(this);
				var productId = $btn.data("product-id");
				updateIconState($btn, true);
				$(".ec-card__float-btn--compare[data-product-id=\"" + productId + "\"], .pv-action-btn.js-add-to-compare-button[data-product-id=\"" + productId + "\"], .pv-action-btn.js-remove-from-compare-button[data-product-id=\"" + productId + "\"]").each(function() {
					updateIconState($(this), true);
				});
			});
			$(document).on("click", ".pv-action-btn.js-remove-from-compare-button", function(e) {
				var $btn = $(this);
				var productId = $btn.data("product-id");
				updateIconState($btn, false);
				$(".ec-card__float-btn--compare[data-product-id=\"" + productId + "\"], .pv-action-btn.js-add-to-compare-button[data-product-id=\"" + productId + "\"], .pv-action-btn.js-remove-from-compare-button[data-product-id=\"" + productId + "\"]").each(function() {
					updateIconState($(this), false);
				});
			});
		}
		if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", initGalleries);
		else initGalleries();
	})();
	//#endregion
})();

//# sourceMappingURL=pv-gallery.js.map