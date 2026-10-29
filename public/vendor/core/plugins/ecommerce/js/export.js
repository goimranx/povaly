(function() {
	//#region platform/plugins/ecommerce/resources/js/export.js
	$(() => {
		$(document).on("click", "[data-bb-toggle=\"data-export\"]", function(event) {
			event.preventDefault();
			const _self = $(event.currentTarget);
			$httpClient.make().withButtonLoading(_self).withLoading(_self.closest(".card")).withResponseType("blob").post(_self.attr("href")).then(({ data }) => {
				let a = document.createElement("a");
				let url = window.URL.createObjectURL(data);
				a.href = url;
				a.download = _self.data("filename");
				document.body.append(a);
				a.click();
				a.remove();
				window.URL.revokeObjectURL(url);
			});
		});
	});
	//#endregion
})();

//# sourceMappingURL=export.js.map