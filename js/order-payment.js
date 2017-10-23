/*
Step 1: include stripe.js in your view:

<script src="https://js.stripe.com/v3/"></script>



Step 2: Create a container element for your credit card field, e.g.:

<form method="post" id="payment-form">
	<div class="form-group">
		<label for="card-element">
			Credit or debit card
		</label>
		<div id="card-element">
			<!-- a Stripe Element will be inserted here. -->
		</div>
		<div id="card-errors"></div>
	</div>
	<button type="submit">Submit Payment</button>
</form>


Stripe's official Elements walkthrough is here: https://stripe.com/docs/elements
*/

var stripe = Stripe(YOUR_STRIPE_PUBLISHABLE_API_KEY_GOES_HERE);

/**
 * Initialize the card input
 */
function configureCardElement() {
    var elements = stripe.elements();

    // You can also customize the container with CSS
    var style = {
        base: {
            iconColor: '#3b76be',
            color: '#555555',
            lineHeight: '40px',
            fontWeight: 300,
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSize: '14px',
            '::placeholder': {
                color: '#bbbbbf',
            }
        }
    };

    // Create an instance of the card Element
    var card = elements.create('card', {style: style});

    // Add an instance of the card Element into the `card-element` <div>
    card.mount('#card-element');

    // Listen for and display errors
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    return card;
}

/**
 * Hit the Stripe API with card data and ask for a token.
 */
function getStripeToken(card) {
    $('button[type=submit]').attr('disabled', true);

    stripe.createToken(card).then(function(result) {
        if (result.error) {
            alert(result.error.message);
            $('button[type=submit]').attr('disabled', false);
        } else {
            pay(result.token);
        }
    });
}

/**
 * Process the payment on the FFF server
 */
function pay(stripe_token) {
    var data = {
        stripe_token: stripe_token
    };

    App.Ajax.post('orders/pay-for-order', data,
        function() {
            alert('Payment collected!');
            $('button[type=submit]').attr('disabled', false);
        },
        function(response) {
            alert(response.msg);
            $('button[type=submit]').attr('disabled', false);
        }
    );
}

// Configure event bindings to set the wheels in motion
$(document).ready(function() {
	var card = configureCardElement();

	$('#upgrade').on('submit', function(e) {
        e.preventDefault();
        getStripeToken(card);
    });
});