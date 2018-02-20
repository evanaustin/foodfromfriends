<!-- <div class="container-fluid">
    <div class="row"> -->
        <!-- <main class="col-md-12"> -->
        <main>
            <div class="main container">
                <?php
                
                if (isset($GrowerOperation) && $GrowerOperation->is_active) {
                    
                    ?>

                    <div class="row">   
                        <div class="col-lg-3">
                            <div id="sidebar-content">
                                <div class="photo box">
                                    <?php img(ENV . $GrowerOperation->details['path'], $GrowerOperation->details['ext'], 'S3', 'img-fluid'); ?>
                                </div>
                                
                                <div class="map box">
                                    <div id="map"></div>
                                </div>

                                <div class="details box">
                                    <ul class="list-group">
                                        <li class="list-group-item heading">
                                            <span>Food exchange options:</span>
                                        </li>

                                        <ul class="list-group">
                                            <li class="list-group-item sub">
                                                <span class="<?php if (!$GrowerOperation->Delivery || !$GrowerOperation->Delivery->is_offered) { echo 'inactive'; } ?>">Delivery</span>
                                                
                                                <div class="float-right">
                                                    <?php if ($GrowerOperation->Delivery && $GrowerOperation->Delivery->is_offered) { ?>
                                                        <i class="fa fa-check"></i>
                                                    <?php } else { ?>
                                                        <i class="fa fa-times"></i>
                                                    <?php } ?>
                                                </div>
                                            </li>

                                            <li class="list-group-item sub">
                                                <span class="<?php if (!$GrowerOperation->Pickup || !$GrowerOperation->Pickup->is_offered) { echo 'inactive'; } ?>">Pickup</span>
                                                
                                                <div class="float-right">
                                                    <?php if ($GrowerOperation->Pickup && $GrowerOperation->Pickup->is_offered) { ?>
                                                        <i class="fa fa-check"></i>
                                                    <?php } else { ?>
                                                        <i class="fa fa-times"></i>
                                                    <?php } ?>
                                                </div>
                                            </li>

                                            <li class="list-group-item sub">
                                                <span class="<?php if (!$GrowerOperation->Meetup || !$GrowerOperation->Meetup->is_offered) { echo 'inactive'; } ?>">Meetup</span>
                                                
                                                <div class="float-right">
                                                    <?php if ($GrowerOperation->Meetup && $GrowerOperation->Meetup->is_offered) { ?>
                                                        <i class="fa fa-check"></i>
                                                    <?php } else { ?>
                                                        <i class="fa fa-times"></i>
                                                    <?php } ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-lg-9">
                            <div id="main-content">
                                <h2 class="dark-gray bold margin-btm-25em">
                                    <?php echo $GrowerOperation->name; ?>

                                    <a href="<?php echo PUBLIC_ROOT . 'dashboard/messages/inbox/buying/thread?grower=' . $GrowerOperation->id; ?>">
                                        <div id="message" class="float-right btn btn-primary" data-toggle="tooltip" data-placement="bottom" data-title="Message">
                                            <i class="fa fa-envelope"></i>
                                        </div>
                                    </a>
                                </h2>

                                <div class="muted normal margin-btm-25em">
                                    <?php echo "<span class=\"brand\">{$grower_stars}</span>" . (count($ratings) > 0 ? "<div class=\"rounded-circle\">" . count($ratings) . "</div>" : " ") . "&bull; {$GrowerOperation->details['city']}, {$GrowerOperation->details['state']}" . ((isset($distance) && $distance['length'] > 0) ? " &bull; {$distance['length']} {$distance['units']} away" : ""); ?>
                                </div>

                                <div class="muted bold margin-btm-1em">
                                    <?php echo 'Joined in ' . $joined_on->format('F\, Y'); ?>
                                </div>

                                <?php
                                
                                if (!empty($GrowerOperation->details['bio'])) {
                                    echo "<p class=\"muted margin-btm-2em\">{$GrowerOperation->details['bio']}</p>";
                                }
                                
                                ?>

                                <div class="food-listings set">
                                    <h4 class="margin-btm-50em ">
                                        <bold class="dark-gray">Items</bold> 
                                        <light class="light-gray">(<?php echo count($listings); ?>)</light>
                                    </h4>

                                    <div class="muted margin-btm-1em">
                                        Food for sale from <?php echo $GrowerOperation->name; ?>
                                    </div>

                                    <div class='row'>

                                        <?php

                                        foreach ($listings as $listing) {
                                            
                                            $Item = new FoodListing([
                                                'DB' => $DB,
                                                'id' => $listing['id']
                                            ]);

                                            ?>
                                            
                                            <div class="col-md-4 <?php /* echo $tile_width; */ ?>">
                                                <a href="<?php echo PUBLIC_ROOT . $GrowerOperation->link . '/' . $Item->link; ?>">
                                                    <div class="card animated zoomIn">
                                                        <div class="card-img-top">
                                                            <?php img(ENV . '/food-listings/' . $Item->filename, $Item->ext, 'S3', 'animated fadeIn hidden'); ?>
                                                        
                                                            <div class="loading">
                                                                <i class="fa fa-circle-o-notch loading-icon"></i>
                                                            </div>
                                                        </div>

                                                        <div class="card-block d-flex flex-row">
                                                            <div class="listing-info d-flex flex-column">
                                                                <h5 class="dark-gray bold margin-btm-50em">
                                                                    <?php echo $Item->title; ?>
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
                                                </a>
                                            </div>

                                            <?php

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
                                                <div class="user-photo" style="background-image: url(<?php echo (!empty($ReviewUser->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ReviewUser->filename . '.' . $ReviewUser->ext /* . '?' . time() */: PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg'); ?>);"></div>
                                                
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
                    echo 'Oops! This URL does not belong to an active grower.';
                }

            ?>
            </div>
        </main>
    <!-- </div> -->
<!-- </div> -->

<script>
    var lat = <?php echo (isset($GrowerOperation)) ? number_format($GrowerOperation->details['lat'], 2) : 0; ?>;
    var lng = <?php echo (isset($GrowerOperation)) ? number_format($GrowerOperation->details['lng'], 2) : 0; ?>;

    var user = <?php echo (isset($User)) ? $User->id : 0; ?>
</script>