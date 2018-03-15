<main>
    <div class="main container">
        <?php
        
        if ((isset($GrowerOperation) && $GrowerOperation->is_active) || $is_owner) {

            if ($is_owner) {

                ?>

                <div class="alerts" style="display:block;">
                    <div class="alert alert-<?php echo (($GrowerOperation->is_active) ? 'info' : 'warning'); ?>">

                        <?php

                        if ($GrowerOperation->is_active) {
                            echo '<span>This is your public profile. Click <a href="' . PUBLIC_ROOT . 'dashboard/grower/settings/edit-profile">here</a> to go edit your information.</span>';
                        } else {
                            echo '<span><i class="fa fa-warning"></i> This is only a preview of your seller profile. Click <a href="' . PUBLIC_ROOT . 'dashboard/grower">here</a> to finish activating your seller account.</span>';
                        }

                        ?>

                        <a class="close" data-dismiss="alert">×</a>
                    </div>
                </div>

                <?php

            }
            
            ?>

            <div class="row">   
                <div class="col-12 order-2 col-lg-3 order-lg-1">
                    <div class="sidebar-content">
                        <div class="photo box">
                            <?php
                            
                            if (!empty($GrowerOperation->filename)) {
                                img(ENV . "/grower-operation-images/{$GrowerOperation->filename}", $GrowerOperation->ext, 'S3', 'img-fluid');
                            } else {
                                img('placeholders/user-thumbnail', 'jpg', 'local', 'img-fluid rounded');

                                if ($is_owner) {
                                    echo '<a href="' . PUBLIC_ROOT . 'dashboard/grower/settings/edit-profile" class="btn btn-cta btn-block">Add a profile picture</a>';
                                }
                            }

                            ?>
                        </div>
                        
                        <div class="details box">
                            <ul class="list-group">
                                <li class="list-group-item heading">
                                    <span>Item exchange options:</span>
                                </li>

                                <ul class="list-group">
                                    <li class="list-group-item sub">
                                        <fable>
                                            <cell class="<?php if (!$GrowerOperation->Delivery || !$GrowerOperation->Delivery->is_offered) { echo 'inactive'; } ?>">Delivery</cell>
                                            
                                            <cell class="flexend">
                                                <?php
                                                
                                                if ($GrowerOperation->Delivery && $GrowerOperation->Delivery->is_offered) {
                                                    echo '<i class="fa fa-check"></i>';
                                                } else {
                                                    echo ($is_owner) ? '<a href="' . PUBLIC_ROOT . 'dashboard/grower/exchange-options/delivery" class="btn btn-cta btn-block">Enable</a>' : '<i class="fa fa-times"></i>';
                                                }
                                                
                                                ?>
                                            </cell>
                                        </fable>
                                    </li>

                                    <li class="list-group-item sub">
                                        <fable>
                                            <cell class="<?php if (!$GrowerOperation->Pickup || !$GrowerOperation->Pickup->is_offered) { echo 'inactive'; } ?>">Pickup</cell>
                                        
                                            <cell class="flexend">
                                                <?php
                                                
                                                if ($GrowerOperation->Pickup && $GrowerOperation->Pickup->is_offered) {
                                                    echo '<i class="fa fa-check"></i>';
                                                } else {
                                                    echo ($is_owner) ? '<a href="' . PUBLIC_ROOT . 'dashboard/grower/exchange-options/pickup" class="btn btn-cta">Enable</a>' : '<i class="fa fa-times"></i>';
                                                }
                                                
                                                ?>
                                            </cell>
                                        </fable>
                                    </li>

                                    <li class="list-group-item sub">
                                        <fable>
                                            <cell class="<?php if (!$GrowerOperation->Meetup || !$GrowerOperation->Meetup->is_offered) { echo 'inactive'; } ?>">Meetup</cell>
                                            
                                            <cell class="flexend">
                                                <?php
                                                
                                                if ($GrowerOperation->Meetup && $GrowerOperation->Meetup->is_offered) {
                                                    echo '<i class="fa fa-check"></i>';
                                                } else {
                                                    echo ($is_owner) ? '<a href="' . PUBLIC_ROOT . 'dashboard/grower/exchange-options/meetup" class="btn btn-cta btn-block">Enable</a>' : '<i class="fa fa-times"></i>';
                                                }
                                                
                                                ?>
                                            </cell>
                                        </fable>
                                    </li>
                                </ul>
                            </ul>
                        </div>

                        <div class="<?php echo (isset($GrowerOperation->latitude, $GrowerOperation->longitude) ? 'map' : 'photo'); ?> box">
                            <?php
                                    
                            if (isset($GrowerOperation->latitude, $GrowerOperation->longitude)) {
                                echo "<div id=\"map\"></div>";
                            } else {
                                img('placeholders/location-thumbnail', 'jpg', 'local', 'img-fluid rounded');

                                if ($is_owner) {
                                    echo '<a href="' . PUBLIC_ROOT . 'dashboard/grower/settings/edit-profile" class="btn btn-cta btn-block">Set your address</a>';
                                }
                            }

                            ?>
                        </div>
                    </div>
                </div>
            
                <div class="col-12 order-1 col-lg-9 order-lg-2">
                    <div id="main-content">
                        <h2 class="dark-gray bold margin-btm-25em">
                            <?php
                            
                            echo $GrowerOperation->name;

                            if (!$is_owner) {

                                ?>

                                <a href="<?php echo PUBLIC_ROOT . 'dashboard/messages/inbox/buying/thread?grower=' . $GrowerOperation->id; ?>">
                                    <div id="message" class="float-right btn btn-primary" data-toggle="tooltip" data-placement="bottom" data-title="Message">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                </a>

                                <?php
                            
                            }

                            ?>
                        </h2>

                        <div class="muted normal margin-btm-25em">
                            <?php echo "<span class=\"brand\">{$grower_stars}</span>" . (count($ratings) > 0 ? "<div class=\"rounded-circle\">" . count($ratings) . "</div>" : " ") . (isset($GrowerOperation->city, $GrowerOperation->state) ? "&bull; {$GrowerOperation->city}, {$GrowerOperation->state}" : '') . ((isset($distance) && $distance['length'] > 0) ? " &bull; {$distance['length']} {$distance['units']} away" : ""); ?>
                        </div>

                        <div class="muted bold margin-btm-1em">
                            <?php echo 'Joined in ' . $joined_on->format('F\, Y'); ?>
                        </div>

                        <?php
                        
                        if (!empty($GrowerOperation->bio)) {
                            echo "<p class=\"muted margin-btm-2em\">{$GrowerOperation->bio}</p>";
                        } else if ($is_owner) {
                            echo '<div class="row"><div class="col-md-4"><a href="' . PUBLIC_ROOT . 'dashboard/grower/settings/edit-profile" class="btn btn-cta">Add a bio</a></div></div>';
                        }
                        
                        ?>

                        <div class="items set">
                            <h4 class="margin-btm-50em ">
                                <bold class="dark-gray">Items</bold> 
                                <?php echo (!empty($listings)) ? '<light class="light-gray">(' . count($listings) . ')</light>' : ''; ?>
                            </h4>

                            <div class="muted margin-btm-1em">
                                Items for sale from <?php echo $GrowerOperation->name; ?>
                            </div>

                            <?php

                            if (!empty($listings)) {
                                echo '<div class="row">';
                                
                                foreach ($listings as $listing) {
                                    
                                    $Item = new FoodListing([
                                        'DB' => $DB,
                                        'id' => $listing['id']
                                    ]);

                                    ?>
                                    
                                    <div class="col-md-4">
                                        
                                        <div class="card animated zoomIn">
                                            <a href="<?php echo PUBLIC_ROOT . $GrowerOperation->link . '/' . $Item->link; ?>">
                                                <div class="card-img-top">
                                                    
                                                    <?php
                                                    
                                                    if (!empty($Item->filename)) {
                                                        img(ENV . '/items/' . $Item->filename, $Item->ext, 'S3', 'animated fadeIn hidden img-fluid');
                                                        
                                                        ?>

                                                        <div class="loading">
                                                            <i class="fa fa-circle-o-notch loading-icon"></i>
                                                        </div>

                                                        <?php

                                                    } else {
                                                        img('placeholders/default-thumbnail', 'jpg', 'local', 'animated fadeIn img-fluid rounded');
                        
                                                        if ($is_owner) {
                                                            echo "<a href=\"" . PUBLIC_ROOT . "dashboard/grower/items/edit?id={$Item->id}\" class=\"btn btn-cta btn-block margin-top-50em\">Add an item image</a>";
                                                        }
                                                    }

                                                    ?>
                                                
                                                </div>
                                            </a>

                                            <div class="card-body d-flex flex-row">
                                                <div class="listing-info d-flex flex-column">
                                                    <h5 class="dark-gray bold margin-btm-50em">
                                                        <a href="<?php echo PUBLIC_ROOT . $GrowerOperation->link . '/' . $Item->link; ?>">
                                                            <?php echo $Item->title; ?>
                                                        </a>
                                                    </h5>
                                                    
                                                    <h6 class="muted normal margin-btm-50em">
                                                        <span class="brand">
                                                            <?php echo stars($Item->average_rating); ?>
                                                        </span>

                                                        &nbsp;&bull;&nbsp;

                                                        <?php echo '$' . number_format($Item->price / 100, 2) . ' • $' . number_format(($Item->price / $Item->weight) / 100, 2) . '/' . $Item->units; ?> 
                                                    </h6>

                                                    <p class="card-text">
                                                        <?php
                                                            if (!$Item->is_available) {
                                                                // $niblet = 'bg-faded text-muted';
                                                                // $availability = 'text-muted';

                                                                echo 'Unavailable';
                                                            } else {
                                                                $niblet = 'text-white';
                                                                $availability = 'text-success';

                                                                if ($Item->quantity == 0) {
                                                                    $niblet .= ' bg-danger';
                                                                } else if ($Item->quantity > 0 && $Item->quantity < 6) {
                                                                    $niblet .= ' bg-warning';
                                                                } else if ($Item->quantity > 5) {
                                                                    $niblet .= ' bg-success';
                                                                }

                                                                echo "<span class=\"quantity {$niblet}\">{$Item->quantity}</span> in stock";
                                                            }

                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php

                                }

                                echo '</div>';
                            } else {
                                echo "<div class=\"callout\">{$GrowerOperation->name} doesn't have any items for sale yet</div>";
                                
                                if ($is_owner) {
                                    echo '<a href="' . PUBLIC_ROOT . 'dashboard/grower/items/add-new" class="btn btn-cta margin-top-1em">Add your first item</a>';
                                }
                            }

                            ?>

                            </div>
                        </div>
                        
                        <?php

                        if (!empty($ratings)) {

                            ?>

                            <div class="reviews set">
                                <h4 class="margin-btm-50em ">
                                    <bold class="dark-gray">Reviews</bold> 
                                    <light class="light-gray">(<?php echo count($ratings); ?>)</light>
                                </h4>
                                
                                <div class="muted margin-btm-1em">
                                    Ratings & reviews from customers
                                </div>

                                <?php 
                                
                                foreach ($ratings as $rating) { 
                                
                                    $ReviewUser = new User([
                                        'DB' => $DB,
                                        'id' => $rating['user_id']
                                    ]);

                                    ?>           
                                    
                                    <div class="user-block margin-btm-1em">                  
                                        <div class="user-photo" style="background-image: url(<?php echo (!empty($ReviewUser->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ReviewUser->filename . '.' . $ReviewUser->ext /* . '?' . time() */: PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>
                                        
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
                    </div>
                </div>
            </div>
            
            <?php

        } else {
            echo 'Oops! This URL does not belong to an active seller.';
        }

    ?>
    </div>
</main>

<script>
    var lat = <?php echo (isset($GrowerOperation)) ? number_format($GrowerOperation->latitude, 2) : 0; ?>;
    var lng = <?php echo (isset($GrowerOperation)) ? number_format($GrowerOperation->longitude, 2) : 0; ?>;

    var user = <?php echo (isset($User)) ? $User->id : 0; ?>
</script>