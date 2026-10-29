(function() {
	//#region platform/plugins/ecommerce/resources/js/invoice.js
	$(() => {
		$(document).on("click", ".invoice-generate", (event) => {
			event.preventDefault();
			const _self = $(event.currentTarget);
			const url = $(_self.find("span[data-trigger]")).data("url");
			$httpClient.make().withButtonLoading(_self).get(url).then(({ data }) => {
				Botble.showSuccess(data.message);
				window.LaravelDataTables["botble-ecommerce-tables-invoice-table"].draw();
			});
		});
	});
	//#endregion
})();

//# sourceMappingURL=invoice.js.map