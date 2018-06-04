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
                    <button type="submit" form="add-item" class="btn btn-success">
                        <i class="pre fa fa-upload"></i>
                        Create item
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <hr>

        <div class="alerts"></div>

        <form id="add-item">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12"> 
                            <label>
                                Type
                                <a href="#" class="" data-toggle="modal" data-target="#suggest-item-modal">
                                    <i class="fa fa-plus-circle" data-toggle="tooltip" data-title="Click to suggest a new type"></i>
                                </a>
                            </label>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <select id="categories" name="category" class="custom-select form-control" data-parsley-trigger="change" required>
                                    <option selected disabled>
                                        Select item category
                                    </option>

                                    <?php foreach($categories as $category): ?>

                                        <option value="<?= $category['id'] ?>" <?php if ($category['id'] == $category_id) echo 'selected' ?>>
                                    
                                            <?= ucfirst($category['title']) ?>
                                    
                                        </option>
                                    
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <select id="subcategories" name="subcategory" class="custom-select form-control" data-parsley-trigger="change" required <?php if (!isset($_GET['subcategory'])) { echo 'disabled'; } ?>>
                                    <option selected disabled>
                                        Select item subcategory
                                    </option>

                                    <?php if (isset($subcategory_id)): ?>
                                        
                                        <?php foreach($subcategories as $subcategory): ?>
                                            
                                            <option value="<?= $subcategory['id'] ?>" <?php if ($subcategory['id'] == $subcategory_id) echo 'selected' ?>>
                                                <?= ucfirst($subcategory['title']) ?>
                                            </option>
                                        
                                        <?php endforeach; ?>

                                    <?php endif; ?>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <select id="varieties" name="variety" class="custom-select form-control <?php if (!isset($lim_varieties)) echo 'hidden' ?>" data-parsley-trigger="change" <?php if (!isset($lim_varieties)) echo 'disabled' ?>>
                                    <option selected disabled>
                                        Select item variety
                                    </option>
                                    
                                    <option value="0">
                                        None
                                    </option>

                                    <?php if (isset($lim_varieties)): ?>
                                        
                                        <?php foreach($lim_varieties as $variety) {
                                            if ($variety['item_subcategory_id'] != $subcategory_id) continue;
                                            echo "<option value=\"{$variety['id']}\">" . ucfirst($variety['title']) . "</option>";
                                        } ?>

                                    <?php endif; ?>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Name (optional)
                                </label>

                                <input id="name" type="text" name="name" class="form-control" placeholder="<?= (!empty($subcategory_id) ? ucfirst($subcategories[$subcategory_id - 1]['title']) : 'Item name') ?>" data-parsley-maxlength="40" data-parsley-trigger="change">
                                
                                <small class="form-text text-muted">
                                    Customize this item name or let it default to the type
                                </small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>
                                    Availability    
                                </label>

                                <div class="toggle-box">
                                    <input id="is-available" type="checkbox" name="is-available">
                                    <label for="is-available">Toggle</label>
                                </div>

                                <small class="form-text text-muted">
                                    Is this item available?
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>
                                    Wholesale
                                </label>
                                
                                <div class="toggle-box">
                                    <input id="is-wholesale" type="checkbox" name="is-wholesale">
                                    <label for="is-wholesale">Toggle</label>
                                </div>
                                
                                <small class="form-text text-muted">
                                    Is this a wholesale item?
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Price
                                </label>

                                <div class="input-group w-addon">
                                    <div class="input-group-addon">
                                        $
                                    </div>
                                    
                                    <input id="price" type="text" name="price" class="form-control" placeholder="Item price" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-max="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your price should include both dollars and cents (ex: $2.50)" data-parsley-trigger="change" required> 
                                </div>

                                <small class="form-text text-muted">
                                    Enter the full price of this item (including cents)
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label for="quantity">
                                    Quantity
                                </label>
                                
                                <input id="quantity" type="number" name="quantity" class="form-control" placeholder="Item quantity" min="0" max="10000" data-parsley-type="number" data-parsley-min="0" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="This value should be a whole number" data-parsley-trigger="change" required>
                                
                                <small class="form-text text-muted">
                                    Enter the current stock of this item
                                </small>
                            </div>
                        </div> 
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Package type
                                </label>

                                <select name="package-type" class="custom-select form-control" data-parsley-trigger="change" required>
                                    <option selected disabled>Select item package type</option>
                                        
                                    <?php foreach($package_types as $package_type): ?>
                                        
                                        <option value="<?= $package_type['id'] ?>">
                                            <?= ucfirst($package_type['title']) ?>
                                        </option>
                                    
                                    <?php endforeach; ?>

                                </select>

                                <small class="form-text text-muted">
                                    Select how this item is sold
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Measurement (optional)
                                </label>

                                <div class="input-group w-addon">
                                    <input type="text" name="measurement" class="form-control" placeholder="Item measurement" data-parsley-pattern="^([0-9]*[.x\s])*[0-9]+$" data-parsley-maxlength="10">
                                    
                                    <select name="metric" class="input-group-addon">
                                        <option disabled selected>
                                            Metric
                                        </option>
                                        
                                        <?php foreach ($metrics as $metric): ?>

                                            <option value="<?= $metric['id'] ?>">
                                                <?= $metric['title'] ?>
                                            </option>
                                        
                                        <?php endforeach; ?>

                                    </select>
                                </div>

                                <small class="form-text text-muted">
                                    Enter the weight or volume of this item
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            Description (optional)
                        </label>

                        <textarea type="text" name="description" class="form-control" rows="3" placeholder="Tell customers what makes this item special"></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <label>
                        Photo
                    </label>
                        
                    <a href="" class="remove-image float-right hidden" data-toggle="tooltip" data-placement="left" title="Remove item photo"><i class="fa fa-trash"></i></a>

                    <div class="image-box slide-over">
                        <div class="image-container">
                            
                            <?php img('placeholders/default-thumbnail', 'jpg', [
                                'server'    => 'local', 
                                'class'     => 'file'
                            ]); ?>
                            
                            <input type="file" name="image" accept="image/png/jpg">
                            
                            <div class="overlay-slide">
                                <i class="fa fa-camera"></i>
                                Add new item photo
                            </div>
                        </div>
                    </div>

                    <div id="similar-items">
                        <div class="form-group">
                            <div class="row">

                                <?php if (!empty($similar_items)): ?>
                                
                                    <?php foreach($similar_items as $item): ?>
                                    
                                        <?php $SimilarItem = new $Item([
                                            'DB' => $DB,
                                            'id' => $item['id']
                                        ]); ?>

                                        <?php if (!empty($SimilarItem->Image->filename) && !in_array($SimilarItem->Image->image_id, $item_images)): ?>
                                            
                                            <div class="col-md-4">
                                                <div class="image-box suggested-photo" data-image-id="<?= $SimilarItem->Image->image_id ?>" data-toggle="tooltip" data-title="Use this photo" data-placement="bottom">

                                                    <?php img(ENV . '/item-images/' . $SimilarItem->Image->filename, $SimilarItem->Image->ext, [
                                                        'server'    => 'S3',
                                                        'class'     => 'rounded img-fluid'
                                                    ]); ?>

                                                </div>
                                            </div>

                                            <?php array_push($item_images, $SimilarItem->Image->image_id) ?>

                                        <?php endif; ?>

                                    <?php endforeach; ?>

                                <?php endif; ?>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    var subcategories  = <?= json_encode($subcategories); ?>;
    var varieties      = <?= json_encode($varieties); ?>;
</script>