// Create the root namespace
var App = function() {
	function listener() {
		// show the tile cards after the images load
		$('div.card').each(function(i, obj) {
			$(this).imagesLoaded(function() {
				setTimeout(function() {
					$(obj).find('div.card-img-top div.loading').addClass('hidden');
					$(obj).find('div.card-img-top img').removeClass('hidden');
				}, 200 * i);
			});
		});

		$('div.card').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
			$(this).removeClass('animated');
		});
	}

	return {
		listener: listener
	};
}();