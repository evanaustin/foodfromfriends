<main>
    <div class="main container">

        <?php if ((isset($Seller) && $Seller->is_active) || $is_owner): ?>

            <?php if ($is_owner): ?>

                <div class="alerts" style="display:block;">
                    <div class="alert alert-<?= (($Seller->is_active) ? 'info' : 'warning') ?>">
                        
                        <?php if ($Seller->is_active): ?>

                            <span>
                                This is your public profile. 
                                Click <a href="<?= PUBLIC_ROOT ?>'dashboard/selling/settings/edit-profile">here</a> to go edit your information.
                            </span>

                        <?php else: ?>

                            <span>
                                <i class="fa fa-warning"></i> This is only a preview of your seller profile. 
                                Click <a href="<?= PUBLIC_ROOT ?>'dashboard/selling/">here</a> to finish activating your seller account.
                            </span>

                        <?php endif ?>

                        <a class="close" data-dismiss="alert">×</a>
                    </div>
                </div>

            <?php endif ?>

            <div class="row">   
                <div class="col-12 order-2 col-lg-3 order-lg-1">
                    <div class="sidebar-content">
                        <div class="photo box">

                            <?php if (!empty($Seller->filename)): ?>
                                
                                <a href="#" data-toggle="modal" data-target="#img-zoom-modal">

                                <?= _img(ENV . "/grower-operation-images/{$Seller->filename}", $Seller->ext, [
                                    'server'    => 'S3',
                                    'class'     => 'img-fluid'
                                ]) ?>

                                </a>

                            <?php else: ?>

                                <?= _img('placeholders/user-thumbnail', 'jpg', [
                                    'server'    => 'local', 
                                    'class'     => 'img-fluid rounded'
                                ]) ?>

                                <?php if ($is_owner): ?>

                                    <a href="<?= PUBLIC_ROOT ?>'dashboard/selling/settings/edit-profile" class="btn btn-cta btn-block">
                                        Add a profile picture
                                    </a>';

                                <?php endif ?>

                            <?php endif ?>

                        </div>
                        
                        <div class="details box">
                            <ul class="list-group">
                                <li class="list-group-item heading">
                                    <span>Item exchange options:</span>
                                </li>

                                <ul class="list-group">
                                    <li class="list-group-item sub">
                                        <fable>
                                            <cell class="<?php if (!$Seller->Delivery || !$Seller->Delivery->is_offered) echo 'inactive' ?>">
                                                Delivery
                                            </cell>
                                            
                                            <cell class="flexend">
                                                
                                                <?php if ($Seller->Delivery && $Seller->Delivery->is_offered): ?>
                                                    
                                                    <i class="fa fa-check"></i>

                                                <?php else: ?>

                                                    <?= ($is_owner) ? '<a href="' . PUBLIC_ROOT . 'dashboard/selling/exchange-options/delivery" class="btn btn-cta btn-block">Enable</a>' : '<i class="fa fa-times"></i>' ?>
                                                
                                                <?php endif ?>

                                            </cell>
                                        </fable>
                                    </li>

                                    <li class="list-group-item sub">
                                        <fable>
                                            <cell class="<?php if (!$Seller->Pickup || !$Seller->Pickup->is_offered) echo 'inactive' ?>">
                                                Pickup
                                            </cell>
                                        
                                            <cell class="flexend">

                                                <?php if ($Seller->Pickup && $Seller->Pickup->is_offered): ?>
                                                    
                                                    <i class="fa fa-check"></i>

                                                <?php else: ?>

                                                    <?= ($is_owner) ? '<a href="' . PUBLIC_ROOT . 'dashboard/selling/exchange-options/pickup" class="btn btn-cta btn-block">Enable</a>' : '<i class="fa fa-times"></i>' ?>
                                                
                                                <?php endif ?>

                                            </cell>
                                        </fable>
                                    </li>

                                    <li class="list-group-item sub">
                                        <fable>
                                            <cell class="<?php if (!$Seller->Meetup || !$Seller->Meetup->is_offered) { echo 'inactive'; } ?>">Meetup</cell>
                                            
                                            <cell class="justify-content-end">

                                                <?php if ($Seller->Meetup && $Seller->Meetup->is_offered): ?>
                                                    
                                                    <i class="fa fa-check"></i>

                                                <?php else: ?>

                                                    <?= ($is_owner) ? '<a href="' . PUBLIC_ROOT . 'dashboard/selling/exchange-options/meetup" class="btn btn-cta btn-block">Enable</a>' : '<i class="fa fa-times"></i>' ?>
                                                
                                                <?php endif ?>

                                            </cell>
                                        </fable>
                                    </li>
                                </ul>
                            </ul>
                        </div>

                        <div class="<?= (isset($Seller->latitude, $Seller->longitude) ? 'map' : 'photo') ?> box">
                            
                            <?php if (isset($Seller->latitude, $Seller->longitude)): ?>

                                <div id="map"></div>

                            <?php else: ?>

                                <?= _img('placeholders/location-thumbnail', 'jpg', [
                                    'server'    => 'local', 
                                    'class'     => 'img-fluid rounded'
                                ]) ?>

                                <?php if ($is_owner): ?>

                                    <a href="<?= PUBLIC_ROOT ?>'dashboard/selling/settings/edit-profile" class="btn btn-cta btn-block">Set your address</a>
                                
                                <?php endif ?>
                            
                            <?php endif ?>

                        </div>
                    </div>
                </div>
            
                <div class="col-12 order-1 col-lg-9 order-lg-2">
                    <div id="main-content">
                        <h2 class="dark-gray bold margin-btm-25em">
                            <?= $Seller->name ?>

                            <?php if (!$is_owner): ?>
                            
                                <a href="<?= PUBLIC_ROOT . "dashboard/messages/inbox/buying/thread?grower={$Seller->id}" ?>">
                                    <div id="message" class="float-right btn btn-primary" data-toggle="tooltip" data-placement="bottom" data-title="Message">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                </a>
                            
                            <?php endif ?>

                            <?php if (isset($User->WholesaleAccount)): ?>

                                <div id="request-wholesale" class="float-right btn btn-muted margin-right-1em" data-seller-id="<?= $Seller->id ?>" data-toggle="tooltip" data-placement="bottom" data-title="Request wholesale account">
                                    <i class="fa fa-cutlery"></i>
                                </div>

                            <?php endif ?>

                        </h2>

                        <div class="muted normal margin-btm-25em">
                            <?= "<span class=\"brand\">{$grower_stars}</span>" . (count($ratings) > 0 ? "<div class=\"rounded-circle\">" . count($ratings) . "</div>" : " ") . (isset($Seller->city, $Seller->state) ? "&bull; {$Seller->city}, {$Seller->state}" : '') . ((isset($distance, $distance['length']) && $distance['length'] > 0) ? " &bull; {$distance['length']} {$distance['units']} away" : "") ?>
                        </div>

                        <div class="muted bold margin-btm-1em">
                            <?= 'Joined in ' . $joined_on->format('F\, Y') ?>
                        </div>

                        <?php if (!empty($Seller->bio)): ?>
                            
                            <p class="muted margin-btm-2em">
                                <?= $Seller->bio ?>
                            </p>

                        <?php elseif ($is_owner): ?>

                            <div class="row">
                                <div class="col-md-4">
                                    <a href="<?= PUBLIC_ROOT ?>'dashboard/selling/settings/edit-profile" class="btn btn-cta">
                                        Add a bio
                                    </a>
                                </div>
                            </div>
                        
                        <?php endif ?>

                        <div class="items set">
                            <h4 class="margin-btm-50em ">
                                <bold class="dark-gray">Items</bold> 
                                <?= (!empty($listings)) ? '<light class="light-gray">(' . count($listings) . ')</light>' : '' ?>
                            </h4>

                            <div class="muted margin-btm-1em">
                                Items for sale from <?= $Seller->name ?>
                            </div>

                            <?php if (!empty($listings)): ?>

                                <div class="row">
                                
                                <?php foreach ($listings as $listing): ?>
                                    
                                    <?php $Item = new FoodListing([
                                        'DB' => $DB,
                                        'id' => $listing['id']
                                    ]) ?>
                                    
                                    <div class="col-md-4">
                                        <div class="item card animated zoomIn">
                                            <div class="card-img-top">
                                                <a href="<?= PUBLIC_ROOT . "{$Seller->link}/{$Item->link}" ?>">

                                                    <?php if (!empty($Item->filename)): ?>
                                                        
                                                        <?= _img(ENV . '/items/' . $Item->filename, $Item->ext, [
                                                            'server'    => 'S3',
                                                            'class'     => 'img-fluid animated fadeIn hidden'
                                                        ]) ?>

                                                        <div class="loading">
                                                            <i class="fa fa-circle-o-notch loading-icon"></i>
                                                        </div>

                                                    <?php else: ?>

                                                        <?= _img('placeholders/default-thumbnail', 'jpg', [
                                                            'server'    => 'local', 
                                                            'class'     => 'animated fadeIn img-fluid rounded'
                                                        ]) ?>
                        
                                                        <?php if ($is_owner): ?>

                                                            <?= "<a href=\"" . PUBLIC_ROOT . "dashboard/selling/items/edit?id={$Item->id}\" class=\"btn btn-cta btn-block margin-top-50em\">Add an item image</a>" ?>
                                                        
                                                        <?php endif ?>

                                                    <?php endif ?>

                                                </a>
                                            </div>

                                            <div class="card-body d-flex flex-column">
                                                <fable class="card-title margin-btm-50em">
                                                    <cell>
                                                        <?php
                                                        
                                                        $price  = ($wholesale_relationship && !empty($Item->wholesale_price))   ? $Item->wholesale_price    : $Item->price;
                                                        $weight = ($wholesale_relationship && !empty($Item->wholesale_weight))  ? $Item->wholesale_weight   : $Item->weight;
                                                        $units  = ($wholesale_relationship && !empty($Item->wholesale_units))   ? $Item->wholesale_units    : $Item->units;
                                                        
                                                        ?>

                                                        <h5 class="dark-gray bold">
                                                            <?= _amount($price) ?>
                                                        </h5>
                                                        
                                                        <?php if (!empty($weight) && !empty($units)): ?>
                                                            
                                                            &nbsp;
                                                            <span class="light-gray small">
                                                                ($<?= number_format(($price / $weight) / 100, 2) . "/{$units}" ?>)
                                                            </span>

                                                        <?php endif; ?>

                                                        <?php if ($wholesale_relationship && !empty($Item->wholesale_price)): ?>
                                                            
                                                            &nbsp;
                                                            <i class="fa fa-cutlery small muted" data-toggle="tooltip" data-title="Your wholesale price"></i>

                                                        <?php endif; ?>

                                                    </cell>

                                                    <cell class="justify-content-end">
                                                        <span class="small brand">
                                                            <?= stars($Item->average_rating) ?>
                                                        </span>
                                                    </cell>
                                                </fable>

                                                <div class="muted margin-btm-50em">
                                                    <a href="<?= PUBLIC_ROOT . "{$Seller->link}/{$Item->link}" ?>">
                                                        <?= $Item->title ?>
                                                    </a>
                                                </div>
                                                
                                                <?php if ($Item->is_available && $Item->quantity): ?>

                                                    <?php $SubOrderItem = (isset($SubOrder, $SubOrder->FoodListings[$Item->id])) ? $SubOrder->FoodListings[$Item->id] : null; ?>
                                        
                                                    <form id="quick-add-<?= $Item->id ?>" class="quick-add">
                                                        <fable id="in-stock">  
                                                            <cell>
                                                                <input type="hidden" name="user-id"         value="<?= (isset($User)) ? $User->id : 0 ?>">
                                                                <input type="hidden" name="food-listing-id" value="<?= $Item->id ?>"/>
                                                                <input type="hidden" name="order-item-id"   value="<?= (isset($SubOrderItem)) ? $SubOrderItem->id : 0 ?>"/>
                                                                <input type="hidden" name="suborder-id"     value="<?= (isset($SubOrder)) ? $SubOrder->id : 0 ?>"/>
                                                                <input type="hidden" name="exchange-option" value="<?= (isset($SubOrder)) ? $SubOrder->Exchange->type : '' ?>"/>

                                                                <select name="quantity" class="custom-select" data-parsley-trigger="change" required>
                                                                    
                                                                    <?php for ($i = 1; $i <= $Item->quantity; $i++): ?>
                                                                            
                                                                        <?= "<option value=\"{$i}\"" . ((isset($SubOrderItem) && $SubOrderItem->quantity == $i) ? 'selected' : '') . ">{$i}</option>" ?>
                                                                        
                                                                    <?php endfor ?>

                                                                </select>
                                                            </cell>
                                                            
                                                            <cell class="justify-content-end">
                                                                <button type="submit" class="btn no-margin" data-toggle="tooltip" data-title="Save to basket" data-placement="bottom">    
                                                                    <i class="fa fa-shopping-basket"></i>
                                                                </button>
                                                            </cell>
                                                        </fable>
                                                    </form>

                                                <?php else: ?>

                                                    <div class="card-text light-gray">
                                                        Out of stock
                                                    </div>

                                                <?php endif ?>

                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach ?>

                                </div>

                            <?php else: ?>

                                <div class="callout">
                                    <?= $Seller->name ?> doesn't have any items for sale yet
                                </div>
                                
                                <?php if ($is_owner): ?>

                                    <a href="<?= PUBLIC_ROOT ?>'dashboard/selling/items/add-new" class="btn btn-cta margin-top-1em">
                                        Add your first item
                                    </a>
                                
                                <?php endif ?>

                            <?php endif ?>

                            </div>
                        </div>
                        
                        <?php if (!empty($ratings)): ?>

                            <div class="reviews set">
                                <h4 class="margin-btm-50em ">
                                    <bold class="dark-gray">Reviews</bold> 
                                    <light class="light-gray">(<?= count($ratings) ?>)</light>
                                </h4>
                                
                                <div class="muted margin-btm-1em">
                                    Ratings & reviews from customers
                                </div>

                                <?php foreach ($ratings as $rating): ?>

                                    <?php $ReviewUser = new User([
                                        'DB' => $DB,
                                        'id' => $rating['user_id']
                                    ]) ?>           
                                    
                                    <div class="user-block margin-btm-1em">
                                        <a href="<?= PUBLIC_ROOT . "user/{$ReviewUser->slug}" ?>">             
                                            <div class="user-photo" style="background-image: url(<?= (!empty($ReviewUser->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/profile-photos/{$ReviewUser->filename}.{$ReviewUser->ext}" /* . '?' . time() */ : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg') ?>);"></div>
                                        </a>

                                        <div class="user-content">
                                            <p class="muted margin-btm-25em">
                                                &quot;<?= $rating['review'] ?>&quot;
                                            </p>

                                            <small class="flexstart">
                                                <?= "<a href=\"" . PUBLIC_ROOT . "user/{$ReviewUser->slug}\" class=\"strong\">$ReviewUser->name</a> &bull; {$ReviewUser->city}, {$ReviewUser->state}" ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                <?php endforeach ?>

                            </div>

                        <?php endif ?>

                    </div>
                </div>
            </div>
            
        <?php else: ?>

            Oops! This URL does not belong to an active seller.

        <?php endif ?>

    </div>
</main>

<script>
    var lat     = <?= (isset($Seller)) ? number_format($Seller->latitude, 2) : 0 ?>;
    var lng     = <?= (isset($Seller)) ? number_format($Seller->longitude, 2) : 0 ?>;
    var user    = <?= (isset($User)) ? $User->id : 0 ?>;
    var seller_name = '<?= $Seller->name ?>';
    var wholesale_account_name= '<?= (isset($User, $User->WholesaleAccount)) ? $User->WholesaleAccount->name : '' ?>';
</script>