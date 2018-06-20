<!-- cont main -->
    <div class="container animated fadeIn">
        <?php
        
        if (isset($OrderGrower) && $OrderGrower->grower_operation_id == $User->GrowerOperation->id && $time_elapsed['diff']->days < 1 && $OrderGrower->Status->current == 'not yet confirmed') {

            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        New order <span class="text-muted">(ID: <?= "{$Order->id}0{$OrderGrower->id}"; ?>)</span>
                    </div>
                        
                    <div class="page-description text-muted small">
                        <strong>Confirm</strong> this order to commit to fulfilling the requested items in the manner specified. If you cannot fulfill this order then you may <strong>reject</strong> it without penalty.
                    </div>
                </div>

                <div class="col-md-6">
                    <input type="hidden" id="ordergrower-id" value="<?= $OrderGrower->id; ?>">

                    <div class="controls">
                        <button id="confirm-order" class="btn btn-success">
                            <i class="pre fa fa-check"></i>
                            Confirm
                            <i class="post fa fa-gear loading-icon save"></i>
                        </button>
                        
                        <button id="reject-order" class="btn btn-danger">
                            <i class="pre fa fa-times"></i>
                            Reject
                            <i class="post fa fa-gear loading-icon reject"></i>
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
                                <?php amount($OrderGrower->total); ?>
                            </div>

                            <div class="descriptor">
                                Total value
                            </div>
                        </div>

                        <div id="order-summary" class="block animated zoomIn">
                            <div class="callout">
                                <h6>
                                    Order placed
                                </h6>
                                
                                <p>
                                    <?= "{$day_placed} at {$time_placed}"; ?>
                                </p>
                            </div>

                            <div class="callout">
                                <h6>
                                    Time to expiration
                                </h6>
                                
                                <p>
                                    <span class="warning"><?= $time_until['full']; ?></span>
                                </p>
                            </div>

                            <div class="callout">
                                <p class="small">
                                    This order will expire 24 hours from its time of placement if neither confirmed nor rejected. 
                                    Expired orders negatively impact your grower rating!
                                </p>
                            </div>
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

                            <a href="<?= PUBLIC_ROOT ?>dashboard/selling/messages/thread?buyer=<?= $BuyerAccount->id ?>" class="btn btn-primary margin-top-1em margin-w-1em" style="display: block;">
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
                            
                            <?php foreach($OrderGrower->Items as $OrderItem): ?>

                                <?php $Item = new Item([
                                    'DB' => $DB,
                                    'id' => $OrderItem->item_id
                                ]); ?>
                                
                                <div class="item card-alt margin-top-1em animated zoomIn">
                                    <div class="item-image">
                                        <div class="user-photo no-margin" style="background-image: url('<?= (isset($Item->Image->id) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/item-images/{$Item->Image->filename}.{$Item->Image->ext}" : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg') ?>');"></div>
                                    </div>

                                    <div class="card-body">
                                        <h6 class="strong">
                                            <a href="<?= PUBLIC_ROOT . $User->GrowerOperation->link . '/' . $Item->link; ?>">
                                                <?= ucfirst($Item->title); ?>
                                            </a>

                                            <span class="float-right">
                                                <small>x</small> <?= $OrderItem->quantity; ?>
                                            </span>
                                        </h6>
                                        
                                        <small class="light-gray">
                                            <?= ucfirst(((!empty($OrderItem->measurement) && !empty($OrderItem->metric)) ? "{$OrderItem->measurement} {$OrderItem->metric} {$OrderItem->package_type}" : $OrderItem->package_type)) ?>
                                            
                                            <span class="float-right">
                                                <?php amount($OrderItem->total); ?>
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
                                <?= ucfirst($OrderGrower->Exchange->type); ?>
                            </div>

                            <div class="descriptor">
                                Exchange method
                            </div>
                        </div>

                        <div id="exchange-info" class="block animated zoomIn">
                            <div class="callout">
                                <h6>
                                    <?= $OrderGrower->Exchange->type ?> location
                                </h6>
                                
                                <p>
                                    <?= $OrderGrower->Exchange->address_line_1 . (($OrderGrower->Exchange->address_line_2) ? ', ' . $OrderGrower->Exchange->address_line_2 : ''); ?>
                                </p>

                                <p>
                                    <?= "{$OrderGrower->Exchange->city}, {$OrderGrower->Exchange->state} {$OrderGrower->Exchange->zipcode}" ?>
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
                                        <?= $OrderGrower->Exchange->distance ?> miles
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

                                <div class="callout">
                                    <h6>
                                        Time to deliver
                                    </h6>

                                    <p>
                                        24 hours (from order confirmation)
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
                                        Availability
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