$('input[name="is-offered"]').on('change', function() {
    if ($(this).val() == 1) {
        $('#distance, #delivery-type').fadeIn().find('input').prop({
            required: true,
            disabled: false
        });

        // show any settings that are already set
        $('div.setting').each(function(i) {
            var $setting = $(this);

            $setting.find('input').each(function(j) {
                // check if a radio box is checked or for an existing value
                if ($(this).closest('div.radio-box').length > 0) {
                    if ($(this).closest('div.radio-box').find('input').is(':checked')) {
                        $setting.fadeIn();
                        
                        $(this).prop({
                            required: true,
                            disabled: false
                        });
                    }
                } else if ($(this).val() !== '' && $(this).val() !== null) {
                    $setting.fadeIn();
                    
                    $(this).prop({
                        required: true,
                        disabled: false
                    });
                }
            });
        });

        // show fee and conditional setting fields even if blank if delivery type is set
        if ($('input[name="delivery-type"]').is(':checked') && $('#fee').is(':hidden')) {
            var delivery_type = $('input[name="delivery-type"]:checked').val();

            if (delivery_type != 'free') {
                $('#fee').fadeIn().find('input').prop({
                    required: true,
                    disabled: false
                });
                
                if (delivery_type == 'conditional') {
                    $('#conditional-free-delivery').fadeIn().find('input').prop({
                        required: true,
                        disabled: false
                    });
                    
                    $('#feeHelp').fadeIn();
                }
            }
        }
    } else {
        $('div.setting').fadeOut().find('input').prop({
            required: false,
            disabled: true
        });
    }
});

$('input[name="delivery-type"]').on('change', function() {
    switch($(this).val()) {
        case 'charge':
            $('#fee').fadeIn().find('input').prop({
                required: true,
                disabled: false
            });

            $('#conditional-free-delivery').find('input').prop({
                required: false,
                disabled: true
            });

            $('#conditional-free-delivery').fadeOut()

            $('#feeHelp').fadeOut();
            
            break;

        case 'free':
            $('div.fee.setting').each(function() {
                $(this).find('input').prop({
                    required: false,
                    disabled: true
                });

                $(this).fadeOut();
            });
            
            break;

        case 'conditional':
            $('#conditional-free-delivery, #fee').fadeIn().find('input').prop({
                required: true,
                disabled: false
            });

            $('#feeHelp').fadeIn();

            break;
    }
});

$('#save-delivery').on('submit', function(e) {
    e.preventDefault();
    App.Util.hideMsg();
    
    $form = $(this);
    data = $form.serialize();

    if ($form.parsley().isValid()) {
        App.Util.loading();

        App.Ajax.post('dashboard/grower/exchange-options/delivery', data, 
            function(response) {
                App.Util.msg('Your delivery preferences have been saved!', 'success');
                App.Util.animation($('button[type="submit"]'), 'bounce');
                App.Util.finishedLoading();
            },
            function(response) {
                App.Util.msg(response.error, 'danger');
                App.Util.finishedLoading();
            }
        );
    }
});