(function() {
	//#region platform/core/base/resources/js/ckeditor-upload-adapter.js
	/**
	* Upload file adapter for Botble ckeditor
	*/
	var CKEditorUploadAdapter = class {
		/**
		* Creates a new adapter instance.
		*
		*/
		constructor(loader, url, t) {
			/**
			* FileLoader instance to use during the upload.
			*/
			this.loader = loader;
			/**
			* Upload URL.
			*
			* @member {String} #url
			*/
			this.url = url;
			/**
			* Locale translation method.
			*/
			this.t = t;
		}
		/**
		* Starts the upload process.
		*
		* @returns {Promise.<Object>}
		*/
		upload() {
			return this.loader.file.then((file) => {
				return new Promise((resolve, reject) => {
					this._initRequest();
					this._initListeners(resolve, reject, file);
					this._sendRequest(file);
				});
			});
		}
		/**
		* Aborts the upload process.
		*
		*/
		abort() {
			if (this.xhr) this.xhr.abort();
		}
		/**
		* Initializes the XMLHttpRequest object.
		*
		* @private
		*/
		_initRequest() {
			const xhr = this.xhr = new XMLHttpRequest();
			xhr.open("POST", this.url, true);
			xhr.responseType = "json";
		}
		/**
		* Initializes XMLHttpRequest listeners.
		*
		* @private
		* @param {Function} resolve Callback function to be called when the request is successful.
		* @param {Function} reject Callback function to be called when the request cannot be completed.
		* @param {File} file File instance to be uploaded.
		*/
		_initListeners(resolve, reject, file) {
			const xhr = this.xhr;
			const loader = this.loader;
			const t = this.t;
			const genericError = t("Cannot upload file:") + ` ${file.name}.`;
			xhr.addEventListener("error", () => reject(genericError));
			xhr.addEventListener("abort", () => reject());
			xhr.addEventListener("load", () => {
				const response = xhr.response;
				if (!response || !response.uploaded) return reject(response && response.error && response.error.message ? response.error.message : genericError);
				let imageUrl = response.url;
				if (imageUrl && imageUrl.startsWith(window.location.origin)) imageUrl = imageUrl.replace(window.location.origin, "");
				resolve({ default: imageUrl });
			});
			/* istanbul ignore else */
			if (xhr.upload) xhr.upload.addEventListener("progress", (evt) => {
				if (evt.lengthComputable) {
					loader.uploadTotal = evt.total;
					loader.uploaded = evt.loaded;
				}
			});
		}
		/**
		* Prepares the data and sends the request.
		*
		* @private
		* @param {File} file File instance to be uploaded.
		*/
		_sendRequest(file) {
			const data = new FormData();
			data.append("upload", file);
			data.append("_token", $("meta[name=\"csrf-token\"]").attr("content"));
			this.xhr.send(data);
		}
	};
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
	//#region platform/core/base/resources/js/editor.js
	var EditorManagement = class {
		constructor() {
			this.CKEDITOR = {};
			this.ckEditorConfigCallbacks = [];
			this.ckEditorInitialCallbacks = [];
			this.ckFinderCallback = null;
			this.tinyMceConfigCallbacks = [];
			this.tinyMceInitialCallbacks = [];
			document.dispatchEvent(new CustomEvent("core-editor-init", { detail: this }));
		}
		ckEditorConfigUsing(callback) {
			this.ckEditorConfigCallbacks.push(callback);
		}
		ckEditorInitialUsing(callback) {
			this.ckEditorInitialCallbacks.push(callback);
		}
		ckEditorConfig(config) {
			for (let callback of this.ckEditorConfigCallbacks) config = callback(config);
			return config;
		}
		ckFinderUsing(callback) {
			this.ckFinderCallback = callback;
		}
		async ckFinderInitial(editor, element) {
			if (this.ckFinderCallback) return this.ckFinderCallback(editor, element);
			const ckFileRepository = editor.plugins.get("FileRepository");
			if (ckFileRepository && typeof RV_MEDIA_URL != "undefined" && RV_MEDIA_URL.media_upload_from_editor) ckFileRepository.createUploadAdapter = (loader) => {
				return new CKEditorUploadAdapter(loader, RV_MEDIA_URL.media_upload_from_editor, editor.t);
			};
			const ckfinder = editor.commands.get("ckfinder");
			const btnGalleries = $(`#${element}`).parent().find(".btn_gallery[data-action=\"media-insert-ckeditor\"]");
			if (ckfinder && btnGalleries.length) ckfinder.execute = () => btnGalleries.trigger("click");
			else ckfinder.execute = () => Botble.showError("Not available.");
		}
		initCkEditor(element, extraConfig) {
			if (this.CKEDITOR[element] || !$("#" + element).is(":visible")) return false;
			const editor = document.querySelector("#" + element);
			let config = _objectSpread2({
				fontSize: { options: [
					9,
					10,
					11,
					12,
					13,
					"default",
					15,
					16,
					17,
					18,
					19,
					20,
					21,
					22,
					23,
					24
				] },
				fontFamily: { options: [
					"default",
					"Arial, Helvetica, sans-serif",
					"Courier New, Courier, monospace",
					"Georgia, serif",
					"Lucida Sans Unicode, Lucida Grande, sans-serif",
					"Tahoma, Geneva, sans-serif",
					"Times New Roman, Times, serif",
					"Trebuchet MS, Helvetica, sans-serif",
					"Verdana, Geneva, sans-serif",
					...window.ckEditorFontFamilies ? window.ckEditorFontFamilies.split(",").map((font) => font.trim()).filter((font) => font) : []
				] },
				alignment: { options: [
					"left",
					"right",
					"center",
					"justify"
				] },
				heading: { options: [
					{
						model: "paragraph",
						title: "Paragraph",
						class: "ck-heading_paragraph"
					},
					{
						model: "heading1",
						view: "h1",
						title: "Heading 1",
						class: "ck-heading_heading1"
					},
					{
						model: "heading2",
						view: "h2",
						title: "Heading 2",
						class: "ck-heading_heading2"
					},
					{
						model: "heading3",
						view: "h3",
						title: "Heading 3",
						class: "ck-heading_heading3"
					},
					{
						model: "heading4",
						view: "h4",
						title: "Heading 4",
						class: "ck-heading_heading4"
					},
					{
						model: "heading5",
						view: "h5",
						title: "Heading 5",
						class: "ck-heading_heading4"
					},
					{
						model: "heading6",
						view: "h6",
						title: "Heading 6",
						class: "ck-heading_heading4"
					}
				] },
				placeholder: " ",
				toolbar: {
					items: [
						"heading",
						"|",
						"fontColor",
						"fontSize",
						"fontBackgroundColor",
						"fontFamily",
						"bold",
						"italic",
						"underline",
						"link",
						"strikethrough",
						"bulletedList",
						"numberedList",
						"|",
						"alignment",
						"direction",
						"shortcode",
						"outdent",
						"indent",
						"|",
						"htmlEmbed",
						"imageInsert",
						"ckfinder",
						"blockQuote",
						"insertTable",
						"mediaEmbed",
						"bootstrapGrid",
						"undo",
						"redo",
						"findAndReplace",
						"removeFormat",
						"sourceEditing",
						"codeBlock",
						"fullScreen"
					],
					shouldNotGroupWhenFull: true
				},
				language: {
					ui: window.siteEditorLocale || "en",
					content: window.siteEditorLocale || "en"
				},
				image: {
					toolbar: [
						"imageTextAlternative",
						"linkImage",
						"imageStyle:inline",
						"imageStyle:block",
						"imageStyle:side",
						"imageStyle:wrapText",
						"imageStyle:breakText",
						"toggleImageCaption",
						"ImageResize"
					],
					upload: { types: [
						"jpeg",
						"png",
						"gif",
						"bmp",
						"webp",
						"tiff",
						"svg+xml"
					] }
				},
				codeBlock: { languages: [
					{
						language: "plaintext",
						label: "Plain text"
					},
					{
						language: "c",
						label: "C"
					},
					{
						language: "cs",
						label: "C#"
					},
					{
						language: "cpp",
						label: "C++"
					},
					{
						language: "css",
						label: "CSS"
					},
					{
						language: "diff",
						label: "Diff"
					},
					{
						language: "html",
						label: "HTML"
					},
					{
						language: "java",
						label: "Java"
					},
					{
						language: "javascript",
						label: "JavaScript"
					},
					{
						language: "php",
						label: "PHP"
					},
					{
						language: "python",
						label: "Python"
					},
					{
						language: "ruby",
						label: "Ruby"
					},
					{
						language: "typescript",
						label: "TypeScript"
					},
					{
						language: "xml",
						label: "XML"
					},
					{
						language: "dart",
						label: "Dart",
						class: "language-dart"
					}
				] },
				link: {
					defaultProtocol: "http://",
					decorators: { openInNewTab: {
						mode: "manual",
						label: "Open in a new tab",
						attributes: {
							target: "_blank",
							rel: "noopener noreferrer"
						}
					} }
				},
				table: { contentToolbar: [
					"tableColumn",
					"tableRow",
					"mergeTableCells",
					"tableCellProperties",
					"tableProperties"
				] },
				htmlSupport: { allow: [{
					name: /.*/,
					attributes: true,
					classes: true,
					styles: true
				}] },
				mediaEmbed: { extraProviders: [{
					name: "tiktok",
					url: "^.*https:\\/\\/(?:m|www|vm)?\\.?tiktok\\.com\\/((?:.*\\b(?:(?:usr|v|embed|user|video)\\/|\\?shareId=|\\&item_id=)(\\d+))|\\w+)",
					html: (match) => {
						return `<iframe src="https://www.tiktok.com/embed/v2/${match[1]}" width="100%" height="400" frameborder="0"></iframe>`;
					}
				}] }
			}, extraConfig);
			config = this.ckEditorConfig(config);
			ClassicEditor.create(editor, config).then(async (editor) => {
				editor.insertHtml = (html) => {
					const viewFragment = editor.data.processor.toView(html);
					const modelFragment = editor.data.toModel(viewFragment);
					editor.model.insertContent(modelFragment);
				};
				window.editor = editor;
				this.CKEDITOR[element] = editor;
				const minHeight = $("#" + element).prop("rows") * 90;
				const className = `ckeditor-${element}-inline`;
				$(editor.ui.view.editable.element).addClass(className).after(`
                    <style>
                        .ck-editor__editable_inline {
                            min-height: ${minHeight - 100}px;
                            max-height: ${minHeight + 100}px;
                        }
                    </style>
                `);
				let timeout;
				editor.model.document.on("change:data", () => {
					clearTimeout(timeout);
					timeout = setTimeout(() => {
						editor.updateSourceElement();
					}, 150);
				});
				editor.commands._commands.get("mediaEmbed").execute = (url) => {
					editor.execute("shortcode", `[media url="${url}"][/media]`);
				};
				await this.ckEditorInitialUsing(editor);
				await this.ckFinderInitial(editor, element);
			}).catch((error) => {
				console.error(error);
			});
		}
		uploadImageFromEditor(blobInfo, callback) {
			let formData = new FormData();
			if (typeof blobInfo.blob === "function") formData.append("upload", blobInfo.blob(), blobInfo.filename());
			else formData.append("upload", blobInfo);
			$httpClient.make().postForm(RV_MEDIA_URL.media_upload_from_editor, formData).then(({ data }) => {
				if (data.uploaded) {
					let imageUrl = data.url;
					if (imageUrl && imageUrl.startsWith(window.location.origin)) imageUrl = imageUrl.replace(window.location.origin, "");
					callback(imageUrl);
				}
			});
		}
		tinyMceConfigUsing(callback) {
			this.tinyMceConfigCallbacks.push(callback);
		}
		tinyMceInitialUsing(callback) {
			this.tinyMceInitialCallbacks.push(callback);
		}
		tinyMceConfig(config) {
			for (let callback of this.tinyMceConfigCallbacks) config = callback(config);
			return config;
		}
		async tinyMceInitial(editor) {
			return editor;
		}
		async initTinyMce(element) {
			let options = {
				menubar: true,
				selector: `#${element}`,
				min_height: $(`#${element}`).prop("rows") * 110,
				resize: "vertical",
				plugins: "code autolink advlist visualchars link image media table charmap hr pagebreak nonbreaking anchor insertdatetime lists wordcount imagetools visualblocks",
				extended_valid_elements: "input[id|name|value|type|class|style|required|placeholder|autocomplete|onclick]",
				toolbar: "formatselect | bold italic strikethrough forecolor backcolor | link image table | alignleft aligncenter alignright alignjustify  | numlist bullist indent  |  visualblocks code",
				convert_urls: false,
				image_caption: true,
				image_advtab: true,
				image_title: true,
				placeholder: "",
				contextmenu: "link image inserttable | cell row column deletetable",
				images_upload_url: RV_MEDIA_URL.media_upload_from_editor,
				automatic_uploads: true,
				block_unsupported_drop: false,
				file_picker_types: "file image media",
				images_upload_handler: this.uploadImageFromEditor.bind(this),
				file_picker_callback: (callback) => {
					$("<input type=\"file\" accept=\"image/*\" />").click().on("change", (e) => {
						this.uploadImageFromEditor(e.target.files[0], callback);
					});
				},
				directionality: $("body").prop("dir") || "ltr"
			};
			if (localStorage.getItem("themeMode") === "dark") {
				options.skin = "oxide-dark";
				options.content_css = "dark";
			}
			options = this.tinyMceConfig(options);
			const tinymceInstance = tinymce.init(options);
			await this.tinyMceInitial(tinymceInstance);
		}
		initEditor(element, extraConfig, type) {
			if (!element.length) return false;
			let current = this;
			switch (type) {
				case "ckeditor":
					$.each(element, (index, item) => {
						current.initCkEditor($(item).prop("id"), extraConfig);
					});
					break;
				case "tinymce": $.each(element, (index, item) => {
					current.initTinyMce($(item).prop("id"));
				});
			}
		}
		init() {
			let $ckEditor = $(document).find(".editor-ckeditor");
			let $tinyMce = $(document).find(".editor-tinymce");
			let current = this;
			if ($ckEditor.length > 0) current.initEditor($ckEditor, {}, "ckeditor");
			if ($tinyMce.length > 0) current.initEditor($tinyMce, {}, "tinymce");
			$(document).off("click", ".show-hide-editor-btn").on("click", ".show-hide-editor-btn", (event) => {
				event.preventDefault();
				const editorInstance = $(event.currentTarget).data("result");
				let $result = $("#" + editorInstance);
				if ($result.hasClass("editor-ckeditor")) {
					const $editorActionItem = $(".editor-action-item");
					if (this.CKEDITOR[editorInstance] && typeof this.CKEDITOR[editorInstance] !== "undefined") {
						this.CKEDITOR[editorInstance].destroy();
						this.CKEDITOR[editorInstance] = null;
						$editorActionItem.not(".action-show-hide-editor").hide();
					} else {
						current.initCkEditor(editorInstance, {}, "ckeditor");
						$editorActionItem.not(".action-show-hide-editor").show();
					}
				} else if ($result.hasClass("editor-tinymce")) tinymce.execCommand("mceToggleEditor", false, editorInstance);
			});
			return this;
		}
	};
	$(() => {
		window.EDITOR = new EditorManagement().init();
		window.EditorManagement = window.EditorManagement || EditorManagement;
		$(document).on("shown.bs.modal", function() {
			window.EDITOR.init();
		});
		document.addEventListener("core-shortcode-config-loaded", () => {
			setTimeout(() => {
				if (!window.EDITOR) return;
				$(".shortcode-admin-config").find(".editor-ckeditor, .editor-tinymce").each(function() {
					const $editor = $(this);
					const originalId = $editor.attr("id");
					if (originalId) {
						const uniqueId = originalId + "_shortcode_modal_" + Date.now();
						$editor.attr("id", uniqueId);
						$(`label[for="${originalId}"]`).attr("for", uniqueId);
						window.EDITOR.init(uniqueId);
					}
				});
			}, 100);
		});
	});
	//#endregion
})();

//# sourceMappingURL=editor.js.map