<div class="container-fluid">
    <div class="row">
        <main class="col-md-12">
            <div class="main container">
                <?php

                if ($GrowerOperation->is_active) {

                    ?>

                    <div class="row">   
                        <div class="col-lg-3">
                            <div class="sidebar-content">
                                <div class="photo box">
                                    <img class="img-fluid" src="<?php echo $filename; ?>">
                                </div>
                                
                                <div class="map box">
                                    <div id="map"></div>
                                </div>

                                <div class="details box">
                                    <ul class="list-group">
                                        <!-- <li class="list-group-item heading">
                                            <span>Verified info:</span>
                                        </li>

                                        <ul class="list-group">
                                            <li class="list-group-item sub">
                                                <span class="<?php if (!isset($GrowerOperation->email)) { echo 'inactive'; } ?>">Email address</span>
                                                
                                                <div class="float-right">
                                                    <?php if (isset($GrowerOperation->email)) { ?>
                                                        <i class="fa fa-check"></i>
                                                    <?php } else { ?>
                                                        <i class="fa fa-times"></i>
                                                    <?php } ?>
                                                </div>
                                            </li>

                                            <li class="list-group-item sub">
                                                <span class="<?php if (!isset($GrowerOperation->phone)) { echo 'inactive'; } ?>">Phone number</span>
                                                
                                                <div class="float-right">
                                                    <?php if (isset($GrowerOperation->phone)) { ?>
                                                        <i class="fa fa-check"></i>
                                                    <?php } else { ?>
                                                        <i class="fa fa-times"></i>
                                                    <?php } ?>
                                                </div>
                                            </li>

                                            <li class="list-group-item sub">
                                                <span class="<?php if (!isset($GrowerOperation->zipcode)) { echo 'inactive'; } ?>">Location</span>
                                                
                                                <div class="float-right">
                                                    <?php if (isset($GrowerOperation->zipcode)) { ?>
                                                        <i class="fa fa-check"></i>
                                                    <?php } else { ?>
                                                        <i class="fa fa-times"></i>
                                                    <?php } ?>
                                                </div>
                                            </li>
                                        </ul> -->

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
                            </div> <!-- end div.left-content -->
                        </div>
                    
                        <div class="col-lg-9">
                            <div class="main-content">
                                <div class="name">
                                    <small>food from </small>
                                    <span><?php echo $name; ?></span>
                                </div>

                                <?php
                                
                                if (!empty($bio)) {

                                    ?>
                                
                                    <div class="bio">
                                        <?php echo $bio; ?>
                                    </div>
                                        
                                    <div class="location">
                                        <?php echo $city . ', ' . $state . (isset($distance) ? ' &bull; ' . $distance['length'] . ' ' . $distance['units'] . ' away' : ''); ?>
                                    </div>

                                    <div class="joined">
                                        Joined in <?php echo date('F Y', $joined_on); ?>
                                    </div>

                                    <?php
                                
                                }

                                if (!empty($reviews)) {

                                    ?>

                                    <div class="review-count">
                                        <div><?php echo count($reviews); ?></div>
                                        <strong>Reviews</strong>
                                    </div>

                                    <?php
                                
                                }

                                ?>

                                <div class="available-food-listings set">
                                    <div class="title">
                                        <strong>Food listings</strong> 
                                        (<?php echo count($listings); ?>)
                                    </div>

                                    <div class="subtitle">
                                        Available food from <?php echo $name; ?>
                                    </div>

                                    <div class='row'>

                                        <?php

                                        foreach ($listings as $listing) {

                                            ?>
                                            
                                            <div class="col-md-4">
                                            <!-- <div class="<?php //echo $tile_width; ?>"> -->
                                                <a href="<?php echo PUBLIC_ROOT . 'food-listing?id=' . $listing['id']; ?>" class="card animated zoomIn">
                                                    <div class="card-img-top">
                                                        <?php img(ENV . '/food-listings/' . $listing['filename'], $listing['ext'], 'S3', 'animated fadeIn hidden'); ?>
                                                    
                                                        <div class="loading">
                                                            <i class="fa fa-circle-o-notch loading-icon"></i>
                                                        </div>
                                                    </div>

                                                    <div class="card-block d-flex flex-row">
                                                        <div class="listing-info d-flex flex-column">
                                                            <h5 class="card-title">
                                                                <?php echo ucfirst((empty($listing['other_subcategory']) ? ($listing['subcategory_title']) : $listing['other_subcategory'])); ?>
                                                            </h5>
                                                            
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

                                                                    echo '<span class="quantity ' . $niblet . '">' . $listing['quantity'] . '</span> in stock';
                                                                ?>
                                                            </p>
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

                                if (!empty($reviews)) {

                                    ?>

                                    <div class="reviews set">
                                        <div class="title">
                                            <strong>Reviews</strong>
                                            <?php echo '(' . count($reviews) . ')'; ?>
                                        </div>

                                        <div class="subtitle">
                                            Reviews from customers
                                        </div>

                                        <?php 
                                        
                                        foreach ($reviews as $review) { 
                                        
                                            $ReviewUser = new User([
                                                'DB' => $DB,
                                                'id' => $review['reviewer_id']
                                            ]);

                                            ?>           
                                            
                                            <div class="review-block">                  
                                                <div class="reviewer-photo" style="background-image: url(<?php echo (!empty($ReviewUser->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ReviewUser->filename . '.' . $ReviewUser->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg'); ?>);"></div>
                                                
                                                <div class="review-content">
                                                    <div class="quote">
                                                        <?php echo $review['content']; ?>
                                                    </div>

                                                    <div class="reviewer-details">
                                                        <small><?php echo $ReviewUser->first_name . ' &bull; ' . $ReviewUser->city . ', ' . $ReviewUser->state; ?></small>
                                                    </div>
                                                    
                                                    <div class="reviewed-on">
                                                        <small><?php echo date('F Y', $review['reviewed_on']); ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <?php

                                            }

                                        ?>
                                    </div>

                                    <?php

                                }

                                ?>
                            </div> <!-- end div.right-content -->
                        </div>
                    </div>
                    
                    <?php

                } else {
                    echo 'Oops! This ID does not belong to an active grower.';
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