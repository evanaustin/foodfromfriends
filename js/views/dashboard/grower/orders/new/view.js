App.Dashboard.NewOrderView = function() {
    function listener() {
        var ordergrower_id = $('#ordergrower-id').val();

        $('button#confirm-order').on('click', function(e) {
            e.preventDefault();
            
            var data = {
                'ordergrower_id' : ordergrower_id
            };
        
            bootbox.confirm({
                message: 'You want to confirm this order?',
                buttons: {
                    confirm: {
                        label: 'Oh yeah',
                        className: 'btn-warning'
                    },
                    cancel: {
                        label: 'Nope',
                        className: 'btn-muted'
                    }
                },
                callback: function(result) {
                    if (result === true) {
                        App.Util.loading('.save');
                        console.log(data);
        
                        App.Ajax.post('order/confirm', data, 
                            function(response) {
                                App.Util.finishedLoading();
        
                                toastr.success('Confirmed! Now redirecting...');

                                setTimeout(function() {
                                    window.location = PUBLIC_ROOT + 'dashboard/grower/orders/pending/view?id=' + ordergrower_id;
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
                message: 'You want to reject this order?',
                buttons: {
                    confirm: {
                        label: 'Oh yeah',
                        className: 'btn-warning'
                    },
                    cancel: {
                        label: 'Nope',
                        className: 'btn-muted'
                    }
                },
                callback: function(result) {
                    if (result === true) {
                        App.Util.loading('.reject');
        
                        App.Ajax.post('order/reject', data, 
                            function(response) {
                                App.Util.finishedLoading();
        
                                toastr.warning('Rejected. Now redirecting back to new orders...');

                                setTimeout(function() {
                                    window.location = PUBLIC_ROOT + 'dashboard/grower/orders/new';
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