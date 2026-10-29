(function(vue) {
	//#region \0plugin-vue:export-helper
	var _plugin_vue_export_helper_default = (sfc, props) => {
		const target = sfc.__vccOpts || sfc;
		for (const [key, val] of props) target[key] = val;
		return target;
	};
	//#endregion
	//#region platform/core/dashboard/resources/js/components/CheckForUpdates.vue
	var _sfc_main = {
		props: { checkUpdateUrl: {
			type: String,
			default: () => null,
			required: true
		} },
		data() {
			return {
				hasNewVersion: false,
				message: null
			};
		},
		mounted() {
			this.checkUpdate();
		},
		methods: { checkUpdate() {
			const shouldCheckUpdate = () => {
				const lastCheckTime = localStorage.getItem("system_update_check_time");
				if (!lastCheckTime) return true;
				return Date.now() - parseInt(lastCheckTime) > 9e5;
			};
			const cachedHasNewVersion = localStorage.getItem("system_update_has_new_version") === "true";
			const cachedMessage = localStorage.getItem("system_update_message");
			if (cachedHasNewVersion && cachedMessage && !shouldCheckUpdate()) {
				this.hasNewVersion = cachedHasNewVersion;
				this.message = cachedMessage;
				return;
			}
			if (!shouldCheckUpdate()) return;
			axios.get(this.checkUpdateUrl).then(({ data }) => {
				localStorage.setItem("system_update_check_time", Date.now().toString());
				if (!data.error && data.data.has_new_version) {
					this.hasNewVersion = true;
					this.message = data.message;
					localStorage.setItem("system_update_has_new_version", "true");
					localStorage.setItem("system_update_message", data.message);
				} else {
					localStorage.setItem("system_update_has_new_version", "false");
					localStorage.removeItem("system_update_message");
				}
			}).catch(() => {
				localStorage.setItem("system_update_check_time", Date.now().toString());
			});
		} }
	};
	function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
		return (0, vue.renderSlot)(_ctx.$slots, "default", (0, vue.normalizeProps)((0, vue.guardReactiveProps)({
			hasNewVersion: $data.hasNewVersion,
			message: $data.message
		})));
	}
	var CheckForUpdates_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main, [["render", _sfc_render]]);
	//#endregion
	//#region platform/core/dashboard/resources/js/check-for-updates.js
	if (typeof vueApp !== "undefined") vueApp.booting((vue) => {
		vue.component("v-check-for-updates", CheckForUpdates_default);
	});
	//#endregion
})(Vue);

//# sourceMappingURL=check-for-updates.js.map