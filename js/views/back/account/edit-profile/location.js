$('#edit-location').on('submit', function(e) {
	e.preventDefault();
    App.Util.hideMsg();
    
    $form = $(this);
    data = $form.serialize();

    if ($form.parsley().isValid()) {
        App.Ajax.post('account/edit-profile/location', data, 
            function(response) {
                App.Util.msg('Your location has been saved!', 'success');
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