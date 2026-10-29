(function() {
	//#region platform/plugins/ecommerce/resources/js/dashboard-widgets.js
	$(() => {
		BDashboard.loadWidget($("#widget_ecommerce_report_general").find(".widget-content"), route("ecommerce.report.dashboard-widget.general"));
	});
	//#endregion
})();

//# sourceMappingURL=dashboard-widgets.js.map