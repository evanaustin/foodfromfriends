<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="container">
                <h4 class="title">Add a pickup preference</h4>
                <hr>

                <div class="alert"></div>
                <div class="row">
                    <div class="col-md-6">
                        <form id="save-pickup" class="pickup-form" data-parsley-validate>
                            <div id="pickup-setting">
                                <div class="form-group">
                                    <label>Do you want to offer a pickup option?</label>
                                    
                                    <div class="radio-box">
                                        <label class="custom-control custom-radio">
                                            <input id="pickup-yes" name="pickup-setting" type="radio" value="1" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Yes</span>
                                        </label>

                                        <label class="custom-control custom-radio">
                                            <input id="pickip-no" name="pickup-setting" type="radio" value="0" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="pickup-option">
                                <div class="form-group">
                                    <label>How should people pickup your food?</label>
                                    
                                    <div class="radio-box custom-controls-stacked">
                                        <label class="custom-control custom-radio">
                                            <input id="porch-pickup" name="pickup-option" type="radio" value="porch" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Porch pickup</span>
                                        </label>

                                        <label class="custom-control custom-radio">
                                            <input id="face-to-face-pickup" name="pickup-option" type="radio" value="porch" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Face to face - Just knock!</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input id="face-to-face-pickup" name="pickup-option" type="radio" value="porch" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Other - Maybe "behind the shed pickup"!</span>
                                        </label>
                                    </div>
                                </div>  
                            </div>
                            <div id="pickup-description-and-time">
                                <div class="form-group">
                                    <label for="pickup-location">Add directions to help people find your food</label>
                                    <textarea type="text" name="pickup-location" class="form-control" rows="4" placeholder="Where can people find your food?"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="pickup-time">When should people pickup your food?</label>
                                    <textarea type="text" name="pickup-time" class="form-control" rows="4" placeholder="What are good times?"></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">
                                     Add pickup preference
                            </button>
                        </div>
                    </div>
                </form>
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->