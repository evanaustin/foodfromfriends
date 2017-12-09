App.Dashboard.PendingOrderView = function() {
    function listener() {
        var ordergrower_id = $('#ordergrower-id').val();

        $('button#fulfill-order').on('click', function(e) {
            e.preventDefault();
            
            var data = {
                'ordergrower_id' : ordergrower_id
            };
        
            bootbox.confirm({
                message: 'You want to mark this order fulfilled?',
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
        
                        App.Ajax.post('order/mark-fulfilled', data, 
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