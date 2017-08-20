$('input[name="is-offered"]').on('change', function() {
    if ($(this).val() == 1) {
        $('#instructions, #availability').fadeIn().find('textarea').prop({
            required: true,
            disabled: false
        });
    } else {
        $('#instructions, #availability').find('textarea').prop({
            required: false,
            disabled: true
        });

        $('#instructions, #availability').fadeOut();
    }
});

$('#save-pickup').on('submit', function(e) {
	e.preventDefault();
    App.Util.hideMsg();
    
    $form = $(this);
    data = $form.serialize();

    if ($form.parsley().isValid()) {
        App.Util.loading();

        App.Ajax.post('dashboard/exchange-settings/pickup', data, 
            function(response) {
                App.Util.msg('Your pickup preferences have been saved!', 'success');
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