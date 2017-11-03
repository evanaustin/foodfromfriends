<div id="cart" off-canvas="slidebar-right right push">
    <?php
    
    $is_active_cart = $User->ActiveOrder && !empty($User->ActiveOrder->Growers);

    if ($is_active_cart) {
        echo '<div id="ordergrowers">';

        foreach($User->ActiveOrder->Growers as $OrderGrower) {
            $Grower = new GrowerOperation([
                'DB' => $DB,
                'id' => $OrderGrower->id
            ]);

            ?>

            <div id="ordergrower-<?php echo $OrderGrower->id; ?>" class="set">
                <h6>

                    <?php
                        
                    if ($Grower->type == 'none') {
                        $team_members = $Grower->get_team_members();
                        
                        $GrowerUser = new User([
                            'DB' => $DB,
                            'id' => $team_members[0]['id']
                        ]);

                        echo $GrowerUser->first_name;
                    } else {
                        echo $Grower->name;
                    }
                        
                    ?>
                
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
    
                        <div class="cart-item">
                            <div class="item-image">
                                <?php img(ENV . '/food-listings/fl.' . $FoodListingItem->id, $FoodListingItem->ext, 's3', 'img-fluid'); ?>
                            </div>
                            
                            <div class="item-content">
                                <div class="item-title">
                                    <a href="">
                                        <?php echo ucfirst((!empty($FoodListingItem->other_subcategory)) ? $FoodListingItem->other_subcategory : $FoodListingItem->subcategory_title); ?>
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
                                        $<?php echo number_format($CartItem->unit_price / 100, 2); ?>
                                    </div>
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
                        <div class="label">
                            Items
                        </div>
                        
                        <div class="rate item-subtotal">
                            $<?php echo number_format($OrderGrower->subtotal / 100, 2); ?>
                        </div>
                    </div>

                    <div class="line-amount">
                        <div class="label exchange">
                            <?php echo ucfirst($OrderGrower->exchange_option); ?>
                        </div>
                        
                        <div class="rate exchange-fee">
                            $<?php echo number_format((($OrderGrower->exchange_option == 'delivery') ? $OrderGrower->exchange_fee : 0) / 100, 2); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }

        echo '</div>';
    } else {
        echo '<div id="empty-basket">Your basket is empty!</div>';
    }

    ?>

    <!-- ! just make this a border instead -->
    <hr class="<?php echo (!$is_active_cart) ? 'hidden' : ''; ?>">

    <div id="end-breakdown" class="<?php echo (!$is_active_cart) ? 'hidden' : ''; ?>">
        <div class="line-amount">
            <a class="label" data-toggle="tooltip" data-placement="top" data-title="This is the sum of all items">
                Subtotal
                <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-title="This is the sum of all items"></i> -->
            </a>

            <div class="rate subtotal">
                $<?php echo number_format((($User->ActiveOrder) ? $User->ActiveOrder->subtotal : 0) / 100, 2); ?>
            </div>
        </div>
        
        <div class="line-amount">
            <a class="label" data-toggle="tooltip" data-placement="top" data-title="This is the sum of all delivery fees">
                Delivery
                <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-title="This is the sum of all delivery fees"></i> -->
            </a>

            <div class="rate exchange-fee">
                $<?php echo number_format((($User->ActiveOrder) ? $User->ActiveOrder->exchange_fees : 0) / 100, 2); ?>
            </div>
        </div>

        <div class="line-amount">
            <a class="label" data-toggle="tooltip" data-placement="top" data-title="This enables us to run our platform!">
                Service fee
                <!-- <i class="fa fa-info-circle"></i> -->
            </a>

            <div class="rate service-fee">
                $<?php echo number_format((($User->ActiveOrder) ? $User->ActiveOrder->fff_fee : 0) / 100, 2); ?>
            </div>
        </div>

        <div id="total" class="line-amount">
            <div class="label">
                Total
            </div>

            <div class="rate total">
                $<?php echo number_format((($User->ActiveOrder) ? $User->ActiveOrder->total : 0) / 100, 2); ?>
            </div>
        </div>
    </div>
</div>