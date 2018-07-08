<main>
    <div class="main container">
        
        <?php if (isset($Item->id) && !isset($Item->archived_on) && ($SellerAccount->is_active || ($is_owner || (isset($User) && $User->id == 1)))): ?>

            <?php if ($is_owner): ?>

                <div class="alerts" style="display:block;">
                    <div class="alert alert-<?= (($SellerAccount->is_active) ? 'info' : 'warning'); ?>">

                        <?php if ($SellerAccount->is_active): ?>
                        
                            <span>This is what your item looks like to the public. Click <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/edit?id=<?= $Item->id ?>">here</a> to go edit this item.</span>
                        
                        <?php else: ?>

                            <span><i class="fa fa-warning"></i> This is only a preview of this item. Click <a href="<?= PUBLIC_ROOT ?>dashboard/selling/">here</a> to finish activating your seller account.</span>
                        
                        <?php endif; ?>

                        <a class="close" data-dismiss="alert">×</a>
                    </div>
                </div>

            <?php endif; ?>

            <div class="row">
                <div class="col-lg-3 order-lg-1 d-none d-md-block">
                    <div class="sidebar-content">
                        <div class="photo box">
                            <a href="#" class="<?php /* if (empty($Item->Image->filename)) echo 'hidden' */ ?>" data-toggle="modal" data-target="#img-zoom-modal">
                            
                                <?php if (!empty($Item->Image->filename)): ?>

                                    <?php img(ENV . '/item-images/' . $Item->Image->filename, $Item->Image->ext, [
                                        'server'    => 'S3',
                                        'class'     => 'img-fluid'
                                    ]); ?>
                                    
                                <?php else: ?>

                                    <?php img('placeholders/default-thumbnail', 'jpg', [
                                        'server'    => 'local', 
                                        'class'     => 'img-fluid rounded'
                                    ]); ?>

                                    <?php if ($is_owner): ?>
                                        
                                        <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/edit?id=<?= $Item->id ?>" class="btn btn-cta btn-block">
                                            Add an item image
                                        </a>
                                    
                                    <?php endif; ?>

                                <?php endif; ?>
                            
                            </a>

                        </div>
                        
                        <div class="<?= (isset($SellerAccount->latitude, $SellerAccount->longitude) ? 'map' : 'photo'); ?> box">
                            
                            <?php if (isset($SellerAccount->latitude, $SellerAccount->longitude)): ?>

                                <div id="map"></div>

                            <?php else: ?>

                                <?php img('placeholders/location-thumbnail', 'jpg', [
                                    'server'    => 'local', 
                                    'class'     => 'img-fluid rounded'
                                ]); ?>

                                <?php if ($is_owner): ?>

                                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/settings/profile" class="btn btn-cta btn-block">
                                        Set your address
                                    </a>
                                
                                <?php endif; ?>

                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <div class="col-12 order-1 col-lg-5 order-lg-1">
                    <div id="main-content">
                        <h2 class="dark-gray bold margin-btm-25em">
                            <?= $Item->title ?>
                        </h2>

                        <h6 class="muted normal margin-btm-1em">
                            <span class="brand">
                                <?= $item_stars ?>
                            </span>

                            <?php if (!empty($ratings) && count($ratings) > 0): ?>
                                
                                <div class="rounded-circle">
                                    <?= count($ratings) ?>
                                </div>

                            <?php endif; ?>
                            
                            <span>
                                &bull;
                                <?= ($wholesale_relationship && (isset($_GET['type']) && $_GET['type'] == 'wholesale')) ? 'Wholesale' : 'Retail' ?>
                            </span>
                        </h6>
                        
                        <div id="description" class="callout <?php if (empty($Item->description)) echo 'hidden' ?>">
                            <div>

                            <?php if (!empty($Item->description)): ?>    
                            
                                <?= $Item->description ?>
                            
                            <?php elseif ($is_owner): ?>

                                <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/edit?id=<?= $Item->id ?>" class="btn btn-cta">
                                    Add a description
                                </a>
                            
                            <?php endif ?>

                            </div>
                        </div>

                        <div class="available-exchange-options set d-none d-md-block">
                            <h4 class="margin-btm-50em">
                                <bold class="dark-gray">Exchange options</bold>

                                <?php /* if (!empty($exchange_options_available)): ?>
                                
                                    <light class="light-gray">
                                        (<?= count($exchange_options_available) ?>)
                                    </light>
                                
                                <?php endif; */ ?>

                            </h4>

                            <div class="muted margin-btm-1em">
                                Meetup locations or delivery options to get your food
                            </div>

                            <?php if (!empty($exchange_options_available)): ?>

                                <?php if ($SellerAccount->Delivery && $SellerAccount->Delivery->is_offered): ?>

                                    <div class="callout">
                                        <div class="muted font-18 thick">
                                            <i class="fa fa-truck" data-toggle="tooltip" data-title="Request that your order be delivered right to you"></i>
                                            Delivery
                                        </div>
                                        
                                        <div>
                                            Will deliver within: <?= $SellerAccount->Delivery->distance ?> miles
                                        </div>

                                        <?php if ($SellerAccount->Delivery->delivery_type == 'conditional'): ?>
                                            
                                            <div>Free delivery within: {$SellerAccount->Delivery->free_distance} miles</div>";

                                        <?php endif; ?>

                                        <div>
                                            <?= ($SellerAccount->Delivery->delivery_type == 'free' ? 'Free' : 'Rate: $' . number_format($SellerAccount->Delivery->fee / 100, 2) . ' ' . str_replace('-', ' ', $SellerAccount->Delivery->pricing_rate)); ?>
                                        </div>
                                    </div>

                                <?php endif; ?>

                                <?php if (!empty($meetups)): ?>
                            
                                    <?php foreach ($meetups as $meetup): ?>
                                    
                                        <div class="callout">
                                            <div class="muted font-18 thick">
                                                <i class="fa fa-map-signs" data-toggle="tooltip" data-title="Meet here at the time specified to pick up your order"></i>
                                                <?= (!empty($meetup['title'])) ? $meetup['title'] : "{$meetup['address_line_1']} {$meetup['address_line_2']}" ?>
                                            </div>
                                            
                                            <div class="<?php if (empty($meetup['title'])) echo 'hidden' ?>">
                                                <?= "{$meetup['address_line_1']} {$meetup['address_line_2']}" ?>
                                            </div>
                                            
                                            <div>
                                                <?= "{$meetup['city']}, {$meetup['state']}" ?>
                                            </div>
                                            
                                            <div>
                                                <?= "{$meetup['day']} &bull; {$meetup['start_time']} &ndash; {$meetup['end_time']}" ?>
                                            </div>
                                        </div>
                                
                                    <?php endforeach ?>

                                <?php endif ?>

                            <?php else: ?>

                                <div class="callout">
                                    <?= $SellerAccount->name ?> hasn't enabled any exchange options yet
                                </div>
                        
                                <?php if ($is_owner): ?>

                                    <div class="btn-group">
                                        <a href="<?= PUBLIC_ROOT ?>dashboard/selling/exchange-options/delivery" class="btn btn-cta">
                                            Enable delivery
                                        </a>

                                        <a href="<?= PUBLIC_ROOT ?>dashboard/selling/exchange-options/pickup" class="btn btn-cta">
                                            Enable pickup
                                        </a>

                                        <a href="<?= PUBLIC_ROOT ?>dashboard/selling/exchange-options/meetup" class="btn btn-cta">
                                            Enable meetup
                                        </a>
                                    </div>

                                <?php endif; ?>
                            
                            <?php endif; ?>
                            
                        </div>

                        <?php if (!empty($ratings) && count($ratings) > 0): ?>

                            <div class="reviews set">
                                <h4 class="margin-btm-50em ">
                                    <bold class="dark-gray">Reviews</bold> 
                                    <light class="light-gray">(<?= count($ratings); ?>)</light>
                                </h4>
                                
                                <div class="muted margin-btm-1em">
                                    Item reviews from customers
                                </div>

                                <?php foreach ($ratings as $rating): ?>

                                    <?php $ReviewUser = new BuyerAccount([
                                        'DB' => $DB,
                                        'id' => $rating['buyer_account_id']
                                    ]); ?>           
                                    
                                    <div class="user-block margin-btm-1em">
                                        <a href="<?= PUBLIC_ROOT . "{$ReviewUser->link}"; ?>">                
                                            <div class="user-photo" style="background-image: url(<?= (!empty($ReviewUser->Image->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/buyer-account-images/{$ReviewUser->Image->filename}.{$ReviewUser->Image->ext}" /* . '?' . time() */ : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>
                                        </a>

                                        <div class="user-content">
                                            <p class="muted margin-btm-25em">
                                                &quot;<?= $rating['review']; ?>&quot;
                                            </p>

                                            <small class="dark-gray bold flexstart">
                                                <a href="<?= PUBLIC_ROOT . $ReviewUser->link ?>" class="strong">
                                                    <?= $ReviewUser->name ?>
                                                </a>

                                                &nbsp;&bull;&nbsp;

                                                <?= "{$ReviewUser->Address->city}, {$ReviewUser->Address->state}" ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                        <div class="about-grower set d-none d-md-block">
                            <h4 class="margin-btm-50em ">
                                <bold class="dark-gray">About the seller</bold> 
                            </h4>

                            <div class="muted margin-btm-1em">
                                Get to know the <?= (($SellerAccount->type == 'individual') ? 'person' : 'people'); ?> growing your food
                            </div>

                            <div class="user-block margin-btm-50em">
                                <a href="<?= PUBLIC_ROOT . "{$SellerAccount->link}"; ?>">
                                    <div class="user-photo" style="background-image: url(<?= (!empty($SellerAccount->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$SellerAccount->filename}.{$SellerAccount->ext}" : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>    
                                </a>
                                
                                <div class="user-content">
                                    <div class="font-18 muted thick">    
                                        <a href="<?= PUBLIC_ROOT . $SellerAccount->link; ?>">
                                            <?= $SellerAccount->name; ?>
                                        </a>
                                    </div>
                                        
                                    <?php if ($SellerAccount->is_active): ?>

                                        <div class="font-85 muted bold margin-btm-50em">
                                            <?= $grower_stars?>

                                            &nbsp;&bull;&nbsp;

                                            <?= "{$SellerAccount->city}, {$SellerAccount->state}" ?>
                                        </div>
                                    
                                    <?php endif; ?>

                                </div>
                            </div>

                            <?php if (!$SellerAccount->is_active && $is_owner): ?>

                                <a href="<?= PUBLIC_ROOT ?>dashboard/selling" class="btn btn-cta margin-top-1em">
                                    Complete your profile
                                </a>

                                <?php elseif (!empty($SellerAccount->bio)): ?>

                                <div class="callout">
                                    <div><?= $SellerAccount->bio ?></div>
                                </div>

                            <?php endif ?>

                        </div>
                    </div>    
                </div>

                <div class="col-12 order-2 col-lg-4 order-lg-2">
                    <div id="basket-form-container" class="sticky-top">
                        <div class="box">
                            <div class="header">    
                                <?= _amount($Item->price) ?>
                            </div>

                            <div class="content">
                                <div class="alerts"></div>

                                <form id="update-cart">
                                    <input type="hidden" name="seller-id" value="<?= $SellerAccount->id; ?>">
                                    <input type="hidden" name="distance-miles"  value="<?php if (isset($distance_miles)) echo $distance_miles ?>"/>

                                    <div class="form-group">
                                        <label>
                                            Package option
                                        </label>
                                        
                                        <select name="item-id" class="custom-select" data-parsley-trigger="change" required>
                                            
                                            <?php foreach($items as $item): ?>

                                                <?php $ItemOption = new Item([
                                                    'DB' => $DB,
                                                    'id' => $item['id']
                                                ]); ?>
                                                
                                                <option value="<?= $ItemOption->id ?>" <?php if ($ItemOption->id == $Item->id) echo 'selected' ?>>
                                                    <?= $ItemOption->package_metric_title ?>
                                                </option>
                                            
                                            <?php endforeach ?>

                                        </select>
                                    </div>

                                    <div class="form-group <?php if (!$Item->quantity) echo 'hidden' ?>">
                                        <label>
                                            Quantity
                                        </label>
                                        
                                        <select name="quantity" class="custom-select" data-parsley-trigger="change" required>
                                            
                                            <?php for ($i = 1; $i <= $Item->quantity; $i++): ?>
                                                
                                                <option value="<?= $i ?>" <?php if ((isset($_GET['quantity']) && $_GET['quantity'] == $i) || ($in_cart && $OrderItem->quantity == $i)) echo 'selected' ?>>
                                                    <?= $i ?>
                                                </option>
                                            
                                            <?php endfor; ?>

                                        </select>
                                    </div>

                                    <div class="exchange form-group">
                                        <label>
                                            Exchange option
                                        </label>
                                        
                                        <select name="exchange" class="custom-select" data-parsley-trigger="change" required>
                                            
                                            <?php if ($SellerAccount->Delivery && $SellerAccount->Delivery->is_offered): ?>

                                                <option value="delivery" <?php if ($in_cart && $OrderGrower->Exchange->type == 'delivery') echo 'selected' ?>>
                                                    Delivery
                                                </option>

                                            <?php endif ?>

                                            <?php foreach ($meetups as $meetup): ?>
                                                
                                                <option value="<?= $meetup['id'] ?>" <?php if ($in_cart && $OrderGrower->Exchange->type == $meetup['id']) echo 'selected' ?>>
                                                    <?= 'Meetup: ' . ((!empty($meetup['title'])) ? $meetup['title'] : $meetup['address_line_1']) ?>
                                                </option>
                                            
                                            <?php endforeach ?>

                                        </select>
                                    </div>

                                    <input type="submit" class="btn btn-block btn-<?= ($Item->quantity) ? 'cta' : 'danger' ?>" value="<?= (!$Item->quantity) ? 'Out of stock' : ($in_cart ? 'Update basket' : 'Add to basket') ?>" <?php if (!$SellerAccount->is_active || !$Item->quantity) echo 'disabled' ?>/>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 order-3 d-md-none">
                    <div class="sidebar-content">
                        <div class="photo box">
                            
                            <?php img(ENV . '/item-images/' . $Item->Image->filename, $Item->Image->ext, [
                                'server'    => 'S3',
                                'class'     => 'img-fluid'
                            ]); ?>

                        </div>
                    </div>
                </div>

                <div class="col-12 order-4 d-md-none">
                    
                    <div class="available-exchange-options set">
                        <h4 class="margin-btm-50em">
                            Exchange options
                        </h4>

                        <div class="muted margin-btm-1em">
                            Meetup locations or delivery options to get your food
                        </div>
                        
                        <?php if (!empty($exchange_options_available)): ?>

                            <?php if ($SellerAccount->Delivery && $SellerAccount->Delivery->is_offered): ?>

                                <div class="callout">
                                    <div class="muted font-18 thick">
                                        Delivery
                                    </div>
                                    
                                    <div>
                                        <?= "Will deliver within: {$SellerAccount->Delivery->distance} miles"; ?>
                                    </div>

                                    <?php if ($SellerAccount->Delivery->delivery_type == 'conditional'): ?>
                                        
                                        <div>Free delivery within: <?= $SellerAccount->Delivery->free_distance ?> miles</div>

                                    <?php endif; ?>

                                    <div>
                                        <?= ($SellerAccount->Delivery->delivery_type == 'free' ? 'Free' : 'Rate: $' . number_format($SellerAccount->Delivery->fee / 100, 2) . ' ' . str_replace('-', ' ', $SellerAccount->Delivery->pricing_rate)); ?>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <?php if (!empty($meetups)): ?>
                            
                                <?php foreach ($meetups as $meetup): ?>
                                
                                    <div class="callout">
                                        <div class="muted font-18 thick">
                                            <i class="fa fa-map-signs" data-toggle="tooltip" data-title="Meet here at the time specified to pick up your order"></i>
                                            <?= (!empty($meetup['title'])) ? $meetup['title'] : "{$meetup['address_line_1']} {$meetup['address_line_2']}" ?>
                                        </div>
                                        
                                        <div class="<?php if (empty($meetup['title'])) echo 'hidden' ?>">
                                            <?= "{$meetup['address_line_1']} {$meetup['address_line_2']}" ?>
                                        </div>
                                        
                                        <div>
                                            <?= "{$meetup['city']}, {$meetup['state']}" ?>
                                        </div>
                                        
                                        <div>
                                            <?= "{$meetup['day']} &bull; {$meetup['start_time']} &ndash; {$meetup['end_time']}" ?>
                                        </div>
                                    </div>
                            
                                <?php endforeach ?>

                            <?php endif ?>

                        <?php else: ?>

                            <div class="callout">
                                <?= $SellerAccount->name ?> hasn't enabled any exchange options yet
                            </div>

                            <?php if ($is_owner): ?>

                                <div class="btn-group">
                                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/exchange-options/delivery" class="btn btn-cta">
                                        Enable delivery
                                    </a>

                                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/exchange-options/pickup" class="btn btn-cta">
                                        Enable pickup
                                    </a>

                                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/exchange-options/meetup" class="btn btn-cta">
                                        Enable meetup
                                    </a>
                                </div>

                            <?php endif; ?>

                        <?php endif; ?>
                        
                    </div>

                    <?php if (!empty($ratings) && count($ratings) > 0): ?>

                        <div class="reviews set">
                            <h4 class="margin-btm-50em ">
                                <bold class="dark-gray">Reviews</bold> 
                                <light class="light-gray">(<?= count($ratings); ?>)</light>
                            </h4>
                            
                            <div class="muted margin-btm-1em">
                                Item reviews from customers
                            </div>

                            <?php foreach ($ratings as $rating): ?>

                                <?php $ReviewUser = new BuyerAccount([
                                    'DB' => $DB,
                                    'id' => $rating['buyer_account_id']
                                ]); ?>           
                                
                                <div class="user-block margin-btm-1em">
                                    <a href="<?= PUBLIC_ROOT . "{$ReviewUser->link}"; ?>">                
                                        <div class="user-photo" style="background-image: url(<?= (!empty($ReviewUser->Image->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/buyer-account-images/{$ReviewUser->Image->filename}.{$ReviewUser->Image->ext}" /* . '?' . time() */ : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>
                                    </a>

                                    <div class="user-content">
                                        <p class="muted margin-btm-25em">
                                            &quot;<?= $rating['review']; ?>&quot;
                                        </p>

                                        <small class="dark-gray bold flexstart">
                                            <a href="<?= PUBLIC_ROOT . $ReviewUser->link ?>" class="strong">
                                                <?= $ReviewUser->name ?>
                                            </a>

                                            &nbsp;&bull;&nbsp;

                                            <?= "{$ReviewUser->Address->city}, {$ReviewUser->Address->state}" ?>
                                        </small>
                                    </div>
                                </div>
                                
                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                    <div class="about-grower set">
                        <h4 class="margin-btm-50em ">
                            <bold class="dark-gray">About the seller</bold> 
                        </h4>

                        <div class="muted margin-btm-1em">
                            Get to know the <?= (($SellerAccount->type == 'individual') ? 'person' : 'people'); ?> selling these items
                        </div>

                        <div class="user-block">
                            <a href="<?= PUBLIC_ROOT . "{$SellerAccount->link}"; ?>">
                                <div class="user-photo" style="background-image: url(<?= (!empty($SellerAccount->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$SellerAccount->filename}.{$SellerAccount->ext}" : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>    
                            </a>

                            <div class="user-content">
                                <div class="font-18 muted thick">    
                                    <a href="<?= PUBLIC_ROOT . $SellerAccount->link; ?>">
                                        <?= $SellerAccount->name; ?>
                                    </a>
                                </div>

                                <?php if ($SellerAccount->is_active): ?>

                                    <div class="font-85 muted bold margin-btm-50em">
                                        <?= $grower_stars?>

                                        &nbsp;&bull;&nbsp;
                                        
                                        <?= "{$SellerAccount->city}, {$SellerAccount->state}" ?>
                                    </div>

                                <?php endif; ?>

                            </div>
                        </div>

                        <?php if (!$SellerAccount->is_active && $is_owner): ?>

                            <a href="<?= PUBLIC_ROOT ?>dashboard/selling/" class="btn btn-cta margin-top-1em">
                                Complete your profile
                            </a>
                        
                        <?php elseif (!empty($SellerAccount->bio)): ?>

                            <div class="callout">
                                <div><?= $SellerAccount->bio ?></div>
                            </div>
                        
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            
        <?php else: ?>

            Oops! This URL does not belong to an active item.
        
        <?php endif; ?>

    </div>
</main>

<script>
    var buyer_lat   = <?= (isset($User->BuyerAccount->Address->latitude)) ? $User->BuyerAccount->Address->latitude : 0; ?>;
    var buyer_lng   = <?= (isset($User->BuyerAccount->Address->longitude)) ? $User->BuyerAccount->Address->longitude : 0; ?>;
    var seller_lat  = <?= (isset($SellerAccount)) ? number_format($SellerAccount->latitude, 2) : 0; ?>;
    var seller_lng  = <?= (isset($SellerAccount)) ? number_format($SellerAccount->longitude, 2) : 0; ?>;
    var items       = <?= json_encode($hashed_items) ?>
</script>