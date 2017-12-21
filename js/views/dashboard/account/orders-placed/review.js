/* var logID = 'log',
log = $('<div id="'+logID+'"></div>');
$('body').append(log);
$('[type*="radio"]').change(function () {
  var me = $(this);
  log.html(me.attr('value'));
}); */

App.Dashboard.OrderReview = function() {
    function listener() {
        // var ordergrower_id = $('#ordergrower-id').val();

        $('#review-order').on('submit', function(e) {
            e.preventDefault();
            
            $form = $(this);

            /* var data = {
                'ordergrower_id' : ordergrower_id
            }; */
        
            bootbox.confirm({
                title: 'Submit review',
                message: 'Please confirm that you want to submit your review of this order. You cannot change a review after it has been submitted.',
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

                        App.Ajax.post('order/review', data, 
                            function(response) {
                                App.Util.finishedLoading();
        
                                toastr.success('Reviewed! Now redirecting...');

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