$('#pickup-yes').on('click', function(){
    $('#pickup-description-and-time').show();
    $('.form-group').show();
});


$('#save-pickup').on('submit', function(e) {
	e.preventDefault();
    
    $form = $(this);
    console.log($form.serialize());
    App.Ajax.post('dashboard/exchange-settings/pickup', $form.serialize(), 
		function(response) {
            toastr.success('Your preference has been saved!')
		},
		function(response) {
            $form.siblings('div.alert').addClass('alert-danger').html('<i class="fa fa-exclamation-triangle"></i> ' + response.error).show();
		}
	 );	
});	


$('input[name="is-offered"]').on('change', function() {
    if ($(this).val() == 1) {
        $('#pickup-description-and-time').show();
        $('.form-group').show();
    } else {
       $('form > div:not(#pickup-setting)').hide();
    }
});

