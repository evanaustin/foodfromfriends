$('#edit-profile').on('submit', function(e) {
	e.preventDefault();
    
    $form = $(this);
    console.log($form.serialize());
    App.Ajax.post('profile/edit-profile', $form.serialize(), 
		function(response) {
            toastr.success('Your profile has been updated!')
		},
		function(response) {
            $form.siblings('div.alert').addClass('alert-danger').html('<i class="fa fa-exclamation-triangle"></i> ' + response.error).show();
		}
	 );	
});	