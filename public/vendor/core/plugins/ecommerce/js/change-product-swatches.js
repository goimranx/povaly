(function() {
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/typeof.js
	function _typeof(o) {
		"@babel/helpers - typeof";
		return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(o) {
			return typeof o;
		} : function(o) {
			return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
		}, _typeof(o);
	}
	//#endregion
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/toPrimitive.js
	function toPrimitive(t, r) {
		if ("object" != _typeof(t) || !t) return t;
		var e = t[Symbol.toPrimitive];
		if (void 0 !== e) {
			var i = e.call(t, r || "default");
			if ("object" != _typeof(i)) return i;
			throw new TypeError("@@toPrimitive must return a primitive value.");
		}
		return ("string" === r ? String : Number)(t);
	}
	//#endregion
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/toPropertyKey.js
	function toPropertyKey(t) {
		var i = toPrimitive(t, "string");
		return "symbol" == _typeof(i) ? i : i + "";
	}
	//#endregion
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/defineProperty.js
	function _defineProperty(e, r, t) {
		return (r = toPropertyKey(r)) in e ? Object.defineProperty(e, r, {
			value: t,
			enumerable: !0,
			configurable: !0,
			writable: !0
		}) : e[r] = t, e;
	}
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/change-product-swatches.js
	var ChangeProductSwatches = class {
		constructor() {
			_defineProperty(this, "callAjax", function(attributes, $productAttributes, slugAttributes, updateUrl = true, referenceProduct = null) {
				let _self = this;
				let formData = {
					attributes,
					_: +/* @__PURE__ */ new Date()
				};
				if (referenceProduct) formData.reference_product = referenceProduct;
				let id = $productAttributes.prop("id");
				const locale = document.documentElement.lang || "en";
				const cacheKey = JSON.stringify(attributes.sort()) + (referenceProduct || "") + "_v1_" + locale;
				const cached = _self.getFromCache(cacheKey);
				if (cached) {
					_self.handleResponse(cached, $productAttributes, slugAttributes, id, updateUrl);
					return;
				}
				const ajaxUrl = $productAttributes.data("target");
				_self.xhr = $.ajax({
					url: ajaxUrl,
					type: "GET",
					data: formData,
					beforeSend: () => {
						if (window.EcommerceApp && typeof window.EcommerceApp.defaultOnBeforeChangeSwatches === "function") window.EcommerceApp.defaultOnBeforeChangeSwatches(attributes, $productAttributes);
						if (window.onBeforeChangeSwatches && typeof window.onBeforeChangeSwatches === "function") window.onBeforeChangeSwatches(attributes, $productAttributes);
					},
					success: (res) => {
						_self.setCache(cacheKey, res);
						_self.handleResponse(res, $productAttributes, slugAttributes, id, updateUrl);
					},
					complete: (res) => {
						if (window.onChangeSwatchesComplete && typeof window.onChangeSwatchesComplete === "function") window.onChangeSwatchesComplete(res, $productAttributes);
					},
					error: (res) => {
						if (window.onChangeSwatchesError && typeof window.onChangeSwatchesError === "function") window.onChangeSwatchesError(res, $productAttributes);
					}
				});
			});
			_defineProperty(this, "updateSelectingAttributes", function(slugAttributes, $el) {
				$.each(slugAttributes, function(key, slug) {
					let $parent = $el.find(".attribute-swatches-wrapper[data-slug=\"" + key + "\"]");
					if ($parent.length) {
						if ($parent.data("type") === "dropdown") {
							let selected = $parent.find("select option[data-slug=\"" + slug + "\"]").val();
							$parent.find("select").val(selected);
						} else {
							$parent.find("input:checked").prop("checked", false);
							$parent.find("input[data-slug=" + slug + "]").prop("checked", true);
						}
					}
				});
			});
			_defineProperty(this, "parseParamsSearch", function(query, includeArray = false) {
				let pairs = query || window.location.search.substring(1);
				let re = /([^&=]+)=?([^&]*)/g;
				let decodeRE = /\+/g;
				let decode = function(str) {
					return decodeURIComponent(str.replace(decodeRE, " "));
				};
				let params = {}, e;
				while (e = re.exec(pairs)) {
					let k = decode(e[1]), v = decode(e[2]);
					if (k.substring(k.length - 2) === "[]") {
						if (includeArray) k = k.substring(0, k.length - 2);
						(params[k] || (params[k] = [])).push(v);
					} else params[k] = v;
				}
				return params;
			});
			_defineProperty(this, "handleResponse", function(res, $productAttributes, slugAttributes, id, updateUrl) {
				let _self = this;
				if (window.EcommerceApp && typeof window.EcommerceApp.defaultOnChangeSwatchesSuccess === "function") window.EcommerceApp.defaultOnChangeSwatchesSuccess(res, $productAttributes);
				if (window.onChangeSwatchesSuccess && typeof window.onChangeSwatchesSuccess === "function") window.onChangeSwatchesSuccess(res, $productAttributes);
				const { data, message } = res;
				if (data && !data.error_message) {
					if (data.selected_attributes) {
						slugAttributes = {};
						$.each(data.selected_attributes, (index, item) => {
							slugAttributes[item.set_slug] = item.slug;
						});
					}
					if (id) _self.updateSelectingAttributes(slugAttributes, $("#" + id));
					if ($productAttributes.data("update-url") !== false) {
						const url = new URL(window.location);
						$.each(slugAttributes, (name, value) => {
							url.searchParams.set(name, value);
						});
						if (updateUrl && url != window.location.href) window.history.pushState({
							formData: { attributes: res.data.attributes },
							data: res,
							product_attributes_id: id,
							slugAttributes
						}, message, url);
						else window.history.replaceState({
							formData: { attributes: res.data.attributes },
							data: res,
							product_attributes_id: id,
							slugAttributes
						}, message, url);
						const $clipboardBtn = $("[data-bb-toggle=\"social-sharing-clipboard\"]");
						if ($clipboardBtn.length) $clipboardBtn.attr("data-clipboard-text", url.href);
					}
				}
			});
			this.xhr = null;
			this.cache = /* @__PURE__ */ new Map();
			this.cacheTimeout = 3e5;
			this.handleEvents();
		}
		handleEvents() {
			let _self = this;
			let $body = $("body");
			$body.on("mouseenter", ".product-attributes .visual-swatch label", function(e) {
				const $item = $(this).closest(".bb-product-attribute-swatch-item");
				console.log("[HOVER] Mouseenter on swatch label", {
					disabled: $item.hasClass("disabled"),
					variationImage: $item.data("variation-image"),
					item: $item[0]
				});
				if ($item.hasClass("disabled")) {
					console.log("[HOVER] Skipping disabled item");
					return;
				}
				const swatchImage = $item.data("variation-image");
				if (swatchImage) {
					console.log("[HOVER] Calling previewSwatchImage with:", swatchImage);
					$item.data("is-hover-preview", true);
					_self.previewSwatchImage(swatchImage, $item);
				} else console.log("[HOVER] No variation-image data found");
			});
			$body.on("mouseleave", ".product-attributes .visual-swatch label", function(e) {
				const $item = $(this).closest(".bb-product-attribute-swatch-item");
				console.log("[HOVER] Mouseleave from swatch label", { isChecked: $item.find("input[type=radio]").is(":checked") });
				$item.removeData("is-hover-preview");
				if (!$item.find("input[type=radio]").is(":checked")) {
					console.log("[HOVER] Calling restoreSelectedImage");
					_self.restoreSelectedImage();
				}
			});
			$body.on("click", ".product-attributes .visual-swatch label, .product-attributes .text-swatch label", (e) => {
				e.preventDefault();
				let $this = $(e.currentTarget);
				let $radio = $this.find("input[type=radio]");
				let $item = $this.closest(".bb-product-attribute-swatch-item");
				let $wrapper = $this.closest(".bb-product-attribute-swatch");
				if ($item.hasClass("disabled")) return;
				if ($radio.is(":checked")) return;
				$wrapper.find(".bb-product-attribute-swatch-item").removeClass("selected");
				$wrapper.find("input[type=radio]").prop("checked", false).removeAttr("checked");
				$item.addClass("selected");
				$radio.prop("checked", true).attr("checked", "checked");
				const $label = $wrapper.find(".qs-swatch__selected-val");
				if ($label.length) $label.text($item.data("title") || "");
				$radio.trigger("change");
			});
			$body.off("change", ".product-attributes input, .product-attributes select").on("change", ".product-attributes input, .product-attributes select", (event) => {
				const $element = $(event.currentTarget);
				const $parent = $element.closest(".product-attributes");
				if ($element.is("select")) {
					const $wrapper = $element.closest(".bb-product-attribute-swatch");
					const $selectedOption = $element.find("option:selected");
					const $label = $wrapper.find(".qs-swatch__selected-val");
					if ($label.length && $selectedOption.val()) $label.text($selectedOption.data("title") || $selectedOption.text());
				}
				_self.getProductVariation($parent);
			});
			if ($(".product-attribute-swatches").length) window.addEventListener("popstate", function(e) {
				var _e$state;
				if ((_e$state = e.state) === null || _e$state === void 0 ? void 0 : _e$state.product_attributes_id) {
					let $el = $("#" + e.state.product_attributes_id);
					if (window.EcommerceApp && typeof window.EcommerceApp.defaultOnChangeSwatchesSuccess === "function") window.EcommerceApp.defaultOnChangeSwatchesSuccess(e.state.data, $el);
					if (window.onChangeSwatchesSuccess && typeof window.onChangeSwatchesSuccess === "function") window.onChangeSwatchesSuccess(e.state.data, $el);
					if (e.state.slugAttributes) _self.updateSelectingAttributes(e.state.slugAttributes, $el);
				} else $(".product-attribute-swatches").each(function(i, el) {
					let params = _self.parseParamsSearch();
					let attributes = [];
					let slugAttributes = {};
					let $el = $(el);
					if (params && Object.keys(params).length) $.each(params, function(key, slug) {
						let $parent = $el.find(".attribute-swatches-wrapper[data-slug=\"" + key + "\"]");
						if ($parent.length) {
							let value;
							if ($parent.data("type") === "dropdown") value = $parent.find("option[data-slug=\"" + slug + "\"]").val();
							else value = $parent.find("input[data-slug=\"" + slug + "\"]").val();
							if (value) {
								attributes.push(value);
								slugAttributes[key] = value;
							}
						}
					});
					_self.callAjax(attributes, $el, slugAttributes, false);
				});
			}, false);
		}
		getProductVariation($productAttributes) {
			let _self = this;
			let attributes = [];
			let slugAttributes = {};
			/**
			* Break current request
			*/
			if (_self.xhr) {
				_self.xhr.abort();
				_self.xhr = null;
			}
			/**
			* Get attributes
			*/
			let $attributeSwatches = $productAttributes.find(".attribute-swatches-wrapper");
			let referenceProduct = null;
			$attributeSwatches.each((index, el) => {
				let $current = $(el);
				let $input;
				if ($current.data("type") === "dropdown") $input = $current.find("select option:selected");
				else $input = $current.find("input[type=radio]:checked");
				let slug = $input.data("slug");
				let value = $input.val();
				if (value) {
					let setSlug = $current.find(".attribute-swatch").data("slug");
					slugAttributes[setSlug] = slug;
					attributes.push(value);
					referenceProduct = $input.data("reference-product");
				}
			});
			_self.callAjax(attributes, $productAttributes, slugAttributes, true, referenceProduct);
		}
		getFromCache(key) {
			const item = this.cache.get(key);
			if (!item) return null;
			if (Date.now() - item.timestamp > this.cacheTimeout) {
				this.cache.delete(key);
				return null;
			}
			return item.data;
		}
		setCache(key, data) {
			this.cache.set(key, {
				data,
				timestamp: Date.now()
			});
			if (this.cache.size > 100) {
				const firstKey = this.cache.keys().next().value;
				this.cache.delete(firstKey);
			}
		}
		previewSwatchImage(imageUrl, $item) {
			console.log("[PREVIEW] previewSwatchImage called", {
				imageUrl,
				item: $item[0]
			});
			const $product = $item.closest(".bb-product-detail");
			console.log("[PREVIEW] Found product container:", $product.length);
			if (!$product.length) {
				console.log("[PREVIEW] No product container found, exiting");
				return;
			}
			const $quickShopModal = $item.closest("#quick-shop-modal");
			const isQuickShop = $quickShopModal.length > 0;
			console.log("[PREVIEW] Context:", {
				isQuickShop,
				quickShopFound: $quickShopModal.length
			});
			if (isQuickShop) {
				console.log("[PREVIEW] Quick Shop mode - checking for updateQSGalleryImages");
				if (typeof window.updateQSGalleryImages === "function") {
					console.log("[PREVIEW] Calling window.updateQSGalleryImages with:", [imageUrl]);
					window.updateQSGalleryImages([imageUrl]);
				} else console.log("[PREVIEW] window.updateQSGalleryImages not found!");
			} else {
				console.log("[PREVIEW] PV Gallery mode");
				const $pvGallery = $product.find(".pv-gallery-main");
				console.log("[PREVIEW] Found PV gallery:", $pvGallery.length);
				if ($pvGallery.length) {
					const $mainImage = $pvGallery.find("#pv-main-image");
					console.log("[PREVIEW] Found main image:", $mainImage.length, "src:", $mainImage.attr("src"));
					if ($mainImage.length) {
						if (!$mainImage.data("original-src")) {
							$mainImage.data("original-src", $mainImage.attr("src"));
							console.log("[PREVIEW] Stored original src:", $mainImage.data("original-src"));
						}
						$mainImage.attr("src", imageUrl);
						console.log("[PREVIEW] Updated main image src to:", imageUrl);
					}
				} else console.log("[PREVIEW] No PV gallery found in product container");
			}
		}
		restoreSelectedImage() {
			console.log("[RESTORE] restoreSelectedImage called");
			const $selectedSwatch = $(".product-attributes .visual-swatch input:checked").closest(".bb-product-attribute-swatch-item");
			console.log("[RESTORE] Found selected swatch:", $selectedSwatch.length);
			if (!$selectedSwatch.length) {
				console.log("[RESTORE] No selected swatch, exiting");
				return;
			}
			const selectedImage = $selectedSwatch.data("variation-image");
			const $product = $selectedSwatch.closest(".bb-product-detail");
			console.log("[RESTORE] Selected image:", selectedImage, "Product:", $product.length);
			if (!$product.length) {
				console.log("[RESTORE] No product container, exiting");
				return;
			}
			const isQuickShop = $selectedSwatch.closest("#quick-shop-modal").length > 0;
			console.log("[RESTORE] Context:", { isQuickShop });
			if (isQuickShop) {
				console.log("[RESTORE] Quick Shop mode");
				if (selectedImage && typeof window.updateQSGalleryImages === "function") {
					console.log("[RESTORE] Calling updateQSGalleryImages with:", [selectedImage]);
					window.updateQSGalleryImages([selectedImage]);
				} else console.log("[RESTORE] Missing:", {
					selectedImage,
					hasFunction: typeof window.updateQSGalleryImages
				});
			} else {
				console.log("[RESTORE] PV Gallery mode");
				const $pvGallery = $product.find(".pv-gallery-main");
				console.log("[RESTORE] Found PV gallery:", $pvGallery.length);
				if ($pvGallery.length) {
					const $mainImage = $pvGallery.find("#pv-main-image");
					console.log("[RESTORE] Found main image:", $mainImage.length);
					if ($mainImage.length && selectedImage) {
						console.log("[RESTORE] Restoring to selected image:", selectedImage);
						$mainImage.attr("src", selectedImage);
					} else if ($mainImage.length && $mainImage.data("original-src")) {
						console.log("[RESTORE] Restoring to original:", $mainImage.data("original-src"));
						$mainImage.attr("src", $mainImage.data("original-src"));
						$mainImage.removeData("original-src");
					} else console.log("[RESTORE] Cannot restore - missing image or original-src");
				}
			}
		}
	};
	$(() => {
		const swatchInstance = new ChangeProductSwatches();
		$(".product-attribute-swatches").each(function() {
			const $container = $(this);
			const hasCheckedRadio = $container.find("input[type=radio]:checked:not(:disabled)").length > 0;
			const hasSelectedDropdown = $container.find("select.product-filter-item").filter(function() {
				return !!$(this).val();
			}).length > 0;
			if (!hasCheckedRadio && !hasSelectedDropdown) {
				$container.find(".attribute-swatches-wrapper").each(function() {
					const $firstAvailable = $(this).find("input[type=radio]:not(:disabled)").first();
					if ($firstAvailable.length) $firstAvailable.prop("checked", true);
				});
				swatchInstance.getProductVariation($container);
			}
		});
	});
	//#endregion
})();

//# sourceMappingURL=change-product-swatches.js.map