
App.Dashboard.OrderIssue = function() {
    function listener() {
        $('#report-order').on('submit', function(e) {
            e.preventDefault();
            
            $form = $(this);

            bootbox.confirm({
                closeButton: false,
                title: 'Submit report',
                message: '<div class="text-center">Please confirm you want to report an issue with this order</div>',
                buttons: {
                    confirm: {
                        label: 'Submit',
                        className: 'btn-warning'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-muted'
                    }
                },
                callback: function(result) {
                    if (result === true) {
                        App.Util.loading();

                        var data = $form.serialize();

                        App.Ajax.post('dashboard/account/orders-placed/report', data, 
                            function(response) {
                                App.Util.finishedLoading();
        
                                toastr.success('Reported. Now redirecting...');

                                setTimeout(function() {
                                    window.location = PUBLIC_ROOT + 'dashboard/account/orders-placed/overview';
                                }, 1500);
                            },
                            function(response) {
                                App.Util.msg(response.error, 'danger');
                                App.Util.finishedLoading();
                            }
                        );

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