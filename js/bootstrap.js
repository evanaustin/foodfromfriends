App.Bootstrap = function() {
	function listener() {
		/*
		* Collapse
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