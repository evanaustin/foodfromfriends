<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="titlebar">
                <div class="container">
                    Add new food listing
                </div>
            </div>
            
            <div class="container">
                <div class="alert"></div>

                <form id="add-listing" data-parsley-validate>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12"> 
                                    <label for="food-categories">What kind of food do you have?</label>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select id="food-categories" name="food-category" class="custom-select form-control" data-parsley-trigger required>
                                            <option selected disabled>Select a food category</option>

                                            <?php foreach($food_categories as $food_category) { ?>
                                                <option value="<?php echo $food_category['id'] ?>"><?php echo ucfirst($food_category['title']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <select id="food-subcategories" name="food-subcategory" class="custom-select form-control" data-parsley-trigger disabled required>
                                            <option selected disabled>Select a food subcategory</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="other-option" class="form-group">
                                <label for="other-subcategory">Can't find the food you're trying to list?</label>
                                <input id="other-subcategory" type="text" name="other-subcategory" class="form-control" placeholder="Add your food type">
                            </div>

                            <div class="form-group">
                                <label for="price">Listing price</label>
                                <div class="input-group">
                                    <div class="input-group-addon">$</div>
                                     <input id="price" type="text" name="price" class="form-control" placeholder="Enter the full price for your food" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-min="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your price should include both dollars and cents (ex: $2.50)" required> 
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="weight">Average weight per item</label>
                                <div class="input-group">
                                    <input id="weight" type="number" name="weight" class="form-control" placeholder="Enter how much an item typically weighs" min="1" max="10000" data-parsley-type="number" data-parsley-min="1" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="Please round this value to a whole number" required> 
                                    <select name="units" class="input-group-addon" data-parsley-excluded="true">
                                        <?php foreach ([
                                            'oz',
                                            'lbs',
                                            'fl oz',
                                            'liters',
                                            'gallons'
                                        ] as $unit) { ?>
                                            <option val="<?php echo $unit; ?>"><?php echo $unit; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                             <div class="row"> 
                                 <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input id="quantity" type="number" name="quantity" class="form-control" placeholder="Enter how many you have in stock" min="0" max="10000" data-parsley-type="number" data-parsley-min="0" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="This value should be a whole number" required> 
                                    </div>
                                 </div> 

                                 <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label>Availability</label>
                                        
                                        <div class="radio-box">
                                            <label class="custom-control custom-radio">
                                                <input id="available" name="is-available" value="1" type="radio" class="custom-control-input" required>
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Available</span>
                                            </label>

                                            <label class="custom-control custom-radio">
                                                <input id="unavailable" name="is-available" value="0" type="radio" class="custom-control-input" required>
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Unavailable</span>
                                            </label>
                                        </div>
                                    </div>
                                 </div> 
                             </div> 
                        </div>

                        <div class="col-md-5">
                            <div class="image-box">
                                <?php img('placeholders/default-thumbnail', 'jpg', 'local', 'file'); ?>
                                <input type="file" name="listing-image" accept="image/png/jpg">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Listing description</label>
                        <textarea type="text" name="description" class="form-control" rows="4" placeholder="Write a description of your homegrown food"></textarea>
                    </div>

                    <!-- <div class="form-group">
                        <label>Upload a listing image</label>
                        <div id="listing-image">
                            <label class="custom-file">
                                <input id="listing-file" type="file" name="listing-image" class="custom-file-input" accept="image/png/jpg" data-toggle="custom-file" data-target="#file-upload-value">
                                <span id="file-upload-value" class="custom-file-control" data-content="Upload listing image&hellip;"></span>
                            </label>
                        </div>
                    </div> -->

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        Create listing
                    </button>
                </form>
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->

    <script>
        var food_subcategories = <?php echo json_encode($food_subcategories); ?>;
    </script>