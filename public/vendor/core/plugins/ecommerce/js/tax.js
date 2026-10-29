(function() {
	//#region platform/plugins/ecommerce/resources/js/tax.js
	$(() => {
		const spinner = `<div class='w-100 text-center py-3'><div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div></div>`;
		const taxRuleTable = "ecommerce-tax-rule-table";
		const $ruleModal = $(".create-tax-rule-form-modal");
		const $ruleModalBody = $ruleModal.find(".modal-body");
		const $ruleModalTitle = $ruleModal.find(".modal-title strong");
		const $taxModal = $(".create-tax-form-modal");
		const $taxModalBody = $taxModal.find(".modal-body");
		const $taxModalTitle = $taxModal.find(".modal-title strong");
		const resetRuleModal = () => {
			$ruleModalBody.html(spinner);
			$ruleModalTitle.text("...");
		};
		const setRuleModal = (res) => {
			$ruleModalBody.html(res.data.html);
			$ruleModalTitle.text(res.message || "...");
		};
		const resetTaxModal = () => {
			$taxModalBody.html(spinner);
			$taxModalTitle.text("...");
		};
		const setTaxModal = (res) => {
			$taxModalBody.html(res.data.html);
			$taxModalTitle.text(res.message || "...");
		};
		const refreshPage = () => {
			if (window.LaravelDataTables && window.LaravelDataTables[taxRuleTable]) LaravelDataTables[taxRuleTable].draw();
			else window.location.reload();
		};
		$ruleModal.on("show.bs.modal", function() {
			resetRuleModal();
		});
		$taxModal.on("show.bs.modal", function() {
			resetTaxModal();
		});
		$(document).on("click", ".btn-create-tax", function(e) {
			e.preventDefault();
			const $this = $(e.currentTarget);
			$taxModal.modal("show");
			$.ajax({
				url: $this.data("href"),
				success: (res) => {
					if (res.error == false) {
						setTaxModal(res);
						Botble.initResources();
					} else Botble.showError(res.message);
				},
				error: (res) => {
					Botble.handleError(res);
				}
			});
		});
		$(document).on("click", ".btn-edit-tax", function(e) {
			e.preventDefault();
			const $this = $(e.currentTarget);
			$taxModal.modal("show");
			$.ajax({
				url: $this.prop("href"),
				success: (res) => {
					if (res.error == false) {
						setTaxModal(res);
						Botble.initResources();
					} else Botble.showError(res.message);
				},
				error: (res) => {
					Botble.handleError(res);
				}
			});
		});
		$(document).on("submit", "#ecommerce-tax-form", function(e) {
			e.preventDefault();
			const $this = $(e.currentTarget);
			$.ajax({
				url: $this.prop("action"),
				method: "POST",
				data: $this.serializeArray(),
				success: (res) => {
					if (res.error == false) {
						$taxModal.modal("hide");
						Botble.showSuccess(res.message);
						refreshPage();
					} else Botble.showError(res.message);
				},
				error: (res) => {
					Botble.handleError(res);
				}
			});
		});
		$(document).off("click", ".create-tax-rule-item").on("click", ".create-tax-rule-item", function(e) {
			e.preventDefault();
			const $this = $(e.currentTarget);
			$ruleModal.modal("show");
			let url = $this.find("[data-action=create]").data("href") || $this.attr("href");
			$.ajax({
				url,
				success: (res) => {
					if (res.error == false) {
						setRuleModal(res);
						Botble.initResources();
					} else Botble.showError(res.message);
				},
				error: (res) => {
					Botble.handleError(res);
				}
			});
		});
		$(document).on("click", "#" + taxRuleTable + " .btn-edit-item", function(e) {
			e.preventDefault();
			const $this = $(e.currentTarget);
			$ruleModal.modal("show");
			$.ajax({
				url: $this.prop("href"),
				success: (res) => {
					if (res.error == false) {
						setRuleModal(res);
						Botble.initResources();
					} else Botble.showError(res.message);
				},
				error: (res) => {
					Botble.handleError(res);
				}
			});
		});
		$(document).on("click", ".btn-edit-tax-rule", function(e) {
			e.preventDefault();
			const $this = $(e.currentTarget);
			$ruleModal.modal("show");
			$.ajax({
				url: $this.prop("href"),
				success: (res) => {
					if (res.error == false) {
						setRuleModal(res);
						Botble.initResources();
					} else Botble.showError(res.message);
				},
				error: (res) => {
					Botble.handleError(res);
				}
			});
		});
		$(document).on("click", ".btn-delete-tax-rule", function(e) {
			e.preventDefault();
			const url = $(e.currentTarget).data("url");
			if (!confirm("Are you sure you want to delete this tax rule?")) return;
			$.ajax({
				url,
				method: "DELETE",
				headers: { "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content") },
				success: (res) => {
					if (res.error == false) {
						Botble.showSuccess(res.message);
						refreshPage();
					} else Botble.showError(res.message);
				},
				error: (res) => {
					Botble.handleError(res);
				}
			});
		});
		$(document).on("click", ".btn-set-default-tax", function(e) {
			e.preventDefault();
			const url = $(e.currentTarget).data("url");
			$.ajax({
				url,
				method: "POST",
				headers: { "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content") },
				success: (res) => {
					if (res.error == false) {
						Botble.showSuccess(res.message);
						refreshPage();
					} else Botble.showError(res.message);
				},
				error: (res) => {
					Botble.handleError(res);
				}
			});
		});
		$(document).on("click", ".btn-delete-tax", function(e) {
			e.preventDefault();
			const url = $(e.currentTarget).data("url");
			if (!confirm("Are you sure you want to delete this tax?")) return;
			$.ajax({
				url,
				method: "DELETE",
				headers: { "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content") },
				success: (res) => {
					if (res.error == false) {
						Botble.showSuccess(res.message);
						refreshPage();
					} else Botble.showError(res.message);
				},
				error: (res) => {
					Botble.handleError(res);
				}
			});
		});
		$(document).on("submit", "#ecommerce-tax-rule-form", function(e) {
			e.preventDefault();
			const $this = $(e.currentTarget);
			$.ajax({
				url: $this.prop("action"),
				method: "POST",
				data: $this.serializeArray(),
				success: (res) => {
					if (res.error == false) {
						$ruleModal.modal("hide");
						Botble.showSuccess(res.message);
						refreshPage();
					} else Botble.showError(res.message);
				},
				error: (res) => {
					Botble.handleError(res);
				}
			});
		});
	});
	//#endregion
})();

//# sourceMappingURL=tax.js.map