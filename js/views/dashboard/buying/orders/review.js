App.Dashboard.OrderReview = function() {
    function listener() {
        $('#review-order').on('submit', function(e) {
            e.preventDefault();
            
            $form = $(this);

            bootbox.confirm({
                closeButton: false,
                title: 'Submit review',
                message: 'Please confirm you want to submit your review of this order. You cannot change a review after it has been submitted.',
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

                        App.Ajax.post('dashboard/buying/orders/review', data, 
                            function(response) {
                                App.Util.finishedLoading();
        
                                toastr.success('Reviewed! Now redirecting...');

                                setTimeout(function() {
                                    window.location = PUBLIC_ROOT + 'dashboard/buying/orders/orders';
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