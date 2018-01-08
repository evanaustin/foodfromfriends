<!-- <div class="container-fluid">
    <div class="row"> -->
        <!-- <main class="col-md-12"> -->
        <main>
            <div class="main container">
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

                                <!-- <div class="photo box">
                                    <?php //img(ENV . $GrowerOperation->details['path'], $GrowerOperation->details['ext'], 'S3', 'img-fluid'); ?>
                                </div> -->
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div id="main-content">
                                <h2 class="dark-gray bold margin-btm-25em">
                                    <?php echo $FoodListing->title; ?>
                                </h2>

                                <h6 class="muted normal margin-btm-1em">
                                    <span class="brand">
                                        <?php echo $item_stars; ?>
                                    </span>

                                    <?php
                                    
                                    if (!empty($ratings) && count($ratings) > 0) {
                                        echo '<div class="rounded-circle">' . count($ratings) . '</div>';
                                    }
                                    
                                    ?>

                                    <!-- ! dirty -->
                                    &bull;&nbsp;
                                    
                                    $<?php echo number_format(($FoodListing->price / $FoodListing->weight) / 100, 2) . '/' . $FoodListing->units; ?>
                                    
                                    <!-- ! dirty -->
                                    &nbsp;&bull;&nbsp;
                                    
                                    <?php echo $FoodListing->quantity . ' in stock'; ?>
                                </h6>
                                
                                <?php
                                
                                if (!empty($FoodListing->description)) {
                                    echo "<p class=\"muted margin-btm-2em\">{$FoodListing->description}</p>";
                                }

                                ?>

                                <div class="available-exchange-options set">
                                    <h4 class="margin-btm-50em">
                                        <bold class="dark-gray">Exchange options</bold>
                                        <light class="light-gray">(<?php echo count($exchange_options_available); ?>)</light>
                                    </h4>

                                    <div class="muted margin-btm-1em">
                                        Available options for getting your food
                                    </div>
                                    
                                    <?php
                                                
                                    if ($GrowerOperation->Delivery && $GrowerOperation->Delivery->is_offered) {

                                        ?>

                                        <div class="callout">
                                            <div class="muted font-18 thick">
                                                Delivery
                                            </div>
                                            
                                            <div>
                                                Will deliver within: <?php echo $GrowerOperation->Delivery->distance; ?> miles
                                            </div>

                                            <?php
                                            
                                            if ($GrowerOperation->Delivery->delivery_type == 'conditional') {
                                                
                                                echo "<div>Free delivery within: {$GrowerOperation->Delivery->free_distance} miles</div>";

                                            }

                                            ?>

                                            <div>
                                                <?php echo ($GrowerOperation->Delivery->delivery_type == 'free' ? 'Free' : 'Rate: $' . number_format($GrowerOperation->Delivery->fee / 100, 2) . ' ' . str_replace('-', ' ', $GrowerOperation->Delivery->pricing_rate)); ?>
                                            </div>
                                        </div>

                                        <?php

                                    } if ($GrowerOperation->Pickup && $GrowerOperation->Pickup->is_offered) {
                                        
                                        ?>
                                        
                                        <div class="callout">
                                            <div class="muted font-18 thick">
                                                Pickup
                                            </div>

                                            <div>
                                                <?php echo $GrowerOperation->details['city'] . ', ' . $GrowerOperation->details['state']; ?>
                                            </div>
                                            
                                            <?php
                                            
                                            if (isset($distance) && $distance['length'] > 0) {
                                                echo "<div>{$distance['length']} {$distance['units']} away</div>";
                                            }

                                            ?>
                                        </div>

                                        <?php
                                        
                                    } if ($GrowerOperation->Meetup && $GrowerOperation->Meetup->is_offered) {
                                        
                                        ?>
                                        
                                        <div class="callout">
                                            <div class="muted font-18 thick">
                                                Meetup
                                            </div>

                                            <div>
                                                <?php echo $GrowerOperation->Meetup->address_line_1 . (($GrowerOperation->Meetup->address_line_2) ? ', ' . $GrowerOperation->Meetup->address_line_2 : ''); ?><br>
                                                <?php echo $GrowerOperation->Meetup->city . ', ' . $GrowerOperation->Meetup->state . ' ' . $GrowerOperation->Meetup->zipcode; ?>
                                        </div>
                                        </div>

                                        <?php
                                        
                                    }
                                    
                                    ?>
                                </div>

                                <?php
                                    
                                if (!empty($ratings) && count($ratings) > 0) {
                                    
                                    ?>

                                    <div class="reviews set">
                                        <h4 class="margin-btm-50em ">
                                            <bold class="dark-gray">Reviews</bold> 
                                            <light class="light-gray">(<?php echo count($ratings); ?>)</light>
                                        </h4>
                                        
                                        <div class="muted margin-btm-1em">
                                            Item reviews from customers
                                        </div>

                                        <?php 
                                        
                                        foreach ($ratings as $rating) { 
                                        
                                            $ReviewUser = new User([
                                                'DB' => $DB,
                                                'id' => $rating['user_id']
                                            ]);

                                            ?>           
                                            
                                            <div class="user-block margin-btm-1em">                  
                                                <div class="user-photo" style="background-image: url(<?php echo (!empty($ReviewUser->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ReviewUser->filename . '.' . $ReviewUser->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg'); ?>);"></div>
                                                
                                                <div class="user-content">
                                                    <p class="muted margin-btm-25em">
                                                        &quot;<?php echo $rating['review']; ?>&quot;
                                                    </p>

                                                    <small class="dark-gray bold flexstart">
                                                        <?php echo $ReviewUser->first_name . ' &bull; ' . $ReviewUser->city . ', ' . $ReviewUser->state; ?>
                                                    </small>
                                                </div>
                                            </div>
                                            
                                            <?php

                                            }

                                        ?>
                                    </div>

                                    <?php

                                    }

                                ?>

                                <div class="about-grower set">
                                    <h4 class="margin-btm-50em ">
                                        <bold class="dark-gray">About the grower</bold> 
                                    </h4>

                                    <div class="muted margin-btm-1em">
                                        Get to know the <?php echo (($GrowerOperation->type == 'none') ? 'person' : 'people'); ?> growing your food
                                    </div>

                                    <div class="user-block">
                                        <div class="user-photo" style="background-image: url(<?php echo (!empty($GrowerOperation->details['path']) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . $GrowerOperation->details['path'] . '.' . $GrowerOperation->details['ext'] . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg'); ?>);"></div>    
                                    
                                        <div class="user-content">
                                            <div class="font-18 muted thick">    
                                                <a href="<?php echo PUBLIC_ROOT . 'grower?id=' . $GrowerOperation->id; ?>">
                                                    <?php echo $GrowerOperation->details['name']; ?>
                                                </a>
                                            </div>
                                                
                                            <div class="font-85 muted bold margin-btm-50em">
                                                <?php echo $grower_stars; ?>
                                                &nbsp;&bull;&nbsp;
                                                <?php echo $GrowerOperation->details['city'] . ', ' . $GrowerOperation->details['state']; ?>
                                            </div>
                                            
                                            <p class="light-gray">
                                                <?php echo $GrowerOperation->details['bio']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>

                        <div class="col-lg-4">
                            <div id="basket-form-container" class="sticky-top">
                                <div class="box">
                                    <div class="header">    
                                        <?php echo '$' . number_format($FoodListing->price / 100, 2); ?>
                                        
                                        <small>
                                            each
                                        </small>
                                    </div>

                                    <div class="content">
                                        <div class="alerts"></div>

                                        <?php

                                        if (isset($User) && isset($User->ActiveOrder) && isset($User->ActiveOrder->Growers[$GrowerOperation->id]) && isset($User->ActiveOrder->Growers[$GrowerOperation->id]->FoodListings[$FoodListing->id])) {
                                        
                                            $OrderGrower = $User->ActiveOrder->Growers[$GrowerOperation->id];
                                            $OrderItem = $OrderGrower->FoodListings[$FoodListing->id];

                                            ?>

                                            <form id="update-item">
                                                <input type="hidden" name="grower-operation-id" value="<?php echo $GrowerOperation->id; ?>">
                                                <input type="hidden" name="food-listing-id" value="<?php echo $FoodListing->id; ?>">

                                                <div class="form-group">
                                                    <label>
                                                        Quantity
                                                    </label>
                                                    
                                                    <select name="quantity" class="custom-select" data-parsley-trigger="change" required>
                                                        <?php
                                                        
                                                        for ($i = 1; $i <= $FoodListing->quantity; $i++) {
                                                            echo "<option value=\"{$i}\"" . (($OrderItem->quantity == $i) ? 'selected' : '') . ">{$i}</option>";
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
                                                            echo "<button type=\"button\" class=\"exchange-btn btn btn-secondary" . (($active_ex_op == $option) ? ' active' : '') . "\" data-option=\"" . $option . "\">" . ucfirst($option) . "</button>";
                                                        }
                                                        
                                                        ?>
                                                    </div>

                                                    <div class="form-control-feedback hidden">
                                                        Please select an exchange type
                                                    </div>
                                                </div>
                                            </form>

                                            <?php

                                        } else {
                                            
                                            ?>

                                            <form id="add-item">
                                                <input type="hidden" name="user-id" value="<?php echo (isset($User)) ? $User->id : ''; ?>">
                                                <input type="hidden" name="food-listing-id" value="<?php echo $FoodListing->id; ?>">

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
                                                            echo "<button type=\"button\" class=\"exchange-btn btn btn-secondary" . (($active_ex_op == $option) ? ' active' : '') . "\" data-option=\"" . $option . "\">" . ucfirst($option) . "</button>";
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

                                            <?php

                                        }

                                        ?>
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
        </main>
    <!-- </div> -->
<!-- </div> -->

<script>
    var lat = <?php echo number_format($GrowerOperation->details['lat'], 2); ?>;
    var lng = <?php echo number_format($GrowerOperation->details['lng'], 2); ?>;
</script>