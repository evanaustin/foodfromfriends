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
                                            <input id="pickup-yes" name="is-offered" type="radio" value="1" class="custom-control-input" <?php if($details['is_offered'] == 1){echo 'checked';}?>>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Yes</span>
                                        </label>

                                        <label class="custom-control custom-radio">
                                            <input id="pickip-no" name="is-offered" type="radio" value="0" class="custom-control-input" <?php if($details['is_offered'] == 0){echo 'checked';}?>> 
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pickup-location">Add directions to help people find your food</label>
                                <textarea type="text" name="instructions" class="form-control" rows="4" placeholder="Where can people find your food?"><?php echo (!empty($details) ? ($details['instructions']) : '' ); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="when">When should people pickup your food?</label>
                                <textarea type="text" name="when" class="form-control" rows="4" placeholder="What are good times?"><?php echo (!empty($details) ? ($details['when_details']) : '' ); ?></textarea>
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