App.Front.SellerProfile = function() {
    function listener() {
        if ($('#map').length) {
            Mapbox.setCenter([lng, lat]);
        }

        $('#request-wholesale').on('click', function(e) {
            // trigger sign up before requesting wholesale membership
            if (user == 0) {
                e.preventDefault();
                
                var $sign_up_modal = $('#sign-up-modal');
                var $sign_up_form = $sign_up_modal.find('#sign-up');

                $sign_up_modal.modal();

                App.Util.msg('Sign up first &mdash; then you can request a wholesale membership!', 'info', $sign_up_form);

                $sign_up_form
                    .find('input[name="redirect"]')
                    .val(false);

                return;
            }

            var data = {
                'seller-id' : $(this).data('seller-id')
            };

            bootbox.confirm({
                closeButton: false,
                title: 'Request wholesale account membership',
                message: 'Please confirm you want to submit a request for a wholesale membership with ' + seller_name + ' as <strong>' + buyer_name + '</strong>',
                buttons: {
                    confirm: {
                        label: 'Submit',
                        className: 'btn-warning'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-muted'
                    }
                },
                callback: function(result) {
                    if (result === true) {
                        App.Ajax.post('dashboard/buying/wholesale/request-membership', $.param(data), 
                            function(response) {
                                toastr.success('Wholesale membership requested');
                                // App.Util.msg('Wholesale membership requested', 'success');
                            }, function(response) {
                                toastr.error(response.error);
                                // App.Util.msg(response.error, 'danger');
                            }
                        );

                        // window.location.replace(href);
                    }
                }
            });
        });

        $('#message').on('click', function(e) {
            // trigger sign up before directing to messages
            if (user == 0) {
                e.preventDefault();
                
                var $sign_up_modal = $('#sign-up-modal');
                var $sign_up_form = $sign_up_modal.find('#sign-up');

                $sign_up_modal.modal();

                App.Util.msg('Sign up first &mdash; then you can send a message!', 'info', $sign_up_form);

                $sign_up_form
                    .find('input[name="redirect"]')
                    .val(false);

                return;
            }
        });

        
        // Change package option
        $('.quick-add select.item-option').on('change', function() {
            var id = $(this).val();
            $card_body = $(this).parents('.card-body');

            $card_body.find('.price').text(items[id].price);
            $card_body.find('.rating').html(items[id].rating);
            $card_body.find('.title').text(items[id].title);
            
            $(this).parents('.quick-add').siblings('.title').find('a').attr('href', PUBLIC_ROOT + seller_link + '/' + items[id].link + '&package=' + id);

            if (items[id].quantity > 0) {
                $card_body.find('input[type="submit"]')
                .removeClass('btn-danger')
                .addClass('btn-cta')
                .val((items[id].in_cart ? 'Update item in basket' : 'Add to basket'))
                .attr('disabled', false);

                $card_body.find('.item-quantity')
                    .removeClass('hidden')
                    .empty();

                for (var i = 1; i <= items[id].quantity; i++) {
                    $card_body.find('.item-quantity').append($('<option>', {
                        value: i, 
                        text: i,
                        selected: (items[id].in_cart && items[id].cart_qty == i ? true : false)
                    }));
                }
            } else {
                $card_body.find('.item-quantity')
                    .addClass('hidden')
                    .empty();

                $card_body.find('input[type="submit"].btn-cta')
                    .removeClass('btn-cta')
                    .addClass('btn-danger')
                    .val('Out of stock')
                    .attr('disabled', true);
            }
        });


        // Add to cart
        $('form.quick-add').on('submit', function(e) {
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
                
                App.Ajax.post('order/add-to-cart', $.param(data), 
                    function(response) {
                        App.Util.slidebar(Slidebar, 'open', 'right', e);

                        $(Slidebar.events).on('opened', function () {
                            // Check if cart is empty
                            if (!$('#ordergrowers').length) {
                                $('#empty-basket').addClass('hidden');
                                $('hr').removeClass('hidden');

                                $ordergrowers = $('<div id="ordergrowers">').prependTo('#cart');
                            }

                            // Check if OrderGrower is already in cart
                            if (!$('#ordergrower-' + response.ordergrower.id).length) {
                                $set = $('<div id="ordergrower-' + response.ordergrower.id + '" class="set" data-grower-operation="' + response.ordergrower.grower_id + '">').appendTo('#ordergrowers');
                                
                                $set.append('<h6>' + response.ordergrower.name + '</h6>');

                                $cart_items = $('<div class="cart-items">').appendTo($set);
                                $breakdown  = $('<div class="breakdown">').appendTo($set);
                            } else {
                                $cart_items = $('#ordergrower-' + response.ordergrower.id).find('div.cart-items');
                                $breakdown  = $('#ordergrower-' + response.ordergrower.id).find('div.breakdown');
                            }
                            
                            var package = (response.item.measurement != '' && response.item.metric != '') ? response.item.measurement + ' ' + response.item.metric + ' ' + response.item.package_type : response.item.package_type;
                            
                            // Check if OrderItem is already in cart
                            if (response.action == 'add-item') {
                                $cart_item = $(
                                    '<div class="cart-item animated bounceIn" data-item-id="' + response.item.id + '">' +
                                        '<div class="item-image">' +
                                            '<div class="user-photo no-margin" style="background-image: url(' + (response.item.filename ? 'https://s3.amazonaws.com/foodfromfriends/' + ENV + '/item-images/' + response.item.filename + '.' + response.item.ext : PUBLIC_ROOT + 'media/placeholders/default-thumbnail.jpg') + '); height: 50px; width: 50px;"></div>' +
                                        '</div>' +

                                        '<div class="item-content">' +
                                            '<div class="item-title">' +
                                                '<a href="' + PUBLIC_ROOT + response.item.link + '">' +
                                                    response.item.name +
                                                '</a>' +
                                            '</div>' +

                                            '<div class="small light-gray">' +
                                                package.charAt(0).toUpperCase() + package.slice(1) +
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
                            } else if (response.action == 'modify-quantity') {
                                // Update quantity for cart item
                                $('.cart-item[data-item-id="' + response.item.id + '"]').find('select option').attr('selected', false);
                                $('.cart-item[data-item-id="' + response.item.id + '"]').find('select option[value=' + response.orderitem.quantity + ']').attr('selected', 'selected');

                                // Update item subtotal
                                $('.cart-item[data-item-id="' + response.item.id + '"]').find('.item-price').text(response.orderitem.subtotal);
                            }

                            // update OrderGrower line amount
                            var exchange_title = response.ordergrower.exchange.charAt(0).toUpperCase() + response.ordergrower.exchange.slice(1)

                            if ($breakdown.children().length) {
                                $breakdown.find('.label.exchange').text(exchange_title);
                                $breakdown.find('.rate.exchange-fee').text(response.ordergrower.ex_fee);
                            } else {
                                $breakdown.append(
                                    '<div class="line-amount">' +
                                        '<div class="label exchange">' +
                                            exchange_title +
                                        '</div>' +
                                        
                                        '<div class="rate exchange-fee">' +
                                            ((response.ordergrower.exchange == 'delivery') ? response.ordergrower.ex_fee : 'Free') +
                                        '</div>' +
                                    '</div>'
                                );
                            }

                            // update totals breakdown
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
                        toastr.error(response.error);
                    }
                );
            }
        });
    };

    return {
        listener: listener
    };
}();
