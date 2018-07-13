<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="page-title">
                    Your wholesale items
                    &nbsp;
                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/retail" class="badge badge-secondary">
                        Switch to retail
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>

                <div class="page-description text-muted small">
                    These are all of your wholesale items (available only to your approved <a href="<?= PUBLIC_ROOT ?>dashboard/selling/wholesale/buyers" class="strong">wholesale buyers</a>).
                    Tick the bubble at the beginning of each row to mark an item for a bulk action, or click the button at the end of each row for more options.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="edit-items" class="btn btn-success">
                        <i class="pre fa fa-floppy-o"></i>
                        Save changes
                        <i class="post fa fa-gear loading-icon save"></i>
                    </button>

                    <div class="dropdown">
                        <a class="btn btn-muted dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Bulk actions
                        </a>

                        <div class="dropdown-menu">
                            <a id="copy-to-retail" class="dropdown-item action" href="">
                                <i class="fa fa-clone"></i> Copy to retail
                            </a>

                            <!-- <a class="dropdown-item remove-item" href="" data-id="<?= $Item->id ?>">
                                Delete
                            </a> -->
                        </div>
                    </div>
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
                                        <strong>
                                            <?= ucfirst($hashed_categories[$category_id]) ?>
                                        </strong>
                                    </h5>
                                </cell>
                            </fable>

                            <ledger class="collapse show" id="category-<?= $category_id ?>">
                                
                                <?php foreach ($subcategory as $subcategory_id => $variety): ?>
                                
                                    <?php foreach ($variety as $variety_id => $items): ?>
                                    
                                        <?php $rev = array_reverse($items) ?>
                                        <?php $key = array_pop($rev)->id ?>
                                        
                                        <div class="opened record animated fadeIn">
                                            <div class="tab" data-toggle="collapse" data-target="#item-<?= $subcategory_id ?>-<?= $variety_id ?>" aria-controls="item-<?= $subcategory_id ?>-<?= $variety_id ?>" aria-label="Toggle item"></div>
                                            
                                            <fable>
                                                <cell>
                                                    <div class="user-block">
                                                        <div class="user-photo d-none d-md-block" style="background-image: url('<?= (isset($items[$key]->Image->id) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/item-images/{$items[$key]->Image->filename}.{$items[$key]->Image->ext}" : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg') ?>');"></div>
                                                        
                                                        <div class="user-content">
                                                            <h5 class="bold muted margin-btm-25em">
                                                                <strong>
                                                                    <?= ucfirst(((!empty($hashed_varieties[$variety_id])) ? "{$hashed_varieties[$variety_id]}&nbsp;" : '') . $hashed_subcategories[$subcategory_id]) ?>
                                                                </strong>
                                                                
                                                                <a href="<?= PUBLIC_ROOT . $User->GrowerOperation->link . "/" . $items[$key]->link ?>">
                                                                    <i class="fa fa-external-link small"></i>
                                                                </a>
                                                            </h5>

                                                            <small>
                                                                <?php $count = count($items) ?>
                                                                <?= "{$count} option" . (($count > 1) ? 's' : '') ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </cell>
                                            </fable>
                                        
                                            <div class="collapse show" id="item-<?= $subcategory_id ?>-<?= $variety_id ?>">
                                            
                                                <div class="item-list">
                                                    
                                                    <?php $i = 0 ?>
                                                    
                                                    <?php foreach ($items as $item_id => $Item): ?>
                                                        
                                                        <div class="bubble">
                                                            <input type="hidden" class="position" name="items[<?= $Item->id ?>][position]" value="<?= (!empty($Item->position)) ? $Item->position : $i ?>">

                                                            <fable>
                                                                <cell class="justify-center flexgrow-0 align-center form-field">
                                                                    <div class="form-group">
                                                                        <div class="toggle-box-alt">
                                                                            <input id="select-<?= $Item->id ?>" type="checkbox" name="items[<?= $Item->id ?>][select]">
                                                                            <label for="select-<?= $Item->id ?>" data-toggle="tooltip" data-title"Select this item" data-placement="right">Toggle</label>
                                                                        </div>
                                                                        
                                                                        <label>
                                                                            <!-- Select -->
                                                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Select this item for a bulk action" data-placement="bottom"></i>
                                                                        </label>
                                                                    </div>
                                                                </cell>

                                                                <?php if (!empty($Item->Image->id) && $Item->Image->id != $items[$key]->Image->id): ?>

                                                                    <cell class="image align-center margin-right-50em">
                                                                        <div class="user-photo no-margin" style="background-image: url('<?= (isset($Item->Image->id) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/item-images/{$Item->Image->filename}.{$Item->Image->ext}" : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg') ?>');"></div>
                                                                    </cell>

                                                                <?php endif; ?>

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

                                                                <?php endif; ?>

                                                                <cell class="justify-center basis-6em align-center form-field">
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

                                                                <cell class="justify-center basis-6em align-center form-field">
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

                                                                <cell class="justify-center basis-6em align-center form-field">
                                                                    <div class="form-group">
                                                                        <div class="price">
                                                                            <input type="text" name="items[<?= $Item->id ?>][price]" class="form-control" value="<?php if ($Item->price) echo number_format($Item->price / 100, 2) ?>" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-min="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" required>
                                                                        </div>
                                                                    
                                                                        <label>
                                                                            Price
                                                                        </label>
                                                                    </div>
                                                                </cell>
                                                                
                                                                <cell class="justify-center align-center form-field">
                                                                    <div class="form-group">
                                                                        <input type="number" name="items[<?= $Item->id ?>][quantity]" class="form-control" value="<?= $Item->quantity ?>"/>
                                                                        
                                                                        <label>
                                                                            Quantity
                                                                        </label>
                                                                    </div>
                                                                </cell>
                                                                
                                                                <cell class="actions flexgrow-0 margin-left-50em">
                                                                    <div class="dropdown">
                                                                        <div class="dropdown-toggle no-after-border" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <a class="btn btn-lg btn-muted" data-toggle="tooltip" data-title="Actions" data-placement="right">
                                                                                <i class="fa fa-caret-down"></i>
                                                                            </a>
                                                                        </div>

                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            <a class="dropdown-item" href="<?= PUBLIC_ROOT ?>dashboard/selling/items/edit?id=<?= $Item->id ?>">
                                                                                <i class="fa fa-pencil"></i>
                                                                                Edit item
                                                                            </a>

                                                                            <a class="dropdown-item" href="<?= PUBLIC_ROOT . $User->GrowerOperation->link . "/" . $items[$key]->link ?>">
                                                                                <i class="fa fa-external-link"></i>
                                                                                View item
                                                                            </a>

                                                                            <a class="remove-item dropdown-item action" href="" data-id="<?= $Item->id ?>">
                                                                                <i class="fa fa-trash"></i>
                                                                                Delete item
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </cell>
                                                            </fable>
                                                        </div>

                                                    <?php $i++ ?>

                                                    <?php endforeach; ?>
                                                
                                                </div>

                                                <!-- <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/add-new?category=<?= $category_id ?>&subcategory=<?= $subcategory_id ?>" class="padding-top-1em"> -->
                                                <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/add-new?category=<?= $category_id ?>&subcategory=<?= $subcategory_id ?>&variety=<?= $variety_id ?>" class="btn btn-white btn-sm btn-block margin-top-1em">
                                                    Add another option
                                                </a>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>

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