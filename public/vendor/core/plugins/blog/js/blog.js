(function() {
	//#region platform/plugins/blog/resources/js/blog.js
	$(() => {
		"use strict";
		BDashboard.loadWidget($("#widget_posts_recent").find(".widget-content"), $("#widget_posts_recent").data("url"));
	});
	//#endregion
})();

//# sourceMappingURL=blog.js.map