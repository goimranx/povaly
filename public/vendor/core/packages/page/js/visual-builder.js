(function() {
	//#region platform/packages/page/resources/js/visual-builder/utils/postmessage.js
	var PostMessageUtil$1 = {
		allowedOrigin: window.location.origin,
		sendToIframe(iframe, type, payload) {
			if (!iframe || !iframe.contentWindow) return;
			const message = {
				source: "visual-builder",
				type,
				payload: payload || {}
			};
			try {
				iframe.contentWindow.postMessage(message, this.allowedOrigin);
			} catch (e) {}
		},
		listenToIframe(callback) {
			window.addEventListener("message", (event) => {
				if (event.origin !== this.allowedOrigin) return;
				if (!event.data || event.data.source !== "visual-builder-preview") return;
				if (typeof callback === "function") callback(event.data.type, event.data.payload);
			});
		},
		sendToParent(type, payload) {
			if (window.parent === window) return;
			const message = {
				source: "visual-builder-preview",
				type,
				payload: payload || {}
			};
			try {
				window.parent.postMessage(message, this.allowedOrigin);
			} catch (e) {}
		}
	};
	window.PostMessageUtil = PostMessageUtil$1;
	//#endregion
	//#region platform/packages/page/resources/js/visual-builder/utils/inject-edit-icons.js
	window.EditIconInjector = {
		inject(iframeDocument) {
			if (!iframeDocument) return;
			this.blockLinks(iframeDocument);
			const shortcodeElements = iframeDocument.querySelectorAll("[data-shortcode-id]");
			if (shortcodeElements.length === 0) return;
			shortcodeElements.forEach((element) => {
				this.injectIcon(element, iframeDocument);
			});
		},
		injectIcon(element, iframeDocument) {
			const shortcodeId = element.getAttribute("data-shortcode-id");
			const shortcodeName = element.getAttribute("data-shortcode-name") || "shortcode";
			if (element.querySelector(":scope > .vb-shortcode-toolbar")) return;
			if (element.classList.contains("vb-shortcode-toolbar")) return;
			if (window.getComputedStyle(element).position === "static") element.style.position = "relative";
			element.classList.add("vb-shortcode-block");
			const toolbar = iframeDocument.createElement("div");
			toolbar.className = "vb-shortcode-toolbar";
			toolbar.setAttribute("data-shortcode-id", shortcodeId);
			const editBtn = iframeDocument.createElement("button");
			editBtn.className = "vb-toolbar-btn vb-edit-btn";
			editBtn.setAttribute("title", `Edit ${shortcodeName}`);
			editBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>`;
			editBtn.addEventListener("click", (e) => {
				e.preventDefault();
				e.stopPropagation();
				this.handleEditClick(shortcodeId);
			});
			const deleteBtn = iframeDocument.createElement("button");
			deleteBtn.className = "vb-toolbar-btn vb-delete-btn";
			deleteBtn.setAttribute("title", `Delete ${shortcodeName}`);
			deleteBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>`;
			deleteBtn.addEventListener("click", (e) => {
				e.preventDefault();
				e.stopPropagation();
				this.handleDeleteClick(shortcodeId);
			});
			toolbar.appendChild(editBtn);
			toolbar.appendChild(deleteBtn);
			element.insertBefore(toolbar, element.firstChild);
		},
		handleEditClick(shortcodeId) {
			if (typeof ShortcodeList !== "undefined") ShortcodeList.openEditPanel(shortcodeId);
		},
		handleDeleteClick(shortcodeId) {
			if (typeof ShortcodeList !== "undefined") ShortcodeList.deleteShortcode(shortcodeId);
		},
		blockLinks(iframeDocument) {
			if (iframeDocument._linksBlocked) return;
			iframeDocument.addEventListener("click", (e) => {
				if (e.target.closest("a")) {
					e.preventDefault();
					e.stopPropagation();
				}
			}, true);
			iframeDocument._linksBlocked = true;
		},
		removeAll(iframeDocument) {
			if (!iframeDocument) return;
			iframeDocument.querySelectorAll(".vb-shortcode-toolbar").forEach((toolbar) => toolbar.remove());
		},
		highlight(iframeDocument, shortcodeId) {
			if (!iframeDocument) return;
			iframeDocument.querySelectorAll(".vb-active").forEach((el) => el.classList.remove("vb-active"));
			if (shortcodeId) {
				const element = iframeDocument.querySelector(`[data-shortcode-id="${shortcodeId}"]`);
				if (element) {
					element.classList.add("vb-active");
					element.scrollIntoView({
						behavior: "smooth",
						block: "center"
					});
				}
			}
		},
		unhighlight(iframeDocument) {
			if (!iframeDocument) return;
			iframeDocument.querySelectorAll(".vb-active").forEach((el) => el.classList.remove("vb-active"));
		}
	};
	//#endregion
	//#region platform/packages/page/resources/js/visual-builder/utils/shortcode-serializer.js
	window.ShortcodeSerializer = {
		serialize(shortcodes) {
			if (!Array.isArray(shortcodes)) return "";
			return shortcodes.map((sc) => this.serializeOne(sc)).join("\n\n");
		},
		serializeOne(shortcode) {
			if (!shortcode || !shortcode.name) return "";
			let output = "[" + shortcode.name;
			if (shortcode.attributes && typeof shortcode.attributes === "object") {
				for (const [key, value] of Object.entries(shortcode.attributes)) if (value !== null && value !== void 0 && value !== "") {
					const escapedValue = this.escapeAttribute(value);
					output += ` ${key}="${escapedValue}"`;
				}
			}
			output += "]";
			if (shortcode.content) output += shortcode.content;
			output += "[/" + shortcode.name + "]";
			return output;
		},
		escapeAttribute(value) {
			if (typeof value !== "string") value = String(value);
			return value.replace(/\\/g, "\\\\").replace(/"/g, "\\\"");
		},
		parse(shortcodeString) {
			const pattern = new RegExp("\\[(\\w+)([^\\]]*?)(?:\\s*\\/\\]|\\](.*?)\\[\\/\\1\\])", "gs");
			return [...shortcodeString.matchAll(pattern)].map((match, index) => {
				const name = match[1];
				const attributesString = match[2] || "";
				const content = match[3] || "";
				const isSelfClosing = match[0].endsWith("/]");
				return {
					id: "sc_parsed_" + Date.now() + "_" + index,
					name,
					attributes: this.parseAttributes(attributesString),
					content,
					isSelfClosing,
					position: index,
					raw: match[0]
				};
			});
		},
		parseAttributes(attributesString) {
			const attributes = {};
			if (!attributesString || !attributesString.trim()) return attributes;
			const pattern = /(\w+)\s*=\s*(?:"((?:[^\\"]|\\.)*)"|'((?:[^\\']|\\.)*)')/g;
			let match;
			while ((match = pattern.exec(attributesString)) !== null) {
				const key = match[1];
				const value = match[2] || match[3] || "";
				attributes[key] = this.unescapeAttribute(value);
			}
			return attributes;
		},
		unescapeAttribute(value) {
			if (typeof value !== "string") return value;
			return value.replace(/\\"/g, "\"").replace(/\\\\/g, "\\");
		},
		validate(shortcodeString) {
			const errors = [];
			if ((shortcodeString.match(/\[/g) || []).length !== (shortcodeString.match(/\]/g) || []).length) errors.push("Mismatched brackets");
			const pattern = new RegExp("\\[(\\w+)(?:[^\\]]*?)(?:\\s*\\/\\]|\\](.*?)\\[\\/\\1\\])", "gs");
			const matches = [...shortcodeString.matchAll(pattern)];
			const allShortcodes = shortcodeString.match(/\[\w+/g) || [];
			if (matches.length * 2 < allShortcodes.length) errors.push("Possible unclosed shortcodes");
			return {
				valid: errors.length === 0,
				errors
			};
		}
	};
	//#endregion
	//#region platform/packages/page/resources/js/visual-builder/history-manager.js
	var HistoryManager$1 = class {
		constructor(state, maxLength = 20) {
			this.state = state;
			this.stack = [];
			this.pointer = -1;
			this.maxLength = maxLength;
			this.isLocked = false;
		}
		init(initialData) {
			this.stack = [JSON.parse(JSON.stringify(initialData))];
			this.pointer = 0;
		}
		push(data) {
			if (this.isLocked) return;
			if (this.pointer < this.stack.length - 1) this.stack = this.stack.slice(0, this.pointer + 1);
			this.stack.push(JSON.parse(JSON.stringify(data)));
			if (this.stack.length > this.maxLength) this.stack.shift();
			else this.pointer++;
			this.state.emit("history-changed", {
				canUndo: this.canUndo(),
				canRedo: this.canRedo()
			});
		}
		undo() {
			if (!this.canUndo()) return null;
			this.isLocked = true;
			this.pointer--;
			const data = JSON.parse(JSON.stringify(this.stack[this.pointer]));
			this.isLocked = false;
			this.state.emit("history-changed", {
				canUndo: this.canUndo(),
				canRedo: this.canRedo()
			});
			return data;
		}
		redo() {
			if (!this.canRedo()) return null;
			this.isLocked = true;
			this.pointer++;
			const data = JSON.parse(JSON.stringify(this.stack[this.pointer]));
			this.isLocked = false;
			this.state.emit("history-changed", {
				canUndo: this.canUndo(),
				canRedo: this.canRedo()
			});
			return data;
		}
		canUndo() {
			return this.pointer > 0;
		}
		canRedo() {
			return this.pointer < this.stack.length - 1;
		}
	};
	window.HistoryManager = HistoryManager$1;
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
	//#region platform/packages/page/resources/js/visual-builder/state.js
	window.VisualBuilderState = {
		shortcodes: [],
		activeShortcode: null,
		hasChanges: false,
		isSaving: false,
		deviceMode: "desktop",
		listeners: {},
		init(shortcodes) {
			this.shortcodes = shortcodes || [];
			this.activeShortcode = null;
			this.hasChanges = false;
			this.isSaving = false;
			this.deviceMode = "desktop";
			if (typeof HistoryManager !== "undefined") {
				this.history = new HistoryManager(this);
				this.history.init(this.shortcodes);
			}
		},
		getShortcode(id) {
			return this.shortcodes.find((sc) => sc.id === id);
		},
		getAllShortcodes() {
			return this.shortcodes;
		},
		getActiveShortcode() {
			if (!this.activeShortcode) return null;
			return this.getShortcode(this.activeShortcode);
		},
		updateShortcode(id, updates) {
			const index = this.shortcodes.findIndex((sc) => sc.id === id);
			if (index !== -1) {
				this.shortcodes[index] = _objectSpread2(_objectSpread2({}, this.shortcodes[index]), updates);
				this.markChanged();
				this.emit("shortcode-updated", this.shortcodes[index]);
				if (this.history) this.history.push(this.shortcodes);
			}
		},
		addShortcode(shortcode, position) {
			const newShortcode = _objectSpread2({ id: this.generateId() }, shortcode);
			if (typeof position === "number" && position >= 0 && position <= this.shortcodes.length) this.shortcodes.splice(position, 0, newShortcode);
			else this.shortcodes.push(newShortcode);
			this.markChanged();
			this.emit("shortcode-added", newShortcode);
			if (this.history) this.history.push(this.shortcodes);
			return newShortcode;
		},
		deleteShortcode(id) {
			const index = this.shortcodes.findIndex((sc) => sc.id === id);
			if (index !== -1) {
				const deleted = this.shortcodes.splice(index, 1)[0];
				this.markChanged();
				this.emit("shortcode-deleted", deleted);
				if (this.history) this.history.push(this.shortcodes);
				return true;
			}
			return false;
		},
		reorderShortcodes(newOrder) {
			this.shortcodes = newOrder;
			this.markChanged();
			this.emit("shortcodes-reordered", newOrder);
			if (this.history) this.history.push(this.shortcodes);
		},
		reorderShortcodesByIds(ids) {
			const currentMap = new Map(this.shortcodes.map((s) => [s.id, s]));
			const newOrder = [];
			ids.forEach((id) => {
				if (currentMap.has(id)) {
					newOrder.push(currentMap.get(id));
					currentMap.delete(id);
				}
			});
			currentMap.forEach((s) => newOrder.push(s));
			this.reorderShortcodes(newOrder);
		},
		setActive(id) {
			this.activeShortcode = id;
			this.emit("active-changed", id);
		},
		clearActive() {
			this.activeShortcode = null;
			this.emit("active-changed", null);
		},
		markChanged() {
			if (!this.hasChanges) {
				this.hasChanges = true;
				this.emit("has-changes", true);
			}
		},
		clearChanges() {
			this.hasChanges = false;
			this.emit("has-changes", false);
		},
		setDeviceMode(mode) {
			this.deviceMode = mode;
			this.emit("device-mode-changed", mode);
		},
		setSaving(isSaving) {
			this.isSaving = isSaving;
			this.emit("saving-changed", isSaving);
		},
		generateId() {
			return "sc_" + Date.now() + "_" + Math.random().toString(36).substr(2, 9);
		},
		on(event, callback) {
			if (!this.listeners[event]) this.listeners[event] = [];
			this.listeners[event].push(callback);
		},
		off(event, callback) {
			if (this.listeners[event]) this.listeners[event] = this.listeners[event].filter((cb) => cb !== callback);
		},
		emit(event, data) {
			if (this.listeners[event]) this.listeners[event].forEach((callback) => callback(data));
		},
		undo() {
			if (this.history && this.history.canUndo()) {
				const previousState = this.history.undo();
				if (previousState) {
					this.shortcodes = previousState;
					this.emit("state-restored", this.shortcodes);
				}
			}
		},
		redo() {
			if (this.history && this.history.canRedo()) {
				const nextState = this.history.redo();
				if (nextState) {
					this.shortcodes = nextState;
					this.emit("state-restored", this.shortcodes);
				}
			}
		}
	};
	//#endregion
	//#region platform/packages/page/resources/js/visual-builder/context-menu.js
	var ContextMenu$1 = class {
		constructor() {
			this.$menu = null;
			this.iframe = null;
			this.targetId = null;
		}
		init() {
			this.createMenu();
			this.iframe = document.getElementById("vb-preview-iframe");
			if (this.iframe) {
				this.iframe.addEventListener("load", () => {
					this.attachListener();
				});
				if (this.iframe.contentDocument && this.iframe.contentDocument.readyState === "complete") this.attachListener();
			}
			$(document).on("click", () => this.hide());
			this.$menu.on("click", ".dropdown-item", (e) => {
				e.preventDefault();
				const action = $(e.currentTarget).data("action");
				this.handleAction(action);
				this.hide();
			});
		}
		createMenu() {
			$("body").append(`
            <div id="vb-context-menu" class="dropdown-menu dropdown-menu-end position-fixed" style="z-index: 9999; display: none;">
                <a class="dropdown-item" href="#" data-action="edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><line x1="13.5" y1="6.5" x2="17.5" y2="10.5" /></svg>
                    Edit
                </a>
                <a class="dropdown-item" href="#" data-action="duplicate">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-copy me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="8" y="8" width="12" height="12" rx="2" /><path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" /></svg>
                    Duplicate
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-action="copy-style">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brush me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21v-4a4 4 0 1 1 4 4h-4" /><path d="M21 3a16 16 0 0 0 -12.8 10.2" /><path d="M21 3a16 16 0 0 1 -10.2 12.8" /><path d="M10.6 9a9 9 0 0 1 4.4 4.4" /></svg>
                    Copy Style
                </a>
                <a class="dropdown-item" href="#" data-action="paste-style" id="vb-ctx-paste-style" style="display:none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bucket me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 11v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" /><path d="M13 13l9 -9" /><path d="M13 13l-9 9" /><path d="M13 13l9 9" /><path d="M13 13l-9 -9" /></svg>
                    Paste Style
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-action="move-up">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-up me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="18" y1="11" x2="12" y2="5" /><line x1="6" y1="11" x2="12" y2="5" /></svg>
                    Move Up
                </a>
                <a class="dropdown-item" href="#" data-action="move-down">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-down me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="18" y1="13" x2="12" y2="19" /><line x1="6" y1="13" x2="12" y2="19" /></svg>
                    Move Down
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="#" data-action="delete">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                    Delete
                </a>
            </div>
        `);
			this.$menu = $("#vb-context-menu");
		}
		attachListener() {
			const doc = this.iframe.contentDocument;
			if (!doc) return;
			doc.body.addEventListener("contextmenu", (e) => {
				const target = e.target.closest("[data-shortcode-id]");
				if (target) {
					e.preventDefault();
					this.targetId = target.getAttribute("data-shortcode-id");
					const $pasteBtn = this.$menu.find("#vb-ctx-paste-style");
					if (window.vbClipboard && window.vbClipboard.type === "style") {
						const current = VisualBuilderState.getShortcode(this.targetId);
						if (current && current.name === window.vbClipboard.shortcodeName) $pasteBtn.show();
						else $pasteBtn.hide();
					} else $pasteBtn.hide();
					const rect = this.iframe.getBoundingClientRect();
					const x = rect.left + e.clientX;
					const y = rect.top + e.clientY;
					this.show(x, y);
				} else this.hide();
			});
			doc.body.addEventListener("click", (e) => {
				this.hide();
			});
		}
		show(x, y) {
			this.$menu.css({
				top: y + "px",
				left: x + "px",
				display: "block"
			});
			const menuWidth = this.$menu.outerWidth();
			const menuHeight = this.$menu.outerHeight();
			const viewportWidth = $(window).width();
			const viewportHeight = $(window).height();
			let finalX = x;
			if (x + menuWidth > viewportWidth) finalX = viewportWidth - menuWidth - 10;
			let finalY = y;
			if (y + menuHeight > viewportHeight) finalY = viewportHeight - menuHeight - 10;
			this.$menu.css({
				top: finalY + "px",
				left: finalX + "px"
			});
		}
		hide() {
			this.$menu.hide();
		}
		handleAction(action) {
			const id = this.targetId;
			if (!id) return;
			const shortcodes = VisualBuilderState.getAllShortcodes();
			const index = shortcodes.findIndex((s) => s.id === id);
			if (index === -1) return;
			switch (action) {
				case "edit":
					VisualBuilderState.setActive(id);
					if (typeof ShortcodeEditPanel !== "undefined") {}
					break;
				case "duplicate":
					const clone = JSON.parse(JSON.stringify(shortcodes[index]));
					clone.id = VisualBuilderState.generateId();
					VisualBuilderState.addShortcode(clone, index + 1);
					break;
				case "copy-style":
					window.vbClipboard = {
						type: "style",
						shortcodeName: shortcodes[index].name,
						attributes: JSON.parse(JSON.stringify(shortcodes[index].attributes || {}))
					};
					if (typeof Botble !== "undefined" && Botble.showSuccess) Botble.showSuccess("Style copied!");
					break;
				case "paste-style":
					if (window.vbClipboard && window.vbClipboard.type === "style") {
						const newAttributes = _objectSpread2(_objectSpread2({}, shortcodes[index].attributes), window.vbClipboard.attributes);
						VisualBuilderState.updateShortcode(id, { attributes: newAttributes });
						if (typeof PreviewIframe !== "undefined") PreviewIframe.updateShortcode(id, { attributes: newAttributes });
						if (typeof Botble !== "undefined" && Botble.showSuccess) Botble.showSuccess("Style pasted!");
					}
					break;
				case "delete":
					if (confirm(window.visualBuilderData.translations.confirmDelete)) VisualBuilderState.deleteShortcode(id);
					break;
				case "move-up":
					if (index > 0) {
						const newOrder = [...shortcodes];
						const temp = newOrder[index];
						newOrder[index] = newOrder[index - 1];
						newOrder[index - 1] = temp;
						VisualBuilderState.reorderShortcodes(newOrder);
					}
					break;
				case "move-down": if (index < shortcodes.length - 1) {
					const newOrder = [...shortcodes];
					const temp = newOrder[index];
					newOrder[index] = newOrder[index + 1];
					newOrder[index + 1] = temp;
					VisualBuilderState.reorderShortcodes(newOrder);
				}
			}
			if ([
				"duplicate",
				"delete",
				"move-up",
				"move-down"
			].includes(action)) {
				if (typeof PreviewIframe !== "undefined") setTimeout(() => PreviewIframe.reload(), 50);
				if (typeof ShortcodeList !== "undefined") ShortcodeList.render();
			}
		}
	};
	window.ContextMenu = new ContextMenu$1();
	//#endregion
	//#region platform/packages/page/resources/js/visual-builder/shortcode-list.js
	window.ShortcodeList = {
		$container: null,
		sortableInstance: null,
		init() {
			this.$container = $("#vb-shortcode-list");
			this.render();
			this.attachEvents();
			this.initDragDrop();
			VisualBuilderState.on("shortcode-added", () => this.render());
			VisualBuilderState.on("shortcode-deleted", () => this.render());
			VisualBuilderState.on("shortcode-updated", () => this.render());
			VisualBuilderState.on("shortcodes-reordered", () => this.render());
			VisualBuilderState.on("active-changed", (id) => this.updateActiveState(id));
		},
		render() {
			const shortcodes = VisualBuilderState.getAllShortcodes();
			const activeShortcode = VisualBuilderState.getActiveShortcode();
			const activeId = activeShortcode ? activeShortcode.id : null;
			if (shortcodes.length === 0) {
				this.$container.html(`
                <div class="text-center text-muted py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z"></path>
                        <path d="M10 16h-3a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h9a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <p>No shortcodes yet. Click "Add Shortcode" to get started.</p>
                </div>
            `);
				return;
			}
			const config = window.visualBuilderData || {};
			$.ajax({
				url: config.renderItemsUrl,
				method: "POST",
				data: {
					_token: config.csrfToken,
					shortcodes,
					activeId
				},
				success: (response) => {
					if (response.data && response.data.html) {
						this.$container.html(response.data.html);
						this.initDragDrop();
					}
				}
			});
		},
		renderItem(shortcode, index) {
			const activeClass = VisualBuilderState.activeShortcode === shortcode.id ? "active" : "";
			let previewText = "";
			if (shortcode.attributes) previewText = Object.entries(shortcode.attributes).slice(0, 2).map(([key, val]) => `${key}: ${val}`).join(", ") || shortcode.name;
			else previewText = shortcode.name;
			return `
            <div class="vb-shortcode-item ${activeClass}" data-id="${shortcode.id}">
                <div class="vb-item-drag-handle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M9 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                        <path d="M9 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                        <path d="M9 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                        <path d="M15 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                        <path d="M15 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                        <path d="M15 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                    </svg>
                </div>
                <div class="vb-item-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M7 8l-4 4l4 4"></path>
                        <path d="M17 8l4 4l-4 4"></path>
                        <path d="M14 4l-4 16"></path>
                    </svg>
                </div>
                <div class="vb-item-content">
                    <h4 class="vb-item-name">${this.escapeHtml(shortcode.name)}</h4>
                    <div class="vb-item-preview">${this.escapeHtml(previewText)}</div>
                </div>
                <div class="vb-item-actions">
                    <button type="button" class="btn btn-sm btn-ghost vb-delete-btn" data-id="${shortcode.id}" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 7l16 0"></path>
                            <path d="M10 11l0 6"></path>
                            <path d="M14 11l0 6"></path>
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
		},
		attachEvents() {
			const self = this;
			this.$container.on("click", ".vb-delete-btn", function(e) {
				e.preventDefault();
				e.stopPropagation();
				const id = $(this).data("id");
				self.deleteShortcode(id);
			});
			this.$container.on("click", ".vb-shortcode-item", function(e) {
				if ($(e.target).hasClass("vb-delete-btn") || $(e.target).closest(".vb-delete-btn").length) return;
				if ($(e.target).closest(".vb-item-drag-handle").length) return;
				const id = $(this).data("id");
				self.openEditPanel(id);
			});
			this.$container.on("mouseenter", ".vb-shortcode-item", function(e) {
				const id = $(this).data("id");
				if (typeof PreviewIframe !== "undefined" && PreviewIframe.isReady) PreviewIframe.highlightShortcode(id);
			});
			this.$container.on("mouseleave", ".vb-shortcode-item", function(e) {
				if (typeof PreviewIframe !== "undefined" && PreviewIframe.isReady) PreviewIframe.unhighlightShortcode();
			});
		},
		initDragDrop() {
			if (this.sortableInstance) this.sortableInstance.destroy();
			if (typeof Sortable !== "undefined" && this.$container[0]) this.sortableInstance = new Sortable(this.$container[0], {
				animation: 150,
				handle: ".vb-item-drag-handle",
				ghostClass: "sortable-ghost",
				dragClass: "sortable-drag",
				onEnd: (evt) => {
					this.handleReorder(evt);
				}
			});
		},
		handleReorder(evt) {
			const shortcodes = VisualBuilderState.getAllShortcodes();
			const movedItem = shortcodes[evt.oldIndex];
			const newOrder = [...shortcodes];
			newOrder.splice(evt.oldIndex, 1);
			newOrder.splice(evt.newIndex, 0, movedItem);
			VisualBuilderState.reorderShortcodes(newOrder);
			if (typeof PreviewIframe !== "undefined") PreviewIframe.reload();
		},
		openEditPanel(id) {
			VisualBuilderState.setActive(id);
			$("#vb-edit-panel").removeClass("d-none").show();
			if (typeof ShortcodeEditPanel !== "undefined") ShortcodeEditPanel.render(id);
			if (typeof PreviewIframe !== "undefined") PreviewIframe.highlightShortcode(id);
		},
		deleteShortcode(id) {
			var _config$translations;
			if (!id) return;
			const confirmMsg = ((_config$translations = (window.visualBuilderData || {}).translations) === null || _config$translations === void 0 ? void 0 : _config$translations.confirmDelete) || "Are you sure you want to delete this shortcode?";
			if (confirm(confirmMsg)) {
				VisualBuilderState.deleteShortcode(id);
				if (typeof PreviewIframe !== "undefined") PreviewIframe.reload();
			}
		},
		updateActiveState(activeId) {
			this.$container.find(".vb-shortcode-item").removeClass("active");
			if (activeId) this.$container.find(`.vb-shortcode-item[data-id="${activeId}"]`).addClass("active");
		},
		escapeHtml(text) {
			const div = document.createElement("div");
			div.textContent = text;
			return div.innerHTML;
		}
	};
	//#endregion
	//#region platform/packages/page/resources/js/visual-builder/edit-panel.js
	window.ShortcodeEditPanel = {
		$container: null,
		$content: null,
		$title: null,
		currentShortcode: null,
		updateTimeout: null,
		currentRequest: null,
		init() {
			this.$container = $("#vb-edit-panel");
			this.$content = $("#vb-panel-content");
			this.$title = $("#vb-panel-title");
		},
		render(shortcodeId) {
			const shortcode = VisualBuilderState.getShortcode(shortcodeId);
			if (!shortcode) {
				this.$content.html("<p class=\"text-danger\">Shortcode not found. ID mismatch between preview and state.</p>");
				return;
			}
			this.currentShortcode = shortcode;
			this.$title.text(`Edit: ${shortcode.name}`);
			this.loadShortcodeForm(shortcode);
		},
		loadShortcodeForm(shortcode) {
			if (this.currentRequest) {
				this.currentRequest.abort();
				this.currentRequest = null;
			}
			if (!this.$content || this.$content.length === 0) this.$content = $("#vb-panel-content");
			if (this.$content.length === 0) return;
			const config = window.visualBuilderData || {};
			const schema = (config.availableShortcodes || []).find((sc) => sc.key === shortcode.name);
			if (!schema || !schema.url) {
				this.$content.html("<p class=\"text-danger\">Shortcode configuration not found.</p>");
				return;
			}
			const url = schema.url;
			if (typeof Botble !== "undefined" && Botble.showLoading) Botble.showLoading(this.$content);
			const formData = {};
			if (shortcode.attributes) Object.assign(formData, shortcode.attributes);
			if (shortcode.content) formData.content = shortcode.content;
			this.currentRequest = $.ajax({
				url,
				method: "POST",
				dataType: "json",
				data: _objectSpread2({ _token: config.csrfToken }, formData),
				timeout: 3e4,
				success: (response) => {
					this.currentRequest = null;
					if (typeof Botble !== "undefined") Botble.hideLoading(this.$content);
					if (!this.$content || this.$content.length === 0) this.$content = $("#vb-panel-content");
					if (response && response.data) {
						this.$content.html("<form id=\"vb-edit-form\">" + response.data + "</form>");
						const $container = this.$content;
						setTimeout(() => {
							if (typeof Botble !== "undefined") {
								Botble.initResources();
								Botble.initMediaIntegrate();
								Botble.initFieldCollapse();
								if (typeof Botble.initCoreIcon === "function") Botble.initCoreIcon();
								if (typeof Botble.initColorPicker === "function") Botble.initColorPicker();
							}
							document.dispatchEvent(new CustomEvent("visual-builder-form-loaded", { detail: { container: $container } }));
						}, 100);
						this.attachEvents();
					} else this.$content.html("<p class=\"text-danger\">Invalid response format.</p>");
				},
				error: (xhr, status, error) => {
					this.currentRequest = null;
					if (status === "abort") return;
					if (typeof Botble !== "undefined") Botble.hideLoading(this.$content);
					if (!this.$content || this.$content.length === 0) this.$content = $("#vb-panel-content");
					let errorMessage = "Failed to load shortcode form.";
					if (xhr.responseJSON && xhr.responseJSON.message) errorMessage = xhr.responseJSON.message;
					else if (xhr.responseText) errorMessage += " " + xhr.status + ": " + error;
					else if (status === "timeout") errorMessage = "Request timeout. Please try again.";
					this.$content.html("<p class=\"text-danger\">" + errorMessage + "</p>");
				}
			});
		},
		attachEvents() {
			const self = this;
			this.$content.off("input change").on("input change", "input, textarea, select", function() {
				clearTimeout(self.updateTimeout);
				self.updateTimeout = setTimeout(() => {
					self.handleFieldChange();
				}, 300);
			});
		},
		handleFieldChange() {
			if (!this.currentShortcode) return;
			const formData = this.getFormData();
			VisualBuilderState.updateShortcode(this.currentShortcode.id, {
				attributes: formData.attributes,
				content: formData.content
			});
			if (typeof PreviewIframe !== "undefined") PreviewIframe.updateShortcode(this.currentShortcode.id, formData);
		},
		getFormData() {
			const $form = $("#vb-edit-form");
			const attributes = {};
			let content = "";
			const multiCheckboxValues = {};
			$form.find("input[type=\"checkbox\"]").each(function() {
				const $field = $(this);
				const name = $field.attr("name");
				if (!name) return;
				const cleanName = name.replace("[]", "");
				if ($form.find(`input[type="checkbox"][name="${name}"]`).length > 1) {
					if (!multiCheckboxValues[cleanName]) multiCheckboxValues[cleanName] = [];
					if ($field.is(":checked")) multiCheckboxValues[cleanName].push($field.val());
				}
			});
			for (const [key, values] of Object.entries(multiCheckboxValues)) attributes[key] = values.join(",");
			const processedRadioNames = {};
			$form.find("input, textarea, select").each(function() {
				const $field = $(this);
				const name = $field.attr("name");
				if ($field.data("shortcode-attribute") === "content") content = $field.val();
				else if (name) {
					const cleanName = name.replace("[]", "");
					if (multiCheckboxValues.hasOwnProperty(cleanName)) return;
					let value;
					if ($field.attr("type") === "checkbox") value = $field.is(":checked") ? "yes" : "no";
					else if ($field.attr("type") === "radio") {
						if (processedRadioNames[cleanName]) return;
						processedRadioNames[cleanName] = true;
						const $checkedRadio = $form.find(`input[type="radio"][name="${name}"]:checked`);
						value = $checkedRadio.length ? $checkedRadio.val() : null;
					} else value = $field.val();
					if (Array.isArray(value)) value = value.join(",");
					if (value !== void 0 && value !== null) attributes[cleanName] = value;
				}
			});
			return {
				attributes,
				content
			};
		}
	};
	//#endregion
	//#region platform/packages/page/resources/js/visual-builder/add-modal.js
	var AddShortcodeModal$1 = {
		$listModal: null,
		$formModal: null,
		listModalInstance: null,
		formModalInstance: null,
		selectedShortcode: null,
		init() {
			this.$listModal = $("#vb-shortcode-list-modal");
			this.$formModal = $("#vb-shortcode-modal");
			if (typeof bootstrap !== "undefined" && bootstrap.Modal) {
				this.listModalInstance = new bootstrap.Modal(this.$listModal[0]);
				this.formModalInstance = new bootstrap.Modal(this.$formModal[0]);
			}
			this.attachEvents();
		},
		attachEvents() {
			const self = this;
			$(document).on("change", "[data-bb-toggle=\"shortcode-item-radio\"]", function() {
				$("[data-bb-toggle=\"vb-shortcode-use\"]").prop("disabled", false).removeClass("disabled");
			});
			$(document).on("dblclick", "[data-bb-toggle=\"vb-shortcode-select\"]", function(event) {
				const $currentTarget = $(event.currentTarget);
				self.triggerShortcode($currentTarget);
			});
			$("[data-bb-toggle=\"vb-shortcode-use\"]").on("click", function() {
				const $shortcodeSelected = self.$listModal.find(".shortcode-item-input:checked").closest(".shortcode-item-wrapper");
				self.triggerShortcode($shortcodeSelected);
				$("[data-bb-toggle=\"shortcode-item-radio\"]").prop("checked", false);
				$("[data-bb-toggle=\"vb-shortcode-use\"]").prop("disabled", true).addClass("disabled");
			});
			$(document).on("click", "[data-bb-toggle=\"vb-shortcode-button-use\"]", function(event) {
				event.stopPropagation();
				const $shortcodeSelected = $(event.currentTarget).closest(".shortcode-item-wrapper");
				self.triggerShortcode($shortcodeSelected);
			});
			$("[data-bb-toggle=\"vb-shortcode-add-single\"]").on("click", function(event) {
				event.preventDefault();
				self.insertShortcode();
			});
			this.$formModal.on("show.bs.modal", function() {
				if (self.listModalInstance) self.listModalInstance.hide();
				$("[data-bb-toggle=\"shortcode-item-radio\"]").prop("checked", false);
				$("[data-bb-toggle=\"vb-shortcode-use\"]").prop("disabled", true).addClass("disabled");
			});
			$("[data-bb-toggle=\"shortcode-clear-search\"]").on("click", function() {
				self.$listModal.find("input[name=\"search\"]").val("");
				self.filterShortcodes("");
			});
			self.$listModal.find("input[name=\"search\"]").on("input", function() {
				self.filterShortcodes($(this).val());
			});
		},
		show() {
			if (this.listModalInstance) this.listModalInstance.show();
			else this.$listModal.modal("show");
		},
		triggerShortcode($element) {
			this.loadShortcodeConfig({
				href: $element.data("url"),
				key: $element.data("key"),
				name: $element.data("name"),
				description: $element.data("description")
			});
		},
		loadShortcodeConfig(params = {}) {
			const { href, key, name, description = null, data = {}, update = false } = params;
			this.selectedShortcode = {
				key,
				name,
				description
			};
			$(".shortcode-admin-config").html("");
			const $addButton = $(".shortcode-modal button[data-bb-toggle=\"vb-shortcode-add-single\"]");
			$addButton.text($addButton.data(update ? "update-text" : "add-text"));
			$(".shortcode-modal .modal-title").text(name);
			if (this.formModalInstance) this.formModalInstance.show();
			else this.$formModal.modal("show");
			const $modalContent = this.$formModal.find(".modal-content");
			if (typeof Botble !== "undefined" && Botble.showLoading) Botble.showLoading($modalContent);
			$.ajax({
				url: href,
				method: "POST",
				dataType: "json",
				data: _objectSpread2({ _token: (window.visualBuilderData || {}).csrfToken }, data),
				success: (response) => {
					$(".shortcode-data-form").trigger("reset");
					$(".shortcode-input-key").val(key);
					$(".shortcode-admin-config").html(response.data);
					if (typeof Botble !== "undefined") {
						Botble.hideLoading($modalContent);
						Botble.initResources();
						Botble.initMediaIntegrate();
						Botble.initFieldCollapse();
					}
				},
				error: () => {
					if (typeof Botble !== "undefined") {
						Botble.hideLoading($modalContent);
						Botble.showError("Failed to load shortcode configuration");
					}
				}
			});
		},
		insertShortcode() {
			if (!this.selectedShortcode) return;
			const $form = $(".shortcode-modal").find(".shortcode-data-form");
			const formData = $form.serializeObject();
			const attributes = {};
			let content = "";
			$.each(formData, function(name, value) {
				if ($form.find("*[name=\"" + name + "\"]").data("shortcode-attribute") === "content") content = value;
				else if (value) {
					name = name.replace("[]", "");
					attributes[name] = value;
				}
			});
			const newShortcode = {
				name: this.selectedShortcode.key,
				attributes,
				content: content || "",
				isSelfClosing: !content
			};
			VisualBuilderState.addShortcode(newShortcode);
			if (typeof PreviewIframe !== "undefined") PreviewIframe.reload();
			if (this.formModalInstance) this.formModalInstance.hide();
			else this.$formModal.modal("hide");
		},
		filterShortcodes(searchTerm) {
			const term = searchTerm.toLowerCase();
			const $items = this.$listModal.find(".shortcode-item-wrapper");
			let visibleCount = 0;
			$items.each(function() {
				const $item = $(this);
				const name = $item.data("name").toLowerCase();
				const description = ($item.data("description") || "").toLowerCase();
				if (name.includes(term) || description.includes(term)) {
					$item.closest(".col-xl-3").show();
					visibleCount++;
				} else $item.closest(".col-xl-3").hide();
			});
			if (visibleCount === 0) {
				this.$listModal.find(".shortcode-empty").show();
				this.$listModal.find(".shortcode-list").hide();
			} else {
				this.$listModal.find(".shortcode-empty").hide();
				this.$listModal.find(".shortcode-list").show();
			}
		}
	};
	$.fn.serializeObject = function() {
		const o = {};
		const a = this.serializeArray();
		$.each(a, function() {
			if (o[this.name]) {
				if (!o[this.name].push) o[this.name] = [o[this.name]];
				o[this.name].push(this.value || "");
			} else o[this.name] = this.value || "";
		});
		return o;
	};
	window.AddShortcodeModal = AddShortcodeModal$1;
	//#endregion
	//#region platform/packages/page/resources/js/visual-builder/preview-iframe.js
	window.PreviewIframe = {
		$iframe: null,
		$container: null,
		$loading: null,
		$error: null,
		$preview: null,
		iframe: null,
		isReady: false,
		deviceMode: "desktop",
		init() {
			this.$iframe = $("#vb-preview-iframe");
			this.$container = $("#vb-preview-frame-container");
			this.$loading = $("#vb-preview-loading");
			this.$error = $("#vb-preview-error");
			this.$preview = $("#vb-preview");
			this.iframe = this.$iframe[0];
			this.$iframe.on("load", () => {
				this.handleLoad();
			});
			this.$iframe.on("error", () => {
				this.showError("Failed to load preview");
			});
			if (typeof PostMessageUtil !== "undefined") PostMessageUtil.listenToIframe((type, payload) => {
				this.handleMessage(type, payload);
			});
			this.setDeviceMode("desktop");
		},
		handleLoad() {
			this.isReady = true;
			this.hideLoading();
			this.$preview.addClass("loaded");
			setTimeout(() => {
				this.syncShortcodeIds();
				this.injectEditIcons();
				this.enableInlineEditing();
				this.enableSorting();
				this.attachLazyLoadListener();
			}, 1e3);
		},
		syncShortcodeIds() {
			if (!this.iframe || !this.iframe.contentDocument) return;
			const iframeDoc = this.iframe.contentDocument;
			const stateShortcodes = VisualBuilderState.getAllShortcodes();
			if (!stateShortcodes.length) return;
			const allElements = iframeDoc.querySelectorAll("[data-shortcode-id]:not(.vb-shortcode-toolbar)");
			if (!allElements.length) return;
			const uniqueElements = [];
			const seenIds = /* @__PURE__ */ new Set();
			allElements.forEach((el) => {
				const id = el.getAttribute("data-shortcode-id");
				if (!seenIds.has(id)) {
					seenIds.add(id);
					uniqueElements.push(el);
				}
			});
			const count = Math.min(uniqueElements.length, stateShortcodes.length);
			for (let i = 0; i < count; i++) {
				const stateId = stateShortcodes[i].id;
				if (stateId !== uniqueElements[i].getAttribute("data-shortcode-id")) uniqueElements[i].setAttribute("data-shortcode-id", stateId);
			}
		},
		attachLazyLoadListener() {
			if (!this.iframe || !this.iframe.contentDocument) return;
			const iframeDoc = this.iframe.contentDocument;
			iframeDoc.addEventListener("shortcode.loaded", (e) => {
				setTimeout(() => {
					if (typeof EditIconInjector !== "undefined") EditIconInjector.inject(iframeDoc);
				}, 100);
			});
		},
		enableInlineEditing() {
			if (!this.iframe || !this.iframe.contentDocument) return;
			const doc = this.iframe.contentDocument;
			if (this._inlineCtx) {
				doc.body.removeEventListener("click", this._inlineCtx);
				doc.body.removeEventListener("input", this._inlineInputCtx);
			}
			this._inlineCtx = (e) => {
				const $editForm = $("#vb-edit-form");
				if (!$editForm.length) return;
				const target = e.target;
				const normalize = (str) => {
					return str ? str.replace(/[\s\u00A0]+/g, " ").trim().replace(/['""]/g, "'").replace(/[""]/g, "\"") : "";
				};
				if (target.tagName === "IMG") {
					const src = target.getAttribute("src");
					if (!src) return;
					const relativeSrc = src.replace(window.location.origin, "");
					const checkMatch = (val) => {
						if (!val) return false;
						const decodedVal = decodeURIComponent(val);
						const decodedSrc = decodeURIComponent(src);
						const decodedRel = decodeURIComponent(relativeSrc);
						return val.includes(relativeSrc) || src.includes(val) || decodedVal === decodedRel || decodedSrc.endsWith(decodedVal);
					};
					let $match = $editForm.find(`input[type="hidden"], input[type="text"]`).filter((i, el) => {
						return checkMatch($(el).val());
					}).first();
					if ($match.length) {
						e.preventDefault();
						e.stopPropagation();
						const $trigger = $match.closest(".image-box").find(".btn_gallery");
						if ($trigger.length) $trigger.trigger("click");
						return;
					}
				}
				const isSimpleElement = (el) => {
					if (el.children.length === 0) return true;
					for (let child of el.childNodes) if (child.nodeType !== 3 && child.tagName !== "BR") return false;
					return true;
				};
				if (isSimpleElement(target) && target.textContent.trim().length > 0) {
					const text = normalize(target.innerText || target.textContent);
					const $match = $editForm.find("input[type=\"text\"], input[type=\"number\"], textarea").filter((i, el) => {
						let val = $(el).val();
						if (val && val.includes("<")) val = val.replace(/<[^>]+>/g, " ");
						return normalize(val) === text;
					}).first();
					if ($match.length) {
						e.preventDefault();
						e.stopPropagation();
						target.contentEditable = true;
						target.focus();
						$match.addClass("vb-bound-input");
						$match.css({
							"border-color": "#206bc4",
							"box-shadow": "0 0 0 2px rgba(32, 107, 196, 0.25)",
							"transition": "all 0.2s ease"
						});
						if (typeof $match[0].scrollIntoView === "function") $match[0].scrollIntoView({
							behavior: "smooth",
							block: "center"
						});
						const handleInput = () => {
							$match.val(target.innerText);
						};
						const handleBlur = () => {
							target.contentEditable = false;
							$match.removeClass("vb-bound-input");
							$match.css({
								"border-color": "",
								"box-shadow": "",
								"transition": ""
							});
							target.removeEventListener("input", handleInput);
							target.removeEventListener("blur", handleBlur);
							target.removeEventListener("keydown", handleKeydown);
							$match.trigger("change");
						};
						const handleKeydown = (k) => {
							if (k.key === "Enter") {
								k.preventDefault();
								target.blur();
							}
						};
						target.addEventListener("input", handleInput);
						target.addEventListener("blur", handleBlur);
						target.addEventListener("keydown", handleKeydown);
					}
				}
			};
			doc.body.addEventListener("click", this._inlineCtx);
		},
		enableSorting() {
			if (!this.iframe || !this.iframe.contentDocument) return;
			const doc = this.iframe.contentDocument;
			if (!doc.getElementById("vb-sortable-script")) {
				const script = doc.createElement("script");
				script.id = "vb-sortable-script";
				script.src = "/vendor/core/core/base/libraries/sortable/sortable.min.js";
				script.onload = () => this.initSortable();
				doc.head.appendChild(script);
			} else this.initSortable();
		},
		initSortable() {
			const doc = this.iframe.contentDocument;
			if (!doc || !doc.defaultView.Sortable) return;
			const containers = /* @__PURE__ */ new Set();
			doc.querySelectorAll("[data-shortcode-id]").forEach((el) => {
				if (el.parentElement) containers.add(el.parentElement);
			});
			containers.forEach((container) => {
				if (container._sortable) return;
				container._sortable = new doc.defaultView.Sortable(container, {
					animation: 150,
					handle: "[data-shortcode-id]",
					filter: "[contenteditable]",
					preventOnFilter: false,
					onEnd: (evt) => {
						this.handleSort(evt);
					}
				});
			});
		},
		handleSort(evt) {
			evt.item;
			if (evt.newIndex === evt.oldIndex) return;
			const doc = this.iframe.contentDocument;
			const newOrderIds = [];
			doc.querySelectorAll("[data-shortcode-id]").forEach((el) => {
				newOrderIds.push(el.getAttribute("data-shortcode-id"));
			});
			VisualBuilderState.reorderShortcodesByIds(newOrderIds);
		},
		handleMessage(type, payload) {
			switch (type) {
				case "ready":
					this.handleLoad();
					break;
				case "edit-shortcode":
					if (payload && payload.id) this.handleEditRequest(payload.id);
					break;
				case "delete-shortcode": if (payload && payload.id) this.handleDeleteRequest(payload.id);
			}
		},
		handleEditRequest(shortcodeId) {
			if (typeof ShortcodeList !== "undefined") ShortcodeList.openEditPanel(shortcodeId);
		},
		handleDeleteRequest(shortcodeId) {
			if (typeof ShortcodeList !== "undefined") ShortcodeList.deleteShortcode(shortcodeId);
		},
		injectEditIcons() {
			if (!this.iframe || !this.iframe.contentDocument) return;
			try {
				const iframeDoc = this.iframe.contentDocument || this.iframe.contentWindow.document;
				if (typeof EditIconInjector !== "undefined") EditIconInjector.inject(iframeDoc);
				this.injectEditIconStyles(iframeDoc);
			} catch (e) {}
		},
		injectEditIconStyles(iframeDoc) {
			if (iframeDoc.getElementById("vb-edit-icon-styles")) return;
			const style = iframeDoc.createElement("style");
			style.id = "vb-edit-icon-styles";
			style.textContent = `
            .vb-shortcode-block {
                position: relative !important;
                min-height: 20px;
                transition: all 0.2s ease;
            }

            .vb-shortcode-toolbar {
                position: absolute;
                top: 10px;
                right: 10px;
                z-index: 9999;
                display: flex;
                gap: 4px;
                opacity: 0;
                transition: all 0.2s ease;
                pointer-events: none;
            }

            .vb-shortcode-block:hover .vb-shortcode-toolbar,
            .vb-shortcode-block.vb-active .vb-shortcode-toolbar {
                opacity: 1;
                pointer-events: all;
            }

            .vb-toolbar-btn {
                width: 32px;
                height: 32px;
                border: none;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                font-size: 16px;
            }

            .vb-toolbar-btn i,
            .vb-toolbar-btn svg {
                color: #fff;
                stroke: #fff;
                font-size: 16px;
                line-height: 1;
            }

            .vb-toolbar-btn.vb-edit-btn {
                background: #206bc4;
            }

            .vb-toolbar-btn.vb-edit-btn:hover {
                background: #1a5ba7;
                transform: scale(1.05);
                box-shadow: 0 4px 12px rgba(32, 107, 196, 0.3);
            }

            .vb-toolbar-btn.vb-delete-btn {
                background: #d63939;
            }

            .vb-toolbar-btn.vb-delete-btn:hover {
                background: #c72e2e;
                transform: scale(1.05);
                box-shadow: 0 4px 12px rgba(214, 57, 57, 0.3);
            }

            .vb-shortcode-block:hover {
                outline: 2px dashed #206bc4;
                outline-offset: 2px;
            }

            .vb-shortcode-block.vb-active {
                outline: 2px solid #206bc4 !important;
                outline-offset: 2px;
                background: rgba(32, 107, 196, 0.05);
            }

            .vb-shortcode-toolbar * {
                box-sizing: border-box;
            }
        `;
			iframeDoc.head.appendChild(style);
		},
		reload() {
			this.isReady = false;
			this.showLoading();
			this.$preview.removeClass("loaded error");
			const shortcodes = VisualBuilderState.getAllShortcodes();
			const config = window.visualBuilderData || {};
			const previewUrl = config.previewUrl + (config.previewUrl.includes("?") ? "&" : "?") + "visual_builder=1";
			const $form = $("<form>", {
				method: "POST",
				action: previewUrl,
				target: "vb-preview-iframe",
				style: "display: none;"
			});
			$form.append($("<input>", {
				type: "hidden",
				name: "_token",
				value: config.csrfToken
			}));
			$form.append($("<input>", {
				type: "hidden",
				name: "shortcodes",
				value: JSON.stringify(shortcodes)
			}));
			$form.appendTo("body").submit().remove();
		},
		setDeviceMode(mode) {
			this.deviceMode = mode;
			this.$container.removeClass("device-desktop device-tablet device-mobile");
			this.$container.addClass("device-" + mode);
		},
		highlightShortcode(shortcodeId) {
			if (!this.iframe || !this.iframe.contentDocument) return;
			try {
				const iframeDoc = this.iframe.contentDocument || this.iframe.contentWindow.document;
				if (typeof EditIconInjector !== "undefined") EditIconInjector.highlight(iframeDoc, shortcodeId);
			} catch (e) {}
		},
		unhighlightShortcode() {
			if (!this.iframe || !this.iframe.contentDocument) return;
			try {
				const iframeDoc = this.iframe.contentDocument || this.iframe.contentWindow.document;
				if (typeof EditIconInjector !== "undefined") EditIconInjector.unhighlight(iframeDoc);
			} catch (e) {}
		},
		updateShortcode(shortcodeId, data) {
			const shortcode = VisualBuilderState.getShortcode(shortcodeId);
			if (!shortcode) {
				console.warn("[VB] Shortcode not found for update:", shortcodeId);
				return;
			}
			const config = window.visualBuilderData || {};
			const attributes = data && data.attributes ? data.attributes : shortcode.attributes;
			const refinedAttributes = {};
			if (attributes) Object.keys(attributes).forEach((key) => {
				let val = attributes[key];
				if (Array.isArray(val)) val = val.join(",");
				refinedAttributes[key] = val;
			});
			let refLang = config.refLang || null;
			if (!refLang) refLang = new URLSearchParams(window.location.search).get("ref_lang");
			const requestData = {
				_token: config.csrfToken,
				name: shortcode.name,
				attributes: refinedAttributes
			};
			if (refLang) requestData.ref_lang = refLang;
			$.ajax({
				url: "/ajax/render-ui-blocks",
				method: "POST",
				data: requestData,
				success: (response) => {
					if (response && response.data) this.replaceShortcodeContent(shortcodeId, response.data);
				},
				error: (xhr, status, error) => {
					console.error("[VB] Failed to render shortcode:", error, xhr.responseText);
					this.reload();
				}
			});
		},
		replaceShortcodeContent(shortcodeId, newHtml) {
			if (!this.iframe || !this.iframe.contentDocument) return;
			try {
				const iframeDoc = this.iframe.contentDocument || this.iframe.contentWindow.document;
				const element = iframeDoc.querySelector(`[data-shortcode-id="${shortcodeId}"]`);
				if (element) {
					const tempDiv = iframeDoc.createElement("div");
					tempDiv.innerHTML = newHtml;
					const newElement = tempDiv.firstElementChild;
					if (newElement) {
						newElement.setAttribute("data-shortcode-id", shortcodeId);
						newElement.setAttribute("data-shortcode-name", element.getAttribute("data-shortcode-name") || "");
						try {
							element.parentNode.replaceChild(newElement, element);
						} catch (e) {
							console.error("[VB] Error replacing child:", e);
						}
						if (typeof EditIconInjector !== "undefined") EditIconInjector.injectIcon(newElement, iframeDoc);
						if (VisualBuilderState.activeShortcode === shortcodeId) newElement.classList.add("vb-active");
						if (iframeDoc.defaultView && typeof iframeDoc.defaultView.reloadInfiniaTheme === "function") setTimeout(() => {
							try {
								iframeDoc.defaultView.reloadInfiniaTheme();
							} catch (err) {
								console.error("[VB] Error executing reloadInfiniaTheme:", err);
							}
						}, 100);
					} else element.innerHTML = newHtml;
				} else {
					console.warn("[VB] Shortcode element not found in iframe, reloading");
					this.reload();
				}
			} catch (e) {
				console.error("[VB] Error replacing shortcode content:", e);
				this.reload();
			}
		},
		showLoading() {
			this.$loading.show();
			this.$error.hide();
			this.$container.hide();
		},
		hideLoading() {
			this.$loading.hide();
			this.$container.show();
		},
		showError(message) {
			this.isReady = false;
			this.$loading.hide();
			this.$container.hide();
			this.$error.show();
			this.$preview.addClass("error");
			if (message) $("#vb-error-message").text(message);
		}
	};
	//#endregion
	//#region platform/packages/page/resources/js/visual-builder/app.js
	(function($) {
		"use strict";
		window.VisualBuilderApp = {
			config: {},
			modules: {},
			draftCheckPerformed: false,
			init() {
				this.config = window.visualBuilderData || {};
				VisualBuilderState.init(this.config.shortcodes);
				this.initHeader();
				this.initUndoRedo();
				this.initSidebar();
				this.initPreview();
				this.initAutoSave();
				this.initUnsavedWarning();
				if (typeof ContextMenu !== "undefined") ContextMenu.init();
			},
			initHeader() {
				const self = this;
				$("#vb-save-btn").on("click", function() {
					self.save();
				});
				$("#vb-close-btn").on("click", function(e) {
					if (VisualBuilderState.hasChanges) {
						if (confirm(self.config.translations.unsavedChanges)) VisualBuilderState.clearChanges();
						else {
							e.preventDefault();
							return false;
						}
					}
				});
				$("#vb-device-modes input[type=\"radio\"]").on("change", function() {
					if (this.checked) {
						const device = $(this).data("device");
						VisualBuilderState.setDeviceMode(device);
					}
				});
				VisualBuilderState.on("has-changes", (hasChanges) => {
					if (hasChanges) $("#vb-unsaved-indicator").show();
					else $("#vb-unsaved-indicator").hide();
				});
				VisualBuilderState.on("saving-changed", (isSaving) => {
					const $btn = $("#vb-save-btn");
					const saveIcon = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"icon icon-tabler icon-tabler-device-floppy\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2\" /><path d=\"M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0\" /><path d=\"M14 4l0 4l-6 0l0 -4\" /></svg>";
					if (isSaving) {
						$btn.prop("disabled", true);
						$btn.html("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"icon icon-tabler icon-tabler-loader animate-spin\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12 6l0 -3\" /><path d=\"M16.25 7.75l2.15 -2.15\" /><path d=\"M18 12l3 0\" /><path d=\"M16.25 16.25l2.15 2.15\" /><path d=\"M12 18l0 3\" /><path d=\"M7.75 16.25l-2.15 2.15\" /><path d=\"M6 12l-3 0\" /><path d=\"M7.75 7.75l-2.15 -2.15\" /></svg> <span class=\"d-none d-sm-inline\">" + self.config.translations.saving + "</span>");
					} else {
						$btn.prop("disabled", false);
						$btn.html(saveIcon + " <span class=\"d-none d-sm-inline\">" + self.config.translations.saved + "</span>");
						setTimeout(() => {
							if (!VisualBuilderState.isSaving) $btn.html(saveIcon + " <span class=\"d-none d-sm-inline\">" + (self.config.translations.save || "Save") + "</span>");
						}, 2e3);
					}
				});
			},
			initUndoRedo() {
				$("#vb-undo-btn").on("click", () => {
					VisualBuilderState.undo();
				});
				$("#vb-redo-btn").on("click", () => {
					VisualBuilderState.redo();
				});
				VisualBuilderState.on("history-changed", (data) => {
					$("#vb-undo-btn").prop("disabled", !data.canUndo);
					$("#vb-redo-btn").prop("disabled", !data.canRedo);
				});
				VisualBuilderState.on("state-restored", () => {
					if (typeof ShortcodeList !== "undefined") ShortcodeList.render();
					if (typeof PreviewIframe !== "undefined") PreviewIframe.reload();
					VisualBuilderState.clearActive();
					$("#vb-edit-panel").addClass("d-none").hide();
				});
				$(document).on("keydown", (e) => {
					if (e.metaKey || e.ctrlKey) {
						if (e.key === "z") {
							e.preventDefault();
							if (e.shiftKey) VisualBuilderState.redo();
							else VisualBuilderState.undo();
						} else if (e.key === "y") {
							e.preventDefault();
							VisualBuilderState.redo();
						}
					}
				});
			},
			initSidebar() {
				if (typeof ShortcodeList !== "undefined") ShortcodeList.init();
				if (typeof ShortcodeEditPanel !== "undefined") ShortcodeEditPanel.init();
				if (typeof AddShortcodeModal !== "undefined") AddShortcodeModal.init();
				$("#vb-back-btn, #vb-close-panel-btn").on("click", function() {
					$("#vb-edit-panel").addClass("d-none").hide();
					VisualBuilderState.clearActive();
				});
				$("#vb-add-shortcode-btn").on("click", function() {
					if (typeof AddShortcodeModal !== "undefined") AddShortcodeModal.show();
				});
				VisualBuilderState.on("active-changed", (id) => {
					if (id) {
						$("#vb-edit-panel").removeClass("d-none").show();
						if (typeof ShortcodeEditPanel !== "undefined") ShortcodeEditPanel.render(id);
					} else $("#vb-edit-panel").addClass("d-none").hide();
				});
			},
			initPreview() {
				if (typeof PreviewIframe !== "undefined") {
					PreviewIframe.init();
					PreviewIframe.reload();
				}
				$("#vb-reload-preview-btn").on("click", function() {
					if (typeof PreviewIframe !== "undefined") PreviewIframe.reload();
				});
				VisualBuilderState.on("device-mode-changed", (mode) => {
					if (typeof PreviewIframe !== "undefined") PreviewIframe.setDeviceMode(mode);
				});
			},
			save() {
				const self = this;
				VisualBuilderState.setSaving(true);
				let content = "";
				if (typeof ShortcodeSerializer !== "undefined") content = ShortcodeSerializer.serialize(VisualBuilderState.getAllShortcodes());
				$.ajax({
					url: this.config.saveUrl,
					method: "POST",
					data: {
						_token: this.config.csrfToken,
						content
					},
					success: function(response) {
						VisualBuilderState.setSaving(false);
						VisualBuilderState.clearChanges();
						localStorage.removeItem("vb_draft_" + self.config.pageId);
						if (typeof Botble !== "undefined" && Botble.showSuccess) Botble.showSuccess(self.config.translations.saved);
						else alert(self.config.translations.saved);
					},
					error: function(xhr) {
						VisualBuilderState.setSaving(false);
						let errorMsg = self.config.translations.error;
						if (xhr.responseJSON && xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;
						if (typeof Botble !== "undefined" && Botble.showError) Botble.showError(errorMsg);
						else alert(errorMsg);
					}
				});
			},
			initAutoSave() {
				const self = this;
				let autoSaveInterval = null;
				VisualBuilderState.on("has-changes", (hasChanges) => {
					if (hasChanges && !autoSaveInterval) autoSaveInterval = setInterval(() => {
						self.autoSave();
					}, 3e4);
					else if (!hasChanges && autoSaveInterval) {
						clearInterval(autoSaveInterval);
						autoSaveInterval = null;
					}
				});
				this.checkDraft();
			},
			autoSave() {
				const draft = {
					timestamp: Date.now(),
					shortcodes: VisualBuilderState.getAllShortcodes()
				};
				try {
					localStorage.setItem("vb_draft_" + this.config.pageId, JSON.stringify(draft));
				} catch (e) {}
			},
			checkDraft() {
				if (this.draftCheckPerformed) return;
				this.draftCheckPerformed = true;
				const draftKey = "vb_draft_" + this.config.pageId;
				const draftStr = localStorage.getItem(draftKey);
				if (draftStr) try {
					const draft = JSON.parse(draftStr);
					const draftDate = new Date(draft.timestamp);
					if (confirm("Restore unsaved changes from " + draftDate.toLocaleString() + "?")) {
						VisualBuilderState.init(draft.shortcodes);
						VisualBuilderState.markChanged();
						if (typeof ShortcodeList !== "undefined") ShortcodeList.render();
						if (typeof PreviewIframe !== "undefined") PreviewIframe.reload();
					} else localStorage.removeItem(draftKey);
				} catch (e) {
					localStorage.removeItem(draftKey);
				}
			},
			initUnsavedWarning() {
				window.addEventListener("beforeunload", (e) => {
					if (VisualBuilderState.hasChanges) {
						e.preventDefault();
						e.returnValue = "";
						return "";
					}
				});
			}
		};
	})(jQuery);
	//#endregion
	//#region platform/packages/page/resources/js/visual-builder.js
	$(document).ready(function() {
		if (window.visualBuilderData) VisualBuilderApp.init();
	});
	//#endregion
})();

//# sourceMappingURL=visual-builder.js.map