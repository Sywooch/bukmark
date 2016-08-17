var INTERVAL_MILLIS = 60000;

setInterval(function() {
	$.pjax.reload({container: "#estimates-gridview"});
}, INTERVAL_MILLIS);