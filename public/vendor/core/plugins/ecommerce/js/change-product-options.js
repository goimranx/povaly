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
	//#region platform/plugins/ecommerce/resources/js/change-product-options.js
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
				_self.xhr = $.ajax({
					url: $productAttributes.data("target"),
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
			$body.on("click", ".product-attributes .visual-swatch label, .product-attributes .text-swatch label", (e) => {
				e.preventDefault();
				let $this = $(e.currentTarget);
				let $radio = $this.find("input[type=radio]");
				if ($radio.is(":checked")) return;
				$radio.prop("checked", true);
				if ($this.closest(".visual-swatch").find("input[type=radio]:checked").length < 1) $radio.prop("checked", true);
				$radio.trigger("change");
			});
			$body.off("change", ".product-attributes input, .product-attributes select").on("change", ".product-attributes input, .product-attributes select", (event) => {
				const $parent = $(event.currentTarget).closest(".product-attributes");
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

//# sourceMappingURL=change-product-options.js.map