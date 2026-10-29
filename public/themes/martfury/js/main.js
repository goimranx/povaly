(function() {
	//#region platform/themes/martfury/assets/js/main.js
	(function($) {
		"use strict";
		$(document).on("error", "img", function() {});
		window.addEventListener("error", function(e) {
			if (e.target && e.target.tagName === "IMG") {
				e.preventDefault();
				e.stopPropagation();
				return false;
			}
		}, true);
		let MartApp = window.MartApp || {};
		window.MartApp = MartApp;
		MartApp.showSuccess = function(message) {
			console.log("🔔 showSuccess called:", message);
			console.trace("Called from:");
			if (typeof Theme !== "undefined" && Theme.showSuccess) Theme.showSuccess(message);
		};
		MartApp.showError = function(message) {
			console.log("❌ showError called:", message);
			console.trace("Called from:");
			if (typeof Theme !== "undefined" && Theme.showError) Theme.showError(message);
		};
		window.showSuccess = MartApp.showSuccess;
		window.showError = MartApp.showError;
		let isRTL = $("body").prop("dir") === "rtl";
		function backgroundImage() {
			$("[data-background]").each(function() {
				if ($(this).attr("data-background")) {
					let imagePath = $(this).attr("data-background");
					$(this).css({
						"background-image": "url(" + imagePath + ")",
						"background-color": "#fff"
					});
				}
			});
		}
		function siteToggleAction() {
			let navSidebar = $(".navigation--sidebar"), filterSidebar = $(".ps-filter--sidebar");
			$(".menu-toggle-open").on("click", function(e) {
				e.preventDefault();
				$(this).toggleClass("active");
				navSidebar.toggleClass("active");
				$(".ps-site-overlay").toggleClass("active");
			});
			$(".ps-toggle--sidebar").on("click", function(e) {
				e.preventDefault();
				let url = $(this).attr("href");
				$(this).toggleClass("active");
				$(this).siblings("a").removeClass("active");
				$(url).toggleClass("active");
				$(url).siblings(".ps-panel--sidebar").removeClass("active");
				if ($(this).hasClass("active")) $(".ps-site-overlay").addClass("active");
				else $(".ps-site-overlay").removeClass("active");
			});
			$("#filter-sidebar").on("click", function(e) {
				e.preventDefault();
				filterSidebar.addClass("active");
				$(".ps-site-overlay").addClass("active");
			});
			$(".ps-filter--sidebar .ps-filter__header .ps-btn--close").on("click", function(e) {
				e.preventDefault();
				filterSidebar.removeClass("active");
				$(".ps-site-overlay").removeClass("active");
			});
			$("body").on("click", function(e) {
				if ($(e.target).siblings(".ps-panel--sidebar").hasClass("active")) {
					$(".ps-panel--sidebar").removeClass("active");
					$(".ps-site-overlay").removeClass("active");
				}
			});
		}
		function subMenuToggle() {
			$(".menu--mobile .menu-item-has-children > .sub-toggle").on("click", function(e) {
				e.preventDefault();
				let current = $(this).parent(".menu-item-has-children");
				$(this).toggleClass("active");
				current.siblings().find(".sub-toggle").removeClass("active");
				current.children(".sub-menu").slideToggle(350);
				current.siblings().find(".sub-menu").slideUp(350);
				if (current.hasClass("has-mega-menu")) {
					current.children(".mega-menu").slideToggle(350);
					current.siblings(".has-mega-menu").find(".mega-menu").slideUp(350);
				}
			});
			$(".menu--mobile .has-mega-menu .mega-menu__column .sub-toggle").on("click", function(e) {
				e.preventDefault();
				let current = $(this).closest(".mega-menu__column");
				$(this).toggleClass("active");
				current.siblings().find(".sub-toggle").removeClass("active");
				current.children(".mega-menu__list").slideToggle(350);
				current.siblings().find(".mega-menu__list").slideUp(350);
			});
			if ($(document).find(".widget-product-categories").length > 0) $(document).on("click", ".widget-product-categories .menu-item-has-children > .sub-toggle", function(e) {
				e.preventDefault();
				let current = $(this).parent(".menu-item-has-children");
				$(this).toggleClass("active");
				current.siblings().find(".sub-toggle").removeClass("active");
				current.children(".sub-menu").slideToggle(350);
				current.siblings().find(".sub-menu").slideUp(350);
				if (current.hasClass("has-mega-menu")) {
					current.children(".mega-menu").slideToggle(350);
					current.siblings(".has-mega-menu").find(".mega-menu").slideUp(350);
				}
			});
		}
		let initMegaMenu = function() {
			setTimeout(function() {
				const $megaMenu = $(document).find(".mega-menu-wrapper");
				if (!$megaMenu.length) return;
				if ($(window).width() > 1200 && typeof $.fn.masonry !== "undefined") $megaMenu.masonry({
					itemSelector: ".mega-menu__column",
					columnWidth: 200,
					originLeft: !isRTL
				});
			}, 500);
		};
		function stickyHeader() {
			let header = $(".header"), checkpoint = 50;
			header.each(function() {
				if ($(this).data("sticky") === true) {
					let el = $(this);
					$(window).scroll(function() {
						if ($(this).scrollTop() > checkpoint) {
							el.addClass("header--sticky");
							initMegaMenu();
						} else el.removeClass("header--sticky");
					});
				}
			});
			let stickyCart = $("#cart-sticky");
			if (stickyCart.length > 0) $(window).scroll(function() {
				if ($(this).scrollTop() > checkpoint) stickyCart.addClass("active");
				else stickyCart.removeClass("active");
			});
		}
		function initOwlCarousel(container) {
			(container ? $(container).find(".owl-slider, .owl-carousel") : $(".owl-slider, .owl-carousel")).not(".slick-slider").each(function() {
				let el = $(this);
				if (el.hasClass("owl-loaded") || el.data("owl.carousel")) return;
				if (typeof $.fn.owlCarousel === "undefined") {
					console.error("Owl Carousel plugin is not loaded");
					return;
				}
				let dataAuto = el.data("owl-auto"), dataLoop = el.data("owl-loop"), dataSpeed = el.data("owl-speed"), dataGap = el.data("owl-gap"), dataNav = el.data("owl-nav"), dataDots = el.data("owl-dots"), dataAnimateIn = el.data("owl-animate-in") ? el.data("owl-animate-in") : "", dataAnimateOut = el.data("owl-animate-out") ? el.data("owl-animate-out") : "", dataDefaultItem = el.data("owl-item"), dataItemXS = el.data("owl-item-xs"), dataItemSM = el.data("owl-item-sm"), dataItemMD = el.data("owl-item-md"), dataItemLG = el.data("owl-item-lg"), dataItemXL = el.data("owl-item-xl"), dataNavLeft = el.data("owl-nav-left") ? el.data("owl-nav-left") : "<i class='icon-chevron-left'></i>", dataNavRight = el.data("owl-nav-right") ? el.data("owl-nav-right") : "<i class='icon-chevron-right'></i>", duration = el.data("owl-duration"), datamouseDrag = el.data("owl-mousedrag") == "on" ? true : false;
				let dataMargin = el.data("owl-margin"), dataAutoHeight = el.data("owl-autoheight"), dataCenter = el.data("owl-center"), dataMouseDrag = el.data("owl-mouse-drag"), dataTouchDrag = el.data("owl-touch-drag"), dataSmartSpeed = el.data("owl-smart-speed"), dataItems = el.data("owl-items"), dataAutoplayHoverPause = el.data("owl-autoplay-hover-pause"), dataAutoplayTimeout = el.data("owl-autoplay-timeout"), dataResponsive = el.data("owl-responsive");
				let itemCount = el.children().length;
				if (itemCount === 0) itemCount = el.find("> .ps-product, > article, > .item, > div").length;
				if (itemCount >= 2) el.addClass("owl-carousel").owlCarousel({
					rtl: isRTL,
					animateIn: dataAnimateIn,
					animateOut: dataAnimateOut,
					margin: dataMargin || dataGap || 0,
					autoplay: dataAuto,
					autoplayTimeout: dataAutoplayTimeout || dataSpeed || 5e3,
					autoplayHoverPause: dataAutoplayHoverPause !== void 0 ? dataAutoplayHoverPause : true,
					loop: dataLoop,
					nav: dataNav,
					mouseDrag: dataMouseDrag !== void 0 ? dataMouseDrag : datamouseDrag,
					touchDrag: dataTouchDrag !== void 0 ? dataTouchDrag : true,
					autoplaySpeed: duration,
					navSpeed: duration,
					dotsSpeed: duration,
					dragEndSpeed: duration,
					navText: [dataNavLeft, dataNavRight],
					dots: dataDots,
					items: dataItems || dataDefaultItem || 4,
					center: dataCenter || false,
					autoHeight: dataAutoHeight || false,
					smartSpeed: dataSmartSpeed || 450,
					responsive: dataResponsive || {
						0: { items: dataItemXS || 1 },
						480: { items: dataItemSM || 2 },
						768: { items: dataItemMD || 3 },
						992: { items: dataItemLG || 4 },
						1200: { items: dataItemXL || 5 },
						1680: { items: dataDefaultItem || 6 }
					}
				});
				else if (itemCount === 1) el.addClass("owl-carousel single-item-carousel").owlCarousel({
					rtl: isRTL,
					items: 1,
					dots: false,
					nav: false,
					loop: false,
					mouseDrag: false,
					touchDrag: false,
					responsive: {
						0: { items: 1 },
						480: { items: 1 },
						768: { items: 1 },
						992: { items: 1 },
						1200: { items: 1 },
						1680: { items: 1 }
					}
				});
				else el.addClass("owl-carousel").owlCarousel({
					rtl: isRTL,
					items: dataItems || dataDefaultItem || 4,
					nav: dataNav,
					dots: dataDots,
					loop: dataLoop,
					margin: dataMargin || dataGap || 0,
					autoplay: dataAuto,
					autoplayTimeout: dataAutoplayTimeout || dataSpeed || 5e3,
					smartSpeed: dataSmartSpeed || 450,
					responsive: dataResponsive || {
						0: { items: dataItemXS || 1 },
						480: { items: dataItemSM || 2 },
						768: { items: dataItemMD || 3 },
						992: { items: dataItemLG || 4 },
						1200: { items: dataItemXL || 5 },
						1680: { items: dataDefaultItem || 6 }
					}
				});
			});
			labelOwlDots(container);
		}
		function labelOwlDots(container) {
			(container ? $(container) : $(document)).find(".owl-dots button.owl-dot").each(function(index) {
				let $btn = $(this);
				if (!$btn.attr("aria-label")) $btn.attr("aria-label", "Go to slide " + (index + 1));
				if (!$btn.attr("type")) $btn.attr("type", "button");
			});
		}
		function owlCarouselConfig() {
			initOwlCarousel();
		}
		function mapConfig() {
			let map = $("#contact-map");
			if (map.length > 0) map.gmap3({
				address: map.data("address"),
				zoom: map.data("zoom"),
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: false
			}).marker(function(map) {
				return {
					position: map.getCenter(),
					icon: "img/marker.png"
				};
			}).infowindow({ content: map.data("address") }).then(function(infowindow) {
				let map = this.get(0);
				let marker = this.get(1);
				marker.addListener("click", function() {
					infowindow.open(map, marker);
				});
			});
			else return false;
		}
		function slickConfig() {
			let product = $(".ps-product--detail");
			if (product.length > 0) {
				let primary = product.find(".ps-product__gallery"), second = product.find(".ps-product__variants"), vertical = product.find(".ps-product__thumbnail").data("vertical");
				primary.slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					rtl: isRTL,
					asNavFor: ".ps-product__variants",
					fade: true,
					dots: false,
					infinite: false,
					arrows: primary.data("arrow"),
					prevArrow: "<button class='slick-prev slick-arrow'><i class='fa fa-angle-left'></i></button>",
					nextArrow: "<button class='slick-next slick-arrow'><i class='fa fa-angle-right'></i></button>"
				});
				second.slick({
					slidesToShow: second.data("item"),
					slidesToScroll: 1,
					rtl: isRTL,
					infinite: false,
					arrows: second.data("arrow"),
					focusOnSelect: true,
					prevArrow: "<button class='slick-prev slick-arrow'><i class='fa fa-angle-up'></i></button>",
					nextArrow: "<button class='slick-next slick-arrow'><i class='fa fa-angle-down'></i></button>",
					asNavFor: ".ps-product__gallery",
					vertical,
					responsive: [
						{
							breakpoint: 1200,
							settings: {
								arrows: second.data("arrow"),
								slidesToShow: 4,
								vertical: false,
								prevArrow: "<button class='slick-prev slick-arrow'><i class='fa fa-angle-left'></i></button>",
								nextArrow: "<button class='slick-next slick-arrow'><i class='fa fa-angle-right'></i></button>"
							}
						},
						{
							breakpoint: 992,
							settings: {
								arrows: second.data("arrow"),
								slidesToShow: 4,
								vertical: false,
								prevArrow: "<button class='slick-prev slick-arrow'><i class='fa fa-angle-left'></i></button>",
								nextArrow: "<button class='slick-next slick-arrow'><i class='fa fa-angle-right'></i></button>"
							}
						},
						{
							breakpoint: 480,
							settings: {
								slidesToShow: 3,
								vertical: false,
								prevArrow: "<button class='slick-prev slick-arrow'><i class='fa fa-angle-left'></i></button>",
								nextArrow: "<button class='slick-next slick-arrow'><i class='fa fa-angle-right'></i></button>"
							}
						}
					]
				});
			}
		}
		function tabs() {
			function activateTab(target, shouldScroll = true) {
				if (!$(target).length) return;
				$(".ps-tab").removeClass("active");
				$(".ps-tab-list li").removeClass("active");
				$(target).addClass("active");
				$(".ps-tab-list li a[href=\"" + target + "\"]").closest("li").addClass("active");
				setTimeout(function() {
					initOwlCarousel(target);
				}, 100);
				if (shouldScroll && $(".header--product .navigation").length) $("html, body").animate({ scrollTop: $(target).offset().top - $(".header--product .navigation").height() - 165 + "px" }, 800);
			}
			function checkHashOnLoad() {
				let hash = window.location.hash;
				if (hash && $(hash).length && $(hash).hasClass("ps-tab")) setTimeout(function() {
					activateTab(hash, false);
				}, 100);
			}
			$(document).on("click", ".ps-tab-list  li > a ", function(e) {
				e.preventDefault();
				let target = $(this).attr("href");
				if (history.pushState) history.pushState(null, null, target);
				else window.location.hash = target;
				activateTab(target, true);
			});
			$(document).on("click", ".ps-tab-list.owl-slider .owl-item a", function(e) {
				e.preventDefault();
				let target = $(this).attr("href");
				if (history.pushState) history.pushState(null, null, target);
				else window.location.hash = target;
				$(this).closest(".owl-item").siblings(".owl-item").removeClass("active");
				$(this).closest(".owl-item").addClass("active");
				activateTab(target, false);
			});
			$(window).on("hashchange", function() {
				let hash = window.location.hash;
				if (hash && $(hash).length && $(hash).hasClass("ps-tab")) activateTab(hash, false);
			});
			checkHashOnLoad();
		}
		function rating() {
			$("select.ps-rating").each(function() {
				let readOnly;
				if ($(this).attr("data-read-only") == "true") readOnly = true;
				else readOnly = false;
				$(this).barrating({
					theme: "fontawesome-stars",
					readonly: readOnly,
					emptyValue: "0"
				});
			});
		}
		function productLightbox() {
			if (!$.fn.lightGallery) return;
			let product = $(".ps-product--detail");
			if (product.length > 0) {
				$(".ps-product__gallery").lightGallery({
					selector: ".item a",
					thumbnail: true,
					share: false,
					fullScreen: false,
					autoplay: false,
					autoplayControls: false,
					actualSize: false,
					closable: true,
					hideControlOnEnd: false
				});
				if (product.hasClass("ps-product--sticky")) $(".ps-product__thumbnail").lightGallery({
					selector: ".item a",
					thumbnail: true,
					share: false,
					fullScreen: false,
					autoplay: false,
					autoplayControls: false,
					actualSize: false,
					closable: true,
					hideControlOnEnd: false
				});
			}
			if ($(".ps-gallery--image").length) $(".ps-gallery--image").lightGallery({
				selector: ".ps-gallery__item",
				thumbnail: true,
				share: false,
				fullScreen: false,
				autoplay: false,
				autoplayControls: false,
				actualSize: false,
				closable: true,
				hideControlOnEnd: false
			});
			if ($(".ps-video").length) $(".ps-video").lightGallery({
				thumbnail: false,
				share: false,
				fullScreen: false,
				autoplay: false,
				autoplayControls: false,
				actualSize: false,
				closable: true
			});
		}
		function backToTop() {
			let element = $("#back2top");
			if (!element.length) return;
			let scrollPos = 0;
			$(window).scroll(function() {
				let scrollCur = $(window).scrollTop();
				if (scrollCur > scrollPos) {
					if (scrollCur > 500) element.addClass("active");
					else element.removeClass("active");
				} else element.removeClass("active");
				scrollPos = scrollCur;
			});
			element.on("click", function() {
				$("html, body").animate({ scrollTop: "0px" }, 800);
			});
		}
		function modalInit() {
			let modal = $(".ps-modal");
			if (modal.length) {
				if (modal.hasClass("active")) $("body").css("overflow-y", "hidden");
			}
			modal.find(".ps-modal__close, .ps-btn--close").on("click", function(e) {
				e.preventDefault();
				$(this).closest(".ps-modal").removeClass("active");
			});
			$(".ps-modal-link").on("click", function(e) {
				e.preventDefault();
				$($(this).attr("href")).addClass("active");
				$("body").css("overflow-y", "hidden");
			});
			$(".ps-modal").on("click", function(event) {
				if (!$(event.target).closest(".ps-modal__container").length) {
					modal.removeClass("active");
					$("body").css("overflow-y", "auto");
				}
			});
		}
		function searchInit() {
			let searchbox = $(".ps-search");
			$(".ps-search-btn").on("click", function(e) {
				e.preventDefault();
				searchbox.addClass("active");
			});
			searchbox.find(".ps-btn--close").on("click", function(e) {
				e.preventDefault();
				searchbox.removeClass("active");
			});
		}
		function countDown() {
			$(".ps-countdown").each(function() {
				let el = $(this), value = $(this).data("time");
				let countDownDate = new Date(value).getTime();
				let timeout = setInterval(function() {
					let now = (/* @__PURE__ */ new Date()).getTime(), distance = countDownDate - now;
					let days = Math.floor(distance / 864e5), hours = Math.floor(distance % 864e5 / 36e5), minutes = Math.floor(distance % 36e5 / 6e4), seconds = Math.floor(distance % 6e4 / 1e3);
					el.find(".days").html(days < 10 ? "0" + days : days);
					el.find(".hours").html(hours < 10 ? "0" + hours : hours);
					el.find(".minutes").html(minutes < 10 ? "0" + minutes : minutes);
					el.find(".seconds").html(seconds < 10 ? "0" + seconds : seconds);
					if (distance < 0) {
						clearInterval(timeout);
						el.closest(".ps-section").hide();
					}
				}, 1e3);
			});
		}
		function productFilterToggle() {
			$(".ps-filter__trigger").on("click", function(e) {
				e.preventDefault();
				let el = $(this);
				el.find(".ps-filter__icon").toggleClass("active");
				el.closest(".ps-filter").find(".ps-filter__content").slideToggle();
			});
			if ($(".ps-sidebar--home").length > 0) $(".ps-sidebar--home > .ps-sidebar__header > a").on("click", function(e) {
				e.preventDefault();
				$(this).closest(".ps-sidebar--home").children(".ps-sidebar__content").slideToggle();
			});
		}
		function mainSlider() {
			$(".ps-carousel--animate").slick({
				autoplay: true,
				rtl: isRTL,
				speed: 1e3,
				lazyLoad: "progressive",
				arrows: false,
				fade: true,
				dots: true,
				prevArrow: "<i class='slider-prev ba-back'></i>",
				nextArrow: "<i class='slider-next ba-next'></i>"
			});
		}
		function stickySidebar() {
			let sticky = $(".ps-product--sticky"), stickySidebar, checkPoint = 992, windowWidth = $(window).innerWidth();
			if (sticky.length > 0) {
				stickySidebar = new StickySidebar(".ps-product__sticky .ps-product__info", {
					topSpacing: 20,
					bottomSpacing: 20,
					containerSelector: ".ps-product__sticky"
				});
				if ($(".sticky-2").length > 0) new StickySidebar(".ps-product__sticky .sticky-2", {
					topSpacing: 20,
					bottomSpacing: 20,
					containerSelector: ".ps-product__sticky"
				});
				if (checkPoint > windowWidth) {
					stickySidebar.destroy();
					stickySidebar2.destroy();
				}
			} else return false;
		}
		function accordion() {
			let accordion = $(".ps-accordion");
			accordion.find(".ps-accordion__content").hide();
			$(".ps-accordion.active").find(".ps-accordion__content").show();
			accordion.find(".ps-accordion__header").on("click", function(e) {
				e.preventDefault();
				if ($(this).closest(".ps-accordion").hasClass("active")) {
					$(this).closest(".ps-accordion").removeClass("active");
					$(this).closest(".ps-accordion").find(".ps-accordion__content").slideUp(350);
				} else {
					$(this).closest(".ps-accordion").addClass("active");
					$(this).closest(".ps-accordion").find(".ps-accordion__content").slideDown(350);
					$(this).closest(".ps-accordion").siblings(".ps-accordion").find(".ps-accordion__content").slideUp();
				}
				$(this).closest(".ps-accordion").siblings(".ps-accordion").removeClass("active");
				$(this).closest(".ps-accordion").siblings(".ps-accordion").find(".ps-accordion__content").slideUp();
			});
		}
		function progressBar() {
			$(".ps-progress").each(function() {
				let value = $(this).data("value");
				$(this).find("span").css({ width: value + "%" });
			});
		}
		function select2Config() {
			$("select.ps-select").select2({
				placeholder: $(this).data("placeholder"),
				minimumResultsForSearch: -1,
				templateSelection: function(state) {
					return jQuery.trim(state.text);
				}
			});
		}
		function carouselNavigation() {
			let prevBtn = $(".ps-carousel__prev"), nextBtn = $(".ps-carousel__next");
			prevBtn.on("click", function(e) {
				e.preventDefault();
				$($(this).attr("href")).trigger("prev.owl.carousel", [1e3]);
			});
			nextBtn.on("click", function(e) {
				e.preventDefault();
				$($(this).attr("href")).trigger("next.owl.carousel", [1e3]);
			});
		}
		function filterSlider() {
			let nonLinearSlider = document.getElementById("nonlinear");
			if (typeof nonLinearSlider != "undefined" && nonLinearSlider != null) {
				noUiSlider.create(nonLinearSlider, {
					connect: true,
					behaviour: "tap",
					start: [0, 1e3],
					range: {
						min: 0,
						"10%": 100,
						"20%": 200,
						"30%": 300,
						"40%": 400,
						"50%": 500,
						"60%": 600,
						"70%": 700,
						"80%": 800,
						"90%": 900,
						max: 1e3
					}
				});
				let nodes = [document.querySelector(".ps-slider__min"), document.querySelector(".ps-slider__max")];
				nonLinearSlider.noUiSlider.on("update", function(values, handle) {
					nodes[handle].innerHTML = Math.round(values[handle]);
				});
			}
		}
		function handleLiveSearch() {
			$("body").on("click", function(e) {
				if ($(e.target).closest(".ps-form--search-header") || e.target.className === ".ps-form--search-header") $(".ps-panel--search-result").removeClass("active");
			});
		}
		const reviewList = function() {
			let $reviewListWrapper = $("body").find(".comment-list");
			const $loadingSpinner = $("body").find(".loading-spinner");
			$loadingSpinner.addClass("d-none");
			const fetchData = (url, hasAnimation = false) => {
				$.ajax({
					url,
					type: "GET",
					beforeSend: function() {
						$loadingSpinner.removeClass("d-none");
						if (hasAnimation) $("html, body").animate({ scrollTop: `${$(".product-reviews-container").offset().top}px` }, 1500);
					},
					success: function(res) {
						$reviewListWrapper.html(res.data);
						$(".product-reviews-container .product-reviews-header").html(res.message);
						let $galleries = $(".product-reviews-container .block__images");
						if ($galleries.length) $galleries.map((index, value) => {
							if (!$(value).data("lightGallery")) $(value).lightGallery({
								selector: "a",
								thumbnail: true,
								share: false,
								fullScreen: false,
								autoplay: false,
								autoplayControls: false,
								actualSize: false
							});
						});
					},
					complete: function() {
						$loadingSpinner.addClass("d-none");
					}
				});
			};
			if ($reviewListWrapper.length < 1) return;
			fetchData($reviewListWrapper.data("url"));
			$reviewListWrapper.on("click", ".ps-pagination ul li.page-item a", function(e) {
				e.preventDefault();
				const href = $(this).attr("href");
				if (href === "#") return;
				fetchData(href, true);
			});
		};
		function sliderMainConfig() {
			let target = $(".owl-main-slider");
			if (target.length > 0) target.each(function() {
				let el = $(this), dataAuto = el.data("owl-auto"), dataLoop = el.data("owl-loop"), dataSpeed = el.data("owl-speed"), dataGap = el.data("owl-gap"), dataNav = el.data("owl-nav"), dataDots = el.data("owl-dots"), dataAnimateIn = el.data("owl-animate-in") ? el.data("owl-animate-in") : "", dataAnimateOut = el.data("owl-animate-out") ? el.data("owl-animate-out") : "", dataDefaultItem = el.data("owl-item"), dataItemXS = el.data("owl-item-xs"), dataItemSM = el.data("owl-item-sm"), dataItemMD = el.data("owl-item-md"), dataItemLG = el.data("owl-item-lg"), dataItemXL = el.data("owl-item-xl"), dataNavLeft = el.data("owl-nav-left") ? el.data("owl-nav-left") : "<i class='icon-chevron-left'></i>", dataNavRight = el.data("owl-nav-right") ? el.data("owl-nav-right") : "<i class='icon-chevron-right'></i>", duration = el.data("owl-duration"), datamouseDrag = el.data("owl-mousedrag") == "on" ? true : false;
				let itemCount = target.children("div, span, a, img, h1, h2, h3, h4, h5, h5").length;
				if (itemCount >= 2) el.addClass("owl-carousel").owlCarousel({
					rtl: isRTL,
					animateIn: dataAnimateIn,
					animateOut: dataAnimateOut,
					margin: dataGap,
					autoplay: dataAuto,
					autoplayTimeout: dataSpeed,
					autoplayHoverPause: true,
					loop: dataLoop,
					nav: dataNav,
					mouseDrag: datamouseDrag,
					touchDrag: true,
					autoplaySpeed: duration,
					navSpeed: duration,
					dotsSpeed: duration,
					dragEndSpeed: duration,
					navText: [dataNavLeft, dataNavRight],
					dots: dataDots,
					items: dataDefaultItem,
					responsive: {
						0: { items: dataItemXS },
						480: { items: dataItemSM },
						768: { items: dataItemMD },
						992: { items: dataItemLG },
						1200: { items: dataItemXL },
						1680: { items: dataDefaultItem }
					}
				});
				else if (itemCount === 1) el.addClass("owl-carousel single-item-carousel").owlCarousel({
					rtl: isRTL,
					items: 1,
					dots: false,
					nav: false,
					loop: false,
					mouseDrag: false,
					touchDrag: false,
					responsive: {
						0: { items: 1 },
						480: { items: 1 },
						768: { items: 1 },
						992: { items: 1 },
						1200: { items: 1 },
						1680: { items: 1 }
					}
				});
			});
		}
		$(function() {
			backgroundImage();
			owlCarouselConfig();
			sliderMainConfig();
			siteToggleAction();
			subMenuToggle();
			productFilterToggle();
			tabs();
			slickConfig();
			productLightbox();
			rating();
			backToTop();
			stickyHeader();
			mapConfig();
			modalInit();
			searchInit();
			countDown();
			mainSlider();
			stickySidebar();
			accordion();
			progressBar();
			select2Config();
			carouselNavigation();
			filterSlider();
			handleLiveSearch();
			reviewList();
		});
		[...document.querySelectorAll("[data-bs-toggle=\"tooltip\"]")].map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));
		$("#product-quickview").on("shown.bs.modal", function() {
			$(".ps-product--quickview .ps-product__images").slick("setPosition");
		});
		document.addEventListener("ecommerce.quick-shop.before-send", function(e) {
			const { element, modal } = e.detail;
			element.addClass("loading");
			modal.find(".modal-body").html("<div class=\"ps-loading\"><div class=\"ps-loading__spinner\"></div></div>");
			modal.addClass("quick-shop-no-url-update");
		});
		document.addEventListener("ecommerce.quick-shop.completed", function(e) {
			const { element, modal } = e.detail;
			element.removeClass("loading");
			$(modal).find(".product__qty .up").on("click", function(event) {
				event.preventDefault();
				event.stopPropagation();
				let currentVal = parseInt($(this).closest(".product__qty").find(".qty-input").val(), 10) || 0;
				$(this).closest(".product__qty").find(".qty-input").val(currentVal + 1).prop("placeholder", currentVal + 1).trigger("change");
			});
			$(modal).find(".product__qty .down").on("click", function(event) {
				event.preventDefault();
				event.stopPropagation();
				let currentVal = parseInt($(this).closest(".product__qty").find(".qty-input").val(), 10) || 1;
				if (currentVal > 1) $(this).closest(".product__qty").find(".qty-input").val(currentVal - 1).prop("placeholder", currentVal - 1).trigger("change");
			});
			$(modal).find(".modal-close").on("click", function(e) {
				e.preventDefault();
				const bsModal = bootstrap.Modal.getInstance(modal[0]);
				if (bsModal) bsModal.hide();
			});
			$(modal).find("[data-bb-toggle=\"product-form\"]").attr("data-update-url", "false");
			const originalPushState = window.history.pushState;
			const originalReplaceState = window.history.replaceState;
			$(modal).find(".product-attributes").attr("data-no-url-update", "true");
			window.history.pushState = function() {
				if (!($(".product-attributes[data-no-url-update=\"true\"]").length > 0)) return originalPushState.apply(window.history, arguments);
			};
			window.history.replaceState = function() {
				if (!($(".product-attributes[data-no-url-update=\"true\"]").length > 0)) return originalReplaceState.apply(window.history, arguments);
			};
			$(modal).on("hidden.bs.modal", function() {
				window.history.pushState = originalPushState;
				window.history.replaceState = originalReplaceState;
				$(modal).find(".product-attributes").removeAttr("data-no-url-update");
			});
		});
		$(window).on("load", function() {
			$("body").addClass("loaded");
		});
		let collapseBreadcrumb = function() {
			$("ul.breadcrumb li").each(function() {
				let $this = $(this);
				if (!$this.is(":first-child") && !$this.is(":nth-child(2)") && !$this.is(":last-child")) {
					if (!$this.is(":nth-child(3)")) $this.find("a").closest("li").hide();
					else {
						$this.find("a").hide();
						$this.find(".extra-breadcrumb-name").text("...").show();
					}
				}
			});
		};
		if ($(window).width() < 768) collapseBreadcrumb();
		$(window).on("resize", function() {
			collapseBreadcrumb();
		});
		$(document).ready(function() {
			initMegaMenu();
		});
		document.addEventListener("ecommerce.categories-dropdown.success", () => {
			initMegaMenu();
		});
		(function() {
			var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
			return function(obj, callback) {
				if (!obj || obj.nodeType !== 1) return;
				if (MutationObserver) {
					var mutationObserver = new MutationObserver(callback);
					mutationObserver.observe(obj, {
						childList: true,
						subtree: true
					});
					return mutationObserver;
				} else if (window.addEventListener) {
					obj.addEventListener("DOMNodeInserted", callback, false);
					obj.addEventListener("DOMNodeRemoved", callback, false);
				}
			};
		})()(document.body, function(mutations) {
			var hasOwlSlider = false;
			mutations.forEach(function(mutation) {
				if (mutation.addedNodes && mutation.addedNodes.length > 0) mutation.addedNodes.forEach(function(node) {
					if (node.nodeType === 1) {
						if ($(node).hasClass("owl-slider") || $(node).find(".owl-slider").length > 0) hasOwlSlider = true;
					}
				});
			});
			if (hasOwlSlider) setTimeout(function() {
				initOwlCarousel();
			}, 100);
		});
		document.addEventListener("shortcode.loaded", function(event) {
			setTimeout(function() {
				initOwlCarousel();
				backgroundImage();
				tabs();
				rating();
				select2Config();
				countDown();
				productFilterToggle();
				[...document.querySelectorAll("[data-bs-toggle=\"tooltip\"]")].map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));
			}, 100);
		});
		$(document).on("ajaxContentLoaded", function(event, container) {
			if (container) initOwlCarousel(container);
		});
		$(document).on("shown.bs.tab", "a[data-bs-toggle=\"tab\"]", function() {
			const tabPane = $($(this).attr("href"));
			if (tabPane.length) setTimeout(function() {
				initOwlCarousel(tabPane);
			}, 100);
		});
		$(document).on("click", ".product-collections-tab .nav-tabs .nav-link:not([data-loaded])", function(e) {
			e.preventDefault();
			const $this = $(e.currentTarget);
			const tabPanel = $this.closest(".product-collections-tab").find("#" + $this.data("ref"));
			const $template = $this.closest(".product-collections-tab").find(".product-collection-items").html();
			$.ajax({
				url: $this.data("url"),
				dataType: "json",
				success: (res) => {
					if (res.error == false) {
						var _res$data;
						tabPanel.html($template.replace("__data__", (_res$data = res.data) === null || _res$data === void 0 ? void 0 : _res$data.reduce((html, item) => html + "<div class=\"item\">" + item + "</div>", "")));
						setTimeout(function() {
							initOwlCarousel(tabPanel);
						}, 100);
						$this.attr("data-loaded", 1);
					}
				}
			});
		});
		MartApp.loadCart = function() {
			const $cartContainers = $(".ps-cart--mobile");
			if ($cartContainers.length === 0) return;
			const cartUrl = window.siteConfig && window.siteConfig.ajaxCartUrl ? window.siteConfig.ajaxCartUrl : "/ajax/cart";
			$.ajax({
				url: cartUrl,
				type: "GET",
				success: function(response) {
					const data = response.data || response;
					const html = data.html || data;
					const count = data.count;
					$cartContainers.html(html);
					if (count !== void 0) {
						$(".header__extra.btn-shopping-cart span i").text(count);
						$("[data-bb-value=\"cart-count\"]").text(count);
					}
				},
				error: function(error) {
					console.error("Error loading cart:", error);
				}
			});
		};
		window.loadCart = MartApp.loadCart;
		window.loadAjaxCart = function(cartData) {
			if (cartData && cartData.count !== void 0) {
				$(".header__extra.btn-shopping-cart span i").text(cartData.count);
				$("[data-bb-value=\"cart-count\"]").text(cartData.count);
			}
			MartApp.loadCart();
		};
		MartApp.addProductToCart = function() {
			$(document).on("click", "form.add-to-cart-form button[type=submit]", function(e) {
				e.preventDefault();
				e.stopPropagation();
				const $form = $(this).closest("form.add-to-cart-form");
				const $btn = $(this);
				if ($form.data("bb-toggle") === "product-form") return;
				if (!$(".hidden-product-id").val()) {
					$btn.prop("disabled", true).addClass("btn-disabled");
					return;
				}
				$btn.addClass("loading btn-disabled button-loading").prop("disabled", true);
				$form.find(".error-message").hide();
				$form.find(".success-message").hide();
				let data = $form.serializeArray();
				data.push({
					name: "checkout",
					value: $btn.prop("name") === "checkout" ? 1 : 0
				});
				$.ajax({
					type: "POST",
					url: $form.prop("action"),
					data: $.param(data),
					success: function(res) {
						if (res.error) {
							if (window.showError) window.showError(res.message);
							if (res.data && res.data.next_url !== void 0) setTimeout(function() {
								window.location.href = res.data.next_url;
							}, 500);
							return false;
						}
						if (res.data && res.data.next_url !== void 0) {
							window.location.href = res.data.next_url;
							return false;
						}
						var showToast = $form.data("show-toast-on-success");
						if (showToast !== false && showToast !== "false") {
							if (window.showSuccess) window.showSuccess(res.message);
						}
						if (res.data && res.data.count !== void 0) {
							$("[data-bb-value=\"cart-count\"]").text(res.data.count);
							$(".header__extra.btn-shopping-cart span i").text(res.data.count);
						}
						MartApp.loadCart();
						document.dispatchEvent(new CustomEvent("ecommerce.cart.added", { detail: {
							data: res.data,
							element: $btn[0],
							message: res.message
						} }));
					},
					error: function(error) {
						console.error("Error adding to cart:", error);
					},
					complete: function() {
						$btn.removeClass("loading btn-disabled button-loading").prop("disabled", false);
					}
				});
			});
		};
		MartApp.addProductToCart();
		MartApp.upsellRefreshUrl = null;
		MartApp.initBlockLazyLoading = function() {
			$(document).find("[data-bb-toggle=\"block-lazy-loading\"]").each(function() {
				const $element = $(this);
				const url = $element.data("url");
				if (!url) return;
				if (url.includes("up-sale-products")) MartApp.upsellRefreshUrl = url;
				$.ajax({
					url,
					type: "GET",
					success: function(response) {
						const data = response.data || response;
						$element.replaceWith(data);
						if (typeof window.LazyLoad !== "undefined" && window.lazyLoadInstance) window.lazyLoadInstance.update();
						MartApp.initSlickCarousel();
						MartApp.initUpSaleBundle();
					},
					error: function(error) {
						console.error("Error loading lazy content:", error);
					}
				});
			});
			document.addEventListener("ecommerce.cart.added", MartApp.refreshUpSaleSection);
			document.addEventListener("ecommerce.cart.removed", MartApp.refreshUpSaleSection);
			$(document).on("click", ".ec-cross-sale-add-btn[data-bb-toggle=\"add-to-cart\"]", function(e) {
				e.preventDefault();
				e.stopImmediatePropagation();
				const $btn = $(this);
				const url = $btn.data("url");
				const productId = $btn.data("id");
				if ($btn.hasClass("loading")) return;
				$btn.addClass("loading").prop("disabled", true);
				$.ajax({
					url,
					type: "POST",
					data: { id: productId },
					success: function(response) {
						if (response.error) {
							if (window.showError) window.showError(response.message || "Failed to add product to cart");
						} else {
							if (response.message && window.showSuccess) window.showSuccess(response.message);
							if (response.data && response.data.count !== void 0) {
								$(".header__extra.btn-shopping-cart span i").text(response.data.count);
								$("[data-bb-value=\"cart-count\"]").text(response.data.count);
							}
							MartApp.loadCart();
							document.dispatchEvent(new CustomEvent("ecommerce.cart.added", { detail: {
								data: response.data,
								element: $btn[0]
							} }));
						}
					},
					error: function(error) {
						console.error("Error adding to cart:", error);
					},
					complete: function() {
						$btn.removeClass("loading").prop("disabled", false);
					}
				});
			});
		};
		MartApp.initSlickCarousel = function() {
			const $carousel = $(".ec-cross-sale-carousel:not(.slick-initialized)");
			if ($carousel.length && typeof $.fn.slick !== "undefined") $carousel.slick({
				rtl: isRTL,
				slidesToShow: 5,
				slidesToScroll: 1,
				arrows: true,
				dots: false,
				infinite: false,
				appendArrows: $carousel.closest(".ec-cross-sale-slider").find(".ec-cross-sale-arrows"),
				prevArrow: "<button class=\"slick-prev\" type=\"button\"><i class=\"icon-chevron-left\"></i></button>",
				nextArrow: "<button class=\"slick-next\" type=\"button\"><i class=\"icon-chevron-right\"></i></button>",
				responsive: [
					{
						breakpoint: 1200,
						settings: { slidesToShow: 4 }
					},
					{
						breakpoint: 992,
						settings: { slidesToShow: 3 }
					},
					{
						breakpoint: 768,
						settings: { slidesToShow: 2 }
					},
					{
						breakpoint: 480,
						settings: { slidesToShow: 1 }
					}
				]
			});
		};
		MartApp.refreshUpSaleSection = function() {
			if (!MartApp.upsellRefreshUrl) return;
			const $section = $("[data-upsale-bundle]");
			if ($section.length === 0) return;
			$section.css("opacity", "0.5");
			$.ajax({
				url: MartApp.upsellRefreshUrl,
				type: "GET",
				success: function(response) {
					const data = response.data || response;
					$section.replaceWith(data);
					if (typeof window.LazyLoad !== "undefined" && window.lazyLoadInstance) window.lazyLoadInstance.update();
					MartApp.initUpSaleBundle();
				},
				error: function() {
					$section.css("opacity", "1");
				}
			});
		};
		MartApp.initUpSaleBundle = function() {
			const $section = $("[data-upsale-bundle]");
			if ($section.length === 0) return;
			const $checkboxes = $section.find("[data-upsale-checkbox]");
			const $totalPrice = $section.find("[data-upsale-total-price]");
			const $addAllBtn = $section.find("[data-upsale-add-all]");
			const formatPrice = function(price) {
				const currencies = $section.data("currency-config") || window.currencies || {};
				const decimals = currencies.decimals !== void 0 ? currencies.decimals : 0;
				const thousandsSep = currencies.thousands_separator || ",";
				const decimalSep = currencies.decimal_separator || ".";
				const symbol = currencies.symbol || "$";
				const isPrefix = currencies.is_prefix !== void 0 ? currencies.is_prefix : true;
				const regex = "\\d(?=(\\d{3})+$)";
				let priceArr = price.toFixed(Math.max(0, ~~decimals)).toString().split(".");
				let formattedPrice = priceArr[0].replace(new RegExp(regex, "g"), "$&" + thousandsSep) + (priceArr[1] ? decimalSep + priceArr[1] : "");
				return isPrefix ? symbol + formattedPrice : formattedPrice + symbol;
			};
			const updateTotal = function() {
				let total = 0;
				let selectedCount = 0;
				$checkboxes.filter(":checked").each(function() {
					const price = parseFloat($(this).attr("data-price")) || 0;
					total += price;
					selectedCount++;
				});
				$totalPrice.text(formatPrice(total));
				$addAllBtn.prop("disabled", selectedCount === 0);
			};
			$checkboxes.off("change.upsale").on("change.upsale", updateTotal);
			updateTotal();
			$addAllBtn.off("click.upsale").on("click.upsale", function(e) {
				e.preventDefault();
				const $btn = $(this);
				const selectedProducts = [];
				const parentProduct = $btn.data("parent-product");
				$checkboxes.filter(":checked").each(function() {
					const productId = $(this).attr("data-id");
					if (productId) selectedProducts.push(productId);
				});
				if (selectedProducts.length === 0) return;
				$btn.addClass("loading").prop("disabled", true);
				let index = 0;
				let successCount = 0;
				const addNextProduct = function() {
					if (index >= selectedProducts.length) {
						$btn.removeClass("loading");
						$checkboxes.prop("checked", false);
						updateTotal();
						MartApp.refreshUpSaleSection();
						MartApp.loadCart();
						if (successCount > 0 && window.showSuccess) window.showSuccess("Added " + successCount + " item(s) to cart");
						return;
					}
					const productId = selectedProducts[index];
					$.ajax({
						url: $btn.data("url"),
						type: "POST",
						data: {
							id: productId,
							reference_product_for_upsale: parentProduct
						},
						success: function() {
							successCount++;
							index++;
							addNextProduct();
						},
						error: function() {
							index++;
							addNextProduct();
						}
					});
				};
				addNextProduct();
			});
			$section.find("[data-upsale-add-btn]").off("click.upsale").on("click.upsale", function(e) {
				e.preventDefault();
				e.stopPropagation();
				const $btn = $(this);
				const $checkbox = $btn.closest("[data-upsale-bundle-item]").find("[data-upsale-checkbox]");
				const parentProduct = $addAllBtn.data("parent-product") || $btn.data("parent-product");
				const productId = $btn.attr("data-id");
				const addUrl = $btn.data("url");
				$btn.addClass("loading").prop("disabled", true);
				$.ajax({
					url: addUrl,
					type: "POST",
					data: {
						id: productId,
						reference_product_for_upsale: parentProduct
					},
					success: function(response) {
						if (response.error) {
							if (window.showError) window.showError(response.message || "Failed to add product to cart");
							$btn.removeClass("loading").prop("disabled", false);
							return;
						}
						if (response.message && window.showSuccess) window.showSuccess(response.message);
						$checkbox.prop("checked", true);
						updateTotal();
						if (response.data && response.data.count !== void 0) {
							$(".header__extra.btn-shopping-cart span i").text(response.data.count);
							$("[data-bb-value=\"cart-count\"]").text(response.data.count);
						}
						MartApp.loadCart();
						document.dispatchEvent(new CustomEvent("ecommerce.cart.added", { detail: {
							data: response.data,
							element: $btn[0]
						} }));
						MartApp.refreshUpSaleSection();
					},
					error: function(error) {
						console.error("Error adding to cart:", error);
					},
					complete: function() {
						$btn.removeClass("loading").prop("disabled", false);
					}
				});
			});
			$section.find(".ec-upsell-attributes .product-filter-item").off("change.upsale").on("change.upsale", function() {
				if ($(this).prop("disabled")) return;
				const $attrs = $(this).closest(".ec-upsell-attributes");
				const $item = $attrs.closest("[data-upsale-bundle-item]");
				const url = $attrs.data("target");
				if (!url) return;
				const data = { attributes: {} };
				$attrs.find(".product-filter-item:checked").each(function() {
					const slug = $(this).closest(".attribute-swatches-wrapper, .ec-upsell-attribute-group").data("slug");
					if (slug) data.attributes[slug] = $(this).val();
				});
				$.ajax({
					url,
					type: "GET",
					data,
					success: function(res) {
						if (res.data) {
							const variationId = res.data.id;
							let price = res.data.sale_price || res.data.price;
							const errorMessage = res.data.error_message;
							const unavailableAttrIds = res.data.unavailable_attribute_ids || [];
							$attrs.find(".ec-upsell-attribute-option").each(function() {
								const $option = $(this);
								const attrId = parseInt($option.data("id"));
								const $input = $option.find("input[type=\"radio\"]");
								if (unavailableAttrIds.includes(attrId)) {
									$option.addClass("disabled").attr("title", "Not available");
									$input.prop("disabled", true);
								} else {
									$option.removeClass("disabled").removeAttr("title");
									$input.prop("disabled", false);
								}
							});
							if (variationId && !errorMessage) {
								$item.find(".ec-upsell-variation-id").val(variationId);
								$item.find("[data-upsale-checkbox]").attr("data-id", variationId);
								$item.find("[data-upsale-add-btn]").attr("data-id", variationId).prop("disabled", false);
								if (price) {
									const $checkbox = $item.find("[data-upsale-checkbox]");
									const bundleDiscount = parseFloat($checkbox.attr("data-bundle-discount")) || 0;
									const bundleDiscountType = $checkbox.attr("data-bundle-discount-type");
									if (bundleDiscount > 0) {
										if (bundleDiscountType === "percent") price = price - price * bundleDiscount / 100;
										else price = Math.max(0, price - bundleDiscount);
									}
									$checkbox.attr("data-price", price);
									updateTotal();
								}
							} else if (errorMessage) $item.find("[data-upsale-add-btn]").prop("disabled", true);
						}
					}
				});
			});
		};
		$(document).ready(function() {
			MartApp.initBlockLazyLoading();
		});
	})(jQuery);
	//#endregion
})();

//# sourceMappingURL=main.js.map