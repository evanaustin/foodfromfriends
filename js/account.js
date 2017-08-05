App.Account = function() {
    function listener() {
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