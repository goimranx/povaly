"use strict";
(function() {
	//#region platform/plugins/payment/resources/js/payment.js
	var BPayment = BPayment || {};
	BPayment.initResources = function() {
		let paymentMethod = $(document).find("input[name=payment_method]:checked").first();
		if (!paymentMethod.length) {
			paymentMethod = $(document).find("input[name=payment_method]").first();
			paymentMethod.trigger("click").trigger("change");
		}
		if (paymentMethod.length) paymentMethod.closest(".list-group-item").find(".payment_collapse_wrap").addClass("show");
		if ($(".stripe-card-wrapper").length > 0) new Card({
			form: ".payment-checkout-form",
			container: ".stripe-card-wrapper",
			formSelectors: {
				numberInput: "input#stripe-number",
				expiryInput: "input#stripe-exp",
				cvcInput: "input#stripe-cvc",
				nameInput: "input#stripe-name"
			},
			width: 350,
			formatting: true,
			messages: {
				validDate: "valid\ndate",
				monthYear: "mm/yyyy"
			},
			placeholders: {
				number: "•••• •••• •••• ••••",
				name: "Full Name",
				expiry: "••/••",
				cvc: "•••"
			},
			masks: { cardNumber: "•" },
			debug: false
		});
	};
	BPayment.init = function() {
		BPayment.initResources();
		$(document).on("change", ".js_payment_method", function(event) {
			event.preventDefault();
			$(".payment_collapse_wrap").removeClass("collapse").removeClass("show").removeClass("active");
			$(event.currentTarget).closest(".list-group-item").find(".payment_collapse_wrap").addClass("show").addClass("active");
			document.dispatchEvent(new CustomEvent("payment:method-selected", { detail: { method: $(event.currentTarget).val() } }));
		});
		$(document).off("click", ".payment-checkout-btn").on("click", ".payment-checkout-btn", function(event) {
			event.preventDefault();
			const button = $(event.currentTarget);
			const form = button.closest("form");
			const submitInitialText = button.html();
			const paymentMethod = $("input[name=payment_method]:checked").val();
			if (form.valid && !form.valid()) {
				document.dispatchEvent(new CustomEvent("payment:validation-failed"));
				return;
			}
			document.dispatchEvent(new CustomEvent("payment:before-submit", { detail: { method: paymentMethod } }));
			button.prop("disabled", true);
			button.html(`<span class="spinner-border spinner-border-sm me-2" role="status"></span> ${button.data("processing-text")}`);
			if (paymentMethod === "stripe" && $(".stripe-card-wrapper").length > 0) {
				Stripe.setPublishableKey($("#payment-stripe-key").data("value"));
				Stripe.card.createToken(form, function(status, response) {
					if (response.error) {
						if (typeof Botble != "undefined") Botble.showError(response.error.message, button.data("error-header"));
						else alert(response.error.message);
						button.prop("disabled", false);
						button.html(submitInitialText);
						document.dispatchEvent(new CustomEvent("payment:error", { detail: {
							method: "stripe",
							error: response.error.message
						} }));
					} else {
						form.append($("<input type=\"hidden\" name=\"stripeToken\">").val(response.id));
						document.dispatchEvent(new CustomEvent("payment:submitting", { detail: { method: "stripe" } }));
						form.submit();
					}
				});
			} else {
				document.dispatchEvent(new CustomEvent("payment:submitting", { detail: { method: paymentMethod } }));
				form.submit();
			}
		});
		document.dispatchEvent(new CustomEvent("payment:init"));
	};
	$(document).ready(function() {
		BPayment.init();
		document.addEventListener("payment-form-reloaded", function() {
			BPayment.initResources();
		});
	});
	//#endregion
})();

//# sourceMappingURL=payment.js.map