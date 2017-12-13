App.Dashboard.PendingOrderView = function() {
    function listener() {
        var ordergrower_id = $('#ordergrower-id').val();

        $('button#fulfill-order').on('click', function(e) {
            e.preventDefault();
            
            var data = {
                'ordergrower_id' : ordergrower_id
            };
        
            bootbox.confirm({
                title: 'Mark order as fulfilled',
                message: 'Please confirm that you want to mark this order as fulfilled. The buyer will be allowed three days to report an issue with their order before your payout is issued.',
                buttons: {
                    confirm: {
                        label: 'Mark as fulfilled',
                        className: 'btn-warning'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-muted'
                    }
                },
                callback: function(result) {
                    if (result === true) {
                        App.Util.loading('.save');
                        console.log(data);
        
                        App.Ajax.post('order/fulfill', data, 
                            function(response) {
                                App.Util.finishedLoading();
        
                                toastr.success('Fulfilled! Now redirecting...');

                                setTimeout(function() {
                                    window.location = PUBLIC_ROOT + 'dashboard/grower/orders/completed/view?id=' + ordergrower_id;
                                }, 1500);
                            },
                            function(response) {
                                App.Util.msg(response.error, 'danger');
                                App.Util.finishedLoading('.save');
                            }
                        );
                    }
                }
            });
        });
    };

    return {
        listener: listener
    };
}();