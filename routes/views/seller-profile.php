<main>
    <div class="main container">

        <?php if ((isset($Seller) && $Seller->is_active) || $is_owner): ?>

            <?php if ($is_owner): ?>

                <div class="alerts" style="display:block;">
                    <div class="alert alert-<?= (($Seller->is_active) ? 'info' : 'warning') ?>">
                        
                        <?php if ($Seller->is_active): ?>

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
                                            <cell class="<?php if (!$Seller->Meetup || !$Seller->Meetup->is_offered) echo 'inactive' ?>">
                                                Meetup
                                            </cell>
                                            
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
                            <?= $Seller->name ?>

                            <?php //if (!$is_owner): ?>
                            
                                <a href="<?= PUBLIC_ROOT . "dashboard/buying/messages/thread?seller={$Seller->id}" ?>">
                                    <div id="message" class="float-right btn btn-primary" data-toggle="tooltip" data-placement="bottom" data-title="Message">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                </a>
                            
                            <?php //endif ?>

                            <?php // if (isset($User->BuyerAccount)): ?>

                                <div id="request-wholesale" class="float-right btn btn-muted margin-right-1em" data-seller-id="<?= $Seller->id ?>" data-toggle="tooltip" data-placement="bottom" data-title="Request wholesale account">
                                    <i class="fa fa-cutlery"></i>
                                </div>

                            <?php // endif ?>

                        </h2>

                        <div class="muted normal margin-btm-25em">
                            <span class="brand">
                                <?= $grower_stars ?>
                            </span>
                            
                            <?php if (count($ratings) > 0): ?>
                                
                                <div class="rounded-circle">
                                    <?= count($ratings) ?>
                                </div>

                            <?php endif; ?>
                            
                            <?php if (isset($Seller->city, $Seller->state)): ?>
                                
                                <?= "{$Seller->city}, {$Seller->state}" ?>
                            
                            <?php endif; ?>
                            
                            <?php if (!empty($distance)): ?>
                                
                                &bull; <?= "{$distance[0]} {$distance[1]}" ?> away

                            <?php endif; ?>

                        </div>

                        <div class="muted bold margin-btm-1em">
                            Joined in <?= $joined_on->format('F\, Y') ?>
                        </div>

                        <?php if (!empty($Seller->bio)): ?>
                            
                            <p class="muted margin-btm-2em">
                                <?= $Seller->bio ?>
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
                            <?= ($wholesale_relationship && (!isset($_GET['retail']) || (isset($_GET['retail']) && $_GET['retail'] == 'false'))) ? 'Wholesale' : 'Retail' ?> items for sale from <?= $Seller->name ?>

                                <?php if ($wholesale_relationship): ?>
                                    
                                    <?php if (!isset($_GET['retail']) || isset($_GET['retail']) && $_GET['retail'] == 'false'): ?>
                                        &nbsp;    
                                        <a href="<?= PUBLIC_ROOT . $Seller->link . '?retail=true' ?>" class="badge badge-secondary">
                                            Switch to retail
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    <?php elseif (isset($_GET['retail']) && $_GET['retail'] == true): ?>
                                        &nbsp;    
                                        <a href="<?= PUBLIC_ROOT . $Seller->link ?>" class="badge badge-secondary">
                                            Switch to wholesale
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    <?php endif ?>

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

                                                $in_cart = isset($User, $User->BuyerAccount->ActiveOrder, $User->BuyerAccount->ActiveOrder->Growers[$Seller->id], $User->BuyerAccount->ActiveOrder->Growers[$Seller->id]->Items[$FirstOption->id]);

                                                if ($in_cart) {
                                                    $OrderItem = $User->BuyerAccount->ActiveOrder->Growers[$Seller->id]->Items[$FirstOption->id];
                                                }
                                                
                                                ?>

                                                <div class="col-md-4">
                                                    <div class="item card no-hover animated zoomIn">
                                                        <div class="card-img-top">
                                                            <a href="<?= PUBLIC_ROOT . "{$Seller->link}/{$FirstOption->link}" ?>">

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
                                                            <fable class="card-title margin-btm-50em">
                                                                <cell>
                                                                    <h5 class="price dark-gray heavy">
                                                                        <?= _amount($FirstOption->price) ?>
                                                                    </h5>
                                                                </cell>

                                                                <cell class="justify-content-end">
                                                                    <span class="rating small brand">
                                                                        <?= stars($FirstOption->average_rating) ?>
                                                                    </span>
                                                                </cell>
                                                            </fable>

                                                            <div class="title muted margin-btm-50em">
                                                                <a href="<?= PUBLIC_ROOT . "{$Seller->link}/{$FirstOption->link}" ?>">
                                                                    <?= $FirstOption->title ?>
                                                                    <!-- <?= ucfirst(((!empty($hashed_varieties[$variety_id])) ? "{$hashed_varieties[$variety_id]}&nbsp;" : '') . $hashed_subcategories[$subcategory_id]) ?> -->
                                                                </a>
                                                            </div>

                                                            <form id="quick-add-<?= $FirstOption->id ?>" class="quick-add">
                                                                <input type="hidden" name="seller-id"       value="<?= $Seller->id ?>"/>
                                                                <input type="hidden" name="exchange-option" value="<?php if (isset($OrderGrower, $OrderGrower->Exchange)) echo $OrderGrower->Exchange->type ?>"/>
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

                                                                <input type="submit" class="btn btn-sm btn-block btn-<?= ($FirstOption->quantity) ? 'cta' : 'danger' ?>" value="<?= ($FirstOption->quantity) ? (($in_cart) ? 'Update item in basket' : 'Add to basket') : 'Out of stock' ?>" <?php if (!$FirstOption->quantity) echo 'disabled' ?>>
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
                                    <?= $Seller->name ?> doesn't have any items for sale yet
                                </div>
                                
                                <?php if ($is_owner): ?>

                                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/add-new" class="btn btn-cta margin-top-1em">
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
    var lat         = <?= (isset($Seller)) ? number_format($Seller->latitude, 2) : 0 ?>;
    var lng         = <?= (isset($Seller)) ? number_format($Seller->longitude, 2) : 0 ?>;
    var user        = <?= (isset($User)) ? $User->id : 0 ?>;
    var seller_name = '<?= $Seller->name ?>';
    var seller_link = '<?= $Seller->link ?>';
    var buyer_name  = '<?= (isset($User, $User->BuyerAccount)) ? $User->BuyerAccount->name : '' ?>';
    var items       = <?= json_encode($hashed_items) ?>
</script>