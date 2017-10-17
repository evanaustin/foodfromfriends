<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="main container animated fadeIn">
                <?php

                if ($FoodListing->grower_operation_id == $User->GrowerOperation->id) {

                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="page-title">
                                Edit listing: <strong><?php echo $listing_title; ?></strong>
                            </div>

                            <div class="page-description text-muted small">
                                Let's take another gander at this food. Revise the listing details and/or upload a new image. Only foods marked as available can be purchased by buyers.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="controls">
                                <button type="submit" form="edit-listing" class="btn btn-primary">
                                    <i class="pre fa fa-floppy-o"></i>
                                    Save changes
                                    <i class="post fa fa-gear loading-icon save"></i>
                                </button>
                
                                <a class="remove-listing btn btn-danger">
                                    <i class="pre fa fa-trash-o"></i>
                                    Delete listing
                                    <i class="post fa fa-gear loading-icon remove"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <hr>

                    <div class="alert"></div>

                    <form id="edit-listing">
                        <input type="hidden" name="id" value="<?php echo $FoodListing->id; ?>">

                        <div class="row">
                            <div class="col-md-8 flexbox flexcolumn">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantity">Quantity</label>
                                            <input id="quantity" type="number" name="quantity" class="form-control" value="<?php echo $FoodListing->quantity; ?>" placeholder="Enter how many you have in stock" min="0" max="10000" data-parsley-type="number" data-parsley-min="0" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="This value should be a whole number" required> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Availability</label>
                                            
                                            <div class="radio-box">
                                                <label class="custom-control custom-radio">
                                                    <input id="available" name="is-available" value="1" type="radio" class="custom-control-input" <?php if ($FoodListing->is_available) echo 'checked'; ?> required>
                                                    <span class="custom-control-indicator"></span>
                                                    <span class="custom-control-description">Available</span>
                                                </label>

                                                <label class="custom-control custom-radio">
                                                    <input id="unavailable" name="is-available" value="0" type="radio" class="custom-control-input" <?php if (!$FoodListing->is_available) echo 'checked'; ?> required>
                                                    <span class="custom-control-indicator"></span>
                                                    <span class="custom-control-description">Unavailable</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">Listing price</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">$</div>
                                                <input id="price" type="text" name="price" class="form-control" value="<?php echo number_format(($FoodListing->price / 100), 2); ?>" placeholder="Enter the full price for your food" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-min="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your price should include both dollars and cents (ex: $2.50)" required> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="weight">Average weight per item</label>
                                            <div class="input-group">
                                                <input id="weight" type="number" name="weight" class="form-control" value="<?php echo $FoodListing->weight; ?>" placeholder="Enter how much an item typically weighs" min="1" max="10000" data-parsley-type="number" data-parsley-min="1" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="Please round this value to a whole number" required> 
                                                
                                                <select name="units" class="input-group-addon" data-parsley-excluded="true">
                                                    <?php foreach ([
                                                        'g',
                                                        'oz',
                                                        'lb',
                                                        'kg',
                                                        'fl oz',
                                                        'liter',
                                                        'gallon'
                                                    ] as $unit) { ?>
                                                        <option val="<?php echo $unit; ?>" <?php if ($unit == $FoodListing->units) echo 'selected'; ?>><?php echo $unit; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group flexbox flexcolumn flexgrow">
                                    <label for="description">Edit listing description</label>
                                    <textarea type="text" name="description" class="form-control flexgrow" placeholder="Write a description of your homegrown food"><?php echo $FoodListing->description; ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Listing photo</label>

                                    <a href="" class="remove-image float-right <?php if (empty($FoodListing->filename)) echo 'hidden' ?>" data-listing-id="<?php echo $FoodListing->id; ?>" data-toggle="tooltip" data-placement="left" title="Remove listing photo"><i class="fa fa-trash"></i></a>

                                    <div class="image-box slide-over <?php if (!empty($FoodListing->filename)) echo 'existing-image'; ?>">
                                        <?php 
                                        
                                        if (!empty($FoodListing->filename)) {
                                            img(ENV . '/food-listings/' . $FoodListing->filename, $FoodListing->ext . '?' . time(), 'S3', 'file');
                                        } else {
                                            img('placeholders/default-thumbnail', 'jpg', 'local', 'file');
                                        }

                                        ?>

                                        <input type="file" name="listing-image" accept="image/png/jpg">

                                        <div class="overlay-slide">
                                            <i class="fa fa-camera"></i>
                                            Update listing photo
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Save changes

                                    <span class="float-right">
                                        <i class="fa fa-gear loading-icon"></i>
                                    </span>
                                </button>
                            </div> -->
                        </div>
                    </form>

                    <?php

                } else {

                    ?>
                    
                    <p class="text-muted">
                        Oops, looks like this listing doesn't belong to you.
                    </p>

                    <?php
                }

                ?>
            </div> <!-- end div.main.container -->
        </div> <!-- end main -->
    </div> <!-- end div.row -->
</div> <!-- end div.container-fluid -->