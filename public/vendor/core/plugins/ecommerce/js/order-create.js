(function(vue, _vendor_core_plugins_ecommerce_images_next_create_custom_line_item_svg, _vendor_core_plugins_ecommerce_images_next_create_customer_svg) {
	//#region \0rolldown/runtime.js
	var __create = Object.create;
	var __defProp = Object.defineProperty;
	var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
	var __getOwnPropNames = Object.getOwnPropertyNames;
	var __getProtoOf = Object.getPrototypeOf;
	var __hasOwnProp = Object.prototype.hasOwnProperty;
	var __copyProps = (to, from, except, desc) => {
		if (from && typeof from === "object" || typeof from === "function") for (var keys = __getOwnPropNames(from), i = 0, n = keys.length, key; i < n; i++) {
			key = keys[i];
			if (!__hasOwnProp.call(to, key) && key !== except) __defProp(to, key, {
				get: ((k) => from[k]).bind(null, key),
				enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable
			});
		}
		return to;
	};
	var __toESM = (mod, isNodeMode, target) => (target = mod != null ? __create(__getProtoOf(mod)) : {}, __copyProps(isNodeMode || !mod || !mod.__esModule || !__hasOwnProp.call(mod, "default") ? __defProp(target, "default", {
		value: mod,
		enumerable: true
	}) : target, mod));
	//#endregion
	_vendor_core_plugins_ecommerce_images_next_create_custom_line_item_svg = __toESM(_vendor_core_plugins_ecommerce_images_next_create_custom_line_item_svg);
	_vendor_core_plugins_ecommerce_images_next_create_customer_svg = __toESM(_vendor_core_plugins_ecommerce_images_next_create_customer_svg);
	//#region \0plugin-vue:export-helper
	var _plugin_vue_export_helper_default = (sfc, props) => {
		const target = sfc.__vccOpts || sfc;
		for (const [key, val] of props) target[key] = val;
		return target;
	};
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/partials/ProductAvailableComponent.vue
	var _sfc_main$6 = { props: { item: {
		type: Object,
		default: () => {},
		required: true
	} } };
	var _hoisted_1$6 = {
		key: 0,
		class: "text-danger"
	};
	var _hoisted_2$6 = { key: 0 };
	var _hoisted_3$6 = {
		key: 1,
		class: "text-warning"
	};
	var _hoisted_4$6 = { class: "text-info ps-1" };
	function _sfc_render$6(_ctx, _cache, $props, $setup, $data, $options) {
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", null, [$props.item.is_out_of_stock ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_1$6, [(0, vue.createElementVNode)("small", null, "\xA0(" + (0, vue.toDisplayString)(_ctx.__("order.out_of_stock")) + ")", 1)])) : $props.item.with_storehouse_management ? ((0, vue.openBlock)(), (0, vue.createElementBlock)(vue.Fragment, { key: 1 }, [$props.item.quantity > 0 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("small", _hoisted_2$6, "\xA0(" + (0, vue.toDisplayString)($props.item.quantity) + " " + (0, vue.toDisplayString)(_ctx.__("order.products_available")) + ")", 1)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("small", _hoisted_3$6, "\xA0(" + (0, vue.toDisplayString)($props.item.quantity) + " " + (0, vue.toDisplayString)(_ctx.__("order.products_available")) + ")", 1))], 64)) : (0, vue.createCommentVNode)("v-if", true), (0, vue.createElementVNode)("span", _hoisted_4$6, "(" + (0, vue.toDisplayString)($props.item.formatted_price) + ")", 1)]);
	}
	var ProductAvailableComponent_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main$6, [["render", _sfc_render$6]]);
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/partials/ProductOptionComponent.vue
	var _sfc_main$5 = {
		props: {
			options: {
				type: Array,
				default: [],
				required: true
			},
			product: {
				type: Object,
				default: {},
				required: false
			}
		},
		data: function() {
			return { values: [] };
		},
		methods: { changeInput: function($event, option, value) {
			if (!this.values[option.id]) this.values[option.id] = {};
			this.values[option.id] = $event.target.value;
		} }
	};
	var _hoisted_1$5 = ["for"];
	var _hoisted_2$5 = { key: 0 };
	var _hoisted_3$5 = ["onInput", "id"];
	var _hoisted_4$5 = { value: "" };
	var _hoisted_5$4 = ["value"];
	var _hoisted_6$4 = { key: 1 };
	var _hoisted_7$4 = [
		"name",
		"onInput",
		"value",
		"id"
	];
	var _hoisted_8$4 = ["for"];
	var _hoisted_9$4 = { key: 2 };
	var _hoisted_10$3 = [
		"name",
		"onInput",
		"value",
		"id"
	];
	var _hoisted_11$3 = ["for"];
	var _hoisted_12$3 = { key: 3 };
	var _hoisted_13$3 = [
		"onInput",
		"name",
		"id"
	];
	var _hoisted_14$3 = ["for"];
	function _sfc_render$5(_ctx, _cache, $props, $setup, $data, $options) {
		return (0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)($props.options, (option) => {
			return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", { key: option.id }, [
				(0, vue.createElementVNode)("label", {
					class: (0, vue.normalizeClass)(["form-label", { required: option.required }]),
					for: "form-select-" + $props.product.id + "-" + option.id
				}, (0, vue.toDisplayString)(option.name), 11, _hoisted_1$5),
				option.option_type === "dropdown" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_2$5, [(0, vue.createElementVNode)("select", {
					class: "form-select",
					onInput: ($event) => $options.changeInput($event, option, _ctx.value),
					id: "form-select-" + $props.product.id + "-" + option.id
				}, [(0, vue.createElementVNode)("option", _hoisted_4$5, (0, vue.toDisplayString)(_ctx.__("order.select_one")), 1), ((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(option.values, (value) => {
					return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
						key: value.option_value,
						value: value.option_value
					}, (0, vue.toDisplayString)(value.title), 9, _hoisted_5$4);
				}), 128))], 40, _hoisted_3$5)])) : (0, vue.createCommentVNode)("v-if", true),
				option.option_type === "checkbox" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_6$4, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(option.values, (value) => {
					return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", {
						class: "form-check",
						key: value.id
					}, [(0, vue.createElementVNode)("input", {
						class: "form-check-input",
						type: "checkbox",
						name: "option-" + option.id,
						onInput: ($event) => $options.changeInput($event, option, value),
						value: value.option_value,
						id: "form-check-" + $props.product.id + "-" + value.id
					}, null, 40, _hoisted_7$4), (0, vue.createElementVNode)("label", {
						class: "form-check-label",
						for: "form-check-" + $props.product.id + "-" + value.id
					}, (0, vue.toDisplayString)(value.title), 9, _hoisted_8$4)]);
				}), 128))])) : (0, vue.createCommentVNode)("v-if", true),
				option.option_type === "radio" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_9$4, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(option.values, (value) => {
					return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", {
						class: "form-check",
						key: value.id
					}, [(0, vue.createElementVNode)("input", {
						class: "form-check-input",
						type: "radio",
						name: "option-" + option.id,
						onInput: ($event) => $options.changeInput($event, option, value),
						value: value.option_value,
						id: "form-check-" + $props.product.id + "-" + value.id
					}, null, 40, _hoisted_10$3), (0, vue.createElementVNode)("label", {
						class: "form-check-label",
						for: "form-check-" + $props.product.id + "-" + value.id
					}, (0, vue.toDisplayString)(value.title), 9, _hoisted_11$3)]);
				}), 128))])) : (0, vue.createCommentVNode)("v-if", true),
				option.option_type === "field" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_12$3, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(option.values, (value) => {
					return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", {
						class: "form-floating mb-3",
						key: value.id
					}, [(0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control",
						onInput: ($event) => $options.changeInput($event, option, value),
						name: "option-" + option.id,
						id: "form-input-" + $props.product.id + "-" + value.id,
						placeholder: "..."
					}, null, 40, _hoisted_13$3), (0, vue.createElementVNode)("label", { for: "form-input-" + $props.product.id + "-" + value.id }, (0, vue.toDisplayString)(value.title || _ctx.__("order.enter_free_text")), 9, _hoisted_14$3)]);
				}), 128))])) : (0, vue.createCommentVNode)("v-if", true)
			]);
		}), 128);
	}
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/partials/ProductActionComponent.vue
	var _sfc_main$4 = {
		props: { product: {
			type: Object,
			default: {},
			required: false
		} },
		components: {
			ProductAvailable: ProductAvailableComponent_default,
			ProductOption: /* @__PURE__ */ _plugin_vue_export_helper_default(_sfc_main$5, [["render", _sfc_render$5]])
		}
	};
	var _hoisted_1$4 = { class: "row align-items-center gap-2" };
	var _hoisted_2$4 = {
		key: 0,
		class: "text-success"
	};
	var _hoisted_3$4 = { key: 1 };
	var _hoisted_4$4 = {
		key: 0,
		class: "w-100 w-sm-auto col-auto"
	};
	function _sfc_render$4(_ctx, _cache, $props, $setup, $data, $options) {
		const _component_ProductAvailable = (0, vue.resolveComponent)("ProductAvailable");
		const _component_ProductOption = (0, vue.resolveComponent)("ProductOption");
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_1$4, [
			(0, vue.createElementVNode)("div", { class: (0, vue.normalizeClass)(["col d-flex align-content-center", { "overflow-auto": $props.product.variation_attributes }]) }, [$props.product.variation_attributes ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", _hoisted_2$4, (0, vue.toDisplayString)($props.product.variation_attributes), 1)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", _hoisted_3$4, (0, vue.toDisplayString)($props.product.name), 1)), $props.product.is_variation || !$props.product.variations.length ? ((0, vue.openBlock)(), (0, vue.createBlock)(_component_ProductAvailable, {
				key: 2,
				item: $props.product
			}, null, 8, ["item"])) : (0, vue.createCommentVNode)("v-if", true)], 2),
			(0, vue.withDirectives)((0, vue.createVNode)(_component_ProductOption, {
				ref: "product_options_" + $props.product.id,
				product: $props.product,
				options: $props.product.product_options
			}, null, 8, ["product", "options"]), [[vue.vShow, !$props.product.is_variation]]),
			($props.product.is_variation || !$props.product.variations.length) && !$props.product.is_out_of_stock ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_4$4, [(0, vue.createElementVNode)("button", {
				class: "btn btn-outline-primary btn-sm",
				type: "button",
				onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("select-product", $props.product, _ctx.$refs["product_options_" + $props.product.id] || []))
			}, [_cache[1] || (_cache[1] = (0, vue.createElementVNode)("i", { class: "icon-sm ti ti-plus" }, null, -1)), (0, vue.createTextVNode)(" " + (0, vue.toDisplayString)(_ctx.__("order.add")), 1)])])) : (0, vue.createCommentVNode)("v-if", true)
		]);
	}
	var ProductActionComponent_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main$4, [["render", _sfc_render$4]]);
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/partials/OrderCustomerAddressComponent.vue
	var _sfc_main$3 = {
		props: {
			customer: {
				type: Object,
				default: {}
			},
			address: {
				type: Object,
				default: {}
			},
			zip_code_enabled: {
				type: Number,
				default: 0
			},
			use_location_data: {
				type: Number,
				default: 0
			}
		},
		data: function() {
			return {
				countries: [],
				states: [],
				cities: []
			};
		},
		methods: {
			shownEditAddress: function($event) {
				this.loadCountries($event);
				if (this.address.country) this.loadStates($event, this.address.country);
				if (this.address.state) this.loadCities($event, this.address.state);
			},
			loadCountries: function() {
				let context = this;
				if (_.isEmpty(context.countries)) axios.get(route("ajax.countries.list")).then((res) => {
					context.countries = res.data.data;
				}).catch((res) => {
					Botble.handleError(res.response.data);
				});
			},
			loadStates: function($event, country_id) {
				if (!this.use_location_data) return false;
				let context = this;
				axios.get(route("ajax.states-by-country", { country_id: country_id || $event.target.value })).then((res) => {
					context.states = res.data.data;
				}).catch((res) => {
					Botble.handleError(res.response.data);
				});
			},
			loadCities: function($event, state_id) {
				if (!this.use_location_data) return false;
				let context = this;
				axios.get(route("ajax.cities-by-state", { state_id: state_id || $event.target.value })).then((res) => {
					context.cities = res.data.data;
				}).catch((res) => {
					Botble.handleError(res.response.data);
				});
			}
		},
		watch: { address: function($event) {
			if (this.address.country) this.loadStates($event, this.address.country);
			if (this.address.state) this.loadCities($event, this.address.state);
		} }
	};
	var _hoisted_1$3 = { class: "row" };
	var _hoisted_2$3 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_3$3 = { class: "form-label required" };
	var _hoisted_4$3 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_5$3 = { class: "form-label required" };
	var _hoisted_6$3 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_7$3 = { class: "form-label required" };
	var _hoisted_8$3 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_9$3 = { class: "form-label" };
	var _hoisted_10$2 = { class: "col-12 mb-3 position-relative" };
	var _hoisted_11$2 = { class: "form-label required" };
	var _hoisted_12$2 = ["value"];
	var _hoisted_13$2 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_14$2 = { class: "form-label required" };
	var _hoisted_15$2 = ["value"];
	var _hoisted_16$2 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_17$2 = { class: "form-label required" };
	var _hoisted_18$2 = ["value"];
	var _hoisted_19$2 = {
		key: 0,
		class: "col-md-6 mb-3 position-relative"
	};
	var _hoisted_20$2 = { class: "form-label" };
	var _hoisted_21$2 = { class: "mb-3 position-relative" };
	var _hoisted_22$1 = { class: "form-label" };
	var _hoisted_23$1 = { class: "row" };
	var _hoisted_24$1 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_25$1 = { class: "form-label required" };
	var _hoisted_26$1 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_27$1 = { class: "form-label required" };
	var _hoisted_28$1 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_29$1 = { class: "form-label required" };
	var _hoisted_30$1 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_31$1 = { class: "form-label" };
	var _hoisted_32$1 = { class: "col-12 mb-3 position-relative" };
	var _hoisted_33$1 = { class: "form-label required" };
	var _hoisted_34$1 = ["selected", "value"];
	var _hoisted_35$1 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_36$1 = { class: "form-label required" };
	var _hoisted_37$1 = ["selected", "value"];
	var _hoisted_38$1 = { class: "col-md-6 mb-3 position-relative" };
	var _hoisted_39$1 = { class: "form-label" };
	var _hoisted_40$1 = ["value"];
	var _hoisted_41$1 = {
		key: 0,
		class: "col-md-6 mb-3 position-relative"
	};
	var _hoisted_42$1 = { class: "form-label" };
	function _sfc_render$3(_ctx, _cache, $props, $setup, $data, $options) {
		const _component_ec_modal = (0, vue.resolveComponent)("ec-modal");
		return (0, vue.openBlock)(), (0, vue.createElementBlock)(vue.Fragment, null, [
			(0, vue.createVNode)(_component_ec_modal, {
				id: "add-customer",
				title: _ctx.__("order.create_new_customer"),
				"ok-title": _ctx.__("order.save"),
				"cancel-title": _ctx.__("order.cancel"),
				onShown: _cache[12] || (_cache[12] = ($event) => $options.loadCountries($event)),
				onOk: _cache[13] || (_cache[13] = ($event) => _ctx.$emit("create-new-customer", $event))
			}, {
				default: (0, vue.withCtx)(() => [(0, vue.createElementVNode)("div", _hoisted_1$3, [
					(0, vue.createElementVNode)("div", _hoisted_2$3, [(0, vue.createElementVNode)("label", _hoisted_3$3, (0, vue.toDisplayString)(_ctx.__("order.name")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control",
						"onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => $props.address.name = $event)
					}, null, 512), [[vue.vModelText, $props.address.name]])]),
					(0, vue.createElementVNode)("div", _hoisted_4$3, [(0, vue.createElementVNode)("label", _hoisted_5$3, (0, vue.toDisplayString)(_ctx.__("order.phone")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control",
						"onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => $props.address.phone = $event)
					}, null, 512), [[vue.vModelText, $props.address.phone]])]),
					(0, vue.createElementVNode)("div", _hoisted_6$3, [(0, vue.createElementVNode)("label", _hoisted_7$3, (0, vue.toDisplayString)(_ctx.__("order.address")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control",
						"onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => $props.address.address = $event)
					}, null, 512), [[vue.vModelText, $props.address.address]])]),
					(0, vue.createElementVNode)("div", _hoisted_8$3, [(0, vue.createElementVNode)("label", _hoisted_9$3, (0, vue.toDisplayString)(_ctx.__("order.email")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "email",
						class: "form-control",
						"onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => $props.address.email = $event)
					}, null, 512), [[vue.vModelText, $props.address.email]])]),
					(0, vue.createElementVNode)("div", _hoisted_10$2, [(0, vue.createElementVNode)("label", _hoisted_11$2, (0, vue.toDisplayString)(_ctx.__("order.country")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("select", {
						class: "form-select",
						"onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => $props.address.country = $event),
						onChange: _cache[5] || (_cache[5] = ($event) => $options.loadStates($event))
					}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.countries, (countryName, countryCode) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
							value: countryCode,
							key: countryCode
						}, (0, vue.toDisplayString)(countryName), 9, _hoisted_12$2);
					}), 128))], 544), [[vue.vModelSelect, $props.address.country]])]),
					(0, vue.createElementVNode)("div", _hoisted_13$2, [(0, vue.createElementVNode)("label", _hoisted_14$2, (0, vue.toDisplayString)(_ctx.__("order.state")), 1), $props.use_location_data ? (0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("select", {
						key: 0,
						"onUpdate:modelValue": _cache[6] || (_cache[6] = ($event) => $props.address.state = $event),
						onChange: _cache[7] || (_cache[7] = ($event) => $options.loadCities($event)),
						class: "form-select customer-address-state"
					}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.states, (state) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
							value: state.id,
							key: state.id
						}, (0, vue.toDisplayString)(state.name), 9, _hoisted_15$2);
					}), 128))], 544)), [[vue.vModelSelect, $props.address.state]]) : (0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("input", {
						key: 1,
						type: "text",
						class: "form-control customer-address-state",
						"onUpdate:modelValue": _cache[8] || (_cache[8] = ($event) => $props.address.state = $event)
					}, null, 512)), [[vue.vModelText, $props.address.state]])]),
					(0, vue.createElementVNode)("div", _hoisted_16$2, [(0, vue.createElementVNode)("label", _hoisted_17$2, (0, vue.toDisplayString)(_ctx.__("order.city")), 1), $props.use_location_data ? (0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("select", {
						key: 0,
						"onUpdate:modelValue": _cache[9] || (_cache[9] = ($event) => $props.address.city = $event),
						class: "form-select customer-address-city"
					}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.cities, (city) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
							value: city.id,
							key: city.id
						}, (0, vue.toDisplayString)(city.name), 9, _hoisted_18$2);
					}), 128))], 512)), [[vue.vModelSelect, $props.address.city]]) : (0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("input", {
						key: 1,
						type: "text",
						class: "form-control customer-address-city",
						"onUpdate:modelValue": _cache[10] || (_cache[10] = ($event) => $props.address.city = $event)
					}, null, 512)), [[vue.vModelText, $props.address.city]])]),
					$props.zip_code_enabled ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_19$2, [(0, vue.createElementVNode)("label", _hoisted_20$2, (0, vue.toDisplayString)(_ctx.__("order.zip_code")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control",
						"onUpdate:modelValue": _cache[11] || (_cache[11] = ($event) => $props.address.zip_code = $event)
					}, null, 512), [[vue.vModelText, $props.address.zip_code]])])) : (0, vue.createCommentVNode)("v-if", true)
				])]),
				_: 1
			}, 8, [
				"title",
				"ok-title",
				"cancel-title"
			]),
			(0, vue.createVNode)(_component_ec_modal, {
				id: "edit-email",
				title: _ctx.__("order.update_email"),
				"ok-title": _ctx.__("order.update"),
				"cancel-title": _ctx.__("order.close"),
				onOk: _cache[15] || (_cache[15] = ($event) => _ctx.$emit("update-customer-email", $event))
			}, {
				default: (0, vue.withCtx)(() => [(0, vue.createElementVNode)("div", _hoisted_21$2, [(0, vue.createElementVNode)("label", _hoisted_22$1, (0, vue.toDisplayString)(_ctx.__("order.email")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					class: "form-control",
					"onUpdate:modelValue": _cache[14] || (_cache[14] = ($event) => $props.customer.email = $event)
				}, null, 512), [[vue.vModelText, $props.customer.email]])])]),
				_: 1
			}, 8, [
				"title",
				"ok-title",
				"cancel-title"
			]),
			(0, vue.createVNode)(_component_ec_modal, {
				id: "edit-address",
				title: _ctx.__("order.update_address"),
				"ok-title": _ctx.__("order.save"),
				"cancel-title": _ctx.__("order.cancel"),
				onShown: $options.shownEditAddress,
				onOk: _cache[28] || (_cache[28] = ($event) => _ctx.$emit("update-order-address", $event))
			}, {
				default: (0, vue.withCtx)(() => [(0, vue.createElementVNode)("div", _hoisted_23$1, [
					(0, vue.createElementVNode)("div", _hoisted_24$1, [(0, vue.createElementVNode)("label", _hoisted_25$1, (0, vue.toDisplayString)(_ctx.__("order.name")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control customer-address-name",
						"onUpdate:modelValue": _cache[16] || (_cache[16] = ($event) => $props.address.name = $event)
					}, null, 512), [[vue.vModelText, $props.address.name]])]),
					(0, vue.createElementVNode)("div", _hoisted_26$1, [(0, vue.createElementVNode)("label", _hoisted_27$1, (0, vue.toDisplayString)(_ctx.__("order.phone")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control customer-address-phone",
						"onUpdate:modelValue": _cache[17] || (_cache[17] = ($event) => $props.address.phone = $event)
					}, null, 512), [[vue.vModelText, $props.address.phone]])]),
					(0, vue.createElementVNode)("div", _hoisted_28$1, [(0, vue.createElementVNode)("label", _hoisted_29$1, (0, vue.toDisplayString)(_ctx.__("order.address")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control customer-address-address",
						"onUpdate:modelValue": _cache[18] || (_cache[18] = ($event) => $props.address.address = $event)
					}, null, 512), [[vue.vModelText, $props.address.address]])]),
					(0, vue.createElementVNode)("div", _hoisted_30$1, [(0, vue.createElementVNode)("label", _hoisted_31$1, (0, vue.toDisplayString)(_ctx.__("order.email")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control customer-address-email",
						"onUpdate:modelValue": _cache[19] || (_cache[19] = ($event) => $props.address.email = $event)
					}, null, 512), [[vue.vModelText, $props.address.email]])]),
					(0, vue.createElementVNode)("div", _hoisted_32$1, [(0, vue.createElementVNode)("label", _hoisted_33$1, (0, vue.toDisplayString)(_ctx.__("order.country")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("select", {
						class: "form-select customer-address-country",
						"onUpdate:modelValue": _cache[20] || (_cache[20] = ($event) => $props.address.country = $event),
						onChange: _cache[21] || (_cache[21] = ($event) => $options.loadStates($event))
					}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.countries, (countryName, countryCode) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
							selected: $props.address.country === countryCode,
							value: countryCode,
							key: countryCode
						}, (0, vue.toDisplayString)(countryName), 9, _hoisted_34$1);
					}), 128))], 544), [[vue.vModelSelect, $props.address.country]])]),
					(0, vue.createElementVNode)("div", _hoisted_35$1, [(0, vue.createElementVNode)("label", _hoisted_36$1, (0, vue.toDisplayString)(_ctx.__("order.state")), 1), $props.use_location_data ? (0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("select", {
						key: 0,
						class: "form-select customer-address-state",
						"onUpdate:modelValue": _cache[22] || (_cache[22] = ($event) => $props.address.state = $event),
						onChange: _cache[23] || (_cache[23] = ($event) => $options.loadCities($event))
					}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.states, (state) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
							selected: $props.address.state === state.id,
							value: state.id,
							key: state.id
						}, (0, vue.toDisplayString)(state.name), 9, _hoisted_37$1);
					}), 128))], 544)), [[vue.vModelSelect, $props.address.state]]) : (0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("input", {
						key: 1,
						type: "text",
						class: "form-control customer-address-state",
						"onUpdate:modelValue": _cache[24] || (_cache[24] = ($event) => $props.address.state = $event)
					}, null, 512)), [[vue.vModelText, $props.address.state]])]),
					(0, vue.createElementVNode)("div", _hoisted_38$1, [(0, vue.createElementVNode)("label", _hoisted_39$1, (0, vue.toDisplayString)(_ctx.__("order.city")), 1), $props.use_location_data ? (0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("select", {
						key: 0,
						"onUpdate:modelValue": _cache[25] || (_cache[25] = ($event) => $props.address.city = $event),
						class: "form-select customer-address-city"
					}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.cities, (city) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
							value: city.id,
							key: city.id
						}, (0, vue.toDisplayString)(city.name), 9, _hoisted_40$1);
					}), 128))], 512)), [[vue.vModelSelect, $props.address.city]]) : (0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("input", {
						key: 1,
						type: "text",
						class: "form-control customer-address-city",
						"onUpdate:modelValue": _cache[26] || (_cache[26] = ($event) => $props.address.city = $event)
					}, null, 512)), [[vue.vModelText, $props.address.city]])]),
					$props.zip_code_enabled ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_41$1, [(0, vue.createElementVNode)("label", _hoisted_42$1, (0, vue.toDisplayString)(_ctx.__("order.zip_code")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control customer-address-zip-code",
						"onUpdate:modelValue": _cache[27] || (_cache[27] = ($event) => $props.address.zip_code = $event)
					}, null, 512), [[vue.vModelText, $props.address.zip_code]])])) : (0, vue.createCommentVNode)("v-if", true)
				])]),
				_: 1
			}, 8, [
				"title",
				"ok-title",
				"cancel-title",
				"onShown"
			])
		], 64);
	}
	var OrderCustomerAddressComponent_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main$3, [["render", _sfc_render$3]]);
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/partials/AddProductModalComponent.vue
	var _sfc_main$2 = {
		props: { store: {
			type: Object,
			default: () => ({})
		} },
		data: function() {
			return { product: {} };
		},
		methods: { resetProductData: function() {
			this.product = {
				name: null,
				price: 0,
				sku: null,
				status: "published",
				with_storehouse_management: false,
				allow_checkout_when_out_of_stock: false,
				quantity: 0,
				tax_price: 0
			};
		} },
		mounted: function() {
			this.resetProductData();
			$event.on(`ec-modal:open`, (id) => {
				if (id === "add-product-item") this.resetProductData();
			});
		}
	};
	var _hoisted_1$2 = { class: "mb-3 position-relative" };
	var _hoisted_2$2 = { class: "form-label" };
	var _hoisted_3$2 = { class: "row" };
	var _hoisted_4$2 = { class: "col-6 mb-3 position-relative" };
	var _hoisted_5$2 = { class: "form-label" };
	var _hoisted_6$2 = { class: "col-6 mb-3 position-relative" };
	var _hoisted_7$2 = { class: "form-label" };
	var _hoisted_8$2 = { class: "mb-3 position-relative" };
	var _hoisted_9$2 = { class: "form-label" };
	var _hoisted_10$1 = { value: "published" };
	var _hoisted_11$1 = { value: "draft" };
	var _hoisted_12$1 = { value: "pending" };
	var _hoisted_13$1 = { class: "form-check" };
	var _hoisted_14$1 = { class: "form-check-label" };
	var _hoisted_15$1 = { class: "mb-3 position-relative" };
	var _hoisted_16$1 = { class: "form-label" };
	var _hoisted_17$1 = { class: "form-check" };
	var _hoisted_18$1 = { class: "form-check-label" };
	var _hoisted_19$1 = {
		key: 1,
		class: "position-relative"
	};
	var _hoisted_20$1 = { class: "form-check-label" };
	var _hoisted_21$1 = { class: "text-primary" };
	function _sfc_render$2(_ctx, _cache, $props, $setup, $data, $options) {
		const _component_ec_modal = (0, vue.resolveComponent)("ec-modal");
		return (0, vue.openBlock)(), (0, vue.createBlock)(_component_ec_modal, {
			id: "add-product-item",
			title: _ctx.__("order.add_product"),
			"ok-title": _ctx.__("order.save"),
			"cancel-title": _ctx.__("order.cancel"),
			onShown: _cache[7] || (_cache[7] = ($event) => $options.resetProductData()),
			onOk: _cache[8] || (_cache[8] = ($event) => _ctx.$emit("create-product", $event, _ctx.product))
		}, {
			default: (0, vue.withCtx)(() => [
				(0, vue.createElementVNode)("div", _hoisted_1$2, [(0, vue.createElementVNode)("label", _hoisted_2$2, (0, vue.toDisplayString)(_ctx.__("order.name")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					type: "text",
					class: "form-control",
					"onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => _ctx.product.name = $event)
				}, null, 512), [[vue.vModelText, _ctx.product.name]])]),
				(0, vue.createElementVNode)("div", _hoisted_3$2, [(0, vue.createElementVNode)("div", _hoisted_4$2, [(0, vue.createElementVNode)("label", _hoisted_5$2, (0, vue.toDisplayString)(_ctx.__("order.price")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					type: "text",
					class: "form-control",
					"onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => _ctx.product.price = $event)
				}, null, 512), [[vue.vModelText, _ctx.product.price]])]), (0, vue.createElementVNode)("div", _hoisted_6$2, [(0, vue.createElementVNode)("label", _hoisted_7$2, (0, vue.toDisplayString)(_ctx.__("order.sku_optional")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					type: "text",
					class: "form-control",
					"onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => _ctx.product.sku = $event)
				}, null, 512), [[vue.vModelText, _ctx.product.sku]])])]),
				(0, vue.createElementVNode)("div", _hoisted_8$2, [(0, vue.createElementVNode)("label", _hoisted_9$2, (0, vue.toDisplayString)(_ctx.__("order.status")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("select", {
					class: "form-select",
					"onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => _ctx.product.status = $event)
				}, [
					(0, vue.createElementVNode)("option", _hoisted_10$1, (0, vue.toDisplayString)(_ctx.__("order.published")), 1),
					(0, vue.createElementVNode)("option", _hoisted_11$1, (0, vue.toDisplayString)(_ctx.__("order.draft")), 1),
					(0, vue.createElementVNode)("option", _hoisted_12$1, (0, vue.toDisplayString)(_ctx.__("order.pending")), 1)
				], 512), [[vue.vModelSelect, _ctx.product.status]])]),
				(0, vue.createElementVNode)("div", { class: (0, vue.normalizeClass)({
					"position-relative": true,
					"mb-3": _ctx.product.with_storehouse_management || $props.store && $props.store.id
				}) }, [(0, vue.createElementVNode)("label", _hoisted_13$1, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					type: "checkbox",
					class: "form-check-input",
					"onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => _ctx.product.with_storehouse_management = $event),
					value: "1"
				}, null, 512), [[vue.vModelCheckbox, _ctx.product.with_storehouse_management]]), (0, vue.createElementVNode)("span", _hoisted_14$1, (0, vue.toDisplayString)(_ctx.__("order.with_storehouse_management")), 1)])], 2),
				_ctx.product.with_storehouse_management ? ((0, vue.openBlock)(), (0, vue.createElementBlock)(vue.Fragment, { key: 0 }, [(0, vue.createElementVNode)("div", _hoisted_15$1, [(0, vue.createElementVNode)("label", _hoisted_16$1, (0, vue.toDisplayString)(_ctx.__("order.quantity")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					type: "number",
					min: "1",
					class: "form-control",
					"onUpdate:modelValue": _cache[5] || (_cache[5] = ($event) => _ctx.product.quantity = $event)
				}, null, 512), [[vue.vModelText, _ctx.product.quantity]])]), (0, vue.createElementVNode)("div", { class: (0, vue.normalizeClass)({
					"position-relative": true,
					"mb-3": $props.store && $props.store.id
				}) }, [(0, vue.createElementVNode)("label", _hoisted_17$1, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					type: "checkbox",
					class: "form-check-input",
					"onUpdate:modelValue": _cache[6] || (_cache[6] = ($event) => _ctx.product.allow_checkout_when_out_of_stock = $event),
					value: "1"
				}, null, 512), [[vue.vModelCheckbox, _ctx.product.allow_checkout_when_out_of_stock]]), (0, vue.createElementVNode)("span", _hoisted_18$1, (0, vue.toDisplayString)(_ctx.__("order.allow_customer_checkout_when_this_product_out_of_stock")), 1)])], 2)], 64)) : (0, vue.createCommentVNode)("v-if", true),
				$props.store && $props.store.id ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_19$1, [(0, vue.createElementVNode)("label", _hoisted_20$1, [(0, vue.createTextVNode)((0, vue.toDisplayString)(_ctx.__("order.store")) + ": ", 1), (0, vue.createElementVNode)("strong", _hoisted_21$1, (0, vue.toDisplayString)($props.store.name), 1)])])) : (0, vue.createCommentVNode)("v-if", true)
			]),
			_: 1
		}, 8, [
			"title",
			"ok-title",
			"cancel-title"
		]);
	}
	var AddProductModalComponent_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main$2, [["render", _sfc_render$2]]);
	//#endregion
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
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/objectSpread2.js
	function ownKeys(e, r) {
		var t = Object.keys(e);
		if (Object.getOwnPropertySymbols) {
			var o = Object.getOwnPropertySymbols(e);
			r && (o = o.filter(function(r) {
				return Object.getOwnPropertyDescriptor(e, r).enumerable;
			})), t.push.apply(t, o);
		}
		return t;
	}
	function _objectSpread2(e) {
		for (var r = 1; r < arguments.length; r++) {
			var t = null != arguments[r] ? arguments[r] : {};
			r % 2 ? ownKeys(Object(t), !0).forEach(function(r) {
				_defineProperty(e, r, t[r]);
			}) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function(r) {
				Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r));
			});
		}
		return e;
	}
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/CreateOrderComponent.vue
	var _sfc_main$1 = {
		props: {
			products: {
				type: Array,
				default: () => []
			},
			product_ids: {
				type: Array,
				default: () => []
			},
			customer_id: {
				type: Number,
				default: () => null
			},
			customer: {
				type: Object,
				default: () => ({ email: "guest@example.com" })
			},
			customer_addresses: {
				type: Array,
				default: () => []
			},
			customer_address: {
				type: Object,
				default: () => ({
					name: null,
					email: null,
					address: null,
					phone: null,
					country: null,
					state: null,
					city: null,
					zip_code: null
				})
			},
			customer_order_numbers: {
				type: Number,
				default: () => 0
			},
			sub_amount: {
				type: Number,
				default: () => 0
			},
			sub_amount_label: {
				type: String,
				default: () => ""
			},
			tax_amount: {
				type: Number,
				default: () => 0
			},
			tax_amount_label: {
				type: String,
				default: () => ""
			},
			total_amount: {
				type: Number,
				default: () => 0
			},
			total_amount_label: {
				type: String,
				default: () => ""
			},
			coupon_code: {
				type: String,
				default: () => ""
			},
			promotion_amount: {
				type: Number,
				default: () => 0
			},
			promotion_amount_label: {
				type: String,
				default: () => ""
			},
			discount_amount: {
				type: Number,
				default: () => 0
			},
			discount_amount_label: {
				type: String,
				default: () => ""
			},
			discount_description: {
				type: String,
				default: () => null
			},
			shipping_amount: {
				type: Number,
				default: () => 0
			},
			shipping_amount_label: {
				type: String,
				default: () => ""
			},
			shipping_method: {
				type: String,
				default: () => "default"
			},
			shipping_option: {
				type: String,
				default: () => ""
			},
			is_selected_shipping: {
				type: Boolean,
				default: () => false
			},
			shipping_method_name: {
				type: String,
				default: function() {
					return "order.free_shipping";
				}
			},
			payment_method: {
				type: String,
				default: () => "cod"
			},
			currency: {
				type: String,
				default: () => null,
				required: true
			},
			zip_code_enabled: {
				type: Number,
				default: () => 0,
				required: true
			},
			use_location_data: {
				type: Number,
				default: () => 0
			},
			is_tax_enabled: {
				type: Number,
				default: () => true
			},
			paymentMethods: {
				type: Object,
				default: () => ({})
			},
			paymentStatuses: {
				type: Object,
				default: () => ({})
			}
		},
		data: function() {
			return {
				list_products: { data: [] },
				hidden_product_search_panel: true,
				loading: false,
				checking: false,
				note: null,
				customers: { data: [] },
				hidden_customer_search_panel: true,
				customer_keyword: null,
				shipping_type: "free-shipping",
				shipping_methods: {},
				discount_type_unit: this.currency,
				discount_type: "amount",
				child_discount_description: this.discount_description,
				has_invalid_coupon: false,
				has_applied_discount: this.discount_amount > 0,
				discount_custom_value: 0,
				child_coupon_code: this.coupon_code,
				child_customer: this.customer,
				child_customer_id: this.customer_id,
				child_customer_order_numbers: this.customer_order_numbers,
				child_customer_addresses: this.customer_addresses,
				child_customer_address: this.customer_address,
				child_products: this.products,
				child_product_ids: this.product_ids,
				child_sub_amount: this.sub_amount,
				child_sub_amount_label: this.sub_amount_label,
				child_tax_amount: this.tax_amount,
				child_tax_amount_label: this.tax_amount_label,
				child_total_amount: this.total_amount,
				child_total_amount_label: this.total_amount_label,
				child_promotion_amount: this.promotion_amount,
				child_promotion_amount_label: this.promotion_amount_label,
				child_discount_amount: this.discount_amount,
				child_discount_amount_label: this.discount_amount_label,
				child_shipping_amount: this.shipping_amount,
				child_shipping_amount_label: this.shipping_amount_label,
				child_shipping_method: this.shipping_method,
				child_shipping_option: this.shipping_option,
				child_shipping_method_name: this.shipping_method_name,
				child_is_selected_shipping: this.is_selected_shipping,
				child_payment_method: this.payment_method,
				child_transaction_id: null,
				child_payment_status: "pending",
				productSearchRequest: null,
				timeoutProductRequest: null,
				customerSearchRequest: null,
				checkDataOrderRequest: null,
				store: {
					id: 0,
					name: null
				},
				is_available_shipping: false
			};
		},
		components: {
			ProductAction: ProductActionComponent_default,
			OrderCustomerAddress: OrderCustomerAddressComponent_default,
			AddProductModal: AddProductModalComponent_default
		},
		mounted: function() {
			let context = this;
			$(document).on("click", "body", (e) => {
				let container = $(".box-search-advance");
				if (!container.is(e.target) && container.has(e.target).length === 0) {
					context.hidden_customer_search_panel = true;
					context.hidden_product_search_panel = true;
				}
			});
			if (context.product_ids) context.checkDataBeforeCreateOrder();
		},
		methods: {
			loadListCustomersForSearch: function(page = 1, force = false) {
				let context = this;
				context.hidden_customer_search_panel = false;
				$(".textbox-advancesearch.customer").closest(".box-search-advance.customer").find(".panel").addClass("active");
				if (_.isEmpty(context.customers.data) || force) {
					context.loading = true;
					if (context.customerSearchRequest) context.customerSearchRequest.abort();
					context.customerSearchRequest = new AbortController();
					axios.get(route("customers.get-list-customers-for-search", {
						keyword: context.customer_keyword,
						page
					}), { signal: context.customerSearchRequest.signal }).then((res) => {
						context.customers = res.data.data;
						context.loading = false;
					}).catch((error) => {
						if (!axios.isCancel(error)) {
							context.loading = false;
							Botble.handleError(error.response.data);
						}
					});
				}
			},
			handleSearchCustomer: function(value) {
				if (value !== this.customer_keyword) {
					let context = this;
					this.customer_keyword = value;
					setTimeout(() => {
						context.loadListCustomersForSearch(1, true);
					}, 500);
				}
			},
			loadListProductsAndVariations: function(page = 1, force = false, show_panel = true) {
				let context = this;
				if (show_panel) {
					context.hidden_product_search_panel = false;
					$(".textbox-advancesearch.product").closest(".box-search-advance.product").find(".panel").addClass("active");
				} else context.hidden_product_search_panel = true;
				if (_.isEmpty(context.list_products.data) || force) {
					context.loading = true;
					if (context.productSearchRequest) context.productSearchRequest.abort();
					context.productSearchRequest = new AbortController();
					axios.get(route("products.get-all-products-and-variations", {
						keyword: context.product_keyword,
						page,
						product_ids: context.child_product_ids
					}), { signal: context.productSearchRequest.signal }).then((res) => {
						context.list_products = res.data.data;
						context.loading = false;
					}).catch((error) => {
						if (!axios.isCancel(error)) {
							Botble.handleError(error.response.data);
							context.loading = false;
						}
					});
				}
			},
			handleSearchProduct: function(value) {
				if (value !== this.product_keyword) {
					let context = this;
					context.product_keyword = value;
					if (context.timeoutProductRequest) clearTimeout(context.timeoutProductRequest);
					context.timeoutProductRequest = setTimeout(() => {
						context.loadListProductsAndVariations(1, true);
					}, 1e3);
				}
			},
			selectProductVariant: function(product, refOptions) {
				let context = this;
				if (_.isEmpty(product) && product.is_out_of_stock) {
					Botble.showError(context.__("order.cant_select_out_of_stock_product"));
					return false;
				}
				const requiredOptions = product.product_options.filter((item) => item.required);
				if (product.is_variation || !product.variations.length) refOptions = context.$refs["product_actions_" + product.original_product_id][0].$refs["product_options_" + product.original_product_id];
				let productOptions = refOptions.values;
				if (requiredOptions.length) {
					let errorMessage = [];
					requiredOptions.forEach((item) => {
						if (!productOptions[item.id]) errorMessage.push(context.__("order.please_choose_product_option") + ": " + item.name);
					});
					if (errorMessage && errorMessage.length) {
						errorMessage.forEach((message) => {
							Botble.showError(message);
						});
						return;
					}
				}
				let options = [];
				product.product_options.map((item) => {
					if (productOptions[item.id]) options[item.id] = {
						option_type: item.option_type,
						values: productOptions[item.id]
					};
				});
				context.child_products.push({
					id: product.id,
					quantity: 1,
					options
				});
				context.checkDataBeforeCreateOrder();
				context.hidden_product_search_panel = true;
			},
			selectCustomer: function(customer) {
				this.child_customer = customer;
				this.child_customer_id = customer.id;
				this.loadCustomerAddress(this.child_customer_id);
				this.getOrderNumbers();
			},
			checkDataBeforeCreateOrder: function(data = {}, onSuccess = null, onError = null) {
				let context = this;
				let formData = _objectSpread2(_objectSpread2({}, context.getOrderFormData()), data);
				context.checking = true;
				if (context.checkDataOrderRequest) context.checkDataOrderRequest.abort();
				context.checkDataOrderRequest = new AbortController();
				axios.post(route("orders.check-data-before-create-order"), formData, { signal: context.checkDataOrderRequest.signal }).then((res) => {
					let data = res.data.data;
					if (data.update_context_data) {
						context.child_products = data.products;
						context.child_product_ids = _.map(data.products, "id");
						context.child_sub_amount = data.sub_amount;
						context.child_sub_amount_label = data.sub_amount_label;
						context.child_tax_amount = data.tax_amount;
						context.child_tax_amount_label = data.tax_amount_label;
						context.child_promotion_amount = data.promotion_amount;
						context.child_promotion_amount_label = data.promotion_amount_label;
						context.child_discount_amount = data.discount_amount;
						context.child_discount_amount_label = data.discount_amount_label;
						context.child_shipping_amount = data.shipping_amount;
						context.child_shipping_amount_label = data.shipping_amount_label;
						context.child_total_amount = data.total_amount;
						context.child_total_amount_label = data.total_amount_label;
						context.shipping_methods = data.shipping_methods;
						context.child_shipping_method_name = data.shipping_method_name;
						context.child_shipping_method = data.shipping_method;
						context.child_shipping_option = data.shipping_option;
						context.is_available_shipping = data.is_available_shipping;
						context.store = data.store && data.store.id ? data.store : {
							id: 0,
							name: null
						};
					}
					if (res.data.error) {
						Botble.showError(res.data.message);
						if (onError) onError();
					} else if (onSuccess) onSuccess();
					context.checking = false;
				}).catch((error) => {
					if (!axios.isCancel(error)) {
						context.checking = false;
						Botble.handleError(error.response.data);
					}
				});
			},
			getOrderFormData: function() {
				let products = [];
				_.each(this.child_products, function(item) {
					products.push({
						id: item.id,
						quantity: item.select_qty,
						options: item.options
					});
				});
				return {
					products,
					payment_method: this.child_payment_method,
					payment_status: this.child_payment_status,
					shipping_method: this.child_shipping_method,
					shipping_option: this.child_shipping_option,
					shipping_amount: this.child_shipping_amount,
					discount_amount: this.child_discount_amount,
					discount_description: this.child_discount_description,
					coupon_code: this.child_coupon_code,
					customer_id: this.child_customer_id,
					note: this.note,
					sub_amount: this.child_sub_amount,
					tax_amount: this.child_tax_amount,
					amount: this.child_total_amount,
					customer_address: this.child_customer_address,
					discount_type: this.discount_type,
					discount_custom_value: this.discount_custom_value,
					shipping_type: this.shipping_type,
					transaction_id: this.child_transaction_id
				};
			},
			removeCustomer: function() {
				this.child_customer = this.customer;
				this.child_customer_id = null;
				this.child_customer_addresses = [];
				this.child_customer_address = {
					name: null,
					email: null,
					address: null,
					phone: null,
					country: null,
					state: null,
					city: null,
					zip_code: null,
					full_address: null
				};
				this.child_customer_order_numbers = 0;
				this.checkDataBeforeCreateOrder();
			},
			handleRemoveVariant: function(event, variant, vKey) {
				event.preventDefault();
				this.child_product_ids = this.child_product_ids.filter((item, k) => k !== vKey);
				this.child_products = this.child_products.filter((item, k) => k !== vKey);
				this.checkDataBeforeCreateOrder();
			},
			createOrder: function(event) {
				event.preventDefault();
				$(event.target).addClass("btn-loading");
				axios.post(route("orders.create"), this.getOrderFormData()).then((res) => {
					let data = res.data.data;
					if (res.data.error) Botble.showError(res.data.message);
					else {
						Botble.showSuccess(res.data.message);
						$event.emit("ec-modal:close", "create-order");
						setTimeout(() => {
							window.location.href = route("orders.edit", data.id);
						}, 1e3);
					}
				}).catch((res) => {
					Botble.handleError(res.response.data);
				}).then(() => {
					$(event.target).removeClass("btn-loading");
				});
			},
			createProduct: function(event, product) {
				event.preventDefault();
				$(event.target).addClass("btn-loading");
				let context = this;
				if (context.store && context.store.id) product.store_id = context.store.id;
				axios.post(route("products.create-product-when-creating-order"), product).then((res) => {
					if (res.data.error) Botble.showError(res.data.message);
					else {
						context.product = res.data.data;
						context.list_products = { data: [] };
						let productItem = context.product;
						productItem.select_qty = 1;
						context.child_products.push(productItem);
						context.child_product_ids.push(context.product.id);
						context.hidden_product_search_panel = true;
						Botble.showSuccess(res.data.message);
						$event.emit("ec-modal:close", "add-product-item");
						context.checkDataBeforeCreateOrder();
					}
				}).catch((res) => {
					Botble.handleError(res.response.data);
				}).then(() => {
					$(event.target).removeClass("btn-loading");
				});
			},
			updateCustomerEmail: function(event) {
				event.preventDefault();
				$(event.target).addClass("btn-loading");
				let context = this;
				axios.post(route("customers.update-email", context.child_customer.id), { email: context.child_customer.email }).then(({ data }) => {
					if (data.error) Botble.showError(data.message);
					else {
						Botble.showSuccess(data.message);
						$event.emit("ec-modal:close", "edit-email");
					}
				}).catch(({ response }) => {
					Botble.handleError(response.data);
				}).then(() => {
					$(event.target).removeClass("btn-loading");
				});
			},
			updateOrderAddress: function(event) {
				event.preventDefault();
				if (this.customer) {
					$(event.target).addClass("btn-loading");
					this.checkDataBeforeCreateOrder({}, () => {
						setTimeout(() => {
							$(event.target).removeClass("btn-loading");
							$event.emit("ec-modal:close", "edit-address");
						}, 500);
					}, () => {
						setTimeout(() => {
							$(event.target).removeClass("btn-loading");
						}, 500);
					});
				}
			},
			createNewCustomer: function(event) {
				event.preventDefault();
				let context = this;
				$(event.target).addClass("btn-loading");
				axios.post(route("customers.create-customer-when-creating-order"), {
					customer_id: context.child_customer_id,
					name: context.child_customer_address.name,
					email: context.child_customer_address.email,
					phone: context.child_customer_address.phone,
					address: context.child_customer_address.address,
					country: context.child_customer_address.country ? context.child_customer_address.country.toString() : "",
					state: context.child_customer_address.state ? context.child_customer_address.state.toString() : "",
					city: context.child_customer_address.city ? context.child_customer_address.city.toString() : "",
					zip_code: context.child_customer_address.zip_code
				}).then((res) => {
					if (res.data.error) Botble.showError(res.data.message);
					else {
						context.child_customer_address = res.data.data.address;
						context.child_customer = res.data.data.customer;
						context.child_customer_id = context.child_customer.id;
						context.customers = { data: [] };
						Botble.showSuccess(res.data.message);
						context.checkDataBeforeCreateOrder();
						$event.emit("ec-modal:close", "add-customer");
					}
				}).catch((res) => {
					Botble.handleError(res.response.data);
				}).then(() => {
					$(event.target).removeClass("btn-loading");
				});
			},
			selectCustomerAddress: function(event) {
				let context = this;
				_.each(this.child_customer_addresses, (item) => {
					if (parseInt(item.id) === parseInt(event.target.value)) context.child_customer_address = item;
				});
				this.checkDataBeforeCreateOrder();
			},
			getOrderNumbers: function() {
				let context = this;
				axios.get(route("customers.get-customer-order-numbers", context.child_customer_id)).then((res) => {
					context.child_customer_order_numbers = res.data.data;
				}).catch((res) => {
					Botble.handleError(res.response.data);
				});
			},
			loadCustomerAddress: function() {
				let context = this;
				axios.get(route("customers.get-customer-addresses", context.child_customer_id)).then((res) => {
					context.child_customer_addresses = res.data.data;
					if (!_.isEmpty(context.child_customer_addresses)) context.child_customer_address = _.first(context.child_customer_addresses);
					this.checkDataBeforeCreateOrder();
				}).catch((res) => {
					Botble.handleError(res.response.data);
				});
			},
			selectShippingMethod: function(event) {
				event.preventDefault();
				let context = this;
				let $button = $(event.target);
				let $modal = $button.closest(".modal");
				$button.addClass("btn-loading");
				context.child_is_selected_shipping = true;
				if (context.shipping_type === "free-shipping") {
					context.child_shipping_method_name = context.__("order.free_shipping");
					context.child_shipping_amount = 0;
				} else {
					let selected_shipping = $modal.find(".form-select").val();
					if (!_.isEmpty(selected_shipping)) {
						let option = $modal.find(".form-select option:selected");
						context.child_shipping_method = option.data("shipping-method");
						context.child_shipping_option = option.data("shipping-option");
					}
				}
				this.checkDataBeforeCreateOrder({}, () => {
					setTimeout(function() {
						$button.removeClass("btn-loading");
						$event.emit("ec-modal:close", "add-shipping");
					}, 500);
				}, () => {
					setTimeout(function() {
						$button.removeClass("btn-loading");
					}, 500);
				});
			},
			changeDiscountType: function(event) {
				if ($(event.target).val() === "amount") this.discount_type_unit = this.currency;
				else this.discount_type_unit = "%";
				this.discount_type = $(event.target).val();
			},
			handleAddDiscount: function(event) {
				event.preventDefault();
				let $target = $(event.target);
				let context = this;
				context.has_applied_discount = true;
				context.has_invalid_coupon = false;
				let $button = $target.find(".btn-primary");
				$button.addClass("btn-loading").prop("disabled", true);
				if (context.child_coupon_code) context.discount_custom_value = 0;
				else {
					context.discount_custom_value = Math.max(parseFloat(context.discount_custom_value), 0);
					if (context.discount_type === "percentage") context.discount_custom_value = Math.min(context.discount_custom_value, 100);
				}
				context.checkDataBeforeCreateOrder({}, () => {
					setTimeout(function() {
						if (!context.child_coupon_code && !context.discount_custom_value) context.has_applied_discount = false;
						$button.removeClass("btn-loading").prop("disabled", false);
						$event.emit("ec-modal:close", "add-discounts");
					}, 500);
				}, () => {
					if (context.child_coupon_code) context.has_invalid_coupon = true;
					$button.removeClass("btn-loading").prop("disabled", false);
				});
			},
			handleChangeQuantity: function(event, variant, vKey) {
				event.preventDefault();
				let context = this;
				variant.select_qty = parseInt(event.target.value);
				_.each(context.child_products, function(item, key) {
					if (vKey === key) {
						if (variant.with_storehouse_management && parseInt(variant.select_qty) > variant.quantity) variant.select_qty = variant.quantity;
						context.child_products[key] = variant;
					}
				});
				if (context.timeoutChangeQuantity) clearTimeout(context.timeoutChangeQuantity);
				context.timeoutChangeQuantity = setTimeout(() => {
					context.checkDataBeforeCreateOrder();
				}, 1500);
			}
		},
		watch: { child_payment_method: function() {
			this.checkDataBeforeCreateOrder();
		} }
	};
	var _hoisted_1$1 = { class: "row row-cards" };
	var _hoisted_2$1 = { class: "col-md-9" };
	var _hoisted_3$1 = { class: "card" };
	var _hoisted_4$1 = { class: "card-header" };
	var _hoisted_5$1 = { class: "card-title" };
	var _hoisted_6$1 = { class: "card-body" };
	var _hoisted_7$1 = { class: "mb-3" };
	var _hoisted_8$1 = { class: "table table-bordered table-vcenter" };
	var _hoisted_9$1 = { width: "90" };
	var _hoisted_10 = ["src", "alt"];
	var _hoisted_11 = ["href"];
	var _hoisted_12 = { key: 0 };
	var _hoisted_13 = { key: 1 };
	var _hoisted_14 = { class: "text-center" };
	var _hoisted_15 = ["value", "onInput"];
	var _hoisted_16 = { class: "text-center" };
	var _hoisted_17 = ["onClick"];
	var _hoisted_18 = { class: "position-relative box-search-advance product mt-3" };
	var _hoisted_19 = ["placeholder"];
	var _hoisted_20 = {
		key: 0,
		class: "loading-spinner"
	};
	var _hoisted_21 = {
		key: 1,
		class: "list-group list-group-flush overflow-auto",
		style: { "max-height": "25rem" }
	};
	var _hoisted_22 = {
		href: "javascript:void(0)",
		class: "list-group-item list-group-item-action"
	};
	var _hoisted_23 = { class: "row align-items-start" };
	var _hoisted_24 = { class: "col-auto" };
	var _hoisted_25 = { class: "col text-truncate" };
	var _hoisted_26 = {
		key: 0,
		class: "list-group list-group-flush"
	};
	var _hoisted_27 = {
		key: 0,
		class: "p-3"
	};
	var _hoisted_28 = { class: "text-muted text-center mb-0" };
	var _hoisted_29 = {
		key: 2,
		class: "card-footer"
	};
	var _hoisted_30 = { class: "pagination my-0 d-flex justify-content-end" };
	var _hoisted_31 = ["aria-disabled"];
	var _hoisted_32 = ["aria-disabled"];
	var _hoisted_33 = { class: "row" };
	var _hoisted_34 = { class: "col-sm-6" };
	var _hoisted_35 = { class: "mb-3 position-relative" };
	var _hoisted_36 = {
		class: "form-label",
		for: "txt-note"
	};
	var _hoisted_37 = ["placeholder"];
	var _hoisted_38 = { class: "col-sm-6" };
	var _hoisted_39 = { class: "table table-borderless text-end table-vcenter" };
	var _hoisted_40 = {
		key: 0,
		class: "spinner-grow spinner-grow-sm",
		role: "status",
		"aria-hidden": "true"
	};
	var _hoisted_41 = { class: "fw-bold" };
	var _hoisted_42 = {
		key: 0,
		class: "spinner-grow spinner-grow-sm",
		role: "status",
		"aria-hidden": "true"
	};
	var _hoisted_43 = { class: "fw-bold" };
	var _hoisted_44 = {
		class: "spinner-grow spinner-grow-sm",
		role: "status",
		"aria-hidden": "true"
	};
	var _hoisted_45 = {
		type: "button",
		class: "btn btn-outline-primary btn-sm mb-1"
	};
	var _hoisted_46 = {
		key: 0,
		class: "d-block small fw-bold"
	};
	var _hoisted_47 = {
		class: "spinner-grow spinner-grow-sm",
		role: "status",
		"aria-hidden": "true"
	};
	var _hoisted_48 = { key: 0 };
	var _hoisted_49 = {
		type: "button",
		class: "btn btn-outline-primary btn-sm mb-1"
	};
	var _hoisted_50 = {
		key: 0,
		class: "d-block small fw-bold"
	};
	var _hoisted_51 = {
		class: "spinner-grow spinner-grow-sm",
		role: "status",
		"aria-hidden": "true"
	};
	var _hoisted_52 = {
		class: "spinner-grow spinner-grow-sm",
		role: "status",
		"aria-hidden": "true"
	};
	var _hoisted_53 = { class: "d-inline-block" };
	var _hoisted_54 = { key: 1 };
	var _hoisted_55 = { colspan: "2" };
	var _hoisted_56 = {
		for: "payment-method",
		class: "form-label"
	};
	var _hoisted_57 = ["value"];
	var _hoisted_58 = { key: 2 };
	var _hoisted_59 = { colspan: "2" };
	var _hoisted_60 = {
		for: "payment-status",
		class: "form-label"
	};
	var _hoisted_61 = ["value"];
	var _hoisted_62 = { key: 3 };
	var _hoisted_63 = { colspan: "2" };
	var _hoisted_64 = {
		for: "payment-status",
		class: "form-label"
	};
	var _hoisted_65 = { class: "form-hint" };
	var _hoisted_66 = { class: "card-footer" };
	var _hoisted_67 = { class: "d-flex justify-content-between align-items-center flex-wrap gap-2" };
	var _hoisted_68 = { class: "mb-0 text-uppercase" };
	var _hoisted_69 = ["disabled"];
	var _hoisted_70 = { class: "col-md-3" };
	var _hoisted_71 = { class: "card" };
	var _hoisted_72 = { key: 0 };
	var _hoisted_73 = { class: "card-header" };
	var _hoisted_74 = { class: "card-title" };
	var _hoisted_75 = { class: "card-body" };
	var _hoisted_76 = { class: "position-relative box-search-advance customer" };
	var _hoisted_77 = ["placeholder"];
	var _hoisted_78 = {
		key: 0,
		class: "loading-spinner"
	};
	var _hoisted_79 = {
		key: 1,
		class: "list-group list-group-flush overflow-auto",
		style: { "max-height": "25rem" }
	};
	var _hoisted_80 = { class: "list-group-item cursor-pointer" };
	var _hoisted_81 = { class: "row align-items-center" };
	var _hoisted_82 = { class: "col" };
	var _hoisted_83 = ["onClick"];
	var _hoisted_84 = { class: "flexbox-grid-default flexbox-align-items-center" };
	var _hoisted_85 = { class: "row align-items-center" };
	var _hoisted_86 = { class: "col-auto" };
	var _hoisted_87 = { class: "col text-truncate" };
	var _hoisted_88 = { class: "text-body d-block" };
	var _hoisted_89 = {
		key: 0,
		class: "text-secondary text-truncate mt-n1"
	};
	var _hoisted_90 = {
		key: 0,
		class: "list-group-item"
	};
	var _hoisted_91 = {
		key: 2,
		class: "card-footer"
	};
	var _hoisted_92 = { class: "pagination my-0 d-flex justify-content-end" };
	var _hoisted_93 = ["aria-disabled"];
	var _hoisted_94 = ["aria-disabled"];
	var _hoisted_95 = { key: 1 };
	var _hoisted_96 = { class: "card-header" };
	var _hoisted_97 = { class: "card-title" };
	var _hoisted_98 = { class: "card-actions" };
	var _hoisted_99 = { class: "card-body p-0" };
	var _hoisted_100 = { class: "p-3" };
	var _hoisted_101 = { class: "mb-3" };
	var _hoisted_102 = { class: "mb-1" };
	var _hoisted_103 = { class: "mb-n1" };
	var _hoisted_104 = {
		key: 0,
		class: "d-flex justify-content-between align-items-center"
	};
	var _hoisted_105 = ["data-bs-original-title"];
	var _hoisted_106 = { class: "p-3" };
	var _hoisted_107 = { class: "d-flex justify-content-between align-items-center mb-2" };
	var _hoisted_108 = { class: "mb-0" };
	var _hoisted_109 = {
		type: "button",
		class: "btn-action",
		"data-bs-toggle": "tooltip",
		"data-bs-title": "Update address"
	};
	var _hoisted_110 = {
		key: 0,
		class: "mb-3"
	};
	var _hoisted_111 = ["value", "selected"];
	var _hoisted_112 = { class: "row mb-0" };
	var _hoisted_113 = { key: 0 };
	var _hoisted_114 = { key: 1 };
	var _hoisted_115 = ["href"];
	var _hoisted_116 = { class: "next-form-section" };
	var _hoisted_117 = { class: "next-form-grid" };
	var _hoisted_118 = { class: "mb-3 position-relative" };
	var _hoisted_119 = { class: "form-label" };
	var _hoisted_120 = { class: "row" };
	var _hoisted_121 = { class: "col-auto" };
	var _hoisted_122 = { class: "col" };
	var _hoisted_123 = { class: "input-group input-group-flat" };
	var _hoisted_124 = { class: "input-group-text" };
	var _hoisted_125 = { class: "next-form-grid" };
	var _hoisted_126 = { class: "mb-3 position-relative" };
	var _hoisted_127 = { class: "form-label" };
	var _hoisted_128 = { class: "position-relative" };
	var _hoisted_129 = { class: "form-label" };
	var _hoisted_130 = ["placeholder"];
	var _hoisted_131 = { key: 0 };
	var _hoisted_132 = {
		class: "alert alert-success",
		role: "alert"
	};
	var _hoisted_133 = { class: "d-flex" };
	var _hoisted_134 = { class: "alert-title" };
	var _hoisted_135 = { class: "text-muted" };
	var _hoisted_136 = { class: "position-relative" };
	var _hoisted_137 = { class: "form-check form-check-inline" };
	var _hoisted_138 = { key: 1 };
	var _hoisted_139 = { class: "mb-3 position-relative" };
	var _hoisted_140 = { class: "form-check form-check-inline" };
	var _hoisted_141 = ["disabled"];
	var _hoisted_142 = { class: "form-check-label" };
	var _hoisted_143 = {
		key: 0,
		class: "text-warning"
	};
	var _hoisted_144 = { class: "form-select" };
	var _hoisted_145 = [
		"value",
		"selected",
		"data-shipping-method",
		"data-shipping-option"
	];
	var _hoisted_146 = {
		key: 0,
		class: "alert alert-warning",
		role: "alert"
	};
	var _hoisted_147 = { class: "d-inline-block ms-2 mb-0" };
	function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
		const _component_ProductAction = (0, vue.resolveComponent)("ProductAction");
		const _component_AddProductModal = (0, vue.resolveComponent)("AddProductModal");
		const _component_ec_modal = (0, vue.resolveComponent)("ec-modal");
		const _component_OrderCustomerAddress = (0, vue.resolveComponent)("OrderCustomerAddress");
		const _directive_ec_modal = (0, vue.resolveDirective)("ec-modal");
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_1$1, [
			(0, vue.createElementVNode)("div", _hoisted_2$1, [(0, vue.createElementVNode)("div", _hoisted_3$1, [
				(0, vue.createElementVNode)("div", _hoisted_4$1, [(0, vue.createElementVNode)("h4", _hoisted_5$1, (0, vue.toDisplayString)(_ctx.__("order.order_information")), 1)]),
				(0, vue.createElementVNode)("div", _hoisted_6$1, [(0, vue.createElementVNode)("div", _hoisted_7$1, [_ctx.child_products.length ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", {
					key: 0,
					class: (0, vue.normalizeClass)(["table-responsive", { "loading-skeleton": _ctx.checking }])
				}, [(0, vue.createElementVNode)("table", _hoisted_8$1, [(0, vue.createElementVNode)("thead", null, [(0, vue.createElementVNode)("tr", null, [
					_cache[24] || (_cache[24] = (0, vue.createElementVNode)("th", null, null, -1)),
					(0, vue.createElementVNode)("th", null, (0, vue.toDisplayString)(_ctx.__("order.product_name")), 1),
					(0, vue.createElementVNode)("th", null, (0, vue.toDisplayString)(_ctx.__("order.price")), 1),
					(0, vue.createElementVNode)("th", _hoisted_9$1, (0, vue.toDisplayString)(_ctx.__("order.quantity")), 1),
					(0, vue.createElementVNode)("th", null, (0, vue.toDisplayString)(_ctx.__("order.total")), 1),
					(0, vue.createElementVNode)("th", null, (0, vue.toDisplayString)(_ctx.__("order.action")), 1)
				])]), (0, vue.createElementVNode)("tbody", null, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.child_products, (variant, vKey) => {
					return (0, vue.openBlock)(), (0, vue.createElementBlock)("tr", { key: `${variant.id}-${vKey}` }, [
						(0, vue.createElementVNode)("td", null, [(0, vue.createElementVNode)("img", {
							src: variant.image_url,
							alt: variant.name,
							width: "50"
						}, null, 8, _hoisted_10)]),
						(0, vue.createElementVNode)("td", null, [
							(0, vue.createElementVNode)("a", {
								href: variant.product_link,
								target: "_blank"
							}, (0, vue.toDisplayString)(variant.name), 9, _hoisted_11),
							variant.variation_attributes ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("p", _hoisted_12, [(0, vue.createElementVNode)("small", null, (0, vue.toDisplayString)(variant.variation_attributes), 1)])) : (0, vue.createCommentVNode)("v-if", true),
							variant.option_values && Object.keys(variant.option_values).length ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("ul", _hoisted_13, [(0, vue.createElementVNode)("li", null, [(0, vue.createElementVNode)("span", null, (0, vue.toDisplayString)(_ctx.__("order.price")) + ": ", 1), (0, vue.createElementVNode)("span", null, (0, vue.toDisplayString)(variant.original_price_label), 1)]), ((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(variant.option_values, (option) => {
								return (0, vue.openBlock)(), (0, vue.createElementBlock)("li", { key: option.id }, [(0, vue.createElementVNode)("span", null, (0, vue.toDisplayString)(option.title) + ": ", 1), ((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(option.values, (value) => {
									return (0, vue.openBlock)(), (0, vue.createElementBlock)("span", { key: value.id }, [(0, vue.createTextVNode)((0, vue.toDisplayString)(value.value) + " ", 1), (0, vue.createElementVNode)("strong", null, "+" + (0, vue.toDisplayString)(value.price_label), 1)]);
								}), 128))]);
							}), 128))])) : (0, vue.createCommentVNode)("v-if", true)
						]),
						(0, vue.createElementVNode)("td", null, [(0, vue.createElementVNode)("span", null, (0, vue.toDisplayString)(variant.price_label), 1)]),
						(0, vue.createElementVNode)("td", _hoisted_14, [(0, vue.createElementVNode)("input", {
							class: "form-control form-control-sm",
							value: variant.select_qty,
							type: "number",
							min: "1",
							onInput: ($event) => $options.handleChangeQuantity($event, variant, vKey)
						}, null, 40, _hoisted_15)]),
						(0, vue.createElementVNode)("td", null, (0, vue.toDisplayString)(variant.total_price_label), 1),
						(0, vue.createElementVNode)("td", _hoisted_16, [(0, vue.createElementVNode)("a", {
							href: "javascript:void(0)",
							onClick: ($event) => $options.handleRemoveVariant($event, variant, vKey),
							class: "text-decoration-none"
						}, [..._cache[25] || (_cache[25] = [(0, vue.createStaticVNode)("<span class=\"icon-tabler-wrapper icon-sm icon-left\"><svg xmlns=\"http://www.w3.org/2000/svg\" class=\"icon icon-tabler icon-tabler-x\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"></path><path d=\"M18 6l-12 12\"></path><path d=\"M6 6l12 12\"></path></svg></span>", 1)])], 8, _hoisted_17)])
					]);
				}), 128))])])], 2)) : (0, vue.createCommentVNode)("v-if", true), (0, vue.createElementVNode)("div", _hoisted_18, [(0, vue.createElementVNode)("input", {
					type: "text",
					class: "form-control textbox-advancesearch product",
					placeholder: _ctx.__("order.search_or_create_new_product"),
					onClick: _cache[0] || (_cache[0] = ($event) => $options.loadListProductsAndVariations()),
					onKeyup: _cache[1] || (_cache[1] = ($event) => $options.handleSearchProduct($event.target.value))
				}, null, 40, _hoisted_19), (0, vue.createElementVNode)("div", {
					class: (0, vue.normalizeClass)(["card position-absolute z-1 w-100", {
						active: _ctx.list_products,
						hidden: _ctx.hidden_product_search_panel
					}]),
					style: (0, vue.normalizeStyle)([_ctx.loading ? { minHeight: "10rem" } : {}])
				}, [_ctx.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_20)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_21, [
					(0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("a", _hoisted_22, [_cache[26] || (_cache[26] = (0, vue.createElementVNode)("img", {
						width: "28",
						src: _vendor_core_plugins_ecommerce_images_next_create_custom_line_item_svg.default,
						alt: "icon",
						class: "me-2"
					}, null, -1)), (0, vue.createTextVNode)(" " + (0, vue.toDisplayString)(_ctx.__("order.create_a_new_product")), 1)])), [[
						_directive_ec_modal,
						void 0,
						void 0,
						{ "add-product-item": true }
					]]),
					((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.list_products.data, (product_item) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
							class: (0, vue.normalizeClass)({
								"list-group-item list-group-item-action": true,
								"item-selectable": !product_item.variations.length,
								"item-not-selectable": product_item.variations.length
							}),
							key: product_item.id
						}, [(0, vue.createElementVNode)("div", _hoisted_23, [(0, vue.createElementVNode)("div", _hoisted_24, [(0, vue.createElementVNode)("span", {
							class: "avatar",
							style: (0, vue.normalizeStyle)({ backgroundImage: "url(" + product_item.image_url + ")" })
						}, null, 4)]), (0, vue.createElementVNode)("div", _hoisted_25, [(0, vue.createVNode)(_component_ProductAction, {
							ref_for: true,
							ref: "product_actions_" + product_item.id,
							product: product_item,
							onSelectProduct: $options.selectProductVariant
						}, null, 8, ["product", "onSelectProduct"]), product_item.variations.length ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_26, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(product_item.variations, (variation) => {
							return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", {
								class: "list-group-item p-2",
								key: variation.id
							}, [(0, vue.createVNode)(_component_ProductAction, {
								product: variation,
								onSelectProduct: $options.selectProductVariant
							}, null, 8, ["product", "onSelectProduct"])]);
						}), 128))])) : (0, vue.createCommentVNode)("v-if", true)])])], 2);
					}), 128)),
					_ctx.list_products.data && _ctx.list_products.data.length === 0 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_27, [(0, vue.createElementVNode)("p", _hoisted_28, (0, vue.toDisplayString)(_ctx.__("order.no_products_found")), 1)])) : (0, vue.createCommentVNode)("v-if", true)
				])), (_ctx.list_products.links && _ctx.list_products.links.next || _ctx.list_products.links && _ctx.list_products.links.prev) && !_ctx.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_29, [(0, vue.createElementVNode)("ul", _hoisted_30, [(0, vue.createElementVNode)("li", { class: (0, vue.normalizeClass)({
					"page-item": true,
					disabled: _ctx.list_products.meta.current_page === 1
				}) }, [_ctx.list_products.meta.current_page === 1 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", {
					key: 0,
					class: "page-link",
					"aria-disabled": _ctx.list_products.meta.current_page === 1
				}, [..._cache[27] || (_cache[27] = [(0, vue.createElementVNode)("svg", {
					xmlns: "http://www.w3.org/2000/svg",
					width: "24",
					height: "24",
					viewBox: "0 0 24 24",
					fill: "none",
					stroke: "currentColor",
					"stroke-width": "2",
					"stroke-linecap": "round",
					"stroke-linejoin": "round",
					class: "icon"
				}, [(0, vue.createElementVNode)("path", {
					stroke: "none",
					d: "M0 0h24v24H0z",
					fill: "none"
				}), (0, vue.createElementVNode)("path", { d: "M15 6l-6 6l6 6" })], -1)])], 8, _hoisted_31)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
					key: 1,
					href: "javascript:void(0)",
					class: "page-link",
					onClick: _cache[2] || (_cache[2] = ($event) => $options.loadListProductsAndVariations(_ctx.list_products.links.prev ? _ctx.list_products.meta.current_page - 1 : _ctx.list_products.meta.current_page, true))
				}, [..._cache[28] || (_cache[28] = [(0, vue.createElementVNode)("svg", {
					xmlns: "http://www.w3.org/2000/svg",
					width: "24",
					height: "24",
					viewBox: "0 0 24 24",
					fill: "none",
					stroke: "currentColor",
					"stroke-width": "2",
					"stroke-linecap": "round",
					"stroke-linejoin": "round",
					class: "icon"
				}, [(0, vue.createElementVNode)("path", {
					stroke: "none",
					d: "M0 0h24v24H0z",
					fill: "none"
				}), (0, vue.createElementVNode)("path", { d: "M15 6l-6 6l6 6" })], -1)])]))], 2), (0, vue.createElementVNode)("li", { class: (0, vue.normalizeClass)({
					"page-item": true,
					disabled: !_ctx.list_products.links.next
				}) }, [!_ctx.list_products.links.next ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", {
					key: 0,
					class: "page-link",
					"aria-disabled": !_ctx.list_products.links.next
				}, [..._cache[29] || (_cache[29] = [(0, vue.createElementVNode)("svg", {
					xmlns: "http://www.w3.org/2000/svg",
					width: "24",
					height: "24",
					viewBox: "0 0 24 24",
					fill: "none",
					stroke: "currentColor",
					"stroke-width": "2",
					"stroke-linecap": "round",
					"stroke-linejoin": "round",
					class: "icon"
				}, [(0, vue.createElementVNode)("path", {
					stroke: "none",
					d: "M0 0h24v24H0z",
					fill: "none"
				}), (0, vue.createElementVNode)("path", { d: "M9 6l6 6l-6 6" })], -1)])], 8, _hoisted_32)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
					key: 1,
					href: "javascript:void(0)",
					class: "page-link",
					onClick: _cache[3] || (_cache[3] = ($event) => $options.loadListProductsAndVariations(_ctx.list_products.links.next ? _ctx.list_products.meta.current_page + 1 : _ctx.list_products.meta.current_page, true))
				}, [..._cache[30] || (_cache[30] = [(0, vue.createElementVNode)("svg", {
					xmlns: "http://www.w3.org/2000/svg",
					width: "24",
					height: "24",
					viewBox: "0 0 24 24",
					fill: "none",
					stroke: "currentColor",
					"stroke-width": "2",
					"stroke-linecap": "round",
					"stroke-linejoin": "round",
					class: "icon"
				}, [(0, vue.createElementVNode)("path", {
					stroke: "none",
					d: "M0 0h24v24H0z",
					fill: "none"
				}), (0, vue.createElementVNode)("path", { d: "M9 6l6 6l-6 6" })], -1)])]))], 2)])])) : (0, vue.createCommentVNode)("v-if", true)], 6)])]), (0, vue.createElementVNode)("div", _hoisted_33, [(0, vue.createElementVNode)("div", _hoisted_34, [(0, vue.createElementVNode)("div", _hoisted_35, [(0, vue.createElementVNode)("label", _hoisted_36, (0, vue.toDisplayString)(_ctx.__("order.note")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("textarea", {
					"onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => _ctx.note = $event),
					class: "form-control textarea-auto-height",
					id: "txt-note",
					rows: "2",
					placeholder: _ctx.__("order.note_for_order")
				}, null, 8, _hoisted_37), [[vue.vModelText, _ctx.note]])])]), (0, vue.createElementVNode)("div", _hoisted_38, [(0, vue.createElementVNode)("table", _hoisted_39, [_cache[33] || (_cache[33] = (0, vue.createElementVNode)("thead", null, [(0, vue.createElementVNode)("tr", null, [(0, vue.createElementVNode)("td"), (0, vue.createElementVNode)("td", { width: "120" })])], -1)), (0, vue.createElementVNode)("tbody", null, [
					(0, vue.createElementVNode)("tr", null, [(0, vue.createElementVNode)("td", null, (0, vue.toDisplayString)(_ctx.__("order.sub_amount")), 1), (0, vue.createElementVNode)("td", null, [_ctx.checking ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", _hoisted_40)) : (0, vue.createCommentVNode)("v-if", true), (0, vue.createElementVNode)("span", _hoisted_41, (0, vue.toDisplayString)(_ctx.child_sub_amount_label), 1)])]),
					(0, vue.createElementVNode)("tr", null, [(0, vue.createElementVNode)("td", null, (0, vue.toDisplayString)(_ctx.__("order.tax_amount")), 1), (0, vue.createElementVNode)("td", null, [_ctx.checking ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", _hoisted_42)) : (0, vue.createCommentVNode)("v-if", true), (0, vue.createElementVNode)("span", _hoisted_43, (0, vue.toDisplayString)(_ctx.child_tax_amount_label), 1)])]),
					(0, vue.createElementVNode)("tr", null, [(0, vue.createElementVNode)("td", null, (0, vue.toDisplayString)(_ctx.__("order.promotion_discount_amount")), 1), (0, vue.createElementVNode)("td", null, [(0, vue.withDirectives)((0, vue.createElementVNode)("span", _hoisted_44, null, 512), [[vue.vShow, _ctx.checking]]), (0, vue.createElementVNode)("span", { class: (0, vue.normalizeClass)({
						"fw-bold": true,
						"text-success": _ctx.child_promotion_amount
					}) }, (0, vue.toDisplayString)(_ctx.child_promotion_amount_label), 3)])]),
					(0, vue.createElementVNode)("tr", null, [(0, vue.createElementVNode)("td", null, [(0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("button", _hoisted_45, [!_ctx.has_applied_discount ? ((0, vue.openBlock)(), (0, vue.createElementBlock)(vue.Fragment, { key: 0 }, [_cache[31] || (_cache[31] = (0, vue.createElementVNode)("i", { class: "icon-sm ti ti-plus" }, null, -1)), (0, vue.createTextVNode)(" " + (0, vue.toDisplayString)(_ctx.__("order.add_discount")), 1)], 64)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)(vue.Fragment, { key: 1 }, [(0, vue.createTextVNode)((0, vue.toDisplayString)(_ctx.__("order.discount")), 1)], 64))])), [[
						_directive_ec_modal,
						void 0,
						void 0,
						{ "add-discounts": true }
					]]), _ctx.has_applied_discount ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", _hoisted_46, (0, vue.toDisplayString)(_ctx.child_coupon_code || _ctx.child_discount_description), 1)) : (0, vue.createCommentVNode)("v-if", true)]), (0, vue.createElementVNode)("td", null, [(0, vue.withDirectives)((0, vue.createElementVNode)("span", _hoisted_47, null, 512), [[vue.vShow, _ctx.checking]]), (0, vue.createElementVNode)("span", { class: (0, vue.normalizeClass)({ "text-success fw-bold": _ctx.child_discount_amount }) }, (0, vue.toDisplayString)(_ctx.child_discount_amount_label), 3)])]),
					_ctx.is_available_shipping ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("tr", _hoisted_48, [(0, vue.createElementVNode)("td", null, [(0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("button", _hoisted_49, [!_ctx.child_is_selected_shipping ? ((0, vue.openBlock)(), (0, vue.createElementBlock)(vue.Fragment, { key: 0 }, [_cache[32] || (_cache[32] = (0, vue.createElementVNode)("i", { class: "icon-sm ti ti-plus" }, null, -1)), (0, vue.createTextVNode)(" " + (0, vue.toDisplayString)(_ctx.__("order.add_shipping_fee")), 1)], 64)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)(vue.Fragment, { key: 1 }, [(0, vue.createTextVNode)((0, vue.toDisplayString)(_ctx.__("order.shipping")), 1)], 64))])), [[
						_directive_ec_modal,
						void 0,
						void 0,
						{ "add-shipping": true }
					]]), _ctx.child_shipping_method_name ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", _hoisted_50, (0, vue.toDisplayString)(_ctx.child_shipping_method_name), 1)) : (0, vue.createCommentVNode)("v-if", true)]), (0, vue.createElementVNode)("td", null, [(0, vue.withDirectives)((0, vue.createElementVNode)("span", _hoisted_51, null, 512), [[vue.vShow, _ctx.checking]]), (0, vue.createElementVNode)("span", { class: (0, vue.normalizeClass)({ "fw-bold": _ctx.child_shipping_amount }) }, (0, vue.toDisplayString)(_ctx.child_shipping_amount_label), 3)])])) : (0, vue.createCommentVNode)("v-if", true),
					(0, vue.createElementVNode)("tr", null, [(0, vue.createElementVNode)("td", null, (0, vue.toDisplayString)(_ctx.__("order.total_amount")), 1), (0, vue.createElementVNode)("td", null, [(0, vue.withDirectives)((0, vue.createElementVNode)("span", _hoisted_52, null, 512), [[vue.vShow, _ctx.checking]]), (0, vue.createElementVNode)("h4", _hoisted_53, (0, vue.toDisplayString)(_ctx.child_total_amount_label), 1)])]),
					Object.keys($props.paymentMethods).length > 0 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("tr", _hoisted_54, [(0, vue.createElementVNode)("td", _hoisted_55, [(0, vue.createElementVNode)("label", _hoisted_56, (0, vue.toDisplayString)(_ctx.__("order.payment_method")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("select", {
						class: "form-select",
						id: "payment-method",
						"onUpdate:modelValue": _cache[5] || (_cache[5] = ($event) => _ctx.child_payment_method = $event)
					}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)($props.paymentMethods, (value, key) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
							key,
							value: key
						}, (0, vue.toDisplayString)(value), 9, _hoisted_57);
					}), 128))], 512), [[vue.vModelSelect, _ctx.child_payment_method]])])])) : (0, vue.createCommentVNode)("v-if", true),
					Object.keys($props.paymentMethods).length > 0 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("tr", _hoisted_58, [(0, vue.createElementVNode)("td", _hoisted_59, [(0, vue.createElementVNode)("label", _hoisted_60, (0, vue.toDisplayString)(_ctx.__("order.payment_status_label")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("select", {
						class: "form-select",
						id: "payment-status",
						"onUpdate:modelValue": _cache[6] || (_cache[6] = ($event) => _ctx.child_payment_status = $event)
					}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)($props.paymentStatuses, (value, key) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
							key,
							value: key
						}, (0, vue.toDisplayString)(value), 9, _hoisted_61);
					}), 128))], 512), [[vue.vModelSelect, _ctx.child_payment_status]])])])) : (0, vue.createCommentVNode)("v-if", true),
					Object.keys($props.paymentMethods).length > 0 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("tr", _hoisted_62, [(0, vue.createElementVNode)("td", _hoisted_63, [
						(0, vue.createElementVNode)("label", _hoisted_64, (0, vue.toDisplayString)(_ctx.__("order.transaction_id")), 1),
						(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
							type: "text",
							class: "form-control",
							"onUpdate:modelValue": _cache[7] || (_cache[7] = ($event) => _ctx.child_transaction_id = $event)
						}, null, 512), [[vue.vModelText, _ctx.child_transaction_id]]),
						(0, vue.createElementVNode)("small", _hoisted_65, (0, vue.toDisplayString)(_ctx.__("order.incomplete_order_transaction_id_placeholder")), 1)
					])])) : (0, vue.createCommentVNode)("v-if", true)
				])])])])]),
				(0, vue.createElementVNode)("div", _hoisted_66, [(0, vue.createElementVNode)("div", _hoisted_67, [(0, vue.createElementVNode)("p", _hoisted_68, [_cache[34] || (_cache[34] = (0, vue.createStaticVNode)("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"icon\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"></path><path d=\"M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z\"></path><path d=\"M3 10l18 0\"></path><path d=\"M7 15l.01 0\"></path><path d=\"M11 15l2 0\"></path></svg>", 1)), (0, vue.createTextVNode)(" " + (0, vue.toDisplayString)(_ctx.__("order.confirm_payment_and_create_order")), 1)]), (0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("button", {
					disabled: !_ctx.child_product_ids.length || !_ctx.child_customer_id,
					type: "submit",
					class: "btn btn-primary"
				}, [(0, vue.createTextVNode)((0, vue.toDisplayString)(_ctx.__("order.create_order")), 1)], 8, _hoisted_69)), [[
					_directive_ec_modal,
					void 0,
					void 0,
					{ "create-order": true }
				]])])])
			])]),
			(0, vue.createElementVNode)("div", _hoisted_70, [(0, vue.createElementVNode)("div", _hoisted_71, [!_ctx.child_customer_id || !_ctx.child_customer ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_72, [(0, vue.createElementVNode)("div", _hoisted_73, [(0, vue.createElementVNode)("h4", _hoisted_74, (0, vue.toDisplayString)(_ctx.__("order.customer_information")), 1)]), (0, vue.createElementVNode)("div", _hoisted_75, [(0, vue.createElementVNode)("div", _hoisted_76, [(0, vue.createElementVNode)("input", {
				type: "text",
				class: "form-control textbox-advancesearch customer",
				onClick: _cache[8] || (_cache[8] = ($event) => $options.loadListCustomersForSearch()),
				onKeyup: _cache[9] || (_cache[9] = ($event) => $options.handleSearchCustomer($event.target.value)),
				placeholder: _ctx.__("order.search_or_create_new_customer")
			}, null, 40, _hoisted_77), (0, vue.createElementVNode)("div", {
				class: (0, vue.normalizeClass)(["card position-absolute w-100 z-1", {
					active: _ctx.customers,
					hidden: _ctx.hidden_customer_search_panel
				}]),
				style: (0, vue.normalizeStyle)([_ctx.loading ? { minHeight: "10rem" } : {}])
			}, [_ctx.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_78)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_79, [
				(0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_80, [(0, vue.createElementVNode)("div", _hoisted_81, [_cache[35] || (_cache[35] = (0, vue.createElementVNode)("div", { class: "col-auto" }, [(0, vue.createElementVNode)("img", {
					width: "28",
					src: _vendor_core_plugins_ecommerce_images_next_create_customer_svg.default,
					alt: "icon"
				})], -1)), (0, vue.createElementVNode)("div", _hoisted_82, [(0, vue.createElementVNode)("span", null, (0, vue.toDisplayString)(_ctx.__("order.create_new_customer")), 1)])])])), [[
					_directive_ec_modal,
					void 0,
					void 0,
					{ "add-customer": true }
				]]),
				((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.customers.data, (customer) => {
					return (0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
						class: "list-group-item list-group-item-action",
						href: "javascript:void(0)",
						key: customer.id,
						onClick: ($event) => $options.selectCustomer(customer)
					}, [(0, vue.createElementVNode)("div", _hoisted_84, [(0, vue.createElementVNode)("div", _hoisted_85, [(0, vue.createElementVNode)("div", _hoisted_86, [(0, vue.createElementVNode)("span", {
						class: "avatar",
						style: (0, vue.normalizeStyle)({ backgroundImage: "url(" + customer.avatar_url + ")" })
					}, null, 4)]), (0, vue.createElementVNode)("div", _hoisted_87, [(0, vue.createElementVNode)("div", _hoisted_88, (0, vue.toDisplayString)(customer.name), 1), customer.email ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_89, (0, vue.toDisplayString)(customer.email), 1)) : (0, vue.createCommentVNode)("v-if", true)])])])], 8, _hoisted_83);
				}), 128)),
				_ctx.customers.data && _ctx.customers.data.length === 0 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_90, (0, vue.toDisplayString)(_ctx.__("order.no_customer_found")), 1)) : (0, vue.createCommentVNode)("v-if", true)
			])), (_ctx.customers.next_page_url || _ctx.customers.prev_page_url) && !_ctx.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_91, [(0, vue.createElementVNode)("ul", _hoisted_92, [(0, vue.createElementVNode)("li", { class: (0, vue.normalizeClass)({
				"page-item": true,
				disabled: _ctx.customers.current_page === 1
			}) }, [_ctx.customers.current_page === 1 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", {
				key: 0,
				class: "page-link",
				"aria-disabled": _ctx.customers.current_page === 1
			}, [..._cache[36] || (_cache[36] = [(0, vue.createElementVNode)("svg", {
				xmlns: "http://www.w3.org/2000/svg",
				width: "24",
				height: "24",
				viewBox: "0 0 24 24",
				fill: "none",
				stroke: "currentColor",
				"stroke-width": "2",
				"stroke-linecap": "round",
				"stroke-linejoin": "round",
				class: "icon"
			}, [(0, vue.createElementVNode)("path", {
				stroke: "none",
				d: "M0 0h24v24H0z",
				fill: "none"
			}), (0, vue.createElementVNode)("path", { d: "M15 6l-6 6l6 6" })], -1)])], 8, _hoisted_93)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
				key: 1,
				href: "javascript:void(0)",
				class: "page-link",
				onClick: _cache[10] || (_cache[10] = ($event) => $options.loadListCustomersForSearch(_ctx.customers.prev_page_url ? _ctx.customers.current_page - 1 : _ctx.customers.current_page, true))
			}, [..._cache[37] || (_cache[37] = [(0, vue.createElementVNode)("svg", {
				xmlns: "http://www.w3.org/2000/svg",
				width: "24",
				height: "24",
				viewBox: "0 0 24 24",
				fill: "none",
				stroke: "currentColor",
				"stroke-width": "2",
				"stroke-linecap": "round",
				"stroke-linejoin": "round",
				class: "icon"
			}, [(0, vue.createElementVNode)("path", {
				stroke: "none",
				d: "M0 0h24v24H0z",
				fill: "none"
			}), (0, vue.createElementVNode)("path", { d: "M15 6l-6 6l6 6" })], -1)])]))], 2), (0, vue.createElementVNode)("li", { class: (0, vue.normalizeClass)({
				"page-item": true,
				disabled: !_ctx.customers.next_page_url
			}) }, [!_ctx.customers.next_page_url ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", {
				key: 0,
				class: "page-link",
				"aria-disabled": !_ctx.customers.next_page_url
			}, [..._cache[38] || (_cache[38] = [(0, vue.createElementVNode)("svg", {
				xmlns: "http://www.w3.org/2000/svg",
				width: "24",
				height: "24",
				viewBox: "0 0 24 24",
				fill: "none",
				stroke: "currentColor",
				"stroke-width": "2",
				"stroke-linecap": "round",
				"stroke-linejoin": "round",
				class: "icon"
			}, [(0, vue.createElementVNode)("path", {
				stroke: "none",
				d: "M0 0h24v24H0z",
				fill: "none"
			}), (0, vue.createElementVNode)("path", { d: "M9 6l6 6l-6 6" })], -1)])], 8, _hoisted_94)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
				key: 1,
				href: "javascript:void(0)",
				class: "page-link",
				onClick: _cache[11] || (_cache[11] = ($event) => $options.loadListCustomersForSearch(_ctx.customers.next_page_url ? _ctx.customers.current_page + 1 : _ctx.customers.current_page, true))
			}, [..._cache[39] || (_cache[39] = [(0, vue.createElementVNode)("svg", {
				xmlns: "http://www.w3.org/2000/svg",
				width: "24",
				height: "24",
				viewBox: "0 0 24 24",
				fill: "none",
				stroke: "currentColor",
				"stroke-width": "2",
				"stroke-linecap": "round",
				"stroke-linejoin": "round",
				class: "icon"
			}, [(0, vue.createElementVNode)("path", {
				stroke: "none",
				d: "M0 0h24v24H0z",
				fill: "none"
			}), (0, vue.createElementVNode)("path", { d: "M9 6l6 6l-6 6" })], -1)])]))], 2)])])) : (0, vue.createCommentVNode)("v-if", true)], 6)])])])) : (0, vue.createCommentVNode)("v-if", true), _ctx.child_customer_id && _ctx.child_customer ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_95, [(0, vue.createElementVNode)("div", _hoisted_96, [(0, vue.createElementVNode)("h4", _hoisted_97, (0, vue.toDisplayString)(_ctx.__("order.customer")), 1), (0, vue.createElementVNode)("div", _hoisted_98, [(0, vue.createElementVNode)("button", {
				type: "button",
				"data-bs-toggle": "tooltip",
				"data-placement": "top",
				title: "Delete customer",
				onClick: _cache[12] || (_cache[12] = ($event) => $options.removeCustomer()),
				class: "btn-action"
			}, [..._cache[40] || (_cache[40] = [(0, vue.createStaticVNode)("<span class=\"icon-tabler-wrapper icon-sm icon-left\"><svg xmlns=\"http://www.w3.org/2000/svg\" class=\"icon icon-tabler icon-tabler-x\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"></path><path d=\"M18 6l-12 12\"></path><path d=\"M6 6l12 12\"></path></svg></span>", 1)])])])]), (0, vue.createElementVNode)("div", _hoisted_99, [(0, vue.createElementVNode)("div", _hoisted_100, [
				(0, vue.createElementVNode)("div", _hoisted_101, [(0, vue.createElementVNode)("span", {
					class: "avatar avatar-lg avatar-rounded",
					style: (0, vue.normalizeStyle)({ backgroundImage: `url(${_ctx.child_customer.avatar_url || _ctx.child_customer.avatar})` })
				}, null, 4)]),
				(0, vue.createElementVNode)("div", _hoisted_102, [_cache[41] || (_cache[41] = (0, vue.createElementVNode)("svg", {
					xmlns: "http://www.w3.org/2000/svg",
					width: "24",
					height: "24",
					viewBox: "0 0 24 24",
					fill: "none",
					stroke: "currentColor",
					"stroke-width": "2",
					"stroke-linecap": "round",
					"stroke-linejoin": "round",
					class: "icon"
				}, [
					(0, vue.createElementVNode)("path", {
						stroke: "none",
						d: "M0 0h24v24H0z",
						fill: "none"
					}),
					(0, vue.createElementVNode)("path", { d: "M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" }),
					(0, vue.createElementVNode)("path", { d: "M4 13h3l3 3h4l3 -3h3" })
				], -1)), (0, vue.createTextVNode)(" " + (0, vue.toDisplayString)(_ctx.child_customer_order_numbers) + " " + (0, vue.toDisplayString)(_ctx.__("order.orders")), 1)]),
				(0, vue.createElementVNode)("div", _hoisted_103, (0, vue.toDisplayString)(_ctx.child_customer.name), 1),
				_ctx.child_customer.email ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_104, [(0, vue.createElementVNode)("span", null, (0, vue.toDisplayString)(_ctx.child_customer.email), 1), (0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
					href: "javascript:void(0)",
					"data-placement": "top",
					"data-bs-toggle": "tooltip",
					"data-bs-original-title": _ctx.__("order.edit_email"),
					class: "btn-action text-decoration-none"
				}, [..._cache[42] || (_cache[42] = [(0, vue.createStaticVNode)("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"icon\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"></path><path d=\"M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1\"></path><path d=\"M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z\"></path><path d=\"M16 5l3 3\"></path></svg>", 1)])], 8, _hoisted_105)), [[
					_directive_ec_modal,
					void 0,
					void 0,
					{ "edit-email": true }
				]])])) : (0, vue.createCommentVNode)("v-if", true)
			]), _ctx.is_available_shipping ? ((0, vue.openBlock)(), (0, vue.createElementBlock)(vue.Fragment, { key: 0 }, [_cache[44] || (_cache[44] = (0, vue.createElementVNode)("div", { class: "hr my-1" }, null, -1)), (0, vue.createElementVNode)("div", _hoisted_106, [
				(0, vue.createElementVNode)("div", _hoisted_107, [(0, vue.createElementVNode)("h4", _hoisted_108, (0, vue.toDisplayString)(_ctx.__("order.shipping_address")), 1), (0, vue.withDirectives)(((0, vue.openBlock)(), (0, vue.createElementBlock)("button", _hoisted_109, [..._cache[43] || (_cache[43] = [(0, vue.createStaticVNode)("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"icon\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"></path><path d=\"M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1\"></path><path d=\"M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z\"></path><path d=\"M16 5l3 3\"></path></svg>", 1)])])), [[
					_directive_ec_modal,
					void 0,
					void 0,
					{ "edit-address": true }
				]])]),
				_ctx.child_customer_addresses.length > 1 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_110, [(0, vue.createElementVNode)("select", {
					class: "form-select",
					onChange: _cache[13] || (_cache[13] = ($event) => $options.selectCustomerAddress($event))
				}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.child_customer_addresses, (address_item) => {
					return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
						value: address_item.id,
						selected: address_item.id === $props.customer_address.id,
						key: address_item.id
					}, (0, vue.toDisplayString)(address_item.full_address), 9, _hoisted_111);
				}), 128))], 32)])) : (0, vue.createCommentVNode)("v-if", true),
				(0, vue.createElementVNode)("dl", _hoisted_112, [
					(0, vue.createElementVNode)("dd", null, (0, vue.toDisplayString)(_ctx.child_customer_address.name), 1),
					(0, vue.createElementVNode)("dd", null, (0, vue.toDisplayString)(_ctx.child_customer_address.phone), 1),
					(0, vue.createElementVNode)("dd", null, (0, vue.toDisplayString)(_ctx.child_customer_address.email), 1),
					(0, vue.createElementVNode)("dd", null, (0, vue.toDisplayString)(_ctx.child_customer_address.address), 1),
					(0, vue.createElementVNode)("dd", null, (0, vue.toDisplayString)(_ctx.child_customer_address.city_name), 1),
					(0, vue.createElementVNode)("dd", null, (0, vue.toDisplayString)(_ctx.child_customer_address.state_name), 1),
					(0, vue.createElementVNode)("dd", null, (0, vue.toDisplayString)(_ctx.child_customer_address.country_name), 1),
					$props.zip_code_enabled ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("dd", _hoisted_113, (0, vue.toDisplayString)(_ctx.child_customer_address.zip_code), 1)) : (0, vue.createCommentVNode)("v-if", true),
					_ctx.child_customer_address.full_address ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("dd", _hoisted_114, [(0, vue.createElementVNode)("a", {
						target: "_blank",
						class: "hover-underline",
						href: "https://maps.google.com/?q=" + _ctx.child_customer_address.full_address
					}, (0, vue.toDisplayString)(_ctx.__("order.see_on_maps")), 9, _hoisted_115)])) : (0, vue.createCommentVNode)("v-if", true)
				])
			])], 64)) : (0, vue.createCommentVNode)("v-if", true)])])) : (0, vue.createCommentVNode)("v-if", true)])]),
			(0, vue.createVNode)(_component_AddProductModal, {
				onCreateProduct: $options.createProduct,
				store: _ctx.store
			}, null, 8, ["onCreateProduct", "store"]),
			(0, vue.createVNode)(_component_ec_modal, {
				id: "add-discounts",
				title: _ctx.__("order.add_discount"),
				"ok-title": _ctx.__("order.add_discount"),
				"cancel-title": _ctx.__("order.close"),
				onOk: _cache[19] || (_cache[19] = ($event) => $options.handleAddDiscount($event))
			}, {
				default: (0, vue.withCtx)(() => [(0, vue.createElementVNode)("div", _hoisted_116, [(0, vue.createElementVNode)("div", _hoisted_117, [(0, vue.createElementVNode)("div", _hoisted_118, [(0, vue.createElementVNode)("label", _hoisted_119, (0, vue.toDisplayString)(_ctx.__("order.discount_based_on")), 1), (0, vue.createElementVNode)("div", _hoisted_120, [(0, vue.createElementVNode)("div", _hoisted_121, [
					(0, vue.createElementVNode)("button", {
						value: "amount",
						class: (0, vue.normalizeClass)(["btn btn-active", { active: _ctx.discount_type === "amount" }]),
						onClick: _cache[14] || (_cache[14] = ($event) => $options.changeDiscountType($event))
					}, (0, vue.toDisplayString)($props.currency || "$"), 3),
					_cache[45] || (_cache[45] = (0, vue.createTextVNode)("\xA0 ", -1)),
					(0, vue.createElementVNode)("button", {
						value: "percentage",
						class: (0, vue.normalizeClass)(["btn btn-active", { active: _ctx.discount_type === "percentage" }]),
						onClick: _cache[15] || (_cache[15] = ($event) => $options.changeDiscountType($event))
					}, " % ", 2)
				]), (0, vue.createElementVNode)("div", _hoisted_122, [(0, vue.createElementVNode)("div", _hoisted_123, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					class: "form-control",
					"onUpdate:modelValue": _cache[16] || (_cache[16] = ($event) => _ctx.discount_custom_value = $event)
				}, null, 512), [[vue.vModelText, _ctx.discount_custom_value]]), (0, vue.createElementVNode)("span", _hoisted_124, (0, vue.toDisplayString)(_ctx.discount_type_unit), 1)])])])])]), (0, vue.createElementVNode)("div", _hoisted_125, [(0, vue.createElementVNode)("div", _hoisted_126, [(0, vue.createElementVNode)("label", _hoisted_127, (0, vue.toDisplayString)(_ctx.__("order.or_coupon_code")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					class: "form-control coupon-code-input",
					"onUpdate:modelValue": _cache[17] || (_cache[17] = ($event) => _ctx.child_coupon_code = $event)
				}, null, 512), [[vue.vModelText, _ctx.child_coupon_code]])]), (0, vue.createElementVNode)("div", _hoisted_128, [(0, vue.createElementVNode)("label", _hoisted_129, (0, vue.toDisplayString)(_ctx.__("order.description")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					placeholder: _ctx.__("order.discount_description"),
					class: "form-control",
					"onUpdate:modelValue": _cache[18] || (_cache[18] = ($event) => _ctx.child_discount_description = $event)
				}, null, 8, _hoisted_130), [[vue.vModelText, _ctx.child_discount_description]])])])])]),
				_: 1
			}, 8, [
				"title",
				"ok-title",
				"cancel-title"
			]),
			(0, vue.createVNode)(_component_ec_modal, {
				id: "add-shipping",
				title: _ctx.__("order.shipping_fee"),
				"ok-title": _ctx.__("order.update"),
				"cancel-title": _ctx.__("order.close"),
				onOk: _cache[22] || (_cache[22] = ($event) => $options.selectShippingMethod($event))
			}, {
				default: (0, vue.withCtx)(() => [
					!_ctx.child_products.length || !_ctx.child_customer_address.phone ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_131, [(0, vue.createElementVNode)("div", _hoisted_132, [(0, vue.createElementVNode)("div", _hoisted_133, [_cache[46] || (_cache[46] = (0, vue.createElementVNode)("div", null, [(0, vue.createElementVNode)("i", { class: "icon alert-icon ti ti-alert-circle" })], -1)), (0, vue.createElementVNode)("div", null, [(0, vue.createElementVNode)("h4", _hoisted_134, (0, vue.toDisplayString)(_ctx.__("order.how_to_select_configured_shipping")), 1), (0, vue.createElementVNode)("div", _hoisted_135, (0, vue.toDisplayString)(_ctx.__("order.please_products_and_customer_address_to_see_the_shipping_rates")) + ". ", 1)])])])])) : (0, vue.createCommentVNode)("v-if", true),
					(0, vue.createElementVNode)("div", _hoisted_136, [(0, vue.createElementVNode)("label", _hoisted_137, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "radio",
						class: "form-check-input",
						value: "free-shipping",
						name: "shipping_type",
						"onUpdate:modelValue": _cache[20] || (_cache[20] = ($event) => _ctx.shipping_type = $event)
					}, null, 512), [[vue.vModelRadio, _ctx.shipping_type]]), (0, vue.createTextVNode)(" " + (0, vue.toDisplayString)(_ctx.__("order.free_shipping")), 1)])]),
					_ctx.child_products.length && _ctx.child_customer_address.phone ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_138, [(0, vue.createElementVNode)("div", _hoisted_139, [(0, vue.createElementVNode)("label", _hoisted_140, [
						(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
							type: "radio",
							class: "form-check-input",
							value: "custom",
							name: "shipping_type",
							"onUpdate:modelValue": _cache[21] || (_cache[21] = ($event) => _ctx.shipping_type = $event),
							disabled: _ctx.shipping_methods && !Object.keys(_ctx.shipping_methods).length
						}, null, 8, _hoisted_141), [[vue.vModelRadio, _ctx.shipping_type]]),
						(0, vue.createElementVNode)("span", _hoisted_142, (0, vue.toDisplayString)(_ctx.__("order.custom")), 1),
						_ctx.shipping_methods && !Object.keys(_ctx.shipping_methods).length ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("small", _hoisted_143, (0, vue.toDisplayString)(_ctx.__("order.shipping_method_not_found")), 1)) : (0, vue.createCommentVNode)("v-if", true)
					])]), (0, vue.withDirectives)((0, vue.createElementVNode)("select", _hoisted_144, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.shipping_methods, (shipping, shipping_key) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
							value: shipping_key,
							selected: shipping_key === `${_ctx.child_shipping_method};${_ctx.child_shipping_option}`,
							key: shipping_key,
							"data-shipping-method": shipping.method,
							"data-shipping-option": shipping.option
						}, (0, vue.toDisplayString)(shipping.title), 9, _hoisted_145);
					}), 128))], 512), [[vue.vShow, _ctx.shipping_type === "custom"]])])) : (0, vue.createCommentVNode)("v-if", true)
				]),
				_: 1
			}, 8, [
				"title",
				"ok-title",
				"cancel-title"
			]),
			(0, vue.createVNode)(_component_ec_modal, {
				id: "create-order",
				title: Object.keys($props.paymentMethods).length > 0 ? _ctx.__("order.confirm_payment_title").replace(":status", $props.paymentStatuses[_ctx.child_payment_status]) : _ctx.__("order.create_order"),
				"ok-title": _ctx.__("order.create_order"),
				"cancel-title": _ctx.__("order.close"),
				onOk: _cache[23] || (_cache[23] = ($event) => $options.createOrder($event))
			}, {
				default: (0, vue.withCtx)(() => [$props.paymentMethods.length ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_146, (0, vue.toDisplayString)(_ctx.__("order.confirm_payment_description").replace(":status", $props.paymentStatuses[_ctx.child_payment_status])) + ". ", 1)) : (0, vue.createCommentVNode)("v-if", true), (0, vue.createElementVNode)("div", null, [(0, vue.createElementVNode)("span", null, (0, vue.toDisplayString)(_ctx.__("order.order_amount")) + ":", 1), (0, vue.createElementVNode)("h3", _hoisted_147, (0, vue.toDisplayString)(_ctx.child_total_amount_label), 1)])]),
				_: 1
			}, 8, [
				"title",
				"ok-title",
				"cancel-title"
			]),
			(0, vue.createVNode)(_component_OrderCustomerAddress, {
				customer: _ctx.child_customer,
				address: _ctx.child_customer_address,
				zip_code_enabled: $props.zip_code_enabled,
				use_location_data: $props.use_location_data,
				onUpdateOrderAddress: $options.updateOrderAddress,
				onUpdateCustomerEmail: $options.updateCustomerEmail,
				onCreateNewCustomer: $options.createNewCustomer
			}, null, 8, [
				"customer",
				"address",
				"zip_code_enabled",
				"use_location_data",
				"onUpdateOrderAddress",
				"onUpdateCustomerEmail",
				"onCreateNewCustomer"
			])
		]);
	}
	var CreateOrderComponent_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main$1, [["render", _sfc_render$1]]);
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/EcommerceModal.vue
	var _sfc_main = (0, vue.defineComponent)({
		props: {
			id: {
				type: String,
				required: true
			},
			title: String,
			okTitle: String,
			cancelTitle: String
		},
		data() {
			return { modal: null };
		},
		mounted() {
			this.$emit("shown");
			this.modal = new bootstrap.Modal(document.getElementById(this.id));
			$event.on(`ec-modal:open`, (id) => {
				if (id === this.id) this.modal.show();
			});
			$event.on("ec-modal:close", (id) => {
				if (id === this.id) this.modal.hide();
			});
		}
	});
	var _hoisted_1 = ["id", "aria-labelledby"];
	var _hoisted_2 = {
		class: "modal-dialog modal-dialog-centered",
		role: "document"
	};
	var _hoisted_3 = { class: "modal-content" };
	var _hoisted_4 = { class: "modal-header" };
	var _hoisted_5 = ["id", "textContent"];
	var _hoisted_6 = { class: "modal-body" };
	var _hoisted_7 = { class: "modal-footer" };
	var _hoisted_8 = ["textContent"];
	var _hoisted_9 = ["textContent"];
	function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", {
			class: "modal modal-blur fade",
			id: _ctx.id,
			tabindex: "-1",
			"aria-labelledby": `${_ctx.id}Label`,
			"aria-hidden": "true"
		}, [(0, vue.createElementVNode)("div", _hoisted_2, [(0, vue.createElementVNode)("div", _hoisted_3, [
			(0, vue.createElementVNode)("header", _hoisted_4, [(0, vue.createElementVNode)("h5", {
				class: "modal-title",
				id: `${_ctx.id}Label`,
				textContent: (0, vue.toDisplayString)(_ctx.title)
			}, null, 8, _hoisted_5), _cache[1] || (_cache[1] = (0, vue.createElementVNode)("button", {
				type: "button",
				class: "btn-close",
				"data-bs-dismiss": "modal",
				"aria-label": "Close"
			}, null, -1))]),
			(0, vue.createElementVNode)("div", _hoisted_6, [(0, vue.renderSlot)(_ctx.$slots, "default")]),
			(0, vue.createElementVNode)("div", _hoisted_7, [(0, vue.createElementVNode)("button", {
				type: "button",
				class: "btn",
				"data-bs-dismiss": "modal",
				textContent: (0, vue.toDisplayString)(_ctx.cancelTitle)
			}, null, 8, _hoisted_8), (0, vue.createElementVNode)("button", {
				type: "button",
				class: "btn btn-primary ms-auto",
				onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("ok", $event)),
				textContent: (0, vue.toDisplayString)(_ctx.okTitle)
			}, null, 8, _hoisted_9)])
		])])], 8, _hoisted_1);
	}
	var EcommerceModal_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main, [["render", _sfc_render]]);
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/order-create.js
	if (typeof vueApp !== "undefined") vueApp.registerVuePlugins({ install(app) {
		app.config.globalProperties.$filters = { formatPrice(value) {
			return parseFloat(value).toFixed(2);
		} };
		app.directive("ec-modal", { mounted(el, bindings) {
			if (bindings.modifiers && Object.keys(bindings.modifiers).length > 0) el.addEventListener("click", () => {
				Object.keys(bindings.modifiers).forEach((modifier) => {
					$event.emit(`ec-modal:open`, modifier);
				});
			});
		} });
		app.component("ec-modal", EcommerceModal_default);
		app.component("create-order", CreateOrderComponent_default);
	} });
	//#endregion
})(Vue, "/vendor/core/plugins/ecommerce/images/next-create-custom-line-item.svg", "/vendor/core/plugins/ecommerce/images/next-create-customer.svg");

//# sourceMappingURL=order-create.js.map