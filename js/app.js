// Create the root namespace
var App = function() {
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

	return {};
}();