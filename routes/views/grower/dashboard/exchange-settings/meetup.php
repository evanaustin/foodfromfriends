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
                                            <input id="meetup-yes" name="meetup-setting" type="radio" value="1" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Yes</span>
                                        </label>

                                        <label class="custom-control custom-radio">
                                            <input id="meetup-no" name="meetup-setting" type="radio" value="0" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id='meetup-info-1' class="meetup-location-and-time">
                                <label for="address-1">Where can people find you?</label>
                                <div class="form-group">
                                    <input type="text" name="address-1" class="form-control" aria-describedby="address" placeholder="Address" data-parsley-trigger="submit" required>
                                    <input type="text" name="city-1" class="form-control" aria-describedby="city" placeholder="City" data-parsley-trigger="submit" required>
                                    <input type="text" name="state-1" class="form-control" aria-describedby="state" placeholder="State" data-parsley-trigger="submit" required>
                                    <input type="text" name="zipcode-1" class="form-control" aria-describedby="zip code" placeholder="Zip code" data-parsley-trigger="submit" required>
                                </div>
                                <div class="form-group">
                                    
                                    <label for="meetup-time">When should people meet you here?</label>
                                    <textarea type="text" name="meetup-time" class="form-control" rows="4" placeholder="When will you be here?"></textarea>
                                </div>
                            </div>
                            <div id='meetup-info-2'class="meetup-location-and-time">
                                <label for="address-1">Where can people find you?</label>
                                <div class="form-group">
                                    <input type="text" name="address-2" class="form-control" aria-describedby="address" placeholder="Address" data-parsley-trigger="submit" required>
                                    <input type="text" name="city-2" class="form-control" aria-describedby="city" placeholder="City" data-parsley-trigger="submit" required>
                                    <input type="text" name="state-2" class="form-control" aria-describedby="state" placeholder="State" data-parsley-trigger="submit" required>
                                    <input type="text" name="zipcode-2" class="form-control" aria-describedby="zip code" placeholder="Zip code" data-parsley-trigger="submit" required>
                                </div>
                                <div class="form-group">

                                    <label for="meetup-time">When should people meet you here?</label>
                                    <textarea type="text" name="meetup-time" class="form-control" rows="4" placeholder="When will you be here?"></textarea>
                                </div>
                            </div>
                            <div id='meetup-info-3' class="meetup-location-and-time">
                                <label for="address-1">Where can people find you?</label>
                                <div class="form-group">
                                    <input type="text" name="address-3" class="form-control" aria-describedby="address" placeholder="Address" data-parsley-trigger="submit" required>
                                    <input type="text" name="city-3" class="form-control" aria-describedby="city" placeholder="City" data-parsley-trigger="submit" required>
                                    <input type="text" name="state-3" class="form-control" aria-describedby="state" placeholder="State" data-parsley-trigger="submit" required>
                                    <input type="text" name="zipcode-3" class="form-control" aria-describedby="zip code" placeholder="Zip code" data-parsley-trigger="submit" required>
                                </div>
                                <div class="form-group">
                                    <label for="meetup-time">When should people meet you here?</label>
                                    <textarea type="text" name="meetup-time" class="form-control" rows="4" placeholder="When will you be here?"></textarea>
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