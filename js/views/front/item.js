App.Front.Item = function() {
    function listener() {
        // Mapbox.setZoom(13);
        Mapbox.setCenter([seller_lng, seller_lat]);
        
        var ex = '.exchange.form-group' + ' ';

        // toggle active exchange option
        $('.exchange-btn').on('click', function() {
            // trigger sign up first
            if ($('input[name="user-id"]').val() == 0) {
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

            $('#add-item, #update-item')
                .find('.btn-group')
                .removeClass('has-danger')
                .find('button')
                .removeClass('active');

            $(ex + 'div.form-control-feedback').addClass('hidden').removeClass('danger');
            
            $(this).addClass('active');
        });

        // add item to cart
        $('#add-item').on('submit', function(e) {
            e.preventDefault();
            App.Util.hideMsg();

            $form = $(this);
            var data = $form.serializeArray();
            
            // trigger sign up first
            if ($('input[name="user-id"]').val() == 0) {
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

            // make sure exchange option is selected
            $active_ex_op = ($(ex + 'button.active').length) ? $(ex + 'button.active') : false;
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
            }

            if (exchange_option == 'delivery' && (buyer_lat == false || buyer_lng == false)) {
                $('#delivery-address-modal').modal('show');
                return;
            }

            if ($form.parsley().isValid()) {
                App.Util.loading();

                App.Ajax.post('order/add-to-cart', $.param(data), 
                    function(response) {
                        App.Util.slidebar(Slidebar, 'open', 'right', e);

                        $(Slidebar.events).on('opened', function () {
                            // check if empty cart
                            if (!$('#ordergrowers').length) {
                                $('#empty-basket').addClass('hidden');
                                $('hr').removeClass('hidden');

                                $ordergrowers = $('<div id="ordergrowers">').prependTo('#cart');
                            }

                            // add ordergrower if not already in cart
                            if (!$('#ordergrower-' + response.ordergrower.id).length) {
                                $set = $('<div id="ordergrower-' + response.ordergrower.id + '" class="set">').appendTo('#ordergrowers');
                                
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
                                        '<img src="https://s3.amazonaws.com/foodfromfriends/' + ENV + '/food-listings/' + response.listing.filename + '.' + response.listing.ext + '" class="img-fluid"/>' +
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
            }
        });
        
        // update quantity of item already in cart
        $('#update-item select[name="quantity"]').on('change', function(e) {
            App.Util.hideMsg();

            $form = $('#update-item');
            var data = $form.serializeArray();
            
            var formdata = {};
            $.each(data, function() {
                formdata[this.name] = this.value;
            });
            
            if ($form.parsley().isValid()) {
                App.Util.loading();

                App.Ajax.post('order/modify-quantity', $.param(data), 
                    function(response) {
                        App.Util.slidebar(Slidebar, 'open', 'right', e);

                        $(Slidebar.events).on('opened', function () {
                            // Update quantity for cart item
                            $('.cart-item[data-listing-id="' + formdata['food-listing-id'] + '"]').find('select option').attr('selected', false);
                            $('.cart-item[data-listing-id="' + formdata['food-listing-id'] + '"]').find('select option[value=' + response.item.quantity + ']').attr('selected', 'selected');

                            // Update item subtotal
                            $('.cart-item[data-listing-id="' + formdata['food-listing-id'] + '"]').find('.item-price').text(response.item.subtotal);

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

        // update exchange setting of ordergrower
        $(document).on('click', '#update-item .exchange-btn:not(.active)', function(e) {
            e.preventDefault();
            App.Util.hideMsg();
            
            $form = $('#update-item');
            var data = $form.serializeArray();
            
            data.push({
                name: 'exchange-option', 
                value: $(this).data('option')
            });

            if ($form.parsley().isValid()) {
                App.Util.loading();

                App.Ajax.post('order/set-exchange-method', $.param(data), 
                    function(response) {
                        App.Util.slidebar(Slidebar, 'open', 'right', e);
                        
                        $(Slidebar.events).on('opened', function () {
                            $('#ordergrower-' + response.ordergrower.id).find('.label.exchange').text(response.ordergrower.exchange);
                            $('#ordergrower-' + response.ordergrower.id).find('.rate.exchange-fee').text(response.ordergrower.ex_fee);

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
                        $('#add-item').submit();
                    },
                    function(response) {
                        App.Util.finishedLoading();
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