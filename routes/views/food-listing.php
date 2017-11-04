<div class="container-fluid">
    <div class="row">
        <main class="col-md-12">
            <div class="main container animated fadeIn">
                <?php

                if ($GrowerOperation->id) {

                    ?>

                    <div class="row">
                        <div class="col-lg-3">
                            <div id="sidebar-content">
                                <div class="photo box">
                                    <?php img(ENV . '/food-listings/' . $FoodListing->filename, $FoodListing->ext, 'S3', 'img-fluid'); ?>
                                </div>
                                
                                <div class="map box">
                                    <div id="map"></div>
                                </div>

                                <div class="photo box">
                                    <?php img(ENV . $GrowerOperation->details['path'], $GrowerOperation->details['ext'], 'S3', 'img-fluid'); ?>
                                </div>
                            </div> <!-- end div.sidebar-content -->
                        </div>

                        <div class="col-lg-5">
                            <div id="main-content">
                                <h3 class="listing-name">
                                    <?php echo $FoodListing->title; ?>
                                </h3>

                                <h6 class="listing-subtitle">
                                    <!-- ! dynamically construct -->
                                    <span class="listing-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </span>

                                    <!-- ! this is dirty -->
                                    &nbsp;&bull;&nbsp;
                                    
                                    $<?php echo number_format(($FoodListing->price / $FoodListing->weight) / 100, 2) . '/' . $FoodListing->units; ?>
                                    
                                    &nbsp;&bull;&nbsp;
                                    
                                    <?php echo $FoodListing->quantity . ' in stock'; ?>
                                </h6>
                                
                                <?php
                                
                                if (!empty($FoodListing->description)) echo "<div class=\"bio\">{$FoodListing->description}</div>";

                                ?>

                                <div class="grower-snapshot set">
                                    <div class="title">
                                        <strong>About the grower</strong>
                                    </div>

                                    <div class="subtitle">
                                        Get to know the <?php echo (($GrowerOperation->type == 'none') ? 'person' : 'people'); ?> growing your food
                                    </div>

                                    <div class="callout">   
                                        <div class="grower-title">
                                            <div class="name">
                                                <a href="<?php echo PUBLIC_ROOT . 'grower?id=' . $GrowerOperation->id; ?>">
                                                    <?php echo $GrowerOperation->details['name']; ?>
                                                </a>
                                            </div>

                                            <div class="rating">
                                                <?php echo $grower_stars; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="text-muted">
                                            <?php echo $GrowerOperation->details['bio']; ?>
                                        </div>

                                        <div class="grower-location">
                                            <?php echo $GrowerOperation->details['city'] . ', ' . $GrowerOperation->details['state']; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="available-exchange-options set">
                                    <div class="title">
                                        <strong>Exchange options</strong> 
                                        (<?php echo count($exchange_options_available); ?>)
                                    </div>

                                    <div class="subtitle">
                                        Available options for getting your food
                                    </div>

                                    <?php
                                                
                                    if ($GrowerOperation->Delivery && $GrowerOperation->Delivery->is_offered) {

                                        ?>

                                        <div class="callout">
                                            <h6>
                                                Delivery
                                            </h6>
                                            
                                            <p>
                                                Will deliver within: 
                                                <?php echo $GrowerOperation->Delivery->distance; ?> 
                                                miles
                                            </p>

                                            <?php
                                            
                                            if ($GrowerOperation->Delivery->delivery_type == 'conditional') {
                                                
                                                echo "<p>Free delivery within: {$GrowerOperation->Delivery->free_distance} miles</p>";

                                            }

                                            ?>

                                            <p>
                                                <?php echo ($GrowerOperation->Delivery->delivery_type == 'free' ? 'Free' : 'Rate: $' . number_format($GrowerOperation->Delivery->fee, 2) . '/' . str_replace('-', ' ', $GrowerOperation->Delivery->pricing_rate)); ?>
                                            </p>
                                        </div>

                                        <?php

                                    } if ($GrowerOperation->Pickup && $GrowerOperation->Pickup->is_offered) {
                                        
                                        ?>
                                        
                                        <div class="callout">
                                            <h6>
                                                Pickup
                                            </h6>

                                            <p>
                                                <?php echo $GrowerOperation->details['city'] . ', ' . $GrowerOperation->details['state']; ?>
                                            </p>
                                            
                                            <?php
                                            
                                            if (isset($distance)) {
                                                echo "<p>{$distance['length']} {$distance['units']} away</p>";
                                            }

                                            ?>
                                        </div>

                                        <?php
                                        
                                    } if ($GrowerOperation->Meetup && $GrowerOperation->Meetup->is_offered) {
                                        
                                        ?>
                                        
                                        <div class="callout">
                                            <strong>Meetup</strong>
                                            <p>Will deliver within: <?php echo $GrowerOperation->Delivery->distance; ?> miles</p>
                                            <p>Free delivery within: <?php echo $GrowerOperation->Delivery->free_distance; ?> miles</p>
                                            <p><?php echo $GrowerOperation->Delivery->fee . '/' . $GrowerOperation->Delivery->pricing_rate; ?></p>
                                        </div>

                                        <?php
                                        
                                    }
                                    
                                    ?>
                                </div>
                            </div>    
                        </div> <!-- end div.main-content -->

                        <div class="col-lg-4">
                            <div id="add-to-cart" class="sticky-top">
                                <div class="box">
                                    <div class="header">    
                                        <?php echo '$' . number_format($FoodListing->price / 100, 2); ?>
                                        
                                        <small>
                                            each
                                        </small>
                                    </div>

                                    <div class="content">
                                        <div class="alerts"></div>

                                        <form id="add-item">
                                            <input type="hidden" name="user_id" value="<?php echo (isset($User)) ? $User->id : ''; ?>">
                                            <input type="hidden" name="food_listing_id" value="<?php echo $FoodListing->id; ?>">

                                            <div class="form-group">
                                                <label>
                                                    Quantity
                                                </label>
                                                
                                                <select name="quantity" class="custom-select" data-parsley-trigger="change" required>
                                                    <?php
                                                    
                                                    for ($i = 1; $i <= $FoodListing->quantity; $i++) {
                                                        echo "<option value=\"{$i}\">{$i}</option>";
                                                    }
                                                    
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="exchange form-group">
                                                <label>
                                                    Exchange option
                                                </label>

                                                <div class="btn-group">
                                                    <?php
                                                    
                                                    foreach ($exchange_options_available as $option) {
                                                        echo "<button type=\"button\" class=\"btn btn-secondary\" data-option=\"" . $option . "\">" . ucfirst($option) . "</button>";
                                                    }
                                                    
                                                    ?>
                                                </div>

                                                <div class="form-control-feedback hidden">
                                                    Please select an exchange type
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary btn-block">
                                                Add to basket
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div> <!-- end div.right-content -->
                        </div>
                    </div>
                    
                    <?php

                } else {
                    echo 'Oops! This ID does not belong to an active food listing.';
                }

            ?>
            </div>
        </main> <!-- end main -->
    </div> <!-- end div.row -->
</div> <!-- end div.container-fluid -->

<script>
    var lat = <?php echo number_format($GrowerOperation->details['lat'], 2); ?>;
    var lng = <?php echo number_format($GrowerOperation->details['lng'], 2); ?>;
</script>