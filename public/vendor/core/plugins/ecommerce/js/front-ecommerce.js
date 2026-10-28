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
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/checkPrivateRedeclaration.js
	function _checkPrivateRedeclaration(e, t) {
		if (t.has(e)) throw new TypeError("Cannot initialize the same private elements twice on an object");
	}
	//#endregion
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/classPrivateFieldInitSpec.js
	function _classPrivateFieldInitSpec(e, t, a) {
		_checkPrivateRedeclaration(e, t), t.set(e, a);
	}
	//#endregion
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/assertClassBrand.js
	function _assertClassBrand(e, t, n) {
		if ("function" == typeof e ? e === t : e.has(t)) return arguments.length < 3 ? t : n;
		throw new TypeError("Private element is not present on this object");
	}
	//#endregion
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/classPrivateFieldGet2.js
	function _classPrivateFieldGet2(s, a) {
		return s.get(_assertClassBrand(s, a));
	}
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/front-ecommerce.js
	var _transformFormData = /* @__PURE__ */ new WeakMap();
	var _ajaxSearchProducts = /* @__PURE__ */ new WeakMap();
	var _ajaxFilterForm = /* @__PURE__ */ new WeakMap();
	var _initCategoriesDropdown = /* @__PURE__ */ new WeakMap();
	var Ecommerce = class {
		showError(message) {
			if (typeof Theme !== "undefined") Theme.showError(message);
		}
		showSuccess(message) {
			if (typeof Theme !== "undefined") Theme.showSuccess(message);
		}
		handleError(error, $form = null) {
			if (typeof Theme !== "undefined") Theme.handleError(error, $form);
		}
		updateLazyLoad() {
			if (typeof Theme !== "undefined" && Theme.lazyLoadInstance) Theme.lazyLoadInstance.update();
		}
		getCsrfToken($form = null) {
			if ($form && $form.length) {
				const formToken = $form.find("input[name=\"_token\"]").val();
				if (formToken) return formToken;
			}
			return $("meta[name=\"csrf-token\"]").attr("content") || "";
		}
		getAjaxData(data = {}, $form = null) {
			const token = this.getCsrfToken($form);
			if (token) data._token = token;
			return data;
		}
		constructor() {
			_defineProperty(this, "quickSearchAjax", null);
			_defineProperty(this, "filterAjax", null);
			_defineProperty(this, "lastFilterFormData", null);
			_defineProperty(this, "lastFilterFormAction", null);
			_defineProperty(this, "filterTimeout", null);
			_classPrivateFieldInitSpec(this, _transformFormData, (formData) => {
				let data = [];
				let groupedData = {};
				let seenParams = {};
				formData.forEach((item) => {
					if (item.name.includes("attributes[")) {
						if (item.value) data.push(item);
					} else if (item.name.endsWith("[]")) {
						const baseName = item.name.slice(0, -2);
						if (!groupedData[baseName]) groupedData[baseName] = /* @__PURE__ */ new Set();
						if (item.value) groupedData[baseName].add(item.value);
					} else if (!seenParams[item.name]) {
						seenParams[item.name] = true;
						if (item.value) data.push(item);
					}
				});
				Object.keys(groupedData).forEach((key) => {
					const values = Array.from(groupedData[key]);
					if (values.length > 0) data.push({
						name: key,
						value: values.join(",")
					});
				});
				return data;
			});
			_defineProperty(this, "highlightSearchKeywords", ($element, phrase) => {
				if (!phrase.trim()) return;
				let keywords = phrase.trim().split(/\s+/);
				let regex = new RegExp(`(${keywords.join("|")})`, "gi");
				$element.html($element.text().replace(regex, "<span class=\"bb-quick-search-highlight\">$1</span>"));
			});
			_classPrivateFieldInitSpec(this, _ajaxSearchProducts, (form, url) => {
				const button = form.find("button[type=\"submit\"]");
				const input = form.find("input[name=\"q\"]");
				const results = form.find(".bb-quick-search-results");
				if (!input.val()) {
					results.removeClass("show").html("");
					return;
				}
				this.quickSearchAjax = $.ajax({
					type: "GET",
					url: url || form.data("ajax-url"),
					data: form.serialize(),
					beforeSend: () => {
						button.addClass("btn-loading");
						if (!url) results.removeClass("show").html("");
						if (this.quickSearchAjax !== null) this.quickSearchAjax.abort();
					},
					success: ({ error, message, data }) => {
						if (error) {
							EcommerceApp.showError(message);
							return;
						}
						results.addClass("show");
						if (url) results.find(".bb-quick-search-list").append($(data).find(".bb-quick-search-list").html());
						else results.html(data);
						let that = this;
						let searchPhrase = input.val();
						results.find(".bb-quick-search-item-name").each(function() {
							$(this).html($(this).text());
							if (searchPhrase) that.highlightSearchKeywords($(this), searchPhrase);
						});
						EcommerceApp.updateLazyLoad();
					},
					complete: () => button.removeClass("btn-loading")
				});
			});
			_classPrivateFieldInitSpec(this, _ajaxFilterForm, (url, data, nextUrl) => {
				const form = $(".bb-product-form-filter");
				if (url && !data) {
					this.lastFilterFormData = null;
					this.lastFilterFormAction = null;
				}
				let ajaxData = data;
				if (Array.isArray(data)) {
					const params = new URLSearchParams();
					const paramsByName = {};
					const attributeArrays = {};
					data.forEach((item) => {
						if (item.name && item.value) {
							if (item.name.includes("attributes[") && item.name.endsWith("[]")) {
								if (!attributeArrays[item.name]) attributeArrays[item.name] = [];
								attributeArrays[item.name].push(item.value);
							} else paramsByName[item.name] = item.value;
						}
					});
					Object.keys(paramsByName).forEach((name) => {
						params.set(name, paramsByName[name]);
					});
					Object.keys(attributeArrays).forEach((name) => {
						attributeArrays[name].forEach((value) => {
							params.append(name, value);
						});
					});
					ajaxData = {};
					Object.keys(paramsByName).forEach((name) => {
						ajaxData[name] = paramsByName[name];
					});
					Object.keys(attributeArrays).forEach((name) => {
						ajaxData[name] = attributeArrays[name];
					});
				}
				this.filterAjax = $.ajax({
					url,
					type: "GET",
					data: ajaxData,
					beforeSend: () => {
						document.dispatchEvent(new CustomEvent("ecommerce.product-filter.before", { detail: {
							data: ajaxData,
							element: form
						} }));
					},
					success: (data) => {
						const { message, error } = data;
						if (error) {
							EcommerceApp.showError(message);
							this.filterAjax = null;
							return;
						}
						let finalUrl = nextUrl || url;
						if (finalUrl.includes("?")) {
							const urlParts = finalUrl.split("?");
							const baseUrl = urlParts[0];
							const params = new URLSearchParams(urlParts[1]);
							const uniqueParams = new URLSearchParams();
							const attributeArrays = {};
							for (const [key, value] of params.entries()) if (key.includes("attributes[") && key.endsWith("[]")) {
								if (!attributeArrays[key]) attributeArrays[key] = [];
								attributeArrays[key].push(value);
							} else if (!uniqueParams.has(key)) uniqueParams.set(key, value);
							Object.keys(attributeArrays).forEach((key) => {
								attributeArrays[key].forEach((value) => {
									uniqueParams.append(key, value);
								});
							});
							finalUrl = baseUrl + "?" + uniqueParams.toString();
						}
						window.history.pushState(data, null, finalUrl);
						document.dispatchEvent(new CustomEvent("ecommerce.product-filter.success", { detail: {
							data,
							element: form
						} }));
						this.filterAjax = null;
						if ($(".bb-product-price-filter").length) EcommerceApp.initPriceFilter();
					},
					error: (xhr) => {
						if (xhr.statusText !== "abort") EcommerceApp.handleError(xhr);
					},
					complete: () => {
						this.filterTimeout = null;
						this.filterAjax = null;
						EcommerceApp.updateLazyLoad();
						document.dispatchEvent(new CustomEvent("ecommerce.product-filter.completed", { detail: { element: form } }));
					}
				});
			});
			_classPrivateFieldInitSpec(this, _initCategoriesDropdown, async () => {
				const makeRequest = (url, beforeCallback, successCallback) => {
					beforeCallback();
					fetch(url, {
						method: "GET",
						headers: {
							"Content-Type": "application/json",
							"Accept": "application/json"
						}
					}).then((response) => {
						if (!response.ok) throw new Error("Network response was not ok");
						return response.json();
					}).then(({ error, data }) => {
						if (error) return;
						successCallback(data);
						document.dispatchEvent(new CustomEvent("ecommerce.categories-dropdown.success", { detail: { data } }));
					}).catch((error) => {
						EcommerceApp.handleError(error);
					});
				};
				const initCategoriesDropdown = $(document).find("[data-bb-toggle=\"init-categories-dropdown\"]");
				if (initCategoriesDropdown.length) makeRequest(initCategoriesDropdown.first().data("url"), () => {}, (data) => {
					initCategoriesDropdown.each((index, element) => {
						const currentTarget = $(element);
						const target = $(currentTarget.data("bb-target"));
						if (target.length) target.html(data.dropdown);
						else currentTarget.append(data.select);
						EcommerceApp.updateLazyLoad();
					});
				});
			});
			_defineProperty(this, "productQuantityToggle", () => {
				$("[data-bb-toggle=\"product-quantity\"]").on("click", "[data-bb-toggle=\"product-quantity-toggle\"]", function(e) {
					const $currentTarget = $(e.currentTarget);
					let $calculation = $currentTarget.data("value");
					if (!$calculation) return;
					let $input = null;
					if ($calculation === "plus") $input = $currentTarget.prev();
					else if ($calculation === "minus") $input = $currentTarget.next();
					if (!$input) return;
					let $quantity = parseInt($input.val()) || 1;
					$input.val($calculation === "plus" ? $quantity + 1 : $quantity === 1 ? 1 : $quantity - 1);
					document.dispatchEvent(new CustomEvent("ecommerce.cart.quantity.change", { detail: {
						element: $currentTarget,
						action: $calculation === "+" ? "increase" : "decrease"
					} }));
				});
			});
			_defineProperty(
				this,
				/**
				* Default handler invoked before a swatch change AJAX fires.
				* Called unconditionally by change-product-swatches.js; user hook
				* window.onBeforeChangeSwatches runs after this (additive).
				*
				* @param {Array<Number>} data
				* @param {jQuery} element
				*/
				"defaultOnBeforeChangeSwatches",
				(data, element) => {
					const form = element.closest("form");
					if (data) {
						form.find("button[type=\"submit\"]").prop("disabled", true);
						form.find("button[data-bb-toggle=\"add-to-cart\"]").prop("disabled", true);
					}
				}
			);
			_defineProperty(
				this,
				/**
				* Default handler invoked on successful swatch change AJAX.
				* Updates price, SKU, stock, disabled attributes, and the main product
				* gallery (images + thumbs + quick-view). Called unconditionally by
				* change-product-swatches.js; user hook window.onChangeSwatchesSuccess
				* runs after this (additive).
				*
				* @param {{data: Object, error: Boolean, message: String}} response
				* @param {jQuery} element
				*/
				"defaultOnChangeSwatchesSuccess",
				(response, element) => {
					if (!response) return;
					let $product = element && element.length ? element.closest(".bb-product-detail") : $();
					if (!$product.length) $product = $(".bb-product-detail").first();
					const $form = element.closest("form");
					const $button = $form.find("button[type=\"submit\"]");
					const $quantity = $form.find("input[name=\"qty\"]");
					const $available = $product.find(".number-items-available");
					const $sku = $product.find("[data-bb-value=\"product-sku\"]");
					const { error, data } = response;
					if (error) {
						$button.prop("disabled", true);
						$quantity.prop("disabled", true);
						$form.find("input[name=\"id\"]").val("");
						return;
					}
					$button.prop("disabled", false);
					$quantity.prop("disabled", false);
					$form.find("input[name=\"id\"]").val(data.id);
					$product.find("[data-bb-value=\"product-price\"]").text(data.display_sale_price);
					if (data.sale_price !== data.price) $product.find("[data-bb-value=\"product-original-price\"]").text(data.display_price).show();
					else $product.find("[data-bb-value=\"product-original-price\"]").hide();
					if (data.sku) {
						$sku.text(data.sku);
						$sku.closest("div").show();
					} else $sku.closest("div").hide();
					if (data.error_message) {
						$button.prop("disabled", true);
						$quantity.prop("disabled", true);
						$available.html(`<span class='text-danger'>${data.error_message}</span>`).show();
					} else if (data.warning_message) $available.html(`<span class='text-warning fw-medium fs-6'>${data.warning_message}</span>`).show();
					else if (data.success_message) $available.html(`<span class='text-success'>${data.success_message}</span>`).show();
					else $available.html("").hide();
					$product.find(".bb-product-attribute-swatch-item").removeClass("disabled");
					$product.find(".bb-product-attribute-swatch-list select option").prop("disabled", false);
					const unavailableAttributeIds = data.unavailable_attribute_ids || [];
					if (unavailableAttributeIds.length) unavailableAttributeIds.map((id) => {
						let $swatchItem = $product.find(`.bb-product-attribute-swatch-item[data-id="${id}"]`);
						if ($swatchItem.length) {
							$swatchItem.addClass("disabled");
							$swatchItem.find("input").prop("checked", false);
						} else {
							$swatchItem = $product.find(`.bb-product-attribute-swatch-list select option[data-id="${id}"]`);
							if ($swatchItem.length) $swatchItem.prop("disabled", true);
						}
					});
					let imageHtml = "";
					let thumbHtml = "";
					const siteConfig = window.siteConfig || {};
					const placeholder = $product.find(".bb-product-gallery").data("placeholder") || siteConfig.img_placeholder;
					if (!data.image_with_sizes) data.image_with_sizes = {
						origin: [],
						thumb: []
					};
					if (!data.image_with_sizes.origin.length && placeholder) data.image_with_sizes.origin.push(placeholder);
					data.image_with_sizes.origin.forEach(function(item) {
						imageHtml += `
            <a href='${item}'>
                <img src='${item}' alt='${data.name}'>
            </a>
        `;
					});
					if (!data.image_with_sizes.thumb.length && placeholder) data.image_with_sizes.thumb.push(placeholder);
					data.image_with_sizes.thumb.forEach(function(item) {
						thumbHtml += `
            <div>
                <img src='${item}' alt='${data.name}'>
            </div>
        `;
					});
					const $galleryImages = $product.find(".bb-product-gallery");
					const $existingGalleryImages = $galleryImages.find(".bb-product-gallery-images");
					const $existingThumbnails = $galleryImages.find(".bb-product-gallery-thumbnails");
					const existingVideoElements = $existingGalleryImages.find(".bb-product-video").clone();
					const existingVideoThumbnails = $existingThumbnails.find(".video-thumbnail").clone();
					let videoImageHtml = "";
					let videoThumbHtml = "";
					if (existingVideoElements.length > 0) existingVideoElements.each(function() {
						videoImageHtml += $(this)[0].outerHTML;
					});
					if (existingVideoThumbnails.length > 0) existingVideoThumbnails.each(function() {
						videoThumbHtml += `<div>${$(this)[0].outerHTML}</div>`;
					});
					const videoPosition = $galleryImages.data("video-position") || "bottom";
					let finalImageHtml = "";
					let finalThumbHtml = "";
					const imageItems = $("<div>").html(imageHtml).children().toArray().map((el) => el.outerHTML);
					const thumbItems = $("<div>").html(thumbHtml).children().toArray().map((el) => el.outerHTML);
					if (videoPosition === "top" || videoPosition === "after_first_image" && imageItems.length === 0) {
						finalImageHtml = videoImageHtml + imageHtml;
						finalThumbHtml = videoThumbHtml + thumbHtml;
					} else if (videoPosition === "after_first_image" && imageItems.length > 0) {
						finalImageHtml = imageItems[0] + videoImageHtml + imageItems.slice(1).join("");
						finalThumbHtml = (thumbItems[0] || "") + videoThumbHtml + thumbItems.slice(1).join("");
					} else if (videoPosition === "before_last_image" && imageItems.length > 1) {
						finalImageHtml = imageItems.slice(0, -1).join("") + videoImageHtml + imageItems.slice(-1).join("");
						finalThumbHtml = thumbItems.slice(0, -1).join("") + videoThumbHtml + thumbItems.slice(-1).join("");
					} else {
						finalImageHtml = imageHtml + videoImageHtml;
						finalThumbHtml = thumbHtml + videoThumbHtml;
					}
					const $thumbnails = $galleryImages.find(".bb-product-gallery-thumbnails");
					if ($.fn.slick && $thumbnails.length && $thumbnails.hasClass("slick-initialized")) $thumbnails.slick("unslick");
					$thumbnails.html(finalThumbHtml);
					const $quickViewGalleryImages = $product.find(".bb-quick-view-gallery-images");
					if ($quickViewGalleryImages.length) {
						if ($.fn.slick && $quickViewGalleryImages.hasClass("slick-initialized")) $quickViewGalleryImages.slick("unslick");
						$quickViewGalleryImages.html(finalImageHtml);
					}
					const $galleryImagesSlider = $galleryImages.find(".bb-product-gallery-images");
					if ($.fn.slick && $galleryImagesSlider.length && $galleryImagesSlider.hasClass("slick-initialized")) $galleryImagesSlider.slick("unslick");
					$galleryImagesSlider.html(finalImageHtml);
					if (!$quickViewGalleryImages.length) {
						if (typeof EcommerceApp !== "undefined") EcommerceApp.initProductGallery();
					} else if (typeof EcommerceApp !== "undefined") EcommerceApp.initProductGallery(true);
					console.log("🔄 === AUTO-SELECT START ===");
					console.log("📦 Data received:", data);
					this.autoSelectOtherAttributes($product, data);
					console.log("👁️ === VISUAL ITEMS VISIBILITY START ===");
					this.updateVisualItemsVisibility($product, data);
					console.log("✅ === SWATCH UPDATE COMPLETE ===");
				}
			);
			_defineProperty(
				this,
				/**
				* When user clicks a DISABLED item, auto-select enabled values for OTHER attributes
				* This runs AFTER CMS has marked items as disabled, so we check the RESULT
				*/
				"autoSelectOtherAttributes",
				($product, data) => {
					console.log("🎯 autoSelectOtherAttributes called");
					console.log("   Product element:", $product.length ? "FOUND" : "NOT FOUND");
					console.log("   Data:", data);
					if (!data || !data.unavailable_attribute_ids) {
						console.log("   ❌ No data or unavailable_attribute_ids, exiting");
						return;
					}
					console.log("   🚫 Unavailable IDs:", data.unavailable_attribute_ids);
					if (window._isAutoSelecting) {
						console.log("   ⏸️ Already auto-selecting, preventing infinite loop");
						return;
					}
					const $allWrappers = $product.find(".qs-swatch-wrap");
					console.log("   📋 Found wrappers:", $allWrappers.length);
					let hasDisabledSelection = false;
					let clickedWrapper = null;
					$allWrappers.each((index, wrapper) => {
						const $wrapper = $(wrapper);
						const wrapperType = $wrapper.data("type");
						const wrapperSlug = $wrapper.data("slug");
						console.log(`\n   🔍 Wrapper ${index + 1}: type="${wrapperType}", slug="${wrapperSlug}"`);
						const $checkedInput = $wrapper.find("input[type=\"radio\"]:checked, select option:selected");
						console.log("      Checked input found?", $checkedInput.length > 0);
						if (!$checkedInput.length) {
							console.log("      ⏭️ No checked input, skipping");
							return;
						}
						const checkedId = parseInt($checkedInput.val());
						console.log("      Checked ID:", checkedId);
						console.log("      Is in unavailable list?", data.unavailable_attribute_ids.includes(checkedId));
						if (checkedId && data.unavailable_attribute_ids.includes(checkedId)) {
							console.log("      ⚠️ DISABLED selection detected!");
							hasDisabledSelection = true;
							clickedWrapper = $wrapper;
							return false;
						} else console.log("      ✅ Selection is enabled");
					});
					console.log("\n   📊 Has disabled selection?", hasDisabledSelection);
					if (!hasDisabledSelection) {
						console.log("   ⏭️ No disabled selection, exiting");
						return;
					}
					console.log("   🔄 Starting auto-selection for OTHER attributes...");
					window._isAutoSelecting = true;
					console.log("   🔒 Set _isAutoSelecting flag");
					$allWrappers.each((index, wrapper) => {
						const $wrapper = $(wrapper);
						const wrapperSlug = $wrapper.data("slug");
						console.log(`\n   🔧 Processing wrapper ${index + 1}: "${wrapperSlug}"`);
						if (clickedWrapper && $wrapper.is(clickedWrapper)) {
							console.log("      ⏭️ This is the clicked wrapper, skipping");
							return;
						}
						const $allItems = $wrapper.find(".bb-product-attribute-swatch-item");
						console.log("      Total items:", $allItems.length);
						const $disabledItems = $wrapper.find(".bb-product-attribute-swatch-item.disabled");
						console.log("      Disabled items:", $disabledItems.length);
						const $enabled = $wrapper.find(".bb-product-attribute-swatch-item:not(.disabled)").first();
						console.log("      First enabled item found?", $enabled.length > 0);
						if ($enabled.length) {
							const enabledId = $enabled.data("id");
							const enabledTitle = $enabled.data("title");
							console.log("      Enabled item ID:", enabledId, "Title:", enabledTitle);
							$wrapper.find(".bb-product-attribute-swatch-item").removeClass("selected");
							$enabled.addClass("selected");
							console.log("      ✅ Added .selected class to enabled item");
							const $input = $enabled.find("input[type=\"radio\"]");
							console.log("      Radio input found?", $input.length > 0);
							if ($input.length) {
								$wrapper.find("input[type=\"radio\"]").prop("checked", false);
								$input.prop("checked", true);
								console.log("      ✅ Radio button checked");
							}
						} else console.log("      ❌ No enabled items found!");
						const $select = $wrapper.find("select");
						if ($select.length) {
							console.log("      Dropdown found, processing...");
							const $enabledOption = $select.find("option:not(:disabled)").first();
							if ($enabledOption.length && $enabledOption.val()) {
								$select.val($enabledOption.val());
								console.log("      ✅ Dropdown value set to:", $enabledOption.val());
							}
						}
					});
					console.log("\n   ✅ Auto-selection complete");
					setTimeout(() => {
						window._isAutoSelecting = false;
						console.log("   🔓 Cleared _isAutoSelecting flag");
					}, 100);
				}
			);
			_defineProperty(
				this,
				/**
				* Hide disabled visual items completely using display:none !important
				*/
				"updateVisualItemsVisibility",
				($product, data) => {
					console.log("👁️ updateVisualItemsVisibility called");
					console.log("   Product element:", $product.length ? "FOUND" : "NOT FOUND");
					const $visualWrappers = $product.find(".qs-swatch-wrap[data-type=\"visual\"]");
					console.log("   📋 Found visual wrappers:", $visualWrappers.length);
					$visualWrappers.each(function(wrapperIndex) {
						const $wrapper = $(this);
						const wrapperSlug = $wrapper.data("slug");
						console.log(`\n   🔍 Visual Wrapper ${wrapperIndex + 1}: slug="${wrapperSlug}"`);
						const $items = $wrapper.find(".bb-product-attribute-swatch-item");
						console.log("      Total items:", $items.length);
						$items.each(function(itemIndex) {
							const $item = $(this);
							const itemId = $item.data("id");
							const itemTitle = $item.data("title");
							const hasDisabledClass = $item.hasClass("disabled");
							const currentDisplay = $item.css("display");
							console.log(`      Item ${itemIndex + 1}: ID=${itemId}, Title="${itemTitle}", .disabled=${hasDisabledClass}, display="${currentDisplay}"`);
							if (hasDisabledClass) {
								$item.css("display", "none");
								$item.attr("style", "display: none !important");
								console.log(`         ❌ HIDING item ${itemId}`);
							} else {
								$item.css("display", "");
								$item.removeAttr("style");
								console.log(`         ✅ SHOWING item ${itemId}`);
							}
							const newDisplay = $item.css("display");
							console.log(`         Final display: "${newDisplay}"`);
						});
					});
					console.log("   ✅ updateVisualItemsVisibility complete");
				}
			);
			_defineProperty(this, "onChangeProductAttribute", () => {});
			_defineProperty(this, "handleUpdateCart", (element) => {
				let form;
				if (element) form = $(element).closest("form");
				else form = $("form.cart-form");
				$.ajax({
					type: "POST",
					url: form.prop("action"),
					data: form.serialize(),
					success: ({ error, message, data }) => {
						if (error) EcommerceApp.showError(message);
						this.ajaxLoadCart(data);
					},
					error: (error) => EcommerceApp.handleError(error)
				});
			});
			_defineProperty(this, "ajaxLoadCart", (data) => {
				if (!data) return;
				const $cart = $("[data-bb-toggle=\"cart-content\"]");
				if (data.count !== void 0) $("[data-bb-value=\"cart-count\"]").text(data.count);
				if (data.total_price !== void 0) $("[data-bb-value=\"cart-total-price\"]").text(data.total_price);
				if ($cart.length) {
					$cart.replaceWith(data.cart_content);
					this.productQuantityToggle();
					EcommerceApp.updateLazyLoad();
				}
			});
			this.initClipboard();
			this.initFileUpload();
			$(document).on("click", "[data-bb-toggle=\"toggle-product-categories-tree\"]", (e) => {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				currentTarget.toggleClass("active");
				currentTarget.closest(".bb-product-filter-item").find("> .bb-product-filter-items").slideToggle().toggleClass("active");
			}).on("click", "[data-bb-toggle=\"toggle-filter-sidebar\"]", () => {
				$(".bb-filter-offcanvas-area").toggleClass("offcanvas-opened");
				$(".body-overlay").toggleClass("opened");
			}).on("click", ".body-overlay", () => {
				$(".bb-filter-offcanvas-area").removeClass("offcanvas-opened");
				$(".body-overlay").removeClass("opened");
			}).on("submit", "form.bb-product-form-filter", (e) => {
				e.preventDefault();
				const form = $(e.currentTarget);
				const formData = _classPrivateFieldGet2(_transformFormData, this).call(this, form.serializeArray());
				const url = form.prop("action");
				let nextUrl = url;
				const searchParams = new URLSearchParams();
				const paramsByName = {};
				const attributeArrays = {};
				const currentUrlParams = new URLSearchParams(window.location.search);
				const currentFilterState = {};
				for (const [key, value] of currentUrlParams.entries()) if (key !== "page" && key !== "_" && value) {
					if (!currentFilterState[key]) currentFilterState[key] = [];
					currentFilterState[key].push(value);
				}
				formData.forEach((item) => {
					if (item.name.includes("attributes[") && item.name.endsWith("[]")) {
						if (!attributeArrays[item.name]) attributeArrays[item.name] = [];
						attributeArrays[item.name].push(item.value);
					} else if (!paramsByName[item.name]) paramsByName[item.name] = item.value;
				});
				const newFilterState = {};
				Object.keys(paramsByName).forEach((name) => {
					if (name !== "page" && name !== "_" && paramsByName[name]) {
						if (!newFilterState[name]) newFilterState[name] = [];
						newFilterState[name].push(paramsByName[name]);
					}
				});
				Object.keys(attributeArrays).forEach((name) => {
					if (!newFilterState[name]) newFilterState[name] = [];
					newFilterState[name] = newFilterState[name].concat(attributeArrays[name]);
				});
				Object.keys(currentFilterState).forEach((key) => {
					if (Array.isArray(currentFilterState[key])) currentFilterState[key].sort();
				});
				Object.keys(newFilterState).forEach((key) => {
					if (Array.isArray(newFilterState[key])) newFilterState[key].sort();
				});
				const filterStateChanged = JSON.stringify(currentFilterState) !== JSON.stringify(newFilterState);
				if (filterStateChanged && paramsByName["page"]) delete paramsByName["page"];
				Object.keys(paramsByName).forEach((name) => {
					if (paramsByName[name]) searchParams.set(name, paramsByName[name]);
				});
				Object.keys(attributeArrays).forEach((name) => {
					attributeArrays[name].forEach((value) => {
						searchParams.append(name, value);
					});
				});
				const queryString = searchParams.toString();
				if (queryString) nextUrl += `?${queryString}`;
				let filteredFormData = formData;
				if (filterStateChanged && formData.some((item) => item.name === "page")) filteredFormData = formData.filter((item) => item.name !== "page");
				const formDataWithTimestamp = [...filteredFormData, {
					name: "_",
					value: Date.now()
				}];
				if (window.location.href === nextUrl) return;
				const formDataString = JSON.stringify(formData);
				const currentFormAction = form.prop("action");
				if (this.lastFilterFormData === formDataString && this.lastFilterFormAction === currentFormAction) return;
				this.lastFilterFormData = formDataString;
				this.lastFilterFormAction = currentFormAction;
				if (this.filterAjax) this.filterAjax.abort();
				if (this.filterTimeout) clearTimeout(this.filterTimeout);
				this.filterTimeout = setTimeout(() => {
					_classPrivateFieldGet2(_ajaxFilterForm, this).call(this, url, formDataWithTimestamp, nextUrl);
				}, 100);
			}).on("change", "form.bb-product-form-filter input, form.bb-product-form-filter select", (e) => {
				const currentTarget = $(e.currentTarget);
				const form = currentTarget.closest("form");
				const inputName = currentTarget.attr("name");
				if (inputName.includes("attributes[")) form.trigger("submit");
				else if (currentTarget.attr("type") === "checkbox" && inputName && inputName.endsWith("[]")) {
					const baseName = inputName.slice(0, -2);
					const checkboxes = form.find(`input[name="${inputName}"]`).filter(":checked");
					let singleInput = form.find(`input[name="${baseName}"]`);
					if (!singleInput.length) {
						form.append(`<input type="hidden" name="${baseName}" value="">`);
						singleInput = form.find(`input[name="${baseName}"]`);
					}
					const values = checkboxes.map(function() {
						return $(this).val();
					}).get();
					singleInput.val(values.join(","));
				}
				form.trigger("submit");
			}).on("keyup", ".bb-form-quick-search input", (e) => {
				_classPrivateFieldGet2(_ajaxSearchProducts, this).call(this, $(e.currentTarget).closest("form"));
			}).on("click", "body", (e) => {
				if (!$(e.target).closest(".bb-form-quick-search").length) $(".bb-quick-search-results").removeClass("show").html("");
			}).on("click", "[data-bb-toggle=\"quick-shop\"]", (e) => {
				e.preventDefault();
				e.stopPropagation();
				const currentTarget = $(e.currentTarget);
				const modal = $("#quick-shop-modal");
				$.ajax({
					url: currentTarget.data("url"),
					type: "GET",
					beforeSend: () => {
						if (modal.find(".quick-shop-content").length) {
							modal.find(".quick-shop-content").html("");
							modal.find(".modal-body").addClass("modal-empty");
							modal.find(".loading-spinner").removeClass("d-none").show();
							modal.find(".quick-shop-content").hide();
						} else modal.find(".modal-body").html("");
						modal.modal("show");
						document.dispatchEvent(new CustomEvent("ecommerce.quick-shop.before-send", { detail: {
							element: currentTarget,
							modal
						} }));
					},
					success: ({ data }) => {
						if (modal.find(".quick-shop-content").length) {
							modal.find(".quick-shop-content").html(data);
							modal.find(".loading-spinner").addClass("d-none").hide();
							modal.find(".modal-body").removeClass("modal-empty");
							modal.find(".quick-shop-content").show();
						} else modal.find(".modal-body").html(data);
					},
					error: (error) => EcommerceApp.handleError(error),
					complete: () => {
						document.dispatchEvent(new CustomEvent("ecommerce.quick-shop.completed", { detail: {
							element: currentTarget,
							modal
						} }));
					}
				});
			}).on("click", ".bb-product-filter-link", (e) => {
				const currentTarget = $(e.currentTarget);
				const form = currentTarget.closest("form");
				if (!form.length) return;
				e.preventDefault();
				const parent = currentTarget.closest(".bb-product-filter");
				const categoryId = currentTarget.data("id");
				let categoriesInput = form.find("input[name=\"categories\"]");
				if (!categoriesInput.length) categoriesInput = form.find("input[name=\"categories[]\"]");
				parent.find(".bb-product-filter-link").removeClass("active");
				currentTarget.addClass("active");
				form.find("input[name=\"page\"]").remove();
				form.find("input[name=\"per-page\"]").remove();
				if (categoriesInput.length && categoryId) {
					if (categoriesInput.attr("name") === "categories[]") categoriesInput.val(categoryId).trigger("change");
					else categoriesInput.val(categoryId).trigger("change");
				} else {
					if (!categoryId) {
						if (categoriesInput.length) categoriesInput.val(null);
					}
					form.prop("action", currentTarget.prop("href")).trigger("submit");
				}
			}).on("click", ".bb-product-filter-clear", (e) => {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				_classPrivateFieldGet2(_ajaxFilterForm, this).call(this, currentTarget.prop("href"));
			}).on("click", ".bb-product-filter-clear-all", (e) => {
				e.preventDefault();
				const form = $(".bb-product-form-filter");
				form.find("input[type=\"text\"], input[type=\"hidden\"], input[type=\"radio\"], select").val(null);
				form.find("input[type=\"checkbox\"]").prop("checked", false);
				form.trigger("submit");
			}).on("submit", "form#cancel-order-form", (e) => {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				const modal = currentTarget.closest(".modal");
				const button = modal.find("button[type=\"submit\"]");
				$.ajax({
					url: currentTarget.prop("action"),
					type: "POST",
					data: currentTarget.serialize(),
					beforeSend: () => {
						button.addClass("btn-loading");
					},
					success: ({ error, message }) => {
						if (error) {
							EcommerceApp.showError(message);
							return;
						}
						EcommerceApp.showSuccess(message);
						modal.modal("hide");
						setTimeout(() => window.location.reload(), 1e3);
					},
					error: (error) => EcommerceApp.handleError(error),
					complete: () => button.removeClass("btn-loading")
				});
			}).on("click", "[data-bb-toggle=\"add-to-compare\"]", function(e) {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				const url = currentTarget.hasClass("active") ? currentTarget.data("remove-url") : currentTarget.data("url");
				let data = currentTarget.hasClass("active") ? { _method: "DELETE" } : {};
				const $form = currentTarget.closest("form");
				$.ajax({
					url,
					method: "POST",
					data: EcommerceApp.getAjaxData(data, $form),
					beforeSend: () => currentTarget.addClass("btn-loading"),
					success: ({ error, message, data }) => {
						if (error) EcommerceApp.showError(message);
						else {
							EcommerceApp.showSuccess(message);
							currentTarget.toggleClass("active");
							if (data.count !== void 0) $("[data-bb-value=\"compare-count\"]").text(data.count);
							if (currentTarget.hasClass("active")) document.dispatchEvent(new CustomEvent("ecommerce.compare.added", { detail: {
								data,
								element: currentTarget,
								extraData: data.extra_data
							} }));
							else document.dispatchEvent(new CustomEvent("ecommerce.compare.removed", { detail: {
								data,
								element: currentTarget,
								extraData: data.extra_data
							} }));
						}
					},
					error: (error) => EcommerceApp.handleError(error),
					complete: () => currentTarget.removeClass("btn-loading")
				});
			}).on("click", "[data-bb-toggle=\"remove-from-compare\"]", (e) => {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				const table = currentTarget.closest("table");
				const $form = currentTarget.closest("form");
				$.ajax({
					url: currentTarget.data("url"),
					method: "POST",
					data: EcommerceApp.getAjaxData({ _method: "DELETE" }, $form),
					success: ({ error, message, data }) => {
						if (error) EcommerceApp.showError(message);
						else {
							EcommerceApp.showSuccess(message);
							document.dispatchEvent(new CustomEvent("ecommerce.compare.removed", { detail: {
								data,
								element: currentTarget,
								extraData: data.extra_data
							} }));
							if (data.count !== void 0) {
								$("[data-bb-value=\"compare-count\"]").text(data.count);
								if (data.count > 0) table.find(`td:nth-child(${currentTarget.closest("td").index() + 1})`).remove();
							} else window.location.reload();
						}
					},
					error: (error) => EcommerceApp.handleError(error)
				});
			}).on("click", "[data-bb-toggle=\"add-to-wishlist\"]", function(e) {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				const url = currentTarget.data("url");
				const $form = currentTarget.closest("form");
				$.ajax({
					url,
					method: "POST",
					data: EcommerceApp.getAjaxData({}, $form),
					beforeSend: () => currentTarget.addClass("btn-loading"),
					success: ({ error, message, data }) => {
						if (error) EcommerceApp.showError(message);
						else {
							if (data.count !== void 0) $("[data-bb-value=\"wishlist-count\"]").text(data.count);
							EcommerceApp.showSuccess(message);
							document.dispatchEvent(new CustomEvent("ecommerce.wishlist.added", { detail: {
								data,
								element: currentTarget,
								extraData: data.extra_data,
								added: data.added
							} }));
						}
					},
					error: (error) => EcommerceApp.handleError(error),
					complete: () => currentTarget.removeClass("btn-loading")
				});
			}).on("click", "[data-bb-toggle=\"remove-from-wishlist\"]", (e) => {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				const $form = currentTarget.closest("form");
				$.ajax({
					url: currentTarget.data("url"),
					method: "POST",
					data: EcommerceApp.getAjaxData({ _method: "DELETE" }, $form),
					beforeSend: () => currentTarget.addClass("btn-loading"),
					success: ({ error, message, data }) => {
						if (error) EcommerceApp.showError(message);
						else {
							EcommerceApp.showSuccess(message);
							currentTarget.closest("tr").remove();
							if (data.count !== void 0) {
								$("[data-bb-value=\"wishlist-count\"]").text(data.count);
								if (data.count === 0) window.location.reload();
							}
							document.dispatchEvent(new CustomEvent("ecommerce.wishlist.removed", { detail: {
								data,
								element: currentTarget,
								extraData: data.extra_data
							} }));
						}
					},
					error: (error) => EcommerceApp.handleError(error),
					complete: () => currentTarget.removeClass("btn-loading")
				});
			}).on("click", "[data-bb-toggle=\"add-to-cart\"]", (e) => {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				const $form = currentTarget.closest("form");
				const quantity = currentTarget.closest("tr").find("input[name=\"qty\"]");
				const data = { id: currentTarget.data("id") };
				if (quantity) data.qty = quantity.val();
				$.ajax({
					url: currentTarget.data("url"),
					method: "POST",
					data: EcommerceApp.getAjaxData(data, $form),
					dataType: "json",
					beforeSend: () => currentTarget.addClass("btn-loading").prop("disabled", true),
					success: ({ error, message, data }) => {
						if (error) {
							EcommerceApp.showError(message);
							if (data.next_url !== void 0) setTimeout(() => {
								window.location.href = data.next_url;
							}, 500);
							return false;
						}
						let showSuccess = true;
						if (currentTarget.data("show-toast-on-success") !== void 0) showSuccess = currentTarget.data("show-toast-on-success");
						if (showSuccess) EcommerceApp.showSuccess(message);
						if (data.count !== void 0) $("[data-bb-value=\"cart-count\"]").text(data.count);
						document.dispatchEvent(new CustomEvent("ecommerce.cart.added", { detail: {
							data,
							element: currentTarget,
							message,
							extraData: data.extra_data
						} }));
						if (data && data.next_url !== void 0) window.location.href = data.next_url;
					},
					error: (error) => EcommerceApp.handleError(error),
					complete: () => currentTarget.removeClass("btn-loading").prop("disabled", false)
				});
			}).on("click", "[data-bb-toggle=\"remove-from-cart\"]", (e) => {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				$.ajax({
					url: currentTarget.prop("href") || currentTarget.data("url"),
					method: "GET",
					beforeSend: () => currentTarget.addClass("btn-loading"),
					success: ({ error, message, data }) => {
						if (error) EcommerceApp.showError(message);
						else {
							EcommerceApp.showSuccess(message);
							currentTarget.closest("tr").remove();
							if (data.count !== void 0) {
								$("[data-bb-value=\"cart-count\"]").text(data.count);
								if (data.count === 0) window.location.reload();
							}
							document.dispatchEvent(new CustomEvent("ecommerce.cart.removed", { detail: {
								data,
								element: currentTarget,
								extraData: data.extra_data
							} }));
						}
					},
					error: (error) => EcommerceApp.handleError(error),
					complete: () => currentTarget.removeClass("btn-loading")
				});
			}).on("submit", "[data-bb-toggle=\"coupon-form\"]", (e) => {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				const button = currentTarget.find("button[type=\"submit\"]");
				$.ajax({
					url: currentTarget.prop("action"),
					type: "POST",
					data: currentTarget.serialize(),
					beforeSend: () => button.prop("disabled", true).addClass("btn-loading"),
					success: ({ error, message, data }) => {
						if (error) EcommerceApp.showError(message);
						else {
							EcommerceApp.showSuccess(message);
							document.dispatchEvent(new CustomEvent("ecommerce.coupon.applied", { detail: {
								data,
								element: currentTarget
							} }));
						}
					},
					error: (error) => EcommerceApp.handleError(error),
					complete: () => button.prop("disabled", false).removeClass("btn-loading")
				});
			}).on("click", "[data-bb-toggle=\"quick-view-product\"]", (e) => {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				$.ajax({
					url: currentTarget.data("url"),
					type: "GET",
					beforeSend: () => currentTarget.prop("disabled", true).addClass("btn-loading"),
					success: ({ error, message, data }) => {
						if (error) EcommerceApp.showError(message);
						else {
							const quickViewModal = $("[data-bb-toggle=\"quick-view-modal\"]");
							quickViewModal.modal("show");
							quickViewModal.find(".modal-body").html(data);
							document.dispatchEvent(new CustomEvent("ecommerce.quick-view.initialized", { detail: {
								data,
								element: currentTarget
							} }));
							setTimeout(() => {
								this.initProductGallery(true);
							}, 100);
						}
					},
					error: (error) => EcommerceApp.handleError(error),
					complete: () => currentTarget.prop("disabled", false).removeClass("btn-loading")
				});
			}).on("click", "[data-bb-toggle=\"product-form\"] button[type=\"submit\"]", (e) => {
				e.preventDefault();
				const currentTarget = $(e.currentTarget);
				const form = currentTarget.closest("form");
				const data = form.serializeArray();
				if (form.find("input[name=\"id\"]").val() === "") return;
				data.push({
					name: "checkout",
					value: currentTarget.prop("name") === "checkout" ? 1 : 0
				});
				$.ajax({
					type: "POST",
					url: form.prop("action"),
					data,
					beforeSend: () => {
						currentTarget.prop("disabled", true).addClass("btn-loading");
					},
					success: ({ error, message, data }) => {
						if (error) {
							EcommerceApp.showError(message);
							return;
						}
						const showToast = form.data("show-toast-on-success");
						if (showToast !== false && showToast !== "false") EcommerceApp.showSuccess(message);
						form.find("input[name=\"qty\"]").val(1);
						if (data.count !== void 0) $("[data-bb-value=\"cart-count\"]").text(data.count);
						document.dispatchEvent(new CustomEvent("ecommerce.cart.added", { detail: {
							data,
							element: currentTarget,
							extraData: data.extra_data
						} }));
						if (data && data.next_url !== void 0) window.location.href = data.next_url;
					},
					error: (error) => EcommerceApp.handleError(error),
					complete: () => currentTarget.prop("disabled", false).removeClass("btn-loading")
				});
			}).on("change", "[data-bb-toggle=\"product-form-filter-item\"]", (e) => {
				const currentTarget = $(e.currentTarget);
				const $form = $(".bb-product-form-filter");
				const name = currentTarget.prop("name");
				const value = currentTarget.val();
				if (name.endsWith("[]") && !name.includes("attributes[")) {
					const baseName = name.slice(0, -2);
					let $input = $form.find(`input[name="${baseName}"]`);
					if (!$input.length) {
						$form.append(`<input type="hidden" name="${baseName}" value="">`);
						$input = $form.find(`input[name="${baseName}"]`);
					}
					let currentValues = $input.val() ? $input.val().split(",") : [];
					const uniqueValues = new Set(currentValues);
					uniqueValues.add(value);
					$input.val(Array.from(uniqueValues).join(","));
				} else if (currentTarget.is(":checkbox")) {
					const isChecked = currentTarget.prop("checked");
					const $input = $form.find(`input[type="hidden"][name="${name}"]`);
					if ($input.length) $input.val(isChecked ? value : "");
					else $form.append(`<input type="hidden" name="${name}" value="${isChecked ? value : ""}">`);
				} else {
					const $input = $form.find(`input[name="${name}"]`);
					if ($input.length) $input.val(value);
				}
				$form.trigger("submit");
			});
			if ($(".bb-product-price-filter").length) this.initPriceFilter();
			_classPrivateFieldGet2(_initCategoriesDropdown, this).call(this);
		}
		/**
		* @returns {boolean}
		*/
		isRtl() {
			return document.body.getAttribute("dir") === "rtl";
		}
		/**
		* @param {JQuery} element
		*/
		initLightGallery(element) {
			if (!element.length) return;
			if (element.data("lightGallery")) element.data("lightGallery").destroy(true);
			element.lightGallery({
				selector: "a",
				thumbnail: true,
				share: false,
				fullScreen: false,
				autoplay: false,
				autoplayControls: false,
				actualSize: false
			});
		}
		initProductGallery(onlyQuickView = false) {
			if (typeof $.fn.slick === "undefined") return;
			if (!onlyQuickView) {
				const $gallery = $(document).find(".bb-product-gallery-images");
				if ($gallery.length) {
					const $thumbnails = $(document).find(".bb-product-gallery-thumbnails");
					function postMessageToPlayer(player, command) {
						if (!player || !command || !player.src) return;
						let origin;
						try {
							origin = new URL(player.src).origin;
						} catch (e) {
							return;
						}
						player.contentWindow.postMessage(JSON.stringify(command), origin);
					}
					function playPauseVideo(slick, control) {
						let currentSlide, slideType, startTime, player, video;
						currentSlide = slick.find(".slick-current");
						slideType = currentSlide.data("provider");
						player = currentSlide.get(0);
						startTime = currentSlide.data("video-start");
						if (slideType === "vimeo") switch (control) {
							case "play":
								if (startTime != null && startTime > 0 && !currentSlide.hasClass("started")) {
									currentSlide.addClass("started");
									postMessageToPlayer(player, {
										method: "setCurrentTime",
										value: startTime
									});
								}
								postMessageToPlayer(player, {
									method: "play",
									value: 1
								});
								break;
							case "pause": postMessageToPlayer(player, {
								method: "pause",
								value: 1
							});
						}
						else if (slideType === "youtube") switch (control) {
							case "play":
								postMessageToPlayer(player, {
									event: "command",
									func: "mute"
								});
								postMessageToPlayer(player, {
									event: "command",
									func: "playVideo"
								});
								break;
							case "pause": postMessageToPlayer(player, {
								event: "command",
								func: "pauseVideo"
							});
						}
						else if (slideType === "video") {
							video = currentSlide.children("video").get(0);
							if (video != null) {
								if (control === "play") video.play();
								else video.pause();
							}
						}
					}
					$gallery.on("init", function(slick) {
						slick = $(slick.currentTarget);
						setTimeout(function() {
							playPauseVideo(slick, "play");
						}, 1e3);
					});
					$gallery.on("beforeChange", function(event, slick) {
						slick = $(slick.$slider);
						playPauseVideo(slick, "pause");
					});
					$gallery.on("afterChange", function(event, slick) {
						slick = $(slick.$slider);
						playPauseVideo(slick, "play");
					});
					$(document).on("click", ".bb-button-trigger-play-video", function(e) {
						const $button = $(e.currentTarget);
						const videoElement = document.getElementById($button.data("target"));
						videoElement.muted = false;
						videoElement.play();
						$button.closest(".bb-product-video").addClass("bb-product-video-playing");
					});
					if (!window.bbProductVideoEventsBound) {
						window.bbProductVideoEventsBound = true;
						document.addEventListener("play", (e) => {
							if (e.target.controls) $(e.target).closest(".bb-product-video").addClass("bb-product-video-playing");
						}, true);
						document.addEventListener("pause", (e) => {
							if (e.target.controls && !e.target.ended) $(e.target).closest(".bb-product-video").removeClass("bb-product-video-playing");
						}, true);
						document.addEventListener("ended", (e) => {
							const $wrapper = $(e.target).closest(".bb-product-video");
							if ($wrapper.length) {
								e.target.currentTime = 0;
								e.target.pause();
								$wrapper.removeClass("bb-product-video-playing");
							}
						}, true);
					}
					$(document).on("click", ".bb-product-video-facade", function(e) {
						e.preventDefault();
						const $facade = $(e.currentTarget);
						const src = $facade.data("src");
						if (!src) return;
						const iframe = document.createElement("iframe");
						iframe.setAttribute("src", src);
						iframe.setAttribute("frameborder", "0");
						iframe.setAttribute("allow", "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share");
						iframe.setAttribute("referrerpolicy", "strict-origin-when-cross-origin");
						iframe.setAttribute("allowfullscreen", "");
						iframe.setAttribute("title", $facade.data("title") || "");
						iframe.style.cssText = "position:absolute;top:0;left:0;width:100%;height:100%;border:0;";
						$facade.replaceWith(iframe);
					});
					if ($gallery.length) $gallery.each((index, item) => {
						const $item = $(item);
						if ($item.hasClass("slick-initialized")) $item.slick("unslick");
						$item.removeClass("slick-initialized slick-slider slick-vertical slick-dotted");
						if (!$item.children().length) return;
						try {
							$item.slick({
								slidesToShow: 1,
								slidesToScroll: 1,
								arrows: false,
								dots: false,
								infinite: false,
								fade: true,
								lazyLoad: "ondemand",
								asNavFor: ".bb-product-gallery-thumbnails",
								rtl: this.isRtl()
							});
						} catch (e) {}
					});
					if ($thumbnails.length) $thumbnails.each((index, item) => {
						const $item = $(item);
						if ($item.hasClass("slick-initialized")) $item.slick("unslick");
						$item.removeClass("slick-initialized slick-slider slick-vertical slick-dotted");
						if (!$item.children().length) return;
						let isVertical = $item.data("vertical") === 1;
						if (window.innerWidth < 768) isVertical = false;
						try {
							$item.on("init", function(event, slick) {
								const $slides = $(slick.$slides).not(".slick-cloned");
								const $imageSlides = $slides.filter(":not(.video-thumbnail)");
								const $videoSlides = $slides.filter(".video-thumbnail)");
								if ($imageSlides.length > 4) {
									const moreCount = $imageSlides.length - 4;
									const $fifthImage = $imageSlides.eq(4);
									if ($fifthImage.length && !$fifthImage.find(".gallery-more-count").length) {
										const $img = $fifthImage.find("img");
										if (!$img.parent().hasClass("thumbnail-wrapper")) $img.wrap("<div class=\"thumbnail-wrapper\" style=\"position:relative;width:100%;height:100%;\"></div>");
										$img.parent().append("<span class=\"gallery-more-count\">+" + moreCount + "</span>");
									}
								}
								if ($videoSlides.length > 2) {
									const moreCount = $videoSlides.length - 2;
									const $thirdVideo = $videoSlides.eq(2);
									if ($thirdVideo.length && !$thirdVideo.find(".gallery-more-count").length) {
										const $img = $thirdVideo.find("img").first();
										if (!$img.parent().hasClass("thumbnail-wrapper")) $img.wrap("<div class=\"thumbnail-wrapper\" style=\"position:relative;width:100%;height:100%;\"></div>");
										$img.parent().append("<span class=\"gallery-more-count\">+" + moreCount + "</span>");
									}
								}
							});
							$item.slick({
								slidesToShow: 4,
								slidesToScroll: 1,
								asNavFor: ".bb-product-gallery-images",
								focusOnSelect: true,
								infinite: false,
								arrows: false,
								rtl: this.isRtl() && !isVertical,
								vertical: isVertical,
								verticalSwiping: isVertical,
								responsive: [{
									breakpoint: 768,
									settings: {
										slidesToShow: 4,
										arrows: false
									}
								}]
							});
						} catch (e) {}
					});
					this.initLightGallery($gallery);
					EcommerceApp.updateLazyLoad();
				}
			}
			const $quickViewGallery = $(document).find(".bb-quick-view-gallery-images");
			if ($quickViewGallery.length) $quickViewGallery.each((index, item) => {
				const $item = $(item);
				if ($item.hasClass("slick-initialized")) $item.slick("unslick");
				$item.removeClass("slick-initialized slick-slider slick-vertical slick-dotted");
				if (!$item.children().length) return;
				try {
					$item.slick({
						slidesToShow: 1,
						slidesToScroll: 1,
						dots: false,
						arrows: true,
						adaptiveHeight: false,
						rtl: this.isRtl()
					});
				} catch (e) {}
			});
			this.initLightGallery($quickViewGallery);
		}
		initPriceFilter() {
			if (typeof $.fn.slider === "undefined") throw new Error("jQuery UI slider is required for price filter");
			const $priceFilter = $(document).find(".bb-product-price-filter");
			const $sliderRange = $priceFilter.find(".price-slider");
			const $rangeLabel = $priceFilter.find(".input-range-label");
			if ($priceFilter) {
				const $minPrice = $priceFilter.find("input[name=\"min_price\"]");
				const $maxPrice = $priceFilter.find("input[name=\"max_price\"]");
				const currentMinPrice = parseInt($minPrice.val()) || $sliderRange.data("min");
				const currentMaxPrice = parseInt($maxPrice.val()) || $sliderRange.data("max");
				const isRtl = this.isRtl();
				const sliderMin = isRtl ? -$sliderRange.data("max") : $sliderRange.data("min");
				const sliderMax = isRtl ? -$sliderRange.data("min") : $sliderRange.data("max");
				const sliderValues = isRtl ? [-currentMaxPrice, -currentMinPrice] : [currentMinPrice, currentMaxPrice];
				$sliderRange.slider({
					range: true,
					min: sliderMin,
					max: sliderMax,
					values: sliderValues,
					slide: function(_, ui) {
						const realMin = isRtl ? -ui.values[1] : ui.values[0];
						const realMax = isRtl ? -ui.values[0] : ui.values[1];
						$rangeLabel.find(".from").text(this.formatPrice(realMin));
						$rangeLabel.find(".to").text(this.formatPrice(realMax));
					}.bind(this),
					change: function(_, ui) {
						const realMin = isRtl ? -ui.values[1] : ui.values[0];
						const realMax = isRtl ? -ui.values[0] : ui.values[1];
						if (parseInt(currentMinPrice) !== realMin) $minPrice.val(realMin).trigger("change");
						if (parseInt(currentMaxPrice) !== realMax) $maxPrice.val(realMax).trigger("change");
					}.bind(this)
				});
				const currentValues = $sliderRange.slider("values");
				const displayMin = isRtl ? -currentValues[1] : currentValues[0];
				const displayMax = isRtl ? -currentValues[0] : currentValues[1];
				$rangeLabel.find(".from").text(this.formatPrice(displayMin));
				$rangeLabel.find(".to").text(this.formatPrice(displayMax));
			}
		}
		formatPrice(price, numberAfterDot, x) {
			const currencies = window.currencies || {};
			if (!numberAfterDot) numberAfterDot = currencies.number_after_dot !== void 0 ? currencies.number_after_dot : 2;
			const regex = "\\d(?=(\\d{" + (x || 3) + "})+$)";
			let priceUnit = "";
			if (currencies.show_symbol_or_title) priceUnit = currencies.symbol || currencies.title || "";
			if (currencies.display_big_money) {
				let label = "";
				if (price >= 1e6 && price < 1e9) {
					price = price / 1e6;
					label = currencies.million;
				} else if (price >= 1e9) {
					price = price / 1e9;
					label = currencies.billion;
				}
				priceUnit = label + (priceUnit ? ` ${priceUnit}` : "");
			}
			price = price.toFixed(Math.max(0, ~~numberAfterDot)).toString().split(".");
			price = price[0].toString().replace(new RegExp(regex, "g"), `$&${currencies.thousands_separator}`) + (price[1] ? currencies.decimal_separator + price[1] : "");
			if (currencies.show_symbol_or_title) price = currencies.is_prefix_symbol ? priceUnit + price : price + priceUnit;
			return price;
		}
		/**
		* Initialize attribute swatch hover labels
		* On hover: show the hovered item's title
		* On leave: restore the selected item's title (or default)
		*/
		initAttributeSwatchHoverLabels() {
			$(document).on("mouseenter", ".bb-product-attribute-swatch-item", function() {
				const $item = $(this);
				const $label = $item.closest(".qs-swatch-wrap").find(".qs-swatch__selected-val");
				if (!$label.length) return;
				const itemTitle = $item.data("title") || $item.attr("title") || "";
				if (itemTitle) $label.text(itemTitle);
			});
			$(document).on("mouseleave", ".bb-product-attribute-swatch-item", function() {
				const $wrapper = $(this).closest(".qs-swatch-wrap");
				const $label = $wrapper.find(".qs-swatch__selected-val");
				const $selected = $wrapper.find(".bb-product-attribute-swatch-item.selected");
				if (!$label.length) return;
				if ($selected.length) {
					const selectedTitle = $selected.data("title") || $selected.attr("title") || "";
					$label.text(selectedTitle);
				} else {
					const defaultValue = $label.data("default") || "";
					$label.text(defaultValue);
				}
			});
		}
		initFileUpload() {
			$(document).on("change", ".bb-file-input", (e) => {
				const input = e.target;
				const label = $(input).siblings(".bb-file-label");
				const fileName = label.find(".bb-file-name");
				label.find(".bb-file-placeholder");
				if (input.files && input.files.length > 0) {
					const file = input.files[0];
					fileName.text(file.name);
					label.addClass("has-file");
				} else {
					fileName.text("");
					label.removeClass("has-file");
				}
			});
		}
		initClipboard() {
			$(document).on("click", "[data-ecommerce-clipboard]", async (e) => {
				e.preventDefault();
				const target = $(e.currentTarget);
				const copiedMessage = target.data("clipboard-message");
				const text = target.data("clipboard-text");
				if (!text) return;
				let copied = false;
				if (navigator.clipboard && window.isSecureContext) try {
					await navigator.clipboard.writeText(text);
					copied = true;
				} catch (err) {}
				if (!copied) {
					const textArea = document.createElement("textarea");
					textArea.value = text;
					textArea.style.position = "fixed";
					textArea.style.left = "-999999px";
					textArea.style.top = "-999999px";
					document.body.appendChild(textArea);
					textArea.focus();
					textArea.select();
					try {
						document.execCommand("copy");
						copied = true;
					} catch (err) {}
					document.body.removeChild(textArea);
				}
				if (copied) {
					if (copiedMessage) EcommerceApp.showSuccess(copiedMessage);
					const originalHtml = target.html();
					target.html("<i class=\"ti ti-check\"></i> " + (copiedMessage || "Copied!"));
					setTimeout(() => {
						target.html(originalHtml);
					}, 2e3);
				} else EcommerceApp.showError("Failed to copy to clipboard");
			});
		}
	};
	$(() => {
		window.EcommerceApp = new Ecommerce();
		EcommerceApp.productQuantityToggle();
		EcommerceApp.initAttributeSwatchHoverLabels();
		EcommerceApp.initProductGallery();
		EcommerceApp.onChangeProductAttribute();
		if ($(".bb-product-price-filter").length) EcommerceApp.initPriceFilter();
		document.addEventListener("ecommerce.quick-shop.completed", () => {
			EcommerceApp.productQuantityToggle();
		});
		document.addEventListener("ecommerce.cart.quantity.change", (e) => {
			const { element } = e.detail;
			EcommerceApp.handleUpdateCart(element);
		});
		document.addEventListener("ecommerce.product-filter.before", () => {
			let $wrapper = $("[data-bb-toggle=\"product-list\"]").find(".bb-product-items-wrapper");
			if ($wrapper.length) $wrapper.append("<div class=\"loading-spinner\"></div>");
		});
		document.addEventListener("ecommerce.product-filter.success", (e) => {
			const { data } = e.detail;
			const $productItemsWrapper = $(".bb-product-items-wrapper");
			if ($productItemsWrapper.length) $productItemsWrapper.html(data.data);
			if (data.additional) {
				const $descriptionContainer = $(".bb-product-listing-page-description");
				const descriptionHtml = data.additional.product_listing_page_description_html;
				if (descriptionHtml) {
					if ($descriptionContainer.length) {
						$descriptionContainer.html(descriptionHtml);
						document.dispatchEvent(new CustomEvent("shortcode.loaded", { detail: {
							name: null,
							attributes: [],
							html: descriptionHtml
						} }));
						EcommerceApp.updateLazyLoad();
					}
				} else $descriptionContainer.html("");
				const $defaultSidebar = $(".bb-shop-sidebar");
				let $sidebar = $("[data-bb-filter-sidebar]");
				if (!$sidebar.length) $sidebar = $defaultSidebar;
				const activeFilterLinks = {};
				$(".bb-product-filter-link.active").each(function() {
					const filterGroup = $(this).closest(".bb-product-filter").data("filter-group");
					if (filterGroup) activeFilterLinks[filterGroup] = $(this).data("id");
				});
				const checkedCheckboxes = [];
				$sidebar.find("input[type=\"checkbox\"]:checked").each(function() {
					const $checkbox = $(this);
					const name = $checkbox.attr("name");
					const value = $checkbox.val();
					const id = $checkbox.attr("id");
					checkedCheckboxes.push({
						name,
						value,
						id
					});
				});
				$sidebar.replaceWith(data.additional.filters_html);
				setTimeout(() => {
					const $newSidebar = $("[data-bb-filter-sidebar]").length ? $("[data-bb-filter-sidebar]") : $(".bb-shop-sidebar");
					Object.keys(activeFilterLinks).forEach((group) => {
						const id = activeFilterLinks[group];
						$newSidebar.find(`.bb-product-filter[data-filter-group="${group}"] .bb-product-filter-link[data-id="${id}"]`).addClass("active");
					});
					checkedCheckboxes.forEach((checkbox) => {
						let $targetCheckbox = null;
						if (checkbox.id) $targetCheckbox = $newSidebar.find(`#${checkbox.id}`);
						if (!$targetCheckbox || !$targetCheckbox.length) $targetCheckbox = $newSidebar.find(`input[type="checkbox"][name="${checkbox.name}"][value="${checkbox.value}"]`);
						if (!$targetCheckbox || !$targetCheckbox.length) $targetCheckbox = $newSidebar.find(`input[type="checkbox"][value="${checkbox.value}"]`).filter(function() {
							return $(this).attr("name") === checkbox.name;
						});
						if ($targetCheckbox && $targetCheckbox.length) $targetCheckbox.prop("checked", true);
					});
				}, 10);
			}
			if ($(document).find(".bb-product-price-filter").length) EcommerceApp.initPriceFilter();
			if ($productItemsWrapper.length) $("html, body").animate({ scrollTop: $productItemsWrapper.offset().top - 120 });
			let $wrapper = $("[data-bb-toggle=\"product-list\"]").find(".bb-product-items-wrapper");
			if ($wrapper.length) $wrapper.find(".loading-spinner").remove();
		});
		document.addEventListener("ecommerce.product-filter.completed", () => {
			EcommerceApp.updateLazyLoad();
		});
	});
	//#endregion
})();

//# sourceMappingURL=front-ecommerce.js.map