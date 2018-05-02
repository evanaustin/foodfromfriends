<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Edit your billing information
                </div>

                <div class="page-description text-muted small">
                    This information is only used when an order is placed.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="edit-billing" class="btn btn-success">
                        <i class="pre fa fa-floppy-o"></i>
                        Save changes
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr>

        <div class="alerts"></div>

        <form id="edit-billing">
            <div class="row">
                <div class="col-md-8">
                    <label>
                        Name on card
                    </label>

                    <div class="form-group">
                        <input type="text" name="card-name" class="form-control" placeholder="Enter the name on the card" value="<?php if (!empty($User->BuyerAccount->Billing->card_name)) { echo $User->BuyerAccount->Billing->card_name; } ?>" data-parsley-trigger="change" required>
                    </div>

                    <label>
                        Billing address
                    </label>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" name="address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($User->BuyerAccount->Billing->address_line_1)) { echo $User->BuyerAccount->Billing->address_line_1; } ?>" data-parsley-trigger="change" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, etc." value="<?php if (!empty($User->BuyerAccount->Billing->address_line_2)) { echo $User->BuyerAccount->Billing->address_line_2; } ?>" data-parsley-trigger="change">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="city" class="form-control" placeholder="City" value="<?php if (!empty($User->BuyerAccount->Billing->city)) { echo $User->BuyerAccount->Billing->city; } ?>" data-parsley-trigger="change" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" placeholder="State" value="<?php if (!empty($User->BuyerAccount->Billing->state)) { echo $User->BuyerAccount->Billing->state; } ?>" data-parsley-pattern="^[a-zA-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="zipcode" class="form-control" placeholder="Zip code" value="<?php if (!empty($User->BuyerAccount->Billing->zipcode)) { echo $User->BuyerAccount->Billing->zipcode; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>