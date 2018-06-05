<div id="cart" off-canvas="slidebar-right right push">
    <?php
    
    $is_active_cart =  isset($User->BuyerAccount) && $User->BuyerAccount->ActiveOrder && !empty($User->BuyerAccount->ActiveOrder->Growers);
    
    if ($is_active_cart) {
        echo '<div id="ordergrowers">';

        foreach($User->BuyerAccount->ActiveOrder->Growers as $OrderGrower) {
            $Grower = new GrowerOperation([
                'DB' => $DB,
                'id' => $OrderGrower->grower_operation_id
            ],[
                'exchange' => true
            ]);

            ?>

            <div id="ordergrower-<?= $OrderGrower->id; ?>" class="set" data-grower-operation-id="<?= $Grower->id; ?>">
                <h6>
                    <a href="<?= PUBLIC_ROOT . $Grower->link; ?>">
                        <?= $Grower->name; ?>
                    </a>
                </h6>

                <?php

                if (!empty($OrderGrower->Items)) {
                    echo '<div class="cart-items">';

                    foreach ($OrderGrower->Items as $CartItem) {
                        $ItemItem = new Item([
                            'DB' => $DB,
                            'id' => $CartItem->item_id
                        ]);
    
                        ?>
    
                        <div class="cart-item" data-item-id="<?= $ItemItem->id; ?>">
                            <div class="item-image">
                                <?php
                                
                                if (!empty($ItemItem->filename)) {
                                    img(ENV . '/items/' . $ItemItem->filename, $ItemItem->ext, [
                                        'server'    => 'S3',
                                        'class'     => 'img-fluid'
                                    ]);
                                } else {
                                    img('placeholders/default-thumbnail', 'jpg', [
                                        'server'    => 'local', 
                                        'class'     => 'img-fluid rounded'
                                    ]);
                                }
                                
                                ?>
                            </div>
                            
                            <div class="item-content">
                                <div class="item-title">
                                    <a href="<?= PUBLIC_ROOT . $Grower->link . '/' . $ItemItem->link; ?>">
                                        <?= $ItemItem->title; ?>
                                    </a>
                                </div>
    
                                <div class="item-details">
                                    <select class="custom-select">
                                    
                                    <?php
                                                            
                                    for ($i = 1; $i <= $ItemItem->quantity; $i++) {
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
                        <?php

                        $exchange_options_available = [];
                            
                        if ($Grower->Delivery && $Grower->Delivery->is_offered)   $exchange_options_available []= 'delivery';
                        if ($Grower->Pickup && $Grower->Pickup->is_offered)       $exchange_options_available []= 'pickup';
                        if ($Grower->Meetup && $Grower->Meetup->is_offered)       $exchange_options_available []= 'meetup';

                        echo "<select class=\"custom-select ordergrower-exchange\" name=\"exchange\">";
                        
                        foreach($exchange_options_available as $option) {
                            echo "<option value=\"{$option}\" " . ($OrderGrower->Exchange->type == $option ? "selected" : "") . ">" . ucfirst($option) . "</option>";
                        }
                        
                        echo "</select>";

                        ?>
                        
                        <div class="rate exchange-fee">
                            <?php
                            
                            if ($OrderGrower->Exchange->type == 'delivery') {
                                if ($OrderGrower->Exchange->fee > 0) {
                                    amount($OrderGrower->Exchange->fee);
                                } else {
                                    echo 'Free';
                                }
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
                $<?= number_format(((isset($User) && $User->BuyerAccount->ActiveOrder) ? $User->BuyerAccount->ActiveOrder->subtotal : 0) / 100, 2); ?>
            </div>
        </div>
        
        <div class="line-amount <?php if (!isset($User) || !$User->BuyerAccount->ActiveOrder || isset($User) && $User->BuyerAccount->ActiveOrder->exchange_fees == 0) { echo 'hidden'; } ?>">
            <a class="label" data-toggle="tooltip" data-placement="top" data-title="This is the sum of all delivery fees">
                Delivery
                <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-title="This is the sum of all delivery fees"></i> -->
            </a>

            <div class="rate exchange-fee">
                $<?= number_format(((isset($User) && $User->BuyerAccount->ActiveOrder) ? $User->BuyerAccount->ActiveOrder->exchange_fees : 0) / 100, 2); ?>
            </div>
        </div>

        <div class="line-amount">
            <a class="label" data-toggle="tooltip" data-placement="top" data-title="This enables us to run our platform!">
                Service fee
                <!-- <i class="fa fa-info-circle"></i> -->
            </a>

            <div class="rate service-fee">
                $<?= number_format(((isset($User) && $User->BuyerAccount->ActiveOrder) ? $User->BuyerAccount->ActiveOrder->fff_fee : 0) / 100, 2); ?>
            </div>
        </div>

        <div id="total" class="line-amount">
            <div class="label">
                Total
            </div>

            <div class="rate total">
                $<?= number_format(((isset($User) && $User->BuyerAccount->ActiveOrder) ? $User->BuyerAccount->ActiveOrder->total : 0) / 100, 2); ?>
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