"use strict";
(function() {
	//#region platform/core/base/resources/js/license-activation.js
	$(() => {
		$(document).on("submit", "[data-bb-toggle=\"activate-license\"]", function(e) {
			e.preventDefault();
			const $form = $(this);
			const formData = new FormData(e.currentTarget);
			Botble.showLoading($form[0]);
			$httpClient.make().postForm($form.prop("action"), formData).then(({ data }) => {
				Botble.showSuccess(data.message);
				localStorage.removeItem("license_check_time");
				if ($form.data("reload")) {
					setTimeout(() => {
						window.location.reload();
					}, 1e3);
					return;
				}
				let redirect = $form.data("redirect");
				if (redirect) window.location.assign(redirect);
			}).finally(() => {
				Botble.hideLoading($form[0]);
			});
		});
	});
	//#endregion
})();

//# sourceMappingURL=license-activation.js.map