<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Set your delivery preferencs
                </div>

                <div class="page-description text-muted small">
                    Thinking about making an extra buck by offering delivery to your customers? With all our customizable settings, you can create a delivery policy that suits you perfectly.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="save-delivery" class="btn btn-success">
                        <i class="pre fa fa-floppy-o"></i>
                        Save changes
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <hr>
        
        <div class="alerts"></div>

        <div class="row">
            <div class="col-md-6">

                <form id="save-delivery" data-parsley-excluded="[disabled=disabled]">
                    <div id="delivery-setting">
                        <div class="form-group">
                            <label>
                                Do you want to offer delivery?
                            </label>
                            
                            <div class="radio-box">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="yes-offered" name="is-offered" class="custom-control-input" value="1" <?php if ($details['is_offered'] == 1) { echo 'checked'; } ?>>
                                    <label class="custom-control-label" for="yes-offered">Yes</label>
                                </div>
                                
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no-offered" name="is-offered" class="custom-control-input" value="0" <?php if ($details['is_offered'] == 0) { echo 'checked'; } ?>>
                                    <label class="custom-control-label" for="no-offered">No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="distance" class="setting" <?php if (!$details['is_offered']) { echo 'style="display:none;"'; } ?>>
                        <div class="form-group">
                            <label for="distance">
                                Distance to buyer (one way)
                            </label>

                            <div class="input-group w-addon">
                                <input type="number" name="distance" class="form-control" placeholder="Enter how far you would travel" value="<?php if (!empty($details['distance'])) { echo $details['distance']; } ?>" min="1" max="10000" data-parsley-type="number" data-parsley-min="1" data-parsley-type-message="Please round this value to a whole number" data-parsley-trigger="change" <?php if (empty($details['is_offered'])) { echo 'disabled'; } ?>>
                                <span class="input-group-addon">miles</span>
                            </div>
                        </div>
                    </div>
                            
                    <div id="delivery-type" class="setting" <?php if (!$details['is_offered']) { echo 'style="display:none;"'; } ?>>
                        <div class="form-group">  
                            <label>
                                How do you want to offer delivery?
                            </label>

                            <div class="radio-box">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="delivery-charge" name="delivery-type" class="custom-control-input" value="charge" <?php if ($details['delivery_type'] == 'charge') { echo 'checked'; } else if (empty($details['is_offered'])) { echo 'disabled'; } ?>>
                                    <label class="custom-control-label" for="delivery-charge">Charge</label>
                                </div>
                                
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="delivery-free" name="delivery-type" class="custom-control-input" value="free" <?php if ($details['delivery_type'] == 'free') { echo 'checked'; } else if (empty($details['is_offered'])) { echo 'disabled'; } ?>>
                                    <label class="custom-control-label" for="delivery-free">Free</label>
                                </div>
                                
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="delivery-conditional" name="delivery-type" class="custom-control-input" value="conditional" <?php if ($details['delivery_type'] == 'conditional') { echo 'checked'; } else if (empty($details['is_offered'])) { echo 'disabled'; } ?>>
                                    <label class="custom-control-label" for="delivery-conditional">Free under a certain distance</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="conditional-free-delivery" class="setting fee" <?php if (!$details['is_offered'] || $details['delivery_type'] != 'conditional') { echo 'style="display:none;"'; } ?>>
                        <div class="form-group">
                            <label for="free-distance">
                                Free delivery distance (one way)
                            </label>

                            <div class="input-group w-addon">
                                <input type="number" name="free-distance" class="form-control" placeholder="Enter how far you would travel for free" value="<?php if (!empty($details['free_distance'])) { echo $details['free_distance']; } ?>" min="1" max="10000" data-parsley-type="number" data-parsley-min="1" data-parsley-type-message="Please round this value to a whole number" data-parsley-trigger="change" <?php if (empty($details['is_offered']) || $details['delivery_type'] != 'conditional') { echo 'disabled'; } ?>>
                                
                                <span class="input-group-addon">
                                    miles
                                </span>
                            </div>
                        </div>
                    </div>

                    <div id="fee" class="setting fee" <?php if (!$details['is_offered'] || (!empty($details['delivery_type']) && $details['delivery_type'] == 'free')) { echo 'style="display:none;"'; } ?>>
                        <div class="form-group">
                            <label for="fee-rate">
                                Set your delivery fee
                            </label>

                            <div class="input-group w-addon">
                                <div class="input-group-addon">$</div>
                                
                                <input id="fee-rate" type="text" name="fee" class="form-control" placeholder="Enter a delivery fee" value="<?php if (!empty($details['fee'])) { echo number_format($details['fee'] / 100, 2); } ?>" data-parsley-type="number" data-parlsey-min="0" data-parlsey-max="99999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your price should include both dollars and cents (ex: $2.50)" data-parsley-trigger="change" <?php if (empty($details['is_offered']) || $details['delivery_type'] == 'free') { echo 'disabled'; } ?>>
                                
                                <select name="pricing-rate" class="input-group-addon" data-parsley-excluded="true">
                                    <option value="per-mile" <?php if ($details['pricing_rate'] == 'per-mile') { echo 'selected'; } ?>>per mile</option>    
                                    <option value="flat-rate" <?php if ($details['pricing_rate'] == 'flat-rate') { echo 'selected'; } ?>>flat rate</option>    
                                </select>
                            </div>
                            
                            <small id="feeHelp" class="form-text text-muted" <?php if (!$details['is_offered'] || (!empty($details['delivery_type']) && $details['delivery_type'] != 'conditional')) { echo 'style="display:none;"'; } ?>>
                                Delivery fee is charged only after free delivery distance threshold is reached
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>