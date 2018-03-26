<!-- cont main -->
    <div class="container animated fadeIn">
        <?php

        if (isset($OrderGrower) && $OrderGrower->grower_operation_id == $User->GrowerOperation->id && $OrderGrower->Status->current == 'pending fulfillment') {

            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Pending order <span class="text-muted">(ID: <?php echo "{$Order->id}0{$OrderGrower->id}"; ?>)</span>
                    </div>
                        
                    <div class="page-description text-muted small">
                        Great! Now that you've confirmed this order it's your responsibility to make sure it gets fulfilled. Only once fulfillment is complete will the payout process continue.
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="controls">
                        <input id="ordergrower-id" type="hidden" value="<?php echo $OrderGrower->id; ?>">

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
                                <?php echo amount($OrderGrower->total); ?>
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
                                    <?php echo $time_elapsed['full']; ?>
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
                                        <span class="warning"><?php echo $time_until['full']; ?></span>
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
                                <div class="user-photo" style="background-image: url('<?php echo (!empty($Buyer->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $Buyer->filename . '.' . $Buyer->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>');"></div>

                                <div class="user-content flexgrow-0">
                                    <h5 class="bold margin-btm-25em">
                                        <?php echo $Buyer->name; ?>
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

                            <a href="<?php echo PUBLIC_ROOT . 'dashboard/messages/inbox/selling/thread?' . (($User->GrowerOperation->type != 'individual') ? 'grower=' . $User->GrowerOperation->id . '&' : '') . 'user=' . $Buyer->id;?>" class="btn btn-primary margin-top-1em margin-w-1em" style="display: block;">
                                Message
                            </a>
                        </div>
                    </div>   

                    <div class="col-md-4">
                        <div id="items-sold" class="block animated zoomIn">
                            <div class="value">
                                <?php echo $items_sold; ?>
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
                                
                                <a href="<?php echo PUBLIC_ROOT . $User->GrowerOperation->link . '/' . $FoodListing->link; ?>" class="card animated zoomIn">
                                    <div class="item-image">
                                        <?php img(ENV . '/items/fl.' . $FoodListing->id, $FoodListing->ext, 'S3', 'img-fluid'); ?>
                                    </div>

                                    <div class="card-body muted">
                                        <div class="listing-info">
                                            <h5 class="card-title">
                                                <span>
                                                    <?php echo ucfirst($FoodListing->title); ?>
                                                </span>
                                            </h5>
                                            
                                            <fable>
                                                <cell>
                                                    <strong class="rounded-circle success no-margin"><span class="white"><?php echo $OrderListing->quantity; ?></span></strong>
                                                </cell>
                                                
                                                <cell>
                                                    <?php echo bcmul($OrderListing->quantity, $OrderListing->unit_weight) . ' ' . $OrderListing->weight_units; ?>
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
                        <div id="exchange-method" class="block animated zoomIn">
                            <div class="value">
                                <?php echo ucfirst($OrderGrower->Exchange->type); ?>
                            </div>

                            <div class="descriptor">
                                Exchange method
                            </div>
                        </div>

                        <div id="exchange-info" class="block animated zoomIn">
                            <div class="callout">
                                <h6>
                                    <?php echo $OrderGrower->Exchange->type; ?> location
                                </h6>
                                
                                <p>
                                    <?php echo $OrderGrower->Exchange->address_line_1 . (($OrderGrower->Exchange->address_line_2) ? ', ' . $OrderGrower->Exchange->address_line_2 : ''); ?>
                                </p>

                                <p>
                                    <?php echo "{$OrderGrower->Exchange->city}, {$OrderGrower->Exchange->state} {$OrderGrower->Exchange->zipcode}"; ?>
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
                                        <?php echo "{$OrderGrower->Exchange->distance} miles"; ?>
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
                                        <?php echo $OrderGrower->Exchange->instructions; ?>
                                    </p>
                                </div>

                                <div class="callout">
                                    <h6>
                                        time
                                    </h6>

                                    <p>
                                        <?php echo $OrderGrower->Exchange->time; ?>
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
                                        <?php echo $OrderGrower->Exchange->time; ?>
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