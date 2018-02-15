$('a.remove-listing').on('click', function(e) {
    e.preventDefault();

    $card = $(this).parents('div.card');

    var data = {
        'listing_id': $(this).data('id')
    };

    bootbox.confirm({
        closeButton: false,
        message: 'You want to remove this listing?',
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
                App.Ajax.post('dashboard/grower/food-listings/remove-listing', data, 
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