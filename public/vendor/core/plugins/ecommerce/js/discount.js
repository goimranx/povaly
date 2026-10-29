(function(){if(typeof document==='undefined')return;var s=document.createElement('style');s.textContent=".date-time-group .invalid-feedback {\n  position: absolute;\n  bottom: -15px;\n}/*$vite$:1*/";document.head.appendChild(s);})();(function(vue) {
	//#region \0plugin-vue:export-helper
	var _plugin_vue_export_helper_default = (sfc, props) => {
		const target = sfc.__vccOpts || sfc;
		for (const [key, val] of props) target[key] = val;
		return target;
	};
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/partials/DiscountSearchBoxPagination.vue
	var _sfc_main$2 = (0, vue.defineComponent)({
		props: { resource: Object },
		emits: ["onPrev", "onNext"]
	});
	var _hoisted_1$2 = { class: "pagination my-0 d-flex justify-content-end" };
	var _hoisted_2$1 = ["aria-disabled"];
	var _hoisted_3$1 = ["aria-disabled"];
	function _sfc_render$2(_ctx, _cache, $props, $setup, $data, $options) {
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("ul", _hoisted_1$2, [(0, vue.createElementVNode)("li", { class: (0, vue.normalizeClass)({
			"page-item": true,
			disabled: _ctx.resource.current_page === 1
		}) }, [_ctx.resource.current_page === 1 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", {
			key: 0,
			class: "page-link",
			"aria-disabled": _ctx.resource.current_page === 1
		}, [..._cache[2] || (_cache[2] = [(0, vue.createElementVNode)("svg", {
			xmlns: "http://www.w3.org/2000/svg",
			class: "icon",
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
		}), (0, vue.createElementVNode)("path", { d: "M15 6l-6 6l6 6" })], -1)])], 8, _hoisted_2$1)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
			key: 1,
			href: "javascript:void(0)",
			class: "page-link",
			onClick: _cache[0] || (_cache[0] = ($event) => _ctx.$emit("onPrev"))
		}, [..._cache[3] || (_cache[3] = [(0, vue.createElementVNode)("svg", {
			xmlns: "http://www.w3.org/2000/svg",
			class: "icon",
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
		}), (0, vue.createElementVNode)("path", { d: "M15 6l-6 6l6 6" })], -1)])]))], 2), (0, vue.createElementVNode)("li", { class: (0, vue.normalizeClass)({
			"page-item": true,
			disabled: !_ctx.resource.next_page_url
		}) }, [!_ctx.resource.next_page_url ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", {
			key: 0,
			class: "page-link",
			"aria-disabled": !_ctx.resource.next_page_url
		}, [..._cache[4] || (_cache[4] = [(0, vue.createElementVNode)("svg", {
			xmlns: "http://www.w3.org/2000/svg",
			class: "icon",
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
		}), (0, vue.createElementVNode)("path", { d: "M9 6l6 6l-6 6" })], -1)])], 8, _hoisted_3$1)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
			key: 1,
			href: "javascript:void(0)",
			class: "page-link",
			onClick: _cache[1] || (_cache[1] = ($event) => _ctx.$emit("onNext"))
		}, [..._cache[5] || (_cache[5] = [(0, vue.createElementVNode)("svg", {
			xmlns: "http://www.w3.org/2000/svg",
			class: "icon",
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
		}), (0, vue.createElementVNode)("path", { d: "M9 6l6 6l-6 6" })], -1)])]))], 2)]);
	}
	var DiscountSearchBoxPagination_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main$2, [["render", _sfc_render$2]]);
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/partials/DiscountListItemRemoveIconButton.vue
	var _sfc_main$1 = {};
	var _hoisted_1$1 = {
		href: "javascript:void(0)",
		class: "text-decoration-none list-group-item-actions"
	};
	function _sfc_render$1(_ctx, _cache) {
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("a", _hoisted_1$1, [..._cache[0] || (_cache[0] = [(0, vue.createElementVNode)("svg", {
			xmlns: "http://www.w3.org/2000/svg",
			class: "icon text-secondary",
			width: "24",
			height: "24",
			viewBox: "0 0 24 24",
			"stroke-width": "2",
			stroke: "currentColor",
			fill: "none",
			"stroke-linecap": "round",
			"stroke-linejoin": "round"
		}, [
			(0, vue.createElementVNode)("path", {
				stroke: "none",
				d: "M0 0h24v24H0z",
				fill: "none"
			}),
			(0, vue.createElementVNode)("path", { d: "M18 6l-12 12" }),
			(0, vue.createElementVNode)("path", { d: "M6 6l12 12" })
		], -1)])]);
	}
	var DiscountListItemRemoveIconButton_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main$1, [["render", _sfc_render$1]]);
	//#endregion
	//#region node_modules/moment/dist/moment.js
	//! moment.js
	//! version : 2.31.0
	//! authors : Tim Wood, Iskren Chernev, Moment.js contributors
	//! license : MIT
	//! momentjs.com
	var hookCallback;
	function hooks() {
		return hookCallback.apply(null, arguments);
	}
	function setHookCallback(callback) {
		hookCallback = callback;
	}
	function isArray(input) {
		return input instanceof Array || Object.prototype.toString.call(input) === "[object Array]";
	}
	function isObject(input) {
		return input != null && Object.prototype.toString.call(input) === "[object Object]";
	}
	function hasOwnProp(a, b) {
		return Object.prototype.hasOwnProperty.call(a, b);
	}
	function isObjectEmpty(obj) {
		if (Object.getOwnPropertyNames) return Object.getOwnPropertyNames(obj).length === 0;
		else {
			var k;
			for (k in obj) if (hasOwnProp(obj, k)) return false;
			return true;
		}
	}
	function isUndefined(input) {
		return input === void 0;
	}
	function isNumber(input) {
		return typeof input === "number" || Object.prototype.toString.call(input) === "[object Number]";
	}
	function isDate(input) {
		return input instanceof Date || Object.prototype.toString.call(input) === "[object Date]";
	}
	function map(arr, fn) {
		var res = [], i, arrLen = arr.length;
		for (i = 0; i < arrLen; ++i) res.push(fn(arr[i], i));
		return res;
	}
	function extend(a, b) {
		for (var i in b) if (hasOwnProp(b, i)) a[i] = b[i];
		if (hasOwnProp(b, "toString")) a.toString = b.toString;
		if (hasOwnProp(b, "valueOf")) a.valueOf = b.valueOf;
		return a;
	}
	function createUTC(input, format, locale, strict) {
		return createLocalOrUTC(input, format, locale, strict, true).utc();
	}
	function defaultParsingFlags() {
		return {
			empty: false,
			unusedTokens: [],
			unusedInput: [],
			overflow: -2,
			charsLeftOver: 0,
			nullInput: false,
			invalidEra: null,
			invalidMonth: null,
			invalidOffset: null,
			invalidFormat: false,
			userInvalidated: false,
			iso: false,
			parsedDateParts: [],
			era: null,
			meridiem: null,
			rfc2822: false,
			weekdayMismatch: false
		};
	}
	function getParsingFlags(m) {
		if (m._pf == null) m._pf = defaultParsingFlags();
		return m._pf;
	}
	var some;
	if (Array.prototype.some) some = Array.prototype.some;
	else some = function(fun) {
		var t = Object(this), len = t.length >>> 0, i = 0;
		for (; i < len; i++) if (i in t && fun.call(this, t[i], i, t)) return true;
		return false;
	};
	function isValid$2(m) {
		var flags = null, parsedParts = false, isNowValid = m._d && !isNaN(m._d.getTime());
		if (isNowValid) {
			flags = getParsingFlags(m);
			parsedParts = some.call(flags.parsedDateParts, function(i) {
				return i != null;
			});
			isNowValid = flags.overflow < 0 && !flags.empty && !flags.invalidEra && !flags.invalidMonth && !flags.invalidOffset && !flags.invalidWeekday && !flags.weekdayMismatch && !flags.nullInput && !flags.invalidFormat && !flags.userInvalidated && (!flags.meridiem || flags.meridiem && parsedParts);
			if (m._strict) isNowValid = isNowValid && flags.charsLeftOver === 0 && flags.unusedTokens.length === 0 && flags.bigHour === void 0;
		}
		if (Object.isFrozen == null || !Object.isFrozen(m)) m._isValid = isNowValid;
		else return isNowValid;
		return m._isValid;
	}
	function createInvalid$1(flags) {
		var m = createUTC(NaN);
		if (flags != null) extend(getParsingFlags(m), flags);
		else getParsingFlags(m).userInvalidated = true;
		return m;
	}
	var momentProperties = hooks.momentProperties = [];
	var updateInProgress = false;
	function copyConfig(to, from) {
		var i, prop, val, momentPropertiesLen = momentProperties.length;
		if (!isUndefined(from._isAMomentObject)) to._isAMomentObject = from._isAMomentObject;
		if (!isUndefined(from._i)) to._i = from._i;
		if (!isUndefined(from._f)) to._f = from._f;
		if (!isUndefined(from._l)) to._l = from._l;
		if (!isUndefined(from._strict)) to._strict = from._strict;
		if (!isUndefined(from._tzm)) to._tzm = from._tzm;
		if (!isUndefined(from._isUTC)) to._isUTC = from._isUTC;
		if (!isUndefined(from._offset)) to._offset = from._offset;
		if (!isUndefined(from._pf)) to._pf = getParsingFlags(from);
		if (!isUndefined(from._locale)) to._locale = from._locale;
		if (momentPropertiesLen > 0) for (i = 0; i < momentPropertiesLen; i++) {
			prop = momentProperties[i];
			val = from[prop];
			if (!isUndefined(val)) to[prop] = val;
		}
		return to;
	}
	function Moment(config) {
		copyConfig(this, config);
		this._d = new Date(config._d != null ? config._d.getTime() : NaN);
		if (!this.isValid()) this._d = /* @__PURE__ */ new Date(NaN);
		if (updateInProgress === false) {
			updateInProgress = true;
			hooks.updateOffset(this);
			updateInProgress = false;
		}
	}
	function isMoment(obj) {
		return obj instanceof Moment || obj != null && obj._isAMomentObject != null;
	}
	function warn(msg) {
		if (hooks.suppressDeprecationWarnings === false && typeof console !== "undefined" && console.warn) console.warn("Deprecation warning: " + msg);
	}
	function deprecate(msg, fn) {
		var firstTime = true;
		return extend(function() {
			if (hooks.deprecationHandler != null) hooks.deprecationHandler(null, msg);
			if (firstTime) {
				var args = [], arg, i, key, argLen = arguments.length;
				for (i = 0; i < argLen; i++) {
					arg = "";
					if (typeof arguments[i] === "object") {
						arg += "\n[" + i + "] ";
						for (key in arguments[0]) if (hasOwnProp(arguments[0], key)) arg += key + ": " + arguments[0][key] + ", ";
						arg = arg.slice(0, -2);
					} else arg = arguments[i];
					args.push(arg);
				}
				warn(msg + "\nArguments: " + Array.prototype.slice.call(args).join("") + "\n" + (/* @__PURE__ */ new Error()).stack);
				firstTime = false;
			}
			return fn.apply(this, arguments);
		}, fn);
	}
	var deprecations = {};
	function deprecateSimple(name, msg) {
		if (hooks.deprecationHandler != null) hooks.deprecationHandler(name, msg);
		if (!deprecations[name]) {
			warn(msg + "\n" + (/* @__PURE__ */ new Error()).stack);
			deprecations[name] = true;
		}
	}
	hooks.suppressDeprecationWarnings = false;
	hooks.deprecationHandler = null;
	function isFunction(input) {
		return typeof Function !== "undefined" && input instanceof Function || Object.prototype.toString.call(input) === "[object Function]";
	}
	var aliases = {
		D: "date",
		dates: "date",
		date: "date",
		d: "day",
		days: "day",
		day: "day",
		e: "weekday",
		weekdays: "weekday",
		weekday: "weekday",
		E: "isoWeekday",
		isoweekdays: "isoWeekday",
		isoweekday: "isoWeekday",
		DDD: "dayOfYear",
		dayofyears: "dayOfYear",
		dayofyear: "dayOfYear",
		h: "hour",
		hours: "hour",
		hour: "hour",
		ms: "millisecond",
		milliseconds: "millisecond",
		millisecond: "millisecond",
		m: "minute",
		minutes: "minute",
		minute: "minute",
		M: "month",
		months: "month",
		month: "month",
		Q: "quarter",
		quarters: "quarter",
		quarter: "quarter",
		s: "second",
		seconds: "second",
		second: "second",
		gg: "weekYear",
		weekyears: "weekYear",
		weekyear: "weekYear",
		GG: "isoWeekYear",
		isoweekyears: "isoWeekYear",
		isoweekyear: "isoWeekYear",
		w: "week",
		weeks: "week",
		week: "week",
		W: "isoWeek",
		isoweeks: "isoWeek",
		isoweek: "isoWeek",
		y: "year",
		years: "year",
		year: "year"
	};
	function normalizeUnits(units) {
		return typeof units === "string" ? aliases[units] || aliases[units.toLowerCase()] : void 0;
	}
	function normalizeObjectUnits(inputObject) {
		var normalizedInput = {}, normalizedProp, prop;
		for (prop in inputObject) if (hasOwnProp(inputObject, prop)) {
			normalizedProp = normalizeUnits(prop);
			if (normalizedProp) normalizedInput[normalizedProp] = inputObject[prop];
		}
		return normalizedInput;
	}
	var priorities = {
		date: 9,
		day: 11,
		weekday: 11,
		isoWeekday: 11,
		dayOfYear: 4,
		hour: 13,
		millisecond: 16,
		minute: 14,
		month: 8,
		quarter: 7,
		second: 15,
		weekYear: 1,
		isoWeekYear: 1,
		week: 5,
		isoWeek: 5,
		year: 1
	};
	function getPrioritizedUnits(unitsObj) {
		var units = [], u;
		for (u in unitsObj) if (hasOwnProp(unitsObj, u)) units.push({
			unit: u,
			priority: priorities[u]
		});
		units.sort(function(a, b) {
			return a.priority - b.priority;
		});
		return units;
	}
	function zeroFill(number, targetLength, forceSign) {
		var absNumber = "" + Math.abs(number), zerosToFill = targetLength - absNumber.length;
		return (number >= 0 ? forceSign ? "+" : "" : "-") + Math.pow(10, Math.max(0, zerosToFill)).toString().substr(1) + absNumber;
	}
	var formattingTokens = /(\[[^\[]*\])|(\\e)|(\\)?(eHHmm|[Hh]mm(ss)?|Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Qo?|N{1,5}|YYYYYY|YYYYY|YYYY|YY|y{2,4}|yo?|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|kk?|mm?|ss?|S{1,9}|x|X|zz?|ZZ?|.)/g;
	var localFormattingTokens = /(\[[^\[]*\])|(\\)?(LTS|LT|LL?L?L?|l{1,4})/g;
	var formatFunctions = {};
	var formatTokenFunctions = {};
	function addFormatToken(token, padded, ordinal, callback) {
		var func = callback;
		if (typeof callback === "string") func = function() {
			return this[callback]();
		};
		if (token) formatTokenFunctions[token] = func;
		if (padded) formatTokenFunctions[padded[0]] = function() {
			return zeroFill(func.apply(this, arguments), padded[1], padded[2]);
		};
		if (ordinal) formatTokenFunctions[ordinal] = function() {
			return this.localeData().ordinal(func.apply(this, arguments), token);
		};
	}
	function removeFormattingTokens(input) {
		if (input.match(/\[[\s\S]/)) return input.replace(/^\[|\]$/g, "");
		return input.replace(/\\/g, "");
	}
	function makeFormatFunction(format) {
		var array = format.match(formattingTokens), i = 0, length = array.length;
		for (; i < length; i++) if (formatTokenFunctions[array[i]]) array[i] = formatTokenFunctions[array[i]];
		else array[i] = removeFormattingTokens(array[i]);
		return function(mom) {
			var output = "", i = 0;
			for (; i < length; i++) output += isFunction(array[i]) ? array[i].call(mom, format) : array[i];
			return output;
		};
	}
	function formatMoment(m, format) {
		if (!m.isValid()) return m.localeData().invalidDate();
		format = expandFormat(format, m.localeData());
		var cacheKey = "$" + format;
		if (!hasOwnProp(formatFunctions, cacheKey)) formatFunctions[cacheKey] = makeFormatFunction(format);
		return formatFunctions[cacheKey](m);
	}
	function expandFormat(format, locale) {
		var i = 5;
		function replaceLongDateFormatTokens(input) {
			return locale.longDateFormat(input) || input;
		}
		localFormattingTokens.lastIndex = 0;
		while (i >= 0 && localFormattingTokens.test(format)) {
			format = format.replace(localFormattingTokens, replaceLongDateFormatTokens);
			localFormattingTokens.lastIndex = 0;
			i -= 1;
		}
		return format;
	}
	var match1 = /\d/;
	var match2 = /\d\d/;
	var match3 = /\d{3}/;
	var match4 = /\d{4}/;
	var match6 = /[+-]?\d{6}/;
	var match1to2 = /\d\d?/;
	var match3to4 = /\d\d\d\d?/;
	var match5to6 = /\d\d\d\d\d\d?/;
	var match1to3 = /\d{1,3}/;
	var match1to4 = /\d{1,4}/;
	var match1to6 = /[+-]?\d{1,6}/;
	var matchUnsigned = /\d+/;
	var matchSigned = /[+-]?\d+/;
	var matchOffset = /Z|[+-]\d\d:?\d\d/gi;
	var matchShortOffset = /Z|[+-]\d\d(?::?\d\d)?/gi;
	var matchTimestamp = /[+-]?\d+(\.\d{1,3})?/;
	var matchWord = /[0-9]{0,256}['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFF07\uFF10-\uFFEF]{1,256}|[\u0600-\u06FF\/]{1,256}(\s*?[\u0600-\u06FF]{1,256}){1,2}/i;
	var match1to2NoLeadingZero = /^[1-9]\d?/;
	var match1to2HasZero = /^([1-9]\d|\d)/;
	var regexes = {};
	function addRegexToken(token, regex, strictRegex) {
		regexes[token] = isFunction(regex) ? regex : function(isStrict, localeData) {
			return isStrict && strictRegex ? strictRegex : regex;
		};
	}
	function getParseRegexForToken(token, config) {
		if (!hasOwnProp(regexes, token)) return new RegExp(unescapeFormat(token));
		return regexes[token](config._strict, config._locale);
	}
	function unescapeFormat(s) {
		return regexEscape(s.replace("\\", "").replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g, function(matched, p1, p2, p3, p4) {
			return p1 || p2 || p3 || p4;
		}));
	}
	function regexEscape(s) {
		return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&");
	}
	function absFloor(number) {
		if (number < 0) return Math.ceil(number) || 0;
		else return Math.floor(number);
	}
	function toInt(argumentForCoercion) {
		var coercedNumber = +argumentForCoercion, value = 0;
		if (coercedNumber !== 0 && isFinite(coercedNumber)) value = absFloor(coercedNumber);
		return value;
	}
	var tokens = {};
	function addParseToken(token, callback) {
		var i, func = callback, tokenLen;
		if (typeof token === "string") token = [token];
		if (isNumber(callback)) func = function(input, array) {
			array[callback] = toInt(input);
		};
		tokenLen = token.length;
		for (i = 0; i < tokenLen; i++) tokens[token[i]] = func;
	}
	function addWeekParseToken(token, callback) {
		addParseToken(token, function(input, array, config, token) {
			config._w = config._w || {};
			callback(input, config._w, config, token);
		});
	}
	function addTimeToArrayFromToken(token, input, config) {
		if (input != null && hasOwnProp(tokens, token)) tokens[token](input, config._a, config, token);
	}
	function isLeapYear(year) {
		return year % 4 === 0 && year % 100 !== 0 || year % 400 === 0;
	}
	var YEAR = 0;
	var MONTH = 1;
	var DATE = 2;
	var HOUR = 3;
	var MINUTE = 4;
	var SECOND = 5;
	var MILLISECOND = 6;
	var WEEK = 7;
	var WEEKDAY = 8;
	addFormatToken("Y", 0, 0, function() {
		var y = this.year();
		return y <= 9999 ? zeroFill(y, 4) : "+" + y;
	});
	addFormatToken(0, ["YY", 2], 0, function() {
		return this.year() % 100;
	});
	addFormatToken(0, ["YYYY", 4], 0, "year");
	addFormatToken(0, ["YYYYY", 5], 0, "year");
	addFormatToken(0, [
		"YYYYYY",
		6,
		true
	], 0, "year");
	addRegexToken("Y", matchSigned);
	addRegexToken("YY", match1to2, match2);
	addRegexToken("YYYY", match1to4, match4);
	addRegexToken("YYYYY", match1to6, match6);
	addRegexToken("YYYYYY", match1to6, match6);
	addParseToken(["YYYYY", "YYYYYY"], YEAR);
	addParseToken("YYYY", function(input, array) {
		array[YEAR] = input.length === 2 ? hooks.parseTwoDigitYear(input) : toInt(input);
	});
	addParseToken("YY", function(input, array) {
		array[YEAR] = hooks.parseTwoDigitYear(input);
	});
	addParseToken("Y", function(input, array) {
		array[YEAR] = parseInt(input, 10);
	});
	function daysInYear(year) {
		return isLeapYear(year) ? 366 : 365;
	}
	hooks.parseTwoDigitYear = function(input) {
		return toInt(input) + (toInt(input) > 68 ? 1900 : 2e3);
	};
	var getSetYear = makeGetSet("FullYear", true);
	function getIsLeapYear() {
		return isLeapYear(this.year());
	}
	function makeGetSet(unit, keepTime) {
		return function(value) {
			if (value != null) {
				set$1(this, unit, value);
				hooks.updateOffset(this, keepTime);
				return this;
			} else return get$2(this, unit);
		};
	}
	function get$2(mom, unit) {
		if (!mom.isValid()) return NaN;
		var d = mom._d, isUTC = mom._isUTC;
		switch (unit) {
			case "Milliseconds": return isUTC ? d.getUTCMilliseconds() : d.getMilliseconds();
			case "Seconds": return isUTC ? d.getUTCSeconds() : d.getSeconds();
			case "Minutes": return isUTC ? d.getUTCMinutes() : d.getMinutes();
			case "Hours": return isUTC ? d.getUTCHours() : d.getHours();
			case "Date": return isUTC ? d.getUTCDate() : d.getDate();
			case "Day": return isUTC ? d.getUTCDay() : d.getDay();
			case "Month": return isUTC ? d.getUTCMonth() : d.getMonth();
			case "FullYear": return isUTC ? d.getUTCFullYear() : d.getFullYear();
			default: return NaN;
		}
	}
	function set$1(mom, unit, value) {
		var d, isUTC, year, month, date;
		if (!mom.isValid() || isNaN(value)) return;
		d = mom._d;
		isUTC = mom._isUTC;
		switch (unit) {
			case "Milliseconds":
				isUTC ? d.setUTCMilliseconds(value) : d.setMilliseconds(value);
				return;
			case "Seconds":
				isUTC ? d.setUTCSeconds(value) : d.setSeconds(value);
				return;
			case "Minutes":
				isUTC ? d.setUTCMinutes(value) : d.setMinutes(value);
				return;
			case "Hours":
				isUTC ? d.setUTCHours(value) : d.setHours(value);
				return;
			case "Date":
				isUTC ? d.setUTCDate(value) : d.setDate(value);
				return;
			case "FullYear": break;
			default: return;
		}
		year = value;
		month = mom.month();
		date = mom.date();
		date = date === 29 && month === 1 && !isLeapYear(year) ? 28 : date;
		isUTC ? d.setUTCFullYear(year, month, date) : d.setFullYear(year, month, date);
	}
	function stringGet(units) {
		units = normalizeUnits(units);
		if (isFunction(this[units])) return this[units]();
		return this;
	}
	function stringSet(units, value) {
		if (typeof units === "object") {
			units = normalizeObjectUnits(units);
			var prioritized = getPrioritizedUnits(units), i, prioritizedLen = prioritized.length;
			for (i = 0; i < prioritizedLen; i++) this[prioritized[i].unit](units[prioritized[i].unit]);
		} else {
			units = normalizeUnits(units);
			if (isFunction(this[units])) return this[units](value);
		}
		return this;
	}
	function mod$1(n, x) {
		return (n % x + x) % x;
	}
	var indexOf;
	if (Array.prototype.indexOf) indexOf = Array.prototype.indexOf;
	else indexOf = function(o) {
		var i = 0;
		for (; i < this.length; ++i) if (this[i] === o) return i;
		return -1;
	};
	function daysInMonth(year, month) {
		if (isNaN(year) || isNaN(month)) return NaN;
		var modMonth = mod$1(month, 12);
		year += (month - modMonth) / 12;
		return modMonth === 1 ? isLeapYear(year) ? 29 : 28 : 31 - modMonth % 7 % 2;
	}
	addFormatToken("M", ["MM", 2], "Mo", function() {
		return this.month() + 1;
	});
	addFormatToken("MMM", 0, 0, function(format) {
		return this.localeData().monthsShort(this, format);
	});
	addFormatToken("MMMM", 0, 0, function(format) {
		return this.localeData().months(this, format);
	});
	addRegexToken("M", match1to2, match1to2NoLeadingZero);
	addRegexToken("MM", match1to2, match2);
	addRegexToken("MMM", function(isStrict, locale) {
		return locale.monthsShortRegex(isStrict);
	});
	addRegexToken("MMMM", function(isStrict, locale) {
		return locale.monthsRegex(isStrict);
	});
	addParseToken(["M", "MM"], function(input, array) {
		array[MONTH] = toInt(input) - 1;
	});
	addParseToken(["MMM", "MMMM"], function(input, array, config, token) {
		var month = config._locale.monthsParse(input, token, config._strict);
		if (month != null) array[MONTH] = month;
		else getParsingFlags(config).invalidMonth = input;
	});
	var defaultLocaleMonths = "January_February_March_April_May_June_July_August_September_October_November_December".split("_");
	var defaultLocaleMonthsShort = "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_");
	var MONTHS_IN_FORMAT = /D[oD]?(\[[^\[\]]*\]|\s)+MMMM?/;
	var defaultMonthsShortRegex = matchWord;
	var defaultMonthsRegex = matchWord;
	var monthsParseProperties = [
		"monthsParse",
		"longMonthsParse",
		"shortMonthsParse",
		"monthsRegex",
		"monthsShortRegex",
		"monthsStrictRegex",
		"monthsShortStrictRegex"
	];
	function clearMonthsParseCache(locale, config) {
		var i = 0, prop;
		for (; i < monthsParseProperties.length; i++) {
			prop = monthsParseProperties[i];
			if (!hasOwnProp(config, prop)) delete locale["_" + prop];
		}
	}
	function localeMonths(m, format) {
		if (!m) return isArray(this._months) ? this._months : this._months["standalone"];
		return isArray(this._months) ? this._months[m.month()] : this._months[(this._months.isFormat || MONTHS_IN_FORMAT).test(format) ? "format" : "standalone"][m.month()];
	}
	function localeMonthsShort(m, format) {
		if (!m) return isArray(this._monthsShort) ? this._monthsShort : this._monthsShort["standalone"];
		return isArray(this._monthsShort) ? this._monthsShort[m.month()] : this._monthsShort[MONTHS_IN_FORMAT.test(format) ? "format" : "standalone"][m.month()];
	}
	function handleStrictParse$1(monthName, format, strict) {
		var i, ii, mom, llc = monthName.toLocaleLowerCase();
		if (!this._monthsParse) {
			this._monthsParse = [];
			this._longMonthsParse = [];
			this._shortMonthsParse = [];
			for (i = 0; i < 12; ++i) {
				mom = createUTC([2e3, i]);
				this._shortMonthsParse[i] = this.monthsShort(mom, "").toLocaleLowerCase();
				this._longMonthsParse[i] = this.months(mom, "").toLocaleLowerCase();
			}
		}
		if (strict) {
			if (format === "MMM") {
				ii = indexOf.call(this._shortMonthsParse, llc);
				return ii !== -1 ? ii : null;
			} else {
				ii = indexOf.call(this._longMonthsParse, llc);
				return ii !== -1 ? ii : null;
			}
		} else if (format === "MMM") {
			ii = indexOf.call(this._shortMonthsParse, llc);
			if (ii !== -1) return ii;
			ii = indexOf.call(this._longMonthsParse, llc);
			return ii !== -1 ? ii : null;
		} else {
			ii = indexOf.call(this._longMonthsParse, llc);
			if (ii !== -1) return ii;
			ii = indexOf.call(this._shortMonthsParse, llc);
			return ii !== -1 ? ii : null;
		}
	}
	function localeMonthsParse(monthName, format, strict) {
		var i, mom, regex;
		if (this._monthsParseExact) return handleStrictParse$1.call(this, monthName, format, strict);
		if (!this._monthsParse) {
			this._monthsParse = [];
			this._longMonthsParse = [];
			this._shortMonthsParse = [];
		}
		for (i = 0; i < 12; i++) {
			mom = createUTC([2e3, i]);
			if (strict && !this._longMonthsParse[i]) {
				this._longMonthsParse[i] = new RegExp("^" + this.months(mom, "").replace(".", "") + "$", "i");
				this._shortMonthsParse[i] = new RegExp("^" + this.monthsShort(mom, "").replace(".", "") + "$", "i");
			}
			if (!strict && !this._monthsParse[i]) {
				regex = "^" + this.months(mom, "") + "|^" + this.monthsShort(mom, "");
				this._monthsParse[i] = new RegExp(regex.replace(".", ""), "i");
			}
			if (strict && format === "MMMM" && this._longMonthsParse[i].test(monthName)) return i;
			else if (strict && format === "MMM" && this._shortMonthsParse[i].test(monthName)) return i;
			else if (!strict && this._monthsParse[i].test(monthName)) return i;
		}
	}
	function setMonth(mom, value) {
		if (!mom.isValid()) return mom;
		if (typeof value === "string") {
			if (/^\d+$/.test(value)) value = toInt(value);
			else {
				value = mom.localeData().monthsParse(value);
				if (!isNumber(value)) return mom;
			}
		}
		var month = value, date = mom.date();
		date = date < 29 ? date : Math.min(date, daysInMonth(mom.year(), month));
		mom._isUTC ? mom._d.setUTCMonth(month, date) : mom._d.setMonth(month, date);
		return mom;
	}
	function getSetMonth(value) {
		if (value != null) {
			setMonth(this, value);
			hooks.updateOffset(this, true);
			return this;
		} else return get$2(this, "Month");
	}
	function getDaysInMonth() {
		return daysInMonth(this.year(), this.month());
	}
	function monthsShortRegex(isStrict) {
		if (this._monthsParseExact) {
			if (!hasOwnProp(this, "_monthsRegex")) computeMonthsParse.call(this);
			if (isStrict) return this._monthsShortStrictRegex;
			else return this._monthsShortRegex;
		} else {
			if (!hasOwnProp(this, "_monthsShortRegex")) this._monthsShortRegex = defaultMonthsShortRegex;
			return this._monthsShortStrictRegex && isStrict ? this._monthsShortStrictRegex : this._monthsShortRegex;
		}
	}
	function monthsRegex(isStrict) {
		if (this._monthsParseExact) {
			if (!hasOwnProp(this, "_monthsRegex")) computeMonthsParse.call(this);
			if (isStrict) return this._monthsStrictRegex;
			else return this._monthsRegex;
		} else {
			if (!hasOwnProp(this, "_monthsRegex")) this._monthsRegex = defaultMonthsRegex;
			return this._monthsStrictRegex && isStrict ? this._monthsStrictRegex : this._monthsRegex;
		}
	}
	function computeMonthsParse() {
		function cmpLenRev(a, b) {
			return b.length - a.length;
		}
		var shortPieces = [], longPieces = [], mixedPieces = [], i = 0, mom, shortP, longP;
		for (; i < 12; i++) {
			mom = createUTC([2e3, i]);
			shortP = regexEscape(this.monthsShort(mom, ""));
			longP = regexEscape(this.months(mom, ""));
			shortPieces.push(shortP);
			longPieces.push(longP);
			mixedPieces.push(longP);
			mixedPieces.push(shortP);
		}
		shortPieces.sort(cmpLenRev);
		longPieces.sort(cmpLenRev);
		mixedPieces.sort(cmpLenRev);
		this._monthsRegex = new RegExp("^(" + mixedPieces.join("|") + ")", "i");
		this._monthsShortRegex = this._monthsRegex;
		this._monthsStrictRegex = new RegExp("^(" + longPieces.join("|") + ")", "i");
		this._monthsShortStrictRegex = new RegExp("^(" + shortPieces.join("|") + ")", "i");
	}
	addFormatToken("d", 0, "do", "day");
	addFormatToken("dd", 0, 0, function(format) {
		return this.localeData().weekdaysMin(this, format);
	});
	addFormatToken("ddd", 0, 0, function(format) {
		return this.localeData().weekdaysShort(this, format);
	});
	addFormatToken("dddd", 0, 0, function(format) {
		return this.localeData().weekdays(this, format);
	});
	addFormatToken("e", 0, 0, "weekday");
	addFormatToken("E", 0, 0, "isoWeekday");
	addFormatToken("eHHmm", 0, 0, function() {
		return "" + this.weekday() + zeroFill(this.hours(), 2) + zeroFill(this.minutes(), 2);
	});
	addRegexToken("d", match1to2);
	addRegexToken("e", match1to2);
	addRegexToken("E", match1to2);
	addRegexToken("eHHmm", match5to6);
	addRegexToken("dd", function(isStrict, locale) {
		return locale.weekdaysMinRegex(isStrict);
	});
	addRegexToken("ddd", function(isStrict, locale) {
		return locale.weekdaysShortRegex(isStrict);
	});
	addRegexToken("dddd", function(isStrict, locale) {
		return locale.weekdaysRegex(isStrict);
	});
	addWeekParseToken([
		"dd",
		"ddd",
		"dddd"
	], function(input, week, config, token) {
		var weekday = config._locale.weekdaysParse(input, token, config._strict);
		if (weekday != null) week.d = weekday;
		else getParsingFlags(config).invalidWeekday = input;
	});
	addWeekParseToken([
		"d",
		"e",
		"E"
	], function(input, week, config, token) {
		week[token] = toInt(input);
	});
	addWeekParseToken("eHHmm", function(input, week, config) {
		var weekdayEnd = input.length - 4;
		week.e = toInt(input.substr(0, weekdayEnd));
		config._a[HOUR] = toInt(input.substr(weekdayEnd, 2));
		config._a[MINUTE] = toInt(input.substr(weekdayEnd + 2));
	});
	function parseWeekday(input, locale) {
		if (typeof input !== "string") return input;
		if (!isNaN(input)) return parseInt(input, 10);
		input = locale.weekdaysParse(input);
		if (typeof input === "number") return input;
		return null;
	}
	function parseIsoWeekday(input, locale) {
		if (typeof input === "string") return locale.weekdaysParse(input) % 7 || 7;
		return isNaN(input) ? null : input;
	}
	function shiftWeekdays(ws, n) {
		return ws.slice(n, 7).concat(ws.slice(0, n));
	}
	var defaultLocaleWeekdays = "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_");
	var defaultLocaleWeekdaysShort = "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_");
	var defaultLocaleWeekdaysMin = "Su_Mo_Tu_We_Th_Fr_Sa".split("_");
	var defaultWeekdaysRegex = matchWord;
	var defaultWeekdaysShortRegex = matchWord;
	var defaultWeekdaysMinRegex = matchWord;
	var weekdaysParseProperties = [
		"weekdaysParse",
		"fullWeekdaysParse",
		"shortWeekdaysParse",
		"minWeekdaysParse",
		"weekdaysRegex",
		"weekdaysShortRegex",
		"weekdaysMinRegex",
		"weekdaysStrictRegex",
		"weekdaysShortStrictRegex",
		"weekdaysMinStrictRegex"
	];
	function clearWeekdaysParseCache(locale, config) {
		var i = 0, prop;
		for (; i < weekdaysParseProperties.length; i++) {
			prop = weekdaysParseProperties[i];
			if (!hasOwnProp(config, prop)) delete locale["_" + prop];
		}
	}
	function localeWeekdays(m, format) {
		var weekdays = isArray(this._weekdays) ? this._weekdays : this._weekdays[m && m !== true && this._weekdays.isFormat.test(format) ? "format" : "standalone"];
		return m === true ? shiftWeekdays(weekdays, this._week.dow) : m ? weekdays[m.day()] : weekdays;
	}
	function localeWeekdaysShort(m) {
		return m === true ? shiftWeekdays(this._weekdaysShort, this._week.dow) : m ? this._weekdaysShort[m.day()] : this._weekdaysShort;
	}
	function localeWeekdaysMin(m) {
		return m === true ? shiftWeekdays(this._weekdaysMin, this._week.dow) : m ? this._weekdaysMin[m.day()] : this._weekdaysMin;
	}
	function handleStrictParse(weekdayName, format, strict) {
		var i, ii, mom, llc = weekdayName.toLocaleLowerCase();
		if (!this._weekdaysParse) {
			this._weekdaysParse = [];
			this._shortWeekdaysParse = [];
			this._minWeekdaysParse = [];
			for (i = 0; i < 7; ++i) {
				mom = createUTC([2e3, 1]).day(i);
				this._minWeekdaysParse[i] = this.weekdaysMin(mom, "").toLocaleLowerCase();
				this._shortWeekdaysParse[i] = this.weekdaysShort(mom, "").toLocaleLowerCase();
				this._weekdaysParse[i] = this.weekdays(mom, "").toLocaleLowerCase();
			}
		}
		if (strict) {
			if (format === "dddd") {
				ii = indexOf.call(this._weekdaysParse, llc);
				return ii !== -1 ? ii : null;
			} else if (format === "ddd") {
				ii = indexOf.call(this._shortWeekdaysParse, llc);
				return ii !== -1 ? ii : null;
			} else {
				ii = indexOf.call(this._minWeekdaysParse, llc);
				return ii !== -1 ? ii : null;
			}
		} else if (format === "dddd") {
			ii = indexOf.call(this._weekdaysParse, llc);
			if (ii !== -1) return ii;
			ii = indexOf.call(this._shortWeekdaysParse, llc);
			if (ii !== -1) return ii;
			ii = indexOf.call(this._minWeekdaysParse, llc);
			return ii !== -1 ? ii : null;
		} else if (format === "ddd") {
			ii = indexOf.call(this._shortWeekdaysParse, llc);
			if (ii !== -1) return ii;
			ii = indexOf.call(this._weekdaysParse, llc);
			if (ii !== -1) return ii;
			ii = indexOf.call(this._minWeekdaysParse, llc);
			return ii !== -1 ? ii : null;
		} else {
			ii = indexOf.call(this._minWeekdaysParse, llc);
			if (ii !== -1) return ii;
			ii = indexOf.call(this._weekdaysParse, llc);
			if (ii !== -1) return ii;
			ii = indexOf.call(this._shortWeekdaysParse, llc);
			return ii !== -1 ? ii : null;
		}
	}
	function localeWeekdaysParse(weekdayName, format, strict) {
		var i, mom, regex;
		if (this._weekdaysParseExact) return handleStrictParse.call(this, weekdayName, format, strict);
		if (!this._weekdaysParse) {
			this._weekdaysParse = [];
			this._minWeekdaysParse = [];
			this._shortWeekdaysParse = [];
			this._fullWeekdaysParse = [];
		}
		for (i = 0; i < 7; i++) {
			mom = createUTC([2e3, 1]).day(i);
			if (strict && !this._fullWeekdaysParse[i]) {
				this._fullWeekdaysParse[i] = new RegExp("^" + this.weekdays(mom, "").replace(".", "\\.?") + "$", "i");
				this._shortWeekdaysParse[i] = new RegExp("^" + this.weekdaysShort(mom, "").replace(".", "\\.?") + "$", "i");
				this._minWeekdaysParse[i] = new RegExp("^" + this.weekdaysMin(mom, "").replace(".", "\\.?") + "$", "i");
			}
			if (!this._weekdaysParse[i]) {
				regex = "^" + this.weekdays(mom, "") + "|^" + this.weekdaysShort(mom, "") + "|^" + this.weekdaysMin(mom, "");
				this._weekdaysParse[i] = new RegExp(regex.replace(".", ""), "i");
			}
			if (strict && format === "dddd" && this._fullWeekdaysParse[i].test(weekdayName)) return i;
			else if (strict && format === "ddd" && this._shortWeekdaysParse[i].test(weekdayName)) return i;
			else if (strict && format === "dd" && this._minWeekdaysParse[i].test(weekdayName)) return i;
			else if (!strict && this._weekdaysParse[i].test(weekdayName)) return i;
		}
	}
	function getSetDayOfWeek(input) {
		if (!this.isValid()) return input != null ? this : NaN;
		var day = get$2(this, "Day");
		if (input != null) {
			input = parseWeekday(input, this.localeData());
			return this.add(input - day, "d");
		} else return day;
	}
	function getSetLocaleDayOfWeek(input) {
		if (!this.isValid()) return input != null ? this : NaN;
		var weekday = (this.day() + 7 - this.localeData()._week.dow) % 7;
		return input == null ? weekday : this.add(input - weekday, "d");
	}
	function getSetISODayOfWeek(input) {
		if (!this.isValid()) return input != null ? this : NaN;
		if (input != null) {
			var weekday = parseIsoWeekday(input, this.localeData());
			return this.day(this.day() % 7 ? weekday : weekday - 7);
		} else return this.day() || 7;
	}
	function weekdaysRegex(isStrict) {
		if (this._weekdaysParseExact) {
			if (!hasOwnProp(this, "_weekdaysRegex")) computeWeekdaysParse.call(this);
			if (isStrict) return this._weekdaysStrictRegex;
			else return this._weekdaysRegex;
		} else {
			if (!hasOwnProp(this, "_weekdaysRegex")) this._weekdaysRegex = defaultWeekdaysRegex;
			return this._weekdaysStrictRegex && isStrict ? this._weekdaysStrictRegex : this._weekdaysRegex;
		}
	}
	function weekdaysShortRegex(isStrict) {
		if (this._weekdaysParseExact) {
			if (!hasOwnProp(this, "_weekdaysRegex")) computeWeekdaysParse.call(this);
			if (isStrict) return this._weekdaysShortStrictRegex;
			else return this._weekdaysShortRegex;
		} else {
			if (!hasOwnProp(this, "_weekdaysShortRegex")) this._weekdaysShortRegex = defaultWeekdaysShortRegex;
			return this._weekdaysShortStrictRegex && isStrict ? this._weekdaysShortStrictRegex : this._weekdaysShortRegex;
		}
	}
	function weekdaysMinRegex(isStrict) {
		if (this._weekdaysParseExact) {
			if (!hasOwnProp(this, "_weekdaysRegex")) computeWeekdaysParse.call(this);
			if (isStrict) return this._weekdaysMinStrictRegex;
			else return this._weekdaysMinRegex;
		} else {
			if (!hasOwnProp(this, "_weekdaysMinRegex")) this._weekdaysMinRegex = defaultWeekdaysMinRegex;
			return this._weekdaysMinStrictRegex && isStrict ? this._weekdaysMinStrictRegex : this._weekdaysMinRegex;
		}
	}
	function computeWeekdaysParse() {
		function cmpLenRev(a, b) {
			return b.length - a.length;
		}
		var minPieces = [], shortPieces = [], longPieces = [], mixedPieces = [], i = 0, mom, minp, shortp, longp;
		for (; i < 7; i++) {
			mom = createUTC([2e3, 1]).day(i);
			minp = regexEscape(this.weekdaysMin(mom, ""));
			shortp = regexEscape(this.weekdaysShort(mom, ""));
			longp = regexEscape(this.weekdays(mom, ""));
			minPieces.push(minp);
			shortPieces.push(shortp);
			longPieces.push(longp);
			mixedPieces.push(minp);
			mixedPieces.push(shortp);
			mixedPieces.push(longp);
		}
		minPieces.sort(cmpLenRev);
		shortPieces.sort(cmpLenRev);
		longPieces.sort(cmpLenRev);
		mixedPieces.sort(cmpLenRev);
		this._weekdaysRegex = new RegExp("^(" + mixedPieces.join("|") + ")", "i");
		this._weekdaysShortRegex = this._weekdaysRegex;
		this._weekdaysMinRegex = this._weekdaysRegex;
		this._weekdaysStrictRegex = new RegExp("^(" + longPieces.join("|") + ")", "i");
		this._weekdaysShortStrictRegex = new RegExp("^(" + shortPieces.join("|") + ")", "i");
		this._weekdaysMinStrictRegex = new RegExp("^(" + minPieces.join("|") + ")", "i");
	}
	function set(config) {
		var prop, i;
		clearMonthsParseCache(this, config);
		clearWeekdaysParseCache(this, config);
		for (i in config) if (hasOwnProp(config, i)) {
			prop = config[i];
			if (isFunction(prop)) this[i] = prop;
			else this["_" + i] = prop;
		}
		this._config = config;
		this._dayOfMonthOrdinalParseLenient = new RegExp((this._dayOfMonthOrdinalParse.source || this._ordinalParse.source) + "|" + /\d{1,2}/.source);
	}
	function mergeConfigs(parentConfig, childConfig) {
		var res = extend({}, parentConfig), prop;
		for (prop in childConfig) if (hasOwnProp(childConfig, prop)) {
			if (isObject(parentConfig[prop]) && isObject(childConfig[prop])) {
				res[prop] = {};
				extend(res[prop], parentConfig[prop]);
				extend(res[prop], childConfig[prop]);
			} else if (childConfig[prop] != null) res[prop] = childConfig[prop];
			else delete res[prop];
		}
		for (prop in parentConfig) if (hasOwnProp(parentConfig, prop) && !hasOwnProp(childConfig, prop) && isObject(parentConfig[prop])) res[prop] = extend({}, res[prop]);
		return res;
	}
	function Locale(config) {
		if (config != null) this.set(config);
	}
	var keys;
	if (Object.keys) keys = Object.keys;
	else keys = function(obj) {
		var i, res = [];
		for (i in obj) if (hasOwnProp(obj, i)) res.push(i);
		return res;
	};
	var defaultCalendar = {
		sameDay: "[Today at] LT",
		nextDay: "[Tomorrow at] LT",
		nextWeek: "dddd [at] LT",
		lastDay: "[Yesterday at] LT",
		lastWeek: "[Last] dddd [at] LT",
		sameElse: "L"
	};
	function calendar$1(key, mom, now) {
		var output = this._calendar[key] || this._calendar["sameElse"];
		return isFunction(output) ? output.call(mom, now) : output;
	}
	var defaultLongDateFormat = {
		LTS: "h:mm:ss A",
		LT: "h:mm A",
		L: "MM/DD/YYYY",
		LL: "MMMM D, YYYY",
		LLL: "MMMM D, YYYY h:mm A",
		LLLL: "dddd, MMMM D, YYYY h:mm A"
	};
	function longDateFormat(key) {
		var format = this._longDateFormat[key], formatUpper = this._longDateFormat[key.toUpperCase()], formatCache = this._longDateFormatCache;
		if (format || !formatUpper) return format;
		if (formatCache && formatCache[key] && formatCache[key].formatUpper === formatUpper) return formatCache[key].format;
		format = formatUpper.match(formattingTokens).map(function(tok) {
			if (tok === "MMMM" || tok === "MM" || tok === "DD" || tok === "dddd") return tok.slice(1);
			return tok;
		}).join("");
		if (!formatCache) formatCache = this._longDateFormatCache = {};
		formatCache[key] = {
			formatUpper,
			format
		};
		return format;
	}
	var defaultInvalidDate = "Invalid date";
	function invalidDate() {
		return this._invalidDate;
	}
	var defaultOrdinal = "%d";
	var defaultDayOfMonthOrdinalParse = /\d{1,2}/;
	function ordinal(number) {
		return this._ordinal.replace("%d", number);
	}
	var defaultRelativeTime = {
		future: "in %s",
		past: "%s ago",
		s: "a few seconds",
		ss: "%d seconds",
		m: "a minute",
		mm: "%d minutes",
		h: "an hour",
		hh: "%d hours",
		d: "a day",
		dd: "%d days",
		w: "a week",
		ww: "%d weeks",
		M: "a month",
		MM: "%d months",
		y: "a year",
		yy: "%d years"
	};
	function relativeTimeWithoutPostformat(number, withoutSuffix, string, isFuture) {
		var output = this._relativeTime[string];
		return isFunction(output) ? output(number, withoutSuffix, string, isFuture) : output.replace(/%d/i, number);
	}
	function relativeTime$1(number, withoutSuffix, string, isFuture) {
		return this.postformat(relativeTimeWithoutPostformat.call(this, number, withoutSuffix, string, isFuture));
	}
	function pastFutureWithoutPostformat(diff, output) {
		var format = this._relativeTime[diff > 0 ? "future" : "past"];
		return isFunction(format) ? format(output) : format.replace(/%s/i, output);
	}
	function pastFuture(diff, output) {
		return this.postformat(pastFutureWithoutPostformat.call(this, diff, output));
	}
	function createDate(y, m, d, h, M, s, ms) {
		var date;
		if (y < 100 && y >= 0) {
			date = new Date(y + 400, m, d, h, M, s, ms);
			if (isFinite(date.getFullYear())) date.setFullYear(y);
		} else date = new Date(y, m, d, h, M, s, ms);
		return date;
	}
	function createUTCDate(y) {
		var date, args;
		if (y < 100 && y >= 0) {
			args = Array.prototype.slice.call(arguments);
			args[0] = y + 400;
			date = new Date(Date.UTC.apply(null, args));
			if (isFinite(date.getUTCFullYear())) date.setUTCFullYear(y);
		} else date = new Date(Date.UTC.apply(null, arguments));
		return date;
	}
	function firstWeekOffset(year, dow, doy) {
		var fwd = 7 + dow - doy;
		return -((7 + createUTCDate(year, 0, fwd).getUTCDay() - dow) % 7) + fwd - 1;
	}
	function dayOfYearFromWeeks(year, week, weekday, dow, doy) {
		var localWeekday = (7 + weekday - dow) % 7, weekOffset = firstWeekOffset(year, dow, doy), dayOfYear = 1 + 7 * (week - 1) + localWeekday + weekOffset, resYear, resDayOfYear;
		if (dayOfYear <= 0) {
			resYear = year - 1;
			resDayOfYear = daysInYear(resYear) + dayOfYear;
		} else if (dayOfYear > daysInYear(year)) {
			resYear = year + 1;
			resDayOfYear = dayOfYear - daysInYear(year);
		} else {
			resYear = year;
			resDayOfYear = dayOfYear;
		}
		return {
			year: resYear,
			dayOfYear: resDayOfYear
		};
	}
	function weekOfYearFromDayOfYear(year, dayOfYear, dow, doy) {
		var weekOffset = firstWeekOffset(year, dow, doy), week = Math.floor((dayOfYear - weekOffset - 1) / 7) + 1, resWeek, resYear;
		if (week < 1) {
			resYear = year - 1;
			resWeek = week + weeksInYear(resYear, dow, doy);
		} else if (week > weeksInYear(year, dow, doy)) {
			resWeek = week - weeksInYear(year, dow, doy);
			resYear = year + 1;
		} else {
			resYear = year;
			resWeek = week;
		}
		return {
			week: resWeek,
			year: resYear
		};
	}
	function weekOfYear(mom, dow, doy) {
		return weekOfYearFromDayOfYear(mom.year(), mom.dayOfYear(), dow, doy);
	}
	function weekOfYearFromDate(year, month, date, dow, doy) {
		return weekOfYearFromDayOfYear(year, Math.round((createUTCDate(year, month, date) - createUTCDate(year, 0, 1)) / 864e5) + 1, dow, doy);
	}
	function weeksInYear(year, dow, doy) {
		var weekOffset = firstWeekOffset(year, dow, doy), weekOffsetNext = firstWeekOffset(year + 1, dow, doy);
		return (daysInYear(year) - weekOffset + weekOffsetNext) / 7;
	}
	addFormatToken("w", ["ww", 2], "wo", "week");
	addFormatToken("W", ["WW", 2], "Wo", "isoWeek");
	addRegexToken("w", match1to2, match1to2NoLeadingZero);
	addRegexToken("ww", match1to2, match2);
	addRegexToken("W", match1to2, match1to2NoLeadingZero);
	addRegexToken("WW", match1to2, match2);
	addWeekParseToken([
		"w",
		"ww",
		"W",
		"WW"
	], function(input, week, config, token) {
		week[token.substr(0, 1)] = toInt(input);
	});
	function localeWeek(mom) {
		return weekOfYear(mom, this._week.dow, this._week.doy).week;
	}
	var defaultLocaleWeek = {
		dow: 0,
		doy: 6
	};
	function localeFirstDayOfWeek() {
		return this._week.dow;
	}
	function localeFirstDayOfYear() {
		return this._week.doy;
	}
	function getSetWeek(input) {
		var week = this.localeData().week(this);
		return input == null ? week : this.add((input - week) * 7, "d");
	}
	function getSetISOWeek(input) {
		var week = weekOfYear(this, 1, 4).week;
		return input == null ? week : this.add((input - week) * 7, "d");
	}
	function hFormat() {
		return this.hours() % 12 || 12;
	}
	function kFormat() {
		return this.hours() || 24;
	}
	addFormatToken("H", ["HH", 2], 0, "hour");
	addFormatToken("h", ["hh", 2], 0, hFormat);
	addFormatToken("k", ["kk", 2], 0, kFormat);
	addFormatToken("hmm", 0, 0, function() {
		return "" + hFormat.apply(this) + zeroFill(this.minutes(), 2);
	});
	addFormatToken("hmmss", 0, 0, function() {
		return "" + hFormat.apply(this) + zeroFill(this.minutes(), 2) + zeroFill(this.seconds(), 2);
	});
	addFormatToken("Hmm", 0, 0, function() {
		return "" + this.hours() + zeroFill(this.minutes(), 2);
	});
	addFormatToken("Hmmss", 0, 0, function() {
		return "" + this.hours() + zeroFill(this.minutes(), 2) + zeroFill(this.seconds(), 2);
	});
	function meridiem(token, lowercase) {
		addFormatToken(token, 0, 0, function() {
			return this.localeData().meridiem(this.hours(), this.minutes(), lowercase);
		});
	}
	meridiem("a", true);
	meridiem("A", false);
	function matchMeridiem(isStrict, locale) {
		return locale._meridiemParse;
	}
	addRegexToken("a", matchMeridiem);
	addRegexToken("A", matchMeridiem);
	addRegexToken("H", match1to2, match1to2HasZero);
	addRegexToken("h", match1to2, match1to2NoLeadingZero);
	addRegexToken("k", match1to2, match1to2NoLeadingZero);
	addRegexToken("HH", match1to2, match2);
	addRegexToken("hh", match1to2, match2);
	addRegexToken("kk", match1to2, match2);
	addRegexToken("hmm", match3to4);
	addRegexToken("hmmss", match5to6);
	addRegexToken("Hmm", match3to4);
	addRegexToken("Hmmss", match5to6);
	addParseToken(["H", "HH"], HOUR);
	addParseToken(["k", "kk"], function(input, array, config) {
		var kInput = toInt(input);
		array[HOUR] = kInput === 24 ? 0 : kInput;
	});
	addParseToken(["a", "A"], function(input, array, config) {
		config._isPm = config._locale.isPM(input);
		config._meridiem = input;
	});
	addParseToken(["h", "hh"], function(input, array, config) {
		array[HOUR] = toInt(input);
		getParsingFlags(config).bigHour = true;
	});
	addParseToken("hmm", function(input, array, config) {
		var pos = input.length - 2;
		array[HOUR] = toInt(input.substr(0, pos));
		array[MINUTE] = toInt(input.substr(pos));
		getParsingFlags(config).bigHour = true;
	});
	addParseToken("hmmss", function(input, array, config) {
		var pos1 = input.length - 4, pos2 = input.length - 2;
		array[HOUR] = toInt(input.substr(0, pos1));
		array[MINUTE] = toInt(input.substr(pos1, 2));
		array[SECOND] = toInt(input.substr(pos2));
		getParsingFlags(config).bigHour = true;
	});
	addParseToken("Hmm", function(input, array, config) {
		var pos = input.length - 2;
		array[HOUR] = toInt(input.substr(0, pos));
		array[MINUTE] = toInt(input.substr(pos));
	});
	addParseToken("Hmmss", function(input, array, config) {
		var pos1 = input.length - 4, pos2 = input.length - 2;
		array[HOUR] = toInt(input.substr(0, pos1));
		array[MINUTE] = toInt(input.substr(pos1, 2));
		array[SECOND] = toInt(input.substr(pos2));
	});
	function localeIsPM(input) {
		return (input + "").toLowerCase().charAt(0) === "p";
	}
	var defaultLocaleMeridiemParse = /[ap]\.?m?\.?/i;
	var getSetHour = makeGetSet("Hours", true);
	function localeMeridiem(hours, minutes, isLower) {
		if (hours > 11) return isLower ? "pm" : "PM";
		else return isLower ? "am" : "AM";
	}
	var baseConfig = {
		calendar: defaultCalendar,
		longDateFormat: defaultLongDateFormat,
		invalidDate: defaultInvalidDate,
		ordinal: defaultOrdinal,
		dayOfMonthOrdinalParse: defaultDayOfMonthOrdinalParse,
		relativeTime: defaultRelativeTime,
		months: defaultLocaleMonths,
		monthsShort: defaultLocaleMonthsShort,
		week: defaultLocaleWeek,
		weekdays: defaultLocaleWeekdays,
		weekdaysMin: defaultLocaleWeekdaysMin,
		weekdaysShort: defaultLocaleWeekdaysShort,
		meridiemParse: defaultLocaleMeridiemParse
	};
	var locales = {};
	var localeFamilies = {};
	var globalLocale;
	function commonPrefix(arr1, arr2) {
		var i, minl = Math.min(arr1.length, arr2.length);
		for (i = 0; i < minl; i += 1) if (arr1[i] !== arr2[i]) return i;
		return minl;
	}
	function normalizeLocale(key) {
		return key ? key.toLowerCase().replace("_", "-") : key;
	}
	function chooseLocale(names) {
		var i = 0, j, next, locale, split;
		while (i < names.length) {
			split = normalizeLocale(names[i]).split("-");
			j = split.length;
			next = normalizeLocale(names[i + 1]);
			next = next ? next.split("-") : null;
			while (j > 0) {
				locale = loadLocale(split.slice(0, j).join("-"));
				if (locale) return locale;
				if (next && next.length >= j && commonPrefix(split, next) >= j - 1) break;
				j--;
			}
			i++;
		}
		return globalLocale;
	}
	function isLocaleNameSane(name) {
		return typeof name === "string" && /^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(name);
	}
	function loadLocale(name) {
		var oldLocale = null, aliasedRequire, normalizedName;
		if (hasOwnProp(locales, name)) return locales[name];
		normalizedName = normalizeLocale(name);
		if (hasOwnProp(locales, normalizedName)) return locales[normalizedName];
		if (typeof module !== "undefined" && module && module.exports && isLocaleNameSane(normalizedName)) try {
			oldLocale = globalLocale._abbr;
			aliasedRequire = require;
			aliasedRequire("./locale/" + normalizedName);
			getSetGlobalLocale(oldLocale);
		} catch (e) {
			locales[normalizedName] = null;
		}
		if (hasOwnProp(locales, normalizedName)) return locales[normalizedName];
	}
	function getSetGlobalLocale(key, values) {
		var data;
		if (key) {
			if (isUndefined(values)) data = getLocale(key);
			else data = defineLocale(key, values);
			if (data) globalLocale = data;
			else if (typeof console !== "undefined" && console.warn) console.warn("Locale " + key + " not found. Did you forget to load it?");
		}
		return globalLocale._abbr;
	}
	function defineLocale(name, config) {
		if (config !== null) {
			var locale, parentConfig = baseConfig;
			config.abbr = name;
			if (locales[name] != null) {
				deprecateSimple("defineLocaleOverride", "use moment.updateLocale(localeName, config) to change an existing locale. moment.defineLocale(localeName, config) should only be used for creating a new locale See http://momentjs.com/guides/#/warnings/define-locale/ for more info.");
				parentConfig = locales[name]._config;
			} else if (config.parentLocale != null) {
				if (locales[config.parentLocale] != null) parentConfig = locales[config.parentLocale]._config;
				else {
					locale = loadLocale(config.parentLocale);
					if (locale != null) parentConfig = locale._config;
					else {
						if (!localeFamilies[config.parentLocale]) localeFamilies[config.parentLocale] = [];
						localeFamilies[config.parentLocale].push({
							name,
							config
						});
						return null;
					}
				}
			}
			locales[name] = new Locale(mergeConfigs(parentConfig, config));
			if (localeFamilies[name]) localeFamilies[name].forEach(function(x) {
				defineLocale(x.name, x.config);
			});
			getSetGlobalLocale(name);
			return locales[name];
		} else {
			delete locales[name];
			return null;
		}
	}
	function updateLocale(name, config) {
		var locale, tmpLocale = loadLocale(name), parentConfig = baseConfig;
		if (tmpLocale != null) name = tmpLocale._abbr;
		if (config != null) {
			if (locales[name] != null && locales[name].parentLocale != null) locales[name].set(mergeConfigs(locales[name]._config, config));
			else {
				if (tmpLocale != null) parentConfig = tmpLocale._config;
				config = mergeConfigs(parentConfig, config);
				if (tmpLocale == null) config.abbr = name;
				locale = new Locale(config);
				locale.parentLocale = locales[name];
				locales[name] = locale;
			}
			getSetGlobalLocale(name);
		} else if (locales[name] != null) {
			if (locales[name].parentLocale != null) {
				locales[name] = locales[name].parentLocale;
				if (name === getSetGlobalLocale()) getSetGlobalLocale(name);
			} else if (locales[name] != null) delete locales[name];
		}
		return locales[name];
	}
	function getLocale(key) {
		var locale;
		if (key && key._locale && key._locale._abbr) key = key._locale._abbr;
		if (!key) return globalLocale;
		if (!isArray(key)) {
			locale = loadLocale(key);
			if (locale) return locale;
			key = [key];
		}
		return chooseLocale(key);
	}
	function listLocales() {
		return keys(locales);
	}
	function checkOverflow(m) {
		var overflow, a = m._a;
		if (a && getParsingFlags(m).overflow === -2) {
			overflow = a[MONTH] < 0 || a[MONTH] > 11 ? MONTH : a[DATE] < 1 || a[DATE] > daysInMonth(a[YEAR], a[MONTH]) ? DATE : a[HOUR] < 0 || a[HOUR] > 24 || a[HOUR] === 24 && (a[MINUTE] !== 0 || a[SECOND] !== 0 || a[MILLISECOND] !== 0) ? HOUR : a[MINUTE] < 0 || a[MINUTE] > 59 ? MINUTE : a[SECOND] < 0 || a[SECOND] > 59 ? SECOND : a[MILLISECOND] < 0 || a[MILLISECOND] > 999 ? MILLISECOND : -1;
			if (getParsingFlags(m)._overflowDayOfYear && (overflow < YEAR || overflow > DATE)) overflow = DATE;
			if (getParsingFlags(m)._overflowWeeks && overflow === -1) overflow = WEEK;
			if (getParsingFlags(m)._overflowWeekday && overflow === -1) overflow = WEEKDAY;
			getParsingFlags(m).overflow = overflow;
		}
		return m;
	}
	var extendedIsoRegex = /^\s*((?:[+-]\d{6}|\d{4})-(?:\d\d-\d\d|W\d\d-\d|W\d\d|\d\d\d|\d\d))(?:(T| )(\d\d(?::\d\d(?::\d\d(?:[.,]\d+)?)?)?)([+-]\d\d(?::?\d\d)?|\s*Z)?)?$/;
	var basicIsoRegex = /^\s*((?:[+-]\d{6}|\d{4})(?:\d\d\d\d|W\d\d\d|W\d\d|\d\d\d|\d\d|))(?:(T| )(\d\d(?:\d\d(?:\d\d(?:[.,]\d+)?)?)?)([+-]\d\d(?::?\d\d)?|\s*Z)?)?$/;
	var tzRegex = /Z|[+-]\d\d(?::?\d\d)?/;
	var isoDates = [
		["YYYYYY-MM-DD", /[+-]\d{6}-\d\d-\d\d/],
		["YYYY-MM-DD", /\d{4}-\d\d-\d\d/],
		["GGGG-[W]WW-E", /\d{4}-W\d\d-\d/],
		[
			"GGGG-[W]WW",
			/\d{4}-W\d\d/,
			false
		],
		["YYYY-DDD", /\d{4}-\d{3}/],
		[
			"YYYY-MM",
			/\d{4}-\d\d/,
			false
		],
		["YYYYYYMMDD", /[+-]\d{10}/],
		["YYYYMMDD", /\d{8}/],
		["GGGG[W]WWE", /\d{4}W\d{3}/],
		[
			"GGGG[W]WW",
			/\d{4}W\d{2}/,
			false
		],
		["YYYYDDD", /\d{7}/],
		[
			"YYYYMM",
			/\d{6}/,
			false
		],
		[
			"YYYY",
			/\d{4}/,
			false
		]
	];
	var isoTimes = [
		["HH:mm:ss.SSSS", /\d\d:\d\d:\d\d\.\d+/],
		["HH:mm:ss,SSSS", /\d\d:\d\d:\d\d,\d+/],
		["HH:mm:ss", /\d\d:\d\d:\d\d/],
		["HH:mm", /\d\d:\d\d/],
		["HHmmss.SSSS", /\d\d\d\d\d\d\.\d+/],
		["HHmmss,SSSS", /\d\d\d\d\d\d,\d+/],
		["HHmmss", /\d\d\d\d\d\d/],
		["HHmm", /\d\d\d\d/],
		["HH", /\d\d/]
	];
	var aspNetJsonRegex = /^\/?Date\((-?\d+)/i;
	var rfc2822 = /^(?:(Mon|Tue|Wed|Thu|Fri|Sat|Sun),?\s)?(\d{1,2})\s(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s(\d{2,4})\s(\d\d):(\d\d)(?::(\d\d))?\s(?:(UT|GMT|[ECMP][SD]T)|([Zz])|([+-]\d{4}))$/;
	var obsOffsets = {
		UT: 0,
		GMT: 0,
		EDT: -240,
		EST: -300,
		CDT: -300,
		CST: -360,
		MDT: -360,
		MST: -420,
		PDT: -420,
		PST: -480
	};
	function configFromISO(config) {
		var i, l, string = config._i, match = extendedIsoRegex.exec(string) || basicIsoRegex.exec(string), allowTime, dateFormat, timeFormat, tzFormat, isoDatesLen = isoDates.length, isoTimesLen = isoTimes.length;
		if (match) {
			getParsingFlags(config).iso = true;
			for (i = 0, l = isoDatesLen; i < l; i++) if (isoDates[i][1].exec(match[1])) {
				dateFormat = isoDates[i][0];
				allowTime = isoDates[i][2] !== false;
				break;
			}
			if (dateFormat == null) {
				config._isValid = false;
				return;
			}
			if (match[3]) {
				for (i = 0, l = isoTimesLen; i < l; i++) if (isoTimes[i][1].exec(match[3])) {
					timeFormat = (match[2] || " ") + isoTimes[i][0];
					break;
				}
				if (timeFormat == null) {
					config._isValid = false;
					return;
				}
			}
			if (!allowTime && timeFormat != null) {
				config._isValid = false;
				return;
			}
			if (match[4]) {
				if (tzRegex.exec(match[4])) tzFormat = "Z";
				else {
					config._isValid = false;
					return;
				}
			}
			config._f = dateFormat + (timeFormat || "") + (tzFormat || "");
			configFromStringAndFormat(config);
		} else config._isValid = false;
	}
	function extractFromRFC2822Strings(yearStr, monthStr, dayStr, hourStr, minuteStr, secondStr) {
		var result = [
			untruncateYear(yearStr),
			defaultLocaleMonthsShort.indexOf(monthStr),
			parseInt(dayStr, 10),
			parseInt(hourStr, 10),
			parseInt(minuteStr, 10)
		];
		if (secondStr) result.push(parseInt(secondStr, 10));
		return result;
	}
	function untruncateYear(yearStr) {
		var year = parseInt(yearStr, 10);
		if (year <= 49) return 2e3 + year;
		else if (year <= 999) return 1900 + year;
		return year;
	}
	function preprocessRFC2822(s) {
		return s.replace(/\([^()]*\)|[\n\t]/g, " ").replace(/(\s\s+)/g, " ").replace(/^\s\s*/, "").replace(/\s\s*$/, "");
	}
	function checkWeekday(weekdayStr, parsedInput, config) {
		if (weekdayStr) {
			if (defaultLocaleWeekdaysShort.indexOf(weekdayStr) !== new Date(parsedInput[0], parsedInput[1], parsedInput[2]).getDay()) {
				getParsingFlags(config).weekdayMismatch = true;
				config._isValid = false;
				return false;
			}
		}
		return true;
	}
	function calculateOffset(obsOffset, militaryOffset, numOffset) {
		if (obsOffset) return obsOffsets[obsOffset];
		else if (militaryOffset) return 0;
		else {
			var hm = parseInt(numOffset, 10), m = hm % 100;
			return (hm - m) / 100 * 60 + m;
		}
	}
	function configFromRFC2822(config) {
		var match = rfc2822.exec(preprocessRFC2822(config._i)), parsedArray;
		if (match) {
			parsedArray = extractFromRFC2822Strings(match[4], match[3], match[2], match[5], match[6], match[7]);
			if (!checkWeekday(match[1], parsedArray, config)) return;
			config._a = parsedArray;
			config._tzm = calculateOffset(match[8], match[9], match[10]);
			config._d = createUTCDate.apply(null, config._a);
			config._d.setUTCMinutes(config._d.getUTCMinutes() - config._tzm);
			getParsingFlags(config).rfc2822 = true;
		} else config._isValid = false;
	}
	function configFromString(config) {
		var matched = aspNetJsonRegex.exec(config._i);
		if (matched !== null) {
			config._d = /* @__PURE__ */ new Date(+matched[1]);
			return;
		}
		configFromISO(config);
		if (config._isValid === false) delete config._isValid;
		else return;
		configFromRFC2822(config);
		if (config._isValid === false) delete config._isValid;
		else return;
		if (config._strict) config._isValid = false;
		else hooks.createFromInputFallback(config);
	}
	hooks.createFromInputFallback = deprecate("value provided is not in a recognized RFC2822 or ISO format. moment construction falls back to js Date(), which is not reliable across all browsers and versions. Non RFC2822/ISO date formats are discouraged. Please refer to http://momentjs.com/guides/#/warnings/js-date/ for more info.", function(config) {
		config._d = /* @__PURE__ */ new Date(config._i + (config._useUTC ? " UTC" : ""));
	});
	function defaults(a, b, c) {
		if (a != null) return a;
		if (b != null) return b;
		return c;
	}
	function currentDateArray(config, now, forWeek) {
		var hadWeekContext = Object.prototype.hasOwnProperty.call(config, "_isDefaultDatePartsForWeek"), weekContext = config._isDefaultDatePartsForWeek;
		config._isDefaultDatePartsForWeek = !!forWeek;
		try {
			return hooks._getDefaultDateParts(config, now, forWeek);
		} finally {
			if (hadWeekContext) config._isDefaultDatePartsForWeek = weekContext;
			else delete config._isDefaultDatePartsForWeek;
		}
	}
	function currentDateNow(config) {
		var now = config._defaultDatePartsNow;
		if (!now) return hooks.now();
		if (!now.hasValue) {
			now.value = hooks.now();
			now.hasValue = true;
		}
		return now.value;
	}
	function getDefaultDateParts(config, now, forWeek) {
		var useWeekDefaults = forWeek || config._isDefaultDatePartsForWeek, nowValue = useWeekDefaults ? createLocal(now) : new Date(now);
		if (useWeekDefaults) return [
			nowValue.year(),
			nowValue.month(),
			nowValue.date()
		];
		if (config._useUTC) return [
			nowValue.getUTCFullYear(),
			nowValue.getUTCMonth(),
			nowValue.getUTCDate()
		];
		return [
			nowValue.getFullYear(),
			nowValue.getMonth(),
			nowValue.getDate()
		];
	}
	hooks._getDefaultDateParts = getDefaultDateParts;
	function configFromArray(config) {
		var i, date, input = [], now, currentDate, expectedWeekday, yearToUse, dateIsDefaulted;
		if (config._d) return;
		if (config._a[YEAR] == null || config._a[MONTH] == null || config._a[DATE] == null) {
			now = currentDateNow(config);
			currentDate = currentDateArray(config, now);
		}
		if (config._w && config._a[DATE] == null && config._a[MONTH] == null) dayOfYearFromWeekInfo(config, currentDateArray(config, now, true));
		if (config._dayOfYear != null) {
			yearToUse = config._a[YEAR] != null ? config._a[YEAR] : currentDate[YEAR];
			if (config._dayOfYear > daysInYear(yearToUse) || config._dayOfYear === 0) getParsingFlags(config)._overflowDayOfYear = true;
			date = createUTCDate(yearToUse, 0, config._dayOfYear);
			config._a[MONTH] = date.getUTCMonth();
			config._a[DATE] = date.getUTCDate();
		}
		dateIsDefaulted = config._a[YEAR] == null || config._a[MONTH] == null || config._a[DATE] == null;
		for (i = 0; i < 3 && config._a[i] == null; ++i) config._a[i] = input[i] = currentDate[i];
		for (; i < 7; i++) config._a[i] = input[i] = config._a[i] == null ? i === 2 ? 1 : 0 : config._a[i];
		if (config._a[HOUR] === 24 && config._a[MINUTE] === 0 && config._a[SECOND] === 0 && config._a[MILLISECOND] === 0) {
			config._nextDay = true;
			config._a[HOUR] = 0;
		}
		config._d = (config._useUTC ? createUTCDate : createDate).apply(null, input);
		expectedWeekday = config._useUTC ? config._d.getUTCDay() : config._d.getDay();
		if (config._tzm != null) config._d.setUTCMinutes(config._d.getUTCMinutes() - config._tzm);
		if (config._nextDay) config._a[HOUR] = 24;
		if (config._w && typeof config._w.d !== "undefined" && !dateIsDefaulted && config._w.d !== expectedWeekday) getParsingFlags(config).weekdayMismatch = true;
	}
	function dayOfYearFromWeekInfo(config, currentDate) {
		var w = config._w, weekYear, week, weekday, dow, doy, temp, weekdayOverflow, curWeek;
		if (w.GG != null || w.W != null || w.E != null) {
			dow = 1;
			doy = 4;
			weekYear = defaults(w.GG, config._a[YEAR], weekOfYearFromDate(currentDate[YEAR], currentDate[MONTH], currentDate[DATE], 1, 4).year);
			week = defaults(w.W, 1);
			weekday = defaults(w.E, 1);
			if (weekday < 1 || weekday > 7) weekdayOverflow = true;
		} else {
			dow = config._locale._week.dow;
			doy = config._locale._week.doy;
			curWeek = weekOfYearFromDate(currentDate[YEAR], currentDate[MONTH], currentDate[DATE], dow, doy);
			weekYear = defaults(w.gg, config._a[YEAR], curWeek.year);
			week = defaults(w.w, curWeek.week);
			if (w.d != null) {
				weekday = w.d;
				if (weekday < 0 || weekday > 6) weekdayOverflow = true;
			} else if (w.e != null) {
				weekday = w.e + dow;
				if (w.e < 0 || w.e > 6) weekdayOverflow = true;
			} else weekday = dow;
		}
		if (week < 1 || week > weeksInYear(weekYear, dow, doy)) getParsingFlags(config)._overflowWeeks = true;
		else if (weekdayOverflow != null) getParsingFlags(config)._overflowWeekday = true;
		else {
			temp = dayOfYearFromWeeks(weekYear, week, weekday, dow, doy);
			config._a[YEAR] = temp.year;
			config._dayOfYear = temp.dayOfYear;
		}
	}
	hooks.ISO_8601 = function() {};
	hooks.RFC_2822 = function() {};
	function configFromStringAndFormat(config) {
		if (config._f === hooks.ISO_8601) {
			configFromISO(config);
			return;
		}
		if (config._f === hooks.RFC_2822) {
			configFromRFC2822(config);
			return;
		}
		config._a = [];
		getParsingFlags(config).empty = true;
		var string = "" + config._i, i, parsedInput, tokens, token, skipped, stringLength = string.length, totalParsedInputLength = 0, era, tokenLen;
		tokens = expandFormat(config._f, config._locale).match(formattingTokens) || [];
		tokenLen = tokens.length;
		for (i = 0; i < tokenLen; i++) {
			token = tokens[i];
			parsedInput = (string.match(getParseRegexForToken(token, config)) || [])[0];
			if (parsedInput) {
				skipped = string.substr(0, string.indexOf(parsedInput));
				if (skipped.length > 0) getParsingFlags(config).unusedInput.push(skipped);
				string = string.slice(string.indexOf(parsedInput) + parsedInput.length);
				totalParsedInputLength += parsedInput.length;
			}
			if (formatTokenFunctions[token]) {
				if (parsedInput) getParsingFlags(config).empty = false;
				else getParsingFlags(config).unusedTokens.push(token);
				addTimeToArrayFromToken(token, parsedInput, config);
			} else if (config._strict && !parsedInput) getParsingFlags(config).unusedTokens.push(token);
		}
		getParsingFlags(config).charsLeftOver = stringLength - totalParsedInputLength;
		if (string.length > 0) getParsingFlags(config).unusedInput.push(string);
		if (config._a[HOUR] <= 12 && getParsingFlags(config).bigHour === true && config._a[HOUR] > 0) getParsingFlags(config).bigHour = void 0;
		getParsingFlags(config).parsedDateParts = config._a.slice(0);
		getParsingFlags(config).meridiem = config._meridiem;
		config._a[HOUR] = meridiemFixWrap(config._locale, config._a[HOUR], config._meridiem);
		era = getParsingFlags(config).era;
		if (era !== null) config._a[YEAR] = config._locale.erasConvertYear(era, config._a[YEAR]);
		configFromArray(config);
		checkOverflow(config);
	}
	function meridiemFixWrap(locale, hour, meridiem) {
		var isPm;
		if (meridiem == null) return hour;
		if (locale.meridiemHour != null) return locale.meridiemHour(hour, meridiem);
		else if (locale.isPM != null) {
			isPm = locale.isPM(meridiem);
			if (isPm && hour < 12) hour += 12;
			if (!isPm && hour === 12) hour = 0;
			return hour;
		} else return hour;
	}
	function configFromStringAndArray(config) {
		var tempConfig, bestMoment, scoreToBeat, i, currentScore, validFormatFound, bestFormatIsValid = false, defaultDatePartsNow = {}, configfLen = config._f.length;
		if (configfLen === 0) {
			getParsingFlags(config).invalidFormat = true;
			config._d = /* @__PURE__ */ new Date(NaN);
			return;
		}
		for (i = 0; i < configfLen; i++) {
			currentScore = 0;
			validFormatFound = false;
			tempConfig = copyConfig({}, config);
			if (config._useUTC != null) tempConfig._useUTC = config._useUTC;
			tempConfig._defaultDatePartsNow = defaultDatePartsNow;
			tempConfig._f = config._f[i];
			configFromStringAndFormat(tempConfig);
			if (isValid$2(tempConfig)) validFormatFound = true;
			currentScore += getParsingFlags(tempConfig).charsLeftOver;
			currentScore += getParsingFlags(tempConfig).unusedTokens.length * 10;
			getParsingFlags(tempConfig).score = currentScore;
			if (!bestFormatIsValid) {
				if (scoreToBeat == null || currentScore < scoreToBeat || validFormatFound) {
					scoreToBeat = currentScore;
					bestMoment = tempConfig;
					if (validFormatFound) bestFormatIsValid = true;
				}
			} else if (currentScore < scoreToBeat) {
				scoreToBeat = currentScore;
				bestMoment = tempConfig;
			}
		}
		extend(config, bestMoment || tempConfig);
	}
	function configFromObject(config) {
		if (config._d) return;
		var i = normalizeObjectUnits(config._i), dayOrDate = i.day === void 0 ? i.date : i.day;
		config._a = map([
			i.year,
			i.month,
			dayOrDate,
			i.hour,
			i.minute,
			i.second,
			i.millisecond
		], function(obj) {
			return obj && parseInt(obj, 10);
		});
		configFromArray(config);
	}
	function createFromConfig(config) {
		var res = new Moment(checkOverflow(prepareConfig(config)));
		if (res._nextDay) {
			res.add(1, "d");
			res._nextDay = void 0;
		}
		return res;
	}
	function prepareConfig(config) {
		var input = config._i, format = config._f;
		config._locale = config._locale || getLocale(config._l);
		if (input === null || format === void 0 && input === "") return createInvalid$1({ nullInput: true });
		if (typeof input === "string") config._i = input = config._locale.preparse(input);
		if (isMoment(input)) return new Moment(checkOverflow(input));
		else if (isDate(input)) config._d = input;
		else if (isArray(format)) configFromStringAndArray(config);
		else if (format) configFromStringAndFormat(config);
		else configFromInput(config);
		if (!isValid$2(config)) config._d = null;
		return config;
	}
	function configFromInput(config) {
		var input = config._i;
		if (isUndefined(input)) config._d = new Date(hooks.now());
		else if (isDate(input)) config._d = new Date(input.valueOf());
		else if (typeof input === "string") configFromString(config);
		else if (isArray(input)) {
			config._a = map(input.slice(0), function(obj) {
				return parseInt(obj, 10);
			});
			configFromArray(config);
		} else if (isObject(input)) configFromObject(config);
		else if (isNumber(input)) config._d = new Date(input);
		else hooks.createFromInputFallback(config);
	}
	function createLocalOrUTC(input, format, locale, strict, isUTC) {
		var c = {};
		if (format === true || format === false) {
			strict = format;
			format = void 0;
		}
		if (locale === true || locale === false) {
			strict = locale;
			locale = void 0;
		}
		if (isObject(input) && isObjectEmpty(input) || isArray(input) && input.length === 0) input = void 0;
		c._isAMomentObject = true;
		c._useUTC = c._isUTC = isUTC;
		c._l = locale;
		c._i = input;
		c._f = format;
		c._strict = strict;
		return createFromConfig(c);
	}
	function createLocal(input, format, locale, strict) {
		return createLocalOrUTC(input, format, locale, strict, false);
	}
	var prototypeMin = deprecate("moment().min is deprecated, use moment.max instead. http://momentjs.com/guides/#/warnings/min-max/", function() {
		var other = createLocal.apply(null, arguments);
		if (this.isValid() && other.isValid()) return other < this ? this : other;
		else return createInvalid$1();
	});
	var prototypeMax = deprecate("moment().max is deprecated, use moment.min instead. http://momentjs.com/guides/#/warnings/min-max/", function() {
		var other = createLocal.apply(null, arguments);
		if (this.isValid() && other.isValid()) return other > this ? this : other;
		else return createInvalid$1();
	});
	function pickBy(fn, moments) {
		var res, i;
		if (moments.length === 1 && isArray(moments[0])) moments = moments[0];
		if (!moments.length) return createLocal();
		for (i = 0; i < moments.length; ++i) if (isMoment(moments[i])) {
			res = moments[i];
			break;
		}
		if (!res) return createInvalid$1();
		for (++i; i < moments.length; ++i) if (isMoment(moments[i]) && (!moments[i].isValid() || moments[i][fn](res))) res = moments[i];
		return res;
	}
	function min() {
		return pickBy("isBefore", [].slice.call(arguments, 0));
	}
	function max() {
		return pickBy("isAfter", [].slice.call(arguments, 0));
	}
	var now = function() {
		return Date.now ? Date.now() : +/* @__PURE__ */ new Date();
	};
	var ordering = [
		"year",
		"quarter",
		"month",
		"week",
		"day",
		"hour",
		"minute",
		"second",
		"millisecond"
	];
	function isDurationValid(m) {
		var key, unitHasDecimal = false, i, orderLen = ordering.length;
		for (key in m) if (hasOwnProp(m, key) && !(indexOf.call(ordering, key) !== -1 && (m[key] == null || !isNaN(m[key])))) return false;
		for (i = 0; i < orderLen; ++i) if (m[ordering[i]]) {
			if (unitHasDecimal) return false;
			if (parseFloat(m[ordering[i]]) !== toInt(m[ordering[i]])) unitHasDecimal = true;
		}
		return true;
	}
	function isValid$1() {
		return this._isValid;
	}
	function createInvalid() {
		return createDuration(NaN);
	}
	function Duration(duration) {
		var normalizedInput = normalizeObjectUnits(duration), years = normalizedInput.year || 0, quarters = normalizedInput.quarter || 0, months = normalizedInput.month || 0, weeks = normalizedInput.week || normalizedInput.isoWeek || 0, days = normalizedInput.day || 0, hours = normalizedInput.hour || 0, minutes = normalizedInput.minute || 0, seconds = normalizedInput.second || 0, milliseconds = normalizedInput.millisecond || 0;
		this._isValid = isDurationValid(normalizedInput);
		this._milliseconds = +milliseconds + seconds * 1e3 + minutes * 6e4 + hours * 1e3 * 60 * 60;
		this._days = +days + weeks * 7;
		this._months = +months + quarters * 3 + years * 12;
		this._data = {};
		this._locale = getLocale();
		this._bubble();
	}
	function isDuration(obj) {
		return obj instanceof Duration;
	}
	function absRound(number) {
		if (number < 0) return Math.round(-1 * number) * -1;
		else return Math.round(number);
	}
	function compareArrays(array1, array2, dontConvert) {
		var len = Math.min(array1.length, array2.length), lengthDiff = Math.abs(array1.length - array2.length), diffs = 0, i = 0;
		for (; i < len; i++) if (toInt(array1[i]) !== toInt(array2[i])) diffs++;
		return diffs + lengthDiff;
	}
	function offset(token, separator) {
		addFormatToken(token, 0, 0, function() {
			var offset = this.utcOffset(), sign = "+";
			if (offset < 0) {
				offset = -offset;
				sign = "-";
			}
			return sign + zeroFill(~~(offset / 60), 2) + separator + zeroFill(~~offset % 60, 2);
		});
	}
	offset("Z", ":");
	offset("ZZ", "");
	addRegexToken("Z", matchShortOffset);
	addRegexToken("ZZ", matchShortOffset);
	addParseToken(["Z", "ZZ"], function(input, array, config) {
		var offset = offsetFromString(matchShortOffset, input);
		config._useUTC = true;
		config._tzm = offset;
		if (offset === null) getParsingFlags(config).invalidOffset = input;
	});
	var chunkOffset = /([\+\-]|\d\d)/gi;
	function offsetFromString(matcher, string) {
		var matches = (string || "").match(matcher), chunk, parts, minutes;
		if (matches === null) return null;
		chunk = matches[matches.length - 1] || [];
		parts = (chunk + "").match(chunkOffset) || [
			"-",
			0,
			0
		];
		minutes = +(parts[1] * 60) + toInt(parts[2]);
		if (toInt(parts[2]) > 59 || (parts[0] === "+" ? minutes > 840 : minutes > 720)) return null;
		return minutes === 0 ? 0 : parts[0] === "+" ? minutes : -minutes;
	}
	function cloneWithOffset(input, model) {
		var res, diff;
		if (model._isUTC) {
			res = model.clone();
			diff = (isMoment(input) || isDate(input) ? input.valueOf() : createLocal(input).valueOf()) - res.valueOf();
			res._d.setTime(res._d.valueOf() + diff);
			hooks.updateOffset(res, false);
			return res;
		} else return createLocal(input).local();
	}
	function getDateOffset(m) {
		return -Math.round(m._d.getTimezoneOffset());
	}
	hooks.updateOffset = function() {};
	function getSetOffset(input, keepLocalTime, keepMinutes) {
		var offset = this._offset || 0, localAdjust;
		if (!this.isValid()) return input != null ? this : NaN;
		if (input != null) {
			if (typeof input === "string") {
				input = offsetFromString(matchShortOffset, input);
				if (input === null) return this;
			} else if (Math.abs(input) < 16 && !keepMinutes) input = input * 60;
			if (!this._isUTC && keepLocalTime) localAdjust = getDateOffset(this);
			this._offset = input;
			this._isUTC = true;
			if (localAdjust != null) this.add(localAdjust, "m");
			if (offset !== input) {
				if (!keepLocalTime || this._changeInProgress) addSubtract$1(this, createDuration(input - offset, "m"), 1, false);
				else if (!this._changeInProgress) {
					this._changeInProgress = true;
					hooks.updateOffset(this, true);
					this._changeInProgress = null;
				}
			}
			return this;
		} else return this._isUTC ? offset : getDateOffset(this);
	}
	function getSetZone(input, keepLocalTime) {
		if (input != null) {
			if (typeof input !== "string") input = -input;
			this.utcOffset(input, keepLocalTime);
			return this;
		} else return -this.utcOffset();
	}
	function setOffsetToUTC(keepLocalTime) {
		return this.utcOffset(0, keepLocalTime);
	}
	function setOffsetToLocal(keepLocalTime) {
		if (this._isUTC) {
			this.utcOffset(0, keepLocalTime);
			this._isUTC = false;
			if (keepLocalTime) this.subtract(getDateOffset(this), "m");
		}
		return this;
	}
	function setOffsetToParsedOffset() {
		if (this._tzm != null) this.utcOffset(this._tzm, false, true);
		else if (typeof this._i === "string") {
			var tZone = offsetFromString(matchOffset, this._i);
			if (tZone != null) this.utcOffset(tZone);
			else this.utcOffset(0, true);
		}
		return this;
	}
	function hasAlignedHourOffset(input) {
		if (!this.isValid()) return false;
		input = input ? createLocal(input).utcOffset() : 0;
		return (this.utcOffset() - input) % 60 === 0;
	}
	function isDaylightSavingTime() {
		return this.utcOffset() > this.clone().month(0).utcOffset() || this.utcOffset() > this.clone().month(5).utcOffset();
	}
	function isDaylightSavingTimeShifted() {
		if (!isUndefined(this._isDSTShifted)) return this._isDSTShifted;
		var c = {}, other;
		copyConfig(c, this);
		c = prepareConfig(c);
		if (c._a) {
			other = c._isUTC ? createUTC(c._a) : createLocal(c._a);
			this._isDSTShifted = this.isValid() && compareArrays(c._a, other.toArray()) > 0;
		} else this._isDSTShifted = false;
		return this._isDSTShifted;
	}
	function isLocal() {
		return this.isValid() ? !this._isUTC : false;
	}
	function isUtcOffset() {
		return this.isValid() ? this._isUTC : false;
	}
	function isUtc() {
		return this.isValid() ? this._isUTC && this._offset === 0 : false;
	}
	var aspNetRegex = /^(-|\+)?(?:(\d*)[. ])?(\d+):(\d+)(?::(\d+)(\.\d*)?)?$/;
	var isoRegex = /^(-|\+)?P(?:([-+]?[0-9,.]*)Y)?(?:([-+]?[0-9,.]*)M)?(?:([-+]?[0-9,.]*)W)?(?:([-+]?[0-9,.]*)D)?(?:T(?:([-+]?[0-9,.]*)H)?(?:([-+]?[0-9,.]*)M)?(?:([-+]?[0-9,.]*)S)?)?$/;
	function createDuration(input, key) {
		var duration = input, match = null, sign, ret, diffRes;
		if (isDuration(input)) duration = {
			ms: input._milliseconds,
			d: input._days,
			M: input._months
		};
		else if (isNumber(input) || !isNaN(+input)) {
			duration = {};
			if (key) duration[key] = +input;
			else duration.milliseconds = +input;
		} else if (match = aspNetRegex.exec(input)) {
			sign = match[1] === "-" ? -1 : 1;
			duration = {
				y: 0,
				d: toInt(match[DATE]) * sign,
				h: toInt(match[HOUR]) * sign,
				m: toInt(match[MINUTE]) * sign,
				s: toInt(match[SECOND]) * sign,
				ms: toInt(absRound(match[MILLISECOND] * 1e3)) * sign
			};
		} else if (match = isoRegex.exec(input)) {
			sign = match[1] === "-" ? -1 : 1;
			duration = {
				y: parseIso(match[2], sign),
				M: parseIso(match[3], sign),
				w: parseIso(match[4], sign),
				d: parseIso(match[5], sign),
				h: parseIso(match[6], sign),
				m: parseIso(match[7], sign),
				s: parseIso(match[8], sign)
			};
		} else if (duration == null) duration = {};
		else if (typeof duration === "object" && ("from" in duration || "to" in duration)) {
			diffRes = momentsDifference(createLocal(duration.from), createLocal(duration.to));
			duration = {};
			duration.ms = diffRes.milliseconds;
			duration.M = diffRes.months;
		}
		ret = new Duration(duration);
		if (isDuration(input) && hasOwnProp(input, "_locale")) ret._locale = input._locale;
		if (isDuration(input) && hasOwnProp(input, "_isValid")) ret._isValid = input._isValid;
		return ret;
	}
	createDuration.fn = Duration.prototype;
	createDuration.invalid = createInvalid;
	function parseIso(inp, sign) {
		var res = inp && parseFloat(inp.replace(",", "."));
		return (isNaN(res) ? 0 : res) * sign;
	}
	function positiveMomentsDifference(base, other) {
		var res = {};
		res.months = other.month() - base.month() + (other.year() - base.year()) * 12;
		if (base.clone().add(res.months, "M").isAfter(other)) --res.months;
		res.milliseconds = +other - +base.clone().add(res.months, "M");
		return res;
	}
	function momentsDifference(base, other) {
		var res;
		if (!(base.isValid() && other.isValid())) return {
			milliseconds: 0,
			months: 0
		};
		other = cloneWithOffset(other, base);
		if (base.isBefore(other)) res = positiveMomentsDifference(base, other);
		else {
			res = positiveMomentsDifference(other, base);
			res.milliseconds = -res.milliseconds;
			res.months = -res.months;
		}
		return res;
	}
	function createAdder(direction, name) {
		return function(val, period) {
			var dur, tmp;
			if (period !== null && !isNaN(+period)) {
				deprecateSimple(name, "moment()." + name + "(period, number) is deprecated. Please use moment()." + name + "(number, period). See http://momentjs.com/guides/#/warnings/add-inverted-param/ for more info.");
				tmp = val;
				val = period;
				period = tmp;
			}
			dur = createDuration(val, period);
			addSubtract$1(this, dur, direction);
			return this;
		};
	}
	function addSubtract$1(mom, duration, isAdding, updateOffset) {
		var milliseconds = duration._milliseconds, days = absRound(duration._days), months = absRound(duration._months);
		if (!mom.isValid()) return;
		updateOffset = updateOffset == null ? true : updateOffset;
		if (months) setMonth(mom, get$2(mom, "Month") + months * isAdding);
		if (days) set$1(mom, "Date", get$2(mom, "Date") + days * isAdding);
		if (milliseconds) mom._d.setTime(mom._d.valueOf() + milliseconds * isAdding);
		if (updateOffset) hooks.updateOffset(mom, days || months);
	}
	var add$1 = createAdder(1, "add");
	var subtract$1 = createAdder(-1, "subtract");
	function isString(input) {
		return typeof input === "string" || input instanceof String;
	}
	function isMomentInput(input) {
		return isMoment(input) || isDate(input) || isString(input) || isNumber(input) || isNumberOrStringArray(input) || isMomentInputObject(input) || input === null || input === void 0;
	}
	function isMomentInputObject(input) {
		var objectTest = isObject(input) && !isObjectEmpty(input), propertyTest = false, properties = [
			"years",
			"year",
			"y",
			"months",
			"month",
			"M",
			"days",
			"day",
			"d",
			"dates",
			"date",
			"D",
			"hours",
			"hour",
			"h",
			"minutes",
			"minute",
			"m",
			"seconds",
			"second",
			"s",
			"milliseconds",
			"millisecond",
			"ms"
		], i, property, propertyLen = properties.length;
		for (i = 0; i < propertyLen; i += 1) {
			property = properties[i];
			propertyTest = propertyTest || hasOwnProp(input, property);
		}
		return objectTest && propertyTest;
	}
	function isNumberOrStringArray(input) {
		var arrayTest = isArray(input), dataTypeTest = false;
		if (arrayTest) dataTypeTest = input.filter(function(item) {
			return !isNumber(item) && isString(input);
		}).length === 0;
		return arrayTest && dataTypeTest;
	}
	function isCalendarSpec(input) {
		var objectTest = isObject(input) && !isObjectEmpty(input), propertyTest = false, properties = [
			"sameDay",
			"nextDay",
			"lastDay",
			"nextWeek",
			"lastWeek",
			"sameElse"
		], i = 0, property;
		for (; i < properties.length; i += 1) {
			property = properties[i];
			propertyTest = propertyTest || hasOwnProp(input, property);
		}
		return objectTest && propertyTest;
	}
	function getCalendarFormat(myMoment, now) {
		var diff = myMoment.diff(now, "days", true);
		return diff < -6 ? "sameElse" : diff < -1 ? "lastWeek" : diff < 0 ? "lastDay" : diff < 1 ? "sameDay" : diff < 2 ? "nextDay" : diff < 7 ? "nextWeek" : "sameElse";
	}
	function calendar(time, formats) {
		if (arguments.length === 1) {
			if (!arguments[0]) {
				time = void 0;
				formats = void 0;
			} else if (isMomentInput(arguments[0])) {
				time = arguments[0];
				formats = void 0;
			} else if (isCalendarSpec(arguments[0])) {
				formats = arguments[0];
				time = void 0;
			}
		}
		var now = time || createLocal(), sod = cloneWithOffset(now, this).startOf("day"), format = hooks.calendarFormat(this, sod) || "sameElse", output = formats && (isFunction(formats[format]) ? formats[format].call(this, now) : formats[format]);
		return this.format(output || this.localeData().calendar(format, this, createLocal(now)));
	}
	function clone$1() {
		return new Moment(this);
	}
	function isAfter(input, units) {
		var localInput = isMoment(input) ? input : createLocal(input);
		if (!(this.isValid() && localInput.isValid())) return false;
		units = normalizeUnits(units) || "millisecond";
		if (units === "millisecond") return this.valueOf() > localInput.valueOf();
		else return localInput.valueOf() < this.clone().startOf(units).valueOf();
	}
	function isBefore(input, units) {
		var localInput = isMoment(input) ? input : createLocal(input);
		if (!(this.isValid() && localInput.isValid())) return false;
		units = normalizeUnits(units) || "millisecond";
		if (units === "millisecond") return this.valueOf() < localInput.valueOf();
		else return this.clone().endOf(units).valueOf() < localInput.valueOf();
	}
	function isBetween(from, to, units, inclusivity) {
		var localFrom = isMoment(from) ? from : createLocal(from), localTo = isMoment(to) ? to : createLocal(to);
		if (!(this.isValid() && localFrom.isValid() && localTo.isValid())) return false;
		inclusivity = inclusivity || "()";
		return (inclusivity[0] === "(" ? this.isAfter(localFrom, units) : !this.isBefore(localFrom, units)) && (inclusivity[1] === ")" ? this.isBefore(localTo, units) : !this.isAfter(localTo, units));
	}
	function isSame(input, units) {
		var localInput = isMoment(input) ? input : createLocal(input), inputMs;
		if (!(this.isValid() && localInput.isValid())) return false;
		units = normalizeUnits(units) || "millisecond";
		if (units === "millisecond") return this.valueOf() === localInput.valueOf();
		else {
			inputMs = localInput.valueOf();
			return this.clone().startOf(units).valueOf() <= inputMs && inputMs <= this.clone().endOf(units).valueOf();
		}
	}
	function isSameOrAfter(input, units) {
		return this.isSame(input, units) || this.isAfter(input, units);
	}
	function isSameOrBefore(input, units) {
		return this.isSame(input, units) || this.isBefore(input, units);
	}
	function diff(input, units, asFloat) {
		var that, zoneDelta, output;
		if (!this.isValid()) return NaN;
		that = cloneWithOffset(input, this);
		if (!that.isValid()) return NaN;
		zoneDelta = (that.utcOffset() - this.utcOffset()) * 6e4;
		units = normalizeUnits(units);
		switch (units) {
			case "year":
				output = monthDiff(this, that) / 12;
				break;
			case "month":
				output = monthDiff(this, that);
				break;
			case "quarter":
				output = monthDiff(this, that) / 3;
				break;
			case "second":
				output = (this - that) / 1e3;
				break;
			case "minute":
				output = (this - that) / 6e4;
				break;
			case "hour":
				output = (this - that) / 36e5;
				break;
			case "day":
				output = (this - that - zoneDelta) / 864e5;
				break;
			case "week":
				output = (this - that - zoneDelta) / 6048e5;
				break;
			default: output = this - that;
		}
		return asFloat ? output : absFloor(output);
	}
	function monthDiff(a, b) {
		if (a.date() < b.date()) return -monthDiff(b, a);
		var wholeMonthDiff = (b.year() - a.year()) * 12 + (b.month() - a.month()), anchor = a.clone().add(wholeMonthDiff, "months"), anchor2, adjust;
		if (b - anchor < 0) {
			anchor2 = a.clone().add(wholeMonthDiff - 1, "months");
			adjust = (b - anchor) / (anchor - anchor2);
		} else {
			anchor2 = a.clone().add(wholeMonthDiff + 1, "months");
			adjust = (b - anchor) / (anchor2 - anchor);
		}
		return -(wholeMonthDiff + adjust) || 0;
	}
	hooks.defaultFormat = "YYYY-MM-DDTHH:mm:ssZ";
	hooks.defaultFormatUtc = "YYYY-MM-DDTHH:mm:ss[Z]";
	function toString() {
		return this.clone().locale("en").format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ");
	}
	function toISOString$1(keepOffset) {
		if (!this.isValid()) return null;
		var utc = keepOffset !== true, m = utc ? this.clone().utc() : this;
		if (m.year() < 0 || m.year() > 9999) return formatMoment(m, utc ? "YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]" : "YYYYYY-MM-DD[T]HH:mm:ss.SSSZ");
		if (isFunction(Date.prototype.toISOString)) {
			if (utc) return this.toDate().toISOString();
			else return new Date(this.valueOf() + this.utcOffset() * 60 * 1e3).toISOString().replace("Z", formatMoment(m, "Z"));
		}
		return formatMoment(m, utc ? "YYYY-MM-DD[T]HH:mm:ss.SSS[Z]" : "YYYY-MM-DD[T]HH:mm:ss.SSSZ");
	}
	/**
	* Return a human readable representation of a moment that can
	* also be evaluated to get a new moment which is the same
	*
	* @link https://nodejs.org/dist/latest/docs/api/util.html#util_custom_inspect_function_on_objects
	*/
	function inspect() {
		if (!this.isValid()) return "moment.invalid(/* " + this._i + " */)";
		var func = "moment", zone = "", prefix, year, datetime, suffix;
		if (!this.isLocal()) {
			func = this.utcOffset() === 0 ? "moment.utc" : "moment.parseZone";
			zone = "Z";
		}
		prefix = "[" + func + "(\"]";
		year = 0 <= this.year() && this.year() <= 9999 ? "YYYY" : "YYYYYY";
		datetime = "-MM-DD[T]HH:mm:ss.SSS";
		suffix = zone + "[\")]";
		return this.format(prefix + year + datetime + suffix);
	}
	function format(inputString) {
		if (!inputString) inputString = this.isUtc() ? hooks.defaultFormatUtc : hooks.defaultFormat;
		var output = formatMoment(this, inputString);
		return this.localeData().postformat(output);
	}
	function from(time, withoutSuffix) {
		if (this.isValid() && (isMoment(time) && time.isValid() || createLocal(time).isValid())) return createDuration({
			to: this,
			from: time
		}).locale(this.locale()).humanize(!withoutSuffix);
		else return this.localeData().invalidDate();
	}
	function fromNow(withoutSuffix) {
		return this.from(createLocal(), withoutSuffix);
	}
	function to(time, withoutSuffix) {
		if (this.isValid() && (isMoment(time) && time.isValid() || createLocal(time).isValid())) return createDuration({
			from: this,
			to: time
		}).locale(this.locale()).humanize(!withoutSuffix);
		else return this.localeData().invalidDate();
	}
	function toNow(withoutSuffix) {
		return this.to(createLocal(), withoutSuffix);
	}
	function locale(key) {
		var newLocaleData;
		if (key === void 0) return this._locale._abbr;
		else {
			newLocaleData = getLocale(key);
			if (newLocaleData != null) this._locale = newLocaleData;
			return this;
		}
	}
	var lang = deprecate("moment().lang() is deprecated. Instead, use moment().localeData() to get the language configuration. Use moment().locale() to change languages.", function(key) {
		if (key === void 0) return this.localeData();
		else return this.locale(key);
	});
	function localeData() {
		return this._locale;
	}
	var MS_PER_SECOND = 1e3;
	var MS_PER_MINUTE = 60 * MS_PER_SECOND;
	var MS_PER_HOUR = 60 * MS_PER_MINUTE;
	var MS_PER_400_YEARS = 3506328 * MS_PER_HOUR;
	function mod(dividend, divisor) {
		return (dividend % divisor + divisor) % divisor;
	}
	function localStartOfDate(y, m, d) {
		if (y < 100 && y >= 0) return new Date(y + 400, m, d) - MS_PER_400_YEARS;
		else return new Date(y, m, d).valueOf();
	}
	function utcStartOfDate(y, m, d) {
		if (y < 100 && y >= 0) return Date.UTC(y + 400, m, d) - MS_PER_400_YEARS;
		else return Date.UTC(y, m, d);
	}
	function startOf(units) {
		var time, startOfDate;
		units = normalizeUnits(units);
		if (units === void 0 || units === "millisecond" || !this.isValid()) return this;
		startOfDate = this._isUTC ? utcStartOfDate : localStartOfDate;
		switch (units) {
			case "year":
				time = startOfDate(this.year(), 0, 1);
				break;
			case "quarter":
				time = startOfDate(this.year(), this.month() - this.month() % 3, 1);
				break;
			case "month":
				time = startOfDate(this.year(), this.month(), 1);
				break;
			case "week":
				time = startOfDate(this.year(), this.month(), this.date() - this.weekday());
				break;
			case "isoWeek":
				time = startOfDate(this.year(), this.month(), this.date() - (this.isoWeekday() - 1));
				break;
			case "day":
			case "date":
				time = startOfDate(this.year(), this.month(), this.date());
				break;
			case "hour":
				time = this._d.valueOf();
				time -= mod(time + (this._isUTC ? 0 : this.utcOffset() * MS_PER_MINUTE), MS_PER_HOUR);
				break;
			case "minute":
				time = this._d.valueOf();
				time -= mod(time, MS_PER_MINUTE);
				break;
			case "second":
				time = this._d.valueOf();
				time -= mod(time, MS_PER_SECOND);
		}
		this._d.setTime(time);
		hooks.updateOffset(this, true);
		return this;
	}
	function endOf(units) {
		var time, startOfDate;
		units = normalizeUnits(units);
		if (units === void 0 || units === "millisecond" || !this.isValid()) return this;
		startOfDate = this._isUTC ? utcStartOfDate : localStartOfDate;
		switch (units) {
			case "year":
				time = startOfDate(this.year() + 1, 0, 1) - 1;
				break;
			case "quarter":
				time = startOfDate(this.year(), this.month() - this.month() % 3 + 3, 1) - 1;
				break;
			case "month":
				time = startOfDate(this.year(), this.month() + 1, 1) - 1;
				break;
			case "week":
				time = startOfDate(this.year(), this.month(), this.date() - this.weekday() + 7) - 1;
				break;
			case "isoWeek":
				time = startOfDate(this.year(), this.month(), this.date() - (this.isoWeekday() - 1) + 7) - 1;
				break;
			case "day":
			case "date":
				time = startOfDate(this.year(), this.month(), this.date() + 1) - 1;
				break;
			case "hour":
				time = this._d.valueOf();
				time += MS_PER_HOUR - mod(time + (this._isUTC ? 0 : this.utcOffset() * MS_PER_MINUTE), MS_PER_HOUR) - 1;
				break;
			case "minute":
				time = this._d.valueOf();
				time += MS_PER_MINUTE - mod(time, MS_PER_MINUTE) - 1;
				break;
			case "second":
				time = this._d.valueOf();
				time += MS_PER_SECOND - mod(time, MS_PER_SECOND) - 1;
		}
		this._d.setTime(time);
		hooks.updateOffset(this, true);
		return this;
	}
	function valueOf$1() {
		return this._d.valueOf() - (this._offset || 0) * 6e4;
	}
	function unix() {
		return Math.floor(this.valueOf() / 1e3);
	}
	function toDate() {
		return new Date(this.valueOf());
	}
	function toArray() {
		var m = this;
		return [
			m.year(),
			m.month(),
			m.date(),
			m.hour(),
			m.minute(),
			m.second(),
			m.millisecond()
		];
	}
	function toObject() {
		var m = this;
		return {
			years: m.year(),
			months: m.month(),
			date: m.date(),
			hours: m.hours(),
			minutes: m.minutes(),
			seconds: m.seconds(),
			milliseconds: m.milliseconds()
		};
	}
	function toJSON() {
		return this.isValid() ? this.toISOString() : null;
	}
	function isValid() {
		return isValid$2(this);
	}
	function parsingFlags() {
		return extend({}, getParsingFlags(this));
	}
	function invalidAt() {
		return getParsingFlags(this).overflow;
	}
	function creationData() {
		return {
			input: this._i,
			format: this._f,
			locale: this._locale,
			isUTC: this._isUTC,
			strict: this._strict
		};
	}
	addFormatToken("N", 0, 0, "eraAbbr");
	addFormatToken("NN", 0, 0, "eraAbbr");
	addFormatToken("NNN", 0, 0, "eraAbbr");
	addFormatToken("NNNN", 0, 0, "eraName");
	addFormatToken("NNNNN", 0, 0, "eraNarrow");
	addFormatToken("y", ["y", 1], "yo", "eraYear");
	addFormatToken("y", ["yy", 2], 0, "eraYear");
	addFormatToken("y", ["yyy", 3], 0, "eraYear");
	addFormatToken("y", ["yyyy", 4], 0, "eraYear");
	addRegexToken("N", matchEraAbbr);
	addRegexToken("NN", matchEraAbbr);
	addRegexToken("NNN", matchEraAbbr);
	addRegexToken("NNNN", matchEraName);
	addRegexToken("NNNNN", matchEraNarrow);
	addParseToken([
		"N",
		"NN",
		"NNN",
		"NNNN",
		"NNNNN"
	], function(input, array, config, token) {
		var era = config._locale.erasParse(input, token, config._strict);
		if (era) getParsingFlags(config).era = era;
		else getParsingFlags(config).invalidEra = input;
	});
	addRegexToken("y", matchUnsigned);
	addRegexToken("yy", matchUnsigned);
	addRegexToken("yyy", matchUnsigned);
	addRegexToken("yyyy", matchUnsigned);
	addRegexToken("yo", matchEraYearOrdinal);
	addParseToken([
		"y",
		"yy",
		"yyy",
		"yyyy"
	], YEAR);
	addParseToken(["yo"], function(input, array, config, token) {
		var match;
		if (config._locale._eraYearOrdinalRegex) match = input.match(config._locale._eraYearOrdinalRegex);
		if (config._locale.eraYearOrdinalParse) array[YEAR] = config._locale.eraYearOrdinalParse(input, match);
		else array[YEAR] = parseInt(input, 10);
	});
	function localeEras(m, format) {
		var i, l, date, eras = this._eras || getLocale("en")._eras;
		for (i = 0, l = eras.length; i < l; ++i) {
			switch (typeof eras[i].since) {
				case "string":
					date = hooks(eras[i].since).startOf("day");
					eras[i].since = date.valueOf();
			}
			switch (typeof eras[i].until) {
				case "undefined":
					eras[i].until = Infinity;
					break;
				case "string":
					date = hooks(eras[i].until).startOf("day").valueOf();
					eras[i].until = date.valueOf();
			}
		}
		return eras;
	}
	function localeErasParse(eraName, format, strict) {
		var i, l, eras = this.eras(), name, abbr, narrow;
		eraName = eraName.toUpperCase();
		for (i = 0, l = eras.length; i < l; ++i) {
			name = eras[i].name.toUpperCase();
			abbr = eras[i].abbr.toUpperCase();
			narrow = eras[i].narrow.toUpperCase();
			if (strict) switch (format) {
				case "N":
				case "NN":
				case "NNN":
					if (abbr === eraName) return eras[i];
					break;
				case "NNNN":
					if (name === eraName) return eras[i];
					break;
				case "NNNNN": if (narrow === eraName) return eras[i];
			}
			else if ([
				name,
				abbr,
				narrow
			].indexOf(eraName) >= 0) return eras[i];
		}
	}
	function localeErasConvertYear(era, year) {
		var dir = era.since <= era.until ? 1 : -1;
		if (year === void 0) return hooks(era.since).year();
		else return hooks(era.since).year() + (year - era.offset) * dir;
	}
	function getEraName() {
		var i, l, val, eras = this.localeData().eras();
		for (i = 0, l = eras.length; i < l; ++i) {
			val = this.clone().startOf("day").valueOf();
			if (eras[i].since <= val && val <= eras[i].until) return eras[i].name;
			if (eras[i].until <= val && val <= eras[i].since) return eras[i].name;
		}
		return "";
	}
	function getEraNarrow() {
		var i, l, val, eras = this.localeData().eras();
		for (i = 0, l = eras.length; i < l; ++i) {
			val = this.clone().startOf("day").valueOf();
			if (eras[i].since <= val && val <= eras[i].until) return eras[i].narrow;
			if (eras[i].until <= val && val <= eras[i].since) return eras[i].narrow;
		}
		return "";
	}
	function getEraAbbr() {
		var i, l, val, eras = this.localeData().eras();
		for (i = 0, l = eras.length; i < l; ++i) {
			val = this.clone().startOf("day").valueOf();
			if (eras[i].since <= val && val <= eras[i].until) return eras[i].abbr;
			if (eras[i].until <= val && val <= eras[i].since) return eras[i].abbr;
		}
		return "";
	}
	function getEraYear() {
		var i, l, dir, val, eras = this.localeData().eras();
		for (i = 0, l = eras.length; i < l; ++i) {
			dir = eras[i].since <= eras[i].until ? 1 : -1;
			val = this.clone().startOf("day").valueOf();
			if (eras[i].since <= val && val <= eras[i].until || eras[i].until <= val && val <= eras[i].since) return (this.year() - hooks(eras[i].since).year()) * dir + eras[i].offset;
		}
		return this.year();
	}
	function erasNameRegex(isStrict) {
		if (!hasOwnProp(this, "_erasNameRegex")) computeErasParse.call(this);
		return isStrict ? this._erasNameRegex : this._erasRegex;
	}
	function erasAbbrRegex(isStrict) {
		if (!hasOwnProp(this, "_erasAbbrRegex")) computeErasParse.call(this);
		return isStrict ? this._erasAbbrRegex : this._erasRegex;
	}
	function erasNarrowRegex(isStrict) {
		if (!hasOwnProp(this, "_erasNarrowRegex")) computeErasParse.call(this);
		return isStrict ? this._erasNarrowRegex : this._erasRegex;
	}
	function matchEraAbbr(isStrict, locale) {
		return locale.erasAbbrRegex(isStrict);
	}
	function matchEraName(isStrict, locale) {
		return locale.erasNameRegex(isStrict);
	}
	function matchEraNarrow(isStrict, locale) {
		return locale.erasNarrowRegex(isStrict);
	}
	function matchEraYearOrdinal(isStrict, locale) {
		return locale._eraYearOrdinalRegex || matchUnsigned;
	}
	function computeErasParse() {
		var abbrPieces = [], namePieces = [], narrowPieces = [], mixedPieces = [], i, l, erasName, erasAbbr, erasNarrow, eras = this.eras();
		for (i = 0, l = eras.length; i < l; ++i) {
			erasName = regexEscape(eras[i].name);
			erasAbbr = regexEscape(eras[i].abbr);
			erasNarrow = regexEscape(eras[i].narrow);
			namePieces.push(erasName);
			abbrPieces.push(erasAbbr);
			narrowPieces.push(erasNarrow);
			mixedPieces.push(erasName);
			mixedPieces.push(erasAbbr);
			mixedPieces.push(erasNarrow);
		}
		this._erasRegex = new RegExp("^(" + mixedPieces.join("|") + ")", "i");
		this._erasNameRegex = new RegExp("^(" + namePieces.join("|") + ")", "i");
		this._erasAbbrRegex = new RegExp("^(" + abbrPieces.join("|") + ")", "i");
		this._erasNarrowRegex = new RegExp("^(" + narrowPieces.join("|") + ")", "i");
	}
	addFormatToken(0, ["gg", 2], 0, function() {
		return this.weekYear() % 100;
	});
	addFormatToken(0, ["GG", 2], 0, function() {
		return this.isoWeekYear() % 100;
	});
	function addWeekYearFormatToken(token, getter) {
		addFormatToken(0, [token, token.length], 0, getter);
	}
	addWeekYearFormatToken("gggg", "weekYear");
	addWeekYearFormatToken("ggggg", "weekYear");
	addWeekYearFormatToken("GGGG", "isoWeekYear");
	addWeekYearFormatToken("GGGGG", "isoWeekYear");
	addRegexToken("G", matchSigned);
	addRegexToken("g", matchSigned);
	addRegexToken("GG", match1to2, match2);
	addRegexToken("gg", match1to2, match2);
	addRegexToken("GGGG", match1to4, match4);
	addRegexToken("gggg", match1to4, match4);
	addRegexToken("GGGGG", match1to6, match6);
	addRegexToken("ggggg", match1to6, match6);
	addWeekParseToken([
		"gggg",
		"ggggg",
		"GGGG",
		"GGGGG"
	], function(input, week, config, token) {
		week[token.substr(0, 2)] = toInt(input);
	});
	addWeekParseToken(["gg", "GG"], function(input, week, config, token) {
		week[token] = hooks.parseTwoDigitYear(input);
	});
	function getSetWeekYear(input) {
		return getSetWeekYearHelper.call(this, input, this.week(), this.weekday() + this.localeData()._week.dow, this.localeData()._week.dow, this.localeData()._week.doy);
	}
	function getSetISOWeekYear(input) {
		return getSetWeekYearHelper.call(this, input, this.isoWeek(), this.isoWeekday(), 1, 4);
	}
	function getISOWeeksInYear() {
		return weeksInYear(this.year(), 1, 4);
	}
	function getISOWeeksInISOWeekYear() {
		return weeksInYear(this.isoWeekYear(), 1, 4);
	}
	function getWeeksInYear() {
		var weekInfo = this.localeData()._week;
		return weeksInYear(this.year(), weekInfo.dow, weekInfo.doy);
	}
	function getWeeksInWeekYear() {
		var weekInfo = this.localeData()._week;
		return weeksInYear(this.weekYear(), weekInfo.dow, weekInfo.doy);
	}
	function getSetWeekYearHelper(input, week, weekday, dow, doy) {
		var weeksTarget;
		if (input == null) return weekOfYear(this, dow, doy).year;
		else {
			weeksTarget = weeksInYear(input, dow, doy);
			if (week > weeksTarget) week = weeksTarget;
			return setWeekAll.call(this, input, week, weekday, dow, doy);
		}
	}
	function setWeekAll(weekYear, week, weekday, dow, doy) {
		var dayOfYearData = dayOfYearFromWeeks(weekYear, week, weekday, dow, doy), date = createUTCDate(dayOfYearData.year, 0, dayOfYearData.dayOfYear);
		this.year(date.getUTCFullYear());
		this.month(date.getUTCMonth());
		this.date(date.getUTCDate());
		return this;
	}
	addFormatToken("Q", 0, "Qo", "quarter");
	addRegexToken("Q", match1);
	addParseToken("Q", function(input, array) {
		array[MONTH] = (toInt(input) - 1) * 3;
	});
	function getSetQuarter(input) {
		return input == null ? Math.ceil((this.month() + 1) / 3) : this.month((input - 1) * 3 + this.month() % 3);
	}
	addFormatToken("D", ["DD", 2], "Do", "date");
	addRegexToken("D", match1to2, match1to2NoLeadingZero);
	addRegexToken("DD", match1to2, match2);
	addRegexToken("Do", function(isStrict, locale) {
		return isStrict ? locale._dayOfMonthOrdinalParse || locale._ordinalParse : locale._dayOfMonthOrdinalParseLenient;
	});
	addParseToken(["D", "DD"], DATE);
	addParseToken("Do", function(input, array) {
		array[DATE] = toInt(input.match(match1to2)[0]);
	});
	var getSetDayOfMonth = makeGetSet("Date", true);
	addFormatToken("DDD", ["DDDD", 3], "DDDo", "dayOfYear");
	addRegexToken("DDD", match1to3);
	addRegexToken("DDDD", match3);
	addParseToken(["DDD", "DDDD"], function(input, array, config) {
		config._dayOfYear = toInt(input);
	});
	function getSetDayOfYear(input) {
		var dayOfYear = Math.round((this.clone().startOf("day") - this.clone().startOf("year")) / 864e5) + 1;
		return input == null ? dayOfYear : this.add(input - dayOfYear, "d");
	}
	addFormatToken("m", ["mm", 2], 0, "minute");
	addRegexToken("m", match1to2, match1to2HasZero);
	addRegexToken("mm", match1to2, match2);
	addParseToken(["m", "mm"], MINUTE);
	var getSetMinute = makeGetSet("Minutes", false);
	addFormatToken("s", ["ss", 2], 0, "second");
	addRegexToken("s", match1to2, match1to2HasZero);
	addRegexToken("ss", match1to2, match2);
	addParseToken(["s", "ss"], SECOND);
	var getSetSecond = makeGetSet("Seconds", false);
	addFormatToken("S", 0, 0, function() {
		return ~~(this.millisecond() / 100);
	});
	addFormatToken(0, ["SS", 2], 0, function() {
		return ~~(this.millisecond() / 10);
	});
	addFormatToken(0, ["SSS", 3], 0, "millisecond");
	addFormatToken(0, ["SSSS", 4], 0, function() {
		return this.millisecond() * 10;
	});
	addFormatToken(0, ["SSSSS", 5], 0, function() {
		return this.millisecond() * 100;
	});
	addFormatToken(0, ["SSSSSS", 6], 0, function() {
		return this.millisecond() * 1e3;
	});
	addFormatToken(0, ["SSSSSSS", 7], 0, function() {
		return this.millisecond() * 1e4;
	});
	addFormatToken(0, ["SSSSSSSS", 8], 0, function() {
		return this.millisecond() * 1e5;
	});
	addFormatToken(0, ["SSSSSSSSS", 9], 0, function() {
		return this.millisecond() * 1e6;
	});
	addRegexToken("S", match1to3, match1);
	addRegexToken("SS", match1to3, match2);
	addRegexToken("SSS", match1to3, match3);
	var token = "SSSS";
	var getSetMillisecond;
	for (; token.length <= 9; token += "S") addRegexToken(token, matchUnsigned);
	function parseMs(input, array) {
		array[MILLISECOND] = toInt(("0." + input) * 1e3);
	}
	for (token = "S"; token.length <= 9; token += "S") addParseToken(token, parseMs);
	getSetMillisecond = makeGetSet("Milliseconds", false);
	addFormatToken("z", 0, 0, "zoneAbbr");
	addFormatToken("zz", 0, 0, "zoneName");
	function getZoneAbbr() {
		return this._isUTC ? "UTC" : "";
	}
	function getZoneName() {
		return this._isUTC ? "Coordinated Universal Time" : "";
	}
	var proto$2 = Moment.prototype;
	proto$2.add = add$1;
	proto$2.calendar = calendar;
	proto$2.clone = clone$1;
	proto$2.diff = diff;
	proto$2.endOf = endOf;
	proto$2.format = format;
	proto$2.from = from;
	proto$2.fromNow = fromNow;
	proto$2.to = to;
	proto$2.toNow = toNow;
	proto$2.get = stringGet;
	proto$2.invalidAt = invalidAt;
	proto$2.isAfter = isAfter;
	proto$2.isBefore = isBefore;
	proto$2.isBetween = isBetween;
	proto$2.isSame = isSame;
	proto$2.isSameOrAfter = isSameOrAfter;
	proto$2.isSameOrBefore = isSameOrBefore;
	proto$2.isValid = isValid;
	proto$2.lang = lang;
	proto$2.locale = locale;
	proto$2.localeData = localeData;
	proto$2.max = prototypeMax;
	proto$2.min = prototypeMin;
	proto$2.parsingFlags = parsingFlags;
	proto$2.set = stringSet;
	proto$2.startOf = startOf;
	proto$2.subtract = subtract$1;
	proto$2.toArray = toArray;
	proto$2.toObject = toObject;
	proto$2.toDate = toDate;
	proto$2.toISOString = toISOString$1;
	proto$2.inspect = inspect;
	if (typeof Symbol !== "undefined" && Symbol.for != null) proto$2[Symbol.for("nodejs.util.inspect.custom")] = function() {
		return "Moment<" + this.format() + ">";
	};
	proto$2.toJSON = toJSON;
	proto$2.toString = toString;
	proto$2.unix = unix;
	proto$2.valueOf = valueOf$1;
	proto$2.creationData = creationData;
	proto$2.eraName = getEraName;
	proto$2.eraNarrow = getEraNarrow;
	proto$2.eraAbbr = getEraAbbr;
	proto$2.eraYear = getEraYear;
	proto$2.year = getSetYear;
	proto$2.isLeapYear = getIsLeapYear;
	proto$2.weekYear = getSetWeekYear;
	proto$2.isoWeekYear = getSetISOWeekYear;
	proto$2.quarter = proto$2.quarters = getSetQuarter;
	proto$2.month = getSetMonth;
	proto$2.daysInMonth = getDaysInMonth;
	proto$2.week = proto$2.weeks = getSetWeek;
	proto$2.isoWeek = proto$2.isoWeeks = getSetISOWeek;
	proto$2.weeksInYear = getWeeksInYear;
	proto$2.weeksInWeekYear = getWeeksInWeekYear;
	proto$2.isoWeeksInYear = getISOWeeksInYear;
	proto$2.isoWeeksInISOWeekYear = getISOWeeksInISOWeekYear;
	proto$2.date = getSetDayOfMonth;
	proto$2.day = proto$2.days = getSetDayOfWeek;
	proto$2.weekday = getSetLocaleDayOfWeek;
	proto$2.isoWeekday = getSetISODayOfWeek;
	proto$2.dayOfYear = getSetDayOfYear;
	proto$2.hour = proto$2.hours = getSetHour;
	proto$2.minute = proto$2.minutes = getSetMinute;
	proto$2.second = proto$2.seconds = getSetSecond;
	proto$2.millisecond = proto$2.milliseconds = getSetMillisecond;
	proto$2.utcOffset = getSetOffset;
	proto$2.utc = setOffsetToUTC;
	proto$2.local = setOffsetToLocal;
	proto$2.parseZone = setOffsetToParsedOffset;
	proto$2.hasAlignedHourOffset = hasAlignedHourOffset;
	proto$2.isDST = isDaylightSavingTime;
	proto$2.isLocal = isLocal;
	proto$2.isUtcOffset = isUtcOffset;
	proto$2.isUtc = isUtc;
	proto$2.isUTC = isUtc;
	proto$2.zoneAbbr = getZoneAbbr;
	proto$2.zoneName = getZoneName;
	proto$2.dates = deprecate("dates accessor is deprecated. Use date instead.", getSetDayOfMonth);
	proto$2.months = deprecate("months accessor is deprecated. Use month instead", getSetMonth);
	proto$2.years = deprecate("years accessor is deprecated. Use year instead", getSetYear);
	proto$2.zone = deprecate("moment().zone is deprecated, use moment().utcOffset instead. http://momentjs.com/guides/#/warnings/zone/", getSetZone);
	proto$2.isDSTShifted = deprecate("isDSTShifted is deprecated. See http://momentjs.com/guides/#/warnings/dst-shifted/ for more information", isDaylightSavingTimeShifted);
	function createUnix(input) {
		return createLocal(input * 1e3);
	}
	function createInZone() {
		return createLocal.apply(null, arguments).parseZone();
	}
	function preParsePostFormat(string) {
		return string;
	}
	var proto$1 = Locale.prototype;
	proto$1.calendar = calendar$1;
	proto$1.longDateFormat = longDateFormat;
	proto$1.invalidDate = invalidDate;
	proto$1.ordinal = ordinal;
	proto$1.preparse = preParsePostFormat;
	proto$1.postformat = preParsePostFormat;
	proto$1.relativeTime = relativeTime$1;
	proto$1.pastFuture = pastFuture;
	proto$1.set = set;
	proto$1.eras = localeEras;
	proto$1.erasParse = localeErasParse;
	proto$1.erasConvertYear = localeErasConvertYear;
	proto$1.erasAbbrRegex = erasAbbrRegex;
	proto$1.erasNameRegex = erasNameRegex;
	proto$1.erasNarrowRegex = erasNarrowRegex;
	proto$1.months = localeMonths;
	proto$1.monthsShort = localeMonthsShort;
	proto$1.monthsParse = localeMonthsParse;
	proto$1.monthsRegex = monthsRegex;
	proto$1.monthsShortRegex = monthsShortRegex;
	proto$1.week = localeWeek;
	proto$1.firstDayOfYear = localeFirstDayOfYear;
	proto$1.firstDayOfWeek = localeFirstDayOfWeek;
	proto$1.weekdays = localeWeekdays;
	proto$1.weekdaysMin = localeWeekdaysMin;
	proto$1.weekdaysShort = localeWeekdaysShort;
	proto$1.weekdaysParse = localeWeekdaysParse;
	proto$1.weekdaysRegex = weekdaysRegex;
	proto$1.weekdaysShortRegex = weekdaysShortRegex;
	proto$1.weekdaysMinRegex = weekdaysMinRegex;
	proto$1.isPM = localeIsPM;
	proto$1.meridiem = localeMeridiem;
	function get$1(format, index, field, setter) {
		var locale = getLocale(), utc = createUTC().set(setter, index);
		return locale[field](utc, format);
	}
	function listMonthsImpl(format, index, field) {
		if (isNumber(format)) {
			index = format;
			format = void 0;
		}
		format = format || "";
		if (index != null) return get$1(format, index, field, "month");
		var i, out = [];
		for (i = 0; i < 12; i++) out[i] = get$1(format, i, field, "month");
		return out;
	}
	function listWeekdaysImpl(localeSorted, format, index, field) {
		if (typeof localeSorted === "boolean") {
			if (isNumber(format)) {
				index = format;
				format = void 0;
			}
			format = format || "";
		} else {
			format = localeSorted;
			index = format;
			localeSorted = false;
			if (isNumber(format)) {
				index = format;
				format = void 0;
			}
			format = format || "";
		}
		var locale = getLocale(), shift = localeSorted ? locale._week.dow : 0, i, out = [];
		if (index != null) return get$1(format, (index + shift) % 7, field, "day");
		for (i = 0; i < 7; i++) out[i] = get$1(format, (i + shift) % 7, field, "day");
		return out;
	}
	function listMonths(format, index) {
		return listMonthsImpl(format, index, "months");
	}
	function listMonthsShort(format, index) {
		return listMonthsImpl(format, index, "monthsShort");
	}
	function listWeekdays(localeSorted, format, index) {
		return listWeekdaysImpl(localeSorted, format, index, "weekdays");
	}
	function listWeekdaysShort(localeSorted, format, index) {
		return listWeekdaysImpl(localeSorted, format, index, "weekdaysShort");
	}
	function listWeekdaysMin(localeSorted, format, index) {
		return listWeekdaysImpl(localeSorted, format, index, "weekdaysMin");
	}
	getSetGlobalLocale("en", {
		eras: [{
			since: "0001-01-01",
			until: Infinity,
			offset: 1,
			name: "Anno Domini",
			narrow: "AD",
			abbr: "AD"
		}, {
			since: "0000-12-31",
			until: -Infinity,
			offset: 1,
			name: "Before Christ",
			narrow: "BC",
			abbr: "BC"
		}],
		dayOfMonthOrdinalParse: /\d{1,2}(th|st|nd|rd)/,
		ordinal: function(number) {
			var b = number % 10;
			return number + (toInt(number % 100 / 10) === 1 ? "th" : b === 1 ? "st" : b === 2 ? "nd" : b === 3 ? "rd" : "th");
		}
	});
	hooks.lang = deprecate("moment.lang is deprecated. Use moment.locale instead.", getSetGlobalLocale);
	hooks.langData = deprecate("moment.langData is deprecated. Use moment.localeData instead.", getLocale);
	var mathAbs = Math.abs;
	function abs$1() {
		var data = this._data;
		this._milliseconds = mathAbs(this._milliseconds);
		this._days = mathAbs(this._days);
		this._months = mathAbs(this._months);
		data.milliseconds = mathAbs(data.milliseconds);
		data.seconds = mathAbs(data.seconds);
		data.minutes = mathAbs(data.minutes);
		data.hours = mathAbs(data.hours);
		data.months = mathAbs(data.months);
		data.years = mathAbs(data.years);
		return this;
	}
	function addSubtract(duration, input, value, direction) {
		var other = createDuration(input, value);
		duration._milliseconds += direction * other._milliseconds;
		duration._days += direction * other._days;
		duration._months += direction * other._months;
		return duration._bubble();
	}
	function add(input, value) {
		return addSubtract(this, input, value, 1);
	}
	function subtract(input, value) {
		return addSubtract(this, input, value, -1);
	}
	function absCeil(number) {
		if (number < 0) return Math.floor(number);
		else return Math.ceil(number);
	}
	function bubble() {
		var milliseconds = this._milliseconds, days = this._days, months = this._months, data = this._data, seconds, minutes, hours, years, monthsFromDays;
		if (!(milliseconds >= 0 && days >= 0 && months >= 0 || milliseconds <= 0 && days <= 0 && months <= 0)) {
			milliseconds += absCeil(monthsToDays(months) + days) * 864e5;
			days = 0;
			months = 0;
		}
		data.milliseconds = milliseconds % 1e3;
		seconds = absFloor(milliseconds / 1e3);
		data.seconds = seconds % 60;
		minutes = absFloor(seconds / 60);
		data.minutes = minutes % 60;
		hours = absFloor(minutes / 60);
		data.hours = hours % 24;
		days += absFloor(hours / 24);
		monthsFromDays = absFloor(daysToMonths(days));
		months += monthsFromDays;
		days -= absCeil(monthsToDays(monthsFromDays));
		years = absFloor(months / 12);
		months %= 12;
		data.days = days;
		data.months = months;
		data.years = years;
		return this;
	}
	function daysToMonths(days) {
		return days * 4800 / 146097;
	}
	function monthsToDays(months) {
		return months * 146097 / 4800;
	}
	function as(units) {
		if (!this.isValid()) return NaN;
		var days, months, milliseconds = this._milliseconds;
		units = normalizeUnits(units);
		if (units === "month" || units === "quarter" || units === "year") {
			days = this._days + milliseconds / 864e5;
			months = this._months + daysToMonths(days);
			switch (units) {
				case "month": return months;
				case "quarter": return months / 3;
				case "year": return months / 12;
			}
		} else {
			days = this._days + Math.round(monthsToDays(this._months));
			switch (units) {
				case "week": return days / 7 + milliseconds / 6048e5;
				case "day": return days + milliseconds / 864e5;
				case "hour": return days * 24 + milliseconds / 36e5;
				case "minute": return days * 1440 + milliseconds / 6e4;
				case "second": return days * 86400 + milliseconds / 1e3;
				case "millisecond": return Math.floor(days * 864e5) + milliseconds;
				default: throw new Error("Unknown unit " + units);
			}
		}
	}
	function makeAs(alias) {
		return function() {
			return this.as(alias);
		};
	}
	var asMilliseconds = makeAs("ms");
	var asSeconds = makeAs("s");
	var asMinutes = makeAs("m");
	var asHours = makeAs("h");
	var asDays = makeAs("d");
	var asWeeks = makeAs("w");
	var asMonths = makeAs("M");
	var asQuarters = makeAs("Q");
	var asYears = makeAs("y");
	var valueOf = asMilliseconds;
	function clone() {
		return createDuration(this);
	}
	function get(units) {
		units = normalizeUnits(units);
		return this.isValid() ? this[units + "s"]() : NaN;
	}
	function makeGetter(name) {
		return function() {
			return this.isValid() ? this._data[name] : NaN;
		};
	}
	var milliseconds = makeGetter("milliseconds");
	var seconds = makeGetter("seconds");
	var minutes = makeGetter("minutes");
	var hours = makeGetter("hours");
	var days = makeGetter("days");
	var months = makeGetter("months");
	var years = makeGetter("years");
	function weeks() {
		return absFloor(this.days() / 7);
	}
	var round = Math.round;
	var thresholds = {
		ss: 44,
		s: 45,
		m: 45,
		h: 22,
		d: 26,
		w: null,
		M: 11
	};
	function substituteTimeAgo(string, number, withoutSuffix, isFuture, locale) {
		return relativeTimeWithoutPostformat.call(locale, number || 1, !!withoutSuffix, string, isFuture);
	}
	function relativeTime(posNegDuration, withoutSuffix, thresholds, locale) {
		var duration = createDuration(posNegDuration).abs(), seconds = round(duration.as("s")), minutes = round(duration.as("m")), hours = round(duration.as("h")), days = round(duration.as("d")), months = round(duration.as("M")), weeks = round(duration.as("w")), years = round(duration.as("y")), a = seconds <= thresholds.ss && ["s", seconds] || seconds < thresholds.s && ["ss", seconds] || minutes <= 1 && ["m"] || minutes < thresholds.m && ["mm", minutes] || hours <= 1 && ["h"] || hours < thresholds.h && ["hh", hours] || days <= 1 && ["d"] || days < thresholds.d && ["dd", days];
		if (thresholds.w != null) a = a || weeks <= 1 && ["w"] || weeks < thresholds.w && ["ww", weeks];
		a = a || months <= 1 && ["M"] || months < thresholds.M && ["MM", months] || years <= 1 && ["y"] || ["yy", years];
		a[2] = withoutSuffix;
		a[3] = +posNegDuration > 0;
		a[4] = locale;
		return substituteTimeAgo.apply(null, a);
	}
	function getSetRelativeTimeRounding(roundingFunction) {
		if (roundingFunction === void 0) return round;
		if (typeof roundingFunction === "function") {
			round = roundingFunction;
			return true;
		}
		return false;
	}
	function getSetRelativeTimeThreshold(threshold, limit) {
		if (thresholds[threshold] === void 0) return false;
		if (limit === void 0) return thresholds[threshold];
		thresholds[threshold] = limit;
		if (threshold === "s") thresholds.ss = limit - 1;
		return true;
	}
	function humanize(argWithSuffix, argThresholds) {
		if (!this.isValid()) return this.localeData().invalidDate();
		var withSuffix = false, th = thresholds, locale, output;
		if (typeof argWithSuffix === "object") {
			argThresholds = argWithSuffix;
			argWithSuffix = false;
		}
		if (typeof argWithSuffix === "boolean") withSuffix = argWithSuffix;
		if (typeof argThresholds === "object") {
			th = extend(extend({}, thresholds), argThresholds || {});
			if (argThresholds.s != null && argThresholds.ss == null) th.ss = argThresholds.s - 1;
		}
		locale = this.localeData();
		output = relativeTime(this, !withSuffix, th, locale);
		if (withSuffix) output = pastFutureWithoutPostformat.call(locale, +this, output);
		return locale.postformat(output);
	}
	var abs = Math.abs;
	function sign(x) {
		return (x > 0) - (x < 0) || +x;
	}
	function toISOString() {
		if (!this.isValid()) return this.localeData().invalidDate();
		var seconds = abs(this._milliseconds) / 1e3, days = abs(this._days), months = abs(this._months), minutes, hours, years, s, total = this.asSeconds(), totalSign, ymSign, daysSign, hmsSign;
		if (!total) return "P0D";
		minutes = absFloor(seconds / 60);
		hours = absFloor(minutes / 60);
		seconds %= 60;
		minutes %= 60;
		years = absFloor(months / 12);
		months %= 12;
		s = seconds ? seconds.toFixed(3).replace(/\.?0+$/, "") : "";
		totalSign = total < 0 ? "-" : "";
		ymSign = sign(this._months) !== sign(total) ? "-" : "";
		daysSign = sign(this._days) !== sign(total) ? "-" : "";
		hmsSign = sign(this._milliseconds) !== sign(total) ? "-" : "";
		return totalSign + "P" + (years ? ymSign + years + "Y" : "") + (months ? ymSign + months + "M" : "") + (days ? daysSign + days + "D" : "") + (hours || minutes || seconds ? "T" : "") + (hours ? hmsSign + hours + "H" : "") + (minutes ? hmsSign + minutes + "M" : "") + (seconds ? hmsSign + s + "S" : "");
	}
	var proto = Duration.prototype;
	proto.isValid = isValid$1;
	proto.abs = abs$1;
	proto.add = add;
	proto.subtract = subtract;
	proto.as = as;
	proto.asMilliseconds = asMilliseconds;
	proto.asSeconds = asSeconds;
	proto.asMinutes = asMinutes;
	proto.asHours = asHours;
	proto.asDays = asDays;
	proto.asWeeks = asWeeks;
	proto.asMonths = asMonths;
	proto.asQuarters = asQuarters;
	proto.asYears = asYears;
	proto.valueOf = valueOf;
	proto._bubble = bubble;
	proto.clone = clone;
	proto.get = get;
	proto.milliseconds = milliseconds;
	proto.seconds = seconds;
	proto.minutes = minutes;
	proto.hours = hours;
	proto.days = days;
	proto.weeks = weeks;
	proto.months = months;
	proto.years = years;
	proto.humanize = humanize;
	proto.toISOString = toISOString;
	proto.toString = toISOString;
	proto.toJSON = toISOString;
	proto.locale = locale;
	proto.localeData = localeData;
	proto.toIsoString = deprecate("toIsoString() is deprecated. Please use toISOString() instead (notice the capitals)", toISOString);
	proto.lang = lang;
	addFormatToken("X", 0, 0, "unix");
	addFormatToken("x", 0, 0, "valueOf");
	addRegexToken("x", matchSigned);
	addRegexToken("X", matchTimestamp);
	addParseToken("X", function(input, array, config) {
		config._d = /* @__PURE__ */ new Date(parseFloat(input) * 1e3);
	});
	addParseToken("x", function(input, array, config) {
		config._d = new Date(toInt(input));
	});
	//! moment.js
	hooks.version = "2.31.0";
	setHookCallback(createLocal);
	hooks.fn = proto$2;
	hooks.min = min;
	hooks.max = max;
	hooks.now = now;
	hooks.utc = createUTC;
	hooks.unix = createUnix;
	hooks.months = listMonths;
	hooks.isDate = isDate;
	hooks.locale = getSetGlobalLocale;
	hooks.invalid = createInvalid$1;
	hooks.duration = createDuration;
	hooks.isMoment = isMoment;
	hooks.weekdays = listWeekdays;
	hooks.parseZone = createInZone;
	hooks.localeData = getLocale;
	hooks.isDuration = isDuration;
	hooks.monthsShort = listMonthsShort;
	hooks.weekdaysMin = listWeekdaysMin;
	hooks.defineLocale = defineLocale;
	hooks.updateLocale = updateLocale;
	hooks.locales = listLocales;
	hooks.weekdaysShort = listWeekdaysShort;
	hooks.normalizeUnits = normalizeUnits;
	hooks.relativeTimeRounding = getSetRelativeTimeRounding;
	hooks.relativeTimeThreshold = getSetRelativeTimeThreshold;
	hooks.calendarFormat = getCalendarFormat;
	hooks.prototype = proto$2;
	hooks.HTML5_FMT = {
		DATETIME_LOCAL: "YYYY-MM-DDTHH:mm",
		DATETIME_LOCAL_SECONDS: "YYYY-MM-DDTHH:mm:ss",
		DATETIME_LOCAL_MS: "YYYY-MM-DDTHH:mm:ss.SSS",
		DATE: "YYYY-MM-DD",
		TIME: "HH:mm",
		TIME_SECONDS: "HH:mm:ss",
		TIME_MS: "HH:mm:ss.SSS",
		WEEK: "GGGG-[W]WW",
		MONTH: "YYYY-MM"
	};
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/components/DiscountComponent.vue
	var _sfc_main = {
		components: {
			DiscountListItemRemoveIconButton: DiscountListItemRemoveIconButton_default,
			DiscountSearchBoxPagination: DiscountSearchBoxPagination_default
		},
		data: () => {
			return {
				title: null,
				code: null,
				is_promotion: false,
				type: "coupon",
				is_unlimited: true,
				apply_via_url: false,
				display_at_checkout: false,
				description: null,
				quantity: 0,
				unlimited_time: true,
				start_date: hooks().format("Y-MM-DD"),
				start_time: "00:00",
				end_date: hooks().format("Y-MM-DD"),
				end_time: "23:59",
				type_option: "amount",
				discount_value: null,
				target: "all-orders",
				can_use_with_promotion: false,
				can_use_with_flash_sale: false,
				value_label: null,
				variants: { data: [] },
				selected_variants: [],
				selected_variant_ids: [],
				hidden_product_search_panel: true,
				product_collection_id: null,
				product_collections: [],
				product_category_id: null,
				product_categories: [],
				discount_on: "per-order",
				min_order_price: 0,
				product_quantity: 1,
				products: { data: [] },
				selected_products: [],
				selected_product_ids: [],
				product_keyword: null,
				customers: { data: [] },
				selected_customers: [],
				selected_customer_ids: [],
				customer_keyword: null,
				hidden_customer_search_panel: true,
				loading: false,
				discountUnit: "$"
			};
		},
		props: {
			currency: {
				type: String,
				default: () => null,
				required: true
			},
			dateFormat: {
				type: String,
				default: () => "Y-m-d",
				required: false
			},
			discount: {
				type: Object,
				default: () => null
			}
		},
		mounted: async function() {
			let context = this;
			$(document).on("click", "body", (e) => {
				let container = $(".box-search-advance");
				if (!container.is(e.target) && container.has(e.target).length === 0) {
					context.hidden_product_search_panel = true;
					context.hidden_customer_search_panel = true;
				}
			});
			this.value_label = this.__("discount.discount");
			this.discountUnit = this.currency;
			if (this.discount) {
				this.title = this.discount.title;
				this.type = this.discount.type;
				this.code = this.discount.code;
				this.is_promotion = this.type !== "coupon";
				this.start_date = hooks(this.discount.start_date).format("Y-MM-DD");
				this.start_time = hooks(this.discount.start_date).utc(false).format("HH:mm");
				if (this.discount.end_date) {
					this.end_date = hooks(this.discount.end_date).format("Y-MM-DD");
					this.end_time = hooks(this.discount.end_date).utc(false).format("HH:mm");
				}
				this.unlimited_time = !this.discount.end_date;
				this.is_unlimited = !this.discount.quantity;
				this.apply_via_url = this.discount.apply_via_url;
				this.display_at_checkout = this.discount.display_at_checkout;
				this.description = this.discount.description;
				this.type_option = this.discount.type_option;
				this.target = this.discount.target;
				this.product_quantity = this.discount.product_quantity;
				this.min_order_price = this.discount.min_order_price || 0;
				this.discount_on = this.discount.discount_on || "per-order";
				this.can_use_with_promotion = this.discount.can_use_with_promotion;
				this.can_use_with_flash_sale = this.discount.can_use_with_flash_sale;
				this.discount_value = this.discount.value;
				this.quantity = this.discount.quantity;
				this.discount.products.forEach((product) => {
					this.handleSelectProducts(product);
				});
				this.discount.customers.forEach((customer) => {
					this.handleSelectCustomers(customer);
				});
				if (this.discount.product_collections.length) {
					await this.getListProductCollections();
					this.product_collection_id = this.discount.product_collections[0].id;
				}
				if (this.discount.product_categories.length) {
					await this.getListProductCategories();
					this.product_category_id = this.discount.product_categories[0].id;
				}
				this.discount.product_variants.forEach((variant) => {
					variant.product_link = route("products.edit", variant.id);
					variant.product_name = variant.name;
					variant.variation_items = variant.variationItems;
					this.selected_variants.push(variant);
					this.selected_variant_ids.push(variant.id);
				});
				this.handleChangeTypeOption(false);
			}
		},
		methods: {
			generateCouponCode: function(event) {
				event.preventDefault();
				let context = this;
				axios.post(route("discounts.generate-coupon")).then((res) => {
					context.code = res.data.data;
					context.title = null;
					$(".coupon-code-input").closest("div").find(".invalid-feedback").remove();
				}).catch((res) => {
					Botble.handleError(res.response.data);
				});
			},
			changeDiscountType: function() {
				let context = this;
				if (context.type === "coupon") {
					context.is_promotion = false;
					context.code = context.title;
					context.title = null;
				} else {
					context.is_promotion = true;
					context.title = context.code;
					context.code = null;
				}
			},
			handleChangeTypeOption: function(resetTarget = true) {
				let context = this;
				context.discountUnit = context.currency;
				context.value_label = context.__("discount.discount");
				switch (context.type_option) {
					case "amount":
						if (resetTarget) context.target = "all-orders";
						break;
					case "percentage":
						if (resetTarget) context.target = "all-orders";
						context.discountUnit = "%";
						break;
					case "shipping":
						context.value_label = context.__("discount.when_shipping_fee_less_than");
						if (resetTarget) context.target = "all-orders";
						break;
					case "same-price":
						if (resetTarget) context.target = "group-products";
						context.value_label = context.__("discount.is");
						context.getListProductCollections();
				}
			},
			loadListProductsForSearch: function(include_variation = 1, page = 1, force = false) {
				let context = this;
				context.hidden_product_search_panel = false;
				$(".textbox-advancesearch").closest(".box-search-advance").find(".panel").removeClass("hidden");
				if (_.isEmpty(context.variants.data) || _.isEmpty(context.products.data) || force) {
					context.loading = true;
					axios.get(route("products.get-list-products-for-select", {
						keyword: context.product_keyword,
						include_variation,
						page
					})).then((res) => {
						if (include_variation) context.variants = res.data.data;
						else context.products = res.data.data;
						context.loading = false;
					}).catch((res) => {
						Botble.handleError(res.response.data);
					});
				}
			},
			handleSearchProduct: function(include_variation = 1, value) {
				if (value !== this.product_keyword) {
					let context = this;
					this.product_keyword = value;
					setTimeout(() => {
						context.loadListProductsForSearch(include_variation, 1, true);
					}, 500);
				}
			},
			handleChangeTarget: function() {
				let context = this;
				switch (context.target) {
					case "group-products":
						context.getListProductCollections();
						break;
					case "products-by-category":
						context.getListProductCategories();
						break;
					case "specific-product":
						context.selected_variant_ids = [];
						context.selected_customers = [];
						context.selected_customer_ids = [];
						break;
					case "product-variant":
						context.selected_products = [];
						context.selected_product_id = [];
						context.selected_customers = [];
						context.selected_customer_ids = [];
						break;
					case "customer":
						context.selected_products = [];
						context.selected_product_ids = [];
						context.selected_variant_ids = [];
				}
			},
			getListProductCollections: async function() {
				let context = this;
				if (_.isEmpty(context.product_collections)) {
					context.loading = true;
					await axios.get(route("product-collections.get-list-product-collections-for-select")).then((res) => {
						context.product_collections = res.data.data;
						if (!_.isEmpty(res.data.data)) context.product_collection_id = _.first(res.data.data).id;
						context.loading = false;
					}).catch((res) => {
						Botble.handleError(res.response.data);
					});
				}
			},
			getListProductCategories: async function() {
				let context = this;
				if (context.product_categories.length < 1) {
					context.loading = true;
					await $httpClient.make().get(route("product-categories.get-list-product-categories-for-select")).then(({ data }) => {
						context.product_categories = data.data;
						if (data.data.length > 0) context.product_category_id = data.data[0].id;
					}).catch(({ response }) => Botble.handleError(response.data)).finally(() => context.loading = false);
				}
			},
			loadListCustomersForSearch: function(page = 1, force = false) {
				let context = this;
				context.hidden_customer_search_panel = false;
				$(".textbox-advancesearch.customer").closest(".box-search-advance.customer").find(".panel").addClass("active");
				if (_.isEmpty(context.customers.data) || force) {
					context.loading = true;
					axios.get(route("customers.get-list-customers-for-search", {
						keyword: context.customer_keyword,
						page
					})).then((res) => {
						context.customers = res.data.data;
						context.loading = false;
					}).catch((res) => {
						Botble.handleError(res.response.data);
					});
				}
			},
			handleSearchCustomer: function(value) {
				if (value !== this.customer_keyword) {
					let context = this;
					this.customer_keyword = value;
					setTimeout(() => {
						context.loadListCustomersForSearch(1, true);
					}, 500);
				}
			},
			handleSelectProducts: function(item) {
				if (!_.includes(this.selected_product_ids, item.id)) {
					item.product_link = route("products.edit", item.id);
					this.selected_products.push(item);
					this.selected_product_ids.push(item.id);
				}
				this.hidden_product_search_panel = true;
			},
			handleRemoveProduct: function($event, currentItem) {
				$event.preventDefault();
				this.selected_product_ids = _.reject(this.selected_product_ids, (item) => {
					return item === currentItem.id;
				});
				this.selected_products = _.reject(this.selected_products, (item) => {
					return item.id === currentItem.id;
				});
			},
			handleSelectCustomers: function(item) {
				if (!_.includes(this.selected_customer_ids, item.id)) {
					item.customer_link = route("customers.edit", item.id);
					this.selected_customers.push(item);
					this.selected_customer_ids.push(item.id);
				}
				this.hidden_customer_search_panel = true;
			},
			handleRemoveCustomer: function($event, currentItem) {
				$event.preventDefault();
				this.selected_customer_ids = _.reject(this.selected_customer_ids, (item) => {
					return item === currentItem.id;
				});
				this.selected_customers = _.reject(this.selected_customers, (item) => {
					return item.id === currentItem.id;
				});
			},
			handleSelectVariants: function(productVariant, variation) {
				if (!_.includes(this.selected_variant_ids, variation.product_id)) {
					let variantItem = variation;
					variantItem.product_name = productVariant.name;
					variantItem.image_url = productVariant.image_url;
					variantItem.product_link = route("products.edit", variation.configurable_product_id);
					this.selected_variants.push(variantItem);
					this.selected_variant_ids.push(variation.product_id);
				}
				this.hidden_product_search_panel = true;
			},
			handleRemoveVariant: function($event, variant) {
				$event.preventDefault();
				this.selected_variant_ids = _.reject(this.selected_variant_ids, (item) => {
					return item === variant.product_id;
				});
				this.selected_variants = _.reject(this.selected_variants, (item) => {
					return item.product_id === variant.product_id;
				});
			}
		}
	};
	var _hoisted_1 = { class: "row" };
	var _hoisted_2 = { class: "col-md-8" };
	var _hoisted_3 = { class: "card" };
	var _hoisted_4 = { class: "card-body" };
	var _hoisted_5 = { class: "mb-3 position-relative" };
	var _hoisted_6 = { class: "form-label" };
	var _hoisted_7 = { value: "coupon" };
	var _hoisted_8 = { value: "promotion" };
	var _hoisted_9 = { class: "mb-3 position-relative" };
	var _hoisted_10 = { class: "form-label" };
	var _hoisted_11 = { class: "input-group input-group-flat" };
	var _hoisted_12 = { class: "input-group-text" };
	var _hoisted_13 = ["placeholder"];
	var _hoisted_14 = { class: "mb-3 position-relative" };
	var _hoisted_15 = { class: "form-check" };
	var _hoisted_16 = { class: "form-check-label" };
	var _hoisted_17 = { class: "mb-3 position-relative" };
	var _hoisted_18 = { class: "form-check" };
	var _hoisted_19 = { class: "form-check-label" };
	var _hoisted_20 = { class: "form-check-description" };
	var _hoisted_21 = { class: "mb-3 position-relative" };
	var _hoisted_22 = { class: "form-check" };
	var _hoisted_23 = { class: "form-check-label" };
	var _hoisted_24 = { class: "mb-3 position-relative" };
	var _hoisted_25 = { class: "form-label" };
	var _hoisted_26 = ["disabled"];
	var _hoisted_27 = { class: "mb-3 position-relative" };
	var _hoisted_28 = { class: "form-check" };
	var _hoisted_29 = { class: "form-check-label" };
	var _hoisted_30 = ["innerHTML"];
	var _hoisted_31 = { class: "mb-3 position-relative" };
	var _hoisted_32 = { class: "form-check" };
	var _hoisted_33 = { class: "form-check-label" };
	var _hoisted_34 = { class: "form-check-description" };
	var _hoisted_35 = { class: "mb-3 position-relative" };
	var _hoisted_36 = {
		for: "description",
		class: "form-label"
	};
	var _hoisted_37 = ["placeholder"];
	var _hoisted_38 = { class: "border-top pt-3" };
	var _hoisted_39 = { class: "row" };
	var _hoisted_40 = { class: "col-md-4 mb-3" };
	var _hoisted_41 = {
		class: "form-label",
		for: "discount-type-option"
	};
	var _hoisted_42 = { value: "amount" };
	var _hoisted_43 = { value: "percentage" };
	var _hoisted_44 = {
		key: 0,
		value: "shipping"
	};
	var _hoisted_45 = { value: "same-price" };
	var _hoisted_46 = {
		class: "form-label",
		for: "discount-value"
	};
	var _hoisted_47 = { class: "input-group" };
	var _hoisted_48 = { class: "input-group-text" };
	var _hoisted_49 = { class: "col-md-4 mb-3" };
	var _hoisted_50 = {
		class: "form-label",
		for: "select-offers"
	};
	var _hoisted_51 = {
		key: 0,
		value: "all-orders"
	};
	var _hoisted_52 = {
		key: 1,
		value: "amount-minimum-order"
	};
	var _hoisted_53 = { value: "group-products" };
	var _hoisted_54 = { value: "products-by-category" };
	var _hoisted_55 = { value: "specific-product" };
	var _hoisted_56 = {
		key: 2,
		value: "customer"
	};
	var _hoisted_57 = { value: "product-variant" };
	var _hoisted_58 = {
		key: 3,
		value: "once-per-customer"
	};
	var _hoisted_59 = {
		key: 0,
		class: "col-md-4 mb-3"
	};
	var _hoisted_60 = ["value"];
	var _hoisted_61 = {
		key: 1,
		class: "col-md-4 mb-3"
	};
	var _hoisted_62 = ["value", "innerHTML"];
	var _hoisted_63 = {
		key: 2,
		class: "col-md-4 mb-3"
	};
	var _hoisted_64 = { class: "position-relative box-search-advance product" };
	var _hoisted_65 = ["placeholder"];
	var _hoisted_66 = {
		key: 0,
		class: "loading-spinner"
	};
	var _hoisted_67 = {
		key: 1,
		class: "list-group list-group-flush overflow-auto",
		style: { "max-height": "25rem" }
	};
	var _hoisted_68 = ["onClick"];
	var _hoisted_69 = { class: "row align-items-center" };
	var _hoisted_70 = { class: "col-auto" };
	var _hoisted_71 = { class: "col text-truncate" };
	var _hoisted_72 = { class: "text-body d-block" };
	var _hoisted_73 = {
		key: 0,
		class: "p-3"
	};
	var _hoisted_74 = { class: "text-muted text-center mb-0" };
	var _hoisted_75 = {
		key: 2,
		class: "card-footer"
	};
	var _hoisted_76 = {
		key: 3,
		class: "col-md-4 mb-3"
	};
	var _hoisted_77 = { class: "position-relative box-search-advance customer" };
	var _hoisted_78 = ["placeholder"];
	var _hoisted_79 = {
		key: 0,
		class: "loading-spinner"
	};
	var _hoisted_80 = {
		key: 1,
		class: "list-group list-group-flush overflow-auto",
		style: { "max-height": "25rem" }
	};
	var _hoisted_81 = ["onClick"];
	var _hoisted_82 = { class: "row align-items-center" };
	var _hoisted_83 = { class: "col-auto" };
	var _hoisted_84 = { class: "col text-truncate" };
	var _hoisted_85 = { class: "text-body d-block" };
	var _hoisted_86 = ["href"];
	var _hoisted_87 = {
		key: 0,
		class: "p-3"
	};
	var _hoisted_88 = { class: "text-muted text-center mb-0" };
	var _hoisted_89 = {
		key: 2,
		class: "card-footer"
	};
	var _hoisted_90 = {
		key: 4,
		class: "col-md-4 mb-3"
	};
	var _hoisted_91 = { class: "position-relative box-search-advance product" };
	var _hoisted_92 = ["placeholder"];
	var _hoisted_93 = {
		key: 0,
		class: "loading-spinner"
	};
	var _hoisted_94 = {
		key: 1,
		class: "list-group list-group-flush overflow-auto",
		style: { "max-height": "25rem" }
	};
	var _hoisted_95 = {
		class: "list-group-item list-group-item-action",
		href: "javascript:void(0)"
	};
	var _hoisted_96 = {
		key: 0,
		class: "row align-items-start"
	};
	var _hoisted_97 = { class: "col-auto" };
	var _hoisted_98 = { class: "col text-truncate" };
	var _hoisted_99 = { class: "text-body d-block" };
	var _hoisted_100 = { class: "list-group list-group-flush" };
	var _hoisted_101 = ["onClick"];
	var _hoisted_102 = { class: "text-primary" };
	var _hoisted_103 = { key: 0 };
	var _hoisted_104 = { key: 0 };
	var _hoisted_105 = {
		key: 2,
		class: "card-footer"
	};
	var _hoisted_106 = {
		key: 5,
		class: "col-md-4 mb-3"
	};
	var _hoisted_107 = { value: "per-order" };
	var _hoisted_108 = { value: "per-every-item" };
	var _hoisted_109 = {
		key: 6,
		class: "col-md-4 mb-3"
	};
	var _hoisted_110 = { class: "input-group input-group-flat" };
	var _hoisted_111 = { class: "input-group-text" };
	var _hoisted_112 = { class: "mb-3 position-relative" };
	var _hoisted_113 = {
		class: "form-label",
		for: "product-quantity"
	};
	var _hoisted_114 = {
		key: 0,
		class: "list-group list-group-flush list-group-hoverable"
	};
	var _hoisted_115 = { class: "list-group-item" };
	var _hoisted_116 = { class: "row align-items-center" };
	var _hoisted_117 = { class: "col-auto" };
	var _hoisted_118 = { class: "col text-truncate" };
	var _hoisted_119 = ["href"];
	var _hoisted_120 = { class: "text-secondary text-truncate" };
	var _hoisted_121 = { key: 0 };
	var _hoisted_122 = { class: "col-auto" };
	var _hoisted_123 = {
		key: 1,
		class: "list-group list-group-flush list-group-hoverable"
	};
	var _hoisted_124 = { class: "list-group-item" };
	var _hoisted_125 = { class: "row align-items-center" };
	var _hoisted_126 = { class: "col-auto" };
	var _hoisted_127 = { class: "col text-truncate" };
	var _hoisted_128 = ["href"];
	var _hoisted_129 = { class: "col-auto" };
	var _hoisted_130 = {
		key: 2,
		class: "list-group list-group-flush list-group-hoverable"
	};
	var _hoisted_131 = { class: "list-group-item" };
	var _hoisted_132 = { class: "row align-items-center" };
	var _hoisted_133 = { class: "col-auto" };
	var _hoisted_134 = { class: "col text-truncate" };
	var _hoisted_135 = ["href"];
	var _hoisted_136 = { class: "text-secondary text-truncate" };
	var _hoisted_137 = { class: "col-auto" };
	var _hoisted_138 = { class: "col-md-4" };
	var _hoisted_139 = { class: "meta-boxes card mb-3" };
	var _hoisted_140 = { class: "card-header" };
	var _hoisted_141 = { class: "card-title" };
	var _hoisted_142 = { class: "card-body" };
	var _hoisted_143 = { class: "mb-3 position-relative" };
	var _hoisted_144 = { class: "form-label" };
	var _hoisted_145 = { class: "d-flex" };
	var _hoisted_146 = { class: "input-icon datepicker" };
	var _hoisted_147 = ["placeholder", "data-date-format"];
	var _hoisted_148 = { class: "input-icon" };
	var _hoisted_149 = { class: "mb-3 position-relative" };
	var _hoisted_150 = { class: "form-label" };
	var _hoisted_151 = { class: "d-flex" };
	var _hoisted_152 = { class: "input-icon datepicker" };
	var _hoisted_153 = [
		"placeholder",
		"data-date-format",
		"disabled"
	];
	var _hoisted_154 = { class: "input-icon" };
	var _hoisted_155 = ["disabled"];
	var _hoisted_156 = { class: "position-relative" };
	var _hoisted_157 = { class: "form-check" };
	var _hoisted_158 = { class: "form-check-label" };
	var _hoisted_159 = { class: "card" };
	var _hoisted_160 = { class: "card-body" };
	var _hoisted_161 = { class: "btn-list" };
	var _hoisted_162 = {
		type: "submit",
		name: "submitter",
		value: "apply",
		class: "btn btn-primary"
	};
	var _hoisted_163 = {
		type: "submit",
		name: "submitter",
		value: "save",
		class: "btn"
	};
	function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
		const _component_discount_search_box_pagination = (0, vue.resolveComponent)("discount-search-box-pagination");
		const _component_discount_list_item_remove_icon_button = (0, vue.resolveComponent)("discount-list-item-remove-icon-button");
		return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_1, [(0, vue.createElementVNode)("div", _hoisted_2, [(0, vue.createElementVNode)("div", _hoisted_3, [(0, vue.createElementVNode)("div", _hoisted_4, [
			(0, vue.createElementVNode)("div", _hoisted_5, [(0, vue.createElementVNode)("label", _hoisted_6, (0, vue.toDisplayString)(_ctx.__("discount.select_type_of_discount")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("select", {
				class: "form-select",
				id: "select-promotion",
				name: "type",
				"onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => _ctx.type = $event),
				onChange: _cache[1] || (_cache[1] = ($event) => $options.changeDiscountType())
			}, [(0, vue.createElementVNode)("option", _hoisted_7, (0, vue.toDisplayString)(_ctx.__("discount.coupon_code")), 1), (0, vue.createElementVNode)("option", _hoisted_8, (0, vue.toDisplayString)(_ctx.__("discount.promotion")), 1)], 544), [[vue.vModelSelect, _ctx.type]])]),
			(0, vue.createElementVNode)("div", _hoisted_9, [
				(0, vue.createElementVNode)("label", _hoisted_10, [_ctx.is_promotion ? ((0, vue.openBlock)(), (0, vue.createElementBlock)(vue.Fragment, { key: 0 }, [(0, vue.createTextVNode)((0, vue.toDisplayString)(_ctx.__("discount.create_discount_promotion")), 1)], 64)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)(vue.Fragment, { key: 1 }, [(0, vue.createTextVNode)((0, vue.toDisplayString)(_ctx.__("discount.create_coupon_code")), 1)], 64))]),
				(0, vue.withDirectives)((0, vue.createElementVNode)("div", _hoisted_11, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					type: "text",
					class: "form-control coupon-code-input",
					name: "code",
					"onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => _ctx.code = $event)
				}, null, 512), [[vue.vModelText, _ctx.code]]), (0, vue.createElementVNode)("span", _hoisted_12, [(0, vue.createElementVNode)("a", {
					href: "javascript:void(0)",
					onClick: _cache[3] || (_cache[3] = ($event) => $options.generateCouponCode($event)),
					class: "input-group-link"
				}, (0, vue.toDisplayString)(_ctx.__("discount.generate_coupon_code")), 1)])], 512), [[vue.vShow, !_ctx.is_promotion]]),
				(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					type: "text",
					class: "form-control",
					name: "title",
					"onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => _ctx.title = $event),
					placeholder: _ctx.__("discount.enter_promotion_name")
				}, null, 8, _hoisted_13), [[vue.vModelText, _ctx.title], [vue.vShow, _ctx.is_promotion]]),
				(0, vue.withDirectives)((0, vue.createElementVNode)("small", { class: "form-hint" }, (0, vue.toDisplayString)(_ctx.__("discount.customers_will_enter_this_coupon_code_when_they_checkout")) + ". ", 513), [[vue.vShow, !_ctx.is_promotion]])
			]),
			!_ctx.is_promotion ? ((0, vue.openBlock)(), (0, vue.createElementBlock)(vue.Fragment, { key: 0 }, [
				(0, vue.createElementVNode)("div", _hoisted_14, [(0, vue.createElementVNode)("label", _hoisted_15, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					class: "form-check-input",
					type: "checkbox",
					name: "can_use_with_promotion",
					"onUpdate:modelValue": _cache[5] || (_cache[5] = ($event) => _ctx.can_use_with_promotion = $event),
					value: "1"
				}, null, 512), [[vue.vModelCheckbox, _ctx.can_use_with_promotion]]), (0, vue.createElementVNode)("span", _hoisted_16, (0, vue.toDisplayString)(_ctx.__("discount.can_be_used_with_promotion")), 1)])]),
				(0, vue.createElementVNode)("div", _hoisted_17, [(0, vue.createElementVNode)("label", _hoisted_18, [
					(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						class: "form-check-input",
						type: "checkbox",
						name: "can_use_with_flash_sale",
						"onUpdate:modelValue": _cache[6] || (_cache[6] = ($event) => _ctx.can_use_with_flash_sale = $event),
						value: "1"
					}, null, 512), [[vue.vModelCheckbox, _ctx.can_use_with_flash_sale]]),
					(0, vue.createElementVNode)("span", _hoisted_19, (0, vue.toDisplayString)(_ctx.__("discount.can_be_used_with_flash_sale")), 1),
					(0, vue.createElementVNode)("span", _hoisted_20, (0, vue.toDisplayString)(_ctx.__("discount.can_be_used_with_flash_sale_help")), 1)
				])]),
				(0, vue.createElementVNode)("div", _hoisted_21, [(0, vue.createElementVNode)("label", _hoisted_22, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					class: "form-check-input",
					type: "checkbox",
					name: "is_unlimited",
					"onUpdate:modelValue": _cache[7] || (_cache[7] = ($event) => _ctx.is_unlimited = $event),
					value: "1"
				}, null, 512), [[vue.vModelCheckbox, _ctx.is_unlimited]]), (0, vue.createElementVNode)("span", _hoisted_23, (0, vue.toDisplayString)(_ctx.__("discount.unlimited_coupon")), 1)])]),
				(0, vue.withDirectives)((0, vue.createElementVNode)("div", _hoisted_24, [(0, vue.createElementVNode)("label", _hoisted_25, (0, vue.toDisplayString)(_ctx.__("discount.enter_number")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					type: "text",
					class: "form-control",
					name: "quantity",
					"onUpdate:modelValue": _cache[8] || (_cache[8] = ($event) => _ctx.quantity = $event),
					autocomplete: "off",
					disabled: _ctx.is_unlimited
				}, null, 8, _hoisted_26), [[vue.vModelText, _ctx.quantity]])], 512), [[vue.vShow, !_ctx.is_unlimited]]),
				(0, vue.createElementVNode)("div", _hoisted_27, [(0, vue.createElementVNode)("label", _hoisted_28, [
					(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						class: "form-check-input",
						type: "checkbox",
						name: "apply_via_url",
						"onUpdate:modelValue": _cache[9] || (_cache[9] = ($event) => _ctx.apply_via_url = $event),
						value: "1"
					}, null, 512), [[vue.vModelCheckbox, _ctx.apply_via_url]]),
					(0, vue.createElementVNode)("span", _hoisted_29, (0, vue.toDisplayString)(_ctx.__("discount.apply_via_url")), 1),
					(0, vue.createElementVNode)("span", {
						class: "form-check-description",
						innerHTML: _ctx.__("discount.apply_via_url_description")
					}, null, 8, _hoisted_30)
				])]),
				(0, vue.createElementVNode)("div", _hoisted_31, [(0, vue.createElementVNode)("label", _hoisted_32, [
					(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						class: "form-check-input",
						type: "checkbox",
						name: "display_at_checkout",
						"onUpdate:modelValue": _cache[10] || (_cache[10] = ($event) => _ctx.display_at_checkout = $event),
						value: "1"
					}, null, 512), [[vue.vModelCheckbox, _ctx.display_at_checkout]]),
					(0, vue.createElementVNode)("span", _hoisted_33, (0, vue.toDisplayString)(_ctx.__("discount.display_at_checkout")), 1),
					(0, vue.createElementVNode)("span", _hoisted_34, (0, vue.toDisplayString)(_ctx.__("discount.display_at_checkout_description")), 1)
				])]),
				(0, vue.withDirectives)((0, vue.createElementVNode)("div", _hoisted_35, [(0, vue.createElementVNode)("label", _hoisted_36, (0, vue.toDisplayString)(_ctx.__("discount.description")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("textarea", {
					name: "description",
					id: "description",
					class: "form-control",
					"onUpdate:modelValue": _cache[11] || (_cache[11] = ($event) => _ctx.description = $event),
					placeholder: _ctx.__("discount.description_placeholder")
				}, null, 8, _hoisted_37), [[vue.vModelText, _ctx.description]])], 512), [[vue.vShow, !_ctx.is_promotion && _ctx.display_at_checkout]])
			], 64)) : (0, vue.createCommentVNode)("v-if", true),
			(0, vue.createElementVNode)("div", _hoisted_38, [
				(0, vue.createElementVNode)("div", _hoisted_39, [
					(0, vue.createElementVNode)("div", _hoisted_40, [(0, vue.createElementVNode)("label", _hoisted_41, (0, vue.toDisplayString)(_ctx.__("discount.discount_type")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("select", {
						id: "discount-type-option",
						name: "type_option",
						class: "form-select",
						"onUpdate:modelValue": _cache[12] || (_cache[12] = ($event) => _ctx.type_option = $event),
						onChange: _cache[13] || (_cache[13] = ($event) => $options.handleChangeTypeOption())
					}, [
						(0, vue.createElementVNode)("option", _hoisted_42, (0, vue.toDisplayString)($props.currency), 1),
						(0, vue.createElementVNode)("option", _hoisted_43, (0, vue.toDisplayString)(_ctx.__("discount.percentage_discount")), 1),
						!_ctx.is_promotion ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("option", _hoisted_44, (0, vue.toDisplayString)(_ctx.__("discount.free_shipping")), 1)) : (0, vue.createCommentVNode)("v-if", true),
						(0, vue.createElementVNode)("option", _hoisted_45, (0, vue.toDisplayString)(_ctx.__("discount.same_price")), 1)
					], 544), [[vue.vModelSelect, _ctx.type_option]])]),
					(0, vue.createElementVNode)("div", { class: (0, vue.normalizeClass)(["mb-3", {
						"col-md-4": _ctx.type_option !== "shipping",
						"col-md-8": _ctx.type_option === "shipping"
					}]) }, [(0, vue.createElementVNode)("label", _hoisted_46, (0, vue.toDisplayString)(_ctx.value_label), 1), (0, vue.createElementVNode)("div", _hoisted_47, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "number",
						id: "discount-value",
						class: "form-control",
						name: "value",
						"onUpdate:modelValue": _cache[14] || (_cache[14] = ($event) => _ctx.discount_value = $event),
						autocomplete: "off",
						placeholder: "0"
					}, null, 512), [[vue.vModelText, _ctx.discount_value]]), (0, vue.createElementVNode)("span", _hoisted_48, (0, vue.toDisplayString)(_ctx.discountUnit), 1)])], 2),
					(0, vue.withDirectives)((0, vue.createElementVNode)("div", _hoisted_49, [(0, vue.createElementVNode)("label", _hoisted_50, (0, vue.toDisplayString)(_ctx.__("discount.apply_to")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("select", {
						id: "select-offers",
						class: "form-select",
						name: "target",
						"onUpdate:modelValue": _cache[15] || (_cache[15] = ($event) => _ctx.target = $event),
						onChange: _cache[16] || (_cache[16] = ($event) => $options.handleChangeTarget())
					}, [
						_ctx.type_option !== "same-price" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("option", _hoisted_51, (0, vue.toDisplayString)(_ctx.__("discount.all_orders")), 1)) : (0, vue.createCommentVNode)("v-if", true),
						_ctx.type_option !== "same-price" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("option", _hoisted_52, (0, vue.toDisplayString)(_ctx.__("discount.order_amount_from")), 1)) : (0, vue.createCommentVNode)("v-if", true),
						(0, vue.createElementVNode)("option", _hoisted_53, (0, vue.toDisplayString)(_ctx.__("discount.product_collection")), 1),
						(0, vue.createElementVNode)("option", _hoisted_54, (0, vue.toDisplayString)(_ctx.__("discount.product_category")), 1),
						(0, vue.createElementVNode)("option", _hoisted_55, (0, vue.toDisplayString)(_ctx.__("discount.product")), 1),
						_ctx.type_option !== "same-price" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("option", _hoisted_56, (0, vue.toDisplayString)(_ctx.__("discount.customer")), 1)) : (0, vue.createCommentVNode)("v-if", true),
						(0, vue.createElementVNode)("option", _hoisted_57, (0, vue.toDisplayString)(_ctx.__("discount.variant")), 1),
						_ctx.type_option !== "same-price" && _ctx.type === "coupon" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("option", _hoisted_58, (0, vue.toDisplayString)(_ctx.__("discount.once_per_customer")), 1)) : (0, vue.createCommentVNode)("v-if", true)
					], 544), [[vue.vModelSelect, _ctx.target]])], 512), [[vue.vShow, _ctx.type_option !== "shipping" && _ctx.type_option]]),
					_ctx.target === "group-products" && _ctx.type_option !== "shipping" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_59, [(0, vue.withDirectives)((0, vue.createElementVNode)("select", {
						name: "product_collections",
						class: "form-select",
						"onUpdate:modelValue": _cache[17] || (_cache[17] = ($event) => _ctx.product_collection_id = $event)
					}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.product_collections, (product_collection) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", { value: product_collection.id }, (0, vue.toDisplayString)(product_collection.name), 9, _hoisted_60);
					}), 256))], 512), [[vue.vModelSelect, _ctx.product_collection_id]])])) : (0, vue.createCommentVNode)("v-if", true),
					_ctx.target === "products-by-category" && _ctx.type_option !== "shipping" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_61, [(0, vue.withDirectives)((0, vue.createElementVNode)("select", {
						name: "product_categories",
						class: "form-select",
						"onUpdate:modelValue": _cache[18] || (_cache[18] = ($event) => _ctx.product_category_id = $event)
					}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.product_categories, (productCategory) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("option", {
							value: productCategory.id,
							innerHTML: productCategory.name
						}, null, 8, _hoisted_62);
					}), 256))], 512), [[vue.vModelSelect, _ctx.product_category_id]])])) : (0, vue.createCommentVNode)("v-if", true),
					_ctx.target === "specific-product" && _ctx.type_option !== "shipping" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_63, [(0, vue.createElementVNode)("div", _hoisted_64, [(0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control textbox-advancesearch",
						onClick: _cache[19] || (_cache[19] = ($event) => $options.loadListProductsForSearch(0)),
						onKeyup: _cache[20] || (_cache[20] = ($event) => $options.handleSearchProduct(0, $event.target.value)),
						placeholder: _ctx.__("discount.search_product")
					}, null, 40, _hoisted_65), (0, vue.createElementVNode)("div", {
						class: (0, vue.normalizeClass)(["card position-absolute w-100 z-1", {
							active: _ctx.products,
							hidden: _ctx.hidden_product_search_panel
						}]),
						style: (0, vue.normalizeStyle)([_ctx.loading ? { minHeight: "10rem" } : {}])
					}, [_ctx.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_66)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_67, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.products.data, (product) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
							class: "list-group-item list-group-item-action",
							onClick: ($event) => $options.handleSelectProducts(product),
							href: "javascript:void(0)"
						}, [(0, vue.createElementVNode)("div", _hoisted_69, [(0, vue.createElementVNode)("div", _hoisted_70, [(0, vue.createElementVNode)("span", {
							class: "avatar",
							style: (0, vue.normalizeStyle)({ backgroundImage: "url(" + product.image_url + ")" })
						}, null, 4)]), (0, vue.createElementVNode)("div", _hoisted_71, [(0, vue.createElementVNode)("div", _hoisted_72, (0, vue.toDisplayString)(product.name), 1)])])], 8, _hoisted_68);
					}), 256)), _ctx.products.data.length === 0 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_73, [(0, vue.createElementVNode)("p", _hoisted_74, (0, vue.toDisplayString)(_ctx.__("discount.no_products_found")), 1)])) : (0, vue.createCommentVNode)("v-if", true)])), (_ctx.products.next_page_url || _ctx.products.prev_page_url) && !_ctx.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_75, [(0, vue.createVNode)(_component_discount_search_box_pagination, {
						resource: _ctx.products,
						onOnPrev: _cache[21] || (_cache[21] = ($event) => $options.loadListProductsForSearch(0, _ctx.products.prev_page_url ? _ctx.products.current_page - 1 : _ctx.products.current_page, true)),
						onOnNext: _cache[22] || (_cache[22] = ($event) => $options.loadListProductsForSearch(0, _ctx.products.next_page_url ? _ctx.products.current_page + 1 : _ctx.products.current_page, true))
					}, null, 8, ["resource"])])) : (0, vue.createCommentVNode)("v-if", true)], 6)])])) : (0, vue.createCommentVNode)("v-if", true),
					_ctx.target === "customer" && _ctx.type_option !== "shipping" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_76, [(0, vue.createElementVNode)("div", _hoisted_77, [(0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control textbox-advancesearch customer",
						onClick: _cache[23] || (_cache[23] = ($event) => $options.loadListCustomersForSearch()),
						onKeyup: _cache[24] || (_cache[24] = ($event) => $options.handleSearchCustomer($event.target.value)),
						placeholder: _ctx.__("discount.search_customer")
					}, null, 40, _hoisted_78), (0, vue.createElementVNode)("div", {
						class: (0, vue.normalizeClass)(["card position-absolute w-100 z-1", {
							active: _ctx.customers,
							hidden: _ctx.hidden_customer_search_panel
						}]),
						style: (0, vue.normalizeStyle)([_ctx.loading ? { minHeight: "10rem" } : {}])
					}, [_ctx.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_79)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_80, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.customers.data, (customer) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
							class: "list-group-item list-group-item-action",
							onClick: ($event) => $options.handleSelectCustomers(customer),
							href: "javascript:void(0)"
						}, [(0, vue.createElementVNode)("div", _hoisted_82, [(0, vue.createElementVNode)("div", _hoisted_83, [(0, vue.createElementVNode)("span", {
							class: "avatar",
							style: (0, vue.normalizeStyle)({ backgroundImage: "url(" + customer.avatar_url + ")" })
						}, null, 4)]), (0, vue.createElementVNode)("div", _hoisted_84, [(0, vue.createElementVNode)("div", _hoisted_85, (0, vue.toDisplayString)(customer.name), 1), (0, vue.createElementVNode)("a", {
							href: `mailto:${customer.email}`,
							class: "text-secondary text-truncate mt-n1"
						}, (0, vue.toDisplayString)(customer.email), 9, _hoisted_86)])])], 8, _hoisted_81);
					}), 256)), _ctx.customers.data.length === 0 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_87, [(0, vue.createElementVNode)("p", _hoisted_88, (0, vue.toDisplayString)(_ctx.__("discount.no_customer_found")), 1)])) : (0, vue.createCommentVNode)("v-if", true)])), (_ctx.customers.next_page_url || _ctx.customers.prev_page_url) && !_ctx.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_89, [(0, vue.createVNode)(_component_discount_search_box_pagination, {
						resource: _ctx.customers,
						onOnPrev: _cache[25] || (_cache[25] = ($event) => $options.loadListCustomersForSearch(_ctx.customers.prev_page_url ? _ctx.customers.current_page - 1 : _ctx.customers.current_page, true)),
						onOnNext: _cache[26] || (_cache[26] = ($event) => $options.loadListCustomersForSearch(_ctx.customers.next_page_url ? _ctx.customers.current_page + 1 : _ctx.customers.current_page, true))
					}, null, 8, ["resource"])])) : (0, vue.createCommentVNode)("v-if", true)], 6)])])) : (0, vue.createCommentVNode)("v-if", true),
					_ctx.target === "product-variant" && _ctx.type_option !== "shipping" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_90, [(0, vue.createElementVNode)("div", _hoisted_91, [(0, vue.createElementVNode)("input", {
						type: "text",
						class: "form-control textbox-advancesearch",
						onClick: _cache[27] || (_cache[27] = ($event) => $options.loadListProductsForSearch()),
						onKeyup: _cache[28] || (_cache[28] = ($event) => $options.handleSearchProduct(1, $event.target.value)),
						placeholder: _ctx.__("discount.search_product")
					}, null, 40, _hoisted_92), (0, vue.createElementVNode)("div", {
						class: (0, vue.normalizeClass)(["card position-absolute w-100 z-1", {
							active: _ctx.variants,
							hidden: _ctx.hidden_product_search_panel
						}]),
						style: (0, vue.normalizeStyle)([_ctx.loading ? { minHeight: "10rem" } : {}])
					}, [_ctx.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_93)) : ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_94, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.variants.data, (product_variant) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("a", _hoisted_95, [product_variant.variations.length ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_96, [(0, vue.createElementVNode)("div", _hoisted_97, [(0, vue.createElementVNode)("span", {
							class: "avatar",
							style: (0, vue.normalizeStyle)({ backgroundImage: "url(" + product_variant.image_url + ")" })
						}, null, 4)]), (0, vue.createElementVNode)("div", _hoisted_98, [(0, vue.createElementVNode)("div", _hoisted_99, (0, vue.toDisplayString)(product_variant.name), 1), (0, vue.createElementVNode)("div", _hoisted_100, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(product_variant.variations, (variation) => {
							return (0, vue.openBlock)(), (0, vue.createElementBlock)("a", {
								onClick: ($event) => $options.handleSelectVariants(product_variant, variation),
								class: "list-group-item list-group-item-action small p-2 border-0"
							}, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(variation.variation_items, (variantItem, index) => {
								return (0, vue.openBlock)(), (0, vue.createElementBlock)("span", _hoisted_102, [(0, vue.createTextVNode)((0, vue.toDisplayString)(variantItem.attribute_title) + " ", 1), index !== variation.variation_items.length - 1 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", _hoisted_103, " / ")) : (0, vue.createCommentVNode)("v-if", true)]);
							}), 256))], 8, _hoisted_101);
						}), 256))])])])) : (0, vue.createCommentVNode)("v-if", true)]);
					}), 256)), _ctx.variants.data.length === 0 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_104, [(0, vue.createElementVNode)("span", null, (0, vue.toDisplayString)(_ctx.__("discount.no_products_found")), 1)])) : (0, vue.createCommentVNode)("v-if", true)])), (_ctx.variants.next_page_url || _ctx.variants.prev_page_url) && !_ctx.loading ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_105, [(0, vue.createVNode)(_component_discount_search_box_pagination, {
						resource: _ctx.variants,
						onOnPrev: _cache[29] || (_cache[29] = ($event) => $options.loadListProductsForSearch(1, _ctx.variants.prev_page_url ? _ctx.variants.current_page - 1 : _ctx.variants.current_page, true)),
						onOnNext: _cache[30] || (_cache[30] = ($event) => $options.loadListProductsForSearch(1, _ctx.variants.next_page_url ? _ctx.variants.current_page + 1 : _ctx.variants.current_page, true))
					}, null, 8, ["resource"])])) : (0, vue.createCommentVNode)("v-if", true)], 6)])])) : (0, vue.createCommentVNode)("v-if", true),
					!_ctx.is_promotion && (_ctx.target === "group-products" || _ctx.target === "products-by-category" || _ctx.target === "specific-product" || _ctx.target === "product-variant") && _ctx.type_option === "amount" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_106, [(0, vue.withDirectives)((0, vue.createElementVNode)("select", {
						class: "form-select",
						name: "discount_on",
						"onUpdate:modelValue": _cache[31] || (_cache[31] = ($event) => _ctx.discount_on = $event)
					}, [(0, vue.createElementVNode)("option", _hoisted_107, (0, vue.toDisplayString)(_ctx.__("discount.one_time_per_order")), 1), (0, vue.createElementVNode)("option", _hoisted_108, (0, vue.toDisplayString)(_ctx.__("discount.one_time_per_product_in_cart")), 1)], 512), [[vue.vModelSelect, _ctx.discount_on]])])) : (0, vue.createCommentVNode)("v-if", true),
					_ctx.target === "amount-minimum-order" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_109, [(0, vue.createElementVNode)("div", _hoisted_110, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "number",
						class: "form-control form-control--invisible",
						"onUpdate:modelValue": _cache[32] || (_cache[32] = ($event) => _ctx.min_order_price = $event),
						name: "min_order_price"
					}, null, 512), [[vue.vModelText, _ctx.min_order_price]]), (0, vue.createElementVNode)("span", _hoisted_111, (0, vue.toDisplayString)($props.currency), 1)])])) : (0, vue.createCommentVNode)("v-if", true)
				]),
				(0, vue.withDirectives)((0, vue.createElementVNode)("div", _hoisted_112, [(0, vue.createElementVNode)("label", _hoisted_113, (0, vue.toDisplayString)(_ctx.__("discount.number_of_products")), 1), (0, vue.withDirectives)((0, vue.createElementVNode)("input", {
					type: "text",
					class: "form-control",
					name: "product_quantity",
					id: "product-quantity",
					"onUpdate:modelValue": _cache[33] || (_cache[33] = ($event) => _ctx.product_quantity = $event)
				}, null, 512), [[vue.vModelText, _ctx.product_quantity]])], 512), [[vue.vShow, _ctx.is_promotion]]),
				_ctx.selected_variants.length && _ctx.target === "product-variant" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_114, [
					(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "hidden",
						"onUpdate:modelValue": _cache[34] || (_cache[34] = ($event) => _ctx.selected_variant_ids = $event),
						name: "variants"
					}, null, 512), [[vue.vModelText, _ctx.selected_variant_ids]]),
					(0, vue.createElementVNode)("h4", null, (0, vue.toDisplayString)(_ctx.__("discount.selected_products")), 1),
					((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.selected_variants, (variant) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_115, [(0, vue.createElementVNode)("div", _hoisted_116, [
							(0, vue.createElementVNode)("div", _hoisted_117, [(0, vue.createElementVNode)("span", {
								class: "avatar",
								style: (0, vue.normalizeStyle)({ backgroundImage: "url(" + variant.image_url + ")" })
							}, null, 4)]),
							(0, vue.createElementVNode)("div", _hoisted_118, [(0, vue.createElementVNode)("a", {
								href: variant.product_link,
								target: "_blank",
								class: "text-body d-block"
							}, (0, vue.toDisplayString)(variant.product_name), 9, _hoisted_119), (0, vue.createElementVNode)("div", _hoisted_120, [((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(variant.variation_items, (variantItem, index) => {
								return (0, vue.openBlock)(), (0, vue.createElementBlock)("span", null, [(0, vue.createTextVNode)((0, vue.toDisplayString)(variantItem.attribute_title) + " ", 1), index !== variant.variation_items.length - 1 ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("span", _hoisted_121, " / ")) : (0, vue.createCommentVNode)("v-if", true)]);
							}), 256))])]),
							(0, vue.createElementVNode)("div", _hoisted_122, [(0, vue.createVNode)(_component_discount_list_item_remove_icon_button, { onClick: ($event) => $options.handleRemoveVariant($event, variant) }, null, 8, ["onClick"])])
						])]);
					}), 256))
				])) : (0, vue.createCommentVNode)("v-if", true),
				_ctx.selected_products.length && _ctx.target === "specific-product" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_123, [
					(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "hidden",
						"onUpdate:modelValue": _cache[35] || (_cache[35] = ($event) => _ctx.selected_product_ids = $event),
						name: "products"
					}, null, 512), [[vue.vModelText, _ctx.selected_product_ids]]),
					(0, vue.createElementVNode)("h4", null, (0, vue.toDisplayString)(_ctx.__("discount.selected_products")), 1),
					((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.selected_products, (product) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_124, [(0, vue.createElementVNode)("div", _hoisted_125, [
							(0, vue.createElementVNode)("div", _hoisted_126, [(0, vue.createElementVNode)("span", {
								class: "avatar",
								style: (0, vue.normalizeStyle)({ backgroundImage: "url(" + product.image_url + ")" })
							}, null, 4)]),
							(0, vue.createElementVNode)("div", _hoisted_127, [(0, vue.createElementVNode)("a", {
								href: product.product_link,
								class: "text-body d-block",
								target: "_blank"
							}, (0, vue.toDisplayString)(product.name), 9, _hoisted_128)]),
							(0, vue.createElementVNode)("div", _hoisted_129, [(0, vue.createVNode)(_component_discount_list_item_remove_icon_button, { onClick: ($event) => $options.handleRemoveProduct($event, product) }, null, 8, ["onClick"])])
						])]);
					}), 256))
				])) : (0, vue.createCommentVNode)("v-if", true),
				_ctx.selected_customers.length && _ctx.target === "customer" ? ((0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_130, [
					(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
						type: "hidden",
						"onUpdate:modelValue": _cache[36] || (_cache[36] = ($event) => _ctx.selected_customer_ids = $event),
						name: "customers"
					}, null, 512), [[vue.vModelText, _ctx.selected_customer_ids]]),
					(0, vue.createElementVNode)("h4", null, (0, vue.toDisplayString)(_ctx.__("discount.selected_customers")), 1),
					((0, vue.openBlock)(true), (0, vue.createElementBlock)(vue.Fragment, null, (0, vue.renderList)(_ctx.selected_customers, (customer) => {
						return (0, vue.openBlock)(), (0, vue.createElementBlock)("div", _hoisted_131, [(0, vue.createElementVNode)("div", _hoisted_132, [
							(0, vue.createElementVNode)("div", _hoisted_133, [(0, vue.createElementVNode)("span", {
								class: "avatar",
								style: (0, vue.normalizeStyle)({ backgroundImage: "url(" + customer.avatar_url + ")" })
							}, null, 4)]),
							(0, vue.createElementVNode)("div", _hoisted_134, [(0, vue.createElementVNode)("a", {
								href: customer.customer_link,
								class: "text-body d-block",
								target: "_blank"
							}, (0, vue.toDisplayString)(customer.name), 9, _hoisted_135), (0, vue.createElementVNode)("div", _hoisted_136, (0, vue.toDisplayString)(customer.email), 1)]),
							(0, vue.createElementVNode)("div", _hoisted_137, [(0, vue.createVNode)(_component_discount_list_item_remove_icon_button, { onClick: ($event) => $options.handleRemoveCustomer($event, customer) }, null, 8, ["onClick"])])
						])]);
					}), 256))
				])) : (0, vue.createCommentVNode)("v-if", true)
			])
		])])]), (0, vue.createElementVNode)("div", _hoisted_138, [(0, vue.createElementVNode)("div", _hoisted_139, [(0, vue.createElementVNode)("div", _hoisted_140, [(0, vue.createElementVNode)("h4", _hoisted_141, (0, vue.toDisplayString)(_ctx.__("discount.time")), 1)]), (0, vue.createElementVNode)("div", _hoisted_142, [
			(0, vue.createElementVNode)("div", _hoisted_143, [(0, vue.createElementVNode)("label", _hoisted_144, (0, vue.toDisplayString)(_ctx.__("discount.start_date")), 1), (0, vue.createElementVNode)("div", _hoisted_145, [(0, vue.createElementVNode)("div", _hoisted_146, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
				type: "text",
				placeholder: $props.dateFormat,
				"data-date-format": $props.dateFormat,
				name: "start_date",
				"onUpdate:modelValue": _cache[37] || (_cache[37] = ($event) => _ctx.start_date = $event),
				class: "form-control rounded-end-0",
				readonly: "",
				"data-input": ""
			}, null, 8, _hoisted_147), [[vue.vModelText, _ctx.start_date]]), _cache[42] || (_cache[42] = (0, vue.createStaticVNode)("<span class=\"input-icon-addon\"><svg xmlns=\"http://www.w3.org/2000/svg\" class=\"icon\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"></path><path d=\"M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z\"></path><path d=\"M16 3v4\"></path><path d=\"M8 3v4\"></path><path d=\"M4 11h16\"></path><path d=\"M11 15h1\"></path><path d=\"M12 15v3\"></path></svg></span>", 1))]), (0, vue.createElementVNode)("div", _hoisted_148, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
				type: "text",
				placeholder: "hh:mm",
				name: "start_time",
				"onUpdate:modelValue": _cache[38] || (_cache[38] = ($event) => _ctx.start_time = $event),
				class: "form-control rounded-start-0 timepicker timepicker-24"
			}, null, 512), [[vue.vModelText, _ctx.start_time]]), _cache[43] || (_cache[43] = (0, vue.createStaticVNode)("<span class=\"input-icon-addon\"><svg xmlns=\"http://www.w3.org/2000/svg\" class=\"icon icon-tabler icon-tabler-clock\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"></path><path d=\"M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0\"></path><path d=\"M12 7v5l3 3\"></path></svg></span>", 1))])])]),
			(0, vue.createElementVNode)("div", _hoisted_149, [(0, vue.createElementVNode)("label", _hoisted_150, (0, vue.toDisplayString)(_ctx.__("discount.end_date")), 1), (0, vue.createElementVNode)("div", _hoisted_151, [(0, vue.createElementVNode)("div", _hoisted_152, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
				type: "text",
				placeholder: $props.dateFormat,
				"data-date-format": $props.dateFormat,
				name: "end_date",
				"onUpdate:modelValue": _cache[39] || (_cache[39] = ($event) => _ctx.end_date = $event),
				class: "form-control rounded-end-0",
				disabled: _ctx.unlimited_time,
				readonly: "",
				"data-input": ""
			}, null, 8, _hoisted_153), [[vue.vModelText, _ctx.end_date]]), _cache[44] || (_cache[44] = (0, vue.createStaticVNode)("<span class=\"input-icon-addon\"><svg xmlns=\"http://www.w3.org/2000/svg\" class=\"icon\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"></path><path d=\"M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z\"></path><path d=\"M16 3v4\"></path><path d=\"M8 3v4\"></path><path d=\"M4 11h16\"></path><path d=\"M11 15h1\"></path><path d=\"M12 15v3\"></path></svg></span>", 1))]), (0, vue.createElementVNode)("div", _hoisted_154, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
				type: "text",
				placeholder: "hh:mm",
				name: "end_time",
				"onUpdate:modelValue": _cache[40] || (_cache[40] = ($event) => _ctx.end_time = $event),
				class: "form-control rounded-start-0 timepicker timepicker-24",
				disabled: _ctx.unlimited_time
			}, null, 8, _hoisted_155), [[vue.vModelText, _ctx.end_time]]), _cache[45] || (_cache[45] = (0, vue.createStaticVNode)("<span class=\"input-icon-addon\"><svg xmlns=\"http://www.w3.org/2000/svg\" class=\"icon icon-tabler icon-tabler-clock\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"></path><path d=\"M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0\"></path><path d=\"M12 7v5l3 3\"></path></svg></span>", 1))])])]),
			(0, vue.createElementVNode)("div", _hoisted_156, [(0, vue.createElementVNode)("label", _hoisted_157, [(0, vue.withDirectives)((0, vue.createElementVNode)("input", {
				class: "form-check-input",
				type: "checkbox",
				name: "unlimited_time",
				"onUpdate:modelValue": _cache[41] || (_cache[41] = ($event) => _ctx.unlimited_time = $event),
				value: "1"
			}, null, 512), [[vue.vModelCheckbox, _ctx.unlimited_time]]), (0, vue.createElementVNode)("span", _hoisted_158, (0, vue.toDisplayString)(_ctx.__("discount.never_expired")), 1)])])
		])]), (0, vue.createElementVNode)("div", _hoisted_159, [(0, vue.createElementVNode)("div", _hoisted_160, [(0, vue.createElementVNode)("div", _hoisted_161, [(0, vue.createElementVNode)("button", _hoisted_162, [_cache[46] || (_cache[46] = (0, vue.createStaticVNode)("<svg xmlns=\"http://www.w3.org/2000/svg\" class=\"icon\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"></path><path d=\"M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2\"></path><path d=\"M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0\"></path><path d=\"M14 4l0 4l-6 0l0 -4\"></path></svg>", 1)), (0, vue.createTextVNode)(" " + (0, vue.toDisplayString)(_ctx.__("core/base::forms.save_and_continue")), 1)]), (0, vue.createElementVNode)("button", _hoisted_163, [_cache[47] || (_cache[47] = (0, vue.createElementVNode)("svg", {
			xmlns: "http://www.w3.org/2000/svg",
			class: "icon",
			width: "24",
			height: "24",
			viewBox: "0 0 24 24",
			"stroke-width": "2",
			stroke: "currentColor",
			fill: "none",
			"stroke-linecap": "round",
			"stroke-linejoin": "round"
		}, [
			(0, vue.createElementVNode)("path", {
				stroke: "none",
				d: "M0 0h24v24H0z",
				fill: "none"
			}),
			(0, vue.createElementVNode)("path", { d: "M14 3v4a1 1 0 0 0 1 1h4" }),
			(0, vue.createElementVNode)("path", { d: "M5 13v-8a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5.5m-9.5 -2h7m-3 -3l3 3l-3 3" })
		], -1)), (0, vue.createTextVNode)(" " + (0, vue.toDisplayString)(_ctx.__("core/base::forms.save")), 1)])])])])])]);
	}
	var DiscountComponent_default = /*#__PURE__*/ _plugin_vue_export_helper_default(_sfc_main, [["render", _sfc_render]]);
	//#endregion
	//#region platform/plugins/ecommerce/resources/js/discount.js
	if (typeof vueApp !== "undefined") vueApp.booting((vue) => {
		vue.component("discount-component", DiscountComponent_default);
	});
	//#endregion
})(Vue);

//# sourceMappingURL=discount.js.map