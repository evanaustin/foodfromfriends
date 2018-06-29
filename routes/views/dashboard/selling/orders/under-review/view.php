<!-- cont main -->
    <div class="container animated fadeIn">
        <?php
        
        if (isset($OrderGrower) && $OrderGrower->grower_operation_id == $User->GrowerOperation->id && ($OrderGrower->Status->current == 'open for review' || $OrderGrower->Status->current == 'issue reported')) {

            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Under review order <span class="muted">(ID: <?= "{$Order->id}0{$OrderGrower->id}"; ?>)</span>
                    </div>
                        
                    <div class="page-description text-muted small">
                        This order has been fulfilled and is currently under review by the buyer. Unless the buyer reports an issue, this order will be cleared for payout three days after fulfillment <u>or</u> once the buyer submits a review (whichever is sooner).
                    </div>
                </div>
            </div>

            <hr>

            <div class="seamless total-blocks">
                <div class="row">
                    <div class="col-md-4">
                        <div id="exchange-method" class="block animated zoomIn">
                            <div class="value">
                                <?php amount($OrderGrower->total); ?>
                            </div>

                            <div class="descriptor">
                                Order total
                            </div>
                        </div>

                        <div id="placed-on" class="block animated zoomIn">
                            <div class="callout">
                                <h6>
                                    Order placed
                                </h6>
                                
                                <p>
                                    <?= $date_placed; ?>
                                </p>
                            </div>
                            
                            <div class="callout">
                                <h6>
                                    Order fulfilled
                                </h6>
                                
                                <p>
                                    <?= $date_fulfilled; ?>
                                </p>
                            </div>
                            
                            <?php

                            if ($OrderGrower->Status->current == 'open for review') {
                                
                                $time_until = \Time::until($OrderGrower->Status->fulfilled_on, '3 days');

                                ?>

                                <div class="callout">
                                    <h6>
                                        Time for review
                                    </h6>
                                    
                                    <p>
                                        <span class="warning"><?= $time_until['full']; ?></span>
                                    </p>
                                </div>

                                <?php

                            } else if ($OrderGrower->Status->current == 'issue reported') {
                                
                                $reported_on   = new DateTime($OrderGrower->Status->reported_on, new DateTimeZone('UTC'));
                                $reported_on->setTimezone(new DateTimeZone($User->timezone));
                                $date_reported = $reported_on->format('F j, Y');

                                ?>

                                <div class="callout">
                                    <h6>
                                        Issue reported by buyer
                                    </h6>
                                    
                                    <p>
                                        <span class="warning"><?= $date_reported; ?></span>
                                    </p>
                                </div>

                                <?php

                            }

                            ?>

                            <div class="callout">
                                <h6>
                                    Exchange method
                                </h6>
                                
                                <p>
                                    <?= ucfirst($OrderGrower->Exchange->type); ?>
                                </p>
                            </div>
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
                                            <?= $OrderItem->package_metric_title ?>
                                            
                                            <span class="float-right">
                                                <?php amount($OrderItem->total); ?>
                                            </span>
                                        </small>
                                    </div>
                                </div>
                                
                                <?php

                            }

                            ?>
                        </div>
                    </div>

                    <div class="col-md-4">
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