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
                        $Item = new Item([
                            'DB' => $DB,
                            'id' => $CartItem->item_id
                        ]);
    
                        ?>
    
                        <div class="cart-item" data-item-id="<?= $Item->id; ?>">
                            <div class="item-image">
                                <div class="user-photo no-margin" style="background-image: url('<?= (isset($Item->Image->id) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/item-images/{$Item->Image->filename}.{$Item->Image->ext}" : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg') ?>'); height: 60px; width: 60px;"></div>
                            </div>
                            
                            <div class="item-content">
                                <div class="item-title">
                                    <a href="<?= PUBLIC_ROOT . $Grower->link . '/' . $Item->link; ?>">
                                        <?= $Item->title; ?>
                                    </a>
                                </div>

                                <div class="small light-gray">
                                    <?= ucfirst(((!empty($Item->measurement) && !empty($Item->metric)) ? "{$Item->measurement} {$Item->metric} {$Item->package_type}" : $Item->package_type)) ?>
                                </div>
    
                                <div class="item-details">
                                    <select class="custom-select">
                                    
                                    <?php
                                                            
                                    for ($i = 1; $i <= $Item->quantity; $i++) {
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

                        $meetups = $Grower->retrieve([
                            'where' => [
                                'grower_operation_id' => $Grower->id
                            ],
                            'table' => 'meetups'
                        ]);

                        $exchange_options_available = [];
                            
                        if ($Grower->Delivery && $Grower->Delivery->is_offered)   $exchange_options_available []= 'delivery';
                        // if ($Grower->Pickup && $Grower->Pickup->is_offered)       $exchange_options_available []= 'pickup';
                        if ($meetups) $exchange_options_available []= 'meetup';

                        echo "<select class=\"custom-select ordergrower-exchange\" name=\"exchange\">";
                        
                        if ($Grower->Delivery && $Grower->Delivery->is_offered) {
                            echo "<option value=\"delivery\" " . ($OrderGrower->Exchange->type == 'delivery' ? "selected" : "") . ">Delivery</option>";
                        }

                        foreach ($meetups as $meetup) {
                            echo "<option value=\"{$meetup['id']}\" " . ($OrderGrower->Exchange->type == $meetup['id'] ? "selected" : "") . ">" . (!empty($meetup['title']) ? ucfirst($meetup['title']) : $meetup['address_line_1']) . "</option>";
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