App.Bootstrap = function() {
	function listener() {
		/*
		* Enable tooltips
		*/
		$(function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
		
		/*
		* Activate collapse
		*/
		var $nav = $('.nav-item');

		$nav.on('show.bs.collapse','.collapse', function() {
			$nav.find('.collapse').collapse('hide');
		});
	}

	return {
        listener: listener
    };
}();