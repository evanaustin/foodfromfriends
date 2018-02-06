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

                var data = $($form).serializeArray();
                
                var tz = jstz.determine();
                data.push({name: 'timezone', value: tz.name()});

                App.Ajax.post('user/sign-up', data, 
                    function(response) {
                        if (response.redirect == false) {
                            window.location.reload();
                        } else {
                            window.location.replace(response.redirect);
                        }
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

                var data = $($form).serializeArray();
                
                var tz = jstz.determine();
                data.push({name: 'timezone', value: tz.name()});

                App.Ajax.post('user/log-in', data, 
                    function(response) {
                        if (response.redirect == false) {
                            window.location.reload();
                        } else {
                            window.location.replace(response.redirect);
                        }
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
            App.Ajax.post('user/log-out', null, 
                function(response) {
                    window.location.replace(PUBLIC_ROOT);
                },
                function(response) {
                    toastr.error(response.error);
                }
            );
        });
        
        /*
        * Switch operations
        */
        $('a.switch-operation').on('click', function() {
            var data = {
                'grower_operation_id' : $(this).data('grower-operation-id')
            };

            App.Ajax.post('user/switch-operation', data, 
                function(response) {
                    window.location.replace(PUBLIC_ROOT + response.redirect);
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