App.Dashboard.Overview = function() {
    function listener() {
        $('.subcategory-list').sortable({
            cursor: 'move',
            stop: function(event, ui) {
                $(this).find('.bubble').each(function(index) {
                    $(this).find('.position').val(index);
                });

                $('form').data('changed', true);
            }
        });

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

        $('a.remove-item').on('click', function(e) {
            e.preventDefault();
        
            $card = $(this).parents('.bubble');
        
            var data = {
                'item_id': $(this).data('id')
            };
        
            bootbox.confirm({
                closeButton: false,
                message: '<div class="text-center">Please confirm you want to remove this item</div>',
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
                        App.Ajax.post('dashboard/selling/items/remove-item', data, 
                            function(response) {
                                $('a.remove-item').tooltip('hide');
                                App.Util.animation($card, 'zoomOut', 'out', true, $card);
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
