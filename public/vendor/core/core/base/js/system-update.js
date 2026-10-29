(function(){if(typeof document==='undefined')return;var s=document.createElement('style');s.textContent=".half-circle-spinner,.half-circle-spinner *{box-sizing:border-box}.half-circle-spinner{width:60px;height:60px;border-radius:100%;position:relative}.half-circle-spinner .circle{content:\"\";position:absolute;width:100%;height:100%;border-radius:100%;border:6px solid transparent}.half-circle-spinner .circle.circle-1{border-top-color:#ff1d5e;animation:half-circle-spinner-animation 1s infinite}.half-circle-spinner .circle.circle-2{border-bottom-color:#ff1d5e;animation:half-circle-spinner-animation 1s infinite alternate}@keyframes half-circle-spinner-animation{0%{transform:rotate(0)}to{transform:rotate(360deg)}}\n.updating[data-v-bd728141] {\n  position: fixed;\n  top: 0;\n  left: 0;\n  width: 100%;\n  height: 100%;\n  background: rgba(0, 0, 0, 0.6);\n  z-index: 9999;\n  overflow: hidden;\n  backdrop-filter: blur(5px);\n}\n.updating > .updating-wrapper[data-v-bd728141] {\n  position: absolute;\n  top: calc(30% - 100px);\n  height: 100%;\n  width: 100%;\n}\n.updating > .updating-wrapper > .updating-container[data-v-bd728141] {\n  max-width: 500px;\n  margin: 0 auto;\n  text-align: center;\n}\n.updating > .updating-wrapper > .updating-container .loader[data-v-bd728141] {\n  margin: 0 auto;\n}\n.updating > .updating-wrapper > .updating-container .loader[data-v-bd728141]::after {\n  display: none;\n}\n.updating > .updating-wrapper > .updating-container .percent[data-v-bd728141] {\n  font-size: 86px;\n  color: #fefefe;\n  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, \"Liberation Mono\", \"Courier New\", monospace;\n  margin-bottom: 24px;\n}\n.updating > .updating-wrapper > .updating-container .information[data-v-bd728141] {\n  padding: 0 8px;\n  margin: 32px 0;\n  font-size: 18px;\n  color: #efefef;\n}\n.updating > .updating-wrapper > .updating-container .important[data-v-bd728141] {\n  color: #efefef;\n}\n.updating > .updating-wrapper > .updating-container .loader .half-circle-spinner[data-v-bd728141] {\n  margin: 0 auto 20px;\n}\n.updating .red[data-v-bd728141] {\n  color: #fdc9c9;\n}\n.updating .red-shadow[data-v-bd728141] {\n  text-shadow: 0 0 16px #ef0012;\n}/*$vite$:1*/";document.head.appendChild(s);})();(function(vue) {
	//#region node_modules/epic-spinners/dist/es/plugin-vue_export-helper.js
	var _export_sfc = (sfc, props) => {
		const target = sfc.__vccOpts || sfc;
		for (const [key, val] of props) target[key] = val;
		return target;
	};
	//#endregion
	//#region node_modules/epic-spinners/dist/es/components/HalfCircleSpinner.js
	var _sfc_main$1 = {
		name: "HalfCircleSpinner",
		props: {
			animationDuration: {
				type: Number,
				default: 1e3
			},
			size: {
				type: Number,
				default: 60
			},
			color: {
				type: String,
				default: "#fff"
			}
		},
		computed: {
			spinnerStyle() {
				return {
					height: `${this.size}px`,
					width: `${this.size}px`
				};
			},
			circleStyle() {
				return {
					borderWidth: `${this.size / 10}px`,
					animationDuration: `${this.animationDuration}ms`
				};
			},
			circle1Style() {
				return Object.assign({ borderTopColor: this.color }, this.circleStyle);
			},
			circle2Style() {
				return Object.assign({ borderBottomColor: this.color }, this.circleStyle);
			}
		}
	};
	function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", {
			class: "half-circle-spinner",
			style: (0, vue.normalizeStyle)($options.spinnerStyle)
		}, [(0, vue.createElementVNode)("div", {
			class: "circle circle-1",
			style: (0, vue.normalizeStyle)($options.circle1Style)
		}, null, 4), (0, vue.createElementVNode)("div", {
			class: "circle circle-2",
			style: (0, vue.normalizeStyle)($options.circle2Style)
		}, null, 4)], 4);
	}
	var HalfCircleSpinner = /*#__PURE__*/ _export_sfc(_sfc_main$1, [["render", _sfc_render$1]]);
	//#endregion
	//#region \0plugin-vue:export-helper
	var _plugin_vue_export_helper_default = (sfc, props) => {
		const target = sfc.__vccOpts || sfc;
		for (const [key, val] of props) target[key] = val;
		return target;
	};
	//#endregion
	//#region platform/core/base/resources/js/components/SystemUpdateComponent.vue
	var _sfc_main = {
		components: { HalfCircleSpinner },
		props: {
			updateUrl: String,
			updateId: String,
			version: String,
			firstStep: String,
			firstStepMessage: String,
			lastStep: String,
			isOutdated: Boolean,
			isActivated: Boolean
		},
		data() {
			return {
				askToProcessUpdate: false,
				performingUpdate: false,
				results: [],
				realPercent: 0,
				percent: 0,
				percentInterval: 0,
				step: this.firstStep,
				loading: false
			};
		},
		watch: { realPercent() {
			if (this.percentInterval) return;
			this.percentInterval = setInterval(() => {
				if (this.percent >= this.realPercent) return;
				if (this.percent === 100) {
					clearInterval(this.percentInterval);
					return;
				}
				this.percent += 1;
			}, 100);
		} },
		methods: {
			triggerAskToProcessUpdate() {
				if (this.isActivated) this.askToProcessUpdate = true;
				else $("#system-updater-confirm-modal").modal("show");
			},
			async performUpdate() {
				this.loading = true;
				this.performingUpdate = true;
				this.realPercent = 5;
				this.results.push({
					text: this.firstStepMessage,
					error: false
				});
				try {
					await this.triggerUpdate();
					setTimeout(() => this.refresh(), 3e4);
				} catch (e) {
					this.loading = false;
				}
			},
			async triggerUpdate() {
				return this.$httpClient.makeWithoutErrorHandler().post(this.updateUrl, {
					step_name: this.step,
					update_id: this.updateId,
					version: this.version
				}).then(({ data }) => {
					if (!data.data || !data.data.next_step || !data.data.next_message) throw new Error("Something went wrong, could not update the system.");
					this.step = data.data.next_step;
					this.realPercent = data.data.current_percent;
					this.results.push({
						text: data.data.next_message,
						error: false
					});
					if (data.data.next_step !== this.lastStep) return this.triggerUpdate();
					this.percent = 100;
					this.loading = false;
					clearInterval(this.percentInterval);
					localStorage.removeItem("system_update_has_new_version");
					localStorage.removeItem("system_update_message");
					localStorage.removeItem("system_update_check_time");
				}).catch((error) => {
					this.loading = false;
					const message = this.extractErrorMessage(error);
					this.results.push({
						text: message,
						error: true
					});
					throw error;
				});
			},
			decodeHtmlEntities(text) {
				if (typeof text !== "string" || !text) return text;
				const textarea = document.createElement("textarea");
				textarea.innerHTML = text;
				return textarea.value;
			},
			extractErrorMessage(error) {
				var _error$response, _error$response2, _error$data, _error$response3;
				const stepLabel = {
					download: "downloading update files",
					update_files: "updating system files",
					update_database: "updating databases",
					publish_core_assets: "publishing core assets",
					publish_packages_assets: "publishing packages assets",
					clean_up: "cleaning up"
				}[this.step] || this.step;
				const statusCode = (_error$response = error.response) === null || _error$response === void 0 ? void 0 : _error$response.status;
				const serverMessage = this.decodeHtmlEntities(((_error$response2 = error.response) === null || _error$response2 === void 0 || (_error$response2 = _error$response2.data) === null || _error$response2 === void 0 ? void 0 : _error$response2.message) || ((_error$data = error.data) === null || _error$data === void 0 ? void 0 : _error$data.message) || null);
				if (serverMessage) return `Error while ${stepLabel}: ${serverMessage}`;
				if (typeof ((_error$response3 = error.response) === null || _error$response3 === void 0 ? void 0 : _error$response3.data) === "string" && error.response.data.includes("<title>")) {
					const titleMatch = error.response.data.match(/<title>(.*?)<\/title>/i);
					if (titleMatch && titleMatch[1]) return `Error while ${stepLabel}: ${titleMatch[1].trim()} (HTTP ${statusCode})`;
				}
				if (statusCode === 500) return `Server error while ${stepLabel} (HTTP 500). Check the server logs for more details.`;
				if (statusCode === 504 || statusCode === 408) return `Request timed out while ${stepLabel} (HTTP ${statusCode}). Try increasing max_execution_time or memory_limit.`;
				if (statusCode === 503) return `Service unavailable while ${stepLabel} (HTTP 503). The server may be overloaded.`;
				if (statusCode) return `Error while ${stepLabel}: ${error.message} (HTTP ${statusCode})`;
				if (!error.response) return `Network error while ${stepLabel}. Please check your connection and try again.`;
				return error.message || "An unknown error occurred.";
			},
			refresh() {
				window.location.reload();
			}
		}
	};
	var _hoisted_1 = { class: "content" };
	var _hoisted_2 = { key: 0 };
	var _hoisted_3 = {
		key: 0,
		xmlns: "http://www.w3.org/2000/svg",
		class: "icon ms-1",
		width: "24",
		height: "24",
		viewBox: "0 0 24 24",
		"stroke-width": "2",
		stroke: "currentColor",
		fill: "none",
		"stroke-linecap": "round",
		"stroke-linejoin": "round"
	};
	var _hoisted_4 = {
		key: 1,
		xmlns: "http://www.w3.org/2000/svg",
		class: "icon ms-1",
		width: "24",
		height: "24",
		viewBox: "0 0 24 24",
		"stroke-width": "2",
		stroke: "currentColor",
		fill: "none",
		"stroke-linecap": "round",
		"stroke-linejoin": "round"
	};
	var _hoisted_5 = { key: 2 };
	var _hoisted_6 = { key: 3 };
	var _hoisted_7 = {
		key: 1,
		class: "updating"
	};
	var _hoisted_8 = { class: "updating-wrapper" };
	var _hoisted_9 = { class: "updating-container" };
	var _hoisted_10 = {
		key: 0,
		class: "loader"
	};
	var _hoisted_11 = ["textContent"];
	var _hoisted_12 = { class: "information" };
	var _hoisted_13 = ["textContent"];
	var _hoisted_14 = {
		key: 1,
		class: "important red-shadow"
	};
	var _hoisted_15 = { key: 2 };
	function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
		const _component_half_circle_spinner = (0, vue.resolveComponent)("half-circle-spinner");
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_1, [
			(0, vue.renderSlot)(_ctx.$slots, "default", (0, vue.normalizeProps)((0, vue.guardReactiveProps)({ performUpdate: $options.performUpdate })), void 0, true),
			!$data.performingUpdate ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_2, [!$data.askToProcessUpdate ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("button", {
				key: 0,
				type: "button",
				class: "btn btn-warning",
				onClick: _cache[0] || (_cache[0] = (0, vue.withModifiers)((...args) => $options.triggerAskToProcessUpdate && $options.triggerAskToProcessUpdate(...args), ["prevent"]))
			}, [!$props.isOutdated ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("svg", _hoisted_3, [..._cache[3] || (_cache[3] = [
				(0, vue.createElementVNode)("path", {
					stroke: "none",
					d: "M0 0h24v24H0z",
					fill: "none"
				}, null, -1),
				(0, vue.createElementVNode)("path", { d: "M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" }, null, -1),
				(0, vue.createElementVNode)("path", { d: "M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" }, null, -1)
			])])) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("svg", _hoisted_4, [..._cache[4] || (_cache[4] = [
				(0, vue.createElementVNode)("path", {
					stroke: "none",
					d: "M0 0h24v24H0z",
					fill: "none"
				}, null, -1),
				(0, vue.createElementVNode)("path", { d: "M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" }, null, -1),
				(0, vue.createElementVNode)("path", { d: "M7 11l5 5l5 -5" }, null, -1),
				(0, vue.createElementVNode)("path", { d: "M12 4l0 12" }, null, -1)
			])])), $props.isOutdated ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", _hoisted_5, "Download & Install Update")) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", _hoisted_6, "Re-install The Latest Version"))])) : (0, vue.createCommentVNode)("v-if", true), $data.askToProcessUpdate ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("button", {
				key: 1,
				type: "button",
				class: "btn btn-danger",
				onClick: _cache[1] || (_cache[1] = (...args) => $options.performUpdate && $options.performUpdate(...args))
			}, [..._cache[5] || (_cache[5] = [(0, vue.createElementVNode)("svg", {
				xmlns: "http://www.w3.org/2000/svg",
				class: "icon ms-1",
				width: "24",
				height: "24",
				viewBox: "0 0 24 24",
				"stroke-width": "2",
				stroke: "currentColor",
				fill: "none",
				"stroke-linecap": "round",
				"stroke-linejoin": "round"
			}, [(0, vue.createElementVNode)("path", {
				stroke: "none",
				d: "M0 0h24v24H0z",
				fill: "none"
			}), (0, vue.createElementVNode)("path", { d: "M5 12l5 5l10 -10" })], -1), (0, vue.createTextVNode)(" Click To Confirm! ", -1)])])) : (0, vue.createCommentVNode)("v-if", true)])) : (0, vue.createCommentVNode)("v-if", true),
			$data.performingUpdate ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_7, [(0, vue.createElementVNode)("div", _hoisted_8, [(0, vue.createElementVNode)("div", _hoisted_9, [
				$data.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_10, [(0, vue.createVNode)(_component_half_circle_spinner, {
					"animation-duration": 1e3,
					size: 32
				})])) : (0, vue.createCommentVNode)("v-if", true),
				(0, vue.createElementVNode)("div", {
					class: "percent",
					textContent: (0, vue.toDisplayString)(`${$data.percent}%`)
				}, null, 8, _hoisted_11),
				(0, vue.createElementVNode)("div", _hoisted_12, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)($data.results, (result) => {
					return (0, vue.openBlock)(), (0, vue.createElementBlock)("p", {
						textContent: (0, vue.toDisplayString)(result.text),
						class: (0, vue.normalizeClass)(result.error ? "bold text-pink red-shadow" : "bold")
					}, null, 10, _hoisted_13);
				}), 256))]),
				$data.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_14, [..._cache[6] || (_cache[6] = [(0, vue.createElementVNode)("strong", null, "DO NOT CLOSE WINDOWS DURING UPDATE!", -1)])])) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_15, [(0, vue.createElementVNode)("div", {
					class: "btn btn-info",
					onClick: _cache[2] || (_cache[2] = (...args) => $options.refresh && $options.refresh(...args))
				}, "Click Here To Exit")]))
			])])])) : (0, vue.createCommentVNode)("v-if", true)
		]);
	}
	var SystemUpdateComponent_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main, [["render", _sfc_render], ["__scopeId", "data-v-bd728141"]]);
	//#endregion
	//#region platform/core/base/resources/js/system-update.js
	if (typeof vueApp !== "undefined") vueApp.booting((vue) => {
		vue.component("system-update-component", SystemUpdateComponent_default);
	});
	//#endregion
})(Vue);

//# sourceMappingURL=system-update.js.map