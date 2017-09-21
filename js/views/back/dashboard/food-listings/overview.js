$('a.remove-listing').on('click', function(e) {
    e.preventDefault();

    $card = $(this).parents('div.card');

    var data = {
        'listing_id': $(this).data('id')
    };

    bootbox.confirm({
        message: 'You want to remove this listing?',
        buttons: {
            confirm: {
                label: 'Oh yeah',
                className: 'btn-primary'
            },
            cancel: {
                label: 'Nope',
                className: 'btn-secondary'
            }
        },
        callback: function(result) {
            if (result === true) {
                App.Ajax.post('dashboard/food-listings/remove-listing', data, 
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

/*
* Show the image cards
*/
$('div.card').each(function(i, obj) {
    $(this).imagesLoaded(function() {
        setTimeout(function() {
            $(obj).removeClass('hidden');
        }, 200 * i);
    });
});