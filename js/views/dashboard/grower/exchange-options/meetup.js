$('input[name="is-offered"]').on('change', function() {
    if ($(this).val() == 1) {
        $('#meetup-details').fadeIn().find('input:not([name="address-line-2"]), textarea').prop({
            required: true,
            disabled: false
        });

        $('input[name="address-line-2"]').prop('disabled', false);
    } else {
        $('#meetup-details').find('input:not([name="address-line-2"]), textarea').prop({
            required: false,
            disabled: true
        });
        
        $('input[name="address-line-2"]').prop('disabled', true);
        $('#meetup-details').fadeOut();
    }
});

$('#save-meetup').on('submit', function(e) {
	e.preventDefault();
    App.Util.hideMsg();
    
    $form = $(this);
    data = $form.serialize();

    if ($form.parsley().isValid()) {
        App.Ajax.post('dashboard/grower/exchange-options/meetup', data, 
            function(response) {
                App.Util.msg('Your meetup preferences have been saved!', 'success');
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