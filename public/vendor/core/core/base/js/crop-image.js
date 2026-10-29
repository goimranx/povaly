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
	//#region platform/core/base/resources/js/crop-image.js
	var CropImage = class {
		constructor() {
			_defineProperty(this, "modal", $(document).find(".crop-image-modal"));
			_defineProperty(this, "image", this.modal.find(".cropper-image"));
			_defineProperty(this, "cropper", null);
			this.modal.on("change", "input[type=\"file\"]", (e) => {
				const files = e.target.files;
				let reader;
				let file;
				if (files && files.length > 0) {
					file = files[0];
					if (URL) this.image.prop("src", URL.createObjectURL(file));
					else if (FileReader) {
						reader = new FileReader();
						reader.onload = () => {
							this.image.prop("src", reader.result);
						};
						reader.readAsDataURL(file);
					}
				}
				this.init();
			}).on("click", "button[type=\"submit\"]", (e) => {
				e.preventDefault();
				const button = $(e.currentTarget);
				const form = this.modal.find("form");
				this.cropper.getCroppedCanvas({
					width: 160,
					height: 160
				}).toBlob((blob) => {
					const formData = new FormData();
					formData.append(form.find("input[type=\"file\"]").prop("name"), blob);
					$httpClient.make().withButtonLoading(button).post(form.prop("action"), formData).then(({ data }) => {
						this.updateImage(data.data.url);
						Botble.showSuccess(data.message);
						this.modal.modal("hide");
					});
				});
			}).on("shown.bs.modal", (e) => {
				const originalImage = $(e.relatedTarget).closest(".crop-image-container").find(".crop-image-original");
				const image = new Image();
				image.src = originalImage.prop("src");
				image.onload = () => {
					this.image.prop("src", image.src);
					this.init();
				};
			}).on("hidden.bs.modal", () => {
				this.destroy();
			});
			$(document).on("click", "[data-bb-toggle=\"delete-avatar\"]", (e) => {
				e.preventDefault();
				const button = $(e.currentTarget);
				$httpClient.make().post(button.prop("href")).then(({ data }) => {
					this.updateImage(data.data.url);
					Botble.showSuccess(data.message);
					this.modal.modal("hide");
				});
			});
		}
		init() {
			this.cropper && this.cropper.destroy();
			this.cropper = new Cropper(this.image[0], {
				aspectRatio: 1,
				preview: ".img-preview"
			});
		}
		destroy() {
			this.cropper.destroy();
			this.cropper = null;
			this.image.prop("src", "");
			this.modal.find("input[type=\"file\"]").val("");
		}
		updateImage(url) {
			$(document).find(".crop-image-original").each((i, el) => {
				if ($(el).is("img")) $(el).prop("src", url);
				else $(el).css("background-image", `url(${url})`);
			});
		}
	};
	$(() => {
		new CropImage();
	});
	//#endregion
})();

//# sourceMappingURL=crop-image.js.map