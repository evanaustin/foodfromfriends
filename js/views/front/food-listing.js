App.Front.FoodListing = function() {
    function listener() {
        App.Util.slidebar(Slidebar, 'toggle', 'right');

        // Mapbox.setZoom(13);
        Mapbox.setCenter([lng, lat]);
        
        var ex = 'div.exchange.form-group' + ' ';

        // toggle active exchange option
        $(ex + 'div.btn-group button').on('click', function() {
            $('#add-item')
                .find('div.btn-group')
                .removeClass('has-danger')
                .find('button')
                .removeClass('active');

            $(ex + 'div.form-control-feedback').addClass('hidden').removeClass('form-control-danger');
            
            $(this).addClass('active');
        });

        // add item to cart
        $('#add-item').on('submit', function(e) {
            e.preventDefault();
            App.Util.hideMsg();

            // trigger sign up before adding to cart
            if ($('input[name="user_id"]').val() == 0) {
                var $sign_up_modal = $('#sign-up-modal');
                var $sign_up_form = $sign_up_modal.find('#sign-up');

                $sign_up_modal.modal();

                App.Util.msg('Hey! Sign on up first &mdash; then we can build your cart!', 'info', $sign_up_form);

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
                $(ex + 'div.form-control-feedback').removeClass('hidden').addClass('form-control-danger');
                
                return;
            } else {
                $(ex + 'div.btn-group').removeClass('has-danger');
                $(ex + 'div.form-control-feedback').addClass('hidden').removeClass('form-control-danger');
                
                data.push({
                    name: 'exchange_option', 
                    value: $active_ex_op.data('option')
                });
            }

            if ($form.parsley().isValid()) {
                App.Util.loading();
                
                App.Ajax.post('order/add-to-cart', $.param(data), 
                    function(response) {
                        App.Util.slidebar(Slidebar, 'open', 'right', e);

                        $(Slidebar.events).on('opened', function () {
                            $('#cart > .set:first-child').append(
                                '<div class="cart-item animated bounceIn">' +
                                    '<div class="item-image">' +
                                        // dynamically get ext
                                        '<img src="https://s3.amazonaws.com/foodfromfriends/dev/food-listings/fl.' + $('input[name="food_listing_id"]').val() + '.jpg" class="img-fluid"/>' +
                                    '</div>' +
                                    
                                    '<div class="item-content">' +
                                        '<div class="item-title">' +
                                            '<a href="">' +
                                                'Artichokes' +
                                            '</a>' +
                                        '</div>' +
                    
                                        '<div class="item-details">' +
                                            '<select class="custom-select">' +
                                                '<option>1</option>' +
                                            '</select>' +
                                            
                                            '<div class="item-price">' +
                                                '$1.00' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>');
                        });
                    }, function(response) {
                        console.log(response.error);
                    }
                );
            }
        });
    };

    return {
        listener: listener
    };
}();