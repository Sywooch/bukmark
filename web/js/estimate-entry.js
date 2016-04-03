function getProductImages(productId) {
	var images = [];
	url = productImagesUrl.replace('placeholder', productId);
	$.ajax({
		type: 'POST',
		url: url,
		success: function (data) {
			populateImages(data);
		},
		dataType: 'json'
	});
	return images;
}

function clearImages() {
	$('#images').children().each(function (idx, elem) {
		if($(elem).attr('id') != 'no-image-selected') {
			$(elem).remove();
		} 
	});
}

function populateImages(images) {
	clearImages();
	images.forEach(function (elem) {
		var img = $("<img src=\"" + elem.url + "\" id=\"image_" + elem.id + "\" class=\"unselected-image product-image\">");
		img.appendTo($("#images"));
	}
);}

function setSelection() {
	var productId = $("#product_id").val();
	if (productId) {
		getProductImages(productId);
	} else {
		populateImages([]);
	}
}

$(document).ready(function () {
	setSelection();

	$("#product_id").change(function () {
		setSelection();
	});
});