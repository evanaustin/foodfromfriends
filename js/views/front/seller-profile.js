App.Front.SellerProfile = function() {
    function listener() {
        if ($('#map').length) {
            Mapbox.setCenter([lng, lat]);
        }

        $('#message').on('click', function(e) {
            // trigger sign up before directing to messages
            if (user == 0) {
                e.preventDefault();
                
                var $sign_up_modal = $('#sign-up-modal');
                var $sign_up_form = $sign_up_modal.find('#sign-up');

                $sign_up_modal.modal();

                App.Util.msg('Hey! Sign up first &mdash; then you can send a message!', 'info', $sign_up_form);

                $sign_up_form
                    .find('input[name="redirect"]')
                    .val(false);

                return;
            }
        });

        $('form.quick-add').on('submit', function(e) {
            e.preventDefault();
            App.Util.hideMsg();
            
            $form = $(this);
            var data = $form.serializeArray();
            
            // Trigger sign up first
            if ($form.find('input[name="user-id"]').val() == 0) {
                var $sign_up_modal = $('#sign-up-modal');
                var $sign_up_form = $sign_up_modal.find('#sign-up');

                $sign_up_modal.modal();

                App.Util.msg('Hey! Sign up first &mdash; then we can add to your basket!', 'info', $sign_up_form);

                var getvars = 'quantity=' + $('select[name="quantity"]').val() + '&exchange=' + $(this).data('option');

                $sign_up_form
                    .find('input[name="redirect"]')
                    .val(location.pathname + '?' + getvars);

                return;
            }

            // Make sure exchange option is selected
            if ($form.find('input[name="exchange-option"]').val() == '') {
                $('#set-exchange-option').attr('data-action', $form.attr('id'));
                $('#exchange-option-modal').modal('show');
                return;
            }

            // Make sure delivery address is set
            if (typeof exchange_option !== 'undefined' && exchange_option == 'delivery' && (buyer_lat == false || buyer_lng == false)) {
                $('#edit-delivery-address').attr('data-action', 'add-item');
                $('#delivery-address-modal').modal('show');
                return;
            }

            // Add to cart or modify quantity
            if ($form.parsley().isValid()) {
                App.Util.loading();
                
                if ($form.find('input[name="order-item-id"]').val() == 0) {
                    App.Ajax.post('order/add-to-cart', $.param(data), 
                        function(response) {
                            $form.find('input[name="order-item-id"]').val(response.item.id);
                            $form.find('input[name="suborder-id"]').val(response.ordergrower.id);

                            App.Util.slidebar(Slidebar, 'open', 'right', e);
    
                            $(Slidebar.events).on('opened', function () {
                                // Check if cart is empty
                                if (!$('#ordergrowers').length) {
                                    $('#empty-basket').addClass('hidden');
                                    $('hr').removeClass('hidden');
    
                                    $ordergrowers = $('<div id="ordergrowers">').prependTo('#cart');
                                }
    
                                // Add ordergrower if not already in cart
                                if (!$('#ordergrower-' + response.ordergrower.id).length) {
                                    $set = $('<div id="ordergrower-' + response.ordergrower.id + '" class="set" data-grower-operation="' + response.ordergrower.grower_id + '">').appendTo('#ordergrowers');
                                    
                                    $set.append('<h6>' + response.ordergrower.name + '</h6>');
    
                                    $cart_items = $('<div class="cart-items">').appendTo($set);
                                    $breakdown  = $('<div class="breakdown">').appendTo($set);
                                } else {
                                    $cart_items = $('#ordergrower-' + response.ordergrower.id).find('div.cart-items');
                                    $breakdown  = $('#ordergrower-' + response.ordergrower.id).find('div.breakdown');
                                }
                                
                                $cart_item = $(
                                    '<div class="cart-item animated bounceIn" data-listing-id="' + response.listing.id + '">' +
                                        '<div class="item-image">' +
                                            '<img src="' + (response.listing.filename ? 'https://s3.amazonaws.com/foodfromfriends/' + ENV + '/items/' + response.listing.filename + '.' + response.listing.ext : PUBLIC_ROOT + 'media/placeholders/default-thumbnail.jpg') + '" class="img-fluid"/>' +
                                        '</div>' +
                                        
                                        '<div class="item-content">' +
                                            '<div class="item-title">' +
                                                '<a href="' + PUBLIC_ROOT + response.listing.link + '">' +
                                                    response.listing.name +
                                                '</a>' +
                                            '</div>' +
                        
                                            '<div class="item-details">' +
                                                '<div class="item-price">' +
                                                response.item.subtotal +
                                                '</div>' +
                                                '<a class="remove-item">' +
                                                    '<i class="fa fa-times"></i>' +
                                                '</a>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>'
                                ).appendTo($cart_items);
                                
                                $('<select class="custom-select">').prependTo($cart_item.find('div.item-details'));
                                
                                for (var i = 1; i <= response.listing.quantity; i++) {
                                    // $option = $('<option>').attr('value', i).text(i);
                                    $cart_item.find('select').append($('<option>').attr('value', i).attr('selected', (i == response.item.quantity) ).text(i));
                                };
    
                                if ($breakdown.children().length) {
                                    $breakdown.find('.label.exchange').text(response.ordergrower.exchange);
                                    $breakdown.find('.rate.exchange-fee').text(response.ordergrower.ex_fee);
                                } else {
                                    $breakdown.append(
                                        '<div class="line-amount">' +
                                            '<div class="label exchange">' +
                                                response.ordergrower.exchange +
                                            '</div>' +
                                            
                                            '<div class="rate exchange-fee">' +
                                                ((response.ordergrower.exchange == 'Delivery') ? response.ordergrower.ex_fee : 'Free') +
                                            '</div>' +
                                        '</div>'
                                    );
                                }
    
                                $('#end-breakdown').removeClass('hidden');
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
                                
                                $(Slidebar.events).unbind('opened');
                            });
    
                            $('#add-item button[type="submit"]').attr('disabled', 'disabled');
                        }, function(response) {
                            App.Util.msg(response.error, 'danger');
                        }
                    );
                } else {
                    var item_id = $form.find('input[name="food-listing-id"]').val();

                    App.Ajax.post('order/modify-quantity', $.param(data), 
                        function(response) {
                            App.Util.slidebar(Slidebar, 'open', 'right', e);

                            $(Slidebar.events).on('opened', function () {
                                // Update quantity for cart item
                                $(document).find('.cart-item[data-listing-id="' + item_id + '"]').find('select option').attr('selected', false);
                                $(document).find('.cart-item[data-listing-id="' + item_id + '"]').find('select option[value=' + response.item.quantity + ']').attr('selected', 'selected');

                                // Update item subtotal
                                $(document).find('.cart-item[data-listing-id="' + item_id + '"]').find('.item-price').text(response.item.subtotal);

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

                                $(Slidebar.events).unbind('opened');
                            });
                        }, function(response) {
                            App.Util.msg(response.error, 'danger');
                        }
                    );
                }
            }
        });
    };

    return {
        listener: listener
    };
}();
