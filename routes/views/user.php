<div class="container-fluid">
    <div class="row">
        <main class="col-md-12">
            <div class="main container">
                <div class="row">   
                    <div class="col-lg-3">
                        <div class="left-content">
                            <div class="profile-photo box">
                                <img src="<?php echo (!empty($ThisUser->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ThisUser->filename . '.' . $ThisUser->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg'); ?>">
                            </div>

                            <div class="details box">
                                <ul class="list-group">
                                    <li class="list-group-item heading">
                                        <span>Verified info:</span>
                                    </li>

                                    <ul class="list-group">
                                        <li class="list-group-item sub">
                                            <span class="<?php if (!isset($ThisUser->email)) { echo 'inactive'; } ?>">Email address</span>
                                            
                                            <div class="float-right">
                                                <?php if (isset($ThisUser->email)) { ?>
                                                    <i class="fa fa-check"></i>
                                                <?php } else { ?>
                                                    <i class="fa fa-times"></i>
                                                <?php } ?>
                                            </div>
                                        </li>

                                        <li class="list-group-item sub">
                                            <span class="<?php if (!isset($ThisUser->phone)) { echo 'inactive'; } ?>">Phone number</span>
                                            
                                            <div class="float-right">
                                                <?php if (isset($ThisUser->phone)) { ?>
                                                    <i class="fa fa-check"></i>
                                                <?php } else { ?>
                                                    <i class="fa fa-times"></i>
                                                <?php } ?>
                                            </div>
                                        </li>

                                        <li class="list-group-item sub">
                                            <span class="<?php if (!isset($ThisUser->zipcode)) { echo 'inactive'; } ?>">Location</span>
                                            
                                            <div class="float-right">
                                                <?php if (isset($ThisUser->zipcode)) { ?>
                                                    <i class="fa fa-check"></i>
                                                <?php } else { ?>
                                                    <i class="fa fa-times"></i>
                                                <?php } ?>
                                            </div>
                                        </li>
                                    </ul>

                                    <li class="list-group-item heading">
                                        <span>Food exchange options:</span>
                                    </li>

                                    <ul class="list-group">
                                        <li class="list-group-item sub">
                                            <span class="<?php if (!$delivery_offered) { echo 'inactive'; } ?>">Delivery</span>
                                            
                                            <div class="float-right">
                                                <?php if ($delivery_offered) { ?>
                                                    <i class="fa fa-check"></i>
                                                <?php } else { ?>
                                                    <i class="fa fa-times"></i>
                                                <?php } ?>
                                            </div>
                                        </li>

                                        <li class="list-group-item sub">
                                            <span class="<?php if (!$pickup_offered) { echo 'inactive'; } ?>">Pickup</span>
                                            
                                            <div class="float-right">
                                                <?php if ($pickup_offered) { ?>
                                                    <i class="fa fa-check"></i>
                                                <?php } else { ?>
                                                    <i class="fa fa-times"></i>
                                                <?php } ?>
                                            </div>
                                        </li>

                                        <li class="list-group-item sub">
                                            <span class="<?php if (!$meetup_offered) { echo 'inactive'; } ?>">Meetup</span>
                                            
                                            <div class="float-right">
                                                <?php if ($meetup_offered) { ?>
                                                    <i class="fa fa-check"></i>
                                                <?php } else { ?>
                                                    <i class="fa fa-times"></i>
                                                <?php } ?>
                                            </div>
                                        </li>
                                    </ul>
                                </ul>
                            </div>

                            <div class="map box">
                                <div id="map"></div>
                            </div>
                        </div> <!-- end div.left-content -->
                    </div>
                
                    <div class="col-lg-9">
                        <div class="right-content">
                            <div class="name">
                                <small>food from </small>
                                <span><?php echo $ThisUser->first_name; ?></span>
                            </div>

                            <div class="bio">
                                <?php echo $ThisUser->bio; ?>
                            </div>
                                
                            <div class="location">
                                <?php echo $ThisUser->city . ', ' . $ThisUser->state . (isset($distance) ? ' &bull; ' . $distance['length'] . ' ' . $distance['units'] . ' away' : ''); ?>
                            </div>

                            <div class="joined">
                                Joined in <?php echo date('F Y', $ThisUser->registered_on); ?>
                            </div>

                            <?php

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
                                    Available food from <?php echo $ThisUser->first_name; ?>
                                </div>

                                <div class='row'>

                                    <?php

                                    foreach ($listings as $listing) {

                                        ?>
                                        
                                        <div class="col-md-4">
                                        <!-- <div class="<?php echo $tile_width; ?>"> -->
                                            <div class="card animated zoomIn">
                                            
                                                <?php
                                            
                                                img(ENV . '/food-listings/' . $listing['filename'], $listing['ext'], 'S3', 'card-img-top');

                                                ?>

                                                <div class="card-block d-flex flex-row">
                                                    <div class="listing-info d-flex flex-column">
                                                        <h4 class="card-title">
                                                            <!-- <a href="<?php echo PUBLIC_ROOT . 'grower/food-listings/edit?id=' . $listing['id']; ?>"> -->
                                                                <?php echo ucfirst((empty($listing['other_subcategory']) ? ($listing['subcategory_title']) : $listing['other_subcategory'])); ?>
                                                            <!-- </a> -->
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

                                                                echo '<span class="quantity ' . $niblet . '">' . $listing['quantity'] . '</span> in stock';
                                                            ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
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
            </div>
        </main> <!-- end main -->
    </div> <!-- end div.row -->
</div> <!-- end div.container-fluid -->

<?php 
console_log($delivery_settings);
console_log($delivery_offered);
?>

<script>
    // PRIVACY BREACH
    var lat = <?php echo $ThisUser->latitude; ?>;
    var lng = <?php echo $ThisUser->longitude; ?>;
</script>