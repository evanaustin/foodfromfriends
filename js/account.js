App.Account = function() {
    function listener() {
        /*
        * Sign up
        */
        $('#sign-up').on('submit', function(e) {
            e.preventDefault();
            
            $form = $(this);
            
            if ($form.parsley().isValid()) {
                App.Util.hideMsg();

                App.Ajax.post('account/sign_up', $form.serialize(), 
                    function(response) {
                        window.location.replace(PUBLIC_ROOT + 'dashboard/food-listings/overview');
                    },
                    function(response) {
                        App.Util.msg(response.error, 'danger');
                        //   $form.siblings('div.alert').addClass('alert-danger').html('<i class="fa fa-exclamation-triangle"></i> ' + response.error).show();
                    }
                );
            }
        });

        /*
        * Log in
        */
        $('#log-in').on('submit', function(e) {
            e.preventDefault();

            $form = $(this);

            if ($form.parsley().isValid()) {
                App.Util.hideMsg();

                App.Ajax.post('account/log_in', $form.serialize(), 
                    function(response) {
                        window.location.replace(PUBLIC_ROOT + 'dashboard/food-listings/overview');
                    },
                    function(response) {
                        App.Util.msg(response.error, 'danger');
                        // $form.siblings('div.alert').addClass('alert-danger').html('<i class="fa fa-exclamation-triangle"></i> ' + response.error).show();
                    }
                );
            }
        });	
        
        /*
        * Log out
        */
        $('a#log-out').on('click', function() {
            App.Ajax.post('account/log_out', null, 
                function(response) {
                    window.location.replace(PUBLIC_ROOT);
                },
                function(response) {
                    toastr.error(response.error);
                }
            );
        });
    }
    
    return {
        listener: listener
    };
}();