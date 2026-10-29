(function() {
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
	//#region platform/core/base/resources/js/base/toast.js
	/**
	* Options used for Toastify
	* @typedef {Object} ToastifyConfigurationObject
	* @property {string} text - Message to be displayed in the toast
	* @property {Element} node - Provide a node to be mounted inside the toast. node takes higher precedence over text
	* @property {number} duration - Duration for which the toast should be displayed. -1 for permanent toast
	* @property {string|Element} selector - CSS ID Selector on which the toast should be added
	* @property {boolean} close - To show the close icon or not
	* @property {string} gravity - To show the toast from top or bottom
	* @property {string} position - To show the toast on left or right
	* @property {string} className - Ability to provide custom class name for further customization
	* @property {boolean} stopOnFocus - To stop timer when hovered over the toast (Only if duration is set)
	* @property {Function} callback - Invoked when the toast is dismissed
	* @property {Function} onClick - Invoked when the toast is clicked
	* @property {Object} offset - Ability to add some offset to axis
	* @property {boolean} escapeMarkup - Toggle the default behavior of escaping HTML markup
	* @property {string} ariaLive - Use the HTML DOM style property to add styles to toast
	* @property {Object} style - Use the HTML DOM style property to add styles to toast
	* @property {string} icon - Icon to be shown before text
	*/
	var Toastify = class {
		constructor(options) {
			_defineProperty(this, "defaults", {
				oldestFirst: true,
				text: "Toastify is awesome!",
				node: void 0,
				duration: 3e3,
				selector: void 0,
				callback: function() {},
				close: false,
				gravity: "toastify-top",
				position: "",
				className: "",
				stopOnFocus: true,
				onClick: function() {},
				offset: {
					x: 0,
					y: 0
				},
				escapeMarkup: true,
				ariaLive: "polite",
				style: { background: "" }
			});
			/**
			* The configuration object to configure Toastify
			* @type {ToastifyConfigurationObject}
			* @public
			*/
			this.options = {};
			/**
			* The element that is the Toast
			* @type {Element}
			* @public
			*/
			this.toastElement = null;
			/**
			* The root element that contains all the toasts
			* @type {Element}
			* @private
			*/
			this._rootElement = document.body;
			this._init(options);
		}
		/**
		* Display the toast
		* @public
		*/
		showToast() {
			this.toastElement = this._buildToast();
			if (typeof this.options.selector === "string") this._rootElement = document.getElementById(this.options.selector);
			else if (this.options.selector instanceof HTMLElement || this.options.selector instanceof ShadowRoot) this._rootElement = this.options.selector;
			else this._rootElement = document.body;
			if (!this._rootElement) throw "Root element is not defined";
			this._rootElement.insertBefore(this.toastElement, this._rootElement.firstChild);
			this._reposition();
			if (this.options.duration > 0) this.toastElement.timeOutValue = window.setTimeout(() => {
				this._removeElement(this.toastElement);
			}, this.options.duration);
			return this;
		}
		/**
		* Hide the toast
		* @public
		*/
		hideToast() {
			if (this.toastElement.timeOutValue) clearTimeout(this.toastElement.timeOutValue);
			this._removeElement(this.toastElement);
		}
		/**
		* Init the Toastify class
		* @param {ToastifyConfigurationObject} options - The configuration object to configure Toastify
		* @param {string} [options.text=Hi there!] - Message to be displayed in the toast
		* @param {Element} [options.node] - Provide a node to be mounted inside the toast. node takes higher precedence over text
		* @param {number} [options.duration=3000] - Duration for which the toast should be displayed. -1 for permanent toast
		* @param {string} [options.selector] - CSS Selector on which the toast should be added
		* @param {boolean} [options.close=false] - To show the close icon or not
		* @param {string} [options.gravity=toastify-top] - To show the toast from top or bottom
		* @param {string} [options.position=right] - To show the toast on left or right
		* @param {string} [options.className] - Ability to provide custom class name for further customization
		* @param {boolean} [options.stopOnFocus] - To stop timer when hovered over the toast (Only if duration is set)
		* @param {Function} [options.callback] - Invoked when the toast is dismissed
		* @param {Function} [options.onClick] - Invoked when the toast is clicked
		* @param {Object} [options.offset] - Ability to add some offset to axis
		* @param {boolean} [options.escapeMarkup=true] - Toggle the default behavior of escaping HTML markup
		* @param {string} [options.ariaLive] - Announce the toast to screen readers
		* @param {Object} [options.style] - Use the HTML DOM style property to add styles to toast
		* @private
		*/
		_init(options) {
			this.options = Object.assign(this.defaults, options);
			this.toastElement = null;
			this.options.gravity = options.gravity === "bottom" ? "toastify-bottom" : "toastify-top";
			this.options.stopOnFocus = options.stopOnFocus === void 0 ? true : options.stopOnFocus;
		}
		/**
		* Build the Toastify element
		* @returns {Element}
		* @private
		*/
		_buildToast() {
			if (!this.options) throw "Toastify is not initialized";
			let divElement = document.createElement("div");
			divElement.className = `toastify on ${this.options.className} pe-5`;
			divElement.className += ` toastify-${this.options.position}`;
			divElement.className += ` ${this.options.gravity}`;
			for (const property in this.options.style) divElement.style[property] = this.options.style[property];
			if (this.options.ariaLive) divElement.setAttribute("aria-live", this.options.ariaLive);
			if (this.options.icon !== "") {
				let iconElement = document.createElement("div");
				iconElement.className = "toastify-icon";
				iconElement.innerHTML = this.options.icon;
				divElement.appendChild(iconElement);
			}
			const textElement = document.createElement("span");
			textElement.className = "toastify-text";
			if (this.options.node && this.options.node.nodeType === Node.ELEMENT_NODE) textElement.appendChild(this.options.node);
			else if (this.options.escapeMarkup) textElement.innerText = this.options.text;
			else textElement.innerHTML = this.options.text;
			divElement.appendChild(textElement);
			if (this.options.close === true) {
				let closeElement = document.createElement("button");
				closeElement.type = "button";
				closeElement.setAttribute("aria-label", "Close");
				closeElement.className = "toast-close";
				closeElement.style.cssText = "position: absolute; top: 8px; inset-inline-end: 8px;";
				closeElement.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M18 6l-12 12"></path>
                <path d="M6 6l12 12"></path>
            </svg>`;
				closeElement.addEventListener("click", (event) => {
					event.stopPropagation();
					this._removeElement(this.toastElement);
					window.clearTimeout(this.toastElement.timeOutValue);
				});
				const width = window.innerWidth > 0 ? window.innerWidth : screen.width;
				if (this.options.position === "left" && width > 360) divElement.insertAdjacentElement("afterbegin", closeElement);
				else divElement.appendChild(closeElement);
			}
			if (this.options.stopOnFocus && this.options.duration > 0) {
				divElement.addEventListener("mouseover", (event) => {
					window.clearTimeout(divElement.timeOutValue);
				});
				divElement.addEventListener("mouseleave", () => {
					divElement.timeOutValue = window.setTimeout(() => {
						this._removeElement(divElement);
					}, this.options.duration);
				});
			}
			if (typeof this.options.onClick === "function") divElement.addEventListener("click", (event) => {
				event.stopPropagation();
				this.options.onClick();
			});
			if (typeof this.options.offset === "object") {
				const x = this._getAxisOffsetAValue("x", this.options);
				const y = this._getAxisOffsetAValue("y", this.options);
				const xOffset = this.options.position === "left" ? x : `-${x}`;
				const yOffset = this.options.gravity === "toastify-top" ? y : `-${y}`;
				divElement.style.transform = `translate(${xOffset},${yOffset})`;
			}
			return divElement;
		}
		/**
		* Remove the toast from the DOM
		* @param {Element} toastElement
		*/
		_removeElement(toastElement) {
			toastElement.className = toastElement.className.replace(" on", "");
			window.setTimeout(() => {
				if (this.options.node && this.options.node.parentNode) this.options.node.parentNode.removeChild(this.options.node);
				if (toastElement.parentNode) toastElement.parentNode.removeChild(toastElement);
				this.options.callback.call(toastElement);
				this._reposition();
			}, 400);
		}
		/**
		* Position the toast on the DOM
		* @private
		*/
		_reposition() {
			const bottomBase = parseInt(getComputedStyle(document.documentElement).getPropertyValue("--toastify-bottom-offset")) || 15;
			let topLeftOffsetSize = {
				top: 15,
				bottom: bottomBase
			};
			let topRightOffsetSize = {
				top: 15,
				bottom: bottomBase
			};
			let offsetSize = {
				top: 15,
				bottom: bottomBase
			};
			let allToasts = this._rootElement.querySelectorAll(".toastify");
			let classUsed;
			for (let i = 0; i < allToasts.length; i++) {
				if (allToasts[i].classList.contains("toastify-top") === true) classUsed = "toastify-top";
				else classUsed = "toastify-bottom";
				let height = allToasts[i].offsetHeight;
				classUsed = classUsed.substr(9, classUsed.length - 1);
				let offset = 15;
				if ((window.innerWidth > 0 ? window.innerWidth : screen.width) <= 360) {
					allToasts[i].style[classUsed] = `${offsetSize[classUsed]}px`;
					offsetSize[classUsed] += height + offset;
				} else if (allToasts[i].classList.contains("toastify-left") === true) {
					allToasts[i].style[classUsed] = `${topLeftOffsetSize[classUsed]}px`;
					topLeftOffsetSize[classUsed] += height + offset;
				} else {
					allToasts[i].style[classUsed] = `${topRightOffsetSize[classUsed]}px`;
					topRightOffsetSize[classUsed] += height + offset;
				}
			}
		}
		/**
		* Helper function to get offset
		* @param {string} axis - 'x' or 'y'
		* @param {ToastifyConfigurationObject} options - The options object containing the offset object
		*/
		_getAxisOffsetAValue(axis, options) {
			if (options.offset[axis]) {
				if (isNaN(options.offset[axis])) return options.offset[axis];
				else return `${options.offset[axis]}px`;
			}
			return "0px";
		}
	};
	function injectCSS() {
		const element = document.createElement("style");
		element.textContent = `
        .toastify {
            padding: 0.75rem 2rem 0.75rem 0.75rem;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow:
                0 3px 6px -1px rgba(0, 0, 0, 0.12),
                0 10px 36px -4px rgba(77, 96, 232, 0.3);
            background: -webkit-linear-gradient(315deg, #73a5ff, #5477f5);
            background: linear-gradient(135deg, #73a5ff, #5477f5);
            position: fixed;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.215, 0.61, 0.355, 1);
            border-radius: 2px;
            cursor: pointer;
            text-decoration: none;
            z-index: 999999;
            width: 25rem;
            max-width: calc(100% - 30px);
        }

        .toastify.on {
            opacity: 1;
        }

        .toastify-icon {
            width: 1.5rem;
            height: 1.5rem;
        }

        .toast-close {
            background: transparent;
            border: 0;
            color: white;
            cursor: pointer;
            font-family: inherit;
            font-size: 1em;
            opacity: 0.4;
            padding: 0 5px;
            position: absolute;
            top: 0.25rem;
            inset-inline-end: 0.25rem;
        }

        .toast-close svg {
            width: 1em;
            height: 1em;
        }

        .toastify-text a {
            text-decoration: underline;
            color: #fff;
        }

        .toastify-right {
            inset-inline-end: 15px;
        }

        .toastify-left {
            inset-inline-start: 15px;
        }

        .toastify-top {
            top: -150px;
        }

        .toastify-bottom {
            bottom: -150px;
        }

        .toastify-rounded {
            border-radius: 25px;
        }

        .toastify-center {
            margin-inline-start: auto;
            margin-inline-end: auto;
            inset-inline-start: 0;
            inset-inline-end: 0;
            max-width: fit-content;
            max-width: -moz-fit-content;
        }

        @media only screen and (max-width: 768px) {
            .toastify {
                width: auto;
                max-width: calc(100% - 30px);
                inset-inline-start: 15px !important;
                inset-inline-end: 15px !important;
                transform: none !important;
            }
        }
    `;
		document.head.appendChild(element);
	}
	injectCSS();
	function StartToastifyInstance(options) {
		return new Toastify(options);
	}
	//#endregion
	//#region platform/packages/theme/resources/js/toast.js
	window.Theme = window.Theme || {};
	var Theme = window.Theme;
	Theme.getToastConfig = function() {
		return window.ThemeToastConfig || {
			position: "bottom",
			alignment: "right",
			offsetX: 15,
			offsetY: 15,
			timeout: 5e3,
			successIcon: "",
			errorIcon: ""
		};
	};
	Theme.getToastIcon = function(configIcon, fallbackSvg) {
		return configIcon || fallbackSvg;
	};
	Theme.showNotice = function(messageType, message) {
		const config = this.getToastConfig();
		let color = "#fff";
		let icon = "";
		const defaultSuccessIcon = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0\" /><path d=\"M9 12l2 2l4 -4\" /></svg>";
		const defaultErrorIcon = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0\" /><path d=\"M12 9v4\" /><path d=\"M12 16v.01\" /></svg>";
		switch (messageType) {
			case "success":
				color = "#437a43";
				icon = this.getToastIcon(config.successIcon, defaultSuccessIcon);
				break;
			case "danger":
				color = "#bd362f";
				icon = this.getToastIcon(config.errorIcon, defaultErrorIcon);
				break;
			case "warning":
				color = "#f89406";
				icon = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12 8v4\" /><path d=\"M12 16h.01\" /></svg>";
				break;
			case "info":
				color = "#2f96b4";
				icon = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0\" /><path d=\"M12 9h.01\" /><path d=\"M11 12h1v4h1\" /></svg>";
		}
		StartToastifyInstance({
			text: message,
			icon,
			duration: parseInt(config.timeout) || 5e3,
			close: true,
			gravity: config.position,
			position: config.alignment,
			offset: {
				x: parseInt(config.offsetX) || 15,
				y: parseInt(config.offsetY) || 15
			},
			stopOnFocus: true,
			style: { background: color },
			escapeMarkup: false,
			className: "toastify-" + messageType
		}).showToast();
	};
	Theme.showError = function(message) {
		this.showNotice("danger", message);
	};
	Theme.showSuccess = function(message) {
		console.log("✅ Theme.showSuccess called:", message);
		console.trace("Theme showSuccess called from:");
		this.showNotice("success", message);
	};
	Theme.handleError = (data) => {
		if (typeof data.errors !== "undefined" && data.errors.length) Theme.handleValidationError(data.errors);
		else if (typeof data.responseJSON !== "undefined") {
			if (typeof data.responseJSON.errors !== "undefined") {
				if (data.status === 422) Theme.handleValidationError(data.responseJSON.errors);
			} else if (typeof data.responseJSON.message !== "undefined") Theme.showError(data.responseJSON.message);
			else Theme.showError(data.responseJSON.join(", ").join(", "));
		} else Theme.showError(data.statusText);
	};
	Theme.handleValidationError = (errors) => {
		let message = "";
		Object.values(errors).forEach((item) => {
			if (message !== "") message += "\n";
			message += item;
		});
		Theme.showError(message);
	};
	//#endregion
})();

//# sourceMappingURL=toast.js.map