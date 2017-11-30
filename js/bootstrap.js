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
        
        /*
         * Ledger collapse
         */
        $('.ledger .collapse').on('shown.bs.collapse', function() {
            $(this).parent().removeClass('closed').addClass('opened');
        });
        
        $('.ledger .collapse').on('hidden.bs.collapse', function() {
            $(this).not('.show').parent().removeClass('opened').addClass('closed');
        });
	}

	return {
        listener: listener
    };
}();