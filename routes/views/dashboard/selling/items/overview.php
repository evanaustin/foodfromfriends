<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
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
        
        <?php if ($listing_count > 0): ?>
        
                <form id="edit-items">
                    <div class="row">

                        <?php foreach($listings as $listing): ?>

                            <?php $Item = new FoodListing([
                                'DB' => $DB,
                                'id' => $listing['id']
                            ]); ?>

                            <div class="col-md-12">
                                <fable class="bubble margin-btm-1em">
                                    <cell class="image"> 
                                        <!-- <a href="<?= PUBLIC_ROOT . 'dashboard/selling/items/edit?id=' . $Item->id; ?>">Edit</a> -->
                                        
                                        <?php if (!empty($Item->filename)): ?>

                                            <?php img(ENV . '/items/' . $Item->filename, $Item->ext, [
                                                'server'    => 'S3',
                                                'class'     => 'img-fluid rounded animated fadeIn'
                                            ]); ?>

                                            <div class="loading">
                                                <i class="fa fa-circle-o-notch loading-icon"></i>
                                            </div>

                                        <?php else: ?>

                                            <?php img('placeholders/default-thumbnail', 'jpg', [
                                                'server'    => 'local', 
                                                'class'     => 'animated fadeIn img-fluid'
                                            ]); ?>

                                        <?php endif; ?>

                                    </cell>

                                    <cell class="double justify-center strong">
                                        <a href="<?= PUBLIC_ROOT . "{$User->GrowerOperation->link}/{$Item->link}" ?>" data-toggle="tooltip" data-title="Preview item" data-placement="bottom">
                                            <?= $Item->title ?>
                                        </a>
                                    </cell>

                                    <cell class="justify-center d-flex flexcolumn">
                                        <div class="form-group">
                                            <div class="price">
                                                <input type="text" name="items[<?= $Item->id ?>][retail-price]" class="form-control " value="<?php if ($Item->price) echo number_format($Item->price / 100, 2) ?>" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-min="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$" required>
                                            </div>
                                        
                                            <label>
                                                Retail price
                                                
                                                <?php if (!empty($Item->weight) && !empty($Item->units)): ?>
                                                
                                                    &nbsp;
                                                    <i class="fa fa-info-circle muted" data-toggle="tooltip" data-title="$<?= number_format(($Item->price / $Item->weight) / 100, 2) . "/{$Item->units}" ?>"></i>
        
                                                <?php endif; ?>

                                            </label>
                                        </div>
                                    </cell>
                                    
                                    <cell class="justify-center d-flex flexcolumn">
                                        <div class="form-group">
                                            <div class="price">
                                                <input type="text" name="items[<?= $Item->id ?>][wholesale-price]" class="form-control" value="<?php if ($Item->wholesale_price) echo number_format($Item->wholesale_price / 100, 2) ?>" min="0" max="1000000" data-parsley-type="number" data-parlsey-min="0" data-parlsey-min="999999" data-parsley-pattern="^[0-9]+.[0-9]{2}$">
                                            </div>
                                        
                                            <label>
                                                Wholesale price
                                                
                                                <?php if (!empty($Item->wholesale_weight) && !empty($Item->wholesale_units)): ?>

                                                    &nbsp;
                                                    <i class="fa fa-info-circle muted" data-toggle="tooltip" data-title="$<?= number_format(($Item->wholesale_price / $Item->wholesale_weight) / 100, 2) . "/{$Item->wholesale_units}" ?>"></i>

                                                <?php endif; ?>
                                            
                                            </label>
                                        </div>
                                    </cell>

                                    <cell class="justify-center d-flex flexcolumn">
                                        <div class="form-group">

                                            <input type="number" name="items[<?= $Item->id ?>][quantity]" class="form-control" value="<?= $Item->quantity ?>"/>
                                            
                                            <label>
                                                Quantity
                                            </label>
                                        </div>
                                    </cell>

                                    <cell class="justify-center">
                                        <div class="form-group">
                                            <input id="available-<?= $Item->id ?>" type="checkbox" name="items[<?= $Item->id ?>][available]" <?php if ($Item->is_available) echo 'checked' ?>>

                                            <label for="available-<?= $Item->id ?>">
                                                Available
                                            </label>
                                        </div>
                                    </cell>

                                    <cell class="actions">
                                        <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/edit?id=<?= $Item->id ?>" class="btn btn-muted" data-toggle="tooltip" data-placement="left" title="Edit item">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        
                                        <!-- <a href="<?= PUBLIC_ROOT . "{$User->GrowerOperation->link}/{$Item->link}" ?>" class="btn btn-muted" data-toggle="tooltip" data-placement="left" title="View listing">
                                            <i class="fa fa-eye"></i>
                                        </a> -->

                                        <a class="remove-listing btn btn-danger" data-id="<?= $Item->id; ?>" data-toggle="tooltip" data-placement="left" title="Delete listing">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </cell>
                                </fable>
                            </div>
                            
                        <?php endforeach; ?>

                    </div>
                </form>

                <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/add-new" class="btn btn-primary">
                    Add another item
                </a>

            <?php else: ?>

                <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/add-new" class="btn btn-primary">
                    Create your first item
                </a>

            <?php endif; ?>

        </div>
    </div>
</main>