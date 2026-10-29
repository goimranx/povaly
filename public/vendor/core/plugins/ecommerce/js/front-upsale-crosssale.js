"use strict";
(function() {
	//#region platform/plugins/ecommerce/resources/js/front-upsale-crosssale.js
	(function($) {
		if (typeof window.EcommerceUpSaleCrossSale !== "undefined") return;
		/**
		* Dispatch a custom event with optional cancelable support
		* @param {string} eventName - Name of the event
		* @param {object} detail - Event detail object
		* @param {boolean} cancelable - Whether the event can be canceled
		* @returns {boolean} - Returns false if event was canceled
		*/
		const dispatchEvent = function(eventName, detail = {}, cancelable = false) {
			const event = new CustomEvent(eventName, {
				detail,
				cancelable
			});
			document.dispatchEvent(event);
			return !event.defaultPrevented;
		};
		/**
		* Replace an element with ajax HTML without triggering jQuery's synchronous
		* XMLHttpRequest for embedded `<script src>` tags (a deprecated browser API).
		* jQuery's replaceWith() loads external scripts found in the markup via a
		* blocking sync XHR; here we strip those scripts and (re)load them
		* asynchronously, only once (matched by path, ignoring the ?v= query). Inline
		* scripts are left in place for jQuery to evaluate as before.
		* @param {jQuery} $element - Element to replace
		* @param {string} html - HTML returned from the ajax response
		*/
		const replaceWithSafe = function($element, html) {
			const template = document.createElement("template");
			template.innerHTML = html;
			const sources = [];
			template.content.querySelectorAll("script[src]").forEach((script) => {
				sources.push(script.getAttribute("src"));
				script.remove();
			});
			$element.replaceWith(template.innerHTML);
			const pathOf = (src) => {
				try {
					return new URL(src, window.location.href).pathname;
				} catch (e) {
					return src;
				}
			};
			sources.forEach((src) => {
				if (!src) return;
				if (Array.prototype.some.call(document.querySelectorAll("script[src]"), (existing) => pathOf(existing.getAttribute("src")) === pathOf(src))) return;
				const script = document.createElement("script");
				script.src = src;
				script.async = true;
				document.body.appendChild(script);
			});
		};
		/**
		* Show success message using available theme methods
		* @param {string} message - Message to show
		*/
		const showSuccess = function(message) {
			if (!message) return;
			if (window.Theme && typeof window.Theme.showSuccess === "function") window.Theme.showSuccess(message);
			else if (window.MartApp && typeof window.MartApp.showSuccess === "function") window.MartApp.showSuccess(message);
			else if (window.ShofyApp && typeof window.ShofyApp.showSuccess === "function") window.ShofyApp.showSuccess(message);
			else if (typeof window.showSuccess === "function") window.showSuccess(message);
		};
		/**
		* Show error message using available theme methods
		* @param {string} message - Message to show
		*/
		const showError = function(message) {
			if (!message) return;
			if (window.Theme && typeof window.Theme.showError === "function") window.Theme.showError(message);
			else if (window.MartApp && typeof window.MartApp.showError === "function") window.MartApp.showError(message);
			else if (window.ShofyApp && typeof window.ShofyApp.showError === "function") window.ShofyApp.showError(message);
			else if (typeof window.showError === "function") window.showError(message);
			else console.error(message);
		};
		const getCsrfToken = function() {
			return $("meta[name=\"csrf-token\"]").attr("content") || "";
		};
		window.EcommerceUpSaleCrossSale = {
			upsellRefreshUrl: null,
			crossSaleRefreshUrl: null,
			init: function() {
				for (const appName of [
					"MartApp",
					"ShofyApp",
					"ThemeApp",
					"App"
				]) if (window[appName] && typeof window[appName].initBlockLazyLoading === "function") return;
				this.initBlockLazyLoading();
				this.bindCartEvents();
				this.bindCrossSaleEvents();
			},
			/**
			* Initialize lazy loading for all block-lazy-loading elements.
			* Handles up-sale, cross-sale, and any other lazy-loaded content (e.g. related products).
			*/
			initBlockLazyLoading: function() {
				const self = this;
				$(document).find("[data-bb-toggle=\"block-lazy-loading\"]").each(function() {
					const $element = $(this);
					const url = $element.data("url");
					if (!url) return;
					if (url.includes("up-sale-products")) self.upsellRefreshUrl = url;
					if (url.includes("cross-sale-products")) self.crossSaleRefreshUrl = url;
					$.ajax({
						url,
						type: "GET",
						success: function(response) {
							const data = response.data !== void 0 ? response.data : response;
							replaceWithSafe($element, data);
							if (typeof window.LazyLoad !== "undefined" && window.lazyLoadInstance) window.lazyLoadInstance.update();
							dispatchEvent("ecommerce.block.loaded", {
								html: data,
								url
							});
							if (url.includes("up-sale-products")) {
								dispatchEvent("ecommerce.upsale.bundle.loaded", {
									html: data,
									url
								});
								$(document).trigger("upsell-bundle-loaded");
								self.initUpSaleBundle();
							}
							if (url.includes("cross-sale-products")) {
								dispatchEvent("ecommerce.crosssale.loaded", {
									html: data,
									url
								});
								self.initSlickCarousel();
							}
						},
						error: function(error) {
							console.error("Failed to load lazy content:", error);
						}
					});
				});
			},
			/**
			* Initialize Slick carousel for cross-sale products
			*/
			initSlickCarousel: function() {
				const $carousel = $(".ec-cross-sale-carousel.slick-slides-carousel");
				if ($carousel.length && typeof $.fn.slick !== "undefined" && !$carousel.hasClass("slick-initialized")) {
					const config = $carousel.data("slick") || {};
					$carousel.slick(config);
					dispatchEvent("ecommerce.crosssale.carousel.initialized", {
						element: $carousel[0],
						config
					});
				}
			},
			/**
			* Bind cart events to refresh up-sale section
			*/
			bindCartEvents: function() {
				const self = this;
				document.addEventListener("ecommerce.cart.added", function() {
					self.refreshUpSaleSection();
				});
				document.addEventListener("ecommerce.cart.removed", function() {
					self.refreshUpSaleSection();
				});
			},
			/**
			* Bind cross-sale add to cart events
			*/
			bindCrossSaleEvents: function() {
				$(document).on("click", ".ec-cross-sale-add-btn[data-bb-toggle=\"add-to-cart\"]", function(e) {
					e.preventDefault();
					e.stopPropagation();
					const $btn = $(this);
					const url = $btn.data("url");
					const productId = $btn.data("id");
					if ($btn.hasClass("loading")) return;
					if (!dispatchEvent("ecommerce.crosssale.add-to-cart.before", {
						element: $btn[0],
						productId,
						url
					}, true)) return;
					$btn.addClass("loading").prop("disabled", true);
					$.ajax({
						url,
						type: "POST",
						data: {
							id: productId,
							_token: getCsrfToken()
						},
						success: function(response) {
							if (response.error) {
								showError(response.message || "Failed to add product to cart");
								dispatchEvent("ecommerce.crosssale.add-to-cart.error", {
									element: $btn[0],
									productId,
									error: response.message
								});
							} else {
								showSuccess(response.message);
								dispatchEvent("ecommerce.crosssale.add-to-cart.success", {
									element: $btn[0],
									productId,
									data: response.data,
									message: response.message
								});
								document.dispatchEvent(new CustomEvent("ecommerce.cart.added", { detail: {
									data: response.data,
									element: $btn[0]
								} }));
								if (typeof window.loadAjaxCart === "function" && response.data) window.loadAjaxCart(response.data);
							}
						},
						error: function(error) {
							showError("Failed to add product to cart");
							dispatchEvent("ecommerce.crosssale.add-to-cart.error", {
								element: $btn[0],
								productId,
								error
							});
						},
						complete: function() {
							$btn.removeClass("loading").prop("disabled", false);
						}
					});
				});
			},
			/**
			* Refresh up-sale section when cart changes
			*/
			refreshUpSaleSection: function() {
				const self = this;
				if (!this.upsellRefreshUrl) return;
				const $section = $("[data-upsale-bundle]");
				if ($section.length === 0) return;
				$section.css("opacity", "0.5");
				$.ajax({
					url: this.upsellRefreshUrl,
					type: "GET",
					success: function(response) {
						const data = response.data !== void 0 ? response.data : response;
						replaceWithSafe($section, data);
						dispatchEvent("ecommerce.upsale.section.refreshed", { html: data });
						$(document).trigger("upsell-bundle-loaded");
						self.initUpSaleBundle();
					},
					error: function() {
						$section.css("opacity", "1");
					}
				});
			},
			/**
			* Initialize up-sale bundle checkboxes and functionality
			*/
			initUpSaleBundle: function() {
				const self = this;
				const $section = $("[data-upsale-bundle]");
				if ($section.length === 0) return;
				const $checkboxes = $section.find("[data-upsale-checkbox]");
				const $totalPrice = $section.find("[data-upsale-total-price]");
				const $addAllBtn = $section.find("[data-upsale-add-all]");
				/**
				* Format price according to currency settings
				*/
				const formatPrice = function(price) {
					var _currencies$decimals;
					const currencies = $section.data("currency-config") || window.currencies || {};
					const symbol = currencies.symbol || "$";
					const isPrefix = currencies.is_prefix !== false;
					const decimals = (_currencies$decimals = currencies.decimals) !== null && _currencies$decimals !== void 0 ? _currencies$decimals : 2;
					const thousandsSeparator = currencies.thousands_separator || ",";
					const decimalSeparator = currencies.decimal_separator || ".";
					const formattedNumber = parseFloat(price).toFixed(decimals).replace(".", decimalSeparator).replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);
					return isPrefix ? symbol + formattedNumber : formattedNumber + symbol;
				};
				/**
				* Update total price and add all button state
				*/
				const updateTotal = function() {
					let total = 0;
					let selectedCount = 0;
					const selectedProducts = [];
					$checkboxes.filter(":checked").each(function() {
						const $checkbox = $(this);
						total += parseFloat($checkbox.attr("data-price")) || 0;
						selectedCount++;
						selectedProducts.push({
							id: $checkbox.attr("data-id"),
							price: parseFloat($checkbox.attr("data-price")) || 0
						});
					});
					$totalPrice.text(formatPrice(total));
					$addAllBtn.prop("disabled", selectedCount === 0);
					dispatchEvent("ecommerce.upsale.total.updated", {
						total,
						formattedTotal: formatPrice(total),
						selectedCount,
						selectedProducts
					});
				};
				$checkboxes.off("change.upsale").on("change.upsale", updateTotal);
				$section.find("[data-upsale-add-btn]").off("click.upsale").on("click.upsale", function(e) {
					e.preventDefault();
					const $btn = $(this);
					const $checkbox = $btn.closest("[data-upsale-bundle-item]").find("[data-upsale-checkbox]");
					const parentProduct = $addAllBtn.data("parent-product") || $btn.data("parent-product");
					const productId = $btn.attr("data-id");
					const addUrl = $btn.data("url");
					if (!dispatchEvent("ecommerce.upsale.add-to-cart.before", {
						element: $btn[0],
						productId,
						parentProduct,
						url: addUrl
					}, true)) return;
					$btn.addClass("loading").prop("disabled", true);
					$.ajax({
						url: addUrl,
						type: "POST",
						data: {
							id: productId,
							reference_product_for_upsale: parentProduct,
							_token: getCsrfToken()
						},
						success: function(response) {
							$btn.removeClass("loading");
							if (response.error) {
								$btn.prop("disabled", false);
								showError(response.message || "Failed to add product to cart");
								dispatchEvent("ecommerce.upsale.add-to-cart.error", {
									element: $btn[0],
									productId,
									error: response.message
								});
								return;
							}
							showSuccess(response.message);
							$checkbox.prop("checked", false).prop("disabled", true);
							updateTotal();
							dispatchEvent("ecommerce.upsale.add-to-cart.success", {
								element: $btn[0],
								productId,
								parentProduct,
								data: response.data || {},
								message: response.message || ""
							});
							document.dispatchEvent(new CustomEvent("ecommerce.cart.added", { detail: {
								data: response.data || {},
								element: $btn[0],
								message: response.message || ""
							} }));
							if (typeof window.loadAjaxCart === "function" && response.data) window.loadAjaxCart(response.data);
						},
						error: function(error) {
							$btn.removeClass("loading").prop("disabled", false);
							showError("Failed to add product to cart");
							dispatchEvent("ecommerce.upsale.add-to-cart.error", {
								element: $btn[0],
								productId,
								error
							});
						}
					});
				});
				$addAllBtn.off("click.upsale").on("click.upsale", function(e) {
					e.preventDefault();
					const $btn = $(this);
					const selectedProducts = [];
					const parentProduct = $btn.data("parent-product");
					$checkboxes.filter(":checked").each(function() {
						selectedProducts.push($(this).attr("data-id"));
					});
					if (selectedProducts.length === 0) return;
					if (!dispatchEvent("ecommerce.upsale.add-all.before", {
						element: $btn[0],
						productIds: selectedProducts,
						parentProduct
					}, true)) return;
					$btn.addClass("loading").prop("disabled", true);
					let index = 0;
					let lastResponse = null;
					let successCount = 0;
					const addNextProduct = function() {
						if (index >= selectedProducts.length) {
							$btn.removeClass("loading");
							dispatchEvent("ecommerce.upsale.add-all.success", {
								element: $btn[0],
								productIds: selectedProducts,
								successCount,
								lastResponse
							});
							self.refreshUpSaleSection();
							if (lastResponse && !lastResponse.error && lastResponse.data) {
								document.dispatchEvent(new CustomEvent("ecommerce.cart.added", { detail: {
									data: lastResponse.data,
									element: $btn[0],
									message: lastResponse.message || ""
								} }));
								if (typeof window.loadAjaxCart === "function") window.loadAjaxCart(lastResponse.data);
							}
							if (successCount > 0) showSuccess(`Added ${successCount} item(s) to cart`);
							return;
						}
						$.ajax({
							url: $btn.data("url"),
							type: "POST",
							data: {
								id: selectedProducts[index],
								reference_product_for_upsale: parentProduct,
								_token: getCsrfToken()
							},
							success: function(response) {
								if (response.error) showError(response.message || "Failed to add product to cart");
								else successCount++;
								lastResponse = response;
							},
							complete: function() {
								index++;
								addNextProduct();
							}
						});
					};
					addNextProduct();
				});
				$section.find(".ec-upsell-attributes .product-filter-item").off("change.upsale").on("change.upsale", function() {
					if ($(this).prop("disabled")) return;
					const $input = $(this);
					const $attrs = $input.closest(".ec-upsell-attributes");
					const $item = $attrs.closest("[data-upsale-bundle-item]");
					const url = $attrs.data("target");
					if (!url) return;
					const data = { attributes: {} };
					$attrs.find(".product-filter-item:checked").each(function() {
						const slug = $(this).closest(".ec-upsell-attribute-group").data("slug");
						if (slug) data.attributes[slug] = $(this).val();
					});
					$.ajax({
						url,
						type: "GET",
						data,
						success: function(response) {
							if (response.data) {
								const variationId = response.data.id;
								let price = response.data.sale_price || response.data.price;
								const errorMessage = response.data.error_message;
								const unavailableAttrIds = response.data.unavailable_attribute_ids || [];
								$attrs.find(".ec-upsell-attribute-option").each(function() {
									const $option = $(this);
									const attrId = parseInt($option.data("id"));
									const $optionInput = $option.find("input[type=\"radio\"]");
									if (unavailableAttrIds.includes(attrId)) {
										$option.addClass("disabled").attr("title", $option.data("unavailable-text") || "Not available");
										$optionInput.prop("disabled", true);
									} else {
										$option.removeClass("disabled").removeAttr("title");
										$optionInput.prop("disabled", false);
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
									dispatchEvent("ecommerce.upsale.variation.changed", {
										element: $input[0],
										item: $item[0],
										variationId,
										price,
										attributes: data.attributes,
										response: response.data
									});
								} else if (errorMessage) {
									$item.find("[data-upsale-add-btn]").prop("disabled", true);
									dispatchEvent("ecommerce.upsale.variation.changed", {
										element: $input[0],
										item: $item[0],
										variationId: null,
										error: errorMessage,
										attributes: data.attributes,
										response: response.data
									});
								}
							}
						}
					});
				});
				updateTotal();
				dispatchEvent("ecommerce.upsale.bundle.initialized", {
					section: $section[0],
					checkboxCount: $checkboxes.length
				});
			}
		};
		$(function() {
			window.EcommerceUpSaleCrossSale.init();
		});
	})(jQuery);
	//#endregion
})();

//# sourceMappingURL=front-upsale-crosssale.js.map