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

                            foreach($OrderGrower->FoodListings as $OrderListing) {

                                $FoodListing = new FoodListing([
                                    'DB' => $DB,
                                    'id' => $OrderListing->food_listing_id
                                ]);
                                
                                ?>
                                
                                <a href="<?= PUBLIC_ROOT . $User->GrowerOperation->link . '/' . $FoodListing->link; ?>" class="card animated zoomIn muted">
                                    <div class="item-image">
                                        <?php
                                        
                                        img(ENV . '/items/fl.' . $FoodListing->id, $FoodListing->ext, [
                                            'server'    => 'S3',
                                            'class'     => 'img-fluid'
                                        ]);
                                        
                                        ?>
                                    </div>

                                    <div class="card-body">
                                        <div class="listing-info">
                                            <h5 class="card-title">
                                                <span>
                                                    <?= ucfirst($FoodListing->title); ?>
                                                </span>
                                            </h5>
                                            
                                            <fable>
                                                <cell>
                                                    <strong class="rounded-circle success no-margin"><span class="white"><?= $OrderListing->quantity; ?></span></strong>
                                                </cell>
                                                
                                                <cell>
                                                    <?= bcmul($OrderListing->quantity, $OrderListing->unit_weight) . ' ' . $OrderListing->weight_units; ?>
                                                </cell>

                                                <cell class="float-right">
                                                    <?php amount($OrderListing->total); ?>
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
                        <div id="buyer-info" class="block animated zoomIn">
                            <div class="user-block flexjustifycenter">
                                <div class="user-photo" style="background-image: url('<?= (!empty($Buyer->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $Buyer->filename . '.' . $Buyer->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>');"></div>

                                <div class="user-content flexgrow-0">
                                    <h5 class="bold margin-btm-25em">
                                        <?= $Buyer->name; ?>
                                    </h5>

                                    <small>
                                        <?php
                                        
                                        $city   = (!empty($Buyer->city)) ? $Buyer->city : $Buyer->billing_city;
                                        $state  = (!empty($Buyer->state)) ? $Buyer->state : $Buyer->billing_state;
                                        
                                        echo "{$city}, {$state}";
                                        
                                        ?>
                                    </small>
                                </div>
                            </div>

                            <a href="<?= PUBLIC_ROOT . 'dashboard/selling/messages/thread?buyer=' . $Buyer->id;?>" class="btn btn-primary margin-top-1em margin-w-1em" style="display: block;">
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