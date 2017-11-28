<!-- cont main -->
    <div class="container animated fadeIn">
        <?php

        if (isset($OrderGrower) && $OrderGrower->grower_operation_id == $User->GrowerOperation->id) {

            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Pending order <span class="text-muted">(ID: <?php echo $Order->id . '0' . $OrderGrower->id; ?>)</span>
                    </div>
                        
                    <div class="page-description text-muted small">
                        Great! Now that you've confirmed this order it's your responsibility to make sure it gets fulfilled. Only once fulfillment is complete will your payout be issued.
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="controls">
                        <input id="ordergrower-id" type="hidden" value="<?php echo $OrderGrower->id; ?>">

                        <button id="fulfill-order" class="btn btn-primary">
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
                                $<?php echo number_format($OrderGrower->total / 100, 2); ?>
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
                            
                            if ($OrderGrower->exchange_option == 'delivery') {

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
                            <div 
                                class="buyer-photo"
                                style="background-image: url('<?php echo (!empty($Buyer->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $Buyer->filename . '.' . $Buyer->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg'); ?>');"
                            >
                            </div>

                            <div>
                                <?php echo $Buyer->name; ?>
                            </div>

                            <div>
                                <span class="listing-rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
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
                                
                                <a href="<?php echo PUBLIC_ROOT . 'food-listing?id=' . $FoodListing->id; ?>" class="card animated zoomIn">
                                    <div class="item-image">
                                        <?php img(ENV . '/food-listings/fl.' . $FoodListing->id, $FoodListing->ext, 'S3', 'img-fluid'); ?>
                                    </div>

                                    <div class="card-block">
                                        <div class="listing-info">
                                            <h5 class="card-title">
                                                <span>
                                                    <?php echo ucfirst($FoodListing->title); ?>
                                                </span>

                                                <small class="float-right">
                                                    (<?php echo $OrderListing->quantity; ?>)
                                                </small>
                                                
                                            </h5>
                                            
                                            <h6 class="card-subtitle">
                                                <span>
                                                    Total: <?php echo bcmul($OrderListing->quantity, $OrderListing->unit_weight) . ' ' . $OrderListing->weight_units; ?>
                                                </span>
                                                
                                                <span class="float-right">
                                                    $<?php echo number_format($OrderListing->total / 100, 2); ?>
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
                        <div id="exchange-method" class="block animated zoomIn">
                            <div class="value">
                                <?php echo ucfirst($OrderGrower->exchange_option); ?>
                            </div>

                            <div class="descriptor">
                                Exchange method
                            </div>
                        </div>

                        <div id="exchange-info" class="block animated zoomIn">
                            <div class="callout">
                                <h6>
                                    <?php echo $OrderGrower->exchange_option; ?> location
                                </h6>
                                
                                <p>
                                    <?php echo $address_line_1 . (($address_line_2) ? ', ' . $address_line_2 : ''); ?>
                                </p>

                                <p>
                                    <?php echo $city . ', ' . $state . ' ' . $zipcode; ?>
                                </p>
                            </div>

                            <?php

                            if ($OrderGrower->exchange_option == 'delivery') {
                            
                                ?>
                                
                                <div class="callout">
                                    <h6>
                                        Delivery distance
                                    </h6>

                                    <p>
                                        <?php echo $OrderGrower->distance; ?> miles
                                    </p>
                                </div>

                                <div class="callout">
                                    <h6>
                                        Your delivery fee
                                    </h6>

                                    <p>
                                        $<?php echo number_format($OrderGrower->exchange_fee / 100, 2); ?>
                                    </p>
                                </div>

                                <?php

                            } else if ($OrderGrower->exchange_option == 'pickup') {
                                
                                ?>

                                <div class="callout">
                                    <h6>
                                        Instructions
                                    </h6>

                                    <p>
                                        <?php echo $User->GrowerOperation->Pickup->instructions; ?>
                                    </p>
                                </div>

                                <div class="callout">
                                    <h6>
                                        Availability
                                    </h6>

                                    <p>
                                        <?php echo $User->GrowerOperation->Pickup->availability; ?>
                                    </p>
                                </div>

                                <?php

                            } else if ($OrderGrower->exchange_option == 'meetup') {
                                
                                ?>

                                <div class="callout">
                                    <h6>
                                        Schedule
                                    </h6>

                                    <p>
                                        <?php echo $User->GrowerOperation->Meetup->time; ?>
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
            echo 'This is an invalid ID!';
        }

        ?>
    </div>
</main>

<script>
    /* var data    = <?php //echo json_encode($data); ?>;
    var lat     = <?php //echo $Buyer->latitude; ?>;
    var lng     = <?php //echo $Buyer->longitude; ?>; */
</script>