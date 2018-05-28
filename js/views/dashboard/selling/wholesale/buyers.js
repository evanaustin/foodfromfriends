App.Dashboard.WholesaleBuyers = function() {
    function listener() {
        $('.approve-request').on('click', function() {
            $btn = $(this);
            
            var data = {
                'relationship_id': $(this).parents('tr').data('relationship-id')
            };

            App.Ajax.post('dashboard/selling/wholesale/approve-request', $.param(data), 
                function(response) {
                    App.Util.msg('Wholesale request approved', 'success');

                    $btn.parents('tr').find('.status').html('<span class="green">Approved</span>');
                    $btn.parents('tr').find('.deny-request.hidden').removeClass('hidden');
                    $btn.addClass('hidden');
                }, function(response) {
                    App.Util.msg(response.error, 'danger');
                }
            );
        });
        
        $('.deny-request').on('click', function() {
            $btn = $(this);

            var data = {
                'relationship_id': $(this).parents('tr').data('relationship-id')
            };

            App.Ajax.post('dashboard/selling/wholesale/deny-request', $.param(data), 
                function(response) {
                    App.Util.msg('Wholesale request denied', 'success');

                    $btn.parents('tr').find('.status').html('<span class="red">Denied</span>');
                    $btn.parents('tr').find('.approve-request.hidden').removeClass('hidden');
                    $btn.addClass('hidden');
                }, function(response) {
                    App.Util.msg(response.error, 'danger');
                }
            );
        });
    };

    return {
        listener: listener
    };
}();