(function() {
	//#region platform/packages/revision/resources/js/revision.js
	$(() => {
		$.each($(".html-diff-content"), function(index, item) {
			$(item).html(htmldiff($(item).data("original"), $(item).html()));
		});
	});
	//#endregion
})();

//# sourceMappingURL=revision.js.map