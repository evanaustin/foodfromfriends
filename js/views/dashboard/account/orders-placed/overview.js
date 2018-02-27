App.Dashboard.OrdersPlaced = function() {
    function listener() {
        $('a.cancel-order').on('click', function(e) {
            e.preventDefault();
        
            var data = {
                'ordergrower_id': $(this).data('ordergrower-id')
            };

            bootbox.confirm({
                closeButton: false,
                title: 'Cancel order',
                message: '<div class="text-center">Please confirm you want to cancel this order</div>',
                buttons: {
                    confirm: {
                        label: 'Submit cancellation',
                        className: 'btn-warning'
                    },
                    cancel: {
                        label: 'Go back',
                        className: 'btn-muted'
                    }
                },
                callback: function(result) {
                    if (result === true) {
                        App.Util.loading();

                        App.Ajax.post('dashboard/account/orders-placed/buyer-cancel', data, 
                            function(response) {
                                App.Util.finishedLoading();
        
                                toastr.success('Cancelled. Now reloading...');

                                setTimeout(function() {
                                    window.location.reload(true);
                                }, 1500);
                            },
                            function(response) {
                                App.Util.msg(response.error, 'danger');
                                App.Util.finishedLoading();
                            }
                        );
                        console.log(data);

                        App.Util.finishedLoading();
                    }
                }
            });
        });
    };

    return {
        listener: listener
    };
}();