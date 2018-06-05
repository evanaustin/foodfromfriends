<!-- cont main -->
    <div class="container animated fadeIn">
        <?php

        if (isset($OrderGrower) && $OrderGrower->grower_operation_id == $User->GrowerOperation->id && $OrderGrower->Status->current == 'pending fulfillment') {

            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Pending order <span class="text-muted">(ID: <?= "{$Order->id}0{$OrderGrower->id}"; ?>)</span>
                    </div>
                        
                    <div class="page-description text-muted small">
                        Great! Now that you've confirmed this order it's your responsibility to make sure it gets fulfilled. Only once fulfillment is complete will the payout process continue.
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="controls">
                        <input id="ordergrower-id" type="hidden" value="<?= $OrderGrower->id; ?>">

                        <button id="fulfill-order" class="btn btn-success">
                            <i class="pre fa fa-check"></i>
                            Mark as fulfilled
                            <i class="post fa fa-gear loading-icon save"></i>
                        </button>
                    </div>
                </div>
            </div>

            <hr>

            <div class="alerts"></div>

            <div class="seamless total-blocks">
                <div class="row">
                    <div class="col-md-4">
                        <div id="order-total" class="block animated zoomIn">
                            <div class="value">
                                <?= amount($OrderGrower->total); ?>
                            </div>

                            <div class="descriptor">
                                Total value
                            </div>
                        </div>

                        <div id="order-summary" class="block animated zoomIn">
                            <div class="callout">
                                <h6>
                                    Order confirmed
                                </h6>
                                
                                <p>
                                    <?= $time_elapsed['full']; ?>
                                </p>
                            </div>

                            <?php
                            
                            if ($OrderGrower->Exchange->type == 'delivery') {

                                ?>

                                <div class="callout">
                                    <h6>
                                        Time to deliver
                                    </h6>
                                    
                                    <p>
                                        <span class="warning"><?= $time_until['full']; ?></span>
                                    </p>
                                </div>

                                <div class="callout">
                                    <p class="small">
                                        This order should be delivered within 24 hours of confirmation. 
                                        Timeliness is subject to review by your customer.
                                    </p>
                                </div>

                                <?php

                            }

                            ?>
                        </div>
                        
                        <div id="buyer-info" class="block animated zoomIn">
                        <div class="user-block flexjustifycenter">
                            <div class="user-photo" style="background-image: url('<?= (!empty($BuyerAccount->Image->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/buyer-account-images/{$BuyerAccount->Image->filename}.{$BuyerAccount->Image->ext}" . '?' . time() : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>');"></div>

                                <div class="user-content flexgrow-0">
                                    <h5 class="bold margin-btm-25em">
                                        <?= $BuyerAccount->name; ?>
                                    </h5>

                                    <small>
                                        <?= "{$BuyerAccount->Address->city}, {$BuyerAccount->Address->state}" ?>
                                    </small>
                                </div>
                            </div>

                            <a href="<?= PUBLIC_ROOT . 'dashboard/selling/messages/thread?buyer=' . $BuyerAccount->id;?>" class="btn btn-primary margin-top-1em margin-w-1em" style="display: block;">
                                Message
                            </a>
                        </div>
                    </div>   

                    <div class="col-md-4">
                        <div id="items-sold" class="block animated zoomIn">
                            <div class="value">
                                <?= $items_sold; ?>
                            </div>

                            <div class="descriptor">
                                Items sold
                            </div>
                        </div>

                        <div id="items">
                            <?php

                            foreach($OrderGrower->Items as $OrderItem) {

                                $Item = new Item([
                                    'DB' => $DB,
                                    'id' => $OrderItem->item_id
                                ]);
                                
                                ?>
                                
                                <a href="<?= PUBLIC_ROOT . $User->GrowerOperation->link . '/' . $Item->link; ?>" class="card animated zoomIn">
                                    <div class="item-image">
                                        <?php
                                        
                                        img(ENV . '/items/fl.' . $Item->id, $Item->ext, [
                                            'server'    => 'S3',
                                            'class'     => 'img-fluid'
                                        ]);
                                        
                                        ?>
                                    </div>

                                    <div class="card-body muted">
                                        <div class="item-info">
                                            <h5 class="card-title">
                                                <span>
                                                    <?= ucfirst($Item->title); ?>
                                                </span>
                                            </h5>
                                            
                                            <fable>
                                                <cell>
                                                    <strong class="rounded-circle success no-margin"><span class="white"><?= $OrderItem->quantity; ?></span></strong>
                                                </cell>
                                                
                                                <cell>
                                                    <?= bcmul($OrderItem->quantity, $OrderItem->unit_weight) . ' ' . $OrderItem->weight_units; ?>
                                                </cell>

                                                <cell class="float-right">
                                                    <?php amount($OrderItem->total); ?>
                                                </cell>
                                            </fable>
                                        </div>
                                    </div>
                                </a>
                                
                                <?php

                            }

                            ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div id="exchange-method" class="block animated zoomIn">
                            <div class="value">
                                <?= ucfirst($OrderGrower->Exchange->type); ?>
                            </div>

                            <div class="descriptor">
                                Exchange method
                            </div>
                        </div>

                        <div id="exchange-info" class="block animated zoomIn">
                            <div class="callout">
                                <h6>
                                    <?= $OrderGrower->Exchange->type; ?> location
                                </h6>
                                
                                <p>
                                    <?= $OrderGrower->Exchange->address_line_1 . (($OrderGrower->Exchange->address_line_2) ? ', ' . $OrderGrower->Exchange->address_line_2 : ''); ?>
                                </p>

                                <p>
                                    <?= "{$OrderGrower->Exchange->city}, {$OrderGrower->Exchange->state} {$OrderGrower->Exchange->zipcode}"; ?>
                                </p>
                            </div>

                            <?php

                            if ($OrderGrower->Exchange->type == 'delivery') {
                            
                                ?>
                                
                                <div class="callout">
                                    <h6>
                                        Delivery distance
                                    </h6>

                                    <p>
                                        <?= "{$OrderGrower->Exchange->distance} miles"; ?>
                                    </p>
                                </div>

                                <div class="callout">
                                    <h6>
                                        Your delivery fee
                                    </h6>

                                    <p>
                                        <?php amount($OrderGrower->Exchange->fee); ?>
                                    </p>
                                </div>

                                <?php

                            } else if ($OrderGrower->Exchange->type == 'pickup') {
                                
                                ?>

                                <div class="callout">
                                    <h6>
                                        Instructions
                                    </h6>

                                    <p>
                                        <?= $OrderGrower->Exchange->instructions; ?>
                                    </p>
                                </div>

                                <div class="callout">
                                    <h6>
                                        time
                                    </h6>

                                    <p>
                                        <?= $OrderGrower->Exchange->time; ?>
                                    </p>
                                </div>

                                <?php

                            } else if ($OrderGrower->Exchange->type == 'meetup') {
                                
                                ?>

                                <div class="callout">
                                    <h6>
                                        Schedule
                                    </h6>

                                    <p>
                                        <?= $OrderGrower->Exchange->time; ?>
                                    </p>
                                </div>

                                <?php

                            }

                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php

        } else {
            
            ?>

            <div class="block strong">
                Oops, looks like you found your way here by mistake &hellip; nothing to see here!
            </div>

            <?php
            
        }

        ?>
    </div>
</main>