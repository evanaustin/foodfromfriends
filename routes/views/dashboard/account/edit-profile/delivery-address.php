<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Edit your delivery address
                </div>

                <div class="page-description text-muted small">
                    This information is shared with sellers when delivering your items.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="edit-delivery-address" class="btn btn-primary">
                        <i class="pre fa fa-floppy-o"></i>
                        Save changes
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr>

        <div class="alerts"></div>

        <form id="edit-delivery-address">
            <div class="row">
                <div class="col-md-6">
                    <label>
                        Delivery address
                    </label>

                    <div class="form-group">
                        <input type="text" name="address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($User->delivery_address_line_1)) { echo $User->delivery_address_line_1; } ?>" data-parsley-trigger="change" required>
                    </div>

                    <div class="form-group">
                        <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" value="<?php if (!empty($User->delivery_address_line_2)) { echo $User->delivery_address_line_2; } ?>" data-parsley-trigger="change">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="city" class="form-control" placeholder="City" value="<?php if (!empty($User->delivery_city)) { echo $User->delivery_city; } ?>" data-parsley-trigger="change" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" placeholder="State" value="<?php if (!empty($User->delivery_state)) { echo $User->delivery_state; } ?>" data-parsley-pattern="^[A-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="zipcode" class="form-control" placeholder="Zip code" value="<?php if (!empty($User->delivery_zipcode)) { echo $User->delivery_zipcode; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea name="instructions" class="form-control" placeholder="Enter any special delivery instructions the seller should know"><?php if (!empty($User->delivery_instructions)) { echo $User->delivery_instructions; } ?></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>