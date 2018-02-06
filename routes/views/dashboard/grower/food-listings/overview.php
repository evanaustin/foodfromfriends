<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    All your food listings
                </div>

                <div class="page-description text-muted small">
                    <?php
                    
                    if ($listing_count == 0) {
                        $msg = 'Hey! Unless your garden is as barren as this page, you\'ve got some adding to do. Hop to it!';
                    } else if ($listing_count == 1) {
                        $msg = 'That\'s a mighty fine listing you\'ve got there. Looks like it could use some company though &ndash;' . (($listings[0]['category_title'] == 'fruit') ? ' more' : '') . ' fruits or veggies, perhaps?';
                    } else if ($listing_count > 1 && $listing_count < 3) {
                        $msg = 'Looking good! Your selection is coming along well. Locavores prefer growers who offer a strong variety of food, so keep on diversifying if you can!';
                    } else if ($listing_count > 2 && $listing_count < 6) {
                        $msg = 'Nice variety you\'ve got there! Seriously, you\'re getting pretty good at this. I wonder if you might be able to handle growing even more&hellip;';
                    } else if ($listing_count > 5) {
                        $msg = 'Woah! We are truly blown away by your selection. Growers like you are the lifeblood of this friendly family. Keep on doing what you\'re doing!';
                    }

                    echo $msg;

                    ?>
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

                ?>

                <div class="col-md-4">
                    <div class="card animated zoomIn">
                        <div class="card-img-top"> 
                            <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/food-listings/edit?id=' . $listing['id']; ?>">
                                <?php
                                
                                if (!empty($listing['filename'])) {
                                    img(ENV . '/food-listings/' . $listing['filename'], $listing['ext'], 'S3', 'hidden');
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
                                    <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/food-listings/edit?id=' . $listing['id']; ?>">
                                        <?php echo ucfirst((empty($listing['other_subcategory']) ? ($listing['subcategory_title']) : $listing['other_subcategory'])); ?>
                                    </a>
                                </h4>
                                
                                <h6 class="card-subtitle">
                                    <?php echo '$' . number_format($listing['price'] / 100, 2) . ' • $' . number_format(($listing['price'] / $listing['weight']) / 100, 2) . '/' . $listing['units']; ?>
                                </h6>

                                <p class="card-text">
                                    <?php
                                        if (!$listing['is_available']) {
                                            $niblet = 'bg-faded text-muted';
                                            $availability = 'text-muted';
                                        } else {
                                            $niblet = 'text-white';
                                            $availability = 'text-success';

                                            if ($listing['quantity'] == 0) {
                                                $niblet .= ' bg-danger';
                                            } else if ($listing['quantity'] > 0 && $listing['quantity'] < 6) {
                                                $niblet .= ' bg-warning';
                                            } else if ($listing['quantity'] > 5) {
                                                $niblet .= ' bg-success';
                                            }
                                        }

                                        echo '<span class="quantity ' . $niblet . '">' . $listing['quantity'] . '</span> in stock • <span class="' . $availability . '">' . (($listing['is_available']) ? 'Available' : 'Unavailable') . '</span>';
                                    ?>
                                </p>
                            </div>

                            <div class="listing-controls d-flex flex-column">
                                <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/food-listings/edit?id=' . $listing['id']; ?>" data-toggle="tooltip" data-placement="left" title="Edit listing">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            
                                <!--a href="<?php echo PUBLIC_ROOT . 'food-listings/edit?id=' . $listing['id']; ?>" data-toggle="tooltip" data-placement="left" title="View listing">
                                    <i class="fa fa-eye"></i>
                                </a--> 
                                
                                <a href="" class="remove-listing" data-id="<?php echo $listing['id']; ?>" data-toggle="tooltip" data-placement="left" title="Delete listing">
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