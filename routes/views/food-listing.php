<div class="container-fluid">
    <div class="row">
        <main class="col-md-12">
            <div class="main container">
                <?php

                if ($GrowerOperation->id) {

                    ?>

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="left-content">
                                <div class="photo box">
                                    <img src="<?php echo $listing_filename; ?>" class="img-fluid" alt="<?php echo $listing_title; ?>">
                                </div>
                                
                                <div class="photo box">
                                    <img src="<?php echo $op_filename; ?>" class="img-fluid" alt="<?php echo $name; ?>">
                                </div>

                                <div class="map box">
                                    <div id="map"></div>
                                </div>
                            </div> <!-- end div.left-content -->
                        </div>

                        <div class="col-lg-5">
                            <div class="middle-content">
                                <h3 class="name">
                                    <?php echo ucfirst($listing_title); ?>
                                </h3>

                                <h6 class="text-muted">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>

                                    &nbsp;&bull;&nbsp;
                                    
                                    $<?php echo number_format(($FoodListing->price / $FoodListing->weight) / 100, 2) . '/' . $FoodListing->units; ?>
                                    
                                    &nbsp;&bull;&nbsp;
                                    
                                    <?php echo $FoodListing->quantity . ' in stock'; ?>
                                </h6>
                                
                                <?php
                                
                                if (!empty($FoodListing->description)) {

                                    echo "<div class=\"bio\">{$FoodListing->description}</div>";

                                }

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
                                            <?php echo "<div class=\"name\">{$name}</div><div class=\"rating\">{$grower_stars}</div>"; ?>
                                        </div>
                                        
                                        <div class="text-muted">
                                            <?php echo $bio; ?>
                                        </div>

                                        <div class="grower-location">
                                            <?php echo $city . ', ' . $state; ?>
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
                                                
                                    if ($GrowerOperation->Delivery->is_offered) {

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

                                    } if ($GrowerOperation->Pickup->is_offered) {
                                        
                                        ?>
                                        
                                        <div class="callout">
                                            <h6>
                                                Pickup
                                            </h6>

                                            <p>
                                                <?php echo $city . ', ' . $state; ?>
                                            </p>
                                            
                                            <p>
                                                <?php echo $distance['length'] . ' ' . $distance['units'] . ' away'; ?>
                                            </p>
                                        </div>

                                        <?php
                                        
                                    } if ($GrowerOperation->Meetup->is_offered) {
                                        
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
                        </div> <!-- end div.middle-content -->

                        <div class="col-lg-4">
                            <div class="right-content sticky-top">
                                <div class="box">
                                    <div class="header">    
                                        <?php echo '$' . number_format($FoodListing->price / 100, 2); ?>
                                        
                                        <small>
                                            each
                                        </small>
                                    </div>

                                    <div class="content">
                                        <form id="add-to-cart">
                                            <div class="form-group">
                                                <label>
                                                    Quantity
                                                </label>
                                                
                                                <select id="" name="" class="custom-select form-control" data-parsley-trigger="change" required>
                                                    <?php
                                                    
                                                    for ($i = 1; $i <= $FoodListing->quantity; $i++) {
                                                        echo "<option value=\"{$i}\">{$i}</option>";
                                                    }
                                                    
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>
                                                    Exchange option
                                                </label>

                                                <div class="btn-group">
                                                    <?php
                                                    
                                                    foreach ($exchange_options_available as $option) {
                                                        echo "<button type=\"button\" class=\"btn btn-secondary\">" . ucfirst($option) . "</button>";
                                                    }
                                                    
                                                    ?>
                                                </div>
                                            </div>

                                            <button class="btn btn-primary btn-block">
                                                Add to cart
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
    var lat = <?php echo number_format($latitude, 2); ?>;
    var lng = <?php echo number_format($longitude, 2); ?>;
</script>