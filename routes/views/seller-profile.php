<main>
    <div class="main container">

        <?php if ((isset($SellerAccount) && $SellerAccount->is_active) || ($is_owner || (isset($User) && $User->id == 1))): ?>

            <?php if ($is_owner): ?>

                <div class="alerts" style="display:block;">
                    <div class="alert alert-<?= (($SellerAccount->is_active) ? 'info' : 'warning') ?>">
                        
                        <?php if ($SellerAccount->is_active): ?>

                            <span>
                                This is your public profile. 
                                Click <a href="<?= PUBLIC_ROOT ?>dashboard/selling/settings/profile">here</a> to go edit your information.
                            </span>

                        <?php else: ?>

                            <span>
                                <i class="fa fa-warning"></i> This is only a preview of your seller profile. 
                                Click <a href="<?= PUBLIC_ROOT ?>dashboard/selling/">here</a> to finish activating your seller account.
                            </span>

                        <?php endif; ?>

                        <a class="close" data-dismiss="alert">×</a>
                    </div>
                </div>

            <?php endif; ?>

            <div class="row">   
                <div class="col-12 order-2 col-lg-3 order-lg-1">
                    <div class="sidebar-content">
                        <div class="photo box">

                            <?php if (!empty($SellerAccount->filename)): ?>
                                
                                <a href="#" data-toggle="modal" data-target="#img-zoom-modal">

                                <?= _img(ENV . "/grower-operation-images/{$SellerAccount->filename}", $SellerAccount->ext, [
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

                                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/settings/profile" class="btn btn-cta btn-block">
                                        Add a profile picture
                                    </a>

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
                                            <cell class="<?php if (!$SellerAccount->Delivery || !$SellerAccount->Delivery->is_offered) echo 'inactive' ?>">
                                                Delivery
                                            </cell>
                                            
                                            <cell class="flexend">
                                                
                                                <?php if ($SellerAccount->Delivery && $SellerAccount->Delivery->is_offered): ?>
                                                    
                                                    <i class="fa fa-check"></i>

                                                <?php else: ?>

                                                    <?= ($is_owner) ? '<a href="' . PUBLIC_ROOT . 'dashboard/selling/exchange-options/delivery" class="btn btn-cta btn-block">Enable</a>' : '<i class="fa fa-times"></i>' ?>
                                                
                                                <?php endif ?>

                                            </cell>
                                        </fable>
                                    </li>

                                    <li class="list-group-item sub">
                                        <fable>
                                            <cell class="<?php if (empty($meetups)) echo 'inactive' ?>">
                                                Meetup
                                            </cell>
                                            
                                            <cell class="justify-content-end">

                                                <?php if (!empty($meetups)): ?>
                                                    
                                                    <i class="fa fa-check"></i>

                                                <?php else: ?>

                                                    <?= ($is_owner) ? '<a href="' . PUBLIC_ROOT . 'dashboard/selling/exchange-options/meetups" class="btn btn-cta btn-block">Enable</a>' : '<i class="fa fa-times"></i>' ?>
                                                
                                                <?php endif ?>

                                            </cell>
                                        </fable>
                                    </li>
                                </ul>
                            </ul>
                        </div>

                        <div class="<?= (isset($SellerAccount->latitude, $SellerAccount->longitude) ? 'map' : 'photo') ?> box">
                            
                            <?php if (isset($SellerAccount->latitude, $SellerAccount->longitude)): ?>

                                <div id="map"></div>

                            <?php else: ?>

                                <?= _img('placeholders/location-thumbnail', 'jpg', [
                                    'server'    => 'local', 
                                    'class'     => 'img-fluid rounded'
                                ]) ?>

                                <?php if ($is_owner): ?>

                                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/settings/profile" class="btn btn-cta btn-block">
                                        Set your address
                                    </a>
                                
                                <?php endif ?>
                            
                            <?php endif ?>

                        </div>
                    </div>
                </div>
            
                <div class="col-12 order-1 col-lg-9 order-lg-2">
                    <div id="main-content">
                        <h2 class="dark-gray bold margin-btm-25em">
                            <?= $SellerAccount->name ?>

                            <?php //if (!$is_owner): ?>
                            
                                <a href="<?= PUBLIC_ROOT . "dashboard/buying/messages/thread?seller={$SellerAccount->id}" ?>">
                                    <div id="message" class="float-right btn btn-primary" data-toggle="tooltip" data-placement="bottom" data-title="Message">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                </a>
                            
                            <?php //endif ?>

                        </h2>

                        <div class="muted normal margin-btm-25em">
                            <span class="brand">
                                <?= $grower_stars ?>
                            </span>
                            
                            <?php if (!empty($ratings)): ?>
                                
                                <div class="rounded-circle">
                                    <?= count($ratings) ?>
                                </div>

                            <?php else: ?>

                                &bull;

                            <?php endif ?>
                            
                            <?php if (isset($SellerAccount->city, $SellerAccount->state)): ?>
                                
                                <?= "{$SellerAccount->city}, {$SellerAccount->state}" ?>
                            
                            <?php endif ?>
                            
                            <?php if (!empty($distance)): ?>
                                
                                &bull; <?= "{$distance[0]} {$distance[1]}" ?> away

                            <?php endif ?>

                        </div>

                        <div class="muted bold margin-btm-1em">
                            Joined in <?= $joined_on->format('F\, Y') ?>
                        </div>

                        <?php if (!empty($SellerAccount->bio)): ?>
                            
                            <p class="muted margin-btm-2em">
                                <?= $SellerAccount->bio ?>
                            </p>

                        <?php elseif ($is_owner): ?>

                            <div class="row">
                                <div class="col-md-4">
                                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/settings/profile" class="btn btn-cta">
                                        Add a bio
                                    </a>
                                </div>
                            </div>
                        
                        <?php endif ?>

                        <div class="items set">
                            <h4 class="margin-btm-50em ">
                                <bold class="dark-gray">
                                    Items
                                </bold> 
                                
                                <?php if (!empty($items)): ?>

                                    <light class="light-gray">
                                        (<?= count($items) ?>)
                                    </light>

                                <?php endif; ?>

                            </h4>

                            <div class="muted margin-btm-1em">
                                <?= ($wholesale_active) ? 'Wholesale' : 'Retail' ?> items for sale from <?= $SellerAccount->name ?>

                                &nbsp;

                                <?php if ($wholesale_relationship): ?>
                                    
                                    <?php if ($wholesale_active): ?>
                                        <a href="<?= PUBLIC_ROOT . $SellerAccount->link . '?retail=true' ?>" class="badge badge-secondary">
                                            Switch to retail
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= PUBLIC_ROOT . $SellerAccount->link ?>" class="badge badge-secondary">
                                            Switch to wholesale
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    <?php endif ?>
                                        
                                <?php else: ?>

                                    <a href="#" id="request-wholesale" class="badge badge-secondary" data-seller-id="<?= $SellerAccount->id ?>">
                                        <i class="fa fa-cutlery"></i> Request wholesale pricing
                                    </a>

                                <?php endif ?>

                            </div>

                            <?php if (!empty($categorized_items)): ?>
                                
                                <?php foreach ($categorized_items as $category_id => $subcategories): ?>
                                    
                                    <h6 class="muted heavy margin-top-1em">
                                        <?= ucfirst($hashed_categories[$category_id]) ?>
                                    </h6>

                                    <div class="row">

                                        <?php foreach ($subcategories as $subcategory_id => $varieties): ?>
                                        
                                            <?php foreach ($varieties as $variety_id => $options): ?>
                                            
                                                <?php
                                                
                                                $rev = array_reverse($options);
                                                $key = array_pop($rev)->id;
                                                $FirstOption = $options[$key];

                                                $in_cart = isset($User, $User->BuyerAccount->ActiveOrder, $User->BuyerAccount->ActiveOrder->Growers[$SellerAccount->id], $User->BuyerAccount->ActiveOrder->Growers[$SellerAccount->id]->Items[$FirstOption->id]);

                                                if ($in_cart) {
                                                    $OrderItem = $User->BuyerAccount->ActiveOrder->Growers[$SellerAccount->id]->Items[$FirstOption->id];
                                                }
                                                
                                                ?>

                                                <div class="col-md-4">
                                                    <div class="item card no-hover animated zoomIn">
                                                        <div class="card-img-top">
                                                            <a href="<?= PUBLIC_ROOT . "{$SellerAccount->link}/{$FirstOption->link}" ?>">

                                                                <?php if (!empty($FirstOption->Image->filename)): ?>
                                                                    
                                                                    <?= _img(ENV . '/item-images/' . $FirstOption->Image->filename, $FirstOption->Image->ext, [
                                                                        'server'    => 'S3',
                                                                        'class'     => 'img-fluid animated fadeIn hidden'
                                                                    ]); ?>

                                                                    <div class="loading">
                                                                        <i class="fa fa-circle-o-notch loading-icon"></i>
                                                                    </div>

                                                                <?php else: ?>

                                                                    <?= _img('placeholders/default-thumbnail', 'jpg', [
                                                                        'server'    => 'local', 
                                                                        'class'     => 'animated fadeIn img-fluid rounded'
                                                                    ]); ?>

                                                                    <?php if ($is_owner): ?>

                                                                        <?= "<a href=\"" . PUBLIC_ROOT . "dashboard/selling/items/edit?id={$FirstOption->id}\" class=\"btn btn-cta btn-block margin-top-50em\">Add an item image</a>" ?>
                                                                    
                                                                    <?php endif; ?>

                                                                <?php endif; ?>

                                                            </a>
                                                        </div>

                                                        <div class="card-body d-flex flex-column">
                                                            <div class="card-title margin-btm-50em">
                                                                <h5 class="price dark-gray heavy">
                                                                    <?= _amount($FirstOption->price) ?>
                                                                    
                                                                    <?php if (!empty($FirstOption->measurement) && is_numeric($FirstOption->measurement)): ?>
                                                                    
                                                                        <small class="light-gray">
                                                                            (<?= _amount($FirstOption->price/$FirstOption->measurement) . "/{$FirstOption->metric}" ?>)
                                                                        </small>
                                                                    
                                                                    <?php endif ?>

                                                                    <small class="rating float-right">
                                                                        <?= stars($FirstOption->average_rating) ?>
                                                                    </small>
                                                                </h5>
                                                            </div>

                                                            <div class="title muted margin-btm-50em">
                                                                <a href="<?= PUBLIC_ROOT . "{$SellerAccount->link}/{$FirstOption->link}" ?>">
                                                                    <?= $FirstOption->title ?>
                                                                    <!-- <?= ucfirst(((!empty($hashed_varieties[$variety_id])) ? "{$hashed_varieties[$variety_id]}&nbsp;" : '') . $hashed_subcategories[$subcategory_id]) ?> -->
                                                                </a>

                                                                <?php if ($wholesale_active): ?>
                                                                
                                                                    <i class="fa fa-cutlery float-right" data-toggle="tooltip" data-title="Wholesale item"></i>

                                                                <?php endif ?>
                                                            
                                                            </div>

                                                            <form id="quick-add-<?= $FirstOption->id ?>" class="quick-add">
                                                                <input type="hidden" name="seller-id"       value="<?= $SellerAccount->id ?>"/>
                                                                <input type="hidden" name="exchange"        value="<?php if (isset($OrderGrower, $OrderGrower->Exchange)) echo $OrderGrower->Exchange->type ?>"/>
                                                                <input type="hidden" name="distance-miles"  value="<?php if (isset($distance_miles)) echo $distance_miles ?>"/>

                                                                <div class="form-group">
                                                                    <div class="input-group double-select">
                                                                        <select name="item-id" class="item-option custom-select">

                                                                            <?php foreach ($options as $k => $ItemOption): ?>

                                                                                <option value="<?= $ItemOption->id ?>">
                                                                                    <?= $ItemOption->package_metric_title ?>
                                                                                </option>

                                                                            <?php endforeach ?>

                                                                        </select>

                                                                        <select name="quantity" class="item-quantity custom-select <?php if (!$FirstOption->quantity) echo 'hidden' ?>" data-parsley-trigger="change" required>
                                                                        
                                                                            <?php for ($i = 1; $i <= $FirstOption->quantity; $i++): ?>
                                                                                    
                                                                                <option value="<?= $i ?>" <?php if ($in_cart && $OrderItem->quantity == $i) echo 'selected' ?>>
                                                                                    <?= $i ?>
                                                                                </option>
                                                                                
                                                                            <?php endfor ?>
                                                                            
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <input type="submit" class="btn btn-sm btn-block btn-<?= ($FirstOption->quantity) ? 'cta' : 'light' ?>" value="<?= ($FirstOption->quantity) ? (($in_cart) ? 'Update item in basket' : 'Add to basket') : 'Out of stock' ?>" <?php if (!$FirstOption->quantity) echo 'disabled' ?>>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                        
                                            <?php endforeach // end varieties ?>

                                        <?php endforeach // end subcategories ?>

                                    </div>
                                
                                <?php endforeach // end categories?>

                            <?php else: ?>

                                <div class="callout">
                                    <?= $SellerAccount->name ?> doesn't have any <?= ($wholesale_active) ? 'wholesale' : 'retail' ?> items for sale yet
                                </div>
                                
                                <?php if ($is_owner): ?>

                                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/add-new" class="btn btn-cta margin-top-1em">
                                        Add your first item
                                    </a>
                                
                                <?php endif ?>

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

                                    <div class="bubble muted">
                                        <fable>
                                            <div class="font-18 thick">
                                                <i class="fa fa-truck" data-toggle="tooltip" data-title="Request that your order be delivered right to you"></i>
                                                Delivery
                                            </div>

                                            <cell class="strong flexend">
                                                <?= ($SellerAccount->Delivery->delivery_type == 'free' ? 'Free' : _amount($SellerAccount->Delivery->fee) . ' ' . str_replace('-', ' ', $SellerAccount->Delivery->pricing_rate)); ?>
                                            </cell>
                                        </fable>
                                        
                                        <div>
                                            Will deliver within <strong><?= $SellerAccount->Delivery->distance ?></strong> miles
                                        </div>

                                        <?php if ($SellerAccount->Delivery->delivery_type == 'conditional'): ?>
                                            
                                            <div>
                                                Free delivery within <strong><?= $SellerAccount->Delivery->free_distance ?></strong> miles
                                            </div>

                                        <?php endif; ?>
                                    </div>

                                <?php endif ?>

                                <?php if (!empty($meetups)): ?>
                            
                                    <?php foreach ($meetups as $meetup): ?>
                                    
                                        <!-- <div class="row">
                                            <div class="col-md-"> -->
                                                <div class="bubble muted margin-btm-1em">
                                                    <fable>
                                                        <cell class="font-18 thick">
                                                            <i class="fa fa-map-signs" data-toggle="tooltip" data-title="Meet here at the time specified to pick up your order"></i>
                                                            <?= (!empty($meetup['title'])) ? $meetup['title'] : "{$meetup['address_line_1']} {$meetup['address_line_2']}" ?>
                                                        </cell>
                                                        
                                                        <cell class="flexend">
                                                            <?= "<strong>{$meetup['day']}s</strong>" ?>
                                                        </cell>
                                                    </fable>

                                                    <fable class="margin-0">

                                                        <?php if (!empty($meetup['title'])): ?>
                                                            
                                                            <cell>
                                                                <?= "{$meetup['address_line_1']} {$meetup['address_line_2']}" ?>
                                                            </cell>

                                                        <?php else: ?>
                                                        
                                                            <cell>
                                                                <?= "{$meetup['city']}, {$meetup['state']}" ?>
                                                            </cell>

                                                        <?php endif ?>

                                                        <cell class="flexend">
                                                            <?= "{$meetup['start_time']} &ndash; {$meetup['end_time']}" ?>
                                                        </cell>
                                                    </fable>

                                                    <fable class="margin-0">

                                                        <?php if (!empty($meetup['deadline'])): ?>
                                                        
                                                            <cell>
                                                                <?= _amount($meetup['order_minimum']) ?>
                                                                minimum order
                                                            </cell>
                                                        
                                                        <?php endif ?>
                                                        
                                                        <?php if (!empty($meetup['order_minimum'])): ?>
                                                    
                                                            <cell class="flexend">
                                                                Order
                                                                <!-- <span class="warning"> -->
                                                                    <?= $meetup['deadline'] ?> hours
                                                                <!-- </span> -->
                                                                in advance
                                                            </cell>
                                                        
                                                        <?php endif ?>

                                                    </fable>

                                                </div>
                                            <!-- </div>
                                        </div> -->
                                
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

                                    <?php $ReviewBuyer = new BuyerAccount([
                                        'DB' => $DB,
                                        'id' => $rating['buyer_account_id']
                                    ]); ?>           
                                    
                                    <div class="user-block margin-btm-1em">
                                        <a href="<?= PUBLIC_ROOT . $ReviewBuyer->link ?>">             
                                            <div class="user-photo" style="background-image: url(<?= (!empty($ReviewBuyer->Image->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/buyer-account-images/{$ReviewBuyer->Image->filename}.{$ReviewBuyer->Image->ext}" /* . '?' . time() */ : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg') ?>);"></div>
                                        </a>

                                        <div class="user-content">
                                            <p class="muted margin-btm-25em">
                                                &quot;<?= $rating['review'] ?>&quot;
                                            </p>

                                            <small class="flexstart">
                                                <a href="<?= PUBLIC_ROOT ?>$ReviewBuyer->link" class="strong">
                                                    <?= $ReviewBuyer->name ?>
                                                </a>
                                                &bull;
                                                <?= "{$ReviewBuyer->Address->city}, {$ReviewBuyer->Address->state}" ?>
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
    var lat         = <?= (isset($SellerAccount)) ? number_format($SellerAccount->latitude, 2) : 0 ?>;
    var lng         = <?= (isset($SellerAccount)) ? number_format($SellerAccount->longitude, 2) : 0 ?>;
    var user        = <?= (isset($User)) ? $User->id : 0 ?>;
    var seller_name = '<?= $SellerAccount->name ?>';
    var seller_link = '<?= $SellerAccount->link ?>';
    var buyer_name  = '<?= (isset($User, $User->BuyerAccount)) ? $User->BuyerAccount->name : '' ?>';
    var items       = <?= json_encode($hashed_items) ?>
</script>