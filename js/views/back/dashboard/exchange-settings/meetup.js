$('input[name="meetup-setting"]').on('change', function() {
    if ($(this).val() == 1) {

        $('#meetup-info-1').show();
        $('.add-button').show();
        
        
    } else {
       $('form > div:not(#meetup-setting)').hide();
       count = 0;
    }
});

    var count = 0;

$('#add-button').on('click', function(){
    count++;
    if (count == 1) {
        $('#meetup-info-1').next('.meetup-location-and-time').show();
    }
    if (count == 2){
        $('#meetup-info-2').next('.meetup-location-and-time').show();
    }
    if (count == 3){
        $('.add-button').hide();
    }
});
   