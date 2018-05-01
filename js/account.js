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
         * Send reset password link
         */
       $('#reset-password-link').on('submit', function(e) {
            e.preventDefault();

            $form = $(this);

            if ($form.parsley().isValid()) {
                App.Util.hideMsg();

                var data = $form.serialize();
                
                App.Ajax.post('user/reset-password-link', data, 
                    function(response) {
                        App.Util.msg('Check your inbox for a link to reset your password', 'success');
                    },
                    function(response) {
                        App.Util.msg(response.error, 'success');
                    }
                );
            }
        });
        
        /*
         * Reset password
         */
       $('#reset-password').on('submit', function(e) {
            e.preventDefault();

            $form = $(this);

            if ($form.parsley().isValid()) {
                App.Util.hideMsg();

                var data = $form.serialize();
                
                App.Ajax.post('user/reset-password', data, 
                    function(response) {
                        App.Util.msg('Your password was successfully reset. Click <a href="' + PUBLIC_ROOT + 'log-in">here</a> to go log in', 'success');
                    },
                    function(response) {
                        App.Util.msg(response.error, 'danger');
                    }
                );
            }
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
        
        /*
        * Switch buyer accounts
        */
        $('a.switch-buyer-account').on('click', function(e) {
            e.preventDefault();

            var data = {
                'buyer_account_id' : $(this).data('buyer-account-id')
            };

            App.Ajax.post('user/switch-buyer-account', data, 
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