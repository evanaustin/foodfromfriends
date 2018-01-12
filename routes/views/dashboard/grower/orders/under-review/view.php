<!-- cont main -->
    <div class="container animated fadeIn">
        <?php
        
        if (isset($OrderGrower) && $OrderGrower->grower_operation_id == $User->GrowerOperation->id && $OrderGrower->Status->status == 'open for review') {

            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Under review order <span class="muted">(ID: <?php echo "{$Order->id}0{$OrderGrower->id}"; ?>)</span>
                    </div>
                        
                    <div class="page-description text-muted small">
                        This order has been fulfilled and is currently under review by the buyer. It will be cleared for payout three days after fulfillment or once the buyer submits a review, whichever is sooner.
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
                                    <?php echo $date_placed; ?>
                                </p>
                            </div>
                            
                            <div class="callout">
                                <h6>
                                    Order fulfilled
                                </h6>
                                
                                <p>
                                    <?php echo $date_fulfilled; ?>
                                </p>
                            </div>
                            
                            <div class="callout">
                                <h6>
                                    Time for review
                                </h6>
                                
                                <p>
                                    <span class="warning"><?php echo $time_until['full']; ?></span>
                                </p>
                            </div>

                            <div class="callout">
                                <h6>
                                    Exchange method
                                </h6>
                                
                                <p>
                                    <?php echo ucfirst($OrderGrower->Exchange->type); ?>
                                </p>
                            </div>
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
                                
                                <a href="<?php echo PUBLIC_ROOT . 'food-listing?id=' . $FoodListing->id; ?>" class="card animated zoomIn muted">
                                    <div class="item-image">
                                        <?php img(ENV . '/food-listings/fl.' . $FoodListing->id, $FoodListing->ext, 'S3', 'img-fluid'); ?>
                                    </div>

                                    <div class="card-block">
                                        <div class="listing-info">
                                            <h5 class="card-title">
                                                <span>
                                                    <?php echo ucfirst($FoodListing->title); ?>
                                                </span>

                                                <span class="float-right">
                                                    <small>x</small> <?php echo $OrderListing->quantity; ?>
                                                </span>
                                            </h5>
                                            
                                            <h6 class="card-subtitle">
                                                <span>
                                                    Total: <?php echo bcmul($OrderListing->quantity, $OrderListing->unit_weight) . ' ' . $OrderListing->weight_units; ?>
                                                </span>
                                                
                                                <span class="float-right">
                                                    <?php amount($OrderListing->total); ?>
                                                </span>
                                            </h6>
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
                                <div class="user-photo" style="background-image: url('<?php echo (!empty($Buyer->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $Buyer->filename . '.' . $Buyer->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg'); ?>');"></div>

                                <div class="user-content flexgrow-0">
                                    <h5 class="bold margin-btm-25em">
                                        <?php echo $Buyer->first_name; ?>
                                    </h5>

                                    <small>
                                        <?php echo "{$Buyer->city}, {$Buyer->state}"; ?>
                                    </small>
                                </div>
                            </div>

                            <a href="<?php echo PUBLIC_ROOT . 'dashboard/messages/inbox/selling/thread?' . (($User->GrowerOperation->type != 'none') ? 'grower=' . $User->GrowerOperation->id . '&' : '') . 'user=' . $Buyer->id;?>" class="btn btn-primary margin-top-1em margin-w-1em" style="display: block;">
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