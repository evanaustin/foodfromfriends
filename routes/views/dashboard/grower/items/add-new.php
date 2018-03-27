<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    List a new item
                </div>

                <div class="page-description text-muted small">
                    Select an item type, enter the listing details, and upload an image. Only items marked as available can be purchased by buyers, and you can always choose to deny any order that comes in at no penalty.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="add-listing" class="btn btn-success">
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
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12"> 
                                <label for="food-categories">
                                    What kind of item do you have?
                                </label>
                            </div>

                            <div class="col-md-4">
                                <select id="item-categories" name="item-category" class="custom-select form-control" data-parsley-trigger="change" required>
                                    <option selected disabled>Select category</option>

                                    <?php
                                        
                                    foreach($item_categories as $category) {
                                        echo "<option value=\"{$category['id']}\"" . (($category['id'] == $_GET['category']) ? ' selected' : '') . ">" . ucfirst($category['title']) . "</option>";
                                    }
                                    
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-4"> 
                                <select id="item-subcategories" name="item-subcategory" class="custom-select form-control" data-parsley-trigger="change" required <?php if (!isset($_GET['subcategory'])) { echo 'disabled'; } ?>>
                                    <option selected disabled>Select subcategory</option>

                                    <?php
                                        
                                    if (isset($_GET['subcategory'])) {
                                        foreach($item_subcategories as $subcategory) {
                                            echo "<option value=\"{$subcategory['id']}\"" . (($subcategory['id'] == $_GET['subcategory']) ? ' selected' : '') . ">" . ucfirst($subcategory['title']) . "</option>";
                                        }
                                    }
                                    
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-4"> 
                                <select id="item-varieties" name="item-variety" class="custom-select form-control hidden" data-parsley-trigger="change" disabled>
                                    <option selected disabled>Select variety</option>
                                </select>
                            </div>
                        </div>

                        <small class="form-text text-muted">
                            Can't find the item you want to add? Suggest a new item type <a href="#" class="brand" data-toggle="modal" data-target="#suggest-item-modal">here</a>, so we can review and add it for you.
                        </small>
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
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="available" name="is-available" class="custom-control-input" value="1" data-parsley-trigger="change" required>
                                        <label class="custom-control-label" for="available">Available</label>
                                    </div>
                                    
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="unavailable" name="is-available" class="custom-control-input" value="0" data-parsley-trigger="change" checked required>
                                        <label class="custom-control-label" for="unavailable">Unavailable</label>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="form-group">
                        <label>
                            Item definition
                        </label>

                        <textarea type="text" name="definition" class="form-control" rows="2" placeholder="Describe one item and how it is sold so that buyers better understand what you're offering" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="weight">
                            Average weight per item (optional)
                        </label>

                        <div class="input-group w-addon">
                            <input id="weight" type="number" name="weight" class="form-control" placeholder="Enter how much an item typically weighs" min="1" max="10000" data-parsley-type="number" data-parsley-min="1" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="Please round this value to a whole number" data-parsley-trigger="change"> 
                            
                            <select name="units" class="input-group-addon">
                                <option disabled selected>Units</option>
                                <?php foreach ([
                                    'g',
                                    'oz',
                                    'lbs',
                                    'kg',
                                    'fl oz',
                                    'liters',
                                    'gallons'
                                ] as $unit) { ?>
                                    <option value="<?php echo $unit; ?>"><?php echo $unit; ?></option>
                                <?php } ?>
                            </select>
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
                <label>
                    Listing description (optional)
                </label>

                <textarea type="text" name="description" class="form-control" rows="4" placeholder="Tell customers what makes your food special"></textarea>
            </div>
        </form>
    </div>
</main>

<script>
    var item_subcategories  = <?php echo json_encode($item_subcategories); ?>;
    var item_varieties      = <?php echo json_encode($item_varieties); ?>;
</script>