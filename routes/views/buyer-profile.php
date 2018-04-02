<main>
    <div class="main container">
        <?php
        
        if (isset($ThisUser->id)) {
            
            if ($is_owner) {

                ?>

                <div class="alerts" style="display:block;">
                    <div class="alert alert-info">
                        <span>This is your public profile. Click <a href="<?php echo PUBLIC_ROOT . 'dashboard/account/edit-profile/basic-information'; ?>">here</a> to go edit your information.</span>
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
                                    
                            if (isset($ThisUser->filename)) {
                                echo '<a href="#" data-toggle="modal" data-target="#img-zoom-modal">';

                                img(ENV . '/profile-photos/' . $ThisUser->filename, $ThisUser->ext . '?' . time(), [
                                    'server'    => 'S3',
                                    'class'     => 'img-fluid'
                                ]);
                                
                                echo '</a>';
                            } else {
                                img('placeholders/user-thumbnail', 'jpg', [
                                    'server'    => 'local', 
                                    'class'     => 'img-fluid rounded'
                                ]);

                                if ($is_owner) {
                                    echo "<a href=\"" . PUBLIC_ROOT . "dashboard/account/edit-profile/basic-information\" class=\"btn btn-cta btn-block\">Set your profile picture</a>";
                                }
                            }

                            ?>
                        </div>
                        
                        <div class="<?php echo (isset($ThisUser->latitude, $ThisUser->longitude) ? 'map' : 'photo'); ?> box">
                            <?php
                                    
                            if (isset($ThisUser->latitude, $ThisUser->longitude)) {
                                echo "<div id=\"map\"></div>";
                            } else {
                                img('placeholders/location-thumbnail', 'jpg', [
                                    'server'    => 'local', 
                                    'class'     => 'img-fluid rounded'
                                ]);

                                if ($is_owner) {
                                    echo "<a href=\"" . PUBLIC_ROOT . "dashboard/account/edit-profile/basic-information\" class=\"btn btn-cta btn-block\">Set your address</a>";
                                }
                            }

                            ?>
                        </div>
                    </div>
                </div>
            
                <div class="col-12 order-1 col-lg-9 order-lg-2">
                    <div id="main-content" class="margin-btm-1em">
                        <h2 class="dark-gray bold margin-btm-25em">
                            <?php
                            
                            echo $ThisUser->name;

                            if (isset($User->GrowerOperation) && $User->GrowerOperation->is_active && !$is_owner) {
                                
                                ?>

                                <a href="<?php echo PUBLIC_ROOT . 'dashboard/messages/inbox/selling/thread?buyer=' . $ThisUser->id; ?>">
                                    <div id="message" class="float-right btn btn-primary" data-toggle="tooltip" data-placement="bottom" data-title="Message">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                </a>

                                <?php

                            }

                            ?>
                        </h2>

                        <div class="muted normal margin-btm-25em">
                            <?php echo (isset($ThisUser->city, $ThisUser->state)) ? "{$ThisUser->city}, {$ThisUser->state}" : ''; ?>
                        </div>

                        <div class="muted bold margin-btm-1em">
                            <?php echo 'Joined in ' . $joined_on->format('F\, Y'); ?>
                        </div>

                        <?php
                        
                        if (!empty($ThisUser->bio)) {
                            echo "<p class=\"muted margin-btm-2em\">{$ThisUser->bio}</p>";
                        } else if ($is_owner) {
                            echo "<a href=\"" . PUBLIC_ROOT . "dashboard/account/edit-profile/basic-information\" class=\"btn btn-cta\">Add a bio</a>";
                        }
                        
                            
                        ?>

                        <div class="wish-list set">
                            <h4 class="margin-btm-50em ">
                                <bold class="dark-gray">Wish list</bold> 
                            </h4>

                            <div class="muted margin-btm-1em">
                                <?php 
                                
                                if (isset($wishlist_description)) {
                                    echo $wishlist_description['description'];
                                } else {
                                    echo "Items on {$ThisUser->first_name}'s wish list";
                                }

                                ?>
                            </div>

                            <?php
                            
                            if (!empty($wishlist)) {
                                foreach ($wishlist as $category_id => $category) {
                                    
                                    ?>
                                    
                                    <div class="callout margin-btm-1em">
                                        <h4 class="strong">
                                            <?php echo ucfirst($category['title']); ?>
                                            <light class="light-gray">(<?php echo count($category['subcategories']); ?>)</light>
                                        </h4>

                                        <?php
                                    
                                        foreach ($category['subcategories'] as $subcategory_id => $subcategory) {
                                            if ($is_owner) {
                                                echo "<a class=\"btn btn-white\" href=\"" . PUBLIC_ROOT . "map\" data-toggle=\"tooltip\" data-title=\"See what's for sale\">" . ucfirst($subcategory['title']) . "</a>";
                                            } else {
                                                echo "<a class=\"btn btn-white offer-item\" href=\"" . PUBLIC_ROOT . "dashboard/grower/items/add-new?category={$category_id}&subcategory={$subcategory_id}\" data-toggle=\"tooltip\" data-title=\"Offer {$subcategory['title']} for sale\">" . ucfirst($subcategory['title']) . "</a>";
                                            }
                                        }

                                        ?>
                                    
                                    </div>

                                    <?php

                                }
                            } else {
                                echo "<div class=\"callout\">{$ThisUser->first_name} doesn't have a wish list right now</div>";
                                
                                if ($is_owner) {
                                    echo "<a href=\"" . PUBLIC_ROOT . "dashboard/account/buying/wish-list\" class=\"btn btn-cta margin-top-1em\">Build your wish list</a>";
                                }
                            }

                            ?>

                        </div>
                        
                        <!-- <div class="reviews set">
                            <h4 class="margin-btm-50em ">
                                <bold class="dark-gray">Reviews</bold> 
                                <?php // echo (!empty($ratings)) ? '<light class="light-gray">(' . count($ratings) . ')</light>' : ''; ?>
                            </h4>
                            
                            <div class="muted margin-btm-1em">
                                Ratings & reviews by sellers
                            </div>

                            <?php 
                            
                            /* if (!empty($ratings)) {
                                foreach ($ratings as $rating) { 
                                
                                    $ReviewUser = new User([
                                        'DB' => $DB,
                                        'id' => $rating['user_id']
                                    ]);

                                    ?>           
                                    
                                    <div class="user-block margin-btm-1em">                  
                                        <div class="user-photo" style="background-image: url(<?php echo (!empty($ReviewUser->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ReviewUser->filename . '.' . $ReviewUser->ext : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>
                                        
                                        <div class="user-content">
                                            <p class="muted margin-btm-25em">
                                                &quot;<?php echo $rating['review']; ?>&quot;
                                            </p>

                                            <small class="dark-gray bold flexstart">
                                                <?php echo "{$ReviewUser->first_name} &bull; {$ReviewUser->city}, {$ReviewUser->state}"; ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <?php

                                }
                            } else {
                                echo "<div class=\"callout\">{$ThisUser->first_name} doesn't have any reviews yet</div>";
                            } */

                            ?>

                        </div> -->
                    </div>
                </div>
            </div>
            
            <?php

        } else {
            echo 'Oops! This URL does not belong to an active buyer.';
        }

    ?>
    </div>
</main>

<script>
    var lat = <?php echo (isset($ThisUser->latitude)) ? number_format($ThisUser->latitude, 2) : 0; ?>;
    var lng = <?php echo (isset($ThisUser->longitude)) ? number_format($ThisUser->longitude, 2) : 0; ?>;

    var user = <?php echo (isset($User)) ? $User->id : 0; ?>
</script>