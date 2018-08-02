<!-- cont main -->
    <div class="container animated fadeIn">
        
        <?php if (isset($OrderGrower) && $OrderGrower->grower_operation_id == $User->GrowerOperation->id && $OrderGrower->Status->current == 'pending fulfillment'): ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Pending order <span class="text-muted">(ID: <?= "{$Order->id}0{$OrderGrower->id}" ?>)</span>
                    </div>
                        
                    <div class="page-description text-muted small">
                        Great! Now that you've confirmed this order it's your responsibility to make sure it gets fulfilled. Only once fulfillment is complete will the payout process continue.
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="controls">
                        <input id="ordergrower-id" type="hidden" value="<?= $OrderGrower->id ?>">

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
                                <?= _amount($OrderGrower->total) ?>
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

                            <?php if ($OrderGrower->Exchange->type == 'delivery'): ?>

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

                            <?php endif ?>

                        </div>
                        
                        <div id="buyer-info" class="block animated zoomIn">
                            <div class="user-block flexjustifycenter">
                                <div class="user-photo" style="background-image: url('<?= (!empty($BuyerAccount->Image->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/buyer-account-images/{$BuyerAccount->Image->filename}.{$BuyerAccount->Image->ext}" . '?' . time() : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>');"></div>

                                <div class="user-content flexgrow-0">
                                    <h5 class="bold margin-btm-25em">
                                        <?= $BuyerAccount->name ?>
                                    </h5>

                                    <?php if (!empty($BuyerAccount->Address->city) && !empty($BuyerAccount->Address->city)): ?>

                                        <small>
                                            <?= "{$BuyerAccount->Address->city}, {$BuyerAccount->Address->state}" ?>
                                        </small>

                                    <?php endif ?>

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
                                <?= $items_sold ?>
                            </div>

                            <div class="descriptor">
                                Items sold
                            </div>
                        </div>

                        <div id="items">
                            
                            <?php foreach($OrderGrower->Items as $OrderItem): ?>

                                <?php $Item = new Item([
                                    'DB' => $DB,
                                    'id' => $OrderItem->item_id
                                ]) ?>
                                
                                <div class="item card-alt margin-top-1em animated zoomIn">
                                    <div class="item-image">
                                        <div class="user-photo no-margin" style="background-image: url('<?= (isset($Item->Image->id) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/item-images/{$Item->Image->filename}.{$Item->Image->ext}" : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg') ?>');"></div>
                                    </div>

                                    <div class="card-body">
                                        <h6 class="strong">
                                            <a href="<?= PUBLIC_ROOT . $User->GrowerOperation->link . '/' . $Item->link; ?>">
                                                <?= ucfirst($Item->title) ?>
                                            </a>

                                            <span class="float-right">
                                                <small>
                                                    x
                                                </small>
                                                
                                                <?= $OrderItem->quantity ?>
                                            </span>
                                        </h6>
                                        
                                        <small class="light-gray">
                                            <?= ucfirst(((!empty($OrderItem->measurement) && !empty($OrderItem->metric)) ? "{$OrderItem->measurement} {$OrderItem->metric} {$OrderItem->package_type}" : $OrderItem->package_type)) ?>
                                            
                                            <span class="float-right">
                                                <?= _amount($OrderItem->total) ?>
                                            </span>
                                        </small>
                                    </div>
                                </div>
                                
                            <?php endforeach ?>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div id="exchange-method" class="block animated zoomIn">
                            <div class="value">
                                <?= ($OrderGrower->Exchange->type == 'delivery') ? 'Delivery' : 'Meetup' ?>
                            </div>

                            <div class="descriptor">
                                Exchange method
                            </div>
                        </div>

                        <div id="exchange-info" class="block animated zoomIn">
                            <div class="callout">
                                <h6>
                                    Location
                                </h6>
                                
                                <p>
                                    <?= $OrderGrower->Exchange->address_line_1 . (($OrderGrower->Exchange->address_line_2) ? ', ' . $OrderGrower->Exchange->address_line_2 : ''); ?>
                                </p>

                                <p>
                                    <?= "{$OrderGrower->Exchange->city}, {$OrderGrower->Exchange->state} {$OrderGrower->Exchange->zipcode}"; ?>
                                </p>
                            </div>

                            <?php if ($OrderGrower->Exchange->type == 'delivery'): ?>
                                
                                <div class="callout">
                                    <h6>
                                        Delivery distance
                                    </h6>

                                    <p>
                                        <?= "{$OrderGrower->Exchange->distance} miles" ?>
                                    </p>
                                </div>

                                <div class="callout">
                                    <h6>
                                        Your delivery fee
                                    </h6>

                                    <p>
                                        <?= _amount($OrderGrower->Exchange->fee) ?>
                                    </p>
                                </div>

                            <?php elseif (is_numeric($OrderGrower->Exchange->type)): ?>

                                <div class="callout">
                                    <h6>
                                        Time
                                    </h6>

                                    <p>
                                        <?= $OrderGrower->Exchange->time ?>
                                    </p>
                                </div>

                            <?php endif ?>

                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <div class="block strong">
                Oops, looks like you found your way here by mistake &hellip; nothing to see here!
            </div>

        <?php endif ?>

    </div>
</main>