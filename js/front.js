App.Front = function() {
    mapboxgl.accessToken = 'pk.eyJ1IjoiZm9vZGZyb21mcmllbmRzIiwiYSI6ImNqN2twb2gwdTJmdWkzMm5wNmw0ejJ2cHEifQ.vv9p76S-5nm9ku_guP3-Pg';

    if ($('#map').length) {
        this.Mapbox = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v10',
            zoom: 13
        });
    }

    function listener() {
        // make login direct back to current page
        $('form#log-in')
            .find('input[name="redirect"]')
            .val(false);

        $('.cart-toggle').on('click', function(e) {
            App.Util.slidebar(Slidebar, 'toggle', 'right', e);
        });

        // ! dirty - shouldn't be capturing universally and applying specifically -
        $(Slidebar.events).on('opened', function () {
            $('a.cart-toggle').addClass('active');
            $('#basket-form-container button[type="submit"]').removeClass('btn-primary').addClass('btn-dark');
        }).on('closed', function () {
            $('a.cart-toggle').removeClass('active');
            $('#basket-form-container button[type="submit"]').removeClass('btn-dark').addClass('btn-primary');
        });

        // Active mobile methods of closing the sidebars
        var isMobile = false;
        
        if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
            || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
            isMobile = true;
        }

        if (isMobile) {
            var cart = document.getElementById('cart');
            var cartswipe = new Hammer(cart);
    
            cartswipe.on('swiperight', function(e) {
                App.Util.slidebar(Slidebar, 'close', 'right');
            });
    
            $('div[canvas="container"]').on('click', function(e) {
                App.Util.slidebar(Slidebar, 'close', 'left', e);
                App.Util.slidebar(Slidebar, 'close', 'right', e);
            });
        }


        // Select callout bubble
        $('.callout.bubble:not(.disabled)').on('click', function() {
            $('.callout.bubble').removeClass('selected');
            $(this).addClass('selected');
        });


        // Direct exchange option form
        $('#set-exchange-option').on('submit', function(e) {
            e.preventDefault();
            App.Util.hideMsg();
            
            if ($('.exchange.bubble').hasClass('selected')) {
                $('input[name="exchange-option"]').val($('.selected.exchange.bubble').data('exchange-option'));
                $('#exchange-option-modal').modal('hide');
                $('form#' + $(this).data('action')).submit();
            } else {
                App.Util.msg('Select an exchange type before saving', 'danger');
            }
        });


        // Direct delivery address form
        $('#edit-delivery-address').on('submit', function(e) {
            e.preventDefault();
            App.Util.hideMsg();
            
            $form = $(this);
            data = $form.serialize();
        
            if ($form.parsley().isValid()) {
                App.Util.loading();

                App.Ajax.post('dashboard/account/edit-profile/save-delivery-address', data, 
                    function(response) {
                        App.Util.finishedLoading();
                        
                        buyer_lat = true;
                        buyer_lng = true;

                        $('#delivery-address-modal').modal('hide');

                        switch($form.data('action')) {
                            case 'add-item':
                                $('#add-item').submit();
                                break;
                            case 'update-exchange':
                                $('#update-item .exchange-btn[data-option="delivery"]').click();
                        }
                    },
                    function(response) {
                        App.Util.finishedLoading();
                        App.Util.msg(response.error, 'danger');
                    }
                );
            }
        });


        // Remove item
        $(document).on('click', '#cart a.remove-item', function(e) {
            $cart_item = $(this).parents('div.cart-item');
            var listing_id = $cart_item.data('listing-id');

            bootbox.confirm({
                closeButton: false,
                message: '<div class="text-center">Please confirm you want to remove this item from your basket</div>',
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
                                    $('#end-breakdown').find('.rate.service-fee').text(response.order.fff_fee);
                                    $('#end-breakdown').find('.rate.total').text(response.order.total);

                                    if (response.order.ex_fee != '$0.00') {
                                        $('#end-breakdown').find('.rate.exchange-fee').text(response.order.ex_fee);
                                        $('#end-breakdown').find('.rate.exchange-fee').parent('.line-amount').removeClass('hidden');
                                    } else {
                                        $('#end-breakdown').find('.rate.exchange-fee').text(0);
                                        $('#end-breakdown').find('.rate.exchange-fee').parent('.line-amount').addClass('hidden');
                                    }

                                    // no more items from this ordergrower
                                    if (response.ordergrower.items == 0) {
                                        App.Util.fadeAndRemove($('#ordergrower-' + response.ordergrower.id));
                                    }
                                }
                                
                                $('#checkout-total').text(response.order.total);
                            }, function(response) {
                                App.Util.msg(response.error, 'danger');
                            }
                        );
                    }
                }
            });
        });


        // Update item quantity
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
                    $('#end-breakdown').find('.rate.service-fee').text(response.order.fff_fee);
                    $('#end-breakdown').find('.rate.total').text(response.order.total);

                    if (response.order.ex_fee != '$0.00') {
                        $('#end-breakdown').find('.rate.exchange-fee').text(response.order.ex_fee);
                        $('#end-breakdown').find('.rate.exchange-fee').parent('.line-amount').removeClass('hidden');
                    } else {
                        $('#end-breakdown').find('.rate.exchange-fee').text(0);
                        $('#end-breakdown').find('.rate.exchange-fee').parent('.line-amount').addClass('hidden');
                    }

                    $('#checkout-total').text(response.order.total);
                }, function(response) {
                    console.log(response.error);
                }
            );
        });
        

        // Update OrderGrower Exchange type
        $(document).on('change', '#cart .ordergrower-exchange', function(e) {
            var data = {
                'grower-operation-id': $(this).parents('div.set').data('grower-operation-id'),
                'exchange-option': $(this).val()
            };

            App.Ajax.post('order/set-exchange-method', $.param(data), 
                function(response) {
                    $('#ordergrower-' + response.ordergrower.id).find('.rate.exchange-fee').text(response.ordergrower.ex_fee);

                    if ($('form[data-ordergrower="' + response.ordergrower.id + '"]').find('.exchange-btn').length > 1) {
                        $('.exchange-btn').removeClass('active');
                        $('.exchange-btn[data-option="' + response.ordergrower.exchange + '"]').addClass('active');
                    }

                    $('#end-breakdown').find('.rate.subtotal').text(response.order.subtotal);
                    $('#end-breakdown').find('.rate.service-fee').text(response.order.fff_fee);
                    $('#end-breakdown').find('.rate.total').text(response.order.total);

                    if (response.order.ex_fee != '$0.00') {
                        $('#end-breakdown').find('.rate.exchange-fee').text(response.order.ex_fee);
                        $('#end-breakdown').find('.rate.exchange-fee').parent('.line-amount').removeClass('hidden');
                    } else {
                        $('#end-breakdown').find('.rate.exchange-fee').text(0);
                        $('#end-breakdown').find('.rate.exchange-fee').parent('.line-amount').addClass('hidden');
                    }
                    
                    $('#checkout-total').text(response.order.total);
                }, function(response) {
                    App.Util.msg(response.error, 'danger');
                }
            );
        });


        // Enable keyboard shortcuts
        $(document).keydown(function(e) {
            if (!($('input, textarea, select').is(':focus'))) {
                switch(e.keyCode) {
                    case 66: // b
                        App.Util.slidebar(Slidebar, 'toggle', 'right', e);
                        break;
                    case 68: // d
                        window.location.replace(PUBLIC_ROOT + 'dashboard/buying/orders/overview');
                        break;
                    case 77: // m
                        window.location.replace(PUBLIC_ROOT + 'map');
                }
            }
        });
    };

    return {
        Mapbox: this.Mapbox,
        listener: listener
    };
}();