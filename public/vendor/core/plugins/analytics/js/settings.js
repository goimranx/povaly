(function() {
	//#region platform/plugins/analytics/resources/js/settings.js
	$(function() {
		const initializeParseJsonSetting = () => {
			const $setting = document.getElementById("google-analytics-settings");
			const $field = document.createElement("input");
			let analyticsJsonSettingUrl = null;
			createAnalyticsJsonConfigFileField();
			$(document).on("click", "[data-bb-toggle=\"analytics-trigger-upload-json\"]", (e) => {
				e.preventDefault();
				analyticsJsonSettingUrl = e.currentTarget.dataset.url;
				$field.click();
			});
			function createAnalyticsJsonConfigFileField() {
				$field.type = "file";
				$field.accept = "application/json";
				$field.classList.add("d-none");
				$field.addEventListener("change", (e) => {
					const target = e.currentTarget;
					const editor = $setting.getElementsByClassName("CodeMirror");
					let codeMirror = null;
					if (editor.length > 0) codeMirror = editor[0].CodeMirror;
					else return;
					if ((target === null || target === void 0 ? void 0 : target.files) && target.files.length === 0) return;
					const data = new FormData();
					data.set("json", target.files[0]);
					Botble.showLoading($setting);
					$httpClient.make().postForm(analyticsJsonSettingUrl, data).then(({ data }) => {
						codeMirror.setValue(data.data.content);
					}).catch((error) => {
						if (!error.response || !error.response.data) return;
						codeMirror.setValue(error.response.data.data.content);
					}).finally(() => {
						Botble.hideLoading($setting);
						target.value = "";
					});
				});
				document.body.appendChild($field);
			}
		};
		initializeParseJsonSetting();
	});
	//#endregion
})();

//# sourceMappingURL=settings.js.map