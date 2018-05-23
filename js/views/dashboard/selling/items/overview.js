App.Dashboard.Overview = function() {
    function listener() {
        $('#edit-items').on('submit', function(e) {
            e.preventDefault();
            
            App.Ajax.post('dashboard/selling/items/bulk-edit', $(this).serialize(),
                function(response) {
                    App.Util.msg('Your item changes have been saved', 'success');
                },
                function(response) {
                    App.Util.msg(response.error, 'danger');
                }
            );
        });

        $('a.remove-listing').on('click', function(e) {
            e.preventDefault();
        
            $card = $(this).parents('.card');
        
            var data = {
                'listing_id': $(this).data('id')
            };
        
            bootbox.confirm({
                closeButton: false,
                message: '<div class="text-center">Please confirm you want to remove this listing</div>',
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
                        App.Ajax.post('dashboard/selling/items/remove-listing', data, 
                            function(response) {
                                $('a.remove-listing').tooltip('hide');
                                App.Util.animation($card, 'zoomOut', 'out', true, $card.parents('div.col-md-4'));
                            },
                            function(response) {
                                App.Util.msg(response.error, 'danger');
                            }
                        );
                    }
                }
            });
            
        });
    }

    return {
        listener: listener
    };
}();
