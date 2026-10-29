(function() {
	//#region platform/plugins/marketplace/resources/js/vendor-dashboard/marketplace-vendor.js
	$(() => {
		$(document).on("click", "#confirm-vendor-button", (event) => {
			event.preventDefault();
			const $button = $(event.currentTarget);
			const $form = $button.closest("form");
			const $modal = $button.closest(".modal");
			$httpClient.make().withButtonLoading($button).post($form.prop("action"), $form.serialize()).then(({ data }) => {
				$modal.modal("hide");
				if (data.error) Botble.showError(data.message);
				else {
					Botble.showSuccess(data.message);
					setTimeout(() => {
						window.location.href = route("marketplace.unverified-vendors.index");
					}, 3e3);
				}
			});
		});
	});
	//#endregion
})();

//# sourceMappingURL=marketplace-vendor.js.map