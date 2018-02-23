// Root namespace
var App = function() {
	function listener() {
        /*
         * Show the tile cards after the images load
         */
		$('.card').each(function(i, obj) {
			$(this).imagesLoaded(function() {
				setTimeout(function() {
					$(obj).find('.card-img-top div.loading').addClass('hidden');
					$(obj).find('.card-img-top img').removeClass('hidden');
				}, 500 * i);
			});
		});

		$('.card').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
			$(this).removeClass('animated fadeIn zoomIn bounceIn slideIn');
        });
        
        /*
         * Toastr options
         */
        toastr.options = {
            toastClass: 'alert',
            iconClasses: {
                error: 'alert-danger',
                info: 'alert-info',
                success: 'alert-success',
                warning: 'alert-warning'
            }
        }
	}

	return {
		listener: listener
	};
}();