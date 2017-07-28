$('#save-pickup').on('submit', function(e) {
	e.preventDefault();
    
    $form = $(this);
    
    App.Ajax.post('dashboard/exchange-settings/pickup', $form.serialize(), 
		function(response) {
            toastr.success('Your listed has been created!')
		},
		function(response) {
            $form.siblings('div.alert').addClass('alert-danger').html('<i class="fa fa-exclamation-triangle"></i> ' + response.error).show();
		}
	 );	
});	


$('input[name="pickup-setting"]').on('change', function() {
    if ($(this).val() == 1) {
        $('#pickup-option').show();
        $('#pickup-description-and-time').show();
    } else {
       $('form > div:not(#pickup-setting)').hide();
    }
});

