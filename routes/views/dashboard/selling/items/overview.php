<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="page-title">
                    Your items
                    &nbsp;
                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/overview-grid">
                        <i class="fa fa-th" data-toggle="tooltip" data-title="Switch to grid view" data-placement="right"></i>
                    </a>
                </div>

                <div class="page-description text-muted small">
                    These are all of your items. Update pricing and availability settings from this page, or click an 'Edit' icon to change an item's other settings.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="edit-items" class="btn btn-success">
                        <i class="pre fa fa-floppy-o"></i>
                        Save changes
                        <i class="post fa fa-gear loading-icon save"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <hr>

        <div class="alerts"></div>
        
        <?php if (!empty($hashed_items)): ?>
            
            <form id="edit-items">
                <ledger class="items">

                    <?php foreach ($hashed_items as $category_id => $subcategory): ?>

                        <div class="opened record">
                            <div class="tab" data-toggle="collapse" data-target="#category-<?= $category_id ?>" aria-controls="category-<?= $category_id ?>" aria-label="Toggle category" aria-expanded="true"></div>
                            
                            <fable>
                                <cell>
                                    <h5>
                                        <strong><?= ucfirst($hashed_categories[$category_id]) ?></strong>
                                    </h5>
                                </cell>
                            </fable>

                            <ledger class="collapse show" id="category-<?= $category_id ?>">
                                
                                <?php foreach ($subcategory as $subcategory_id => $items): ?>
                        
                                    <div class="opened record animated fadeIn">
                                        <div class="tab" data-toggle="collapse" data-target="#subcategory-<?= $subcategory_id ?>" aria-controls="subcategory-<?= $subcategory_id ?>" aria-label="Toggle subcategory"></div>
                                        
                                        <fable>
                                            <cell>
                                                <strong><?= ucfirst($hashed_subcategories[$subcategory_id]) ?></strong>
                                            </cell>
                                        </fable>
                                    
                                        <div class="collapse show" id="subcategory-<?= $subcategory_id ?>">
                                        
                                            <div class="subcategory-list">
                                                
                                                <?php $i = 0 ?>
                                                
                                                <?php foreach ($items as $item_id => $Item): ?>
                                                    
                                                    <div class="bubble animated fadeIn">
                                                        <input type="hidden" class="position" name="items[<?= $Item->id ?>][position]" value="<?= (!empty($Item->position)) ? $Item->position : $i ?>">

                                                        <fable>
                                                            <cell class="image align-center margin-right-50em">
                                                                <a href="<?= PUBLIC_ROOT . 'dashboard/selling/items/edit?id=' . $Item->id ?>" data-toggle="tooltip" data-title="Edit item" data-placement="bottom">
                                                                
                                                                    <?php if (!empty($Item->Image->filename)): ?>

                                                                        <?php img(ENV . '/item-images/' . $Item->Image->filename, $Item->Image->ext, [
                                                                            'server'    => 'S3',
                                                                            'class'     => 'img-fluid rounded animated fadeIn'
                                                                        ]); ?>

                                                                    <?php else: ?>

                                                                        <?php img('placeholders/default-thumbnail', 'jpg', [
                                                                            'server'    => 'local', 
                                                                            'class'     => 'img-fluid rounded animated fadeIn'
                                                                        ]); ?>

                                                                    <?php endif; ?>
                                                                    
                                                                </a>
                                                            </cell>

                                                            <?php if (!empty($Item->name)): ?>
                                                                
                                                                <cell class="justify-center align-center flexgrow-2 form-field">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="items[<?= $Item->id ?>][variety-id]" value="<?= $Item->item_variety_id ?>"/>
                                                                        <input type="text" name="items[<?= $Item->id ?>][name]" class="form-control" value="<?= $Item->title ?>"/>
                                                                        
                                                                        <label>
                                                                            Name
                                                                        </label>
                                                                    </div>
                                                                </cell>

                                                            <?php else: ?>
                                                                
                                                                <cell class="justify-center align-center form-field">
                                                                    <div class="form-group">
                                                                        <select name="items[<?= $Item->id ?>][variety-id]" class="custom-select form-control">
                                                                            <option value="0">None</option>

                                                                            <?php foreach ($raw_varieties as $variety) {
                                                                                if ($variety['item_subcategory_id'] != $subcategory_id) continue;
                                                                                echo "<option value=\"{$variety['id']}\"" . ($variety['id'] == $Item->item_variety_id ? 'selected' : '') . '>' . ucfirst($variety['title']) . '</option>';
                                                                            } ?>
                                                                        </select>

                                                                        <label>
                                                                            Variety
                                                                        </label>
                                                                    </div>
                                                                </cell>

                                                            <?php endif; ?>

                                                            <cell class="justify-center align-center form-field">
                                                                <div class="form-group">
                                                                    <div class="input-group w-addon">
                                                                        <input type="text" name="items[<?= $Item->id ?>][measurement]" class="form-control" value="<?php if (!empty($Item->measurement)) echo $Item->measurement ?>" placeholder="-" data-parsley-pattern="^([0-9]*[.x\s])*[0-9]+$" data-parsley-maxlength="10"> 
                                                                        
                                                                        <select name="items[<?= $Item->id ?>][metric]" class="input-group-addon" data-parsley-excluded="true">
                                                                            <option value="0" <?php if (empty($Item->metric_id)) echo 'selected' ?>>
                                                                                None
                                                                            </option>
                                                                            
                                                                            <?php foreach ($metrics as $metric) {
                                                                                echo "<option value=\"{$metric['id']}\"" . ($metric['id'] == $Item->metric_id ? 'selected' : '') . ">{$metric['title']}</option>";
                                                                            } ?>
                                                                        </select>
                                                                    </div>

                                                                    <label>
                                                                        Metrics
                                                                    </label>
                                                                </div>
                                                            </cell>

                                                            <cell class="justify-center flexgrow-0 basis-6em align-center form-field">
                                                                <div class="form-group">
                                                                    <select name="items[<?= $Item->id ?>][package-type]" class="custom-select form-control" data-parsley-trigger="change" required>
                                                                        <option selected disabled>Package</option>
                                                                            
                                                                        <?php foreach($package_types as $package_type): ?>
                                                                            
                                                                            <option value="<?= $package_type['id'] ?>" <?php if ($Item->package_type_id == $package_type['id']) echo 'selected' ?>>
                                                                                <?= ucfirst($package_type['title']) ?>
                                                                            </option>
                                                                        
                                                                        <?php endforeach; ?>

                                                                    </select>
                                                                
                                                                    <label>
                                                                        Package
                                                                    </label>
                                                                </div>
                                                            </cell>

                                                            <cell class="justify-center flexgrow-0 basis-6em align-center form-field">
                                                                <div class="form-group">
                                                                    <div class="price">
                                                                        <input type="text" name="items[<?= $Item->id ?>][price]" class="form-control" value="<?php if ($Item->price) echo number_format($Item->price / 100, 2) ?>" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-min="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" required>
                                                                    </div>
                                                                
                                                                    <label>
                                                                        Price
                                                                    </label>
                                                                </div>
                                                            </cell>
                                                            
                                                            <cell class="justify-center flexgrow-0 align-center form-field">
                                                                <div class="form-group">
                                                                    <input type="number" name="items[<?= $Item->id ?>][quantity]" class="form-control" value="<?= $Item->quantity ?>"/>
                                                                    
                                                                    <label>
                                                                        Quantity
                                                                    </label>
                                                                </div>
                                                            </cell>
                                                            
                                                            <cell class="justify-center flexgrow-0 align-center form-field">
                                                                <div class="form-group">
                                                                    <div class="toggle-box">
                                                                        <input id="is-available-<?= $Item->id ?>" type="checkbox" name="items[<?= $Item->id ?>][is-available]" <?php if ($Item->is_available) echo 'checked' ?>>
                                                                        <label for="is-available-<?= $Item->id ?>">Toggle</label>
                                                                    </div>
                                                                    
                                                                    <label>
                                                                        Available
                                                                    </label>
                                                                </div>
                                                            </cell>
                                                            
                                                            <cell class="justify-center flexgrow-0 basis-6em align-center form-field">
                                                                <div class="form-group">
                                                                    <div class="toggle-box">
                                                                        <input id="is-wholesale-<?= $Item->id ?>" type="checkbox" name="items[<?= $Item->id ?>][is-wholesale]" <?php if ($Item->is_wholesale) echo 'checked' ?>>
                                                                        <label for="is-wholesale-<?= $Item->id ?>">Toggle</label>
                                                                    </div>
                                                                    
                                                                    <label>
                                                                        Wholesale only
                                                                    </label>
                                                                </div>
                                                            </cell>

                                                            <!-- <cell class="actions flexgrow-0 margin-left-50em">
                                                                <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/edit?id=<?= $Item->id ?>" class="btn btn-muted" data-toggle="tooltip" data-placement="right" title="Edit item">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>

                                                                <a class="remove-item btn btn-danger" data-id="<?= $Item->id ?>" data-toggle="tooltip" data-placement="right" title="Delete item">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </cell> -->
                                                        </fable>
                                                    </div>

                                                <?php $i++ ?>

                                                <?php endforeach; ?>
                                            
                                            </div>

                                            <!-- <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/add-new?category=<?= $category_id ?>&subcategory=<?= $subcategory_id ?>" class="padding-top-1em"> -->
                                            <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/add-new?category=<?= $category_id ?>&subcategory=<?= $subcategory_id ?>" class="btn btn-white btn-sm btn-block margin-top-1em">
                                                Add more <?= $hashed_subcategories[$subcategory_id] ?>
                                            </a>
                                        </div>
                                    </div>
                                    
                                <?php endforeach; ?>

                            </ledger>
                        </div>

                    <?php endforeach; ?>

                </ledger>
            </form>

        <?php else: ?>

            <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/add-new" class="btn btn-primary">
                Create your first item
            </a>

        <?php endif; ?>

        </div>
    </div>
</main>