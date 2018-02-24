App.Front.Item = function() {
    function listener() {
        // Mapbox.setZoom(13);
        Mapbox.setCenter([lng, lat]);
        
        var ex = '.exchange.form-group' + ' ';

        // toggle active exchange option
        $('.exchange-btn').on('click', function() {
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

            // trigger sign up before adding to cart
            if ($('input[name="user-id"]').val() == 0) {
                var $sign_up_modal = $('#sign-up-modal');
                var $sign_up_form = $sign_up_modal.find('#sign-up');

                $sign_up_modal.modal();

                App.Util.msg('Hey! Sign up first &mdash; then we can build your cart!', 'info', $sign_up_form);

                $sign_up_form
                    .find('input[name="redirect"]')
                    .val(false);

                return;
            }

            $form = $(this);
            var data = $form.serializeArray();
            
            // make sure exchange option is selected
            $active_ex_op = ($(ex + 'button.active').length) ? $(ex + 'button.active') : false;

            if (!$active_ex_op) {
                $(ex + 'div.btn-group').addClass('has-danger');
                $(ex + 'div.form-control-feedback').removeClass('hidden').addClass('danger');
                
                return;
            } else {
                $(ex + 'div.btn-group').removeClass('has-danger');
                $(ex + 'div.form-control-feedback').addClass('hidden').removeClass('danger');
                
                data.push({
                    name: 'exchange-option', 
                    value: $active_ex_op.data('option')
                });
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
                                $breakdown = $('<div class="breakdown">').appendTo($set);
                            } else {
                                $cart_items = $('#ordergrower-' + response.ordergrower.id).find('div.cart-items');
                                $breakdown = $('#ordergrower-' + response.ordergrower.id).find('div.breakdown');
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
                                            '<a class="remove-item float-right">' +
                                                '<i class="fa fa-times"></i>' +
                                            '</a>' +
                                        '</div>' +
                    
                                        '<div class="item-details">' +
                                            '<div class="item-price">' +
                                                response.item.subtotal +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>'
                            ).appendTo($cart_items);
                            
                            $('<select class="custom-select">').prependTo($cart_item.find('div.item-details'));
                            
                            for (var i = 1; i < response.listing.quantity; i++) {
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
                            // update quantity for cart item
                            $('.cart-item[data-listing-id="' + formdata['food-listing-id'] + '"]').find('select option[value=' + formdata['quantity'] + ']').attr('selected', 'selected');

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
    };

    return {
        listener: listener
    };
}();