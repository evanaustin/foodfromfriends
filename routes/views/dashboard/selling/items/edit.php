<!-- cont main -->
    <div class="container animated fadeIn">
        <?php

        if ($FoodListing->grower_operation_id == $User->GrowerOperation->id) {

            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Edit item: <strong><?= $FoodListing->title; ?></strong>
                    </div>

                    <div class="page-description text-muted small">
                        Update your item details and/or upload a new image. Only items marked as available can be purchased by buyers, and you can always deny any order that comes in.
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="controls">
                        <button type="submit" form="edit-listing" class="btn btn-success">
                            <i class="pre fa fa-floppy-o"></i>
                            Save changes
                            <i class="post fa fa-gear loading-icon save"></i>
                        </button>
        
                        <a class="remove-listing btn btn-danger">
                            <i class="pre fa fa-trash-o"></i>
                            Delete item
                            <i class="post fa fa-gear loading-icon remove"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <hr>

            <div class="alerts"></div>

            <form id="edit-listing">
                <input type="hidden" name="id" value="<?= $FoodListing->id; ?>">

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
                                        <option selected disabled>Select an item category</option>

                                        <?php
                                        
                                        foreach($item_categories as $category) {
                                            $selected   = ($category['id'] == $FoodListing->food_category_id) ? 'selected' : '';
                                            $title      = ucfirst($category['title']);
                                            
                                            echo "<option value=\"{$category['id']}\" {$selected}>{$title}</option>";
                                        }
                                        
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <select id="item-subcategories" name="item-subcategory" class="custom-select form-control" data-parsley-trigger="change" required>
                                        <option selected disabled>Select an item subcategory</option>

                                        <?php
                                        
                                        foreach($item_subcategories as $subcategory) {
                                            if ($subcategory['food_category_id'] == $FoodListing->food_category_id) {
                                                $selected   = ($subcategory['id'] == $FoodListing->food_subcategory_id) ? 'selected' : '';
                                                $title      = ucfirst($subcategory['title']);
                                                
                                                echo "<option value=\"{$subcategory['id']}\" {$selected}>{$title}</option>";
                                            }
                                        }
                                        
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <select id="item-varieties" name="item-variety" class="custom-select form-control" data-parsley-trigger="change">
                                        <option selected disabled>Select an item variety</option>
                                        <option value="0">None</option>

                                        <?php
                                        
                                        foreach($item_varieties as $variety) {
                                            if ($variety['food_subcategory_id'] != $FoodListing->food_subcategory_id) continue;
                                            
                                            $selected   = ($variety['id'] == $FoodListing->item_variety_id) ? 'selected' : '';
                                            $title      = ucfirst($variety['title']);
                                            
                                            echo "<option value=\"{$variety['id']}\" {$selected}>{$title}</option>";
                                        }
                                        
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="item-name">
                                Item name
                            </label>

                            <input id="item-name" type="text" name="item-name" class="form-control" placeholder="Give your item a name" value="<?= $FoodListing->title; ?>" data-parsley-maxlength="40" data-parsley-trigger="change">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input id="quantity" type="number" name="quantity" class="form-control" value="<?= $FoodListing->quantity; ?>" placeholder="Enter how many you have in stock" min="0" max="10000" data-parsley-type="number" data-parsley-min="0" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="This value should be a whole number" required> 
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Availability</label>
                                    
                                    <div class="radio-box">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="available" name="is-available" class="custom-control-input" value="1" data-parsley-trigger="change" <?php if ($FoodListing->is_available) echo 'checked'; ?> required>
                                            <label class="custom-control-label" for="available">Available</label>
                                        </div>
                                        
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="unavailable" name="is-available" class="custom-control-input" value="0" data-parsley-trigger="change" <?php if (!$FoodListing->is_available) echo 'checked'; ?> required>
                                            <label class="custom-control-label" for="unavailable">Unavailable</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Item price</label>
                                    
                                    <div class="input-group w-addon">
                                        <div class="input-group-addon">$</div>
                                        <input id="price" type="text" name="price" class="form-control" value="<?= number_format(($FoodListing->price / 100), 2); ?>" placeholder="Enter the full price for your food" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-min="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your price should include both dollars and cents (ex: $2.50)" required> 
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="weight">Average weight per item (optional)</label>
                                    <div class="input-group w-addon">
                                        <input id="weight" type="number" name="weight" class="form-control" value="<?= (!empty($FoodListing->weight) ? $FoodListing->weight : ''); ?>" placeholder="Enter how much an item typically weighs" min="1" max="10000" data-parsley-type="number" data-parsley-min="1" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="Please round this value to a whole number"> 
                                        
                                        <select name="units" class="input-group-addon" data-parsley-excluded="true">
                                            <option disabled <?php if (empty($FoodListing->units)) echo 'selected' ?>>Units</option>

                                            <?php foreach ([
                                                'g',
                                                'oz',
                                                'lb',
                                                'kg',
                                                'fl oz',
                                                'liter',
                                                'gallon'
                                            ] as $unit) {
                                                echo "<option value=\"{$unit}\"" . ($unit == $FoodListing->units ? 'selected' : '') . ">{$unit}</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Wholesale item price (optional) <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Only your approved wholesale customers will see this item's wholesale price" data-placement="right"></i>
                                    </label>

                                    <div class="input-group w-addon">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" name="wholesale-price" class="form-control" value="<?= number_format(($FoodListing->wholesale_price / 100), 2); ?>" placeholder="Wholesale item price" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-max="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your price should include both dollars and cents (ex: $2.50)" data-parsley-trigger="change" required> 
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Wholesale item weight (optional)
                                    </label>

                                    <div class="input-group w-addon">
                                        <input type="number" name="wholesale-weight" class="form-control" value="<?= (!empty($FoodListing->wholesale_weight) ? $FoodListing->weight : ''); ?>" placeholder="Wholesale item weight" min="1" max="10000" data-parsley-type="number" data-parsley-min="1" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="Please round this value to a whole number" data-parsley-trigger="change"> 
                                        
                                        <select name="wholesale-units" class="input-group-addon">
                                            <option disabled selected>Units</option>
                                            
                                            <?php foreach ([
                                                'g',
                                                'oz',
                                                'lbs',
                                                'kg',
                                                'fl oz',
                                                'liters',
                                                'gallons'
                                            ] as $unit) {
                                                echo "<option value=\"{$unit}\"" . ($unit == $FoodListing->wholesale_units ? 'selected' : '') . ">{$unit}</option>";
                                            } ?>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                Item packaging (optional)
                            </label>

                            <textarea type="text" name="packaging" class="form-control" rows="2" placeholder="Describe how this item is packaged or prepared when sold so that buyers better understand what you're offering"><?= $FoodListing->unit_definition; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Item description (optional)
                            </label>

                            <textarea type="text" name="description" class="form-control" rows="4" placeholder="Tell customers what makes your food special"><?= $FoodListing->description; ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Listing photo</label>

                            <a href="" class="remove-image float-right <?php if (empty($FoodListing->filename)) echo 'hidden' ?>" data-listing-id="<?= $FoodListing->id; ?>" data-toggle="tooltip" data-placement="left" title="Remove item photo"><i class="fa fa-trash"></i></a>

                            <div class="image-box slide-over <?php if (!empty($FoodListing->filename)) echo 'existing-image'; ?>">
                                <div class="image-container">
                                    <?php 
                                    
                                    if (!empty($FoodListing->filename)) {
                                        img(ENV . '/items/' . $FoodListing->filename, $FoodListing->ext . '?' . time(), [
                                            'server'    => 'S3',
                                            'class'     => 'img-fluid file'
                                        ]);
                                    } else {
                                        img('placeholders/default-thumbnail', 'jpg', [
                                            'server'    => 'local', 
                                            'class'     => 'file'
                                        ]);
                                    }

                                    ?>

                                    <input type="file" name="listing-image" accept="image/png/jpg">

                                    <div class="overlay-slide">
                                        <i class="fa fa-camera"></i>
                                        Update item photo
                                    </div>
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
                Oops, looks like this item doesn't belong to you.
            </p>

            <?php
        }

        ?>
    </div>
</main>

<script>
    var item_subcategories  = <?= json_encode($item_subcategories); ?>;
    var item_varieties      = <?= json_encode($item_varieties); ?>;
</script>