App.Front.Item = function() {
    function listener() {
        if ($('#map').length) {
            // Mapbox.setZoom(13);
            Mapbox.setCenter([seller_lng, seller_lat]);
        }
        
        var ex = '.exchange.form-group' + ' ';

        // Toggle active exchange option
        /* $('#update-cart .exchange-btn').on('click', function() {
            // Trigger sign up first
            if (typeof user === 'undefined' || user.id === null) {
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

            $('#update-cart')
                .find('.btn-group')
                .removeClass('has-danger')
                .find('button')
                .removeClass('active');

            $(ex + 'div.form-control-feedback').addClass('hidden').removeClass('danger');
            
            $(this).addClass('active');
        }); */


        // Change package option
        $('#update-cart select[name="item-id"]').on('change', function() {
            var id  = $(this).val();
            $form   = $('#update-cart');
            $form_quantity  = $form.find('select[name="quantity"]');
            $form_submit    = $form.find('input[type="submit"]');   

            $('#description').find('div').text(items[id].description);

            if (items[id].filename) {
                $('.photo.box').find('img').attr('src', 'https://s3.amazonaws.com/foodfromfriends/' + ENV + '/item-images/' + items[id].filename + '.' + items[id].ext);
            }

            if (items[id].quantity > 0) {
                $form_quantity.empty();
                $form_quantity.parents('.form-group').removeClass('hidden');

                for (var i = 1; i <= items[id].quantity; i++) {
                    $form_quantity.append($('<option>', {
                        value: i, 
                        text: i
                    }));
                }

                $form_submit
                    .removeClass('btn-danger')
                    .addClass('btn-cta')
                    .attr('disabled', false);

                $form_submit.val(((items[id].in_cart) ? 'Update item in basket' : 'Add to basket'));
            } else {
                $form_quantity.empty();
                $form_quantity.parents('.form-group').addClass('hidden');

                $form_submit
                    .removeClass('btn-cta')
                    .addClass('btn-danger')
                    .val('Out of stock')
                    .attr('disabled', true);
            }
        });


        // Add item to cart
        $('#update-cart').on('submit', function(e) {
            e.preventDefault();
            App.Util.hideMsg();

            $form = $(this);
            var data = $form.serializeArray();
            
            // Trigger sign up first
            if (typeof user === 'undefined' || user.id === null) {
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
            /* $active_ex_op = ($(ex + 'button.active').length) ? $(ex + 'button.active') : false;
            var exchange_option;

            if (!$active_ex_op) {
                $(ex + 'div.btn-group').addClass('has-danger');
                $(ex + 'div.form-control-feedback').removeClass('hidden').addClass('danger');
                
                return;
            } else {
                $(ex + 'div.btn-group').removeClass('has-danger');
                $(ex + 'div.form-control-feedback').addClass('hidden').removeClass('danger');
                
                exchange_option = $active_ex_op.data('option')

                data.push({
                    name: 'exchange-option', 
                    value: $active_ex_op.data('option')
                });
            } */

            // Make sure delivery address is set
            if ($('input[name="exchange"]') == 'delivery' && (buyer_lat == false || buyer_lng == false)) {
                $('#edit-delivery-address').attr('data-action', 'update-cart');
                $('#delivery-address-modal').modal('show');
                return;
            }

            // Add to cart
            if ($form.parsley().isValid()) {
                App.Util.loading();

                App.Ajax.post('order/add-to-cart', $.param(data), 
                    function(response) {
                        App.Util.slidebar(Slidebar, 'open', 'right', e);

                        $(Slidebar.events).on('opened', function () {
                            if (response.action == 'add-item') {
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
                                    '<div class="cart-item animated bounceIn" data-item-id="' + response.item.id + '">' +
                                        '<div class="item-image">' +
                                            '<img src="' + (response.item.filename ? 'https://s3.amazonaws.com/foodfromfriends/' + ENV + '/item-images/' + response.item.filename + '.' + response.item.ext : PUBLIC_ROOT + 'media/placeholders/default-thumbnail.jpg') + '" class="img-fluid"/>' +
                                        '</div>' +
                                        
                                        '<div class="item-content">' +
                                            '<div class="item-title">' +
                                                '<a href="' + PUBLIC_ROOT + response.item.link + '">' +
                                                    response.item.name +
                                                '</a>' +
                                            '</div>' +
                        
                                            '<div class="item-details">' +
                                                '<div class="item-price">' +
                                                    response.orderitem.subtotal +
                                                '</div>' +
                                                '<a class="remove-item">' +
                                                    '<i class="fa fa-times"></i>' +
                                                '</a>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>'
                                ).appendTo($cart_items);
                                
                                $('<select class="custom-select">').prependTo($cart_item.find('div.item-details'));
                                
                                for (var i = 1; i <= response.item.quantity; i++) {
                                    // $option = $('<option>').attr('value', i).text(i);
                                    $cart_item.find('select').append($('<option>').attr('value', i).attr('selected', (i == response.orderitem.quantity) ).text(i));
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
                            } else if (response.action == 'modify-quantity') {
                                // Update quantity for cart item
                                $('.cart-item[data-item-id="' + response.item.id + '"]').find('select option').attr('selected', false);
                                $('.cart-item[data-item-id="' + response.item.id + '"]').find('select option[value=' + response.orderitem.quantity + ']').attr('selected', 'selected');

                                // Update item subtotal
                                $('.cart-item[data-item-id="' + response.item.id + '"]').find('.item-price').text(response.orderitem.subtotal);
                            }

                            if (response.set_exchange) {
                                $('#ordergrower-' + response.ordergrower.id).find('.ordergrower-exchange option').attr('selected', false);
                                $('#ordergrower-' + response.ordergrower.id).find('.ordergrower-exchange option[value="' + response.ordergrower.exchange + '"]').attr('selected', 'selected');
                                $('#ordergrower-' + response.ordergrower.id).find('.rate.exchange-fee').text(response.ordergrower.ex_fee);
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
                            
                            $(Slidebar.events).unbind('opened');
                        });
                    }, function(response) {
                        App.Util.msg(response.error, 'danger');
                    }
                );
            }
        });
    };

    return {
        listener: listener
    };
}();