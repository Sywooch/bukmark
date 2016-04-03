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
		if ($(elem).attr('id') != 'no-image-selected') {
			$(elem).remove();
		}
	});
}

function populateImages(images) {
	clearImages();
	images.forEach(function (elem) {
		var img = $("<img src=\"" + elem.url + "\" id=\"image_" + elem.id + "\" class=\"unselected-image product-image\">");
		img.appendTo($("#images"));
	});
	$(".product-image").click(function () {
		var id = $(this).attr('id');
		var imageId = null;
		if (id != 'no-image-selected') {
			imageId = parseInt(id.replace('image_', ''));
		}
		selectImage(imageId);
	});
	selectImage($("#product_image_id").val());
}

function selectImage(imageId) {
	$("#product_image_id").val(imageId);
	var imgId = "#no-image-selected";
	if (imageId) {
		imgId = "#image_" + imageId;
	}
	$(".product-image").removeClass("selected-image");
	$(".product-image").addClass("unselected-image");
	$(imgId).removeClass("unselected-image");
	$(imgId).addClass("selected-image");
}

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
		$("#product_image_id").val(null);
		setSelection();
	});
});