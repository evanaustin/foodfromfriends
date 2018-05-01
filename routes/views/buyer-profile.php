<main>
    <div class="main container">
        <?php
        
        if (isset($BuyerAccount->id)) {
            
            if ($is_owner) {

                ?>

                <div class="alerts" style="display:block;">
                    <div class="alert alert-info">
                        <span>This is your public profile. Click <a href="<?= PUBLIC_ROOT . 'dashboard/buying/settings/profile'; ?>">here</a> to go edit your information.</span>
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
                                    
                            if (isset($BuyerAccount->Image->filename)) {
                                echo '<a href="#" data-toggle="modal" data-target="#img-zoom-modal">';

                                img(ENV . "/{$BuyerAccount->Image->path}/{$BuyerAccount->Image->filename}", $BuyerAccount->Image->ext /* . '?' . time() */, [
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
                                    echo "<a href=\"" . PUBLIC_ROOT . "dashboard/buying/settings/profile\" class=\"btn btn-cta btn-block\">Set your profile picture</a>";
                                }
                            }

                            ?>
                        </div>
                        
                        <div class="<?= ($BuyerAccount->Address ? 'map' : 'photo'); ?> box">
                            <?php
                                    
                            if (isset($BuyerAccount->Address->latitude, $BuyerAccount->Address->longitude)) {
                                echo "<div id=\"map\"></div>";
                            } else {
                                img('placeholders/location-thumbnail', 'jpg', [
                                    'server'    => 'local', 
                                    'class'     => 'img-fluid rounded'
                                ]);

                                if ($is_owner) {
                                    echo "<a href=\"" . PUBLIC_ROOT . "dashboard/buying/settings/profile\" class=\"btn btn-cta btn-block\">Set your address</a>";
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
                            
                            echo $BuyerAccount->name;

                            if (isset($User->GrowerOperation) && $User->GrowerOperation->is_active && !$is_owner) {
                                
                                ?>

                                <a href="<?= PUBLIC_ROOT . 'dashboard/messages/inbox/selling/thread?buyer=' . $BuyerAccount->id; ?>">
                                    <div id="message" class="float-right btn btn-primary" data-toggle="tooltip" data-placement="bottom" data-title="Message">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                </a>

                                <?php

                            }

                            ?>
                        </h2>

                        <div class="muted normal margin-btm-25em">
                            <?= (isset($BuyerAccount->Address->city, $BuyerAccount->Address->state)) ? "{$BuyerAccount->Address->city}, {$BuyerAccount->Address->state}" : ''; ?>
                        </div>

                        <div class="muted bold margin-btm-1em">
                            <?= 'Joined in ' . $joined_on->format('F\, Y'); ?>
                        </div>

                        <?php
                        
                        if (!empty($BuyerAccount->bio)) {
                            echo "<p class=\"muted margin-btm-2em\">{$BuyerAccount->bio}</p>";
                        } else if ($is_owner) {
                            echo "<a href=\"" . PUBLIC_ROOT . "dashboard/selling/settings/profile\" class=\"btn btn-cta\">Add a bio</a>";
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
                                    echo "Items on {$BuyerAccount->first_name}'s wish list";
                                }

                                ?>
                            </div>

                            <?php
                            
                            if (!empty($wishlist)) {
                                foreach ($wishlist as $category_id => $category) {
                                    
                                    ?>
                                    
                                    <div class="callout margin-btm-1em">
                                        <h4 class="strong">
                                            <?= ucfirst($category['title']); ?>
                                            <light class="light-gray">(<?= count($category['subcategories']); ?>)</light>
                                        </h4>

                                        <?php
                                    
                                        foreach ($category['subcategories'] as $subcategory_id => $subcategory) {
                                            if ($is_owner) {
                                                echo "<a class=\"btn btn-white\" href=\"" . PUBLIC_ROOT . "map\" data-toggle=\"tooltip\" data-title=\"See what's for sale\">" . ucfirst($subcategory['title']) . "</a>";
                                            } else {
                                                echo "<a class=\"btn btn-white offer-item\" href=\"" . PUBLIC_ROOT . "dashboard/selling/items/add-new?category={$category_id}&subcategory={$subcategory_id}\" data-toggle=\"tooltip\" data-title=\"Offer {$subcategory['title']} for sale\">" . ucfirst($subcategory['title']) . "</a>";
                                            }
                                        }

                                        ?>
                                    
                                    </div>

                                    <?php

                                }
                            } else {
                                echo "<div class=\"callout\">{$BuyerAccount->name} doesn't have a wish list right now</div>";
                                
                                if ($is_owner) {
                                    echo "<a href=\"" . PUBLIC_ROOT . "dashboard/buying/orders/wish-list\" class=\"btn btn-cta margin-top-1em\">Build your wish list</a>";
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
                                        'id' => $rating['buyer_account_id']
                                    ]);

                                    ?>           
                                    
                                    <div class="user-block margin-btm-1em">                  
                                        <div class="user-photo" style="background-image: url(<?= (!empty($ReviewUser->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ReviewUser->filename . '.' . $ReviewUser->ext : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>
                                        
                                        <div class="user-content">
                                            <p class="muted margin-btm-25em">
                                                &quot;<?= $rating['review']; ?>&quot;
                                            </p>

                                            <small class="dark-gray bold flexstart">
                                                <?= "{$ReviewUser->first_name} &bull; {$ReviewUser->city}, {$ReviewUser->state}"; ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <?php

                                }
                            } else {
                                echo "<div class=\"callout\">{$BuyerAccount->first_name} doesn't have any reviews yet</div>";
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
    var lat = <?= (isset($BuyerAccount->Address, $BuyerAccount->Address->latitude)) ? number_format($BuyerAccount->Address->latitude, 2) : 0; ?>;
    var lng = <?= (isset($BuyerAccount->Address, $BuyerAccount->Address->longitude)) ? number_format($BuyerAccount->Address->longitude, 2) : 0; ?>;
</script>