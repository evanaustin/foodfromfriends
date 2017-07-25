// Create the root namespace
var App = function() {
	/*
	* Parsley
	*/
	$('form[data-parsley-validate]').parsley({
		errorClass: 'has-danger',
		successClass: 'has-success',
		classHandler: function(ParsleyField) {
			return ParsleyField.$element.parents('.form-group');
		},
		errorsContainer: function(ParsleyField) {
			return ParsleyField.$element.parents('.form-group');
		},
		errorsWrapper: '<div class="form-control-feedback"></div>',
		errorTemplate: '<span></span>'
	});

	window.Parsley.on('field:error', function() {
		this.$element.addClass('form-control-danger');
	});

	window.Parsley.on('field:success', function() {
		this.$element.addClass('form-control-success');
	});

	/*
	* Collapse
	*/
	var $nav = $('.nav-item');
	$nav.on('show.bs.collapse','.collapse', function() {
		$nav.find('.collapse').collapse('hide');
	});

	/*
	* Log out
	*/
	$('a#log-out').on('click', function() {
		App.Ajax.post('account/log_out', null, 
			function(response) {
				window.location.replace(PUBLIC_ROOT);
			},
			function(response) {
				// should implement toastr here
				// console.log(response.error);
			}
		);
	});

	return {};
}();