$('#estimate-entries-gridview').on('pjax:end',   function() {
	$.pjax.reload({container: "#estimate-view"});
});