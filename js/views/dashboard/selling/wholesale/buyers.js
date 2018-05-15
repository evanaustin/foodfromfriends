App.Dashboard.WholesaleBuyers = function() {
    function listener() {
        $('.approve-buyer').on('click', function() {
            $btn = $(this);
            
            var data = {
                'membership_id': $(this).parents('tr').data('relationship-id')
            };

            App.Ajax.post('dashboard/selling/wholesale/approve-buyer', $.param(data), 
                function(response) {
                    App.Util.msg('Wholesale request approved', 'success');

                    $btn.parents('tr').find('.status').html('<span class="green">Approved</span>');
                    $btn.parents('tr').find('.unapprove-buyer.hidden').removeClass('hidden');
                    $btn.addClass('hidden');
                }, function(response) {
                    App.Util.msg(response.error, 'danger');
                }
            );
        });
        
        $('.unapprove-buyer').on('click', function() {
            $btn = $(this);

            var data = {
                'membership_id': $(this).parents('tr').data('relationship-id')
            };

            App.Ajax.post('dashboard/selling/wholesale/unapprove-buyer', $.param(data), 
                function(response) {
                    App.Util.msg('Wholesale request denied', 'warning');

                    $btn.parents('tr').find('.status').html('<span class="red">Denied</span>');
                    $btn.parents('tr').find('.approve-buyer.hidden').removeClass('hidden');
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