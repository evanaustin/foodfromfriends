<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Add a new item
                </div>

                <div class="page-description text-muted small">
                    Select an item type, enter some details, and upload an image. Only items marked as available can be purchased by buyers, and you can always deny any order that comes in.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="add-listing" class="btn btn-success">
                        <i class="pre fa fa-upload"></i>
                        Create item
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
                                    Item type
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

                    <div class="form-group">
                        <label for="other-subcategory">
                            Item name (optional)
                        </label>

                        <input id="item-name" type="text" name="item-name" class="form-control" placeholder="Item name" data-parsley-maxlength="40" data-parsley-trigger="change">
                        
                        <small class="form-text text-muted">
                            Leave this field blank and the item name will default to the selected item type
                        </small>
                    </div>

                    <div class="row"> 
                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label for="quantity">
                                    Quantity
                                </label>
                                
                                <input id="quantity" type="number" name="quantity" class="form-control" placeholder="Item quantity" min="0" max="10000" data-parsley-type="number" data-parsley-min="0" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="This value should be a whole number" data-parsley-trigger="change" required>
                            </div>
                        </div> 

                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label>
                                    Item availability
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">
                                    Retail item price
                                </label>

                                <div class="input-group w-addon">
                                    <div class="input-group-addon">$</div>
                                    <input id="price" type="text" name="price" class="form-control" placeholder="Retail item price" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-max="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your price should include both dollars and cents (ex: $2.50)" data-parsley-trigger="change" required> 
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="weight">
                                    Retail item weight (optional)
                                </label>

                                <div class="input-group w-addon">
                                    <input id="weight" type="number" name="weight" class="form-control" placeholder="Retail item weight" min="1" max="10000" data-parsley-type="number" data-parsley-min="1" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="Please round this value to a whole number" data-parsley-trigger="change"> 
                                    
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
                                        ] as $unit) {
                                            echo "<option value=\"{$unit}\">{$unit}</option>";
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
                                    <input type="text" name="wholesale-price" class="form-control" placeholder="Wholesale item price" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-max="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your price should include both dollars and cents (ex: $2.50)" data-parsley-trigger="change"> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Wholesale item weight (optional)
                                </label>

                                <div class="input-group w-addon">
                                    <input type="number" name="wholesale-weight" class="form-control" placeholder="Wholesale item weight" min="1" max="10000" data-parsley-type="number" data-parsley-min="1" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="Please round this value to a whole number" data-parsley-trigger="change"> 
                                    
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
                                        ] as $unit) {
                                            echo "<option value=\"{$unit}\">{$unit}</option>";
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

                        <textarea type="text" name="packaging" class="form-control" rows="2" placeholder="Describe how this item is packaged or prepared when sold"></textarea>
                    </div>

                    <div class="form-group">
                        <label>
                            Item description (optional)
                        </label>

                        <textarea type="text" name="description" class="form-control" rows="2" placeholder="Tell customers what makes your food special"></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <label>
                        Item photo
                    </label>
                        
                    <a href="" class="remove-image float-right hidden" data-toggle="tooltip" data-placement="left" title="Remove listing photo"><i class="fa fa-trash"></i></a>

                    <div class="image-box slide-over">
                        <div class="image-container">
                            <?php

                            img('placeholders/default-thumbnail', 'jpg', [
                                'server'    => 'local', 
                                'class'     => 'file'
                            ]);
                            
                            ?>
                            
                            <input type="file" name="listing-image" accept="image/png/jpg">
                            
                            <div class="overlay-slide">
                                <i class="fa fa-camera"></i>
                                Add new listing photo
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    var item_subcategories  = <?= json_encode($item_subcategories); ?>;
    var item_varieties      = <?= json_encode($item_varieties); ?>;
</script>