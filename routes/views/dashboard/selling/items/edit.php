<!-- cont main -->
    <div class="container animated fadeIn">
        
        <?php if ($Item->grower_operation_id == $User->GrowerOperation->id): ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Edit item: <strong><?= $Item->title ?></strong>
                    </div>

                    <div class="page-description text-muted small">
                        Update your item details. Only items marked as available can be purchased by buyers, and remember you can always deny any order that comes in.
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="controls">
                        <button type="submit" form="edit-item" class="btn btn-success">
                            <i class="pre fa fa-floppy-o"></i>
                            Save changes
                            <i class="post fa fa-gear loading-icon save"></i>
                        </button>
        
                        <a class="remove-item action btn btn-danger">
                            <i class="pre fa fa-trash-o"></i>
                            Delete item
                            <i class="post fa fa-gear loading-icon remove"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <hr>

            <div class="alerts"></div>

            <form id="edit-item">
                <input type="hidden" name="id" value="<?= $Item->id ?>">

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

                                        <?php foreach($categories as $category) {
                                            $selected   = ($category['id'] == $Item->item_category_id) ? 'selected' : '';
                                            $title      = ucfirst($category['title']);
                                            
                                            echo "<option value=\"{$category['id']}\" {$selected}>{$title}</option>";
                                        } ?>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <select id="subcategories" name="subcategory" class="custom-select form-control" data-parsley-trigger="change" required>
                                        <option selected disabled>
                                            Select item subcategory
                                        </option>

                                        <?php foreach($subcategories as $subcategory) {
                                            if ($subcategory['item_category_id'] == $Item->item_category_id) {
                                                $selected   = ($subcategory['id'] == $Item->item_subcategory_id) ? 'selected' : '';
                                                $title      = ucfirst($subcategory['title']);
                                                
                                                echo "<option value=\"{$subcategory['id']}\" {$selected}>{$title}</option>";
                                            }
                                        } ?>

                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <select id="varieties" name="variety" class="custom-select form-control" data-parsley-trigger="change">
                                        <option selected disabled>
                                            Select item variety
                                        </option>
                                        
                                        <option value="0" <?php if (!$Item->item_variety_id) echo 'selected' ?>>
                                            None
                                        </option>

                                        <?php foreach($varieties as $variety) {
                                            if ($variety['item_subcategory_id'] != $Item->item_subcategory_id) continue;
                                            
                                            $selected   = ($variety['id'] == $Item->item_variety_id) ? 'selected' : '';
                                            $title      = ucfirst($variety['title']);
                                            
                                            echo "<option value=\"{$variety['id']}\" {$selected}>{$title}</option>";
                                        } ?>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Name
                                    </label>

                                    <input type="text" name="name" class="form-control" placeholder="<?= (!empty($Item->subcategory_title) ? ucfirst((!empty($Item->variety_title) ? $Item->variety_title . ' ' : '') . $Item->subcategory_title) : 'Item name') ?>" value="<?= $Item->name ?>" data-parsley-maxlength="40" data-parsley-trigger="change">

                                    <small class="form-text text-muted">
                                        Item name defaults to the item type
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        Availability    
                                    </label>

                                    <div class="toggle-box">
                                        <input id="is-available" type="checkbox" name="is-available" <?php if ($Item->is_available) echo 'checked' ?>>
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
                                        <input id="is-wholesale" type="checkbox" name="is-wholesale" <?php if ($Item->is_wholesale) echo 'checked' ?>>
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
                                    <label for="price">Price</label>
                                    
                                    <div class="input-group w-addon">
                                        <div class="input-group-addon">
                                            $
                                        </div>
                                        
                                        <input id="price" type="text" name="price" class="form-control" value="<?= number_format(($Item->price / 100), 2) ?>" placeholder="Item price" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-max="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" data-parsley-pattern-message="Your price should include both dollars and cents (ex: $2.50)" required> 
                                    </div>
                                    
                                    <small class="form-text text-muted">
                                        Enter the full price of this item (including cents)
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Quantity
                                    </label>

                                    <input id="quantity" type="number" name="quantity" class="form-control" value="<?= $Item->quantity ?>" placeholder="Item quantity" min="0" max="10000" data-parsley-type="number" data-parsley-min="0" data-parsley-max="999" data-parsley-pattern="^[0-9]+$" data-parsley-type-message="This value should be a whole number" required> 

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
                                            
                                            <option value="<?= $package_type['id'] ?>" <?php if ($Item->package_type_id == $package_type['id']) echo 'selected' ?>>
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
                                        Measurement
                                    </label>
                                    
                                    <div class="input-group w-addon">
                                        <input type="text" name="measurement" class="form-control" value="<?php if (!empty($Item->measurement)) echo $Item->measurement ?>" placeholder="Item measurement" data-parsley-pattern="^([0-9]*[.x\s])*[0-9]+$" data-parsley-maxlength="10">
                                        
                                        <select name="metric" class="input-group-addon" data-parsley-excluded="true">
                                            <option disabled <?php if (empty($Item->metric_id)) echo 'selected' ?>>
                                                Metric
                                            </option>

                                            <?php foreach ($metrics as $metric) {
                                                echo "<option value=\"{$metric['id']}\"" . ($metric['id'] == $Item->metric_id ? 'selected' : '') . ">{$metric['title']}</option>";
                                            } ?>
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

                            <textarea type="text" name="description" class="form-control" rows="3" placeholder="Tell customers what makes this item special"><?= $Item->description ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>
                                Photo
                            </label>

                            <a href="" class="remove-image action float-right <?php if (empty($Item->Image->filename)) echo 'hidden' ?>" data-item-id="<?= $Item->id ?>" data-toggle="tooltip" data-placement="left" title="Remove item photo">
                                <i class="fa fa-trash"></i>
                            </a>

                            <div class="image-box slide-over <?php if (!empty($Item->Image->filename)) echo 'existing-image'; ?>">
                                <div class="image-container">
                                    
                                    <?php if (!empty($Item->Image->filename)) {
                                        img(ENV . '/item-images/' . $Item->Image->filename, $Item->Image->ext . '?' . time(), [
                                            'server'    => 'S3',
                                            'class'     => 'img-fluid file'
                                        ]);
                                    } else {
                                        img('placeholders/default-thumbnail', 'jpg', [
                                            'server'    => 'local', 
                                            'class'     => 'file'
                                        ]);
                                    } ?>

                                    <input type="file" name="image" accept="image/png/jpg">

                                    <div class="overlay-slide">
                                        <i class="fa fa-camera"></i>
                                        Upload new item photo
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($similar_items)): ?>

                                <div class="form-group margin-top-1em">
                                    <div class="row">
                                    
                                        <?php foreach($similar_items as $item): ?>
                                        
                                            <?php $SimilarItem = new $Item([
                                                'DB' => $DB,
                                                'id' => $item['id']
                                            ]); ?>

                                            <?php if (!empty($SimilarItem->Image->filename) && ($Item->Image->image_id != $SimilarItem->Image->image_id)): ?>
                                                
                                                <div class="col-md-4">
                                                    <div class="image-box suggested-photo" data-image-id="<?= $SimilarItem->Image->image_id ?>" data-toggle="tooltip" data-title="Use this photo" data-placement="bottom">

                                                        <?php img(ENV . '/item-images/' . $SimilarItem->Image->filename, $SimilarItem->Image->ext, [
                                                            'server'    => 'S3',
                                                            'class'     => 'rounded img-fluid'
                                                        ]); ?>

                                                    </div>
                                                </div>

                                            <?php endif; ?>

                                        <?php endforeach; ?>

                                    </div>
                                </div>

                            <?php endif; ?>

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

        <?php else: ?>

            <p class="text-muted">
                Oops, looks like this item doesn't belong to you.
            </p>

        <?php endif; ?>

    </div>
</main>

<script>
    var subcategories  = <?= json_encode($subcategories); ?>;
    var varieties      = <?= json_encode($varieties); ?>;
</script>