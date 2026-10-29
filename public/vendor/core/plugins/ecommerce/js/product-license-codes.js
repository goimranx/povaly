(function() {
	//#region platform/plugins/ecommerce/resources/js/product-license-codes.js
	$(() => {
		const $container = $(".license-codes-container");
		if (!$container.length) return;
		const routes = $container.data("routes");
		const confirmDeleteMessage = $container.data("confirm-delete");
		const confirmBulkDeleteMessage = $container.data("confirm-bulk-delete");
		function updateBulkActions() {
			const selectedCount = $(".license-code-checkbox:checked").length;
			const $wrapper = $(".bulk-actions-wrapper");
			$wrapper.find(".selected-count").text(selectedCount);
			if (selectedCount > 0) $wrapper.show();
			else $wrapper.hide();
		}
		$(".select-all-checkbox").on("change", function() {
			const isChecked = $(this).prop("checked");
			$(".license-code-checkbox").prop("checked", isChecked);
			updateBulkActions();
		});
		$(document).on("change", ".license-code-checkbox", function() {
			const totalCheckboxes = $(".license-code-checkbox").length;
			const checkedCheckboxes = $(".license-code-checkbox:checked").length;
			$(".select-all-checkbox").prop("checked", totalCheckboxes === checkedCheckboxes);
			updateBulkActions();
		});
		$(".btn-bulk-delete").on("click", function() {
			const selectedIds = $(".license-code-checkbox:checked").map(function() {
				return $(this).data("id");
			}).get();
			if (selectedIds.length === 0) return;
			if (confirm(confirmBulkDeleteMessage)) {
				const $button = $(this);
				Botble.showButtonLoading($button);
				$httpClient.make().delete(routes.bulkDelete, { ids: selectedIds }).then(({ data }) => {
					if (data.error) Botble.showError(data.message);
					else {
						Botble.showSuccess(data.message);
						location.reload();
					}
				}).catch((error) => {
					Botble.handleError(error);
				}).finally(() => {
					Botble.hideButtonLoading($button);
				});
			}
		});
		$("#add-license-code-form").on("submit", function(e) {
			e.preventDefault();
			const $form = $(this);
			const $button = $form.find("button[type=\"submit\"]");
			Botble.showButtonLoading($button);
			$httpClient.make().postForm(routes.store, new FormData($form[0])).then(({ data }) => {
				if (data.error) Botble.showError(data.message);
				else {
					Botble.showSuccess(data.message);
					location.reload();
				}
			}).catch((error) => {
				Botble.handleError(error);
			}).finally(() => {
				Botble.hideButtonLoading($button);
			});
		});
		$(document).on("click", ".edit-license-code-btn", function() {
			const id = $(this).data("license-code-id");
			const code = $(this).data("license-code");
			$("#edit-license-code-id").val(id);
			$("#edit-license-code").val(code);
			$("#edit-license-code-modal").modal("show");
		});
		$("#edit-license-code-form").on("submit", function(e) {
			e.preventDefault();
			const $form = $(this);
			const $button = $form.find("button[type=\"submit\"]");
			const id = $("#edit-license-code-id").val();
			Botble.showButtonLoading($button);
			$httpClient.make().postForm(routes.update.replace("__ID__", id), new FormData($form[0])).then(({ data }) => {
				if (data.error) Botble.showError(data.message);
				else {
					Botble.showSuccess(data.message);
					location.reload();
				}
			}).catch((error) => {
				Botble.handleError(error);
			}).finally(() => {
				Botble.hideButtonLoading($button);
			});
		});
		$(document).on("click", ".delete-license-code-btn", function() {
			const id = $(this).data("license-code-id");
			if (confirm(confirmDeleteMessage)) $httpClient.make().delete(routes.destroy.replace("__ID__", id)).then(({ data }) => {
				if (data.error) Botble.showError(data.message);
				else {
					Botble.showSuccess(data.message);
					location.reload();
				}
			}).catch((error) => {
				Botble.handleError(error);
			});
		});
		$("#bulk-generate-form").on("submit", function(e) {
			e.preventDefault();
			const $form = $(this);
			const $button = $form.find("button[type=\"submit\"]");
			Botble.showButtonLoading($button);
			$httpClient.make().postForm(routes.bulkGenerate, new FormData($form[0])).then(({ data }) => {
				if (data.error) Botble.showError(data.message);
				else {
					Botble.showSuccess(data.message);
					location.reload();
				}
			}).catch((error) => {
				Botble.handleError(error);
			}).finally(() => {
				Botble.hideButtonLoading($button);
			});
		});
		$("#license-code-format").on("change", function() {
			if ($(this).val() === "custom") $("#custom-pattern-group").show();
			else $("#custom-pattern-group").hide();
		});
	});
	//#endregion
})();

//# sourceMappingURL=product-license-codes.js.map