<div id="cart" off-canvas="slidebar-right right push">
    <div class="set">
        <h6>
            Dancing Feet
        </h6>

        <div class="cart-item">
            <div class="item-image">
                <?php //img('placeholders/default-thumbnail', 'jpg', 'local', 'img-fluid'); ?>
                <?php img('dev/food-listings/fl.59', 'jpg', 's3', 'img-fluid'); ?>
            </div>
            
            <div class="item-content">
                <div class="item-title">
                    <a href="">
                        Artichokes
                    </a>
                </div>

                <div class="item-details">
                    <select class="custom-select">
                        <option>1</option> 
                    </select>
                    
                    <div class="item-price">
                        $<?php echo number_format(340 / 100, 2); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="cart-item">
            <div class="item-image">
                <?php //img('placeholders/default-thumbnail', 'jpg', 'local', 'img-fluid'); ?>
                <?php img('dev/food-listings/fl.42', 'jpg', 's3', 'img-fluid'); ?>
            </div>
            
            <div class="item-content">
                <div class="item-title">
                    <a href="">
                        Bananas
                    </a>
                </div>

                <div class="item-details">
                    <select class="custom-select">
                        <option>3</option> 
                    </select>
                    
                    <div class="item-price">
                        $<?php echo number_format(69 / 100, 2); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="addon">
            <div class="label">
                Delivery
            </div>
            
            <div class="rate">
                $<?php echo number_format(150 / 100, 2); ?>
            </div>
        </div>
    </div>
    
    <div class="set">
        <h6>
            Dustyn
        </h6>

        <div class="cart-item">
            <div class="item-image">
                <?php //img('placeholders/default-thumbnail', 'jpg', 'local', 'img-fluid'); ?>
                <?php img('dev/food-listings/fl.61', 'jpg', 's3', 'img-fluid'); ?>
            </div>
            
            <div class="item-content">
                <div class="item-title">
                    <a href="">
                        Carrots
                    </a>
                </div>

                <div class="item-details">
                    <select class="custom-select">
                        <option>2</option> 
                    </select>

                    <div class="item-price">
                        $<?php echo number_format(89 / 100, 2); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="addon">
            <div class="label">
                Pickup
            </div>
            
            <div class="rate">
            $<?php echo number_format(0 / 100, 2); ?>
            </div>
        </div>
    </div>

    <?php
    
    foreach($User->ActiveOrder->Growers as $OrderGrower) {
        $Grower = new GrowerOperation([
            'DB' => $DB,
            'id' => $OrderGrower->id
        ]);

        ?>

        <div class="set">
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

            ?>

            <div class="addon">
                <div class="label">
                    <!-- exchange method -->
                </div>
                
                <div class="rate">
                    $<?php echo number_format(0 / 100, 2); ?>
                </div>
            </div>
        </div>

        <?php
    }

    // print_r($User->ActiveOrder->id);

    ?>

    <hr>

    <div class="addon">
        <div class="label">
            Items
        </div>

        <div class="rate">
            $7.25
        </div>
    </div>
    
    <div class="addon">
        <div class="label">
            Delivery
            <!-- <i class="fa fa-info" data-toggle="tooltip" data-placement="top" data-title="This is the sum of all delivery fees"></i> -->
        </div>

        <div class="rate">
            $1.50
        </div>
    </div>

    <div class="addon">
        <div class="label">
            Service fee
            <i class="fa fa-info" data-toggle="tooltip" data-placement="top" data-title="This enables us to run our platform!"></i>
        </div>

        <div class="rate">
            $0.73
        </div>
    </div>

    <div class="addon subtotal">
        <div class="label">
            Total
        </div>

        <div class="rate">
            $9.48
        </div>
    </div>
</div>