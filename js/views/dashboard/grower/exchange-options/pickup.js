$('input[name="is-offered"]').on('change', function() {
    if ($(this).val() == 1) {
        $('#instructions, #time').fadeIn().find('textarea').prop({
            required: true,
            disabled: false
        });
    } else {
        $('#instructions, #time').find('textarea').prop({
            required: false,
            disabled: true
        });

        $('#instructions, #time').fadeOut();
    }
});

$('#save-pickup').on('submit', function(e) {
	e.preventDefault();
    App.Util.hideMsg();
    
    $form = $(this);
    data = $form.serialize();

    if ($form.parsley().isValid()) {
        App.Util.loading();

        App.Ajax.post('dashboard/grower/exchange-options/pickup', data, 
            function(response) {
                if (response.is_active) {
                    App.Util.msg('Your pickup preferences have been saved! Edit your <a href="' + PUBLIC_ROOT + 'dashboard/grower/exchange-options/delivery">delivery</a> or <a href="' + PUBLIC_ROOT + 'dashboard/grower/exchange-options/meetup">meetup</a> preferences or <a href="' + PUBLIC_ROOT + response.link + '">view</a> your profile', 'success');
                } else {
                    App.Util.msg('Your pickup preferences have been saved! Click <a href="' + PUBLIC_ROOT + response.link + '">here</a> to view it now or <a href="' + PUBLIC_ROOT + 'dashboard/grower">here</a> to finish activating your seller profile', 'success');
                }

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