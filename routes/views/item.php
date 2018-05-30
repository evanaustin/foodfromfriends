<main>
    <div class="main container">
        
        <?php if ($FoodListing->id && ($GrowerOperation->is_active || $is_owner)): ?>

            <?php if ($is_owner): ?>

                <div class="alerts" style="display:block;">
                    <div class="alert alert-<?= (($GrowerOperation->is_active) ? 'info' : 'warning'); ?>">

                        <?php if ($GrowerOperation->is_active): ?>
                        
                            <span>This is what your item looks like to the public. Click <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/edit?id=<?= $FoodListing->id ?>">here</a> to go edit this item.</span>
                        
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
                            
                            <?php if (!empty($FoodListing->filename)): ?>

                                <a href="#" data-toggle="modal" data-target="#img-zoom-modal">

                                <?php img(ENV . '/items/' . $FoodListing->filename, $FoodListing->ext, [
                                    'server'    => 'S3',
                                    'class'     => 'img-fluid'
                                ]); ?>
                                
                                </a>

                            <?php else: ?>

                                <?php img('placeholders/default-thumbnail', 'jpg', [
                                    'server'    => 'local', 
                                    'class'     => 'img-fluid rounded'
                                ]); ?>

                                <?php if ($is_owner): ?>
                                    
                                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/edit?id=<?= $FoodListing->id ?>" class="btn btn-cta btn-block">
                                        Add an item image
                                    </a>
                                
                                <?php endif; ?>

                            <?php endif; ?>

                        </div>
                        
                        <div class="<?= (isset($GrowerOperation->latitude, $GrowerOperation->longitude) ? 'map' : 'photo'); ?> box">
                            
                            <?php if (isset($GrowerOperation->latitude, $GrowerOperation->longitude)): ?>

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
                            <?= $FoodListing->title; ?>
                        </h2>

                        <h6 class="muted normal margin-btm-1em">
                            <span class="brand">
                                <?= $item_stars; ?>
                            </span>

                            <?php if (!empty($ratings) && count($ratings) > 0): ?>
                                
                                <div class="rounded-circle">
                                    <?= count($ratings) ?>
                                </div>

                            <?php endif; ?>
                            
                            <?php if (!empty($FoodListing->weight) && !empty($FoodListing->units)): ?>
                                
                                &bull;
                                $<?= number_format(($FoodListing->price / $FoodListing->weight) / 100, 2) . '/' . $FoodListing->units ?>
                            
                            <?php endif; ?>

                            &bull;

                            <?= ($FoodListing->is_available ? "{$FoodListing->quantity} in stock" : 'Unavailable') ?>
                        </h6>
                        
                        <?php if (!empty($FoodListing->description)): ?>

                            <div class="callout description">
                                <div><?= $FoodListing->description ?></div>
                            </div>

                        <?php elseif ($is_owner): ?>

                            <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/edit?id=<?= $FoodListing->id ?>" class="btn btn-cta">
                                Add a description
                            </a>
                        
                        <?php endif; ?>

                        <?php if (!empty($FoodListing->packaging) || ($wholesale_relationship && !empty($FoodListing->wholesale_packaging))): ?>
                            
                            <div class="item-definition set d-none d-md-block">
                                <h4 class="margin-btm-50em">
                                    Packaging
                                </h4>

                                <div class="muted margin-btm-1em">
                                    How the item will come packaged
                                </div>

                                <div class="callout">
                                    <div><?= ($wholesale_relationship) ? $FoodListing->wholesale_packaging : $FoodListing->packaging; ?></div>
                                </div>
                            </div>

                        <?php endif; ?>

                        <div class="available-exchange-options set d-none d-md-block">
                            <h4 class="margin-btm-50em">
                                <bold class="dark-gray">Exchange options</bold>

                                <?php if (!empty($exchange_options_available)): ?>
                                
                                    <light class="light-gray">
                                        (<?= count($exchange_options_available) ?>)
                                    </light>
                                
                                <?php endif; ?>

                            </h4>

                            <div class="muted margin-btm-1em">
                                Available options for getting your food
                            </div>
                            
                            <?php if (!empty($exchange_options_available)): ?>

                                <?php if ($GrowerOperation->Delivery && $GrowerOperation->Delivery->is_offered): ?>

                                    <div class="callout">
                                        <div class="muted font-18 thick">
                                            Delivery
                                        </div>
                                        
                                        <div>
                                            Will deliver within: <?= $GrowerOperation->Delivery->distance ?> miles
                                        </div>

                                        <?php if ($GrowerOperation->Delivery->delivery_type == 'conditional'): ?>
                                            
                                            <div>Free delivery within: {$GrowerOperation->Delivery->free_distance} miles</div>";

                                        <?php endif; ?>

                                        <div>
                                            <?= ($GrowerOperation->Delivery->delivery_type == 'free' ? 'Free' : 'Rate: $' . number_format($GrowerOperation->Delivery->fee / 100, 2) . ' ' . str_replace('-', ' ', $GrowerOperation->Delivery->pricing_rate)); ?>
                                        </div>
                                    </div>

                                <?php endif; ?>

                                <?php if ($GrowerOperation->Pickup && $GrowerOperation->Pickup->is_offered): ?>
                                    
                                    <div class="callout">
                                        <div class="muted font-18 thick">
                                            Pickup
                                        </div>

                                        <div>
                                            <?= "{$GrowerOperation->city}, {$GrowerOperation->state}"; ?>
                                        </div>
                                        
                                        <?php
                                        
                                        if (isset($distance) && $distance['length'] > 0) {
                                            echo "<div>{$distance['length']} {$distance['units']} away</div>";
                                        }

                                        ?>
                                    </div>

                                <?php endif; ?>
                                    
                                <?php if ($GrowerOperation->Meetup && $GrowerOperation->Meetup->is_offered): ?>
                                    
                                    <div class="callout">
                                        <div class="muted font-18 thick">
                                            Meetup
                                        </div>

                                        <div>
                                            <?= $GrowerOperation->Meetup->address_line_1 . (($GrowerOperation->Meetup->address_line_2) ? ", {$GrowerOperation->Meetup->address_line_2}" : '') ?><br>
                                            <?= "{$GrowerOperation->Meetup->city}, {$GrowerOperation->Meetup->state} {$GrowerOperation->Meetup->zipcode}" ?><br>
                                            <?= $GrowerOperation->Meetup->time ?>
                                        </div>
                                    </div>

                                <?php endif; ?>

                            <?php else: ?>

                                <div class="callout">
                                    <?= $GrowerOperation->name ?> hasn't enabled any exchange options yet
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
                                Get to know the <?= (($GrowerOperation->type == 'individual') ? 'person' : 'people'); ?> growing your food
                            </div>

                            <div class="user-block margin-btm-50em">
                                <a href="<?= PUBLIC_ROOT . "{$GrowerOperation->link}"; ?>">
                                    <div class="user-photo" style="background-image: url(<?= (!empty($GrowerOperation->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$GrowerOperation->filename}.{$GrowerOperation->ext}" : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>    
                                </a>
                                
                                <div class="user-content">
                                    <div class="font-18 muted thick">    
                                        <a href="<?= PUBLIC_ROOT . $GrowerOperation->link; ?>">
                                            <?= $GrowerOperation->name; ?>
                                        </a>
                                    </div>
                                        
                                    <?php if ($GrowerOperation->is_active): ?>

                                        <div class="font-85 muted bold margin-btm-50em">
                                            <?= $grower_stars?>

                                            &nbsp;&bull;&nbsp;

                                            <?= "{$GrowerOperation->city}, {$GrowerOperation->state}" ?>
                                        </div>
                                    
                                    <?php endif; ?>

                                </div>
                            </div>

                            <?php if (!$GrowerOperation->is_active && $is_owner): ?>

                                <a href="<?= PUBLIC_ROOT ?>dashboard/selling/" class="btn btn-cta margin-top-1em">
                                    Complete your profile
                                </a>

                                <?php elseif (!empty($GrowerOperation->bio)): ?>

                                <div class="callout">
                                    <div><?= $GrowerOperation->bio ?></div>
                                </div>

                            <?php endif; ?>

                        </div>
                    </div>    
                </div>

                <div class="col-12 order-2 col-lg-4 order-lg-2">
                    <div id="basket-form-container" class="sticky-top">
                        <div class="box">
                            <div class="header">    
                                <?= _amount(($wholesale_relationship && !empty($FoodListing->wholesale_price) ? $FoodListing->wholesale_price : $FoodListing->price)) ?>
                                
                                <small>
                                    each
                                </small>
                            </div>

                            <div class="content">
                                <div class="alerts"></div>

                                <?php if (!$FoodListing->is_available): ?>

                                    <span class="muted">This item is currently unavailable</span>

                                <?php else: ?>

                                    <?php if (isset($User, $User->BuyerAccount->ActiveOrder, $User->BuyerAccount->ActiveOrder->Growers[$GrowerOperation->id], $User->BuyerAccount->ActiveOrder->Growers[$GrowerOperation->id]->FoodListings[$FoodListing->id])): ?>
                                
                                        <?php $OrderGrower = $User->BuyerAccount->ActiveOrder->Growers[$GrowerOperation->id] ?>
                                        <?php $OrderItem = $OrderGrower->FoodListings[$FoodListing->id] ?>

                                        <form id="update-item" data-ordergrower="<?= $OrderGrower->id; ?>">
                                            <input type="hidden" name="seller-id" value="<?= $GrowerOperation->id; ?>">
                                            <input type="hidden" name="item-id" value="<?= $FoodListing->id; ?>">
                                            <input type="hidden" name="distance-miles"  value="<?php if (isset($distance_miles)) echo $distance_miles ?>"/>

                                            <div class="form-group">
                                                <label>
                                                    Quantity
                                                </label>
                                                
                                                <select name="quantity" class="custom-select" data-parsley-trigger="change" required>
                                                    
                                                    <?php for ($i = 1; $i <= ($wholesale_relationship && !empty($FoodListing->wholesale_quantity) ? $FoodListing->wholesale_quantity : $FoodListing->quantity); $i++): ?>
                                                        
                                                        <option value="<?= $i ?>" <?php if ($OrderItem->quantity == $i) echo 'selected' ?>>
                                                            <?= $i ?>
                                                        </option>
                                                    
                                                    <?php endfor; ?>

                                                </select>
                                            </div>

                                            <div class="exchange form-group">
                                                <label>
                                                    Exchange option
                                                </label>

                                                <?php if (!empty($exchange_options_available)): ?>

                                                    <div class="btn-group">

                                                        <?php foreach ($exchange_options_available as $option): ?>

                                                            <button type="button" class="exchange-btn btn btn-secondary <?php if (isset($_GET['exchange']) && $_GET['exchange'] == $option) echo 'active' ?>" data-option="<?= $option ?>">
                                                                <?= ucfirst($option) ?>
                                                            </button>

                                                        <?php endforeach; ?>

                                                    </div>

                                                <?php else: ?>

                                                    <div class="callout">
                                                        No exchange options available
                                                    </div>

                                                <?php endif; ?>

                                                <div class="form-control-feedback hidden">
                                                    Please select an exchange type
                                                </div>
                                            </div>
                                        </form>

                                    <?php else: ?>
                                    
                                        <form id="add-item" data-ordergrower="<?= (isset($OrderGrower)) ? $OrderGrower->id : 0; ?>">
                                            <input type="hidden" name="seller-id" value="<?= $GrowerOperation->id; ?>">
                                            <input type="hidden" name="item-id" value="<?= $FoodListing->id; ?>">
                                            <input type="hidden" name="distance-miles"  value="<?php if (isset($distance_miles)) echo $distance_miles ?>"/>
                                            <input type="hidden" name="is-wholesale" value="<?= ($wholesale_relationship) ? 1 : 0 ?>"/>

                                            <div class="form-group">
                                                <label>
                                                    Quantity
                                                </label>
                                                
                                                <select name="quantity" class="custom-select" data-parsley-trigger="change" required>
                                                    
                                                    <?php for ($i = 1; $i <= ($wholesale_relationship && !empty($FoodListing->wholesale_quantity) ? $FoodListing->wholesale_quantity : $FoodListing->quantity); $i++): ?>
                                                        
                                                        <option value="<?= $i ?>" <?php if (isset($_GET['quantity']) && $_GET['quantity'] == $i) echo 'selected' ?>>
                                                            <?= $i ?>
                                                        </option>
                                                    
                                                    <?php endfor; ?>

                                                </select>
                                            </div>

                                            <div class="exchange form-group">
                                                <label>
                                                    Exchange option
                                                </label>
                                                
                                                <?php if (!empty($exchange_options_available)): ?>

                                                    <div class="btn-group">
                                                    
                                                        <?php foreach ($exchange_options_available as $option): ?>
                                                        
                                                            <button type="button" class="exchange-btn btn btn-secondary <?php if ($active_ex_op == $option) echo 'active' ?>" data-option="<?= $option ?>">
                                                                <?= ucfirst($option) ?>
                                                            </button>
                                                        
                                                        <?php endforeach; ?>
                                                    
                                                    </div>

                                                <?php else: ?>

                                                    <div class="callout">
                                                        No exchange options available
                                                    </div>
                    
                                                <?php endif; ?>

                                                <div class="form-control-feedback hidden">
                                                    Please select an exchange type
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary btn-block" <?php if (!$GrowerOperation->is_active) echo 'disabled' ?>>
                                                Add to basket
                                            </button>
                                        </form>

                                    <?php endif; ?>

                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 order-3 d-md-none">
                    <div class="sidebar-content">
                        <div class="photo box">
                            
                            <?php img(ENV . '/items/' . $FoodListing->filename, $FoodListing->ext, [
                                'server'    => 'S3',
                                'class'     => 'img-fluid'
                            ]); ?>

                        </div>
                    </div>
                </div>

                <div class="col-12 order-4 d-md-none">
                    
                    <?php if (!empty($FoodListing->packaging)): ?>

                        <div class="item-definition set">
                            <h4>
                                Item definition
                            </h4>

                            <div class="callout">
                                <div><?= $FoodListing->packaging; ?></div>
                            </div>
                        </div>

                    <?php endif; ?>

                    <div class="available-exchange-options set">
                        <h4 class="margin-btm-50em">
                            Exchange options
                            <light class="light-gray">(<?= count($exchange_options_available); ?>)</light>
                        </h4>

                        <div class="muted margin-btm-1em">
                            Available options for getting your food
                        </div>
                        
                        <?php if (!empty($exchange_options_available)): ?>

                            <?php if ($GrowerOperation->Delivery && $GrowerOperation->Delivery->is_offered): ?>

                                <div class="callout">
                                    <div class="muted font-18 thick">
                                        Delivery
                                    </div>
                                    
                                    <div>
                                        <?= "Will deliver within: {$GrowerOperation->Delivery->distance} miles"; ?>
                                    </div>

                                    <?php if ($GrowerOperation->Delivery->delivery_type == 'conditional'): ?>
                                        
                                        <div>Free delivery within: <?= $GrowerOperation->Delivery->free_distance ?> miles</div>

                                    <?php endif; ?>

                                    <div>
                                        <?= ($GrowerOperation->Delivery->delivery_type == 'free' ? 'Free' : 'Rate: $' . number_format($GrowerOperation->Delivery->fee / 100, 2) . ' ' . str_replace('-', ' ', $GrowerOperation->Delivery->pricing_rate)); ?>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <?php if ($GrowerOperation->Pickup && $GrowerOperation->Pickup->is_offered): ?>
                                
                                <div class="callout">
                                    <div class="muted font-18 thick">
                                        Pickup
                                    </div>

                                    <div>
                                        <?= "{$GrowerOperation->city}, {$GrowerOperation->state}"; ?>
                                    </div>
                                    
                                    <?php if (isset($distance) && $distance['length'] > 0): ?>

                                        <div><?= "{$distance['length']} {$distance['units']}" ?> away</div>

                                    <?php endif; ?>

                                </div>

                            <?php endif; ?>
                                
                            <?php if ($GrowerOperation->Meetup && $GrowerOperation->Meetup->is_offered): ?>
                                
                                <div class="callout">
                                    <div class="muted font-18 thick">
                                        Meetup
                                    </div>

                                    <div>
                                        <?= $GrowerOperation->Meetup->address_line_1 . (($GrowerOperation->Meetup->address_line_2) ? ", {$GrowerOperation->Meetup->address_line_2}" : '') ?><br>
                                        <?= "{$GrowerOperation->Meetup->city}, {$GrowerOperation->Meetup->state} {$GrowerOperation->Meetup->zipcode}" ?><br>
                                        <?= $GrowerOperation->Meetup->time ?>
                                    </div>
                                </div>

                            <?php endif; ?>

                        <?php else: ?>

                            <div class="callout">
                                <?= $GrowerOperation->name ?> hasn't enabled any exchange options yet
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
                            Get to know the <?= (($GrowerOperation->type == 'individual') ? 'person' : 'people'); ?> selling these items
                        </div>

                        <div class="user-block">
                            <a href="<?= PUBLIC_ROOT . "{$GrowerOperation->link}"; ?>">
                                <div class="user-photo" style="background-image: url(<?= (!empty($GrowerOperation->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$GrowerOperation->filename}.{$GrowerOperation->ext}" : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>);"></div>    
                            </a>

                            <div class="user-content">
                                <div class="font-18 muted thick">    
                                    <a href="<?= PUBLIC_ROOT . $GrowerOperation->link; ?>">
                                        <?= $GrowerOperation->name; ?>
                                    </a>
                                </div>

                                <?php if ($GrowerOperation->is_active): ?>

                                    <div class="font-85 muted bold margin-btm-50em">
                                        <?= $grower_stars?>

                                        &nbsp;&bull;&nbsp;
                                        
                                        <?= "{$GrowerOperation->city}, {$GrowerOperation->state}" ?>
                                    </div>

                                <?php endif; ?>

                            </div>
                        </div>

                        <?php if (!$GrowerOperation->is_active && $is_owner): ?>

                            <a href="<?= PUBLIC_ROOT ?>dashboard/selling/" class="btn btn-cta margin-top-1em">
                                Complete your profile
                            </a>
                        
                        <?php elseif (!empty($GrowerOperation->bio)): ?>

                            <div class="callout">
                                <div><?= $GrowerOperation->bio ?></div>
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
    var seller_lat  = <?= (isset($GrowerOperation)) ? number_format($GrowerOperation->latitude, 2) : 0; ?>;
    var seller_lng  = <?= (isset($GrowerOperation)) ? number_format($GrowerOperation->longitude, 2) : 0; ?>;
</script>