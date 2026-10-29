(function(exports) {
	Object.defineProperty(exports, Symbol.toStringTag, { value: "Module" });
	//#region \0rolldown/runtime.js
	var __create = Object.create;
	var __defProp = Object.defineProperty;
	var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
	var __getOwnPropNames = Object.getOwnPropertyNames;
	var __getProtoOf = Object.getPrototypeOf;
	var __hasOwnProp = Object.prototype.hasOwnProperty;
	var __commonJSMin = (cb, mod) => () => (mod || (cb((mod = { exports: {} }).exports, mod, mod.exports), cb = null), mod.exports);
	var __copyProps = (to, from, except, desc) => {
		if (from && typeof from === "object" || typeof from === "function") for (var keys = __getOwnPropNames(from), i = 0, n = keys.length, key; i < n; i++) {
			key = keys[i];
			if (!__hasOwnProp.call(to, key) && key !== except) __defProp(to, key, {
				get: ((k) => from[k]).bind(null, key),
				enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable
			});
		}
		return to;
	};
	var __toESM = (mod, isNodeMode, target) => (target = mod != null ? __create(__getProtoOf(mod)) : {}, __copyProps(isNodeMode || !mod || !mod.__esModule || !__hasOwnProp.call(mod, "default") ? __defProp(target, "default", {
		value: mod,
		enumerable: true
	}) : target, mod));
	//#endregion
	//#region platform/core/media/resources/js/App/Config/MediaConfig.js
	var MediaConfig = JSON.parse(localStorage.getItem("MediaConfig")) || {};
	var defaultConfig = {
		app_key: RV_MEDIA_CONFIG.random_hash ? RV_MEDIA_CONFIG.random_hash : "21d06709fe1d3abdf0e35ddda89c4b282",
		request_params: {
			view_type: "tiles",
			filter: "everything",
			view_in: "all_media",
			sort_by: "created_at-desc",
			folder_id: 0
		},
		hide_details_pane: false,
		icons: { folder: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path>
        </svg>` },
		actions_list: {
			basic: [{
				icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                </svg>`,
				name: "Preview",
				action: "preview",
				order: 0,
				class: "rv-action-preview"
			}, {
				icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M8 5v10a1 1 0 0 0 1 1h10"></path>
                    <path d="M5 8h10a1 1 0 0 1 1 1v10"></path>
                </svg>`,
				name: "Crop",
				action: "crop",
				order: 1,
				class: "rv-action-crop"
			}],
			file: [
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                    <path d="M16 5l3 3"></path>
                </svg>`,
					name: "Rename",
					action: "rename",
					order: 0,
					class: "rv-action-rename"
				},
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"></path>
                    <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path>
                </svg>`,
					name: "Make a copy",
					action: "make_copy",
					order: 1,
					class: "rv-action-make-copy"
				},
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 21l-8 0a2 2 0 0 1 -2 -2l0 -14a2 2 0 0 1 2 -2l4 0l3 3l7 0a2 2 0 0 1 2 2l0 3"></path>
                    <path d="M16 19l3 -3l-3 -3"></path>
                    <path d="M21 16l-8 0"></path>
                </svg>`,
					name: "Move",
					action: "move",
					order: 2,
					class: "rv-action-move"
				},
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M15 8h.01"></path>
                    <path d="M11 20h-4a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v4"></path>
                    <path d="M4 15l4 -4c.928 -.893 2.072 -.893 3 0l3 3"></path>
                    <path d="M14 14l1 -1c.31 -.298 .644 -.497 .987 -.596"></path>
                    <path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z"></path>
                </svg>`,
					name: "Alt text",
					action: "alt_text",
					order: 3,
					class: "rv-action-alt-text"
				},
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M9 15l6 -6"></path>
                    <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464"></path>
                    <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463"></path>
                </svg>`,
					name: "Copy link",
					action: "copy_link",
					order: 4,
					class: "rv-action-copy-link"
				},
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M9 15l6 -6"></path>
                    <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464"></path>
                    <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463"></path>
                </svg>`,
					name: "Copy indirect link",
					action: "copy_indirect_link",
					order: 5,
					class: "rv-action-copy-indirect-link"
				},
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                  <path d="M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                  <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                  <path d="M8.7 10.7l6.6 -3.4"></path>
                  <path d="M8.7 13.3l6.6 3.4"></path>
                </svg>`,
					name: "Share",
					action: "share",
					order: 6,
					class: "rv-action-share"
				}
			],
			user: [{
				icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                </svg>`,
				name: "Favorite",
				action: "favorite",
				order: 2,
				class: "rv-action-favorite"
			}, {
				icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                </svg>`,
				name: "Remove favorite",
				action: "remove_favorite",
				order: 3,
				class: "rv-action-favorite"
			}],
			other: [
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                    <path d="M7 11l5 5l5 -5"></path>
                    <path d="M12 4l0 12"></path>
                </svg>`,
					name: "Download",
					action: "download",
					order: 0,
					class: "rv-action-download"
				},
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M4 7l16 0"></path>
                    <path d="M10 11l0 6"></path>
                    <path d="M14 11l0 6"></path>
                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                </svg>`,
					name: "Move to trash",
					action: "trash",
					order: 1,
					class: "rv-action-trash"
				},
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                    <path d="M18 13.3l-6.3 -6.3"></path>
                </svg>`,
					name: "Delete permanently",
					action: "delete",
					order: 2,
					class: "rv-action-delete"
				},
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"></path>
                </svg>`,
					name: "Restore",
					action: "restore",
					order: 3,
					class: "rv-action-restore"
				},
				{
					icon: `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-palette" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21a9 9 0 0 1 0 -18c4.97 0 9 3.582 9 8c0 1.06 -.474 2.078 -1.318 2.828c-.844 .75 -1.989 1.172 -3.182 1.172h-2.5a2 2 0 0 0 -1 3.75a1.3 1.3 0 0 1 -1 2.25" /><path d="M8.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M16.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>`,
					name: "Properties",
					action: "properties",
					order: 4,
					class: "rv-action-properties"
				}
			]
		}
	};
	if (!MediaConfig.app_key || MediaConfig.app_key !== defaultConfig.app_key) MediaConfig = defaultConfig;
	MediaConfig.request_params.search = "";
	var RecentItems = [];
	//#endregion
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/typeof.js
	function _typeof(o) {
		"@babel/helpers - typeof";
		return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(o) {
			return typeof o;
		} : function(o) {
			return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
		}, _typeof(o);
	}
	//#endregion
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/toPrimitive.js
	function toPrimitive(t, r) {
		if ("object" != _typeof(t) || !t) return t;
		var e = t[Symbol.toPrimitive];
		if (void 0 !== e) {
			var i = e.call(t, r || "default");
			if ("object" != _typeof(i)) return i;
			throw new TypeError("@@toPrimitive must return a primitive value.");
		}
		return ("string" === r ? String : Number)(t);
	}
	//#endregion
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/toPropertyKey.js
	function toPropertyKey(t) {
		var i = toPrimitive(t, "string");
		return "symbol" == _typeof(i) ? i : i + "";
	}
	//#endregion
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/defineProperty.js
	function _defineProperty(e, r, t) {
		return (r = toPropertyKey(r)) in e ? Object.defineProperty(e, r, {
			value: t,
			enumerable: !0,
			configurable: !0,
			writable: !0
		}) : e[r] = t, e;
	}
	//#endregion
	//#region \0@oxc-project+runtime@0.154.0/helpers/esm/objectSpread2.js
	function ownKeys(e, r) {
		var t = Object.keys(e);
		if (Object.getOwnPropertySymbols) {
			var o = Object.getOwnPropertySymbols(e);
			r && (o = o.filter(function(r) {
				return Object.getOwnPropertyDescriptor(e, r).enumerable;
			})), t.push.apply(t, o);
		}
		return t;
	}
	function _objectSpread2(e) {
		for (var r = 1; r < arguments.length; r++) {
			var t = null != arguments[r] ? arguments[r] : {};
			r % 2 ? ownKeys(Object(t), !0).forEach(function(r) {
				_defineProperty(e, r, t[r]);
			}) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function(r) {
				Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r));
			});
		}
		return e;
	}
	//#endregion
	//#region platform/core/media/resources/js/App/Helpers/Helpers.js
	var Helpers = class Helpers {
		static getUrlParam(paramName, url = null) {
			if (!url) url = window.location.search;
			let reParam = new RegExp("(?:[?&]|&)" + paramName + "=([^&]+)", "i");
			let match = url.match(reParam);
			return match && match.length > 1 ? match[1] : null;
		}
		static asset(url) {
			if (url.substring(0, 2) === "//" || url.substring(0, 7) === "http://" || url.substring(0, 8) === "https://") return url;
			let baseUrl = RV_MEDIA_URL.base_url.substr(-1, 1) !== "/" ? RV_MEDIA_URL.base_url + "/" : RV_MEDIA_URL.base_url;
			if (url.substring(0, 1) === "/") return baseUrl + url.substring(1);
			return baseUrl + url;
		}
		static showAjaxLoading($element = $(".rv-media-main")) {
			$element.addClass("on-loading").append($("#rv_media_loading").html());
		}
		static hideAjaxLoading($element = $(".rv-media-main")) {
			$element.removeClass("on-loading").find(".loading-spinner").remove();
		}
		static isOnAjaxLoading($element = $(".rv-media-items")) {
			return $element.hasClass("on-loading");
		}
		static jsonEncode(object) {
			if (typeof object === "undefined") object = null;
			return JSON.stringify(object);
		}
		static jsonDecode(jsonString, defaultValue) {
			if (!jsonString) return defaultValue;
			if (typeof jsonString === "string") {
				let result;
				try {
					result = JSON.parse(jsonString);
				} catch (err) {
					result = defaultValue;
				}
				return result;
			}
			return jsonString;
		}
		static getRequestParams() {
			if (window.rvMedia.options && window.rvMedia.options.open_in === "modal") return _objectSpread2(_objectSpread2({}, MediaConfig.request_params), window.rvMedia.options);
			return MediaConfig.request_params;
		}
		static setSelectedFile(fileId) {
			if (typeof window.rvMedia.options !== "undefined") window.rvMedia.options.selected_file_id = fileId;
			else MediaConfig.request_params.selected_file_id = fileId;
		}
		static getConfigs() {
			return MediaConfig;
		}
		static storeConfig() {
			localStorage.setItem("MediaConfig", Helpers.jsonEncode(MediaConfig));
		}
		static storeRecentItems() {}
		static addToRecent(id, isFolder = false) {
			if (isNaN(parseInt(id))) return;
			let fileData = {
				id,
				is_folder: isFolder
			};
			if (!isFolder) $(".js-media-list-title").each((index, el) => {
				let data = $(el).data() || {};
				if (data.id == id) {
					fileData.is_folder = data.is_folder || false;
					return false;
				}
			});
			$httpClient.make().post(RV_MEDIA_URL.global_actions, {
				action: "add_recent",
				item: fileData
			}).then(({ data }) => {
				if (id instanceof Array) Helpers.each(id, (value) => {
					RecentItems.push(value);
				});
				else RecentItems.push(id);
			}).catch((error) => {
				console.error("Error adding to recent items:", error);
			});
		}
		static getItems() {
			let items = [];
			$(".js-media-list-title").each((index, el) => {
				let $box = $(el);
				let data = $box.data() || {};
				data.index_key = $box.index();
				items.push(data);
			});
			return items;
		}
		static getSelectedItems() {
			let selected = [];
			$(".js-media-list-title input[type=checkbox]:checked").each((index, el) => {
				let $box = $(el).closest(".js-media-list-title");
				let data = $box.data() || {};
				data.index_key = $box.index();
				selected.push(data);
			});
			return selected;
		}
		static getSelectedFiles() {
			let selected = [];
			$(".js-media-list-title[data-context=file] input[type=checkbox]:checked").each((index, el) => {
				let $box = $(el).closest(".js-media-list-title");
				let data = $box.data() || {};
				data.index_key = $box.index();
				selected.push(data);
			});
			return selected;
		}
		static getSelectedFolder() {
			let selected = [];
			$(".js-media-list-title[data-context=folder] input[type=checkbox]:checked").each((index, el) => {
				let $box = $(el).closest(".js-media-list-title");
				let data = $box.data() || {};
				data.index_key = $box.index();
				selected.push(data);
			});
			return selected;
		}
		static isUseInModal() {
			return window.rvMedia && window.rvMedia.options && window.rvMedia.options.open_in === "modal";
		}
		static resetPagination() {
			RV_MEDIA_CONFIG.pagination = {
				paged: 1,
				posts_per_page: 40,
				in_process_get_media: false,
				has_more: true
			};
		}
		static trans(key, fallback = null) {
			return _.get(RV_MEDIA_CONFIG.translations, key, fallback !== null && fallback !== void 0 ? fallback : key);
		}
		static config(key, defaultValue = null) {
			return _.get(RV_MEDIA_CONFIG, key, defaultValue);
		}
		static hasPermission(key) {
			return Helpers.inArray(Helpers.config("permissions", []), key);
		}
		static inArray(array, value) {
			return _.includes(array, value);
		}
		static each(array, callback) {
			return _.each(array, callback);
		}
		static forEach(array, callback) {
			return _.forEach(array, callback);
		}
		static arrayReject(array, callback) {
			return _.reject(array, callback);
		}
		static arrayFilter(array, callback) {
			return _.filter(array, callback);
		}
		static arrayFirst(array) {
			return _.first(array);
		}
		static isArray(value) {
			return _.isArray(value);
		}
		static isEmpty(value) {
			return _.isEmpty(value);
		}
		static size(item) {
			return _.size(item);
		}
	};
	/*!
	* Cropper.js v1.7.0
	* https://fengyuanchen.github.io/cropperjs
	*
	* Copyright 2015-present Chen Fengyuan
	* Released under the MIT license
	*
	* Date: 2026-10-07T07:21:57.525Z
	*/
	//#endregion
	//#region platform/core/media/resources/js/App/Services/MessageService.js
	var import_cropper = /* @__PURE__ */ __toESM((/* @__PURE__ */ __commonJSMin(((exports, module, this$1) => {
		(function(global, factory) {
			typeof exports === "object" && typeof module !== "undefined" ? module.exports = factory() : typeof define === "function" && define.amd ? define(factory) : (global = typeof globalThis !== "undefined" ? globalThis : global || self, global.Cropper = factory());
		})(this$1, (function() {
			"use strict";
			function _arrayLikeToArray(r, a) {
				(null == a || a > r.length) && (a = r.length);
				for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e];
				return n;
			}
			function _arrayWithHoles(r) {
				if (Array.isArray(r)) return r;
			}
			function _arrayWithoutHoles(r) {
				if (Array.isArray(r)) return _arrayLikeToArray(r);
			}
			function _classCallCheck(a, n) {
				if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function");
			}
			function _defineProperties(e, r) {
				for (var t = 0; t < r.length; t++) {
					var o = r[t];
					o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o);
				}
			}
			function _createClass(e, r, t) {
				return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e;
			}
			function _defineProperty(e, r, t) {
				return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, {
					value: t,
					enumerable: !0,
					configurable: !0,
					writable: !0
				}) : e[r] = t, e;
			}
			function _iterableToArray(r) {
				if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r);
			}
			function _iterableToArrayLimit(r, l) {
				var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"];
				if (null != t) {
					var e, n, i, u, a = [], f = !0, o = !1;
					try {
						if (i = (t = t.call(r)).next, 0 === l) {
							if (Object(t) !== t) return;
							f = !1;
						} else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0);
					} catch (r) {
						o = !0, n = r;
					} finally {
						try {
							if (!f && null != t.return && (u = t.return(), Object(u) !== u)) return;
						} finally {
							if (o) throw n;
						}
					}
					return a;
				}
			}
			function _nonIterableRest() {
				throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
			}
			function _nonIterableSpread() {
				throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
			}
			function ownKeys(e, r) {
				var t = Object.keys(e);
				if (Object.getOwnPropertySymbols) {
					var o = Object.getOwnPropertySymbols(e);
					r && (o = o.filter(function(r) {
						return Object.getOwnPropertyDescriptor(e, r).enumerable;
					})), t.push.apply(t, o);
				}
				return t;
			}
			function _objectSpread2(e) {
				for (var r = 1; r < arguments.length; r++) {
					var t = null != arguments[r] ? arguments[r] : {};
					r % 2 ? ownKeys(Object(t), !0).forEach(function(r) {
						_defineProperty(e, r, t[r]);
					}) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function(r) {
						Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r));
					});
				}
				return e;
			}
			function _slicedToArray(r, e) {
				return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest();
			}
			function _toConsumableArray(r) {
				return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread();
			}
			function _toPrimitive(t, r) {
				if ("object" != typeof t || !t) return t;
				var e = t[Symbol.toPrimitive];
				if (void 0 !== e) {
					var i = e.call(t, r || "default");
					if ("object" != typeof i) return i;
					throw new TypeError("@@toPrimitive must return a primitive value.");
				}
				return ("string" === r ? String : Number)(t);
			}
			function _toPropertyKey(t) {
				var i = _toPrimitive(t, "string");
				return "symbol" == typeof i ? i : i + "";
			}
			function _typeof(o) {
				"@babel/helpers - typeof";
				return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(o) {
					return typeof o;
				} : function(o) {
					return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
				}, _typeof(o);
			}
			function _unsupportedIterableToArray(r, a) {
				if (r) {
					if ("string" == typeof r) return _arrayLikeToArray(r, a);
					var t = {}.toString.call(r).slice(8, -1);
					return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0;
				}
			}
			var IS_BROWSER = typeof window !== "undefined" && typeof window.document !== "undefined";
			var WINDOW = IS_BROWSER ? window : {};
			var IS_TOUCH_DEVICE = IS_BROWSER && WINDOW.document.documentElement ? "ontouchstart" in WINDOW.document.documentElement : false;
			var HAS_POINTER_EVENT = IS_BROWSER ? "PointerEvent" in WINDOW : false;
			var NAMESPACE = "cropper";
			var ACTION_ALL = "all";
			var ACTION_CROP = "crop";
			var ACTION_MOVE = "move";
			var ACTION_ZOOM = "zoom";
			var ACTION_EAST = "e";
			var ACTION_WEST = "w";
			var ACTION_SOUTH = "s";
			var ACTION_NORTH = "n";
			var ACTION_NORTH_EAST = "ne";
			var ACTION_NORTH_WEST = "nw";
			var ACTION_SOUTH_EAST = "se";
			var ACTION_SOUTH_WEST = "sw";
			var CLASS_CROP = "".concat(NAMESPACE, "-crop");
			var CLASS_DISABLED = "".concat(NAMESPACE, "-disabled");
			var CLASS_HIDDEN = "".concat(NAMESPACE, "-hidden");
			var CLASS_HIDE = "".concat(NAMESPACE, "-hide");
			var CLASS_INVISIBLE = "".concat(NAMESPACE, "-invisible");
			var CLASS_MODAL = "".concat(NAMESPACE, "-modal");
			var CLASS_MOVE = "".concat(NAMESPACE, "-move");
			var DATA_ACTION = "".concat(NAMESPACE, "Action");
			var DATA_PREVIEW = "".concat(NAMESPACE, "Preview");
			var DRAG_MODE_CROP = "crop";
			var DRAG_MODE_MOVE = "move";
			var DRAG_MODE_NONE = "none";
			var EVENT_BLUR = "blur";
			var EVENT_CROP = "crop";
			var EVENT_CROP_END = "cropend";
			var EVENT_CROP_MOVE = "cropmove";
			var EVENT_CROP_START = "cropstart";
			var EVENT_DBLCLICK = "dblclick";
			var EVENT_ERROR = "error";
			var EVENT_TOUCH_START = IS_TOUCH_DEVICE ? "touchstart" : "mousedown";
			var EVENT_TOUCH_MOVE = IS_TOUCH_DEVICE ? "touchmove" : "mousemove";
			var EVENT_TOUCH_END = IS_TOUCH_DEVICE ? "touchend touchcancel" : "mouseup";
			var EVENT_POINTER_DOWN = HAS_POINTER_EVENT ? "pointerdown" : EVENT_TOUCH_START;
			var EVENT_POINTER_MOVE = HAS_POINTER_EVENT ? "pointermove" : EVENT_TOUCH_MOVE;
			var EVENT_POINTER_UP = HAS_POINTER_EVENT ? "pointerup pointercancel" : EVENT_TOUCH_END;
			var EVENT_READY = "ready";
			var EVENT_RESIZE = "resize";
			var EVENT_WHEEL = "wheel";
			var EVENT_ZOOM = "zoom";
			var MIME_TYPE_JPEG = "image/jpeg";
			var REGEXP_ACTIONS = /^(e|w|s|n|se|sw|ne|nw|all|crop|move|zoom)$/;
			var REGEXP_DATA_URL = /^data:/;
			var REGEXP_DATA_URL_JPEG = /^data:image\/jpeg;base64,/;
			var REGEXP_SPACES = /\s+/;
			var REGEXP_TAG_NAME = /^(img|canvas)$/i;
			var MIN_CONTAINER_WIDTH = 200;
			var MIN_CONTAINER_HEIGHT = 100;
			var DEFAULTS = {
				viewMode: 0,
				dragMode: DRAG_MODE_CROP,
				initialAspectRatio: NaN,
				aspectRatio: NaN,
				borderRadius: 0,
				data: null,
				preview: "",
				responsive: true,
				restore: true,
				checkCrossOrigin: true,
				checkOrientation: true,
				modal: true,
				guides: true,
				center: true,
				highlight: true,
				background: true,
				autoCrop: true,
				autoCropArea: .8,
				movable: true,
				rotatable: true,
				scalable: true,
				zoomable: true,
				zoomOnTouch: true,
				zoomOnWheel: true,
				zoomAroundCenter: false,
				wheelZoomRatio: .1,
				cropBoxMovable: true,
				cropBoxMoveMode: "cursor",
				cropBoxResizable: true,
				cropBoxResizeAroundCenter: false,
				toggleDragModeOnDblclick: true,
				minCanvasWidth: 0,
				minCanvasHeight: 0,
				minCropBoxWidth: 0,
				minCropBoxHeight: 0,
				minContainerWidth: MIN_CONTAINER_WIDTH,
				minContainerHeight: MIN_CONTAINER_HEIGHT,
				ready: null,
				error: null,
				cropstart: null,
				cropmove: null,
				cropend: null,
				crop: null,
				zoom: null
			};
			var TEMPLATE = "<div class=\"cropper-container\" touch-action=\"none\"><div class=\"cropper-wrap-box\"><div class=\"cropper-canvas\"></div></div><div class=\"cropper-drag-box\"></div><div class=\"cropper-crop-box\"><span class=\"cropper-view-box\"></span><span class=\"cropper-dashed dashed-h\"></span><span class=\"cropper-dashed dashed-v\"></span><span class=\"cropper-center\"></span><span class=\"cropper-face\"></span><span class=\"cropper-line line-e\" data-cropper-action=\"e\"></span><span class=\"cropper-line line-n\" data-cropper-action=\"n\"></span><span class=\"cropper-line line-w\" data-cropper-action=\"w\"></span><span class=\"cropper-line line-s\" data-cropper-action=\"s\"></span><span class=\"cropper-point point-e\" data-cropper-action=\"e\"></span><span class=\"cropper-point point-n\" data-cropper-action=\"n\"></span><span class=\"cropper-point point-w\" data-cropper-action=\"w\"></span><span class=\"cropper-point point-s\" data-cropper-action=\"s\"></span><span class=\"cropper-point point-ne\" data-cropper-action=\"ne\"></span><span class=\"cropper-point point-nw\" data-cropper-action=\"nw\"></span><span class=\"cropper-point point-sw\" data-cropper-action=\"sw\"></span><span class=\"cropper-point point-se\" data-cropper-action=\"se\"></span></div></div>";
			/**
			* Check if the given value is not a number.
			*/
			var isNaN = Number.isNaN || WINDOW.isNaN;
			/**
			* Check if the given value is a finite number.
			*/
			var isFinite = Number.isFinite || WINDOW.isFinite;
			/**
			* Check if the given value is a number.
			* @param {*} value - The value to check.
			* @returns {boolean} Returns `true` if the given value is a number, else `false`.
			*/
			function isNumber(value) {
				return typeof value === "number" && !isNaN(value);
			}
			/**
			* Check if the given value is a positive number.
			* @param {*} value - The value to check.
			* @returns {boolean} Returns `true` if the given value is a positive number, else `false`.
			*/
			var isPositiveNumber = function isPositiveNumber(value) {
				return value > 0 && value < Infinity;
			};
			/**
			* Check if the given value is undefined.
			* @param {*} value - The value to check.
			* @returns {boolean} Returns `true` if the given value is undefined, else `false`.
			*/
			function isUndefined(value) {
				return typeof value === "undefined";
			}
			/**
			* Check if the given value is an object.
			* @param {*} value - The value to check.
			* @returns {boolean} Returns `true` if the given value is an object, else `false`.
			*/
			function isObject(value) {
				return _typeof(value) === "object" && value !== null;
			}
			var hasOwnProperty = Object.prototype.hasOwnProperty;
			/**
			* Check if the given value is a plain object.
			* @param {*} value - The value to check.
			* @returns {boolean} Returns `true` if the given value is a plain object, else `false`.
			*/
			function isPlainObject(value) {
				if (!isObject(value)) return false;
				try {
					var constructor = value.constructor;
					var prototype = constructor.prototype;
					return constructor && prototype && hasOwnProperty.call(prototype, "isPrototypeOf");
				} catch (error) {
					return false;
				}
			}
			/**
			* Check if the given value is a function.
			* @param {*} value - The value to check.
			* @returns {boolean} Returns `true` if the given value is a function, else `false`.
			*/
			function isFunction(value) {
				return typeof value === "function";
			}
			var slice = Array.prototype.slice;
			/**
			* Convert array-like or iterable object to an array.
			* @param {*} value - The value to convert.
			* @returns {Array} Returns a new array.
			*/
			function toArray(value) {
				return Array.from ? Array.from(value) : slice.call(value);
			}
			/**
			* Iterate the given data.
			* @param {*} data - The data to iterate.
			* @param {Function} callback - The process function for each element.
			* @returns {*} The original data.
			*/
			function forEach(data, callback) {
				if (data && isFunction(callback)) {
					if (Array.isArray(data) || isNumber(data.length)) toArray(data).forEach(function(value, key) {
						callback.call(data, value, key, data);
					});
					else if (isObject(data)) Object.keys(data).forEach(function(key) {
						callback.call(data, data[key], key, data);
					});
				}
				return data;
			}
			/**
			* Extend the given object.
			* @param {*} target - The target object to extend.
			* @param {*} args - The rest objects for merging to the target object.
			* @returns {Object} The extended object.
			*/
			var assign = Object.assign || function assign(target) {
				for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) args[_key - 1] = arguments[_key];
				if (isObject(target) && args.length > 0) args.forEach(function(arg) {
					if (isObject(arg)) Object.keys(arg).forEach(function(key) {
						target[key] = arg[key];
					});
				});
				return target;
			};
			var REGEXP_DECIMALS = /\.\d*(?:0|9){12}\d*$/;
			/**
			* Normalize decimal number.
			* Check out {@link https://0.30000000000000004.com/}
			* @param {number} value - The value to normalize.
			* @param {number} [times=100000000000] - The times for normalizing.
			* @returns {number} Returns the normalized number.
			*/
			function normalizeDecimalNumber(value) {
				var times = arguments.length > 1 && arguments[1] !== void 0 ? arguments[1] : 1e11;
				return Math.abs(value) < 1 / times || REGEXP_DECIMALS.test(value) ? Math.round(value * times) / times : value;
			}
			var REGEXP_SUFFIX = /^width|height|left|top|marginLeft|marginTop$/;
			/**
			* Apply styles to the given element.
			* @param {Element} element - The target element.
			* @param {Object} styles - The styles for applying.
			*/
			function setStyle(element, styles) {
				var style = element.style;
				forEach(styles, function(value, property) {
					if (REGEXP_SUFFIX.test(property) && isNumber(value)) value = "".concat(value, "px");
					style[property] = value;
				});
			}
			/**
			* Check if the given element has a special class.
			* @param {Element} element - The element to check.
			* @param {string} value - The class to search.
			* @returns {boolean} Returns `true` if the special class was found.
			*/
			function hasClass(element, value) {
				return element.classList ? element.classList.contains(value) : element.className.split(REGEXP_SPACES).indexOf(value) > -1;
			}
			/**
			* Add classes to the given element.
			* @param {Element} element - The target element.
			* @param {string} value - The classes to be added.
			*/
			function addClass(element, value) {
				if (!value) return;
				if (isNumber(element.length)) {
					forEach(element, function(elem) {
						addClass(elem, value);
					});
					return;
				}
				if (element.classList) {
					element.classList.add(value);
					return;
				}
				var className = element.className.trim();
				if (!className) element.className = value;
				else if (className.indexOf(value) < 0) element.className = "".concat(className, " ").concat(value);
			}
			/**
			* Remove classes from the given element.
			* @param {Element} element - The target element.
			* @param {string} value - The classes to be removed.
			*/
			function removeClass(element, value) {
				if (!value) return;
				if (isNumber(element.length)) {
					forEach(element, function(elem) {
						removeClass(elem, value);
					});
					return;
				}
				if (element.classList) {
					element.classList.remove(value);
					return;
				}
				if (element.className.indexOf(value) >= 0) element.className = element.className.split(REGEXP_SPACES).filter(function(item) {
					return item && item !== value;
				}).join(" ");
			}
			/**
			* Add or remove classes from the given element.
			* @param {Element} element - The target element.
			* @param {string} value - The classes to be toggled.
			* @param {boolean} added - Add only.
			*/
			function toggleClass(element, value, added) {
				if (!value) return;
				if (isNumber(element.length)) {
					forEach(element, function(elem) {
						toggleClass(elem, value, added);
					});
					return;
				}
				if (added) addClass(element, value);
				else removeClass(element, value);
			}
			var REGEXP_CAMEL_CASE = /([a-z\d])([A-Z])/g;
			/**
			* Transform the given string from camelCase to kebab-case
			* @param {string} value - The value to transform.
			* @returns {string} The transformed value.
			*/
			function toParamCase(value) {
				return typeof value === "string" ? value.replace(REGEXP_CAMEL_CASE, "$1-$2").toLowerCase() : "";
			}
			/**
			* Get data from the given element.
			* @param {Element} element - The target element.
			* @param {string} name - The data key to get.
			* @returns {string} The data value.
			*/
			function getData(element, name) {
				if (isObject(element[name])) return element[name];
				if (element.dataset) return element.dataset[name];
				return element.getAttribute("data-".concat(toParamCase(name)));
			}
			/**
			* Set data to the given element.
			* @param {Element} element - The target element.
			* @param {string} name - The data key to set.
			* @param {string} data - The data value.
			*/
			function setData(element, name, data) {
				if (isObject(data)) element[name] = data;
				else if (element.dataset) element.dataset[name] = data;
				else element.setAttribute("data-".concat(toParamCase(name)), data);
			}
			/**
			* Remove data from the given element.
			* @param {Element} element - The target element.
			* @param {string} name - The data key to remove.
			*/
			function removeData(element, name) {
				if (isObject(element[name])) try {
					delete element[name];
				} catch (error) {
					element[name] = void 0;
				}
				else if (element.dataset) try {
					delete element.dataset[name];
				} catch (error) {
					element.dataset[name] = void 0;
				}
				else element.removeAttribute("data-".concat(toParamCase(name)));
			}
			var onceSupported = function() {
				var supported = false;
				if (IS_BROWSER) {
					var once = false;
					var listener = function listener() {};
					var options = Object.defineProperty({}, "once", {
						get: function get() {
							supported = true;
							return once;
						},
						/**
						* This setter can fix a `TypeError` in strict mode
						* {@link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Errors/Getter_only}
						* @param {boolean} value - The value to set
						*/
						set: function set(value) {
							once = value;
						}
					});
					WINDOW.addEventListener("test", listener, options);
					WINDOW.removeEventListener("test", listener, options);
				}
				return supported;
			}();
			/**
			* Remove event listener from the target element.
			* @param {Element} element - The event target.
			* @param {string} type - The event type(s).
			* @param {Function} listener - The event listener.
			* @param {Object} options - The event options.
			*/
			function removeListener(element, type, listener) {
				var options = arguments.length > 3 && arguments[3] !== void 0 ? arguments[3] : {};
				if (!element || !type || !isFunction(element.removeEventListener)) return;
				var handler = listener;
				type.trim().split(REGEXP_SPACES).forEach(function(event) {
					if (!onceSupported) {
						var listeners = element.__cropperListeners__ || {};
						if (listeners[event] && listeners[event][listener]) {
							handler = listeners[event][listener];
							delete listeners[event][listener];
							if (Object.keys(listeners[event]).length === 0) delete listeners[event];
							if (Object.keys(listeners).length === 0) delete element.__cropperListeners__;
						}
					}
					element.removeEventListener(event, handler, options);
				});
			}
			/**
			* Add event listener to the target element.
			* @param {Element} element - The event target.
			* @param {string} type - The event type(s).
			* @param {Function} listener - The event listener.
			* @param {Object} options - The event options.
			*/
			function addListener(element, type, listener) {
				var options = arguments.length > 3 && arguments[3] !== void 0 ? arguments[3] : {};
				if (!element || !type || !isFunction(element.addEventListener) || !isFunction(listener)) return;
				var _handler = listener;
				type.trim().split(REGEXP_SPACES).forEach(function(event) {
					if (options.once && !onceSupported) {
						var listeners = element.__cropperListeners__ || {};
						_handler = function handler() {
							if (listeners[event] && listeners[event][listener]) delete listeners[event][listener];
							element.removeEventListener(event, _handler, options);
							for (var _len2 = arguments.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) args[_key2] = arguments[_key2];
							listener.apply(element, args);
						};
						if (!listeners[event]) listeners[event] = {};
						if (listeners[event][listener]) element.removeEventListener(event, listeners[event][listener], options);
						listeners[event][listener] = _handler;
						element.__cropperListeners__ = listeners;
					}
					element.addEventListener(event, _handler, options);
				});
			}
			/**
			* Dispatch event on the target element.
			* @param {Element} element - The event target.
			* @param {string} type - The event type(s).
			* @param {Object} data - The additional event data.
			* @returns {boolean} Indicate if the event is default prevented or not.
			*/
			function dispatchEvent(element, type, data) {
				if (!element || !type || !isFunction(element.dispatchEvent)) return false;
				var event;
				if (isFunction(Event) && isFunction(CustomEvent)) event = new CustomEvent(type, {
					detail: data,
					bubbles: true,
					cancelable: true
				});
				else if (isObject(document) && isFunction(document.createEvent)) {
					event = document.createEvent("CustomEvent");
					event.initCustomEvent(type, true, true, data);
				} else return false;
				return element.dispatchEvent(event);
			}
			/**
			* Get the offset based on the document.
			* @param {Element} element - The target element.
			* @returns {Object} The offset data.
			*/
			function getOffset(element) {
				if (!element || !isFunction(element.getBoundingClientRect)) return {
					left: 0,
					top: 0
				};
				var box = element.getBoundingClientRect();
				var _ref$documentElement = (document || {}).documentElement, documentElement = _ref$documentElement === void 0 ? {} : _ref$documentElement;
				var pageXOffset = WINDOW.pageXOffset || 0;
				var pageYOffset = WINDOW.pageYOffset || 0;
				return {
					left: box.left + (pageXOffset - (documentElement.clientLeft || 0)),
					top: box.top + (pageYOffset - (documentElement.clientTop || 0))
				};
			}
			var location = WINDOW.location;
			var REGEXP_ORIGINS = /^(\w+:)\/\/([^:/?#]*):?(\d*)/i;
			/**
			* Check if the given URL is a cross origin URL.
			* @param {string} url - The target URL.
			* @returns {boolean} Returns `true` if the given URL is a cross origin URL, else `false`.
			*/
			function isCrossOriginURL(url) {
				var parts = url.match(REGEXP_ORIGINS);
				return parts !== null && (parts[1] !== location.protocol || parts[2] !== location.hostname || parts[3] !== location.port);
			}
			/**
			* Add timestamp to the given URL.
			* @param {string} url - The target URL.
			* @returns {string} The result URL.
			*/
			function addTimestamp(url) {
				var hashIndex = url.indexOf("#");
				var hash = hashIndex >= 0 ? url.slice(hashIndex) : "";
				var baseUrl = hashIndex >= 0 ? url.slice(0, hashIndex) : url;
				var timestamp = "timestamp=".concat((/* @__PURE__ */ new Date()).getTime());
				return "".concat(baseUrl).concat(baseUrl.indexOf("?") === -1 ? "?" : "&").concat(timestamp).concat(hash);
			}
			/**
			* Get transforms based on the given object.
			* @param {Object} obj - The target object.
			* @returns {string} A string contains transform values.
			*/
			function getTransforms(_ref2) {
				var rotate = _ref2.rotate, scaleX = _ref2.scaleX, scaleY = _ref2.scaleY, translateX = _ref2.translateX, translateY = _ref2.translateY;
				var values = [];
				if (isNumber(translateX) && translateX !== 0) values.push("translateX(".concat(translateX, "px)"));
				if (isNumber(translateY) && translateY !== 0) values.push("translateY(".concat(translateY, "px)"));
				if (isNumber(rotate) && rotate !== 0) values.push("rotate(".concat(rotate, "deg)"));
				if (isNumber(scaleX) && scaleX !== 1) values.push("scaleX(".concat(scaleX, ")"));
				if (isNumber(scaleY) && scaleY !== 1) values.push("scaleY(".concat(scaleY, ")"));
				var transform = values.length ? values.join(" ") : "none";
				return {
					WebkitTransform: transform,
					msTransform: transform,
					transform
				};
			}
			/**
			* Get the max ratio of a group of pointers.
			* @param {string} pointers - The target pointers.
			* @returns {number} The result ratio.
			*/
			function getMaxZoomRatio(pointers) {
				var pointers2 = _objectSpread2({}, pointers);
				var maxRatio = 0;
				forEach(pointers, function(pointer, pointerId) {
					delete pointers2[pointerId];
					forEach(pointers2, function(pointer2) {
						var x1 = Math.abs(pointer.startX - pointer2.startX);
						var y1 = Math.abs(pointer.startY - pointer2.startY);
						var x2 = Math.abs(pointer.endX - pointer2.endX);
						var y2 = Math.abs(pointer.endY - pointer2.endY);
						var z1 = Math.sqrt(x1 * x1 + y1 * y1);
						var z2 = Math.sqrt(x2 * x2 + y2 * y2);
						if (!isFinite(z1) || !isFinite(z2) || z1 === 0 || z2 === z1) return;
						var ratio = (z2 - z1) / z1;
						if (isFinite(ratio) && Math.abs(ratio) > Math.abs(maxRatio)) maxRatio = ratio;
					});
				});
				return maxRatio;
			}
			/**
			* Get a pointer from an event object.
			* @param {Object} event - The target event object.
			* @param {boolean} endOnly - Indicates if only returns the end point coordinate or not.
			* @returns {Object} The result pointer contains start and/or end point coordinates.
			*/
			function getPointer(_ref3, endOnly) {
				var pageX = _ref3.pageX, pageY = _ref3.pageY;
				var end = {
					endX: pageX,
					endY: pageY
				};
				return endOnly ? end : _objectSpread2({
					startX: pageX,
					startY: pageY
				}, end);
			}
			/**
			* Get the center point coordinate of a group of pointers.
			* @param {Object} pointers - The target pointers.
			* @returns {Object} The center point coordinate.
			*/
			function getPointersCenter(pointers) {
				var pageX = 0;
				var pageY = 0;
				var count = 0;
				forEach(pointers, function(_ref4) {
					var startX = _ref4.startX, startY = _ref4.startY;
					pageX += startX;
					pageY += startY;
					count += 1;
				});
				pageX /= count;
				pageY /= count;
				return {
					pageX,
					pageY
				};
			}
			/**
			* Get the max sizes in a rectangle under the given aspect ratio.
			* @param {Object} data - The original sizes.
			* @param {string} [type='contain'] - The adjust type.
			* @returns {Object} The result sizes.
			*/
			function getAdjustedSizes(_ref5) {
				var aspectRatio = _ref5.aspectRatio, height = _ref5.height, width = _ref5.width;
				var type = arguments.length > 1 && arguments[1] !== void 0 ? arguments[1] : "contain";
				var isValidWidth = isPositiveNumber(width);
				var isValidHeight = isPositiveNumber(height);
				if (isValidWidth && isValidHeight) {
					var adjustedWidth = height * aspectRatio;
					if (type === "contain" && adjustedWidth > width || type === "cover" && adjustedWidth < width) height = width / aspectRatio;
					else width = height * aspectRatio;
				} else if (isValidWidth) height = width / aspectRatio;
				else if (isValidHeight) width = height * aspectRatio;
				return {
					width,
					height
				};
			}
			/**
			* Get the new sizes of a rectangle after rotated.
			* @param {Object} data - The original sizes.
			* @returns {Object} The result sizes.
			*/
			function getRotatedSizes(_ref6) {
				var width = _ref6.width, height = _ref6.height, degree = _ref6.degree;
				degree = Math.abs(degree) % 180;
				if (degree === 90) return {
					width: height,
					height: width
				};
				var arc = degree % 90 * Math.PI / 180;
				var sinArc = Math.sin(arc);
				var cosArc = Math.cos(arc);
				var newWidth = width * cosArc + height * sinArc;
				var newHeight = width * sinArc + height * cosArc;
				return degree > 90 ? {
					width: newHeight,
					height: newWidth
				} : {
					width: newWidth,
					height: newHeight
				};
			}
			/**
			* Get a canvas which draws the given image.
			* @param {HTMLImageElement} image - The image for drawing.
			* @param {Object} imageData - The image data.
			* @param {Object} canvasData - The canvas data.
			* @param {Object} options - The options.
			* @returns {HTMLCanvasElement} The result canvas.
			*/
			function getSourceCanvas(image, _ref7, _ref8, _ref9) {
				var imageAspectRatio = _ref7.aspectRatio, imageNaturalWidth = _ref7.naturalWidth, imageNaturalHeight = _ref7.naturalHeight, _ref7$rotate = _ref7.rotate, rotate = _ref7$rotate === void 0 ? 0 : _ref7$rotate, _ref7$scaleX = _ref7.scaleX, scaleX = _ref7$scaleX === void 0 ? 1 : _ref7$scaleX, _ref7$scaleY = _ref7.scaleY, scaleY = _ref7$scaleY === void 0 ? 1 : _ref7$scaleY;
				var aspectRatio = _ref8.aspectRatio, naturalWidth = _ref8.naturalWidth, naturalHeight = _ref8.naturalHeight;
				var _ref9$fillColor = _ref9.fillColor, fillColor = _ref9$fillColor === void 0 ? "transparent" : _ref9$fillColor, _ref9$imageSmoothingE = _ref9.imageSmoothingEnabled, imageSmoothingEnabled = _ref9$imageSmoothingE === void 0 ? true : _ref9$imageSmoothingE, _ref9$imageSmoothingQ = _ref9.imageSmoothingQuality, imageSmoothingQuality = _ref9$imageSmoothingQ === void 0 ? "low" : _ref9$imageSmoothingQ, _ref9$maxWidth = _ref9.maxWidth, maxWidth = _ref9$maxWidth === void 0 ? Infinity : _ref9$maxWidth, _ref9$maxHeight = _ref9.maxHeight, maxHeight = _ref9$maxHeight === void 0 ? Infinity : _ref9$maxHeight, _ref9$minWidth = _ref9.minWidth, minWidth = _ref9$minWidth === void 0 ? 0 : _ref9$minWidth, _ref9$minHeight = _ref9.minHeight, minHeight = _ref9$minHeight === void 0 ? 0 : _ref9$minHeight;
				var canvas = document.createElement("canvas");
				var context = canvas.getContext("2d");
				var maxSizes = getAdjustedSizes({
					aspectRatio,
					width: maxWidth,
					height: maxHeight
				});
				var minSizes = getAdjustedSizes({
					aspectRatio,
					width: minWidth,
					height: minHeight
				}, "cover");
				var width = Math.min(maxSizes.width, Math.max(minSizes.width, naturalWidth));
				var height = Math.min(maxSizes.height, Math.max(minSizes.height, naturalHeight));
				var destMaxSizes = getAdjustedSizes({
					aspectRatio: imageAspectRatio,
					width: maxWidth,
					height: maxHeight
				});
				var destMinSizes = getAdjustedSizes({
					aspectRatio: imageAspectRatio,
					width: minWidth,
					height: minHeight
				}, "cover");
				var destWidth = Math.min(destMaxSizes.width, Math.max(destMinSizes.width, imageNaturalWidth));
				var destHeight = Math.min(destMaxSizes.height, Math.max(destMinSizes.height, imageNaturalHeight));
				var params = [
					-destWidth / 2,
					-destHeight / 2,
					destWidth,
					destHeight
				];
				canvas.width = normalizeDecimalNumber(width);
				canvas.height = normalizeDecimalNumber(height);
				context.fillStyle = fillColor;
				context.fillRect(0, 0, width, height);
				context.save();
				context.translate(width / 2, height / 2);
				context.rotate(rotate * Math.PI / 180);
				context.scale(scaleX, scaleY);
				context.imageSmoothingEnabled = imageSmoothingEnabled;
				context.imageSmoothingQuality = imageSmoothingQuality;
				context.drawImage.apply(context, [image].concat(_toConsumableArray(params.map(function(param) {
					return Math.floor(normalizeDecimalNumber(param));
				}))));
				context.restore();
				return canvas;
			}
			var fromCharCode = String.fromCharCode;
			/**
			* Get string from char code in data view.
			* @param {DataView} dataView - The data view for read.
			* @param {number} start - The start index.
			* @param {number} length - The read length.
			* @returns {string} The read result.
			*/
			function getStringFromCharCode(dataView, start, length) {
				var str = "";
				length += start;
				for (var i = start; i < length; i += 1) str += fromCharCode(dataView.getUint8(i));
				return str;
			}
			var REGEXP_DATA_URL_HEAD = /^data:.*,/;
			/**
			* Transform Data URL to array buffer.
			* @param {string} dataURL - The Data URL to transform.
			* @returns {ArrayBuffer} The result array buffer.
			*/
			function dataURLToArrayBuffer(dataURL) {
				var base64 = dataURL.replace(REGEXP_DATA_URL_HEAD, "");
				var binary = atob(base64);
				var arrayBuffer = new ArrayBuffer(binary.length);
				var uint8 = new Uint8Array(arrayBuffer);
				forEach(uint8, function(value, i) {
					uint8[i] = binary.charCodeAt(i);
				});
				return arrayBuffer;
			}
			/**
			* Transform array buffer to Data URL.
			* @param {ArrayBuffer} arrayBuffer - The array buffer to transform.
			* @param {string} mimeType - The mime type of the Data URL.
			* @returns {string} The result Data URL.
			*/
			function arrayBufferToDataURL(arrayBuffer, mimeType) {
				var chunks = [];
				var chunkSize = 8192;
				var uint8 = new Uint8Array(arrayBuffer);
				while (uint8.length > 0) {
					chunks.push(fromCharCode.apply(null, toArray(uint8.subarray(0, chunkSize))));
					uint8 = uint8.subarray(chunkSize);
				}
				return "data:".concat(mimeType, ";base64,").concat(btoa(chunks.join("")));
			}
			/**
			* Get orientation value from given array buffer.
			* @param {ArrayBuffer} arrayBuffer - The array buffer to read.
			* @returns {number} The read orientation value.
			*/
			function resetAndGetOrientation(arrayBuffer) {
				var dataView = new DataView(arrayBuffer);
				var orientation;
				try {
					var littleEndian;
					var app1Start;
					var ifdStart;
					if (dataView.getUint8(0) === 255 && dataView.getUint8(1) === 216) {
						var length = dataView.byteLength;
						var offset = 2;
						while (offset + 1 < length) {
							if (dataView.getUint8(offset) === 255 && dataView.getUint8(offset + 1) === 225) {
								app1Start = offset;
								break;
							}
							offset += 1;
						}
					}
					if (app1Start) {
						var exifIDCode = app1Start + 4;
						var tiffOffset = app1Start + 10;
						if (getStringFromCharCode(dataView, exifIDCode, 4) === "Exif") {
							var endianness = dataView.getUint16(tiffOffset);
							littleEndian = endianness === 18761;
							if (littleEndian || endianness === 19789) {
								if (dataView.getUint16(tiffOffset + 2, littleEndian) === 42) {
									var firstIFDOffset = dataView.getUint32(tiffOffset + 4, littleEndian);
									if (firstIFDOffset >= 8) ifdStart = tiffOffset + firstIFDOffset;
								}
							}
						}
					}
					if (ifdStart) {
						var _length = dataView.getUint16(ifdStart, littleEndian);
						var _offset;
						var i = 0;
						for (; i < _length; i += 1) {
							_offset = ifdStart + i * 12 + 2;
							if (dataView.getUint16(_offset, littleEndian) === 274) {
								_offset += 8;
								orientation = dataView.getUint16(_offset, littleEndian);
								dataView.setUint16(_offset, 1, littleEndian);
								break;
							}
						}
					}
				} catch (error) {
					orientation = 1;
				}
				return orientation;
			}
			/**
			* Parse Exif Orientation value.
			* @param {number} orientation - The orientation to parse.
			* @returns {Object} The parsed result.
			*/
			function parseOrientation(orientation) {
				var rotate = 0;
				var scaleX = 1;
				var scaleY = 1;
				switch (orientation) {
					case 2:
						scaleX = -1;
						break;
					case 3:
						rotate = -180;
						break;
					case 4:
						scaleY = -1;
						break;
					case 5:
						rotate = 90;
						scaleY = -1;
						break;
					case 6:
						rotate = 90;
						break;
					case 7:
						rotate = 90;
						scaleX = -1;
						break;
					case 8: rotate = -90;
				}
				return {
					rotate,
					scaleX,
					scaleY
				};
			}
			var render = {
				render: function render() {
					this.initContainer();
					this.initCanvas();
					this.initCropBox();
					this.renderCanvas();
					if (this.cropped) this.renderCropBox();
				},
				initContainer: function initContainer() {
					var element = this.element, options = this.options, container = this.container, cropper = this.cropper;
					var minWidth = Number(options.minContainerWidth);
					var minHeight = Number(options.minContainerHeight);
					addClass(cropper, CLASS_HIDDEN);
					removeClass(element, CLASS_HIDDEN);
					var containerData = {
						width: Math.max(container.offsetWidth, minWidth >= 0 ? minWidth : MIN_CONTAINER_WIDTH),
						height: Math.max(container.offsetHeight, minHeight >= 0 ? minHeight : MIN_CONTAINER_HEIGHT)
					};
					this.containerData = containerData;
					setStyle(cropper, {
						width: containerData.width,
						height: containerData.height
					});
					addClass(element, CLASS_HIDDEN);
					removeClass(cropper, CLASS_HIDDEN);
				},
				initCanvas: function initCanvas() {
					var containerData = this.containerData, imageData = this.imageData;
					var viewMode = this.options.viewMode;
					var rotated = Math.abs(imageData.rotate) % 180 === 90;
					var naturalWidth = rotated ? imageData.naturalHeight : imageData.naturalWidth;
					var naturalHeight = rotated ? imageData.naturalWidth : imageData.naturalHeight;
					var aspectRatio = naturalWidth / naturalHeight;
					var canvasWidth = containerData.width;
					var canvasHeight = containerData.height;
					if (containerData.height * aspectRatio > containerData.width) {
						if (viewMode === 3) canvasWidth = containerData.height * aspectRatio;
						else canvasHeight = containerData.width / aspectRatio;
					} else if (viewMode === 3) canvasHeight = containerData.width / aspectRatio;
					else canvasWidth = containerData.height * aspectRatio;
					var canvasData = {
						aspectRatio,
						naturalWidth,
						naturalHeight,
						width: canvasWidth,
						height: canvasHeight
					};
					this.canvasData = canvasData;
					this.limited = viewMode === 1 || viewMode === 2;
					this.limitCanvas(true, true);
					canvasData.width = Math.min(Math.max(canvasData.width, canvasData.minWidth), canvasData.maxWidth);
					canvasData.height = Math.min(Math.max(canvasData.height, canvasData.minHeight), canvasData.maxHeight);
					canvasData.left = (containerData.width - canvasData.width) / 2;
					canvasData.top = (containerData.height - canvasData.height) / 2;
					canvasData.oldLeft = canvasData.left;
					canvasData.oldTop = canvasData.top;
					this.initialCanvasData = assign({}, canvasData);
				},
				limitCanvas: function limitCanvas(sizeLimited, positionLimited) {
					var options = this.options, containerData = this.containerData, canvasData = this.canvasData, cropBoxData = this.cropBoxData;
					var viewMode = options.viewMode;
					var aspectRatio = canvasData.aspectRatio;
					var cropped = this.cropped && cropBoxData;
					if (sizeLimited) {
						var minCanvasWidth = Number(options.minCanvasWidth) || 0;
						var minCanvasHeight = Number(options.minCanvasHeight) || 0;
						if (viewMode > 1) {
							minCanvasWidth = Math.max(minCanvasWidth, containerData.width);
							minCanvasHeight = Math.max(minCanvasHeight, containerData.height);
							if (viewMode === 3) {
								if (minCanvasHeight * aspectRatio > minCanvasWidth) minCanvasWidth = minCanvasHeight * aspectRatio;
								else minCanvasHeight = minCanvasWidth / aspectRatio;
							}
						} else if (viewMode > 0) {
							if (minCanvasWidth) minCanvasWidth = Math.max(minCanvasWidth, cropped ? cropBoxData.width : 0);
							else if (minCanvasHeight) minCanvasHeight = Math.max(minCanvasHeight, cropped ? cropBoxData.height : 0);
							else if (cropped) {
								minCanvasWidth = cropBoxData.width;
								minCanvasHeight = cropBoxData.height;
								if (minCanvasHeight * aspectRatio > minCanvasWidth) minCanvasWidth = minCanvasHeight * aspectRatio;
								else minCanvasHeight = minCanvasWidth / aspectRatio;
							}
						}
						var _getAdjustedSizes = getAdjustedSizes({
							aspectRatio,
							width: minCanvasWidth,
							height: minCanvasHeight
						});
						minCanvasWidth = _getAdjustedSizes.width;
						minCanvasHeight = _getAdjustedSizes.height;
						canvasData.minWidth = minCanvasWidth;
						canvasData.minHeight = minCanvasHeight;
						canvasData.maxWidth = Infinity;
						canvasData.maxHeight = Infinity;
					}
					if (positionLimited) {
						if (viewMode > (cropped ? 0 : 1)) {
							var newCanvasLeft = containerData.width - canvasData.width;
							var newCanvasTop = containerData.height - canvasData.height;
							canvasData.minLeft = Math.min(0, newCanvasLeft);
							canvasData.minTop = Math.min(0, newCanvasTop);
							canvasData.maxLeft = Math.max(0, newCanvasLeft);
							canvasData.maxTop = Math.max(0, newCanvasTop);
							if (cropped && this.limited) {
								canvasData.minLeft = Math.min(cropBoxData.left, cropBoxData.left + (cropBoxData.width - canvasData.width));
								canvasData.minTop = Math.min(cropBoxData.top, cropBoxData.top + (cropBoxData.height - canvasData.height));
								canvasData.maxLeft = cropBoxData.left;
								canvasData.maxTop = cropBoxData.top;
								if (viewMode === 2) {
									if (canvasData.width >= containerData.width) {
										canvasData.minLeft = Math.min(0, newCanvasLeft);
										canvasData.maxLeft = Math.max(0, newCanvasLeft);
									}
									if (canvasData.height >= containerData.height) {
										canvasData.minTop = Math.min(0, newCanvasTop);
										canvasData.maxTop = Math.max(0, newCanvasTop);
									}
								}
							}
						} else {
							canvasData.minLeft = -canvasData.width;
							canvasData.minTop = -canvasData.height;
							canvasData.maxLeft = containerData.width;
							canvasData.maxTop = containerData.height;
						}
					}
				},
				renderCanvas: function renderCanvas(changed, transformed) {
					var canvasData = this.canvasData, imageData = this.imageData;
					if (transformed) {
						var _getRotatedSizes = getRotatedSizes({
							width: imageData.naturalWidth * Math.abs(imageData.scaleX || 1),
							height: imageData.naturalHeight * Math.abs(imageData.scaleY || 1),
							degree: imageData.rotate || 0
						}), naturalWidth = _getRotatedSizes.width, naturalHeight = _getRotatedSizes.height;
						var width = canvasData.width * (naturalWidth / canvasData.naturalWidth);
						var height = canvasData.height * (naturalHeight / canvasData.naturalHeight);
						canvasData.left -= (width - canvasData.width) / 2;
						canvasData.top -= (height - canvasData.height) / 2;
						canvasData.width = width;
						canvasData.height = height;
						canvasData.aspectRatio = naturalWidth / naturalHeight;
						canvasData.naturalWidth = naturalWidth;
						canvasData.naturalHeight = naturalHeight;
						this.limitCanvas(true, false);
					}
					if (canvasData.width > canvasData.maxWidth || canvasData.width < canvasData.minWidth) canvasData.left = canvasData.oldLeft;
					if (canvasData.height > canvasData.maxHeight || canvasData.height < canvasData.minHeight) canvasData.top = canvasData.oldTop;
					canvasData.width = Math.min(Math.max(canvasData.width, canvasData.minWidth), canvasData.maxWidth);
					canvasData.height = Math.min(Math.max(canvasData.height, canvasData.minHeight), canvasData.maxHeight);
					this.limitCanvas(false, true);
					canvasData.left = Math.min(Math.max(canvasData.left, canvasData.minLeft), canvasData.maxLeft);
					canvasData.top = Math.min(Math.max(canvasData.top, canvasData.minTop), canvasData.maxTop);
					canvasData.oldLeft = canvasData.left;
					canvasData.oldTop = canvasData.top;
					setStyle(this.canvas, assign({
						width: canvasData.width,
						height: canvasData.height
					}, getTransforms({
						translateX: canvasData.left,
						translateY: canvasData.top
					})));
					this.renderImage(changed);
					if (this.cropped && this.limited) this.limitCropBox(true, true);
				},
				renderImage: function renderImage(changed) {
					var canvasData = this.canvasData, imageData = this.imageData;
					var width = imageData.naturalWidth * (canvasData.width / canvasData.naturalWidth);
					var height = imageData.naturalHeight * (canvasData.height / canvasData.naturalHeight);
					assign(imageData, {
						width,
						height,
						left: (canvasData.width - width) / 2,
						top: (canvasData.height - height) / 2
					});
					setStyle(this.image, assign({
						width: imageData.width,
						height: imageData.height
					}, getTransforms(assign({
						translateX: imageData.left,
						translateY: imageData.top
					}, imageData))));
					if (changed) this.output();
				},
				initCropBox: function initCropBox() {
					var options = this.options, canvasData = this.canvasData;
					var aspectRatio = options.aspectRatio || options.initialAspectRatio;
					var autoCropArea = Number(options.autoCropArea) || .8;
					var cropBoxData = {
						width: canvasData.width,
						height: canvasData.height
					};
					if (aspectRatio) {
						if (canvasData.height * aspectRatio > canvasData.width) cropBoxData.height = cropBoxData.width / aspectRatio;
						else cropBoxData.width = cropBoxData.height * aspectRatio;
					}
					this.cropBoxData = cropBoxData;
					this.limitCropBox(true, true);
					cropBoxData.width = Math.min(Math.max(cropBoxData.width, cropBoxData.minWidth), cropBoxData.maxWidth);
					cropBoxData.height = Math.min(Math.max(cropBoxData.height, cropBoxData.minHeight), cropBoxData.maxHeight);
					cropBoxData.width = Math.max(cropBoxData.minWidth, cropBoxData.width * autoCropArea);
					cropBoxData.height = Math.max(cropBoxData.minHeight, cropBoxData.height * autoCropArea);
					cropBoxData.left = canvasData.left + (canvasData.width - cropBoxData.width) / 2;
					cropBoxData.top = canvasData.top + (canvasData.height - cropBoxData.height) / 2;
					cropBoxData.oldLeft = cropBoxData.left;
					cropBoxData.oldTop = cropBoxData.top;
					this.initialCropBoxData = assign({}, cropBoxData);
				},
				limitCropBox: function limitCropBox(sizeLimited, positionLimited) {
					var options = this.options, containerData = this.containerData, canvasData = this.canvasData, cropBoxData = this.cropBoxData, limited = this.limited;
					var aspectRatio = options.aspectRatio;
					if (sizeLimited) {
						var minCropBoxWidth = Number(options.minCropBoxWidth) || 0;
						var minCropBoxHeight = Number(options.minCropBoxHeight) || 0;
						var maxCropBoxWidth = limited ? Math.min(containerData.width, canvasData.width, canvasData.width + canvasData.left, containerData.width - canvasData.left) : containerData.width;
						var maxCropBoxHeight = limited ? Math.min(containerData.height, canvasData.height, canvasData.height + canvasData.top, containerData.height - canvasData.top) : containerData.height;
						minCropBoxWidth = Math.min(minCropBoxWidth, containerData.width);
						minCropBoxHeight = Math.min(minCropBoxHeight, containerData.height);
						if (aspectRatio) {
							if (minCropBoxWidth && minCropBoxHeight) {
								if (minCropBoxHeight * aspectRatio > minCropBoxWidth) minCropBoxHeight = minCropBoxWidth / aspectRatio;
								else minCropBoxWidth = minCropBoxHeight * aspectRatio;
							} else if (minCropBoxWidth) minCropBoxHeight = minCropBoxWidth / aspectRatio;
							else if (minCropBoxHeight) minCropBoxWidth = minCropBoxHeight * aspectRatio;
							if (maxCropBoxHeight * aspectRatio > maxCropBoxWidth) maxCropBoxHeight = maxCropBoxWidth / aspectRatio;
							else maxCropBoxWidth = maxCropBoxHeight * aspectRatio;
						}
						cropBoxData.minWidth = Math.min(minCropBoxWidth, maxCropBoxWidth);
						cropBoxData.minHeight = Math.min(minCropBoxHeight, maxCropBoxHeight);
						cropBoxData.maxWidth = maxCropBoxWidth;
						cropBoxData.maxHeight = maxCropBoxHeight;
					}
					if (positionLimited) {
						if (limited) {
							cropBoxData.minLeft = Math.max(0, canvasData.left);
							cropBoxData.minTop = Math.max(0, canvasData.top);
							cropBoxData.maxLeft = Math.min(containerData.width, canvasData.left + canvasData.width) - cropBoxData.width;
							cropBoxData.maxTop = Math.min(containerData.height, canvasData.top + canvasData.height) - cropBoxData.height;
						} else {
							cropBoxData.minLeft = 0;
							cropBoxData.minTop = 0;
							cropBoxData.maxLeft = containerData.width - cropBoxData.width;
							cropBoxData.maxTop = containerData.height - cropBoxData.height;
						}
					}
				},
				renderCropBox: function renderCropBox() {
					var options = this.options, containerData = this.containerData, cropBoxData = this.cropBoxData;
					if (cropBoxData.width > cropBoxData.maxWidth || cropBoxData.width < cropBoxData.minWidth) cropBoxData.left = cropBoxData.oldLeft;
					if (cropBoxData.height > cropBoxData.maxHeight || cropBoxData.height < cropBoxData.minHeight) cropBoxData.top = cropBoxData.oldTop;
					cropBoxData.width = Math.min(Math.max(cropBoxData.width, cropBoxData.minWidth), cropBoxData.maxWidth);
					cropBoxData.height = Math.min(Math.max(cropBoxData.height, cropBoxData.minHeight), cropBoxData.maxHeight);
					this.limitCropBox(false, true);
					cropBoxData.left = Math.min(Math.max(cropBoxData.left, cropBoxData.minLeft), cropBoxData.maxLeft);
					cropBoxData.top = Math.min(Math.max(cropBoxData.top, cropBoxData.minTop), cropBoxData.maxTop);
					cropBoxData.oldLeft = cropBoxData.left;
					cropBoxData.oldTop = cropBoxData.top;
					if (options.movable && options.cropBoxMovable) setData(this.face, DATA_ACTION, cropBoxData.width >= containerData.width && cropBoxData.height >= containerData.height ? ACTION_MOVE : ACTION_ALL);
					setStyle(this.cropBox, assign({
						width: cropBoxData.width,
						height: cropBoxData.height,
						borderRadius: options.borderRadius
					}, getTransforms({
						translateX: cropBoxData.left,
						translateY: cropBoxData.top
					})));
					if (this.cropped && this.limited) this.limitCanvas(true, true);
					if (!this.disabled) this.output();
				},
				output: function output() {
					this.preview();
					dispatchEvent(this.element, EVENT_CROP, this.getData());
				}
			};
			var preview = {
				initPreview: function initPreview() {
					var element = this.element, crossOrigin = this.crossOrigin;
					var preview = this.options.preview;
					var url = crossOrigin ? this.crossOriginUrl : this.url;
					var alt = element.alt || "The image to preview";
					var image = document.createElement("img");
					if (crossOrigin) image.crossOrigin = crossOrigin;
					image.src = url;
					image.alt = alt;
					this.viewBox.appendChild(image);
					this.viewBoxImage = image;
					if (!preview) return;
					var previews = preview;
					if (typeof preview === "string") previews = element.ownerDocument.querySelectorAll(preview);
					else if (preview.querySelector) previews = [preview];
					this.previews = previews;
					forEach(previews, function(el) {
						var img = document.createElement("img");
						setData(el, DATA_PREVIEW, {
							width: el.offsetWidth,
							height: el.offsetHeight,
							html: el.innerHTML
						});
						if (crossOrigin) img.crossOrigin = crossOrigin;
						img.src = url;
						img.alt = alt;
						/**
						* Override img element styles
						* Add `display:block` to avoid margin top issue
						* Add `height:auto` to override `height` attribute on IE8
						* (Occur only when margin-top <= -height)
						*/
						img.style.cssText = "display:block;width:100%;height:auto;min-width:0!important;min-height:0!important;max-width:none!important;max-height:none!important;image-orientation:0deg!important;";
						el.innerHTML = "";
						el.appendChild(img);
					});
				},
				resetPreview: function resetPreview() {
					forEach(this.previews, function(element) {
						var data = getData(element, DATA_PREVIEW);
						setStyle(element, {
							width: data.width,
							height: data.height
						});
						element.innerHTML = data.html;
						removeData(element, DATA_PREVIEW);
					});
				},
				preview: function preview() {
					var imageData = this.imageData, canvasData = this.canvasData, cropBoxData = this.cropBoxData;
					var cropBoxWidth = cropBoxData.width, cropBoxHeight = cropBoxData.height;
					var width = imageData.width, height = imageData.height;
					var left = cropBoxData.left - canvasData.left - imageData.left;
					var top = cropBoxData.top - canvasData.top - imageData.top;
					if (!this.cropped || this.disabled) return;
					setStyle(this.viewBoxImage, assign({
						width,
						height
					}, getTransforms(assign({
						translateX: -left,
						translateY: -top
					}, imageData))));
					forEach(this.previews, function(element) {
						var data = getData(element, DATA_PREVIEW);
						var originalWidth = data.width;
						var originalHeight = data.height;
						var newWidth = originalWidth;
						var newHeight = originalHeight;
						var ratio = 1;
						if (cropBoxWidth) {
							ratio = originalWidth / cropBoxWidth;
							newHeight = cropBoxHeight * ratio;
						}
						if (cropBoxHeight && newHeight > originalHeight) {
							ratio = originalHeight / cropBoxHeight;
							newWidth = cropBoxWidth * ratio;
							newHeight = originalHeight;
						}
						setStyle(element, {
							width: newWidth,
							height: newHeight
						});
						setStyle(element.getElementsByTagName("img")[0], assign({
							width: width * ratio,
							height: height * ratio
						}, getTransforms(assign({
							translateX: -left * ratio,
							translateY: -top * ratio
						}, imageData))));
					});
				}
			};
			var events = {
				bind: function bind() {
					var element = this.element, options = this.options, cropper = this.cropper;
					if (isFunction(options.cropstart)) addListener(element, EVENT_CROP_START, options.cropstart);
					if (isFunction(options.cropmove)) addListener(element, EVENT_CROP_MOVE, options.cropmove);
					if (isFunction(options.cropend)) addListener(element, EVENT_CROP_END, options.cropend);
					if (isFunction(options.crop)) addListener(element, EVENT_CROP, options.crop);
					if (isFunction(options.zoom)) addListener(element, EVENT_ZOOM, options.zoom);
					addListener(cropper, EVENT_POINTER_DOWN, this.onCropStart = this.cropStart.bind(this));
					if (options.zoomable && options.zoomOnWheel) addListener(cropper, EVENT_WHEEL, this.onWheel = this.wheel.bind(this), {
						passive: false,
						capture: true
					});
					if (options.toggleDragModeOnDblclick) addListener(cropper, EVENT_DBLCLICK, this.onDblclick = this.dblclick.bind(this));
					addListener(element.ownerDocument, EVENT_POINTER_MOVE, this.onCropMove = this.cropMove.bind(this));
					addListener(element.ownerDocument, EVENT_POINTER_UP, this.onCropEnd = this.cropEnd.bind(this));
					addListener(element.ownerDocument.defaultView, EVENT_BLUR, this.onCropBlur = this.cropEnd.bind(this));
					if (options.responsive) addListener(window, EVENT_RESIZE, this.onResize = this.resize.bind(this));
				},
				unbind: function unbind() {
					var element = this.element, options = this.options, cropper = this.cropper;
					if (isFunction(options.cropstart)) removeListener(element, EVENT_CROP_START, options.cropstart);
					if (isFunction(options.cropmove)) removeListener(element, EVENT_CROP_MOVE, options.cropmove);
					if (isFunction(options.cropend)) removeListener(element, EVENT_CROP_END, options.cropend);
					if (isFunction(options.crop)) removeListener(element, EVENT_CROP, options.crop);
					if (isFunction(options.zoom)) removeListener(element, EVENT_ZOOM, options.zoom);
					removeListener(cropper, EVENT_POINTER_DOWN, this.onCropStart);
					if (options.zoomable && options.zoomOnWheel) removeListener(cropper, EVENT_WHEEL, this.onWheel, {
						passive: false,
						capture: true
					});
					if (options.toggleDragModeOnDblclick) removeListener(cropper, EVENT_DBLCLICK, this.onDblclick);
					removeListener(element.ownerDocument, EVENT_POINTER_MOVE, this.onCropMove);
					removeListener(element.ownerDocument, EVENT_POINTER_UP, this.onCropEnd);
					removeListener(element.ownerDocument.defaultView, EVENT_BLUR, this.onCropBlur);
					if (options.responsive) removeListener(window, EVENT_RESIZE, this.onResize);
				}
			};
			var BORDER_RADIUS_CORNER_FACTOR = .5522848;
			function expandBorderRadiusValues(values) {
				if (values.length === 1) return [
					values[0],
					values[0],
					values[0],
					values[0]
				];
				if (values.length === 2) return [
					values[0],
					values[1],
					values[0],
					values[1]
				];
				if (values.length === 3) return [
					values[0],
					values[1],
					values[2],
					values[1]
				];
				return values.slice(0, 4);
			}
			function parseBorderRadiusValues(value, size, scale) {
				return value.trim().split(/\s+/).map(function(part) {
					var number = Number.parseFloat(part);
					if (part.endsWith("%")) return number * size / 100;
					return (number || 0) * scale;
				});
			}
			function getBorderRadii(borderRadius, width, height, scale) {
				var parts = String(borderRadius || 0).trim().split("/");
				var horizontal = expandBorderRadiusValues(parseBorderRadiusValues(parts[0], width, scale));
				var vertical = expandBorderRadiusValues(parseBorderRadiusValues(parts[1] || parts[0], height, scale));
				var radii = [
					{
						x: horizontal[0],
						y: vertical[0]
					},
					{
						x: horizontal[1],
						y: vertical[1]
					},
					{
						x: horizontal[2],
						y: vertical[2]
					},
					{
						x: horizontal[3],
						y: vertical[3]
					}
				];
				var radiusScale = Math.min(1, width / (radii[0].x + radii[1].x || 1), width / (radii[3].x + radii[2].x || 1), height / (radii[0].y + radii[3].y || 1), height / (radii[1].y + radii[2].y || 1));
				return radii.map(function(_ref) {
					var x = _ref.x, y = _ref.y;
					return {
						x: x * radiusScale,
						y: y * radiusScale
					};
				});
			}
			function drawRoundedRect(context, width, height, borderRadius, scale) {
				var _getBorderRadii2 = _slicedToArray(getBorderRadii(borderRadius, width, height, scale), 4), topLeft = _getBorderRadii2[0], topRight = _getBorderRadii2[1], bottomRight = _getBorderRadii2[2], bottomLeft = _getBorderRadii2[3];
				var factor = BORDER_RADIUS_CORNER_FACTOR;
				context.beginPath();
				context.moveTo(topLeft.x, 0);
				context.lineTo(width - topRight.x, 0);
				context.bezierCurveTo(width - topRight.x + topRight.x * factor, 0, width, topRight.y - topRight.y * factor, width, topRight.y);
				context.lineTo(width, height - bottomRight.y);
				context.bezierCurveTo(width, height - bottomRight.y + bottomRight.y * factor, width - bottomRight.x + bottomRight.x * factor, height, width - bottomRight.x, height);
				context.lineTo(bottomLeft.x, height);
				context.bezierCurveTo(bottomLeft.x - bottomLeft.x * factor, height, 0, height - bottomLeft.y + bottomLeft.y * factor, 0, height - bottomLeft.y);
				context.lineTo(0, topLeft.y);
				context.bezierCurveTo(0, topLeft.y - topLeft.y * factor, topLeft.x - topLeft.x * factor, 0, topLeft.x, 0);
				context.closePath();
				context.fill();
			}
			function _zoomTo(ratio, pivot, originalEvent) {
				var options = this.options, canvasData = this.canvasData, containerData = this.containerData;
				var width = canvasData.width, height = canvasData.height, naturalWidth = canvasData.naturalWidth, naturalHeight = canvasData.naturalHeight;
				ratio = Number(ratio);
				if (ratio >= 0 && this.ready && !this.disabled && options.zoomable) {
					var newWidth = naturalWidth * ratio;
					var newHeight = naturalHeight * ratio;
					if (dispatchEvent(this.element, EVENT_ZOOM, {
						ratio,
						oldRatio: width / naturalWidth,
						originalEvent
					}) === false) return this;
					if (isPlainObject(pivot) && isNumber(pivot.x) && isNumber(pivot.y)) {
						canvasData.left -= (newWidth - width) * ((pivot.x - canvasData.left) / width);
						canvasData.top -= (newHeight - height) * ((pivot.y - canvasData.top) / height);
					} else if (options.zoomAroundCenter) {
						var centerX = containerData.width / 2;
						var centerY = containerData.height / 2;
						canvasData.left -= (newWidth - width) * ((centerX - canvasData.left) / width);
						canvasData.top -= (newHeight - height) * ((centerY - canvasData.top) / height);
					} else if (originalEvent) {
						var pointers = this.pointers;
						var offset = getOffset(this.cropper);
						var center = pointers && Object.keys(pointers).length ? getPointersCenter(pointers) : {
							pageX: originalEvent.pageX,
							pageY: originalEvent.pageY
						};
						canvasData.left -= (newWidth - width) * ((center.pageX - offset.left - canvasData.left) / width);
						canvasData.top -= (newHeight - height) * ((center.pageY - offset.top - canvasData.top) / height);
					} else {
						canvasData.left -= (newWidth - width) / 2;
						canvasData.top -= (newHeight - height) / 2;
					}
					canvasData.width = newWidth;
					canvasData.height = newHeight;
					this.renderCanvas(true);
				}
				return this;
			}
			function _zoom(ratio, originalEvent) {
				var canvasData = this.canvasData;
				ratio = Number(ratio);
				if (ratio < 0) ratio = 1 / (1 - ratio);
				else ratio = 1 + ratio;
				return _zoomTo.call(this, canvasData.width * ratio / canvasData.naturalWidth, null, originalEvent);
			}
			function zoomWithEvent(ratio, originalEvent) {
				return _zoom.call(this, ratio, originalEvent);
			}
			var methods = {
				crop: function crop() {
					if (this.ready && !this.cropped && !this.disabled) {
						this.cropped = true;
						this.limitCropBox(true, true);
						if (this.options.modal) addClass(this.dragBox, CLASS_MODAL);
						removeClass(this.cropBox, CLASS_HIDDEN);
						this.setCropBoxData(this.initialCropBoxData);
					}
					return this;
				},
				reset: function reset() {
					if (this.ready && !this.disabled) {
						this.imageData = assign({}, this.initialImageData);
						this.canvasData = assign({}, this.initialCanvasData);
						this.cropBoxData = assign({}, this.initialCropBoxData);
						this.renderCanvas();
						if (this.cropped) this.renderCropBox();
					}
					return this;
				},
				clear: function clear() {
					if (this.cropped && !this.disabled) {
						assign(this.cropBoxData, {
							left: 0,
							top: 0,
							width: 0,
							height: 0
						});
						this.cropped = false;
						this.renderCropBox();
						this.limitCanvas(true, true);
						this.renderCanvas();
						removeClass(this.dragBox, CLASS_MODAL);
						addClass(this.cropBox, CLASS_HIDDEN);
					}
					return this;
				},
				/**
				* Replace the image's src and rebuild the cropper
				* @param {string} url - The new URL.
				* @param {boolean} [hasSameSize] - Indicate if the new image has the same size as the old one.
				* @returns {Cropper} this
				*/
				replace: function replace(url) {
					var hasSameSize = arguments.length > 1 && arguments[1] !== void 0 ? arguments[1] : false;
					if (!this.disabled && url) {
						if (this.isImg) this.element.src = url;
						if (hasSameSize) {
							this.url = url;
							this.image.src = url;
							if (this.ready) {
								this.viewBoxImage.src = url;
								forEach(this.previews, function(element) {
									element.getElementsByTagName("img")[0].src = url;
								});
							}
						} else {
							if (this.isImg) this.replaced = true;
							this.options.data = null;
							this.uncreate();
							this.load(url);
						}
					}
					return this;
				},
				enable: function enable() {
					if (this.ready && this.disabled) {
						this.disabled = false;
						removeClass(this.cropper, CLASS_DISABLED);
					}
					return this;
				},
				disable: function disable() {
					if (this.ready && !this.disabled) {
						this.disabled = true;
						addClass(this.cropper, CLASS_DISABLED);
					}
					return this;
				},
				/**
				* Destroy the cropper and remove the instance from the image
				* @returns {Cropper} this
				*/
				destroy: function destroy() {
					var element = this.element;
					if (!element[NAMESPACE]) return this;
					element[NAMESPACE] = void 0;
					if (this.isImg && this.replaced) element.src = this.originalUrl;
					this.uncreate();
					return this;
				},
				/**
				* Move the canvas with relative offsets
				* @param {number} offsetX - The relative offset distance on the x-axis.
				* @param {number} [offsetY=offsetX] - The relative offset distance on the y-axis.
				* @returns {Cropper} this
				*/
				move: function move(offsetX) {
					var offsetY = arguments.length > 1 && arguments[1] !== void 0 ? arguments[1] : offsetX;
					var _this$canvasData = this.canvasData, left = _this$canvasData.left, top = _this$canvasData.top;
					return this.moveTo(isUndefined(offsetX) ? offsetX : left + Number(offsetX), isUndefined(offsetY) ? offsetY : top + Number(offsetY));
				},
				/**
				* Move the canvas to an absolute point
				* @param {number} x - The x-axis coordinate.
				* @param {number} [y=x] - The y-axis coordinate.
				* @returns {Cropper} this
				*/
				moveTo: function moveTo(x) {
					var y = arguments.length > 1 && arguments[1] !== void 0 ? arguments[1] : x;
					var canvasData = this.canvasData;
					var changed = false;
					x = Number(x);
					y = Number(y);
					if (this.ready && !this.disabled && this.options.movable) {
						if (isNumber(x)) {
							canvasData.left = x;
							changed = true;
						}
						if (isNumber(y)) {
							canvasData.top = y;
							changed = true;
						}
						if (changed) this.renderCanvas(true);
					}
					return this;
				},
				/**
				* Zoom the canvas with a relative ratio
				* @param {number} ratio - The target ratio.
				* @returns {Cropper} this
				*/
				zoom: function zoom(ratio) {
					return _zoom.call(this, ratio);
				},
				/**
				* Zoom the canvas to an absolute ratio
				* @param {number} ratio - The target ratio.
				* @param {Object} pivot - The zoom pivot point coordinate.
				* @returns {Cropper} this
				*/
				zoomTo: function zoomTo(ratio, pivot) {
					return _zoomTo.call(this, ratio, pivot);
				},
				/**
				* Rotate the canvas with a relative degree
				* @param {number} degree - The rotate degree.
				* @returns {Cropper} this
				*/
				rotate: function rotate(degree) {
					return this.rotateTo((this.imageData.rotate || 0) + Number(degree));
				},
				/**
				* Rotate the canvas to an absolute degree
				* @param {number} degree - The rotate degree.
				* @returns {Cropper} this
				*/
				rotateTo: function rotateTo(degree) {
					degree = Number(degree);
					if (isNumber(degree) && this.ready && !this.disabled && this.options.rotatable) {
						this.imageData.rotate = degree % 360;
						this.renderCanvas(true, true);
					}
					return this;
				},
				/**
				* Scale the image on the x-axis.
				* @param {number} scaleX - The scale ratio on the x-axis.
				* @returns {Cropper} this
				*/
				scaleX: function scaleX(_scaleX) {
					var scaleY = this.imageData.scaleY;
					return this.scale(_scaleX, isNumber(scaleY) ? scaleY : 1);
				},
				/**
				* Scale the image on the y-axis.
				* @param {number} scaleY - The scale ratio on the y-axis.
				* @returns {Cropper} this
				*/
				scaleY: function scaleY(_scaleY) {
					var scaleX = this.imageData.scaleX;
					return this.scale(isNumber(scaleX) ? scaleX : 1, _scaleY);
				},
				/**
				* Scale the image
				* @param {number} scaleX - The scale ratio on the x-axis.
				* @param {number} [scaleY=scaleX] - The scale ratio on the y-axis.
				* @returns {Cropper} this
				*/
				scale: function scale(scaleX) {
					var scaleY = arguments.length > 1 && arguments[1] !== void 0 ? arguments[1] : scaleX;
					var imageData = this.imageData;
					var transformed = false;
					scaleX = Number(scaleX);
					scaleY = Number(scaleY);
					if (this.ready && !this.disabled && this.options.scalable) {
						if (isNumber(scaleX)) {
							imageData.scaleX = scaleX;
							transformed = true;
						}
						if (isNumber(scaleY)) {
							imageData.scaleY = scaleY;
							transformed = true;
						}
						if (transformed) this.renderCanvas(true, true);
					}
					return this;
				},
				/**
				* Get the cropped area position and size data (base on the original image)
				* @param {boolean} [rounded=false] - Indicate if round the data values or not.
				* @returns {Object} The result cropped data.
				*/
				getData: function getData() {
					var rounded = arguments.length > 0 && arguments[0] !== void 0 ? arguments[0] : false;
					var options = this.options, imageData = this.imageData, canvasData = this.canvasData, cropBoxData = this.cropBoxData;
					var data;
					if (this.ready && this.cropped) {
						data = {
							x: cropBoxData.left - canvasData.left,
							y: cropBoxData.top - canvasData.top,
							width: cropBoxData.width,
							height: cropBoxData.height
						};
						var ratio = imageData.width / imageData.naturalWidth;
						forEach(data, function(n, i) {
							data[i] = normalizeDecimalNumber(n / ratio);
						});
						if (rounded) {
							var bottom = Math.round(data.y + data.height);
							var right = Math.round(data.x + data.width);
							data.x = Math.round(data.x);
							data.y = Math.round(data.y);
							data.width = right - data.x;
							data.height = bottom - data.y;
						}
					} else data = {
						x: 0,
						y: 0,
						width: 0,
						height: 0
					};
					if (options.rotatable) data.rotate = imageData.rotate || 0;
					if (options.scalable) {
						data.scaleX = imageData.scaleX || 1;
						data.scaleY = imageData.scaleY || 1;
					}
					return data;
				},
				/**
				* Set the cropped area position and size with new data
				* @param {Object} data - The new data.
				* @returns {Cropper} this
				*/
				setData: function setData(data) {
					var options = this.options, imageData = this.imageData, canvasData = this.canvasData;
					var cropBoxData = {};
					if (this.ready && !this.disabled && isPlainObject(data)) {
						var transformed = false;
						if (options.rotatable) {
							if (isNumber(data.rotate) && data.rotate !== imageData.rotate) {
								imageData.rotate = data.rotate;
								transformed = true;
							}
						}
						if (options.scalable) {
							if (isNumber(data.scaleX) && data.scaleX !== imageData.scaleX) {
								imageData.scaleX = data.scaleX;
								transformed = true;
							}
							if (isNumber(data.scaleY) && data.scaleY !== imageData.scaleY) {
								imageData.scaleY = data.scaleY;
								transformed = true;
							}
						}
						if (transformed) this.renderCanvas(true, true);
						var ratio = imageData.width / imageData.naturalWidth;
						if (isNumber(data.x)) cropBoxData.left = data.x * ratio + canvasData.left;
						if (isNumber(data.y)) cropBoxData.top = data.y * ratio + canvasData.top;
						if (isNumber(data.width)) cropBoxData.width = data.width * ratio;
						if (isNumber(data.height)) cropBoxData.height = data.height * ratio;
						this.setCropBoxData(cropBoxData);
					}
					return this;
				},
				/**
				* Get the container size data.
				* @returns {Object} The result container data.
				*/
				getContainerData: function getContainerData() {
					return this.ready ? assign({}, this.containerData) : {};
				},
				/**
				* Get the image position and size data.
				* @returns {Object} The result image data.
				*/
				getImageData: function getImageData() {
					return this.sized ? assign({}, this.imageData) : {};
				},
				/**
				* Get the canvas position and size data.
				* @returns {Object} The result canvas data.
				*/
				getCanvasData: function getCanvasData() {
					var canvasData = this.canvasData;
					var data = {};
					if (this.ready) forEach([
						"left",
						"top",
						"width",
						"height",
						"naturalWidth",
						"naturalHeight"
					], function(n) {
						data[n] = canvasData[n];
					});
					return data;
				},
				/**
				* Set the canvas position and size with new data.
				* @param {Object} data - The new canvas data.
				* @returns {Cropper} this
				*/
				setCanvasData: function setCanvasData(data) {
					var canvasData = this.canvasData;
					var aspectRatio = canvasData.aspectRatio;
					if (this.ready && !this.disabled && isPlainObject(data)) {
						if (isNumber(data.left)) canvasData.left = data.left;
						if (isNumber(data.top)) canvasData.top = data.top;
						if (isNumber(data.width)) {
							canvasData.width = data.width;
							canvasData.height = data.width / aspectRatio;
						} else if (isNumber(data.height)) {
							canvasData.height = data.height;
							canvasData.width = data.height * aspectRatio;
						}
						this.renderCanvas(true);
					}
					return this;
				},
				/**
				* Get the crop box position and size data.
				* @returns {Object} The result crop box data.
				*/
				getCropBoxData: function getCropBoxData() {
					var cropBoxData = this.cropBoxData;
					var data;
					if (this.ready && this.cropped) data = {
						left: cropBoxData.left,
						top: cropBoxData.top,
						width: cropBoxData.width,
						height: cropBoxData.height
					};
					return data || {};
				},
				/**
				* Set the crop box position and size with new data.
				* @param {Object} data - The new crop box data.
				* @returns {Cropper} this
				*/
				setCropBoxData: function setCropBoxData(data) {
					var cropBoxData = this.cropBoxData;
					var aspectRatio = this.options.aspectRatio;
					var widthChanged;
					var heightChanged;
					if (this.ready && this.cropped && !this.disabled && isPlainObject(data)) {
						if (isNumber(data.left)) cropBoxData.left = data.left;
						if (isNumber(data.top)) cropBoxData.top = data.top;
						if (isNumber(data.width) && data.width !== cropBoxData.width) {
							widthChanged = true;
							cropBoxData.width = data.width;
						}
						if (isNumber(data.height) && data.height !== cropBoxData.height) {
							heightChanged = true;
							cropBoxData.height = data.height;
						}
						if (aspectRatio) {
							if (widthChanged) cropBoxData.height = cropBoxData.width / aspectRatio;
							else if (heightChanged) cropBoxData.width = cropBoxData.height * aspectRatio;
						}
						this.renderCropBox();
					}
					return this;
				},
				/**
				* Get a canvas drawn the cropped image.
				* @param {Object} [options={}] - The config options.
				* @returns {HTMLCanvasElement} - The result canvas.
				*/
				getCroppedCanvas: function getCroppedCanvas() {
					var options = arguments.length > 0 && arguments[0] !== void 0 ? arguments[0] : {};
					if (!this.ready || !window.HTMLCanvasElement) return null;
					var canvasData = this.canvasData;
					var source = getSourceCanvas(this.image, this.imageData, canvasData, options);
					if (!this.cropped) return source;
					var _this$getData = this.getData(options.rounded), initialX = _this$getData.x, initialY = _this$getData.y, initialWidth = _this$getData.width, initialHeight = _this$getData.height;
					var ratio = source.width / Math.floor(canvasData.naturalWidth);
					if (ratio !== 1) {
						initialX *= ratio;
						initialY *= ratio;
						initialWidth *= ratio;
						initialHeight *= ratio;
					}
					var aspectRatio = initialWidth / initialHeight;
					var maxSizes = getAdjustedSizes({
						aspectRatio,
						width: options.maxWidth || Infinity,
						height: options.maxHeight || Infinity
					});
					var minSizes = getAdjustedSizes({
						aspectRatio,
						width: options.minWidth || 0,
						height: options.minHeight || 0
					}, "cover");
					var _getAdjustedSizes = getAdjustedSizes({
						aspectRatio,
						width: options.width || (ratio !== 1 ? source.width : initialWidth),
						height: options.height || (ratio !== 1 ? source.height : initialHeight)
					}), width = _getAdjustedSizes.width, height = _getAdjustedSizes.height;
					width = Math.min(maxSizes.width, Math.max(minSizes.width, width));
					height = Math.min(maxSizes.height, Math.max(minSizes.height, height));
					var canvas = document.createElement("canvas");
					var context = canvas.getContext("2d");
					canvas.width = normalizeDecimalNumber(width);
					canvas.height = normalizeDecimalNumber(height);
					context.fillStyle = options.fillColor || "transparent";
					context.fillRect(0, 0, width, height);
					var _options$imageSmoothi = options.imageSmoothingEnabled, imageSmoothingEnabled = _options$imageSmoothi === void 0 ? true : _options$imageSmoothi, imageSmoothingQuality = options.imageSmoothingQuality;
					context.imageSmoothingEnabled = imageSmoothingEnabled;
					if (imageSmoothingQuality) context.imageSmoothingQuality = imageSmoothingQuality;
					if (isFunction(options.beforeDraw)) options.beforeDraw.call(this, context, canvas);
					var sourceWidth = source.width;
					var sourceHeight = source.height;
					var srcX = initialX;
					var srcY = initialY;
					var srcWidth;
					var srcHeight;
					var dstX;
					var dstY;
					var dstWidth;
					var dstHeight;
					if (srcX <= -initialWidth || srcX > sourceWidth) {
						srcX = 0;
						srcWidth = 0;
						dstX = 0;
						dstWidth = 0;
					} else if (srcX <= 0) {
						dstX = -srcX;
						srcX = 0;
						srcWidth = Math.min(sourceWidth, initialWidth + srcX);
						dstWidth = srcWidth;
					} else if (srcX <= sourceWidth) {
						dstX = 0;
						srcWidth = Math.min(initialWidth, sourceWidth - srcX);
						dstWidth = srcWidth;
					}
					if (srcWidth <= 0 || srcY <= -initialHeight || srcY > sourceHeight) {
						srcY = 0;
						srcHeight = 0;
						dstY = 0;
						dstHeight = 0;
					} else if (srcY <= 0) {
						dstY = -srcY;
						srcY = 0;
						srcHeight = Math.min(sourceHeight, initialHeight + srcY);
						dstHeight = srcHeight;
					} else if (srcY <= sourceHeight) {
						dstY = 0;
						srcHeight = Math.min(initialHeight, sourceHeight - srcY);
						dstHeight = srcHeight;
					}
					var params = [
						srcX,
						srcY,
						srcWidth,
						srcHeight
					];
					if (dstWidth > 0 && dstHeight > 0) {
						var scale = width / initialWidth;
						params.push(dstX * scale, dstY * scale, dstWidth * scale, dstHeight * scale);
					}
					context.drawImage.apply(context, [source].concat(_toConsumableArray(params.map(function(param) {
						return Math.floor(normalizeDecimalNumber(param));
					}))));
					if (this.options.borderRadius) {
						context.globalCompositeOperation = "destination-in";
						context.fillStyle = "#000";
						drawRoundedRect(context, width, height, this.options.borderRadius, width / this.cropBoxData.width);
					}
					return canvas;
				},
				/**
				* Change the aspect ratio of the crop box.
				* @param {number} aspectRatio - The new aspect ratio.
				* @returns {Cropper} this
				*/
				setAspectRatio: function setAspectRatio(aspectRatio) {
					var options = this.options;
					if (!this.disabled && !isUndefined(aspectRatio)) {
						options.aspectRatio = Math.max(0, aspectRatio) || NaN;
						if (this.ready) {
							this.initCropBox();
							if (this.cropped) this.renderCropBox();
						}
					}
					return this;
				},
				/**
				* Change the drag mode.
				* @param {string} mode - The new drag mode.
				* @returns {Cropper} this
				*/
				setDragMode: function setDragMode(mode) {
					var options = this.options, dragBox = this.dragBox, face = this.face;
					if (this.ready && !this.disabled) {
						var croppable = mode === DRAG_MODE_CROP;
						var movable = options.movable && mode === DRAG_MODE_MOVE;
						mode = croppable || movable ? mode : DRAG_MODE_NONE;
						options.dragMode = mode;
						setData(dragBox, DATA_ACTION, mode);
						toggleClass(dragBox, CLASS_CROP, croppable);
						toggleClass(dragBox, CLASS_MOVE, movable);
						if (!options.cropBoxMovable) {
							setData(face, DATA_ACTION, mode);
							toggleClass(face, CLASS_CROP, croppable);
							toggleClass(face, CLASS_MOVE, movable);
						}
					}
					return this;
				}
			};
			var handlers = {
				resize: function resize() {
					if (this.disabled) return;
					var options = this.options, container = this.container, containerData = this.containerData;
					var ratioX = container.offsetWidth / containerData.width;
					var ratioY = container.offsetHeight / containerData.height;
					var ratio = Math.abs(ratioX - 1) > Math.abs(ratioY - 1) ? ratioX : ratioY;
					if (ratio !== 1) {
						var canvasData;
						var cropBoxData;
						if (options.restore) {
							canvasData = this.getCanvasData();
							cropBoxData = this.getCropBoxData();
						}
						this.render();
						if (options.restore) {
							this.setCanvasData(forEach(canvasData, function(n, i) {
								canvasData[i] = n * ratio;
							}));
							this.setCropBoxData(forEach(cropBoxData, function(n, i) {
								cropBoxData[i] = n * ratio;
							}));
						}
					}
				},
				dblclick: function dblclick() {
					if (this.disabled || this.options.dragMode === DRAG_MODE_NONE) return;
					this.setDragMode(hasClass(this.dragBox, CLASS_CROP) ? DRAG_MODE_MOVE : DRAG_MODE_CROP);
				},
				wheel: function wheel(event) {
					var _this = this;
					var ratio = Number(this.options.wheelZoomRatio) || .1;
					var delta = 1;
					if (this.disabled) return;
					event.preventDefault();
					if (this.wheeling) return;
					this.wheeling = true;
					setTimeout(function() {
						_this.wheeling = false;
					}, 50);
					if (event.deltaY) delta = event.deltaY > 0 ? 1 : -1;
					else if (event.wheelDelta) delta = -event.wheelDelta / 120;
					else if (event.detail) delta = event.detail > 0 ? 1 : -1;
					zoomWithEvent.call(this, -delta * ratio, event);
				},
				cropStart: function cropStart(event) {
					var buttons = event.buttons, button = event.button;
					if (this.disabled || (event.type === "mousedown" || event.type === "pointerdown" && event.pointerType === "mouse") && (isNumber(buttons) && buttons !== 1 || isNumber(button) && button !== 0 || event.ctrlKey)) return;
					var options = this.options, pointers = this.pointers;
					var action;
					if (event.changedTouches) forEach(event.changedTouches, function(touch) {
						pointers[touch.identifier] = getPointer(touch);
					});
					else pointers[event.pointerId || 0] = getPointer(event);
					if (Object.keys(pointers).length > 1 && options.zoomable && options.zoomOnTouch) action = ACTION_ZOOM;
					else action = getData(event.target, DATA_ACTION);
					if (!REGEXP_ACTIONS.test(action)) return;
					if (dispatchEvent(this.element, EVENT_CROP_START, {
						originalEvent: event,
						action
					}) === false) return;
					event.preventDefault();
					this.action = action;
					this.cropping = false;
					this.cropBoxDragStartData = null;
					this.cropBoxDragStartAction = options.cropBoxMoveMode === "dragStart" && action !== ACTION_CROP && action !== ACTION_MOVE && action !== ACTION_ZOOM ? action : "";
					if (action === ACTION_CROP) {
						this.cropping = true;
						addClass(this.dragBox, CLASS_MODAL);
					}
				},
				cropMove: function cropMove(event) {
					var action = this.action;
					if (this.disabled || !action) return;
					var pointers = this.pointers;
					event.preventDefault();
					if (dispatchEvent(this.element, EVENT_CROP_MOVE, {
						originalEvent: event,
						action
					}) === false) return;
					if (event.changedTouches) forEach(event.changedTouches, function(touch) {
						assign(pointers[touch.identifier] || {}, getPointer(touch, true));
					});
					else assign(pointers[event.pointerId || 0] || {}, getPointer(event, true));
					this.change(event);
				},
				cropEnd: function cropEnd(event) {
					if (this.disabled) return;
					var action = this.action, pointers = this.pointers;
					if (event.type === EVENT_BLUR) Object.keys(pointers).forEach(function(pointerId) {
						delete pointers[pointerId];
					});
					else if (event.changedTouches) forEach(event.changedTouches, function(touch) {
						delete pointers[touch.identifier];
					});
					else delete pointers[event.pointerId || 0];
					if (!action) return;
					event.preventDefault();
					if (!Object.keys(pointers).length) {
						this.action = "";
						this.cropBoxDragStartData = null;
						this.cropBoxDragStartAction = "";
					}
					if (this.cropping) {
						this.cropping = false;
						toggleClass(this.dragBox, CLASS_MODAL, this.cropped && this.options.modal);
					}
					dispatchEvent(this.element, EVENT_CROP_END, {
						originalEvent: event,
						action
					});
				}
			};
			function resizeCropBoxFromCenter(action, range, aspectRatio, cropBoxData, bounds) {
				var centerX = cropBoxData.left + cropBoxData.width / 2;
				var centerY = cropBoxData.top + cropBoxData.height / 2;
				var isEast = action === ACTION_EAST || action === ACTION_NORTH_EAST || action === ACTION_SOUTH_EAST;
				var isSouth = action === ACTION_SOUTH || action === ACTION_SOUTH_EAST || action === ACTION_SOUTH_WEST;
				var isHorizontal = action === ACTION_EAST || action === ACTION_WEST;
				var isVertical = action === ACTION_NORTH || action === ACTION_SOUTH;
				var width = cropBoxData.width, height = cropBoxData.height;
				if (aspectRatio) {
					if (isHorizontal || !isVertical && isSouth) {
						width += (isEast ? 2 : -2) * range.x;
						height = width / aspectRatio;
					} else {
						height += (isSouth ? 2 : -2) * range.y;
						width = height * aspectRatio;
					}
				} else {
					width += (isEast ? 2 : -2) * range.x;
					height += (isSouth ? 2 : -2) * range.y;
				}
				if (width < 0) {
					width = -width;
					if (!aspectRatio || isHorizontal || !isVertical && isSouth) isEast = !isEast;
				}
				if (height < 0) {
					height = -height;
					if (!aspectRatio || isVertical || !isVertical && !isSouth) isSouth = !isSouth;
				}
				var maxWidth = Math.max(0, 2 * Math.min(centerX - bounds.minLeft, bounds.maxWidth - centerX));
				var maxHeight = Math.max(0, 2 * Math.min(centerY - bounds.minTop, bounds.maxHeight - centerY));
				var minWidth = Math.min(cropBoxData.minWidth, maxWidth);
				var minHeight = Math.min(cropBoxData.minHeight, maxHeight);
				if (aspectRatio) {
					var minScale = Math.max(width ? minWidth / width : 0, height ? minHeight / height : 0, 1);
					var maxScale = Math.min(width ? maxWidth / width : 0, height ? maxHeight / height : 0);
					var scale = Math.min(minScale, maxScale);
					width *= scale;
					height *= scale;
				} else {
					width = Math.min(Math.max(width, minWidth), maxWidth);
					height = Math.min(Math.max(height, minHeight), maxHeight);
				}
				if (isEast) action = isSouth ? ACTION_SOUTH_EAST : ACTION_NORTH_EAST;
				else action = isSouth ? ACTION_SOUTH_WEST : ACTION_NORTH_WEST;
				if (isHorizontal) action = isEast ? ACTION_EAST : ACTION_WEST;
				else if (isVertical) action = isSouth ? ACTION_SOUTH : ACTION_NORTH;
				return {
					action,
					left: centerX - width / 2,
					top: centerY - height / 2,
					width,
					height
				};
			}
			var change = { change: function change(event) {
				var options = this.options, canvasData = this.canvasData, containerData = this.containerData, cropBoxData = this.cropBoxData, pointers = this.pointers;
				var action = this.action;
				var aspectRatio = options.aspectRatio;
				var left = cropBoxData.left, top = cropBoxData.top, width = cropBoxData.width, height = cropBoxData.height;
				var right = left + width;
				var bottom = top + height;
				var minLeft = 0;
				var minTop = 0;
				var maxWidth = containerData.width;
				var maxHeight = containerData.height;
				var renderable = true;
				var offset;
				if (!aspectRatio && event.shiftKey) aspectRatio = width && height ? width / height : 1;
				if (this.limited) {
					minLeft = cropBoxData.minLeft;
					minTop = cropBoxData.minTop;
					maxWidth = minLeft + Math.min(containerData.width, canvasData.width, canvasData.left + canvasData.width);
					maxHeight = minTop + Math.min(containerData.height, canvasData.height, canvasData.top + canvasData.height);
				}
				var pointer = pointers[Object.keys(pointers)[0]];
				var isCropBoxDrag = options.cropBoxMoveMode === "dragStart" && this.cropBoxDragStartAction && [
					ACTION_ALL,
					ACTION_EAST,
					ACTION_NORTH,
					ACTION_SOUTH,
					ACTION_WEST,
					ACTION_NORTH_EAST,
					ACTION_NORTH_WEST,
					ACTION_SOUTH_EAST,
					ACTION_SOUTH_WEST
				].includes(action);
				if (isCropBoxDrag) {
					if (!this.cropBoxDragStartData) this.cropBoxDragStartData = _objectSpread2({}, cropBoxData);
					Object.assign(cropBoxData, this.cropBoxDragStartData);
					left = cropBoxData.left;
					top = cropBoxData.top;
					width = cropBoxData.width;
					height = cropBoxData.height;
					right = left + width;
					bottom = top + height;
					action = this.cropBoxDragStartAction;
				}
				var range = {
					x: pointer.endX - pointer.startX,
					y: pointer.endY - pointer.startY
				};
				if (options.cropBoxResizeAroundCenter && [
					ACTION_EAST,
					ACTION_NORTH,
					ACTION_SOUTH,
					ACTION_WEST,
					ACTION_NORTH_EAST,
					ACTION_NORTH_WEST,
					ACTION_SOUTH_EAST,
					ACTION_SOUTH_WEST
				].includes(action)) {
					var cropBox = resizeCropBoxFromCenter(action, range, aspectRatio, cropBoxData, {
						minLeft,
						minTop,
						maxWidth,
						maxHeight
					});
					cropBoxData.left = cropBox.left;
					cropBoxData.top = cropBox.top;
					cropBoxData.width = cropBox.width;
					cropBoxData.height = cropBox.height;
					this.action = cropBox.action;
					this.renderCropBox();
					if (!isCropBoxDrag) forEach(pointers, function(p) {
						p.startX = p.endX;
						p.startY = p.endY;
					});
					return;
				}
				var check = function check(side) {
					switch (side) {
						case ACTION_EAST:
							if (right + range.x > maxWidth) range.x = maxWidth - right;
							break;
						case ACTION_WEST:
							if (left + range.x < minLeft) range.x = minLeft - left;
							break;
						case ACTION_NORTH:
							if (top + range.y < minTop) range.y = minTop - top;
							break;
						case ACTION_SOUTH: if (bottom + range.y > maxHeight) range.y = maxHeight - bottom;
					}
				};
				switch (action) {
					case ACTION_ALL:
						left += range.x;
						top += range.y;
						break;
					case ACTION_EAST:
						if (range.x >= 0 && (right >= maxWidth || aspectRatio && (top <= minTop || bottom >= maxHeight))) {
							renderable = false;
							break;
						}
						check(ACTION_EAST);
						width += range.x;
						if (width < 0) {
							action = ACTION_WEST;
							width = -width;
							left -= width;
							if (width < cropBoxData.minWidth) {
								width = cropBoxData.minWidth;
								left = cropBoxData.left - width;
							}
						}
						if (aspectRatio) {
							height = width / aspectRatio;
							top += (cropBoxData.height - height) / 2;
						}
						break;
					case ACTION_NORTH:
						if (range.y <= 0 && (top <= minTop || aspectRatio && (left <= minLeft || right >= maxWidth))) {
							renderable = false;
							break;
						}
						check(ACTION_NORTH);
						height -= range.y;
						top += range.y;
						if (height < 0) {
							action = ACTION_SOUTH;
							height = -height;
							top -= height;
							if (height < cropBoxData.minHeight) {
								height = cropBoxData.minHeight;
								top = bottom;
							}
						}
						if (aspectRatio) {
							width = height * aspectRatio;
							left += (cropBoxData.width - width) / 2;
						}
						break;
					case ACTION_WEST:
						if (range.x <= 0 && (left <= minLeft || aspectRatio && (top <= minTop || bottom >= maxHeight))) {
							renderable = false;
							break;
						}
						check(ACTION_WEST);
						width -= range.x;
						left += range.x;
						if (width < 0) {
							action = ACTION_EAST;
							width = -width;
							left -= width;
							if (width < cropBoxData.minWidth) {
								width = cropBoxData.minWidth;
								left = right;
							}
						}
						if (aspectRatio) {
							height = width / aspectRatio;
							top += (cropBoxData.height - height) / 2;
						}
						break;
					case ACTION_SOUTH:
						if (range.y >= 0 && (bottom >= maxHeight || aspectRatio && (left <= minLeft || right >= maxWidth))) {
							renderable = false;
							break;
						}
						check(ACTION_SOUTH);
						height += range.y;
						if (height < 0) {
							action = ACTION_NORTH;
							height = -height;
							top -= height;
							if (height < cropBoxData.minHeight) {
								height = cropBoxData.minHeight;
								top = cropBoxData.top - height;
							}
						}
						if (aspectRatio) {
							width = height * aspectRatio;
							left += (cropBoxData.width - width) / 2;
						}
						break;
					case ACTION_NORTH_EAST:
						if (aspectRatio) {
							if (range.y <= 0 && (top <= minTop || right >= maxWidth)) {
								renderable = false;
								break;
							}
							check(ACTION_NORTH);
							height -= range.y;
							top += range.y;
							width = height * aspectRatio;
						} else {
							check(ACTION_NORTH);
							check(ACTION_EAST);
							if (range.x >= 0) {
								if (right < maxWidth) width += range.x;
								else if (range.y <= 0 && top <= minTop) renderable = false;
							} else width += range.x;
							if (range.y <= 0) {
								if (top > minTop) {
									height -= range.y;
									top += range.y;
								}
							} else {
								height -= range.y;
								top += range.y;
							}
						}
						if (width < 0 && height < 0) {
							action = ACTION_SOUTH_WEST;
							height = -height;
							width = -width;
							top -= height;
							left -= width;
						} else if (width < 0) {
							action = ACTION_NORTH_WEST;
							width = -width;
							left -= width;
						} else if (height < 0) {
							action = ACTION_SOUTH_EAST;
							height = -height;
							top -= height;
						}
						break;
					case ACTION_NORTH_WEST:
						if (aspectRatio) {
							if (range.y <= 0 && (top <= minTop || left <= minLeft)) {
								renderable = false;
								break;
							}
							check(ACTION_NORTH);
							height -= range.y;
							top += range.y;
							width = height * aspectRatio;
							left += cropBoxData.width - width;
						} else {
							check(ACTION_NORTH);
							check(ACTION_WEST);
							if (range.x <= 0) {
								if (left > minLeft) {
									width -= range.x;
									left += range.x;
								} else if (range.y <= 0 && top <= minTop) renderable = false;
							} else {
								width -= range.x;
								left += range.x;
							}
							if (range.y <= 0) {
								if (top > minTop) {
									height -= range.y;
									top += range.y;
								}
							} else {
								height -= range.y;
								top += range.y;
							}
						}
						if (width < 0 && height < 0) {
							action = ACTION_SOUTH_EAST;
							height = -height;
							width = -width;
							top -= height;
							left -= width;
						} else if (width < 0) {
							action = ACTION_NORTH_EAST;
							width = -width;
							left -= width;
						} else if (height < 0) {
							action = ACTION_SOUTH_WEST;
							height = -height;
							top -= height;
						}
						break;
					case ACTION_SOUTH_WEST:
						if (aspectRatio) {
							if (range.x <= 0 && (left <= minLeft || bottom >= maxHeight)) {
								renderable = false;
								break;
							}
							check(ACTION_WEST);
							width -= range.x;
							left += range.x;
							height = width / aspectRatio;
						} else {
							check(ACTION_SOUTH);
							check(ACTION_WEST);
							if (range.x <= 0) {
								if (left > minLeft) {
									width -= range.x;
									left += range.x;
								} else if (range.y >= 0 && bottom >= maxHeight) renderable = false;
							} else {
								width -= range.x;
								left += range.x;
							}
							if (range.y >= 0) {
								if (bottom < maxHeight) height += range.y;
							} else height += range.y;
						}
						if (width < 0 && height < 0) {
							action = ACTION_NORTH_EAST;
							height = -height;
							width = -width;
							top -= height;
							left -= width;
						} else if (width < 0) {
							action = ACTION_SOUTH_EAST;
							width = -width;
							left -= width;
						} else if (height < 0) {
							action = ACTION_NORTH_WEST;
							height = -height;
							top -= height;
						}
						break;
					case ACTION_SOUTH_EAST:
						if (aspectRatio) {
							if (range.x >= 0 && (right >= maxWidth || bottom >= maxHeight)) {
								renderable = false;
								break;
							}
							check(ACTION_EAST);
							width += range.x;
							height = width / aspectRatio;
						} else {
							check(ACTION_SOUTH);
							check(ACTION_EAST);
							if (range.x >= 0) {
								if (right < maxWidth) width += range.x;
								else if (range.y >= 0 && bottom >= maxHeight) renderable = false;
							} else width += range.x;
							if (range.y >= 0) {
								if (bottom < maxHeight) height += range.y;
							} else height += range.y;
						}
						if (width < 0 && height < 0) {
							action = ACTION_NORTH_WEST;
							height = -height;
							width = -width;
							top -= height;
							left -= width;
						} else if (width < 0) {
							action = ACTION_SOUTH_WEST;
							width = -width;
							left -= width;
						} else if (height < 0) {
							action = ACTION_NORTH_EAST;
							height = -height;
							top -= height;
						}
						break;
					case ACTION_MOVE:
						this.move(range.x, range.y);
						renderable = false;
						break;
					case ACTION_ZOOM:
						zoomWithEvent.call(this, getMaxZoomRatio(pointers), event);
						renderable = false;
						break;
					case ACTION_CROP:
						if (!range.x || !range.y) {
							renderable = false;
							break;
						}
						offset = getOffset(this.cropper);
						left = pointer.startX - offset.left;
						top = pointer.startY - offset.top;
						if (aspectRatio) {
							width = Math.max(Math.abs(range.x), Math.abs(range.y) * aspectRatio, cropBoxData.minWidth, cropBoxData.minHeight * aspectRatio);
							height = width / aspectRatio;
						} else {
							width = Math.max(Math.abs(range.x), cropBoxData.minWidth);
							height = Math.max(Math.abs(range.y), cropBoxData.minHeight);
						}
						if (range.x > 0) action = range.y > 0 ? ACTION_SOUTH_EAST : ACTION_NORTH_EAST;
						else if (range.x < 0) {
							left -= width;
							action = range.y > 0 ? ACTION_SOUTH_WEST : ACTION_NORTH_WEST;
						}
						if (range.y < 0) top -= height;
						if (!this.cropped) {
							removeClass(this.cropBox, CLASS_HIDDEN);
							this.cropped = true;
							if (this.limited) this.limitCropBox(true, true);
						}
				}
				if (renderable) {
					cropBoxData.width = width;
					cropBoxData.height = height;
					cropBoxData.left = left;
					cropBoxData.top = top;
					this.action = action;
					this.renderCropBox();
				}
				forEach(pointers, function(p) {
					if (action !== ACTION_CROP && !isCropBoxDrag) {
						p.startX = p.endX;
						p.startY = p.endY;
					}
				});
			} };
			var AnotherCropper = WINDOW.Cropper;
			var Cropper = /*#__PURE__*/ function() {
				/**
				* Create a new Cropper.
				* @param {Element} element - The target image or canvas element to crop.
				* @param {Object} [options={}] - The configuration options.
				*/
				function Cropper(element) {
					var options = arguments.length > 1 && arguments[1] !== void 0 ? arguments[1] : {};
					_classCallCheck(this, Cropper);
					if (!element || !REGEXP_TAG_NAME.test(element.tagName)) throw new Error("The first argument is required and must be an <img> or <canvas> element.");
					this.element = element;
					this.options = assign({}, DEFAULTS, isPlainObject(options) && options);
					this.cropped = false;
					this.disabled = false;
					this.pointers = {};
					this.ready = false;
					this.reloading = false;
					this.replaced = false;
					this.sized = false;
					this.sizing = false;
					this.init();
				}
				return _createClass(Cropper, [
					{
						key: "init",
						value: function init() {
							var element = this.element;
							var tagName = element.tagName.toLowerCase();
							var url;
							if (element[NAMESPACE]) return;
							element[NAMESPACE] = this;
							if (tagName === "img") {
								this.isImg = true;
								url = element.getAttribute("src") || "";
								this.originalUrl = url;
								if (!url) return;
								url = element.src;
							} else if (tagName === "canvas" && window.HTMLCanvasElement) url = element.toDataURL();
							this.load(url);
						}
					},
					{
						key: "load",
						value: function load(url) {
							var _this = this;
							if (!url) return;
							this.url = url;
							this.imageData = {};
							var element = this.element, options = this.options;
							if (!options.rotatable && !options.scalable) options.checkOrientation = false;
							if (!options.checkOrientation || !window.ArrayBuffer) {
								this.clone();
								return;
							}
							if (REGEXP_DATA_URL.test(url)) {
								if (REGEXP_DATA_URL_JPEG.test(url)) this.read(dataURLToArrayBuffer(url));
								else this.clone();
								return;
							}
							var xhr = new XMLHttpRequest();
							var clone = this.clone.bind(this);
							this.reloading = true;
							this.xhr = xhr;
							xhr.onabort = clone;
							xhr.onerror = clone;
							xhr.ontimeout = clone;
							xhr.onprogress = function() {
								if (xhr.getResponseHeader("content-type") !== MIME_TYPE_JPEG) xhr.abort();
							};
							xhr.onload = function() {
								_this.read(xhr.response);
							};
							xhr.onloadend = function() {
								_this.reloading = false;
								_this.xhr = null;
							};
							if (options.checkCrossOrigin && isCrossOriginURL(url) && element.crossOrigin) url = addTimestamp(url);
							xhr.open("GET", url, true);
							xhr.responseType = "arraybuffer";
							xhr.withCredentials = element.crossOrigin === "use-credentials";
							xhr.send();
						}
					},
					{
						key: "read",
						value: function read(arrayBuffer) {
							var options = this.options, imageData = this.imageData;
							var orientation = resetAndGetOrientation(arrayBuffer);
							var rotate = 0;
							var scaleX = 1;
							var scaleY = 1;
							if (orientation > 1) {
								this.url = arrayBufferToDataURL(arrayBuffer, MIME_TYPE_JPEG);
								var _parseOrientation = parseOrientation(orientation);
								rotate = _parseOrientation.rotate;
								scaleX = _parseOrientation.scaleX;
								scaleY = _parseOrientation.scaleY;
							}
							if (options.rotatable) imageData.rotate = rotate;
							if (options.scalable) {
								imageData.scaleX = scaleX;
								imageData.scaleY = scaleY;
							}
							this.clone();
						}
					},
					{
						key: "clone",
						value: function clone() {
							var _this2 = this;
							var element = this.element, options = this.options, url = this.url;
							var crossOrigin = element.crossOrigin;
							var crossOriginUrl = url;
							if (this.options.checkCrossOrigin && isCrossOriginURL(url)) {
								if (!crossOrigin) crossOrigin = "anonymous";
								crossOriginUrl = addTimestamp(url);
							}
							this.crossOrigin = crossOrigin;
							this.crossOriginUrl = crossOriginUrl;
							var image = document.createElement("img");
							if (crossOrigin) image.crossOrigin = crossOrigin;
							image.src = crossOriginUrl || url;
							image.alt = element.alt || "The image to crop";
							this.image = image;
							image.onload = this.start.bind(this);
							if (isFunction(options.error)) addListener(element, EVENT_ERROR, options.error, { once: true });
							image.onerror = function(error) {
								dispatchEvent(element, EVENT_ERROR, error);
								_this2.stop();
							};
							addClass(image, CLASS_HIDE);
							element.parentNode.insertBefore(image, element.nextSibling);
						}
					},
					{
						key: "start",
						value: function start() {
							var _this3 = this;
							var image = this.image;
							image.onload = null;
							image.onerror = null;
							this.sizing = true;
							var isIOSWebKit = WINDOW.navigator && /(?:iPad|iPhone|iPod).*?AppleWebKit/i.test(WINDOW.navigator.userAgent);
							var done = function done(naturalWidth, naturalHeight) {
								assign(_this3.imageData, {
									naturalWidth,
									naturalHeight,
									aspectRatio: naturalWidth / naturalHeight
								});
								_this3.initialImageData = assign({}, _this3.imageData);
								_this3.sizing = false;
								_this3.sized = true;
								_this3.build();
							};
							var sizingImage = document.createElement("img");
							var body = document.body || document.documentElement;
							this.sizingImage = sizingImage;
							sizingImage.onload = function() {
								done(sizingImage.width, sizingImage.height);
								if (!isIOSWebKit) body.removeChild(sizingImage);
							};
							sizingImage.src = image.src;
							if (!isIOSWebKit) {
								sizingImage.style.cssText = "left:0;max-height:none!important;max-width:none!important;min-height:0!important;min-width:0!important;opacity:0;image-orientation:0deg;position:absolute;top:0;z-index:-1;";
								body.appendChild(sizingImage);
							}
						}
					},
					{
						key: "stop",
						value: function stop() {
							var image = this.image;
							image.onload = null;
							image.onerror = null;
							image.parentNode.removeChild(image);
							this.image = null;
						}
					},
					{
						key: "build",
						value: function build() {
							if (!this.sized || this.ready) return;
							var element = this.element, options = this.options, image = this.image;
							var container = element.parentNode;
							var template = document.createElement("div");
							template.innerHTML = TEMPLATE;
							var cropper = template.querySelector(".".concat(NAMESPACE, "-container"));
							var canvas = cropper.querySelector(".".concat(NAMESPACE, "-canvas"));
							var dragBox = cropper.querySelector(".".concat(NAMESPACE, "-drag-box"));
							var cropBox = cropper.querySelector(".".concat(NAMESPACE, "-crop-box"));
							var face = cropBox.querySelector(".".concat(NAMESPACE, "-face"));
							this.container = container;
							this.cropper = cropper;
							this.canvas = canvas;
							this.dragBox = dragBox;
							this.cropBox = cropBox;
							this.viewBox = cropper.querySelector(".".concat(NAMESPACE, "-view-box"));
							this.face = face;
							canvas.appendChild(image);
							addClass(element, CLASS_HIDDEN);
							container.insertBefore(cropper, element.nextSibling);
							removeClass(image, CLASS_HIDE);
							this.initPreview();
							this.bind();
							options.initialAspectRatio = Math.max(0, options.initialAspectRatio) || NaN;
							options.aspectRatio = Math.max(0, options.aspectRatio) || NaN;
							options.viewMode = Math.max(0, Math.min(3, Math.round(options.viewMode))) || 0;
							addClass(cropBox, CLASS_HIDDEN);
							if (!options.guides) addClass(cropBox.getElementsByClassName("".concat(NAMESPACE, "-dashed")), CLASS_HIDDEN);
							if (!options.center) addClass(cropBox.getElementsByClassName("".concat(NAMESPACE, "-center")), CLASS_HIDDEN);
							if (options.background) addClass(cropper, "".concat(NAMESPACE, "-bg"));
							if (!options.highlight) addClass(face, CLASS_INVISIBLE);
							if (options.cropBoxMovable) {
								addClass(face, CLASS_MOVE);
								setData(face, DATA_ACTION, ACTION_ALL);
							}
							if (!options.cropBoxResizable) {
								addClass(cropBox.getElementsByClassName("".concat(NAMESPACE, "-line")), CLASS_HIDDEN);
								addClass(cropBox.getElementsByClassName("".concat(NAMESPACE, "-point")), CLASS_HIDDEN);
							}
							this.render();
							this.ready = true;
							this.setDragMode(options.dragMode);
							if (options.autoCrop) this.crop();
							this.setData(options.data);
							if (isFunction(options.ready)) addListener(element, EVENT_READY, options.ready, { once: true });
							dispatchEvent(element, EVENT_READY);
						}
					},
					{
						key: "unbuild",
						value: function unbuild() {
							if (!this.ready) return;
							this.ready = false;
							this.unbind();
							this.resetPreview();
							var parentNode = this.cropper.parentNode;
							if (parentNode) parentNode.removeChild(this.cropper);
							removeClass(this.element, CLASS_HIDDEN);
						}
					},
					{
						key: "uncreate",
						value: function uncreate() {
							if (this.ready) {
								this.unbuild();
								this.ready = false;
								this.cropped = false;
							} else if (this.sizing) {
								this.sizingImage.onload = null;
								this.sizing = false;
								this.sized = false;
							} else if (this.reloading) {
								this.xhr.onabort = null;
								this.xhr.abort();
							} else if (this.image) this.stop();
						}
					}
				], [
					{
						key: "create",
						value: function create(element, options) {
							return new Cropper(element, options);
						}
					},
					{
						key: "setDefaults",
						value: function setDefaults(options) {
							assign(DEFAULTS, isPlainObject(options) && options);
						}
					},
					{
						key: "noConflict",
						value: function noConflict() {
							window.Cropper = AnotherCropper;
							return Cropper;
						}
					}
				]);
			}();
			assign(Cropper.prototype, render, preview, events, handlers, change, methods);
			return Cropper;
		}));
	})))());
	var MessageService = class {
		static showMessage(type, message) {
			Botble.showNotice(type, message);
		}
	};
	//#endregion
	//#region platform/core/media/resources/js/App/Services/ActionsService.js
	var ActionsService = class ActionsService {
		static handleDropdown() {
			let selected = Helpers.size(Helpers.getSelectedItems());
			ActionsService.renderActions();
			if (selected > 0) $(".rv-dropdown-actions > .dropdown-toggle").removeClass("disabled").prop("disabled", false);
			else $(".rv-dropdown-actions > .dropdown-toggle").addClass("disabled").prop("disabled", true);
		}
		static handlePreview() {
			const selectedFiles = Helpers.getSelectedFiles();
			if (Helpers.size(selectedFiles) === 1 && selectedFiles[0].type === "image") {
				const images = ActionsService.getPreviewableImages();
				const index = images.findIndex((image) => image.id === selectedFiles[0].id);
				if (images.length > 1 && index !== -1) {
					Helpers.addToRecent(selectedFiles[0].id);
					Botble.lightbox(images.map((image) => image.preview_url), index);
					return;
				}
			}
			let selected = [];
			Helpers.each(selectedFiles, (value) => {
				if (value.preview_url) {
					if (value.type === "document") {
						const iframe = document.createElement("iframe");
						iframe.src = value.preview_url;
						iframe.allowFullscreen = true;
						iframe.style.width = "100vh";
						iframe.style.height = "100vh";
						selected.push(iframe);
					} else selected.push(value.preview_url);
					Helpers.addToRecent(value.id);
				}
			});
			if (Helpers.size(selected) > 0) Botble.lightbox(selected);
			else this.handleGlobalAction("download");
		}
		/**
		* Images currently listed, in display order, that can be shown in the lightbox.
		*
		* Reads the rows directly rather than going through Helpers.getItems(), which
		* computes a positional index per row and would walk the sibling list once per
		* item on what is now a click-blocking path.
		*/
		static getPreviewableImages() {
			const images = [];
			$(".js-media-list-title[data-context=file]").each((index, el) => {
				const data = $(el).data() || {};
				if (data.type === "image" && data.preview_url) images.push(data);
			});
			return images;
		}
		static renderCropImage() {
			const html = $("#rv_media_crop_image").html();
			const modal = $("#modal_crop_image .crop-image").empty();
			const item = Helpers.getSelectedItems()[0];
			const form = $("#modal_crop_image .form-crop");
			let cropData;
			const el = html.replace(/__src__/gi, item.full_url);
			modal.append(el);
			const image = modal.find("img")[0];
			const options = {
				minContainerWidth: 500,
				minContainerHeight: 550,
				dragMode: "move",
				crop(event) {
					cropData = event.detail;
					form.find("input[name=\"image_id\"]").val(item.id);
					form.find("input[name=\"crop_data\"]").val(JSON.stringify(cropData));
					setHeight(cropData.height);
					setWidth(cropData.width);
				}
			};
			let cropper = new import_cropper.default(image, options);
			form.find("#aspectRatio").on("click", function() {
				cropper.destroy();
				if ($(this).is(":checked")) options.aspectRatio = cropData.width / cropData.height;
				else options.aspectRatio = null;
				cropper = new import_cropper.default(image, options);
			});
			form.find("#dataHeight").on("change", function() {
				cropData.height = parseFloat($(this).val());
				cropper.setData(cropData);
				setHeight(cropData.height);
			});
			form.find("#dataWidth").on("change", function() {
				cropData.width = parseFloat($(this).val());
				cropper.setData(cropData);
				setWidth(cropData.width);
			});
			const setHeight = (height) => {
				form.find("#dataHeight").val(parseInt(height));
			};
			const setWidth = (width) => {
				form.find("#dataWidth").val(parseInt(width));
			};
		}
		static async handleCopyLink() {
			let links = "";
			Helpers.each(Helpers.getSelectedFiles(), (value) => {
				if (!Helpers.isEmpty(links)) links += "\n";
				links += value.full_url;
			});
			await Botble.copyToClipboard(links);
			MessageService.showMessage("success", Helpers.trans("clipboard.success"), Helpers.trans("message.success_header"));
		}
		static async handleCopyIndirectLink() {
			let links = "";
			Helpers.each(Helpers.getSelectedFiles(), (value) => {
				if (!Helpers.isEmpty(links)) links += "\n";
				links += value.indirect_url;
			});
			await Botble.copyToClipboard(links);
			MessageService.showMessage("success", Helpers.trans("clipboard.success"), Helpers.trans("message.success_header"));
		}
		static handleShare() {
			$("#modal_share_items").modal("show").find("form.form-alt-text").data("action", type);
		}
		static handleGlobalAction(type, callback) {
			let selected = [];
			Helpers.each(Helpers.getSelectedItems(), (value) => {
				selected.push({
					is_folder: value.is_folder,
					id: value.id,
					full_url: value.full_url
				});
			});
			switch (type) {
				case "rename":
					$("#modal_rename_items").modal("show").find("form.form-rename").data("action", type);
					break;
				case "copy_link":
					ActionsService.handleCopyLink().then(() => {});
					break;
				case "copy_indirect_link":
					ActionsService.handleCopyIndirectLink().then(() => {});
					break;
				case "share":
					$("#modal_share_items").modal("show");
					break;
				case "preview":
					ActionsService.handlePreview();
					break;
				case "alt_text":
					$("#modal_alt_text_items").modal("show").find("form.form-alt-text").data("action", type);
					break;
				case "move":
					$("#modal_move_items").modal("show");
					break;
				case "crop":
					$("#modal_crop_image").modal("show").find("form.rv-form").data("action", type);
					break;
				case "trash":
					$("#modal_trash_items").modal("show").find("form.form-delete-items").data("action", type);
					break;
				case "delete":
					$("#modal_delete_items").modal("show").find("form.form-delete-items").data("action", type);
					break;
				case "empty_trash":
					$("#modal_empty_trash").modal("show").find("form.form-empty-trash").data("action", type);
					break;
				case "download":
					let files = [];
					Helpers.each(Helpers.getSelectedItems(), (value) => {
						if (!Helpers.inArray(Helpers.getConfigs().denied_download, value.mime_type)) files.push({
							id: value.id,
							is_folder: value.is_folder
						});
					});
					if (files.length) ActionsService.handleDownload(files);
					else MessageService.showMessage("error", Helpers.trans("download.error"), Helpers.trans("message.error_header"));
					break;
				case "properties":
					$("#modal-properties").modal("show");
					break;
				case "manage_access":
					ActionsService.handleManageAccess();
					break;
				default: ActionsService.processAction({
					selected,
					action: type
				}, callback);
			}
		}
		static processAction(data, callback = null) {
			Helpers.showAjaxLoading();
			$httpClient.make().post(RV_MEDIA_URL.global_actions, data).then(({ data }) => {
				Helpers.resetPagination();
				MessageService.showMessage("success", data.message, Helpers.trans("message.success_header"));
				callback && callback(data);
			}).catch(({ response }) => callback && callback(response.data)).finally(() => Helpers.hideAjaxLoading());
		}
		static renderRenameItems() {
			let VIEW = $("#rv_media_rename_item").html();
			let $itemsWrapper = $("#modal_rename_items .rename-items").empty();
			Helpers.each(Helpers.getSelectedItems(), (value) => {
				let item = VIEW.replace(/__icon__/gi, value.icon || `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path>
                </svg>`).replace(/__placeholder__/gi, "Input file name").replace(/__value__/gi, value.name);
				let $item = $(item);
				$item.data("id", value.id.toString());
				$item.data("is_folder", value.is_folder);
				$item.data("name", value.name);
				const $renamePhysicalFile = $item.find("input[name=\"rename_physical_file\"]");
				$renamePhysicalFile.closest(".form-check").find("span").text(value.is_folder ? $renamePhysicalFile.data("folder-label") : $renamePhysicalFile.data("file-label"));
				$item.find("input[name=\"rename_physical_file\"]").on("change", function() {
					$item.data("rename_physical_file", $(this).is(":checked"));
				});
				$itemsWrapper.append($item);
				Botble.initFieldCollapse();
			});
		}
		static renderAltTextItems() {
			let VIEW = $("#rv_media_alt_text_item").html();
			let $itemsWrapper = $("#modal_alt_text_items .alt-text-items").empty();
			Helpers.each(Helpers.getSelectedItems(), (value) => {
				let item = VIEW.replace(/__icon__/gi, value.icon || `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path>
                </svg>`).replace(/__placeholder__/gi, "Input file alt").replace(/__value__/gi, value.alt === null ? "" : value.alt);
				let $item = $(item);
				$item.data("id", value.id);
				$item.data("alt", value.alt);
				$itemsWrapper.append($item);
			});
		}
		static renderShareItems() {
			const target = $("#modal_share_items [data-bb-value=\"share-result\"]");
			const shareType = $("#modal_share_items select[data-bb-value=\"share-type\"]").val();
			target.val("");
			let results = [];
			Helpers.each(Helpers.getSelectedItems(), (value) => {
				let imageUrl = value.full_url;
				if ((shareType === "html" || shareType === "markdown") && imageUrl && imageUrl.startsWith(window.location.origin)) imageUrl = imageUrl.replace(window.location.origin, "");
				switch (shareType) {
					case "html":
						results.push(value.type === "image" ? `<img src="${imageUrl}" alt="${value.alt}" />` : `<a href="${imageUrl}" target="_blank">${value.alt}</a>`);
						break;
					case "markdown":
						results.push((value.type === "image" ? "!" : "") + `[${value.alt}](${imageUrl})`);
						break;
					case "indirect_url":
						results.push(value.indirect_url);
						break;
					default: results.push(value.full_url);
				}
			});
			target.val(results.join("\n"));
		}
		static renderActions() {
			let hasFolderSelected = Helpers.getSelectedFolder().length > 0;
			let ACTION_TEMPLATE = $("#rv_action_item").html();
			let initializedItem = 0;
			let $dropdownActions = $(".rv-dropdown-actions .dropdown-menu");
			$dropdownActions.empty();
			let actionsList = $.extend({}, true, Helpers.getConfigs().actions_list);
			if (hasFolderSelected) {
				const ignoreActions = [
					"preview",
					"crop",
					"alt_text",
					"copy_link",
					"copy_indirect_link",
					"share"
				];
				actionsList.basic = Helpers.arrayReject(actionsList.basic, (item) => ignoreActions.includes(item.action));
				actionsList.file = Helpers.arrayReject(actionsList.file, (item) => ignoreActions.includes(item.action));
				if (!Helpers.hasPermission("folders.create")) actionsList.file = Helpers.arrayReject(actionsList.file, (item) => {
					return item.action === "make_copy";
				});
				if (!Helpers.hasPermission("folders.edit")) {
					actionsList.file = Helpers.arrayReject(actionsList.file, (item) => {
						return Helpers.inArray(["rename", "move"], item.action);
					});
					actionsList.user = Helpers.arrayReject(actionsList.user, (item) => {
						return Helpers.inArray(["rename"], item.action);
					});
				}
				if (!Helpers.hasPermission("folders.trash")) actionsList.other = Helpers.arrayReject(actionsList.other, (item) => {
					return Helpers.inArray(["trash", "restore"], item.action);
				});
				if (!Helpers.hasPermission("folders.destroy")) actionsList.other = Helpers.arrayReject(actionsList.other, (item) => {
					return Helpers.inArray(["delete"], item.action);
				});
				if (!Helpers.hasPermission("folders.favorite")) actionsList.other = Helpers.arrayReject(actionsList.other, (item) => {
					return Helpers.inArray(["favorite", "remove_favorite"], item.action);
				});
			}
			let selectedFiles = Helpers.getSelectedFiles();
			if (!Helpers.arrayFilter(selectedFiles, function(value) {
				return value.preview_url;
			}).length) actionsList.basic = Helpers.arrayReject(actionsList.basic, (item) => {
				return item.action === "preview";
			});
			if (!Helpers.arrayFilter(selectedFiles, function(value) {
				return value.type === "image";
			}).length) {
				actionsList.basic = Helpers.arrayReject(actionsList.basic, (item) => {
					return item.action === "crop";
				});
				actionsList.file = Helpers.arrayReject(actionsList.file, (item) => {
					return item.action === "alt_text";
				});
			}
			if (selectedFiles.length > 0) {
				if (!Helpers.hasPermission("files.create")) actionsList.file = Helpers.arrayReject(actionsList.file, (item) => {
					return item.action === "make_copy";
				});
				if (!Helpers.hasPermission("files.edit")) actionsList.file = Helpers.arrayReject(actionsList.file, (item) => {
					return Helpers.inArray(["rename", "move"], item.action);
				});
				if (!Helpers.hasPermission("files.trash")) actionsList.other = Helpers.arrayReject(actionsList.other, (item) => {
					return Helpers.inArray(["trash", "restore"], item.action);
				});
				if (!Helpers.hasPermission("files.destroy")) actionsList.other = Helpers.arrayReject(actionsList.other, (item) => {
					return Helpers.inArray(["delete"], item.action);
				});
				if (!Helpers.hasPermission("files.favorite")) actionsList.other = Helpers.arrayReject(actionsList.other, (item) => {
					return Helpers.inArray(["favorite", "remove_favorite"], item.action);
				});
				if (selectedFiles.length > 1) actionsList.basic = Helpers.arrayReject(actionsList.basic, (item) => {
					return item.action === "crop";
				});
			}
			if (!Helpers.hasPermission("folders.edit") || selectedFiles.length > 0) actionsList.other = Helpers.arrayReject(actionsList.other, (item) => {
				return Helpers.inArray(["properties"], item.action);
			});
			Helpers.each(actionsList, (action, key) => {
				Helpers.each(action, (item, index) => {
					let is_break = false;
					switch (Helpers.getRequestParams().view_in) {
						case "all_media":
							if (Helpers.inArray([
								"remove_favorite",
								"delete",
								"restore"
							], item.action)) is_break = true;
							break;
						case "recent":
							if (Helpers.inArray([
								"remove_favorite",
								"delete",
								"restore",
								"make_copy"
							], item.action)) is_break = true;
							break;
						case "favorites":
							if (Helpers.inArray([
								"favorite",
								"delete",
								"restore",
								"make_copy"
							], item.action)) is_break = true;
							break;
						case "trash": if (!Helpers.inArray([
							"preview",
							"delete",
							"restore",
							"rename",
							"download"
						], item.action)) is_break = true;
					}
					if (!is_break) {
						let template = ACTION_TEMPLATE.replace(/__action__/gi, item.action || "").replace("<i class=\"__icon__ dropdown-item-icon dropdown-item-icon\"></i>", "<span class=\"icon-tabler-wrapper dropdown-item-icon\">__icon__</span>").replace("__icon__", "<span class=\"icon-tabler-wrapper dropdown-item-icon\">__icon__</span>").replace("__icon__", item.icon || "").replace(/__name__/gi, Helpers.trans(`actions_list.${key}.${item.action}`, item.name));
						if (item.icon) template = template.replace("media-icon", "media-icon dropdown-item-icon");
						if (!index && initializedItem) template = `<li role="separator" class="divider"></li>${template}`;
						$dropdownActions.append(template);
					}
				});
				if (action.length > 0) initializedItem++;
			});
		}
		static handleDownload(files) {
			const html = $(".media-download-popup");
			let downloadTimeout = null;
			html.show();
			$httpClient.make().withResponseType("blob").post(RV_MEDIA_URL.download, { selected: files }).then((response) => {
				let fileName = "download";
				const contentDisposition = response.headers["content-disposition"] || "";
				if (contentDisposition) {
					const fileNameMatch = contentDisposition.match(/filename\*=UTF-8''(.+)|filename="?([^";\n]+)"?/i);
					if (fileNameMatch) fileName = decodeURIComponent(fileNameMatch[1] || fileNameMatch[2]);
				}
				const objectUrl = URL.createObjectURL(response.data);
				const a = document.createElement("a");
				a.href = objectUrl;
				a.download = fileName;
				document.body.appendChild(a);
				a.click();
				a.remove();
				URL.revokeObjectURL(objectUrl);
			}).finally(() => {
				html.hide();
				clearTimeout(downloadTimeout);
			});
		}
		static handleManageAccess() {
			let selectedFolders = Helpers.getSelectedFolder();
			if (!selectedFolders.length) return;
			let folderId = selectedFolders[0].id;
			let modal = $("#modal_folder_permissions");
			let permissionsList = $("#folder-permissions-list");
			let permUrl = RV_MEDIA_URL.folder_permissions.replace("__FOLDER_ID__", folderId);
			modal.data("folder-id", folderId);
			modal.modal("show");
			$httpClient.make().get(RV_MEDIA_URL.folder_permissions_users).then(({ data }) => {
				let select = $("#folder-permission-user-select");
				select.empty().append("<option value=\"\">-- Select user --</option>");
				data.data.forEach((user) => {
					let opt = $("<option></option>").val(user.id).text(`${user.name} (${user.email})`);
					select.append(opt);
				});
			});
			ActionsService._loadFolderPermissions(folderId, permissionsList, permUrl);
			$("#btn-grant-folder-permission").off("click").on("click", () => {
				let userId = $("#folder-permission-user-select").val();
				let permission = $("#folder-permission-level-select").val();
				if (!userId) return;
				$httpClient.make().post(permUrl, {
					user_id: userId,
					permission
				}).then(() => {
					ActionsService._loadFolderPermissions(folderId, permissionsList, permUrl);
					MessageService.showMessage("success", "Permission granted", "Success");
				}).catch(({ response }) => {
					var _response$data;
					MessageService.showMessage("error", (response === null || response === void 0 || (_response$data = response.data) === null || _response$data === void 0 ? void 0 : _response$data.message) || "Error", "Error");
				});
			});
		}
		static _loadFolderPermissions(folderId, container, permUrl) {
			container.html("<div class=\"text-muted text-center py-3\">Loading...</div>");
			$httpClient.make().get(permUrl).then(({ data }) => {
				container.empty();
				if (!data.data || !data.data.length) {
					container.html("<div class=\"text-muted text-center py-3\">No permissions set</div>");
					return;
				}
				data.data.forEach((perm) => {
					let levelBadge = perm.permission === "manage" ? "bg-danger" : perm.permission === "upload" ? "bg-warning" : "bg-info";
					let row = $("<div class=\"list-group-item d-flex justify-content-between align-items-center\"></div>");
					let info = $("<div></div>");
					info.append($("<strong></strong>").text(perm.user_name));
					info.append($("<small class=\"text-muted d-block\"></small>").text(perm.user_email));
					row.append(info);
					let actions = $("<div class=\"d-flex align-items-center gap-2\"></div>");
					actions.append($(`<span class="badge ${levelBadge}"></span>`).text(perm.permission));
					actions.append($(`<button type="button" class="btn btn-sm btn-outline-danger btn-revoke-permission"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg></button>`).data("user-id", perm.user_id));
					row.append(actions);
					container.append(row);
				});
				container.find(".btn-revoke-permission").on("click", function() {
					let userId = $(this).data("user-id");
					$httpClient.make().delete(permUrl + "/" + userId).then(() => {
						ActionsService._loadFolderPermissions(folderId, container, permUrl);
						MessageService.showMessage("success", "Permission revoked", "Success");
					});
				});
			});
		}
	};
	//#endregion
	//#region platform/core/media/resources/js/App/Services/ContextMenuService.js
	var ContextMenuService = class ContextMenuService {
		static initContext() {
			if (jQuery().contextMenu) {
				$.contextMenu({
					selector: ".js-context-menu[data-context=\"file\"]",
					build: () => {
						return { items: ContextMenuService._fileContextMenu() };
					}
				});
				$.contextMenu({
					selector: ".js-context-menu[data-context=\"folder\"]",
					build: () => {
						return { items: ContextMenuService._folderContextMenu() };
					}
				});
			}
		}
		static _fileContextMenu() {
			let items = { preview: {
				name: "Preview",
				icon: (opt, $itemElement, itemKey, item) => {
					$itemElement.html(`<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                    </svg> ${item.name}`);
					return "context-menu-icon-updated";
				},
				callback: () => {
					ActionsService.handlePreview();
				}
			} };
			Helpers.each(Helpers.getConfigs().actions_list, (actionGroup, key) => {
				Helpers.each(actionGroup, (value) => {
					items[value.action] = {
						name: value.name,
						icon: (opt, $itemElement, itemKey, item) => {
							$itemElement.html(`${value.icon} ${Helpers.trans(`actions_list.${key}.${value.action}`, value.name)}`);
							return `context-menu-icon-updated media-action-${value.action}`;
						},
						callback: () => {
							$(`.js-files-action[data-action="${value.action}"]`).trigger("click");
						}
					};
				});
			});
			let except = [];
			switch (Helpers.getRequestParams().view_in) {
				case "all_media":
					except = [
						"remove_favorite",
						"delete",
						"restore"
					];
					break;
				case "recent":
					except = [
						"remove_favorite",
						"delete",
						"restore",
						"make_copy"
					];
					break;
				case "favorites":
					except = [
						"favorite",
						"delete",
						"restore",
						"make_copy"
					];
					break;
				case "trash": items = {
					preview: items.preview,
					rename: items.rename,
					download: items.download,
					delete: items.delete,
					restore: items.restore
				};
			}
			Helpers.each(except, (value) => {
				items[value] = void 0;
			});
			if (Helpers.getSelectedFolder().length > 0) {
				items.preview = void 0;
				items.crop = void 0;
				items.copy_link = void 0;
				items.copy_indirect_link = void 0;
				items.share = void 0;
				items.alt_text = void 0;
				if (!Helpers.hasPermission("folders.create")) items.make_copy = void 0;
				if (!Helpers.hasPermission("folders.edit")) items.rename = void 0;
				if (!Helpers.hasPermission("folders.trash")) {
					items.trash = void 0;
					items.restore = void 0;
				}
				if (!Helpers.hasPermission("folders.destroy")) items.delete = void 0;
				if (!Helpers.hasPermission("folders.favorite")) {
					items.favorite = void 0;
					items.remove_favorite = void 0;
				}
			}
			let selectedFiles = Helpers.getSelectedFiles();
			if (selectedFiles.length > 0) {
				if (!Helpers.hasPermission("files.create")) items.make_copy = void 0;
				if (!Helpers.hasPermission("files.edit")) items.rename = void 0;
				if (!Helpers.hasPermission("files.trash")) {
					items.trash = void 0;
					items.restore = void 0;
				}
				if (!Helpers.hasPermission("files.destroy")) items.delete = void 0;
				if (!Helpers.hasPermission("files.favorite")) {
					items.favorite = void 0;
					items.remove_favorite = void 0;
				}
				if (selectedFiles.length > 1) items.crop = void 0;
				items.properties = void 0;
			}
			if (!Helpers.arrayFilter(selectedFiles, function(value) {
				return value.preview_url;
			}).length) items.preview = void 0;
			if (!Helpers.arrayFilter(selectedFiles, function(value) {
				return value.type === "image";
			}).length) {
				items.crop = void 0;
				items.alt_text = void 0;
			}
			if (!Helpers.arrayFilter(selectedFiles, function(value) {
				return value.full_url;
			}).length) items.copy_link = void 0;
			return items;
		}
		static _folderContextMenu() {
			let items = ContextMenuService._fileContextMenu();
			items.preview = void 0;
			items.copy_link = void 0;
			items.manage_access = {
				name: Helpers.trans("manage_access", "Manage Access"),
				icon: (opt, $itemElement, itemKey, item) => {
					$itemElement.html(`<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path></svg> ${item.name}`);
					return "context-menu-icon-updated";
				},
				callback: () => {
					ActionsService.handleManageAccess();
				}
			};
			return items;
		}
		static destroyContext() {
			if (jQuery().contextMenu) $.contextMenu("destroy");
		}
	};
	//#endregion
	//#region platform/core/media/resources/js/integrate.js
	var EditorService = class {
		static editorSelectFile(selectedFiles) {
			let is_ckeditor = Helpers.getUrlParam("CKEditor") || Helpers.getUrlParam("CKEditorFuncNum");
			if (window.opener && is_ckeditor) {
				let imageUrl = Helpers.arrayFirst(selectedFiles).full_url;
				if (imageUrl && imageUrl.startsWith(window.location.origin)) imageUrl = imageUrl.replace(window.location.origin, "");
				window.opener.CKEDITOR.tools.callFunction(Helpers.getUrlParam("CKEditorFuncNum"), imageUrl);
				if (window.opener) window.close();
			}
		}
	};
	var rvMedia = class {
		constructor(selector, options) {
			let customHandler = window.RvMediaCustomCallback || null;
			if (typeof customHandler === "function") {
				customHandler(selector, options);
				return;
			}
			window.rvMedia = window.rvMedia || {};
			let $body = $("body");
			options = $.extend(true, {
				multiple: true,
				type: "*",
				onSelectFiles: (files, $el) => {}
			}, options);
			let clickCallback = (event) => {
				event.preventDefault();
				let $current = $(event.currentTarget);
				$("#rv_media_modal").modal("show");
				window.rvMedia.options = options;
				window.rvMedia.options.open_in = "modal";
				window.rvMedia.$el = $current;
				MediaConfig.request_params.filter = "everything";
				Helpers.storeConfig();
				let elementOptions = window.rvMedia.$el.data("rv-media");
				if (typeof elementOptions !== "undefined" && elementOptions.length > 0) {
					elementOptions = elementOptions[0];
					window.rvMedia.options = $.extend(true, window.rvMedia.options, elementOptions || {});
					if (typeof elementOptions.selected_file_id !== "undefined") window.rvMedia.options.is_popup = true;
					else if (typeof window.rvMedia.options.is_popup !== "undefined") window.rvMedia.options.is_popup = void 0;
				}
				if ($("#rv_media_body .rv-media-container").length === 0) $("#rv_media_body").load(RV_MEDIA_URL.popup, (data) => {
					if (data.error) alert(data.message);
					$("#rv_media_body").removeClass("media-modal-loading").closest(".modal-content").removeClass("bb-loading");
					$(document).find(".rv-media-container .js-change-action[data-type=refresh]").trigger("click");
					if (Helpers.getRequestParams().filter !== "everything") $(".rv-media-actions .btn.js-rv-media-change-filter-group.js-filter-by-type").hide();
					ContextMenuService.destroyContext();
					ContextMenuService.initContext();
					if (Helpers.isUseInModal()) $(".rv-media-footer").removeClass("d-none");
				});
				else {
					if (Helpers.isUseInModal()) $(".rv-media-footer").removeClass("d-none");
					$(document).find(".rv-media-container .js-change-action[data-type=refresh]").trigger("click");
				}
			};
			if (typeof selector === "string") $body.off("click", selector).on("click", selector, clickCallback);
			else selector.off("click").on("click", clickCallback);
		}
	};
	window.RvMediaStandAlone = rvMedia;
	$(".js-insert-to-editor").off("click").on("click", function(event) {
		event.preventDefault();
		let selectedFiles = Helpers.getSelectedFiles();
		if (Helpers.size(selectedFiles) > 0) EditorService.editorSelectFile(selectedFiles);
	});
	$.fn.rvMedia = function(options) {
		let $selector = $(this);
		MediaConfig.request_params.filter = "everything";
		$(document).find(".js-insert-to-editor").prop("disabled", MediaConfig.request_params.view_in === "trash");
		Helpers.storeConfig();
		let customHandler = window.RvMediaCustomCallback || null;
		if (typeof customHandler === "function") {
			customHandler($selector, options);
			return;
		}
		new rvMedia($selector, options);
	};
	document.dispatchEvent(new CustomEvent("core-media-loaded"));
	//#endregion
	exports.EditorService = EditorService;
})(this.__botble_integrate = this.__botble_integrate || {});

//# sourceMappingURL=integrate.js.map