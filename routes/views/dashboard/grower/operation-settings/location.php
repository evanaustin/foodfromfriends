<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="main container animated fadeIn">
                <?php

                if ($User->permission == 2) {

                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="page-title">
                                Edit your location
                            </div>

                            <div class="page-description text-muted small">
                                This specific information is only shared when an order is confirmed. We use it to make sure that food deliveries and pickups go smoothly.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="controls">
                                <button type="submit" form="edit-location" class="btn btn-primary">
                                    <i class="pre fa fa-floppy-o"></i>
                                    Save changes
                                    <i class="post fa fa-gear loading-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="alert"></div>

                    <form id="edit-location" data-parsley-excluded="[disabled=disabled]">
                        <div class="row">
                            <div class="col-md-6">
                                <label>
                                    Where is your operation?
                                </label>

                                <div class="form-group">
                                    <input type="text" name="address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($User->GrowerOperation->address_line_1)) { echo $User->GrowerOperation->address_line_1; } ?>" data-parsley-trigger="change" <?php echo (($User->GrowerOperation->type == 'none') ? 'disabled' : 'required'); ?>>
                                </div>

                                <div class="form-group">
                                    <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" value="<?php if (!empty($User->GrowerOperation->address_line_2)) { echo $User->GrowerOperation->address_line_2; } ?>" data-parsley-trigger="change" <?php if ($User->GrowerOperation->type == 'none') { echo 'disabled'; } ?>>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="city" class="form-control" placeholder="City" value="<?php if (!empty($User->GrowerOperation->city)) { echo $User->GrowerOperation->city; } ?>" data-parsley-trigger="change" <?php echo (($User->GrowerOperation->type == 'none') ? 'disabled' : 'required'); ?>>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" name="state" class="form-control" placeholder="State" value="<?php if (!empty($User->GrowerOperation->state)) { echo $User->GrowerOperation->state; } ?>" data-parsley-pattern="^[A-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" <?php echo (($User->GrowerOperation->type == 'none') ? 'disabled' : 'required'); ?>>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" name="zip" class="form-control" placeholder="Zip code" value="<?php if (!empty($User->GrowerOperation->zipcode)) { echo $User->GrowerOperation->zipcode; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" <?php echo (($User->GrowerOperation->type == 'none') ? 'disabled' : 'required'); ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <?php

                } else {
                    echo 'You do not have permission to view this page';
                }

                ?>
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->