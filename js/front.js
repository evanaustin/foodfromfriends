App.Front = function() {
    mapboxgl.accessToken = 'pk.eyJ1IjoiZm9vZGZyb21mcmllbmRzIiwiYSI6ImNqN2twb2gwdTJmdWkzMm5wNmw0ejJ2cHEifQ.vv9p76S-5nm9ku_guP3-Pg';

    this.Mapbox = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v10',
        zoom: 13
    });

    // create & initialize a new instance of Slidebars
    this.Slidebar = new slidebars();
    Slidebar.init();

    function listener() {
        // make login direct back to current page
        $('form#log-in')
            .find('input[name="redirect"]')
            .val(false);

        $('#cart-toggle').on('click', function(e) {
            App.Util.slidebar(Slidebar, 'toggle', 'right', e);
        });

        $(Slidebar.events).on('opened', function () {
            $('a#cart-toggle').addClass('active');
            $('#basket-form-container button[type="submit"]').removeClass('btn-primary').addClass('btn-dark');
        }).on('closed', function () {
            $('a#cart-toggle').removeClass('active');
            $('#basket-form-container button[type="submit"]').removeClass('btn-dark').addClass('btn-primary');
        });

        $(document).on('click', '#cart a.remove-item', function(e) {
            $cart_item = $(this).parents('div.cart-item');
            var listing_id = $cart_item.data('listing-id');

            bootbox.confirm({
                message: 'You want to remove this item from your basket?',
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
                        var data = {
                            'food-listing-id': listing_id
                        };
                        
                        App.Ajax.post('order/remove-from-cart', $.param(data), 
                            function(response) {
                                App.Util.fadeAndRemove($cart_item);

                                // check if there are still ordergrowers
                                if (response.ordergrower.count == 0) {
                                    App.Util.fadeAndRemove($('#ordergrowers'));
                                    $('#end-breakdown, hr').addClass('hidden');
                                    $('#empty-basket').removeClass('hidden');
                                } else {
                                    $('#end-breakdown').find('.rate.subtotal').text(response.order.subtotal);
                                    $('#end-breakdown').find('.rate.exchange-fee').text(response.order.ex_fee);
                                    $('#end-breakdown').find('.rate.service-fee').text(response.order.fff_fee);
                                    $('#end-breakdown').find('.rate.total').text(response.order.total);

                                    // no more items from this ordergrower
                                    if (response.ordergrower.items == 0) {
                                        console.log('block');
                                        App.Util.fadeAndRemove($('#ordergrower-' + response.ordergrower.id));
                                    }
                                }
                                console.log(response);
                            }, function(response) {
                                App.Util.msg(response.error, 'danger');
                            }
                        );
                    }
                }
            });
        });

        $(document).on('change', '#cart .cart-item select', function(e) {
            $cart_item = $(this).parents('div.cart-item');

            var data = {
                'food-listing-id': $cart_item.data('listing-id'),
                'quantity': $(this).val()
            };

            App.Ajax.post('order/modify-quantity', $.param(data), 
                function(response) {
                    $cart_item.find('.item-price').text(response.item.subtotal);

                    $('#end-breakdown').find('.rate.subtotal').text(response.order.subtotal);
                    $('#end-breakdown').find('.rate.exchange-fee').text(response.order.ex_fee);
                    $('#end-breakdown').find('.rate.service-fee').text(response.order.fff_fee);
                    $('#end-breakdown').find('.rate.total').text(response.order.total);
                }, function(response) {
                    console.log(response.error);
                }
            );
        });

        $(document).keydown(function(e) {
            if (!($('input, textarea, select').is(':focus'))) {
                switch(e.keyCode) {
                    case 66: // b
                        App.Util.slidebar(Slidebar, 'toggle', 'right', e);
                        break;
                    case 68: // d
                        window.location.replace(PUBLIC_ROOT + 'dashboard/grower');
                        break;
                    case 77: // m
                        window.location.replace(PUBLIC_ROOT + 'map');
                }
            }
        });
    };

    return {
        Mapbox: this.Mapbox,
        Slidebar: this.Slidebar,
        listener: listener
    };
}();