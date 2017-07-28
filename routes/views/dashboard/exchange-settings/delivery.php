<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="container">
                <h4 class="title">Add your delivery preferences</h4>
                <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="alert"></div>

                    <form id="add-delivery-preference" class="delivery-form" data-parsley-validate>
                        <div id="delivery-setting">
                            <div class="form-group">
                                <label>Want to make extra money by offering delivery?</label>
                                
                                <div class="radio-box">
                                    <label class="custom-control custom-radio">
                                        <input id="delivery-yes" name="delivery-setting" type="radio" value="1" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Yes</span>
                                    </label>

                                    <label class="custom-control custom-radio">
                                        <input id="delivery-no" name="delivery-setting" type="radio" value="0" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="distance-and-free-delivery-option">
                            <div class="form-group">
                                <label for="distance">Distance to buyer (miles)</label>
                                <input id="distance" type="number" name="distance-to-buyer" class="form-control " placeholder="Enter how many miles you would be willing to travel (one way)">
                            </div>
                            <div class="form-group">
                                <label>Want to offer free delivery?</label>
                                
                                <div class="radio-box">
                                    <label class="custom-control custom-radio">
                                        <input id="free-delivery" name="free-delivery-option"type="radio" value="free" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Yes</span>
                                    </label>

                                    <label class="custom-control custom-radio">
                                        <input id="no-free-delivery" name="free-delivery-option" type="radio"  value="no-free" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">No</span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input id="conditional-free-delivery" name="free-delivery-option" type="radio"  value="conditional-free" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Only under a certain distance</span>
                                    </label>
                                </div>
                            </div>
                        </div><!--close question-one-->

                        <div id="conditional-free-delivery-option">
                            <div class="form-group">
                                <label for="free-distance">Free delivery distance (Miles)</label>
                                <input id="free-distance" type="number" name="free-distance" class="form-control " placeholder="Enter how many miles you would be willing to travel for free (one way)">
                            </div>
                        </div> <!--close out class conditional free delivery -->

                        <div id="choose-delivery-fee-option">
                            <label>Have a set delivery fee or have a fee for each mile traveled?</label>

                            <div class="radio-box">
                                <label class="custom-control custom-radio">
                                    <input id="per-mile-fee"  name="fee-option" type="radio"  value="per-mile-fee" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Price per mile</span>
                                </label>

                                <label class="custom-control custom-radio">
                                    <input id="set-fee"  name="fee-option" type="radio" value="set-fee" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Set Price</span>
                                </label>
                            </div>
                        </div> <!--close choose delivery --> 

                        <div id="set-fee-option">
                            <div class="form-group">
                                <label for="setprice"> Set delivery fee</label>
                                <div class="input-group">
                                    <div class="input-group-addon">$</div>
                                    <input id="set-price" type="number" name="set-delivery-price" class="form-control" placeholder="Enter a set delivery fee">
                                </div>
                            </div>
                        </div> <!--close out set option -->

                        <div id="per-mile-option">
                            <div class="form-group">
                                <label for="perprice"> Delivery fee per mile</label>
                                <div class="input-group">
                                    <div class="input-group-addon">$</div>
                                    <input id="per-mile-price" type="number" name="per-mile-delivery-price" class="form-control" placeholder="Enter a delivery fee per mile">
                                </div>
                            </div>
                        </div> <!--close out per option -->

                        <button type="submit" class="btn btn-primary btn-block ">
                            Add delivery preference
                        </button>
                    </form>
                </div>
            </div>
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->

    