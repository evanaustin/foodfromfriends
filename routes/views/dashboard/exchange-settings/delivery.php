<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="container">
                <h4 class="title">Add your delivery preferences</h4>
                <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="alert"></div>
                    <form id="save-delivery" class="delivery-form" data-parsley-validate>
                        <div id="delivery-setting">
                            <div class="form-group">
                                <label>Want to make extra money by offering delivery?</label>
                                
                                <div class="radio-box">
                                    <label class="custom-control custom-radio">
                                        <input id="delivery-yes" name="is-offered" type="radio" value="1" class="custom-control-input" <?php if($details['is_offered'] == 1){echo 'checked';}?>>
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Yes</span>
                                    </label>

                                    <label class="custom-control custom-radio">
                                        <input id="delivery-no" name="is-offered" type="radio" value="0" class="custom-control-input"  <?php if($details['is_offered'] == 0){echo 'checked';}?> >
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="distance-and-free-delivery-option">
                            <div class="form-group">
                                <label for="distance">Distance to buyer (miles)</label>
                                <input id="distance" type="number" name="distance" class="form-control " placeholder="Enter how many miles you would be willing to travel (one way)" value="<?php echo (!empty($details) ? ($details['distance']) : '' ); ?>" min="0" data-parsley-min="0" data-parsley-trigger disabled>
                            </div>
                            <div class="form-group">  
                                <label>Want to offer free delivery?</label>
                                
                                <div class="radio-box">
                                    <label class="custom-control custom-radio">
                                        <input id="free-delivery" name="free-delivery" type="radio" value="1" class="custom-control-input"  <?php if($details['free_delivery'] == 1){echo 'checked';} ?> data-parsley-trigger disabled >
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Yes</span>
                                    </label>

                                    <label class="custom-control custom-radio">
                                        <input id="no-free-delivery" name="free-delivery" type="radio"  value="2" class="custom-control-input" <?php if($details['free_delivery'] == 2){echo 'checked';}?>  data-parsley-trigger disabled >
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">No</span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input id="conditional-free-delivery" name="free-delivery" type="radio"  value="conditional" class="custom-control-input" <?php if($details['free_delivery'] == 'conditional'){echo 'checked';}?>  data-parsley-trigger disabled >
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Only under a certain distance</span>
                                    </label>
                                </div>
                            </div>
                        </div><!--close question-one-->

                        <div id="conditional-free-delivery-option">
                            <div class="form-group">
                                <label for="free-distance">Free delivery distance (Miles)</label>
                                <input id="free-distance" type="number" name="free-miles" class="form-control " placeholder="Enter how many miles you would be willing to travel for free (one way)" value="<?php echo (!empty($details) ? ($details['free_miles']) : '' ); ?>"   min="0.1" data-parsley-min="0.1" step="0.1" data-parsley-trigger disabled>
                            </div>
                        </div> <!--close out class conditional free delivery -->

                        <div id="choose-delivery-fee-option">
                            <label>Have a set delivery fee or have a fee for each mile traveled?</label>

                            <div class="radio-box">
                                <label class="custom-control custom-radio">
                                    <input id="per-mile-fee"  name="pricing-rate" type="radio"  value="mile" class="custom-control-input" data-parsley-trigger disabled <?php if($details['pricing_rate'] == 'mile'){echo 'checked';}?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Price per mile</span>
                                </label>

                                <label class="custom-control custom-radio">
                                    <input id="set-fee"  name="pricing-rate" type="radio" value="flat" class="custom-control-input"data-parsley-trigger disabled <?php if($details['pricing_rate'] == 'flat'){echo 'checked';}?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Set Price</span>
                                </label>
                            </div>
                        </div> <!--close choose delivery --> 
                        <div id="per-mile-option">
                            <label for="fee"> Delivery fee per mile</label>  
                        </div>
                        <div id="set-fee-option">
                            <label for="setprice"> Set delivery fee</label>
                        </div>
                        <div id='fee'>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">$</div>
                                    <input type="number" name="fee" class="form-control" placeholder="Enter a delivery fee" value="<?php echo (!empty($details) ? ($details['fee']) : '' ); ?>"   min="0.01" data-parsley-min="0.01" step="0.01"data-parsley-trigger disabled>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block ">
                            Add delivery preference
                        </button>
                    </form>
                </div>
            </div>
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->

    