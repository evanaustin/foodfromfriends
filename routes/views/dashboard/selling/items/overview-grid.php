<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Your items
                    &nbsp;
                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/overview">
                        <i class="fa fa-bars" data-toggle="tooltip" data-title="Switch to stack view" data-placement="right"></i>
                    </a>
                </div>

                <div class="page-description text-muted small">
                    <?= $msg; ?>
                </div>
            </div>
        </div>
        
        <hr>

        <div class="alerts"></div>

        <div class="listings">
        
        <?php
        
        if ($listing_count > 0) {
        
            ?>

            <div class="row">

            <?php

            foreach($listings as $listing) {
                $FoodListing = new FoodListing([
                    'DB' => $DB,
                    'id' => $listing['id']
                ]);

                ?>

                <div class="col-md-4">
                    <div class="card animated zoomIn">
                        <div class="card-img-top"> 
                            <a href="<?= PUBLIC_ROOT . 'dashboard/selling/items/edit?id=' . $FoodListing->id; ?>">
                                <?php
                                
                                if (!empty($FoodListing->filename)) {
                                    img(ENV . '/items/' . $FoodListing->filename, $FoodListing->ext, [
                                        'server'    => 'S3',
                                        'class'     => 'img-fluid animated fadeIn hidden'
                                    ]);
                                    
                                    ?>

                                    <div class="loading">
                                        <i class="fa fa-circle-o-notch loading-icon"></i>
                                    </div>

                                    <?php

                                } else {
                                    img('placeholders/default-thumbnail', 'jpg', [
                                        'server'    => 'local', 
                                        'class'     => 'animated fadeIn img-fluid'
                                    ]);
                                }

                                ?>
                            </a>
                        </div>

                        <div class="card-body d-flex flex-row">
                            <div class="listing-info d-flex flex-column">
                                <h4 class="card-title">
                                    <a href="<?= PUBLIC_ROOT . 'dashboard/selling/items/edit?id=' . $FoodListing->id; ?>">
                                        <?= $FoodListing->title; ?>
                                    </a>
                                </h4>
                                
                                <h6 class="card-subtitle">
                                    <?= '$' . number_format($FoodListing->price / 100, 2) . (!empty($FoodListing->weight) && !empty($FoodListing->units) ? ' • $' . number_format(($FoodListing->price / $FoodListing->weight) / 100, 2) . '/' . $FoodListing->units : ''); ?>
                                </h6>

                                <p class="card-text">
                                    <?php
                                    
                                    if (!$FoodListing->is_available) {
                                        $niblet = 'bg-faded text-muted';
                                        $availability = 'text-muted';
                                    } else {
                                        $niblet = 'text-white';
                                        $availability = 'text-success';

                                        if ($FoodListing->quantity == 0) {
                                            $niblet .= ' bg-danger';
                                        } else if ($FoodListing->quantity > 0 && $FoodListing->quantity < 6) {
                                            $niblet .= ' bg-warning';
                                        } else if ($FoodListing->quantity > 5) {
                                            $niblet .= ' bg-success';
                                        }
                                    }

                                    echo "<span class=\"quantity {$niblet}\">{$FoodListing->quantity}</span> in stock • <span class=\"{$availability}\">" . (($FoodListing->is_available) ? 'Available' : 'Unavailable') . "</span>";
                                    
                                    ?>
                                </p>
                            </div>

                            <div class="listing-controls d-flex flex-column">
                                <a href="<?= PUBLIC_ROOT . 'dashboard/selling/items/edit?id=' . $FoodListing->id; ?>" data-toggle="tooltip" data-placement="left" title="Edit listing">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            
                                <a href="<?= PUBLIC_ROOT . $User->GrowerOperation->link . '/' . $FoodListing->link; ?>" data-toggle="tooltip" data-placement="left" title="View listing">
                                    <i class="fa fa-eye"></i>
                                </a> 
                                
                                <a href="" class="remove-listing" data-id="<?= $FoodListing->id; ?>" data-toggle="tooltip" data-placement="left" title="Delete listing">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php

            }

            ?>

            </div>

            <?php

        } else {

            ?>

            <a href="<?= PUBLIC_ROOT . 'dashboard/selling/items/add-new'; ?>" class="btn btn-primary">
                Let's go create your first listing!
            </a>

            <?php
            
        }

        ?>

        </div>
    </div>
</main>