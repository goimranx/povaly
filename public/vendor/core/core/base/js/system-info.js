(function() {
	//#region platform/core/base/resources/js/system-info.js
	var SystemInformationManagement = class {
		init() {
			let s = document.getElementById("txt-report").value;
			s = s.replace(/(^\s*)|(\s*$)/gi, "");
			s = s.replace(/[ ]{2,}/gi, " ");
			s = s.replace(/\n /, "\n");
			document.getElementById("txt-report").value = s;
			$("#btn-report").on("click", () => {
				$("#report-wrapper").slideToggle();
			});
			$("#copy-report").on("click", () => {
				$("#txt-report").trigger("select");
			});
			$httpClient.make().get($("[data-get-addition-data-url]").data("get-addition-data-url")).then(({ data }) => {
				$("#system-app-size").text(data.data.appSize);
			});
		}
	};
	$(() => {
		new SystemInformationManagement().init();
	});
	//#endregion
})();

//# sourceMappingURL=system-info.js.map