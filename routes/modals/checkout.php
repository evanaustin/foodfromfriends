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
                <div class="elements">
                    <form id="payment-form">
                        <fieldset id="billing-info">
                            <legend>
                                Billing info
                                <span class="normal light-gray"><a href="<?php echo PUBLIC_ROOT . 'dashboard/account/billing-info'; ?>">(edit)</a></span>
                            </legend>
                            
                            <div class="container">
                                <div class="row">
                                    <div class="user-data col-12 order-2 col-md-6 order-md-1">
                                        <?php echo $User->name; ?>
                                    </div>
                                    
                                    <div class="user-data col-12 order-1 col-md-6 order-md-2">
                                        <span id="checkout-total"><?php if (isset($User->ActiveOrder)) { amount($User->ActiveOrder->total); } ?></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="user-data col-md-6">
                                        <?php echo $User->email . '<br>' . preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $User->phone); ?>
                                    </div>

                                    <div class="user-data col-md-6">
                                        <?php echo "{$User->address_line_1}<br>{$User->city} {$User->state}"; ?>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div id="paymentRequest">
                            <!--Stripe paymentRequestButton Element inserted here-->
                        </div>
                
                        <fieldset>
                            <legend class="card-only">
                                Pay with card
                            </legend>
                            
                            <legend class="payment-request-available">
                                Enter card details
                            </legend>
                            
                            <div class="container">
                                <div id="card"></div>
                
                                <button type="submit" class="btn btn-block btn-primary">
                                    Submit order
                                </button>
                            </div>
                        </fieldset>
                
                        <div class="error" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
                                <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
                            </svg>
                
                            <span class="message"></span>
                        </div>
                    </form>
                
                    <div class="success">
                        <div class="icon">
                            <svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none"></circle>
                                <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" d="M23.375 42.5488281 36.8840688 56.0578969 64.891932 28.0500338" stroke-width="4" stroke="#000" fill="none"></path>
                            </svg>
                        </div>
                
                        <h3 class="title">Payment successful</h3>
                
                        <p class="message">
                            Thanks! Your order has been submitted.<br>
                            Click <a href="<?php echo PUBLIC_ROOT . 'dashboard/account/orders-placed/overview'; ?>">here</a> to check on its status.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var STRIPE_PK = <?php echo json_encode((ENV == 'prod' ? STRIPE_PK_LIVE : STRIPE_PK_TEST)); ?>;
</script>