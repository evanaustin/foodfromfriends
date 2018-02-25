App.Front.Checkout = function () {
    // Create a Stripe client
    var stripe = Stripe(STRIPE_PK);

    var stripeElements  = $('#stripe-elements');
    var displayError    = $('#card-errors');

    function handleError(error) {
        stripeElements.removeClass('submitting');
        displayError.textContent = error;
    }

    function handleSuccess() {
        stripeElements.removeClass('submitting');
        stripeElements.addClass('submitted');
        
        // Reset cart
        App.Util.fadeAndRemove($('#ordergrowers'));
        $('#end-breakdown, hr').addClass('hidden');
        $('#empty-basket').removeClass('hidden');
    }

    function stripeTokenHandler(token) {
        var data = {
            card_name:      $('input[name="card-name"]').val(),
            address_line_1: $('input[name="address-line-1"]').val(),
            address_line_2: $('input[name="address-line-2"]').val(),
            city:           $('input[name="city"]').val(),
            state:          $('input[name="state"]').val(),
            zipcode:        $('input[name="zipcode"]').val(),
            stripe_token:   token
        };

        App.Ajax.post('order/place-order', data,
            function(response) {
                handleSuccess();
            }, function(response) {
                handleError(response.error);
            } 
        );
    }

    function listener() {
        // Create an instance of Elements
        var elements = stripe.elements();

        // Custom styling passed to options when creating an Element
        var style = {
            base: {
                color: '#4c4c4c',
                fontWeight: 500,
                fontFamily: 'Museo Sans Rounded, Inter UI, Open Sans, Segoe UI, sans-serif',
                fontSize: '16px',
                fontSmoothing: 'antialiased',

                '::placeholder': {
                    color: '#CFD7DF'
                }
            },
            invalid: {
                color: '#E25950'
            }
        };

        // Create an instance of the card Element
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element
        card.addEventListener('change', function(event) {
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission
        var form = document.getElementById('payment-form');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            // Spin loading icon
            stripeElements.addClass('submitting');

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error
                    handleError(result.error.message);
                } else {
                    // Send the token to server
                    stripeTokenHandler(result.token);
                }
            });
        });
    }

    return {
        listener: listener
    };
}();