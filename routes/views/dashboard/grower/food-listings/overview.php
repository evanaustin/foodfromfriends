<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    All your food listings
                </div>

                <div class="page-description text-muted small">
                    <?php echo $msg; ?>
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
                            <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/food-listings/edit?id=' . $FoodListing->id; ?>">
                                <?php
                                
                                if (!empty($FoodListing->filename)) {
                                    img(ENV . '/food-listings/' . $FoodListing->filename, $FoodListing->ext, 'S3', 'hidden');
                                } else {
                                    img('placeholders/default-thumbnail', 'jpg', 'local');
                                }

                                ?>
                            </a>

                            <div class="loading">
                                <i class="fa fa-circle-o-notch loading-icon"></i>
                            </div>
                        </div>

                        <div class="card-block d-flex flex-row">
                            <div class="listing-info d-flex flex-column">
                                <h4 class="card-title">
                                    <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/food-listings/edit?id=' . $FoodListing->id; ?>">
                                        <?php echo $FoodListing->title; ?>
                                    </a>
                                </h4>
                                
                                <h6 class="card-subtitle">
                                    <?php echo '$' . number_format($FoodListing->price / 100, 2) . ' • $' . number_format(($FoodListing->price / $FoodListing->weight) / 100, 2) . '/' . $FoodListing->units; ?>
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
                                <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/food-listings/edit?id=' . $FoodListing->id; ?>" data-toggle="tooltip" data-placement="left" title="Edit listing">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            
                                <a href="<?php echo PUBLIC_ROOT . $User->GrowerOperation->link . '/' . $FoodListing->link; ?>" data-toggle="tooltip" data-placement="left" title="View listing">
                                    <i class="fa fa-eye"></i>
                                </a> 
                                
                                <a href="" class="remove-listing" data-id="<?php echo $FoodListing->id; ?>" data-toggle="tooltip" data-placement="left" title="Delete listing">
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

            <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/food-listings/add-new'; ?>" class="btn btn-primary">
                Let's go create your first listing!
            </a>

            <?php
            
        }

        ?>

        </div>
    </div>
</main>