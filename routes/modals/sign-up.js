$('#sign-up').on('submit', function(e) {
    e.preventDefault();
    $form = $(this);
    
    App.Ajax.post('account/sign_up', $form.serialize(), 
		function(response) {
            window.location.href(PUBLIC_ROOT + '/dashboard');
		},
		function(response) {
            $form.siblings('div.alert').addClass('alert-danger').html('<i class="fa fa-exclamation-triangle"></i> ' + response.error).show();
		}
    ); 
});	