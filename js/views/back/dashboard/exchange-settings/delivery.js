$('input[name="delivery-setting"]').on('change', function() {
    if ($(this).val() == 1) {
        $('#distance-and-free-delivery-option').show();
    } else {
        $('form > div:not(#delivery-setting)').hide();
    }
});


$('#free-delivery').on('click', function() {
    $('#choose-delivery-fee-option').hide();
    $('#conditional-free-delivery-option').hide();
    $('#set-fee-option').hide();
    $('#per-mile-option').hide();
});

$('#no-free-delivery').on('click', function() {
    $('#choose-delivery-fee-option').show();
        $('#conditional-free-delivery-option').hide();
        $('#set-fee-option').hide();
        $('#per-mile-option').hide();

});

$('#conditional-free-delivery').on('click', function() {
    $('#conditional-free-delivery-option').show();
    $('#choose-delivery-fee-option').show();
    $('#free-distence').focus();
    $('#set-fee-option').hide();
    $('#per-mile-option').hide();
 
});

$('#per-mile-fee').on('click', function() {
    $('#set-fee-option').hide();
    $('#per-mile-option').show();
    $('#per-mile-price').focus();
 
});

$('#set-fee').on('click', function() {
    $('#per-mile-option').hide();
    $('#set-fee-option').show();
    $('#set-price').focus();
 
});

