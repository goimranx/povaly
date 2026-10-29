"use strict";
(function() {
	//#region platform/plugins/ecommerce/resources/js/front/order-return.js
	(function($) {
		$(document).on("change", ".select-return-item-qty", function(e) {
			const $this = $(e.currentTarget);
			const $option = $this.find(":selected");
			if ($option.length) $this.closest(".return-product-controls").find(".return-amount").html($option.data("amount"));
		});
		$(document).ready(function() {
			const $form = $("#order-return-request-form");
			if (!$form.length) return;
			initImageUpload($form);
			initFormValidation($form);
		});
		function initImageUpload($form) {
			const $fileInput = $form.find(".return-image-file-input");
			const $previewList = $form.find(".return-image-preview-list");
			const $uploadBox = $form.find(".return-image-upload-box");
			if (!$fileInput.length || !$previewList.length) return;
			const maxFiles = parseInt($fileInput.data("max-files")) || 3;
			const maxSize = parseInt($fileInput.data("max-size")) || 2048;
			const maxSizeMessage = $fileInput.data("max-size-message") || "File is too large";
			const maxFilesMessage = $fileInput.data("max-files-message") || "Maximum files exceeded";
			let files = new DataTransfer();
			function updateListState() {
				const count = $previewList.find(".return-image-preview-item").length;
				if (count > 0) $previewList.addClass("has-images");
				else $previewList.removeClass("has-images");
				if (count >= maxFiles) $uploadBox.hide();
				else $uploadBox.show();
			}
			function showError(message) {
				const $errorContainer = $form.find(".return-image-error");
				if ($errorContainer.length) {
					$errorContainer.text(message).show();
					setTimeout(function() {
						$errorContainer.fadeOut();
					}, 5e3);
				} else alert(message);
			}
			$fileInput.on("change", function(e) {
				const newFiles = Array.from(e.target.files);
				const currentCount = $previewList.find(".return-image-preview-item").length;
				newFiles.forEach(function(file, index) {
					if (currentCount + index >= maxFiles) {
						showError(maxFilesMessage.replace(":max", maxFiles));
						return;
					}
					if (file.size > maxSize * 1024) {
						showError(maxSizeMessage.replace(":attribute", file.name).replace(":max", maxSize + "KB"));
						return;
					}
					const reader = new FileReader();
					reader.onload = function(event) {
						const template = $("#return-image-template").html();
						const fileId = Date.now() + "_" + index;
						const $item = $(template.replace(/__id__/g, fileId));
						$item.find("img").attr("src", event.target.result);
						$item.data("file-id", fileId);
						$previewList.find(".return-image-upload-box").before($item);
						files.items.add(file);
						$fileInput[0].files = files.files;
						updateListState();
					};
					reader.readAsDataURL(file);
				});
				e.target.value = "";
			});
			$previewList.on("click", ".return-image-remove-btn", function(e) {
				e.preventDefault();
				e.stopPropagation();
				const $item = $(this).closest(".return-image-preview-item");
				const index = $previewList.find(".return-image-preview-item").index($item);
				$item.remove();
				const newFiles = new DataTransfer();
				Array.from(files.files).forEach(function(file, i) {
					if (i !== index) newFiles.items.add(file);
				});
				files = newFiles;
				$fileInput[0].files = files.files;
				updateListState();
			});
		}
		function initFormValidation($form) {
			$form.on("submit", function(e) {
				let hasErrors = false;
				const errors = [];
				$form.find(".validation-error").remove();
				$form.find(".is-invalid").removeClass("is-invalid");
				const $reasonSelect = $form.find(".order-return-reason-select");
				if ($reasonSelect.length && !$reasonSelect.val()) {
					hasErrors = true;
					$reasonSelect.addClass("is-invalid");
					showFieldError($reasonSelect, $reasonSelect.data("error-message") || "Please select a return reason.");
					errors.push("reason");
				}
				$form.find(".return-product-controls select[name*=\"[reason]\"]").each(function() {
					const $select = $(this);
					if ($select.closest(".return-product-card").find("input[type=\"checkbox\"]").is(":checked") && !$select.val()) {
						hasErrors = true;
						$select.addClass("is-invalid");
						showFieldError($select, $select.data("error-message") || "Please select a return reason.");
						errors.push("item-reason");
					}
				});
				if ($form.find(".return-products-list input[type=\"checkbox\"]:checked").length === 0) {
					hasErrors = true;
					showFieldError($form.find(".return-products-list"), "Please select at least one product to return.");
					errors.push("products");
				}
				const $fileInput = $form.find(".return-image-file-input");
				if ($fileInput.length) {
					const maxFiles = parseInt($fileInput.data("max-files")) || 3;
					if ($fileInput[0].files.length > maxFiles) {
						hasErrors = true;
						const maxFilesMessage = $fileInput.data("max-files-message") || "The Return Images must not have more than :max items.";
						showFieldError($form.find(".return-image-upload-wrapper"), maxFilesMessage.replace(":max", maxFiles));
						errors.push("images");
					}
				}
				if (hasErrors) {
					e.preventDefault();
					const $firstError = $form.find(".validation-error:first, .is-invalid:first").first();
					if ($firstError.length) $("html, body").animate({ scrollTop: $firstError.offset().top - 100 }, 300);
				}
			});
			$form.on("change", "select, input", function() {
				const $field = $(this);
				$field.removeClass("is-invalid");
				$field.closest(".form-group, .mb-2, .mb-3").find(".validation-error").remove();
			});
		}
		function showFieldError($field, message) {
			const $error = $("<div class=\"validation-error text-danger small mt-1\"></div>").text(message);
			const $container = $field.closest(".form-group, .mb-2, .mb-3, .return-images-section, .products-selection-section");
			if ($container.length) $container.append($error);
			else $field.after($error);
		}
	})(jQuery);
	//#endregion
})();

//# sourceMappingURL=order-return.js.map