<div id="cart" off-canvas="slidebar-right right push">
    <?php
    
    $is_active_cart =  isset($User) && $User->ActiveOrder && !empty($User->ActiveOrder->Growers);
    
    if ($is_active_cart) {
        echo '<div id="ordergrowers">';

        foreach($User->ActiveOrder->Growers as $OrderGrower) {
            $Grower = new GrowerOperation([
                'DB' => $DB,
                'id' => $OrderGrower->grower_operation_id
            ],[
                'details' => true
            ]);

            ?>

            <div id="ordergrower-<?php echo $OrderGrower->id; ?>" class="set">
                <h6>
                    <a href="<?php echo PUBLIC_ROOT . $Grower->link; ?>">
                        <?php echo $Grower->name; ?>
                    </a>
                </h6>

                <?php

                if (!empty($OrderGrower->FoodListings)) {
                    echo '<div class="cart-items">';

                    foreach ($OrderGrower->FoodListings as $CartItem) {
                        $FoodListingItem = new FoodListing([
                            'DB' => $DB,
                            'id' => $CartItem->food_listing_id
                        ]);
    
                        ?>
    
                        <div class="cart-item" data-listing-id="<?php echo $FoodListingItem->id; ?>">
                            <div class="item-image">
                                <?php img(ENV . '/food-listings/fl.' . $FoodListingItem->id, $FoodListingItem->ext, 's3', 'img-fluid'); ?>
                            </div>
                            
                            <div class="item-content">
                                <div class="item-title">
                                    <a href="<?php echo PUBLIC_ROOT . $Grower->link . '/' . $FoodListingItem->link; ?>">
                                        <?php echo $FoodListingItem->title; ?>
                                    </a>
                                </div>
    
                                <div class="item-details">
                                    <select class="custom-select">
                                    
                                    <?php
                                                            
                                    for ($i = 1; $i <= $FoodListingItem->quantity; $i++) {
                                        echo "<option value=\"{$i}\"" . (($i == $CartItem->quantity) ? 'selected' : '') . ">{$i}</option>";
                                    }
                                        
                                    ?>
    
                                    </select>

                                    <div class="item-price">
                                        <?php amount($CartItem->total); ?>
                                    </div>
    
                                    <a class="remove-item">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
    
                        <?php

                    }

                    echo '</div>';
                }

                ?>

                <div class="breakdown">
                    <div class="line-amount">
                        <div class="label exchange">
                            <strong><?php echo ucfirst($OrderGrower->Exchange->type); ?></strong>
                        </div>
                        
                        <div class="rate exchange-fee">
                            <?php
                            
                            if ($OrderGrower->Exchange->type == 'delivery') {
                                amount($OrderGrower->Exchange->fee);
                            } else {
                                echo 'Free';
                            }
                            
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }

        echo '</div>';
    }

    ?>

    <!-- ! just make this a border instead -->
    <hr class="<?php if (!$is_active_cart) echo 'hidden'; ?>">

    <div id="end-breakdown" class="<?php if (!$is_active_cart) echo 'hidden'; ?>">
        <div class="line-amount">
            <a class="label" data-toggle="tooltip" data-placement="top" data-title="This is the sum of all items">
                Subtotal
                <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-title="This is the sum of all items"></i> -->
            </a>

            <div class="rate subtotal">
                $<?php echo number_format(((isset($User) && $User->ActiveOrder) ? $User->ActiveOrder->subtotal : 0) / 100, 2); ?>
            </div>
        </div>
        
        <div class="line-amount <?php if (!isset($User) || !$User->ActiveOrder || isset($User) && $User->ActiveOrder->exchange_fees == 0) { echo 'hidden'; } ?>">
            <a class="label" data-toggle="tooltip" data-placement="top" data-title="This is the sum of all delivery fees">
                Delivery
                <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-title="This is the sum of all delivery fees"></i> -->
            </a>

            <div class="rate exchange-fee">
                $<?php echo number_format(((isset($User) && $User->ActiveOrder) ? $User->ActiveOrder->exchange_fees : 0) / 100, 2); ?>
            </div>
        </div>

        <div class="line-amount">
            <a class="label" data-toggle="tooltip" data-placement="top" data-title="This enables us to run our platform!">
                Service fee
                <!-- <i class="fa fa-info-circle"></i> -->
            </a>

            <div class="rate service-fee">
                $<?php echo number_format(((isset($User) && $User->ActiveOrder) ? $User->ActiveOrder->fff_fee : 0) / 100, 2); ?>
            </div>
        </div>

        <div id="total" class="line-amount">
            <div class="label">
                Total
            </div>

            <div class="rate total">
                $<?php echo number_format(((isset($User) && $User->ActiveOrder) ? $User->ActiveOrder->total : 0) / 100, 2); ?>
            </div>
        </div>

        <button id="checkout-btn" type="submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#checkout-modal">
            Checkout
        </button>
    </div>

    <div id="empty-basket" class="<?php if ($is_active_cart) echo 'hidden'; ?>">
        Your basket is empty!
    </div>
</div>