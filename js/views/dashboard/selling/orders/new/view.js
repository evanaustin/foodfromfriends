App.Dashboard.NewOrderView = function() {
    function listener() {
        var ordergrower_id = $('#ordergrower-id').val();

        $('button#confirm-order').on('click', function(e) {
            e.preventDefault();
            
            var data = {
                'ordergrower_id' : ordergrower_id
            };
        
            bootbox.confirm({
                closeButton: false,
                title: 'Confirm order',
                message: 'Please confirm that you can commit to fulfilling this order. Cancelling an order after confirmation will negatively impact your grower rating!',
                buttons: {
                    confirm: {
                        label: 'Confirm',
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
        
                        App.Ajax.post('dashboard/selling/orders/confirm', data, 
                            function(response) {
                                App.Util.finishedLoading();
        
                                toastr.success('Confirmed! Now redirecting...');

                                setTimeout(function() {
                                    window.location = PUBLIC_ROOT + 'dashboard/selling/orders/pending/view?id=' + ordergrower_id;
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
        
        $('button#reject-order').on('click', function(e) {
            e.preventDefault();
            
            var data = {
                'ordergrower_id' : $('#ordergrower-id').val()
            };
        
            bootbox.confirm({
                closeButton: false,
                title: 'Reject order',
                message: 'Please confirm that you want to reject this order. Rejecting an order will not negatively impact your grower rating. This action is irreversible!',
                buttons: {
                    confirm: {
                        label: 'Reject',
                        className: 'btn-warning'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-muted'
                    }
                },
                callback: function(result) {
                    if (result === true) {
                        App.Util.loading('.reject');
        
                        App.Ajax.post('dashboard/selling/orders/reject', data, 
                            function(response) {
                                App.Util.finishedLoading();
        
                                toastr.warning('Rejected. Now redirecting back to new orders...');

                                setTimeout(function() {
                                    window.location = PUBLIC_ROOT + 'dashboard/selling/orders/new';
                                }, 1500);
                            },
                            function(response) {
                                App.Util.msg(response.error, 'danger');
                                App.Util.finishedLoading('.reject');
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