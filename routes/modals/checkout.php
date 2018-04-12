<div id="checkout-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Checkout</h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <div id="stripe-elements">
                    <form id="payment-form">
                        <label>
                            Name on card
                        </label>

                        <div class="form-group">
                            <input type="text" name="card-name" class="form-control" placeholder="Enter the name on the card" value="<?php if (!empty($User->billing_card_name)) { echo $User->billing_card_name; } ?>" data-parsley-trigger="change" required>
                        </div>

                        <label>
                            Billing address
                        </label>

                        <div class="form-group">
                            <input type="text" name="billing-address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($User->billing_address_line_1)) { echo $User->billing_address_line_1; } ?>" data-parsley-trigger="change" required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="billing-address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" value="<?php if (!empty($User->billing_address_line_2)) { echo $User->billing_address_line_2; } ?>" data-parsley-trigger="change">
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="billing-city" class="form-control" placeholder="City" value="<?php if (!empty($User->billing_city)) { echo $User->billing_city; } ?>" data-parsley-trigger="change" required>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <input type="text" name="billing-state" class="form-control" placeholder="State" value="<?php if (!empty($User->billing_state)) { echo $User->billing_state; } ?>" data-parsley-pattern="^[A-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                                </div>
                            </div>
                            
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <input type="text" name="billing-zipcode" class="form-control" placeholder="Zip code" value="<?php if (!empty($User->billing_zipcode)) { echo $User->billing_zipcode; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                                </div>
                            </div>
                        </div>    

                        <label for="card-element">
                            Credit or debit card
                        </label>

                        <div id="card-element">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>

                        <!-- Used to display form errors. -->
                        <div id="card-errors" role="alert"></div>

                        <button class="btn btn-block btn-primary">
                            Submit order &mdash; <span id="checkout-total"><?php if (isset($User->ActiveOrder)) { amount($User->ActiveOrder->total); } ?></span>
                        </button>
                    </form>

                    <div id="payment-success">
                        <div class="icon">
                            <svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none"></circle>
                                <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" d="M23.375 42.5488281 36.8840688 56.0578969 64.891932 28.0500338" stroke-width="4" stroke="#000" fill="none"></path>
                            </svg>
                        </div>
                
                        <h3 class="title">Payment successful</h3>
                
                        <p class="message">
                            Thanks! Your order has been submitted.<br>
                            Click <a href="<?= PUBLIC_ROOT . 'dashboard/account/buying/orders'; ?>">here</a> to check on its status.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var STRIPE_PK = <?= json_encode((ENV == 'prod' ? STRIPE_PK_LIVE : STRIPE_PK_TEST)); ?>;
</script>