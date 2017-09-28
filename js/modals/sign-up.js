$('#sign-up').on('submit', function(e) {
      e.preventDefault();
    
      $form = $(this);
    
      if ($(this).parsley().isValid()) {
            App.Ajax.post('account/sign_up', $form.serialize(), 
                  function(response) {
                        window.location.replace(PUBLIC_ROOT + 'grower');
                  },
                  function(response) {
                        $form.siblings('div.alert').addClass('alert-danger').html('<i class="fa fa-exclamation-triangle"></i> ' + response.error).show();
                  }
            );
      }
});	