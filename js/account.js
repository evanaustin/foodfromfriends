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

                App.Ajax.post('dashboard/account/sign_up', $form.serialize(), 
                    function(response) {
                        window.location.replace(PUBLIC_ROOT + 'map');
                    },
                    function(response) {
                        App.Util.msg(response.error, 'danger');
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

                App.Ajax.post('dashboard/account/log_in', $form.serialize(), 
                    function(response) {
                        window.location.replace(PUBLIC_ROOT + 'map');
                    },
                    function(response) {
                        App.Util.msg(response.error, 'danger');
                    }
                );
            }
        });	
        
        /*
        * Log out
        */
        $('a#log-out').on('click', function() {
            App.Ajax.post('dashboard/account/log_out', null, 
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