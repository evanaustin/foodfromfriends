App.Front.Checkout = function () {
    var stripe = Stripe(STRIPE_TEST);

    function registerElements(elements) {
        var wrapper = document.querySelector('.elements');

        var form = wrapper.querySelector('form');
        var resetButton = wrapper.querySelector('a.reset');
        var error = form.querySelector('.error');
        var errorMessage = error.querySelector('.message');

        function enableInputs() {
            Array.prototype.forEach.call(
                form.querySelectorAll(
                    "input[type='text'], input[type='email'], input[type='tel']"
                ),
                function(input) {
                    input.removeAttribute('disabled');
                }
            );
        }

        function disableInputs() {
            Array.prototype.forEach.call(
                form.querySelectorAll(
                    "input[type='text'], input[type='email'], input[type='tel']"
                ),
                function(input) {
                    input.setAttribute('disabled', 'true');
                }
            );
        }

        // Listen for errors from each Element, and show error messages in the UI.
        elements.forEach(function(element) {
            element.on('change', function(event) {
                if (event.error) {
                    error.classList.add('visible');
                    errorMessage.innerText = event.error.message;
                } else {
                    error.classList.remove('visible');
                }
            });
        });

        // Listen on the form's 'submit' handler...
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Show a loading screen...
            wrapper.classList.add('submitting');

            // Disable all inputs.
            disableInputs();

            // Use Stripe.js to create a token. We only need to pass in one Element
            // from the Element group in order to create a token.
            stripe.createToken(elements[0]).then(function(result) {
                if (result.token) {
                    var data = {
                        stripe_token: result.token.id
                    };
        
                    App.Ajax.post('order/place-order', data,
                        function(response) {
                            wrapper.classList.remove('submitting');
                            wrapper.classList.add('submitted');
                            // result.complete('success');
                        }, function(response) {
                            wrapper.classList.remove('submitting');
                            console.log(response.error);
                            // result.complete('fail');
                        } 
                    );
                } else {
                    // Otherwise, un-disable inputs.
                    enableInputs();
                }
            });
        });
    }

    function listener() {
        'use strict';

        // Initialize the card input
        var elements = stripe.elements({
            fonts: [{cssSrc: 'https://rsms.me/inter/inter-ui.css'}],
            locale: 'auto'
        });

        // Customize the card Element
        var style = {
            base: {
                color: '#4c4c4c',
                fontWeight: 500,
                fontFamily: 'Inter UI, Open Sans, Segoe UI, sans-serif',
                fontSize: '15px',
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

        // Add an instance of the card Element into the 'card' <div>
        card.mount('#card');

        /* $('#payment-form').on('submit', function(e) {
            e.preventDefault();
            // getStripeToken(card);
        }); */

        /**
         * Payment Request Element
         */
        /* var paymentRequest = stripe.paymentRequest({
            country: 'US',
            currency: 'usd',
            total: {
                amount: 2000,
                label: 'Total'
            }
        }); */

        /* paymentRequest.on("token", function (result) {
            var example = document.querySelector("elements");
            example.querySelector(".token").innerText = result.token.id;
            example.classList.add("submitted");
            result.complete("success");
        }); */

        /* paymentRequest.on('token', function (ev) {
            console.log('on token');
            // Send the token to your server to charge it!
            fetch('/charges', {
                method: 'POST',
                body: JSON.stringify({ token: ev.token.id }),
            }).then(function (response) {
                if (response.ok) {
                    // Report to the browser that the payment was successful, prompting
                    // it to close the browser payment interface.
                    ev.complete('success');
                } else {
                    // Report to the browser that the payment failed, prompting it to
                    // re-show the payment interface, or show an error message and close
                    // the payment interface.
                    ev.complete('fail');
                }
            });
        }); */

        /* var paymentRequestElement = elements.create('paymentRequestButton', {
            paymentRequest: paymentRequest,
            style: {
                paymentRequestButton: {
                    type: 'pay'
                }
            }
        });

        paymentRequest.canMakePayment().then(function (result) {
            if (result) {
                document.querySelector('elements .card-only').style.display = 'none';
                document.querySelector(
                    'elements .payment-request-available'
                ).style.display =
                    'block';
                paymentRequestElement.mount('#paymentRequest');
            }
        }); */

        registerElements([card/* , paymentRequestElement */]);
    }

    return {
        listener: listener
    };
}();