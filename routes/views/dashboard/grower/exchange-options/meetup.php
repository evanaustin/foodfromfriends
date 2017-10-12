<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="main container animated fadeIn">
                <?php

                if ($User->GrowerOperation->permission == 2) {

                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="page-title">
                                Set your meetup preferencs
                            </div>

                            <div class="page-description text-muted small">
                                Got a lot of orders to fulfill? Make it easier on both you and your customers by setting up a convenient meetup time and location where everyone can pick up their food after purchase.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="controls">
                                <button type="submit" form="save-meetup" class="btn btn-primary">
                                    <i class="pre fa fa-floppy-o"></i>
                                    Save changes
                                    <i class="post fa fa-gear loading-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert"></div>

                            <form id="save-meetup">
                                <div class="form-group">
                                    <label>Do you want to offer meetup?</label>
                                    
                                    <div class="radio-box">
                                        <label class="custom-control custom-radio">
                                            <input name="is-offered" type="radio" value="1" class="custom-control-input" <?php if ($details['is_offered'] == 1) { echo 'checked'; } ?>>
                                            
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">
                                                Yes
                                            </span>
                                        </label>

                                        <label class="custom-control custom-radio">
                                            <input name="is-offered" type="radio" value="0" class="custom-control-input" <?php if ($details['is_offered'] == 0) { echo 'checked'; } ?>>
                                            
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">
                                                No
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div id="meetup-details" <?php if (!$details['is_offered']) { echo 'style="display:none;"'; } ?>>
                                    <label>
                                        Where can people find you?
                                    </label>

                                    <div class="form-group">
                                        <input type="text" name="address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($details['address_line_1'])) { echo $details['address_line_1']; } ?>" data-parsley-trigger="change" <?php echo (!$details['is_offered']) ? 'disabled' : 'required'; ?>>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" value="<?php if (!empty($details['address_line_2'])) { echo $details['address_line_2']; } ?>" data-parsley-trigger="change" <?php if (!$details['is_offered']) { echo 'disabled'; } ?>>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" name="city" class="form-control" placeholder="City" value="<?php if (!empty($details['city'])) { echo $details['city']; } ?>" data-parsley-trigger="change" <?php echo (!$details['is_offered']) ? 'disabled' : 'required'; ?>>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" name="state" class="form-control" placeholder="State" value="<?php if (!empty($details['state'])) { echo $details['state']; } ?>" data-parsley-pattern="^[A-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" <?php echo (!$details['is_offered']) ? 'disabled' : 'required'; ?>>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" name="zip" class="form-control" placeholder="Zip code" value="<?php if (!empty($details['zip'])) { echo $details['zip']; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" <?php echo (!$details['is_offered']) ? 'disabled' : 'required'; ?>>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            Schedule
                                        </label>

                                        <textarea type="text" name="time" class="form-control" rows="4" placeholder="When should people meet you here?" <?php echo (!$details['is_offered']) ? 'disabled' : 'required'; ?>><?php if (!empty($details['time'])) { echo $details['time']; } ?></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <div class="side-art">
                                <?php 
                                
                                img('art/' . $imgs[array_rand($imgs)], 'png', 'local');
                                
                                ?>
                            </div>
                        </div>
                    </div>

                    <?php

                } else {
                    echo 'Oops! You\'re not supposed to be here.';
                }

                ?>
            </div> <!-- end div.main.container -->
        </div> <!-- end main -->
    </div> <!-- end div.row -->
</div> <!-- end div.container-fluid -->