$('#delivery-yes').on('click', function(){
        $('#distance-and-free-delivery-option').show(); 
        $('input[name="free-delivery"]').prop('disabled',false);
        $('input[name="distance"]').prop('disabled',false);
});
          
        
$('input[name="is-offered"]').on('change', function() {
    if ($(this).val() == 1) {
        $('#distance-and-free-delivery-option').show();
        $('input[name="free-delivery"]').prop('disabled',false);
        $('input[name="distance"]').prop('disabled',false);
    } else {
        $('form > div:not(#delivery-setting)').hide();
        $('input[name="distance"]').prop('disabled',true);
        $('input[name="free-delivery"]').prop('disabled',true);
        $('input[name="free-miles"]').prop('disabled',true);
        $('input[name="pricing-rate"]').prop('disabled',true);
        $('input[name="fee"]').prop('disabled',true);

    }
});


$('#free-delivery').on('click', function() {
    $('#choose-delivery-fee-option').hide();
    $('#conditional-free-delivery-option').hide();
    $('#set-fee-option').hide();
    $('#per-mile-option').hide();
    $('input[name="pricing-rate"]').prop('disabled', true);
    $('input[name="free-miles"]').prop('disabled', true);
    $('#fee').prop('disabled', true);
    $('#fee').hide();
    
    
});

$('#no-free-delivery').on('click', function() {
    $('#choose-delivery-fee-option').show();
    $('input[name="pricing-rate"]').prop('disabled', false);
    $('#conditional-free-delivery-option').hide();
    $('#set-fee-option').hide();
    $('#per-mile-option').hide();
    $('input[name="free-miles"]').prop('disabled', true);
    $('#fee').prop('disabled', true);
    

});

$('#conditional-free-delivery').on('click', function() {
    $('#conditional-free-delivery-option').show();
    $('#choose-delivery-fee-option').show();
    $('input[name="free-miles"]').prop('disabled', false);
    $('input[name="pricing-rate"]').prop('disabled', false);
    $('#free-distence').focus();
    $('#set-fee-option').hide();
    $('#per-mile-option').hide();
    $('#fee').prop('disabled', true);
   
});

$('#per-mile-fee').on('click', function() {
    $('#set-fee-option').hide();
    $('#per-mile-option').show();
    $('#fee').show();
    $('input[name="fee"]').prop('disabled', false);
 
});

$('#set-fee').on('click', function() {
    $('#per-mile-option').hide();
    $('#set-fee-option').show();
    $('#fee').show();
    $('input[name="fee"]').prop('disabled', false);
 
});

$('#save-delivery').on('submit', function(e) {
	e.preventDefault();
    
    $form = $(this);
    console.log($form.serialize());
    App.Ajax.post('dashboard/exchange-settings/delivery', $form.serialize(), 
		function(response) {
            toastr.success('Your preference has been saved!')
		},
		function(response) {
            $form.siblings('div.alert').addClass('alert-danger').html('<i class="fa fa-exclamation-triangle"></i> ' + response.error).show();
		}
	 );	
});	