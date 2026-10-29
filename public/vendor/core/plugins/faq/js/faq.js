"use strict";
(function() {
	//#region platform/plugins/faq/resources/js/faq.js
	$(() => {
		$(document).on("click", "[data-bb-toggle=\"select-from-existing\"]", (e) => {
			e.preventDefault();
			$(".existing-faq-schema-items").show();
		});
	});
	//#endregion
})();

//# sourceMappingURL=faq.js.map