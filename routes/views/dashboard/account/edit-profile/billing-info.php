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
                    <button type="submit" form="edit-billing" class="btn btn-primary">
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
                <div class="col-md-6">
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
                        <input type="text" name="address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($User->billing_address_line_1)) { echo $User->billing_address_line_1; } ?>" data-parsley-trigger="change" required>
                    </div>

                    <div class="form-group">
                        <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" value="<?php if (!empty($User->billing_address_line_2)) { echo $User->billing_address_line_2; } ?>" data-parsley-trigger="change">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="city" class="form-control" placeholder="City" value="<?php if (!empty($User->billing_city)) { echo $User->billing_city; } ?>" data-parsley-trigger="change" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" placeholder="State" value="<?php if (!empty($User->billing_state)) { echo $User->billing_state; } ?>" data-parsley-pattern="^[A-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="zipcode" class="form-control" placeholder="Zip code" value="<?php if (!empty($User->billing_zipcode)) { echo $User->billing_zipcode; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>