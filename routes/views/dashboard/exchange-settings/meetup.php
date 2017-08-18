<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="container">
                <h4 class="title">Add a meetup preference</h4>
                <hr>
                <div class="alert"></div>
                <div class="row">
                    <div class="col-md-6">
                        <form id="save-meetup" class="meetup-setting-form" data-parsley-validate>
                            <div id="meetup-setting">
                                <div class="form-group">
                                    <label>Do you want to offer a meetup option?</label>
                                    
                                    <div class="radio-box">
                                        <label class="custom-control custom-radio">
                                            <input id="meetup-yes" name="is_offered" type="radio" value="1" class="custom-control-input" <?php if($settings['is_offered'] == 1){echo 'checked';}?>>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Yes</span>
                                        </label>

                                        <label class="custom-control custom-radio">
                                            <input id="meetup-no" name="is_offered" type="radio" value="0" class="custom-control-input" <?php if($settings['is_offered'] == 0){echo 'checked';}?>>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id='meetup-info-1' class="meetup-location-and-time">
                                <label for="address-1">Where can people find you?</label>
                                <div class="form-group">
                                    <input type="text" name="address-line-1" class="form-control meetup-info-1" aria-describedby="address-line-1" placeholder="Address" value="<?php echo (!empty($details_1) ? ($details_1['address_line_1']) : '' ); ?>" data-parsley-trigger="submit" required disabled>
                                </div>    
                                <div class="form-group">
                                    <input type="text" name="address-line-2" class="form-control meetup-info-1" aria-describedby="address-line-2" placeholder="Apt No." value="<?php echo (!empty($details_1) ? ($details_1['address_line_2']) : '' ); ?>" data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control meetup-info-1" aria-describedby="city" placeholder="City" value="<?php echo (!empty($details_1) ? ($details_1['city']) : '' ); ?>"data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="state" class="form-control meetup-info-1" aria-describedby="state" placeholder="State"value="<?php echo (!empty($details_1) ? ($details_1['state']) : '' ); ?>" data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="zip" class="form-control meetup-info-1" aria-describedby="zip code" placeholder="Zip code" value="<?php echo (!empty($details_1) ? ($details_1['zip']) : '' ); ?>" data-parsley-trigger="submit" required disabled>
                                    <input type="hidden" name="details-id" class="form-control meetup-info-1" aria-describedby="" placeholder="" value="1" data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    
                                    <label for="when-details">When should people meet you here?</label>
                                    <textarea type="text" name="when-details" class="form-control meetup-info-1" rows="4" placeholder="When is a good time?" disabled><?php echo (!empty($details_1) ? ($details_1['when_details']) : '' ); ?></textarea>
                                </div>
                           
                                <div class="form-group">
                                    <label>Do you still want to offer this meetup?</label>
                                    
                                    <div class="radio-box meetup-info-1">
                                        <label class="custom-control custom-radio">
                                            <input id="meetup-yes" name="is_available" type="radio" value="1" class="custom-control-input meetup-info-1" <?php echo (!empty($details_1['is_available'])  ? 'checked' : '' ); ?>>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Yes</span>
                                        </label>

                                        <label class="custom-control custom-radio">
                                            <input id="meetup-no" name="is_available" type="radio" value="0" class="custom-control-input meetup-info-1" <?php echo ( empty($details_1['is_available']) ? 'checked' : '' ); ?>>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id='meetup-info-2'class="meetup-location-and-time">
                                <label for="address-1">Where can people find you?</label>
                                <div class="form-group">
                                    <input type="text" name="address-line-1-2" class="form-control meetup-info-2" aria-describedby="address" placeholder="Address" value="<?php echo (!empty($details_2) ? ($details_2['address_line_1']) : '' ); ?>" data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="address-line-2-2" class="form-control meetup-info-2" aria-describedby="address" placeholder="Address" value="<?php echo (!empty($details_2) ? ($details_2['address_line_2']) : '' ); ?>" data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="city-2" class="form-control meetup-info-2" aria-describedby="city" placeholder="City" value="<?php echo (!empty($details_2) ? ($details_2['city']) : '' ); ?>" data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="state-2" class="form-control meetup-info-2" aria-describedby="state" placeholder="State" value="<?php echo (!empty($details_2) ? ($details_2['state']) : '' ); ?>"data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="zip-2" class="form-control meetup-info-2" aria-describedby="zip code" placeholder="Zip code" value="<?php echo (!empty($details_2) ? ($details_2['zip']) : '' ); ?>"data-parsley-trigger="submit" required disabled>
                                    <input type="hidden" name="details-id-2" class="form-control meetup-info-2" aria-describedby="" placeholder="" value="2" data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <label for="when-details-2">When should people meet you here?</label>
                                    <textarea type="text" name="when-details-2" class="form-control meetup-info-2" rows="4" placeholder="When is a good time?" disabled><?php echo (!empty($details_2) ? ($details_2['when_details']) : '' ); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Do you still want to offer this meetup?</label>
                                    
                                    <div class="radio-box meetup-info-1">
                                        <label class="custom-control custom-radio">
                                            <input id="meetup-yes" name="is-available-2" type="radio" value="1" class="custom-control-input meetup-info-1" <?php echo (!empty($details_2['is_available'])  ? 'checked' : '' ); ?>>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Yes</span>
                                        </label>

                                        <label class="custom-control custom-radio">
                                            <input id="meetup-no" name="is-available-2" type="radio" value="0" class="custom-control-input meetup-info-1" <?php echo (empty($details_2['is_available'])  ? 'checked' : '' ); ?>>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id='meetup-info-3' class="meetup-location-and-time">
                                <label for="address-1-3">Where can people find you?</label>
                                <div class="form-group">
                                    <input type="text" name="address-line-1-3" class="form-control meetup-info-3" aria-describedby="address-line-1" placeholder="Address" value="<?php echo (!empty($details_3) ? ($details_3['address_line_1']) : '' ); ?>"data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="address-line-2-3" class="form-control meetup-info-3" aria-describedby="address-line-2" placeholder="Address line 2" value="<?php echo (!empty($details_3) ? ($details_3['address_line_2']) : '' ); ?>" data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="city-3" class="form-control meetup-info-3" aria-describedby="city" placeholder="City" value="<?php echo (!empty($details_3) ? ($details_3['city']) : '' ); ?>"data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="state-3" class="form-control meetup-info-3" aria-describedby="state" placeholder="State" value="<?php echo (!empty($details_3) ? ($details_3['state']) : '' ); ?>"data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="zip-3" class="form-control meetup-info-3" aria-describedby="zip code" placeholder="Zip code" value="<?php echo (!empty($details_3) ? ($details_3['zip']) : '' ); ?>" data-parsley-trigger="submit" required disabled>
                                    <input type="hidden" name="details-id-3" class="form-control meetup-info-3" aria-describedby="" placeholder="" value="3" data-parsley-trigger="submit" required disabled>
                                </div>
                                <div class="form-group">
                                    <label for="when-details-3">When should people meet you here?</label>
                                    <textarea type="text" name="when-details-3" class="form-control meetup-info-3" rows="4" placeholder="When is a good time?" disabled><?php echo (!empty($details_3) ? ($details_3['when_details']) : '' ); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Do you still want to offer this meetup?</label>
                                    
                                    <div class="radio-box meetup-info-1">
                                        <label class="custom-control custom-radio">
                                            <input id="meetup-yes" name="is-available-3" type="radio" value="1" class="custom-control-input meetup-info-1" <?php echo (!empty($details_3['is_available'])  ? 'checked' : '' ); ?>>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Yes</span>
                                        </label>

                                        <label class="custom-control custom-radio">
                                            <input id="meetup-no" name="is-available-3" type="radio" value="0" class="custom-control-input meetup-info-1" <?php echo (empty($details_3['is_available'])  ? 'checked' : '' ); ?> >
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="add-button">
                                <div class="form-group">
                                    <button id="add-button" type="button" class="btn btn-secondary btn-block">
                                            Add another meetup 
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                     Save meetup preference
                            </button>
                        </div>
                    </div>
                </form>
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->