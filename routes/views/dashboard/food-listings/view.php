<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="titlebar">
                <div class="container">
                    Edit listing: <strong><?php echo ucfirst($FoodListing->subcategory_title); ?></strong>
                </div>
            </div>

            <div class="container">
                <div class="alert"></div>

                <form id="edit-listing" data-parsley-validate>
                    <input type="hidden" name="id" value="<?php echo $FoodListing->id; ?>">

                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="price">Listing price</label>
                                <div class="input-group">
                                    <div class="input-group-addon">$</div>
                                     <input id="price" type="text" name="price" class="form-control" value="<?php echo number_format(($FoodListing->price / 100), 2); ?>" placeholder="Enter the full price for your food" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-min="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your price should include both dollars and cents (ex: $2.50)" required> 
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="weight">Average weight per item</label>
                                <div class="input-group">
                                    <input id="weight" type="number" name="weight" class="form-control" value="<?php echo $FoodListing->weight; ?>" placeholder="Enter how much an item typically weighs" min="1" max="10000" data-parsley-type="number" data-parsley-min="1" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="Please round this value to a whole number" required> 
                                    
                                    <select name="units" class="input-group-addon" data-parsley-excluded="true">
                                        <?php foreach ([
                                            'oz',
                                            'lbs',
                                            'fl oz',
                                            'liters',
                                            'gallons'
                                        ] as $unit) { ?>
                                            <option val="<?php echo $unit; ?>" <?php if ($unit == $FoodListing->units) echo 'selected'; ?>><?php echo $unit; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

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
                            
                            <div class="form-group">
                                <label for="description">Edit listing description</label>
                                <textarea type="text" name="description" class="form-control" rows="4" placeholder="Write a description of your homegrown food"><?php echo $FoodListing->description; ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="image-box">
                                <?php 
                                
                                if (!empty($FoodListing->filename)) {
                                    img('user/' . $FoodListing->filename, $FoodListing->ext . '?' . time(), 'S3', 'file');
                                } else {
                                    img('placeholders/default-thumbnail', 'jpg', 'local', 'file');
                                }

                                ?>
                                <input type="file" name="listing-image" accept="image/png/jpg">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        Update listing
                    </button>
                </form>
            </div>
        </div> <!-- end main -->
    </div> <!-- end div.row -->
</div> <!-- end div.container-fluid -->