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
	//#region platform/core/table/resources/js/filter.js
	var TableFilter = class {
		constructor() {
			_defineProperty(this, "$filterForm", $(document).find("form.filter-form"));
			_defineProperty(this, "$table", this.$filterForm.closest(".table-wrapper").find("table"));
		}
		loadData($element) {
			$httpClient.make().get($(".filter-data-url").val(), {
				class: $(".filter-data-class").val(),
				key: $element.val(),
				value: $element.closest(".filter-item").find(".filter-column-value").val()
			}).then(({ data: res }) => {
				const data = $.map(res.data, (value, key) => {
					return {
						id: key,
						name: value
					};
				});
				$element.closest(".filter-item").find(".filter-column-value-wrap").html(res.html);
				const $input = $element.closest(".filter-item").find(".filter-column-value");
				if ($input.length && $input.prop("type") === "text") {
					$input.typeahead({ source: data });
					$input.data("typeahead").source = data;
				}
				Botble.initResources();
			});
		}
		init() {
			const that = this;
			$.each($(".filter-items-wrap .filter-column-key"), (index, element) => {
				if ($(element).val()) that.loadData($(element));
			});
			this.$filterForm.on("change", ".filter-column-key", (event) => {
				that.loadData($(event.currentTarget));
			}).on("click", ".add-more-filter", () => {
				const html = $(document).find(".sample-filter-item-wrap").html();
				$(document).find(".filter-items-wrap").append(html.replace("<script>", "").replace("<\\/script>", ""));
				Botble.initResources();
				const element = $(document).find(".filter-items-wrap .filter-item:last-child").find(".filter-column-key");
				if ($(element).val()) that.loadData(element);
			}).on("click", ".btn-remove-filter-item", (event) => {
				event.preventDefault();
				const $currentTarget = $(event.currentTarget);
				$currentTarget.closest(".filter-item").remove();
				$currentTarget.tooltip("hide");
			}).on("click", ".btn-apply", (event) => {
				event.preventDefault();
				const form = $(event.currentTarget).closest("form.filter-form");
				this.$filterForm.find("[data-bb-toggle=\"datatable-reset-filter\"]").show();
				const url = new URL(window.location.href);
				const params = new URLSearchParams(url.search);
				const data = form.serializeArray();
				const paramsKey = {};
				$.each(data, (index, item) => {
					let keyName = item.name;
					if (typeof keyName === "string" && keyName.endsWith("[]")) {
						let keyValue = paramsKey[keyName] = paramsKey[keyName] || 0;
						params.set(`${keyName.replace("[]", `[${keyValue}]`)}`, item.value);
						paramsKey[keyName]++;
					} else params.set(item.name, item.value);
				});
				window.history.pushState({}, "", `${url.pathname}?${params.toString()}`);
				this.reloadDatatable(this.$table.DataTable());
			}).on("click", "[data-bb-toggle=\"datatable-reset-filter\"]", (event) => {
				event.preventDefault();
				this.$filterForm.find(".form-filter:not(.filter-item-default)").remove();
				this.$filterForm.find(".filter-item").find(".filter-column-key").val("").trigger("change");
				this.$filterForm.find(".filter-item").find(".filter-column-operator").val("=");
				this.$filterForm.find(".filter-item").find(".filter-column-value").val("");
				$(event.currentTarget).hide();
				const url = new URL(window.location.href);
				window.history.pushState({}, "", url.pathname);
				this.reloadDatatable(this.$table.DataTable());
			});
		}
		reloadDatatable(datatable) {
			datatable.ajax.url(window.location.href).load();
		}
	};
	$(() => {
		new TableFilter().init();
	});
	//#endregion
})();

//# sourceMappingURL=filter.js.map