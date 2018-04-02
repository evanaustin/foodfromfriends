<!-- <div class="container-fluid">
    <div class="row"> -->
        <!-- <main class="col-md-12"> -->
        <main>
            <div class="main container">
                <?php

                if ($FoodListing->id && ($GrowerOperation->is_active || $is_owner)) {

                    if ($is_owner) {

                        ?>

                        <div class="alerts" style="display:block;">
                            <div class="alert alert-<?php echo (($GrowerOperation->is_active) ? 'info' : 'warning'); ?>">

                                <?php

                                if ($GrowerOperation->is_active) {
                                    echo "<span>This is what your item looks like to the public. Click <a href=\"" . PUBLIC_ROOT . "dashboard/grower/items/edit?id={$FoodListing->id}\">here</a> to go edit this item.</span>";
                                } else {
                                    echo '<span><i class="fa fa-warning"></i> This is only a preview of this item. Click <a href="' . PUBLIC_ROOT . 'dashboard/grower">here</a> to finish activating your seller account.</span>';
                                }

                                ?>

                                <a class="close" data-dismiss="alert">×</a>
                            </div>
                        </div>

                        <?php

                    }

                    ?>

                    <div class="row">
                        <div class="col-lg-3 order-lg-1 d-none d-md-block">
                            <div class="sidebar-content">
                                <div class="photo box">
                                    <?php
                                    
                                    if (!empty($FoodListing->filename)) {
                                        echo '<a href="#" data-toggle="modal" data-target="#img-zoom-modal">';

                                        img(ENV . '/items/' . $FoodListing->filename, $FoodListing->ext, [
                                            'server'    => 'S3',
                                            'class'     => 'img-fluid'
                                        ]);
                                        
                                        echo '</a>';
                                    } else {
                                        img('placeholders/default-thumbnail', 'jpg', [
                                            'server'    => 'local', 
                                            'class'     => 'img-fluid rounded'
                                        ]);
        
                                        if ($is_owner) {
                                            echo "<a href=\"" . PUBLIC_ROOT . "dashboard/grower/items/edit?id={$FoodListing->id}\" class=\"btn btn-cta btn-block\">Add an item image</a>";
                                        }
                                    }

                                    ?>
                                </div>
                                
                                <div class="<?php echo (isset($GrowerOperation->latitude, $GrowerOperation->longitude) ? 'map' : 'photo'); ?> box">
                                    <?php
                                            
                                    if (isset($GrowerOperation->latitude, $GrowerOperation->longitude)) {
                                        echo "<div id=\"map\"></div>";
                                    } else {
                                        img('placeholders/location-thumbnail', 'jpg', [
                                            'server'    => 'local', 
                                            'class'     => 'img-fluid rounded'
                                        ]);

                                        if ($is_owner) {
                                            echo '<a href="' . PUBLIC_ROOT . 'dashboard/grower/settings/edit-profile" class="btn btn-cta btn-block">Set your address</a>';
                                        }
                                    }

                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 order-1 col-lg-5 order-lg-1">
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
                                    
                                    if (!empty($FoodListing->weight) && !empty($FoodListing->units)) {
                                        echo '&bull;&nbsp;$' . number_format(($FoodListing->price / $FoodListing->weight) / 100, 2) . '/' . $FoodListing->units;
                                    }

                                    echo '&nbsp;&bull;&nbsp;' . ($FoodListing->is_available ? "{$FoodListing->quantity} in stock" : 'Unavailable');

                                    ?>
                                </h6>
                                
                                <?php
                                
                                if (!empty($FoodListing->description)) {
                                    echo '<div class="callout description">';
                                    echo "<div>{$FoodListing->description}</div>";
                                    echo '</div>';
                                } else if ($is_owner) {
                                    echo "<a href=\"" . PUBLIC_ROOT . "dashboard/grower/items/edit?id={$FoodListing->id}\" class=\"btn btn-cta\">Add a description</a>";
                                }

                                if (!empty($FoodListing->unit_definition)) {
                                    
                                    ?>
                                    
                                    <div class="item-definition set d-none d-md-block">
                                        <h4 class="margin-btm-50em">
                                            Packaging
                                        </h4>

                                        <div class="muted margin-btm-1em">
                                            How the item will come packaged
                                        </div>
        
                                        <div class="callout">
                                            <div><?php echo $FoodListing->unit_definition; ?></div>
                                        </div>
                                    </div>

                                    <?php
                                    
                                }

                                ?>


                                <div class="available-exchange-options set d-none d-md-block">
                                    <h4 class="margin-btm-50em">
                                        <bold class="dark-gray">Exchange options</bold>
                                        <?php if (!empty($exchange_options_available)) echo "<light class=\"light-gray\">(" .count($exchange_options_available) . ")</light>"; ?>
                                    </h4>

                                    <div class="muted margin-btm-1em">
                                        Available options for getting your food
                                    </div>
                                    
                                    <?php
                                            
                                    if (!empty($exchange_options_available)) {
                                        if ($GrowerOperation->Delivery && $GrowerOperation->Delivery->is_offered) {
    
                                            ?>
    
                                            <div class="callout">
                                                <div class="muted font-18 thick">
                                                    Delivery
                                                </div>
                                                
                                                <div>
                                                   <?php echo "Will deliver within: {$GrowerOperation->Delivery->distance} miles"; ?>
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
                                                    <?php echo "{$GrowerOperation->city}, {$GrowerOperation->state}"; ?>
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
                                                    <?php
                                                    
                                                    echo $GrowerOperation->Meetup->address_line_1 . (($GrowerOperation->Meetup->address_line_2) ? ', ' . $GrowerOperation->Meetup->address_line_2 : '') . '<br>';
                                                    echo "{$GrowerOperation->Meetup->city}, {$GrowerOperation->Meetup->state} {$GrowerOperation->Meetup->zipcode}<br>";
                                                    echo $GrowerOperation->Meetup->time;
                                                    
                                                    ?>
                                                </div>
                                            </div>
    
                                            <?php
                                            
                                        }
                                    } else {
                                        echo "<div class=\"callout\">{$GrowerOperation->name} hasn't enabled any exchange options yet</div>";
                                
                                        if ($is_owner) {
                                            ?>

                                            <div class="btn-group">
                                                <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/delivery'; ?>" class="btn btn-cta">Enable delivery</a>
                                                <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/pickup'; ?>" class="btn btn-cta">Enable pickup</a>
                                                <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/meetup'; ?>" class="btn btn-cta">Enable meetup</a>
                                            </div>

                                            <?php
                                        }
                                    }
                                    
                                    ?>
                                </div>

                                <?php
                                    
                                if (!empty($ratings) && count($ratings) > 0) {
                                    
                                    ?>

                                    <div class="reviews set d-none d-md-block">
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
                                                <a href="<?php echo PUBLIC_ROOT . "user/{$ReviewUser->slug}"; ?>">
                                                    <div class="user-photo" style="background-image: url(<?php echo (!empty($ReviewUser->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ReviewUser->filename . '.' . $ReviewUser->ext /* . '?' . time() */: PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>
                                                </a>

                                                <div class="user-content">
                                                    <p class="muted margin-btm-25em">
                                                        &quot;<?php echo $rating['review']; ?>&quot;
                                                    </p>

                                                    <small class="dark-gray bold flexstart">
                                                        <?php echo "<a href=\"" . PUBLIC_ROOT . "user/{$ReviewUser->slug}\" class=\"strong\">{$ReviewUser->name}</a> &bull; {$ReviewUser->city}, {$ReviewUser->state}"; ?>
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

                                <div class="about-grower set d-none d-md-block">
                                    <h4 class="margin-btm-50em ">
                                        <bold class="dark-gray">About the grower</bold> 
                                    </h4>

                                    <div class="muted margin-btm-1em">
                                        Get to know the <?php echo (($GrowerOperation->type == 'individual') ? 'person' : 'people'); ?> growing your food
                                    </div>

                                    <div class="user-block margin-btm-50em">
                                        <a href="<?php echo PUBLIC_ROOT . "{$GrowerOperation->link}"; ?>">
                                            <div class="user-photo" style="background-image: url(<?php echo (!empty($GrowerOperation->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$GrowerOperation->filename}.{$GrowerOperation->ext}" : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>    
                                        </a>
                                        
                                        <div class="user-content">
                                            <div class="font-18 muted thick">    
                                                <a href="<?php echo PUBLIC_ROOT . $GrowerOperation->link; ?>">
                                                    <?php echo $GrowerOperation->name; ?>
                                                </a>
                                            </div>
                                                
                                            <?php

                                            if ($GrowerOperation->is_active) {
                                                echo '<div class="font-85 muted bold margin-btm-50em">';
                                                echo "{$grower_stars} &nbsp;&bull;&nbsp; {$GrowerOperation->city}, {$GrowerOperation->state}";
                                                echo '</div>';
                                            }

                                            ?>
                                        </div>
                                    </div>

                                    <?php

                                    if (!$GrowerOperation->is_active && $is_owner) {
                                        echo '<a href="' . PUBLIC_ROOT . 'dashboard/grower" class="btn btn-cta margin-top-1em">Complete your profile</a>';
                                    } else if (!empty($GrowerOperation->bio)) {
                                        echo '<div class="callout">';
                                        echo "<div>{$GrowerOperation->bio}</div>";
                                        echo '</div>';
                                    }
                                    
                                    ?>
                                </div>
                            </div>    
                        </div>

                        <div class="col-12 order-2 col-lg-4 order-lg-2">
                            <div id="basket-form-container" class="sticky-top">
                                <div class="box">
                                    <div class="header">    
                                        <?php amount($FoodListing->price); ?>
                                        
                                        <small>
                                            each
                                        </small>
                                    </div>

                                    <div class="content">
                                        <div class="alerts"></div>

                                        <?php

                                        if (!$FoodListing->is_available) {
                                            echo '<span class="muted">This item is currently unavailable</span>';
                                        } else {
                                            if (isset($User, $User->ActiveOrder, $User->ActiveOrder->Growers[$GrowerOperation->id], $User->ActiveOrder->Growers[$GrowerOperation->id]->FoodListings[$FoodListing->id])) {
                                        
                                                $OrderGrower = $User->ActiveOrder->Growers[$GrowerOperation->id];
                                                $OrderItem = $OrderGrower->FoodListings[$FoodListing->id];

                                                ?>

                                                <form id="update-item" data-ordergrower="<?php echo $OrderGrower->id; ?>">
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

                                                        <?php
                                                        
                                                        if (!empty($exchange_options_available)) {
                                                            echo '<div class="btn-group">';
                                                            
                                                            foreach ($exchange_options_available as $option) {
                                                                echo "<button type=\"button\" class=\"exchange-btn btn btn-secondary" . (($active_ex_op == $option) ? ' active' : '') . "\" data-option=\"" . $option . "\">" . ucfirst($option) . "</button>";
                                                            }
                                                            
                                                            echo '</div>';
                                                        } else {
                                                            echo "<div class=\"callout\">No exchange options available</div>";
                            
                                                            /* if ($is_owner) {
                                                                ?>

                                                                <div class="btn-group">
                                                                    <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/delivery'; ?>" class="btn">Enable delivery</a>
                                                                    <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/pickup'; ?>" class="btn">Enable pickup</a>
                                                                    <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/meetup'; ?>" class="btn">Enable meetup</a>
                                                                </div>

                                                                <?php
                                                            } */
                                                        }
                                                        
                                                        ?>
                                                        

                                                        <div class="form-control-feedback hidden">
                                                            Please select an exchange type
                                                        </div>
                                                    </div>
                                                </form>

                                                <?php

                                            } else {
                                            
                                                ?>

                                                <form id="add-item" data-ordergrower="<?php echo $OrderGrower->id; ?>">
                                                    <input type="hidden" name="user-id" value="<?php echo (isset($User)) ? $User->id : ''; ?>">
                                                    <input type="hidden" name="food-listing-id" value="<?php echo $FoodListing->id; ?>">

                                                    <div class="form-group">
                                                        <label>
                                                            Quantity
                                                        </label>
                                                        
                                                        <select name="quantity" class="custom-select" data-parsley-trigger="change" required>
                                                            <?php
                                                            
                                                            for ($i = 1; $i <= $FoodListing->quantity; $i++) {
                                                                echo "<option value=\"{$i}\"" . (isset($_GET['quantity']) && $_GET['quantity'] == $i ? 'selected' : '') . ">{$i}</option>";
                                                            }
                                                            
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="exchange form-group">
                                                        <label>
                                                            Exchange option
                                                        </label>

                                                        
                                                        <?php
                                                        
                                                        if (!empty($exchange_options_available)) {
                                                            echo '<div class="btn-group">';
                                                            
                                                            foreach ($exchange_options_available as $option) {
                                                                echo "<button type=\"button\" class=\"exchange-btn btn btn-secondary" . ((isset($_GET['exchange']) && $_GET['exchange'] == $option) ? ' active' : '') . "\" data-option=\"" . $option . "\">" . ucfirst($option) . "</button>";
                                                            }
                                                            
                                                            echo '</div>';
                                                        } else {
                                                            echo "<div class=\"callout\">No exchange options available</div>";
                            
                                                            /* if ($is_owner) {

                                                                ?>

                                                                <div class="btn-group">
                                                                    <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/delivery'; ?>" class="btn">Enable delivery</a>
                                                                    <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/pickup'; ?>" class="btn">Enable pickup</a>
                                                                    <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/meetup'; ?>" class="btn">Enable meetup</a>
                                                                </div>

                                                                <?php

                                                            } */
                                                        }
                                                        
                                                        ?>

                                                        <div class="form-control-feedback hidden">
                                                            Please select an exchange type
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary btn-block" <?php if (!$GrowerOperation->is_active) echo 'disabled'; ?>>
                                                        Add to basket
                                                    </button>
                                                </form>

                                                <?php

                                            }

                                        }

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 order-3 d-md-none">
                            <div class="sidebar-content">
                                <div class="photo box">
                                    <?php
                                    
                                    img(ENV . '/items/' . $FoodListing->filename, $FoodListing->ext, [
                                        'server'    => 'S3',
                                        'class'     => 'img-fluid'
                                    ]);
                                    
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 order-4 d-md-none">
                            <?php

                            if (!empty($FoodListing->unit_definition)) {

                                ?>

                                <div class="item-definition set">
                                    <h4>
                                        Item definition
                                    </h4>

                                    <div class="callout">
                                        <div><?php echo $FoodListing->unit_definition; ?></div>
                                    </div>
                                </div>

                                <?php

                            }

                            ?>

                            <div class="available-exchange-options set">
                                <h4 class="margin-btm-50em">
                                    Exchange options
                                    <light class="light-gray">(<?php echo count($exchange_options_available); ?>)</light>
                                </h4>

                                <div class="muted margin-btm-1em">
                                    Available options for getting your food
                                </div>
                                
                                <?php
                                     
                                if (!empty($exchange_options_available)) {
                                    if ($GrowerOperation->Delivery && $GrowerOperation->Delivery->is_offered) {
    
                                        ?>
    
                                        <div class="callout">
                                            <div class="muted font-18 thick">
                                                Delivery
                                            </div>
                                            
                                            <div>
                                                <?php echo "Will deliver within: {$GrowerOperation->Delivery->distance} miles"; ?>
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
                                                <?php echo "{$GrowerOperation->city}, {$GrowerOperation->state}"; ?>
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
                                                <?php
                                                
                                                echo $GrowerOperation->Meetup->address_line_1 . (($GrowerOperation->Meetup->address_line_2) ? ', ' . $GrowerOperation->Meetup->address_line_2 : '') . '<br>';
                                                echo "{$GrowerOperation->Meetup->city}, {$GrowerOperation->Meetup->state} {$GrowerOperation->Meetup->zipcode}<br>";
                                                echo $GrowerOperation->Meetup->time;
                                                
                                                ?>
                                            </div>
                                        </div>
    
                                        <?php
                                        
                                    }
                                } else {
                                    echo "<div class=\"callout\">{$GrowerOperation->name} hasn't enabled any exchange options yet</div>";
    
                                    if ($is_owner) {
                                        ?>

                                        <div class="btn-group">
                                            <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/delivery'; ?>" class="btn btn-cta">Enable delivery</a>
                                            <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/pickup'; ?>" class="btn btn-cta">Enable pickup</a>
                                            <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/exchange-options/meetup'; ?>" class="btn btn-cta">Enable meetup</a>
                                        </div>

                                        <?php
                                    }
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
                                            <a href="<?php echo PUBLIC_ROOT . "user/{$ReviewUser->slug}"; ?>">                
                                                <div class="user-photo" style="background-image: url(<?php echo (!empty($ReviewUser->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ReviewUser->filename . '.' . $ReviewUser->ext /* . '?' . time() */: PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>
                                            </a>

                                            <div class="user-content">
                                                <p class="muted margin-btm-25em">
                                                    &quot;<?php echo $rating['review']; ?>&quot;
                                                </p>

                                                <small class="dark-gray bold flexstart">
                                                    <?php echo "<a href=\"" . PUBLIC_ROOT . "user/{$ReviewUser->slug}\" class=\"strong\">{$ReviewUser->name}</a> &bull; {$ReviewUser->city}, {$ReviewUser->state}"; ?>
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
                                    <bold class="dark-gray">About the seller</bold> 
                                </h4>

                                <div class="muted margin-btm-1em">
                                    Get to know the <?php echo (($GrowerOperation->type == 'individual') ? 'person' : 'people'); ?> selling these items
                                </div>

                                <div class="user-block">
                                    <a href="<?php echo PUBLIC_ROOT . "{$GrowerOperation->link}"; ?>">
                                        <div class="user-photo" style="background-image: url(<?php echo (!empty($GrowerOperation->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$GrowerOperation->filename}.{$GrowerOperation->ext}" : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>    
                                    </a>

                                    <div class="user-content">
                                        <div class="font-18 muted thick">    
                                            <a href="<?php echo PUBLIC_ROOT . $GrowerOperation->link; ?>">
                                                <?php echo $GrowerOperation->name; ?>
                                            </a>
                                        </div>

                                        <?php

                                        if ($GrowerOperation->is_active) {
                                            echo '<div class="font-85 muted bold margin-btm-50em">';
                                            echo "{$grower_stars} &nbsp;&bull;&nbsp; {$GrowerOperation->city}, {$GrowerOperation->state}";
                                            echo '</div>';
                                        }

                                        ?>
                                    </div>
                                </div>

                                <?php

                                if (!$GrowerOperation->is_active && $is_owner) {
                                    echo '<a href="' . PUBLIC_ROOT . 'dashboard/grower" class="btn btn-cta margin-top-1em">Complete your profile</a>';
                                } else if (!empty($GrowerOperation->bio)) {
                                    echo '<div class="callout">';
                                    echo "<div>{$GrowerOperation->bio}</div>";
                                    echo '</div>';
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php

                } else {
                    echo 'Oops! This URL does not belong to an active item.';
                }

            ?>
            </div>
        </main>
    <!-- </div> -->
<!-- </div> -->

<script>
    var buyer_lat   = <?php echo (isset($User->delivery_latitude)) ? $User->delivery_latitude : 0; ?>;
    var buyer_lng   = <?php echo (isset($User->delivery_longitude)) ? $User->delivery_longitude : 0; ?>;
    var seller_lat  = <?php echo (isset($GrowerOperation)) ? number_format($GrowerOperation->latitude, 2) : 0; ?>;
    var seller_lng  = <?php echo (isset($GrowerOperation)) ? number_format($GrowerOperation->longitude, 2) : 0; ?>;
</script>