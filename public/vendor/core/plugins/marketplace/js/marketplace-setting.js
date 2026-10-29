(function() {
	//#region platform/plugins/marketplace/resources/js/marketplace-setting.js
	var MarketplaceSetting = class {
		constructor() {
			this.eventListeners();
		}
		eventListeners() {
			$(document).on("click", "[data-bb-toggle=\"commission-category-add\"]", (event) => {
				event.preventDefault();
				event.stopPropagation();
				this.addNewCommissionSetting(event.currentTarget);
				return false;
			});
			this.initRemoveCommissionEvent();
			document.querySelectorAll(".tagify-commission-setting").forEach((element) => {
				this.tagify(element);
			});
		}
		tagify(element) {
			const self = this;
			new Tagify(element, {
				delimiters: null,
				enforceWhitelist: true,
				whitelist: self.formatWhitelist(),
				dropdown: {
					enabled: 1,
					classname: "extra-properties",
					searchBy: ["name"]
				}
			});
		}
		formatWhitelist() {
			let data = [];
			window.tagifyWhitelist.map((item) => {
				data.push({
					value: item.name,
					id: item.id
				});
			});
			return data;
		}
		addNewCommissionSetting() {
			const tpl = $("#commission-setting-item-template").html();
			const index = $(".commission-setting-item").length;
			let html = tpl.replace(/__index__/g, index);
			$(".commission-setting-item-wrapper").append(html);
			const element = document.querySelector(`#commission-setting-item-${index} .tagify-commission-setting`);
			this.tagify(element);
			this.initRemoveCommissionEvent();
		}
		initRemoveCommissionEvent() {
			$(document).on("click", "[data-bb-toggle=\"commission-remove\"]", (e) => {
				e.preventDefault();
				e.stopPropagation();
				$(e.target).closest(".commission-setting-item").remove();
			});
		}
	};
	$(document).ready(function() {
		new MarketplaceSetting();
	});
	//#endregion
})();

//# sourceMappingURL=marketplace-setting.js.map