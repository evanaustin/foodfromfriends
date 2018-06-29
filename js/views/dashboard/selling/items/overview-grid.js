$('a.remove-item').on('click', function(e) {
    e.preventDefault();

    $card = $(this).parents('.card');

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