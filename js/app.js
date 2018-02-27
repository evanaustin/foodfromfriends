// Root namespace
var App = function() {
    /*
     * Create & initialize a new instance of Slidebars
     */
    this.Slidebar = new slidebars();
    Slidebar.init();

	function listener() {
        /*
         * Set up mobile nav
         */
        $('#mobile-nav').on('click', function(e) {
            App.Util.slidebar(Slidebar, 'toggle', 'left', e);
        });

        var nav = document.getElementById('nav');
        var navswipe = new Hammer(nav);
        
        navswipe.on('swipeleft', function() {
            App.Util.slidebar(Slidebar, 'close', 'left');
        });

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
        Slidebar: this.Slidebar,
		listener: listener
	};
}();