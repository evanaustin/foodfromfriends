<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Add a new item listing
                </div>

                <div class="page-description text-muted small">
                    Select an item type, enter the listing details, and upload an image. Only items marked as available can be purchased by buyers.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="add-listing" class="btn btn-primary">
                        <i class="pre fa fa-upload"></i>
                        Create listing
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <hr>

        <div class="alerts"></div>

        <form id="add-listing">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12"> 
                            <label for="food-categories">
                                What kind of item do you have?
                            </label>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <select id="item-categories" name="item-category" class="custom-select form-control" data-parsley-trigger="change" required>
                                    <option selected disabled>Select category</option>

                                    <?php
                                        
                                    foreach($item_categories as $category) {
                                        echo "<option value=\"{$category['id']}\">" . ucfirst($category['title']) . "</option>";
                                    }
                                    
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4"> 
                            <div class="form-group">
                                <select id="item-subcategories" name="item-subcategory" class="custom-select form-control" data-parsley-trigger="change" disabled required>
                                    <option selected disabled>Select subcategory</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4"> 
                            <div class="form-group">
                                <select id="item-varieties" name="item-variety" class="custom-select form-control hidden" data-parsley-trigger="change" disabled>
                                    <option selected disabled>Select variety</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- <div id="other-option" class="form-group hidden">
                        <label for="other-subcategory">
                            Can't find the food you're trying to list?
                        </label>

                        <input id="other-subcategory" type="text" name="other-subcategory" class="form-control" placeholder="Add your food type">
                    </div> -->

                    <div class="form-group">
                        <label for="price">
                            Listing price
                        </label>

                        <div class="input-group w-addon">
                            <div class="input-group-addon">$</div>
                            <input id="price" type="text" name="price" class="form-control" placeholder="Enter the full price for your food" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-max="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your price should include both dollars and cents (ex: $2.50)" data-parsley-trigger="change" required> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="weight">
                            Average weight per item
                        </label>

                        <div class="input-group w-addon">
                            <input id="weight" type="number" name="weight" class="form-control" placeholder="Enter how much an item typically weighs" min="1" max="10000" data-parsley-type="number" data-parsley-min="1" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="Please round this value to a whole number" data-parsley-trigger="change" required> 
                            
                            <select name="units" class="input-group-addon" data-parsley-excluded="true">
                                <?php foreach ([
                                    'g',
                                    'oz',
                                    'lbs',
                                    'kg',
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
                                <label for="quantity">
                                    Quantity
                                </label>
                                
                                <input id="quantity" type="number" name="quantity" class="form-control" placeholder="Enter how many you have in stock" min="0" max="10000" data-parsley-type="number" data-parsley-min="0" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="This value should be a whole number" data-parsley-trigger="change" required> 
                            </div>
                            </div> 

                            <div class="col-md-6"> 
                            <div class="form-group">
                                <label>
                                    Availability
                                </label>
                                
                                <div class="radio-box">
                                    <label class="custom-control custom-radio">
                                        <input id="available" name="is-available" value="1" type="radio" class="custom-control-input" data-parsley-trigger="change" required>
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Available</span>
                                    </label>

                                    <label class="custom-control custom-radio">
                                        <input id="unavailable" name="is-available" value="0" type="radio" class="custom-control-input" data-parsley-trigger="change" required>
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Unavailable</span>
                                    </label>
                                </div>
                            </div>
                            </div> 
                        </div> 
                </div>

                <div class="col-md-4">
                    <label>
                        Listing photo
                    </label>
                        
                    <a href="" class="remove-image float-right hidden" data-toggle="tooltip" data-placement="left" title="Remove listing photo"><i class="fa fa-trash"></i></a>

                    <div class="image-box slide-over">
                        <div class="image-container">
                            <?php img('placeholders/default-thumbnail', 'jpg', 'local', 'file'); ?>
                            
                            <input type="file" name="listing-image" accept="image/png/jpg">
                            
                            <div class="overlay-slide">
                                <i class="fa fa-camera"></i>
                                Add new listing photo
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">
                    Listing description
                </label>

                <textarea type="text" name="description" class="form-control" rows="4" placeholder="Write a description of your homegrown food"></textarea>
            </div>
        </form>
    </div>
</main>

<script>
    var item_subcategories  = <?php echo json_encode($item_subcategories); ?>;
    var item_varieties      = <?php echo json_encode($item_varieties); ?>;
</script>