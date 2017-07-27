$('input[name="pickup-setting"]').on('change', function() {
    if ($(this).val() == 1) {
        $('#pickup-option').show();
        $('#pickup-description-and-time').show();
    } else {
       $('form > div:not(#pickup-setting)').hide();
    }
});

