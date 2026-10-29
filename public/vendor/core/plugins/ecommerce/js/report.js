(function(vue) {
	//#region \0plugin-vue:export-helper
	var _plugin_vue_export_helper_default = (sfc, props) => {
		const target = sfc.__vccOpts || sfc;
		for (const [key, val] of props) target[key] = val;
		return target;
	};
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/SalesReportsChart.vue
	var _sfc_main$1 = {
		props: {
			url: {
				type: String,
				default: null,
				required: true
			},
			date_from: {
				type: String,
				default: null,
				required: true
			},
			date_to: {
				type: String,
				default: null,
				required: true
			},
			format: {
				type: String,
				default: "dd/MM/yy",
				required: false
			},
			filters: {
				type: Array,
				default: () => [],
				required: false
			},
			filterDefault: {
				type: String,
				default: "",
				required: false
			}
		},
		data: () => {
			return {
				isLoading: true,
				earningSales: [],
				colors: ["#fcb800", "#80bc00"],
				chart: null,
				filtering: "",
				chartFromDate: null,
				chartToDate: null
			};
		},
		mounted: function() {
			this.setFiltering();
			this.chartFromDate = this.date_from;
			this.chartToDate = this.date_to;
			this.renderChart();
			$event.on("sales-report-chart:reload", (data) => {
				this.chartFromDate = data.date_from;
				this.chartToDate = data.date_to;
				this.renderChart();
			});
		},
		methods: {
			setFiltering: function(f = "") {
				if (!f) f = this.filterDefault;
				if (this.filters.length) {
					const filter = this.filters.find((x) => x.key == f);
					if (filter) this.filtering = filter.text;
					else this.filtering = f;
				}
			},
			renderChart: function() {
				if (this.url) axios.get(this.url + "?date_from=" + this.chartFromDate + "&date_to=" + this.chartToDate).then((res) => {
					if (res.data.error) Botble.showError(res.data.message);
					else {
						this.earningSales = res.data.data.earningSales;
						const series = res.data.data.series;
						const colors = res.data.data.colors;
						const categories = res.data.data.dates;
						if (this.chart === null) {
							this.chart = new ApexCharts(this.$el.querySelector(".sales-reports-chart"), {
								series,
								chart: {
									height: 350,
									type: "area",
									toolbar: { show: false }
								},
								dataLabels: { enabled: false },
								stroke: { curve: "smooth" },
								colors,
								xaxis: {
									type: "datetime",
									categories
								},
								tooltip: { x: { format: this.format } },
								noData: { text: BotbleVariables.languages.tables.no_data }
							});
							this.chart.render();
						} else this.chart.updateOptions({
							series,
							colors,
							xaxis: {
								type: "datetime",
								categories
							}
						});
					}
				});
			},
			clickFilter: function(filter, event) {
				event.preventDefault();
				this.setFiltering("...");
				const that = this;
				axios.get(that.url + "?date_from=" + this.chartFromDate + "&date_to=" + this.chartToDate, { params: { filter } }).then((res) => {
					if (res.data.error) Botble.showError(res.data.message);
					else {
						that.earningSales = res.data.data.earningSales;
						const options = {
							xaxis: {
								type: "datetime",
								categories: res.data.data.dates
							},
							series: res.data.data.series
						};
						if (res.data.data.colors) options.colors = res.data.data.colors;
						this.chart.updateOptions(options);
					}
					this.setFiltering(filter);
				});
			}
		}
	};
	var _hoisted_1$1 = {
		key: 0,
		class: "btn-group d-block text-end"
	};
	var _hoisted_2 = {
		class: "btn btn-sm btn-secondary",
		href: "javascript:",
		"data-bs-toggle": "dropdown",
		"aria-expanded": "false"
	};
	var _hoisted_3 = { class: "dropdown-menu float-end" };
	var _hoisted_4 = ["onClick"];
	var _hoisted_5 = {
		key: 1,
		class: "row px-3"
	};
	var _hoisted_6 = { class: "col-12" };
	var _hoisted_7 = { class: "list-unstyled" };
	function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", null, [
			$props.filters.length ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_1$1, [(0, vue.createElementVNode)("a", _hoisted_2, [
				_cache[0] || (_cache[0] = (0, vue.createElementVNode)("i", {
					class: "fa fa-filter",
					"aria-hidden": "true"
				}, null, -1)),
				(0, vue.createElementVNode)("span", null, (0, vue.toDisplayString)(_ctx.filtering), 1),
				_cache[1] || (_cache[1] = (0, vue.createElementVNode)("i", { class: "fa fa-angle-down" }, null, -1))
			]), (0, vue.createElementVNode)("ul", _hoisted_3, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)($props.filters, (filter) => {
				return (0, vue.openBlock)(), (0, vue.createElementBlock)("li", { key: filter.key }, [(0, vue.createElementVNode)("a", {
					href: "#",
					onClick: ($event) => $options.clickFilter(filter.key, $event)
				}, (0, vue.toDisplayString)(filter.text), 9, _hoisted_4)]);
			}), 128))])])) : (0, vue.createCommentVNode)("v-if", true),
			_cache[2] || (_cache[2] = (0, vue.createElementVNode)("div", { class: "sales-reports-chart" }, null, -1)),
			_ctx.earningSales.length ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_5, [(0, vue.createElementVNode)("div", _hoisted_6, [(0, vue.createElementVNode)("ul", _hoisted_7, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.earningSales, (earningSale) => {
				return (0, vue.openBlock)(), (0, vue.createElementBlock)("li", { key: earningSale.text }, [(0, vue.createElementVNode)("i", {
					class: "icon ti ti-circle-filled",
					style: (0, vue.normalizeStyle)({ color: earningSale.color })
				}, null, 4), (0, vue.createTextVNode)(" " + (0, vue.toDisplayString)(earningSale.text), 1)]);
			}), 128))])])])) : (0, vue.createCommentVNode)("v-if", true),
			_cache[3] || (_cache[3] = (0, vue.createElementVNode)("div", { class: "loading" }, null, -1))
		]);
	}
	var SalesReportsChart_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main$1, [["render", _sfc_render$1]]);
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/RevenueChart.vue
	var { nextTick } = Vue;
	var _sfc_main = {
		props: { data: {
			type: Array,
			default: () => [],
			required: true
		} },
		data() {
			return {
				chartData: this.data,
				chartInstance: null
			};
		},
		mounted() {
			this.renderChart();
			$event.on("revenue-chart:reload", (data) => {
				this.chartData = data;
				this.renderChart();
			});
		},
		methods: { async renderChart() {
			if (!this.chartData.length) return;
			let series = [];
			let colors = [];
			const labels = [];
			let total = 0;
			this.chartData.map((x) => {
				total += parseFloat(x.value);
				labels.push(x.label);
				colors.push(x.color);
			});
			if (total === 0) this.chartData.map(() => {
				series.push(0);
			});
			else this.chartData.map((x) => {
				series.push(100 / total * parseFloat(x.value));
			});
			if (this.chartInstance === null) {
				this.chartInstance = new ApexCharts(this.$refs.chartRef, {
					series,
					colors,
					chart: {
						height: "250",
						type: "donut"
					},
					chartOptions: { labels },
					plotOptions: { pie: {
						donut: {
							size: "71%",
							polygons: { strokeWidth: 0 }
						},
						expandOnClick: true
					} },
					states: { hover: { filter: {
						type: "darken",
						value: .9
					} } },
					dataLabels: { enabled: false },
					legend: { show: false },
					tooltip: { enabled: false }
				});
				this.chartInstance.render();
			} else this.chartInstance.updateOptions({
				series,
				colors,
				chartOptions: { labels }
			});
			if (jQuery && jQuery().tooltip) $("[data-bs-toggle=\"tooltip\"]").tooltip({
				placement: "top",
				boundary: "window"
			});
		} }
	};
	var _hoisted_1 = {
		ref: "chartRef",
		class: "revenue-chart"
	};
	function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", null, [(0, vue.createElementVNode)("div", _hoisted_1, null, 512)]);
	}
	var RevenueChart_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main, [["render", _sfc_render]]);
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/report.js
	if (typeof vueApp !== "undefined") vueApp.booting((vue) => {
		vue.component("sales-reports-chart", SalesReportsChart_default);
		vue.component("revenue-chart", RevenueChart_default);
	});
	$(() => {
		if (!window.moment || !jQuery().daterangepicker) return;
		moment.locale($("html").attr("lang"));
		let $dateRange = $(document).find(".date-range-picker");
		let dateFormat = $dateRange.data("format") || "YYYY-MM-DD";
		let startDate = $dateRange.data("start-date") || moment().subtract(29, "days");
		let today = moment();
		let endDate = moment().endOf("month");
		if (endDate > today) endDate = today;
		let rangesTrans = BotbleVariables.languages.reports;
		let ranges = {
			[rangesTrans.today]: [today, today],
			[rangesTrans.this_week]: [moment().startOf("week"), today],
			[rangesTrans.last_7_days]: [moment().subtract(6, "days"), today],
			[rangesTrans.last_30_days]: [moment().subtract(29, "days"), today],
			[rangesTrans.this_month]: [moment().startOf("month"), endDate],
			[rangesTrans.this_year]: [moment().startOf("year"), moment().endOf("year")]
		};
		$dateRange.daterangepicker({
			ranges,
			alwaysShowCalendars: true,
			startDate,
			endDate,
			maxDate: endDate,
			opens: "left",
			drops: "auto",
			locale: { format: dateFormat },
			autoUpdateInput: false
		}, function(start, end, label) {
			$.ajax({
				url: $dateRange.data("href"),
				data: {
					date_from: start.format("YYYY-MM-DD"),
					date_to: end.format("YYYY-MM-DD"),
					predefined_range: label
				},
				type: "GET",
				success: (data) => {
					if (data.error) Botble.showError(data.message);
					else {
						const newUrl = new URL(window.location.href);
						newUrl.searchParams.set("date_from", start.format("YYYY-MM-DD"));
						newUrl.searchParams.set("date_to", end.format("YYYY-MM-DD"));
						history.pushState({ urlPath: newUrl.href }, "", newUrl.href);
						if (!$("#report-stats-content").length) window.location.reload();
						else $(".widget-item").each((key, widget) => {
							const widgetEl = $(widget).prop("id");
							if (widgetEl) $(`#${widgetEl}`).replaceWith($(data.data).find(`#${widgetEl}`));
						});
						let formatValue = $dateRange.data("format-value");
						if (!formatValue) formatValue = "__from__ - __to__";
						let labelValue = formatValue.replace("__from__", start.format(dateFormat)).replace("__to__", end.format(dateFormat));
						$dateRange.find("span").text(labelValue);
						if (window.LaravelDataTables) Object.keys(window.LaravelDataTables).map((key) => {
							let table = window.LaravelDataTables[key];
							let url = new URL(table.ajax.url());
							url.searchParams.set("date_from", start.format("YYYY-MM-DD"));
							url.searchParams.set("date_to", end.format("YYYY-MM-DD"));
							table.ajax.url(url.href).load();
						});
					}
				},
				error: (data) => {
					Botble.handleError(data);
				}
			});
		});
		$dateRange.on("apply.daterangepicker", function(ev, picker) {
			let $this = $(this);
			let formatValue = $this.data("format-value");
			if (!formatValue) formatValue = "__from__ - __to__";
			let value = formatValue.replace("__from__", picker.startDate.format(dateFormat)).replace("__to__", picker.endDate.format(dateFormat));
			$this.find("span").text(value);
		});
	});
	//#endregion
})(Vue);

//# sourceMappingURL=report.js.map