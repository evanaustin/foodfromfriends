$('#meetup-yes').on('click', function(){
        $('#meetup-info-1').show();
        $('.add-button').show();
        $('.meetup-info-1').prop('disabled', false);
});


$('input[name="is_offered"]').on('change', function() {
    if ($(this).val() == 1) {

        $('#meetup-info-1').show();
        $('.add-button').show();
        $('.meetup-info-1').prop('disabled', false);
        
        
    } else {
        $('form > div:not(#meetup-setting)').hide();
         $('.meetup-info-1').prop('disabled', true);
         $('.meetup-info-2').prop('disabled', true);
         $('.meetup-info-3').prop('disabled', true);
       count = 0;
    }
});

    var count = 0;

$('#add-button').on('click', function(){
    count++;
    if (count == 1) {
        $('#meetup-info-1').next('.meetup-location-and-time').show();
        $('.meetup-info-2').prop('disabled', false);

    }
    if (count == 2){
        $('#meetup-info-2').next('.meetup-location-and-time').show();
        $('.meetup-info-3').prop('disabled', false);
    }
    if (count == 3){
        $('.add-button').hide();
    }
});
   
$('#save-meetup').on('submit', function(e) {
	e.preventDefault();
    
    $form = $(this);
    console.log($form.serialize());
    App.Ajax.post('dashboard/exchange-settings/meetup', $form.serialize(), 
		function(response) {
            toastr.success('Your preference has been saved!')
		},
		function(response) {
            $form.siblings('div.alert').addClass('alert-danger').html('<i class="fa fa-exclamation-triangle"></i> ' + response.error).show();
		}
	 );	
});	