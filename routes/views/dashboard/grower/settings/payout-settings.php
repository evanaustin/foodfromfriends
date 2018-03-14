<!-- cont main -->
<div class="container animated fadeIn">
        <?php

        if ($User->GrowerOperation->permission == 2) {

            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Payout settings
                    </div>

                    <div class="page-description text-muted small">
                        When you receive a payment for an order we add that total to your monthly payout, which can be set up and edited here. Payouts are issued on the 21st day of each month.
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="controls">
                        <button type="submit" form="edit-payout" class="btn btn-success">
                            <i class="pre fa fa-floppy-o"></i>
                            Save changes
                            <i class="post fa fa-gear loading-icon"></i>
                        </button>
                    </div>
                </div>
            </div>

            <hr>

            <div class="alerts"></div>
            
            <form id="edit-payout" data-parsley-excluded="[disabled=disabled]">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>
                                Pay to
                            </label>

                            <input type="text" name="pay-to" class="form-control" placeholder="Business/person name" value="<?php echo (!empty($payout_settings['pay_to'])) ? $payout_settings['pay_to'] : $User->GrowerOperation->name; ?>" data-parsley-trigger="submit" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Routing
                                    </label>

                                    <input type="text" name="routing" class="form-control" placeholder="Bank routing number" value="<?php if (!empty($payout_settings['routing_number'])) echo $payout_settings['routing_number']; ?>" data-parsley-trigger="submit" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Bank Account
                                    </label>

                                    <input type="text" name="bank-account" class="form-control" placeholder="Bank account number" value="<?php if (!empty($payout_settings['bank_account_number'])) echo $payout_settings['bank_account_number']; ?>" data-parsley-trigger="submit" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        First name (optional)
                                    </label>

                                    <input type="text" name="first-name" class="form-control" placeholder="First name of payee" value="<?php if (!empty($payout_settings['first_name'])) echo $payout_settings['first_name']; ?>" data-parsley-trigger="submit">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Last name (optional)
                                    </label>

                                    <input type="text" name="last-name" class="form-control" placeholder="Last name of payee" value="<?php if (!empty($payout_settings['last_name'])) echo $payout_settings['last_name']; ?>" data-parsley-trigger="submit">
                                </div>
                            </div>
                        </div>

                        <label>
                            Mailing address
                        </label>

                        <div class="form-group">
                            <input type="text" name="address-line-1" class="form-control" placeholder="Address" value="<?php echo (!empty($payout_settings['address_line_1'])) ? $payout_settings['address_line_1'] : $User->GrowerOperation->address_line_1; ?>" data-parsley-trigger="change" required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" value="<?php echo (!empty($payout_settings['address_line_2'])) ? $payout_settings['address_line_2'] : $User->GrowerOperation->address_line_2; ?>" data-parsley-trigger="change">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control" placeholder="City" value="<?php echo (!empty($payout_settings['city'])) ? $payout_settings['city'] : $User->GrowerOperation->city; ?>" data-parsley-trigger="change" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="state" class="form-control" placeholder="State" value="<?php echo (!empty($payout_settings['state'])) ? $payout_settings['state'] : $User->GrowerOperation->state; ?>" data-parsley-pattern="^[A-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="zipcode" class="form-control" placeholder="Zip code" value="<?php echo (!empty($payout_settings['zipcode'])) ? $payout_settings['zipcode'] : $User->GrowerOperation->zipcode; ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?php

        } else {
            echo 'Oops! You\'re not allowed access to this page.';
        }

        ?>
    </div>
</main>