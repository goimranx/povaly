(function(vue) {
	//#region \0plugin-vue:export-helper
	var _plugin_vue_export_helper_default = (sfc, props) => {
		const target = sfc.__vccOpts || sfc;
		for (const [key, val] of props) target[key] = val;
		return target;
	};
	//#endregion
	//#region platform/core/setting/resources/js/components/LicenseForm.vue
	var _sfc_main = {
		props: {
			id: {
				type: String,
				default: () => null,
				required: true
			},
			verifyUrl: {
				type: String,
				default: () => null,
				required: true
			},
			activateLicenseUrl: {
				type: String,
				default: () => null,
				required: true
			},
			deactivateLicenseUrl: {
				type: String,
				default: () => null,
				required: true
			},
			resetLicenseUrl: {
				type: String,
				default: () => null,
				required: true
			}
		},
		data() {
			return {
				initialized: null,
				loading: true,
				verified: false,
				license: null
			};
		},
		mounted() {
			this.verifyLicense();
		},
		methods: {
			async verifyLicense() {
				const shouldVerifyLicense = () => {
					const lastVerifyTime = localStorage.getItem("license_verification_time");
					const isVerified = localStorage.getItem("license_is_verified") === "true";
					if (!lastVerifyTime) return true;
					const now = Date.now();
					const lastTime = parseInt(lastVerifyTime);
					if (isVerified) return now - lastTime > 2592e5;
					else return now - lastTime > 864e5;
				};
				const cachedLicense = localStorage.getItem("license_data");
				const cachedVerified = localStorage.getItem("license_is_verified") === "true";
				if (cachedLicense && !shouldVerifyLicense()) try {
					this.verified = cachedVerified;
					this.license = JSON.parse(cachedLicense);
					this.initialized = true;
					this.loading = false;
					return Promise.resolve();
				} catch (e) {}
				return $httpClient.makeWithoutErrorHandler().get(this.verifyUrl).then(({ data }) => {
					this.verified = true;
					this.license = data.data;
					localStorage.setItem("license_verification_time", Date.now().toString());
					localStorage.setItem("license_is_verified", "true");
					localStorage.setItem("license_data", JSON.stringify(data.data));
				}).catch((data) => {
					if (data.response && data.response.status === 400) Botble.showError(data.response.data.message);
					localStorage.setItem("license_verification_time", Date.now().toString());
					localStorage.setItem("license_is_verified", "false");
					localStorage.removeItem("license_data");
				}).finally(() => {
					this.initialized = true;
					this.loading = false;
				});
			},
			async onSubmit() {
				const formData = new FormData(this.$refs.formRef);
				return this.doActivateLicense(formData);
			},
			async resetLicense() {
				const formData = new FormData(this.$refs.formRef);
				return this.doResetLicense(formData);
			},
			async deactivateLicense() {
				this.loading = true;
				return $httpClient.make().post(this.deactivateLicenseUrl).then(() => {
					this.verified = false;
					localStorage.setItem("license_verification_time", Date.now().toString());
					localStorage.setItem("license_is_verified", "false");
					localStorage.removeItem("license_data");
					localStorage.removeItem("license_check_time");
				}).finally(() => {
					this.loading = false;
				});
			},
			async doActivateLicense(formData) {
				this.loading = true;
				return $httpClient.make().postForm(this.activateLicenseUrl, formData).then(({ data }) => {
					this.verified = true;
					this.license = data.data;
					Botble.showSuccess(data.message);
					localStorage.setItem("license_verification_time", Date.now().toString());
					localStorage.setItem("license_is_verified", "true");
					localStorage.setItem("license_data", JSON.stringify(data.data));
					localStorage.removeItem("license_check_time");
				}).finally(() => {
					this.loading = false;
				});
			},
			async doResetLicense(formData) {
				this.loading = true;
				return $httpClient.make().postForm(this.resetLicenseUrl, formData).then(({ data }) => {
					this.verified = false;
					Botble.showSuccess(data.message);
					localStorage.setItem("license_verification_time", Date.now().toString());
					localStorage.setItem("license_is_verified", "false");
					localStorage.removeItem("license_data");
					localStorage.removeItem("license_check_time");
				}).finally(() => {
					this.loading = false;
				});
			}
		}
	};
	function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("form", {
			id: "license-form",
			ref: "formRef",
			onSubmit: _cache[0] || (_cache[0] = (0, vue.withModifiers)((...args) => $options.onSubmit && $options.onSubmit(...args), ["prevent"]))
		}, [(0, vue.renderSlot)(_ctx.$slots, "default", (0, vue.normalizeProps)((0, vue.guardReactiveProps)({
			initialized: $data.initialized,
			loading: $data.loading,
			verified: $data.verified,
			license: $data.license,
			deactivateLicense: $options.deactivateLicense,
			resetLicense: $options.resetLicense
		})))], 544);
	}
	var LicenseForm_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main, [["render", _sfc_render]]);
	//#endregion
	//#region platform/core/setting/resources/js/license-component.js
	if (typeof vueApp !== "undefined") vueApp.booting((vue) => {
		vue.component("v-license-form", LicenseForm_default);
	});
	//#endregion
})(Vue);

//# sourceMappingURL=license-component.js.map