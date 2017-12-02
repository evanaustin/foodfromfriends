<!-- cont main -->
    <div class="container animated fadeIn">
        <?php

        if (isset($pending) && count($pending) > 0) {

            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Your pending orders
                    </div>

                    <div class="page-description text-muted small">
                        These are the orders you've placed that are still pending in some respect. Click the tabs to toggle order details.
                    </div>
                </div>
            </div>

            <hr>

            <div class="alerts"></div>

            <div class="orders ledger">

                <?php

                $i = 1;

                foreach ($pending as $order) {

                    $Order = new Order([
                        'DB' => $DB,
                        'id' => $order['id']
                    ]);

                    $placed_on = new Datetime($Order->placed_on);
                    $placed_on = $placed_on->format('F d, Y');

                    $tab_highlight = 'tab-' . (!isset($Order->completed_on) ? 'warning' : 'success');

                    ?>
                    
                    <div class="closed record">
                        <div class="tab <?php //echo $tab_highlight; ?>" data-toggle="collapse" data-target="#order-<?php echo $Order->id;?>" aria-controls="order-<?php echo $Order->id ;?>" aria-label="Toggle order"></div>
                        
                        <h5 class="fable">
                            <cell>
                                <strong>Order</strong>
                                &nbsp;
                                <span class="text-muted">(ID: <?php echo $Order->id . '0' . substr((pow($Order->id, 3) - pow($Order->id, 2)), 0, 3); ?>)</span>
                            </cell>

                            <cell>
                                <small><?php echo $placed_on; ?></small>
                            </cell>
                            
                            <cell>
                                <?php amount($Order->total); ?>
                            </cell>
                        </h5>
                    
                        <div class="ledger collapse" id="order-<?php echo $Order->id;?>">

                            <?php

                            foreach ($Order->Growers as $OrderGrower) {

                                $ThisGrowerOperation = new GrowerOperation([
                                    'DB' => $DB,
                                    'id' => $OrderGrower->grower_operation_id
                                ],[
                                    'details' => true
                                ]);

                                $tab_highlight = 'tab-';

                                if (!isset($OrderGrower->confirmed_on) && !isset($OrderGrower->expired_on) && !isset($OrderGrower->rejected_on)) {
                                    $status = 'awaiting confirmation';
                                    $tab_highlight .= 'info';
                                } else if (isset($OrderGrower->confirmed_on) && !isset($OrderGrower->fulfilled_on)) {
                                    $status = 'awaiting fulfillment';
                                    $tab_highlight .= 'warning';
                                } else if (isset($OrderGrower->confirmed_on) && isset($OrderGrower->fulfilled_on)) {
                                    $status = 'completed';
                                    $tab_highlight .= 'success';
                                } else if (!isset($OrderGrower->confirmed_on) && (isset($OrderGrower->expired_on) || isset($OrderGrower->rejected_on))) {
                                    $status = 'failed';
                                    $tab_highlight .= 'danger';
                                }

                                ?>
                                
                                <div class="closed record">
                                    <div class="<?php echo $tab_highlight; ?>" data-toggle="collapse" data-target="#suborder-<?php echo $OrderGrower->id;?>" aria-controls="suborder-<?php echo $OrderGrower->id ;?>" aria-label="Toggle suborder"></div>
                                    
                                    <div class="user-block">
                                        <div class="user-photo" style="background-image: url('<?php echo 'https://s3.amazonaws.com/foodfromfriends/' . ENV . $ThisGrowerOperation->details['path'] . '.' . $ThisGrowerOperation->details['ext']; ?>');"></div>
                                        
                                        <div class="user-content">
                                            <h5 class="bold">
                                                <?php echo $ThisGrowerOperation->details['name']; ?>

                                                <span class="float-right">
                                                    <?php amount($OrderGrower->total); ?>
                                                </span>
                                            </h5>

                                            <!-- ! dynamically construct -->
                                            <!-- <small class="listing-rating">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </small> -->
                                        </div>
                                    </div>
                                    
                                    <div class="collapse" id="suborder-<?php echo $OrderGrower->id;?>">
                                        <?php

                                        foreach ($OrderGrower->FoodListings as $OrderListing) {

                                            $ThisFoodListing = new FoodListing([
                                                'DB' => $DB,
                                                'id' => $OrderListing->food_listing_id
                                            ]);

                                            ?>

                                            <div class="item card-alt animated fadeIn">
                                                <div class="item-image">
                                                    <?php img(ENV . '/food-listings/fl.' . $ThisFoodListing->id, $ThisFoodListing->ext, 'S3', 'img-fluid'); ?>
                                                </div>

                                                <div class="card-block">
                                                    <h6 class="healthy">
                                                        <a href="<?php echo PUBLIC_ROOT . 'food-listing?id=' . $ThisFoodListing->id; ?>">
                                                            <?php echo ucfirst($ThisFoodListing->title); ?>
                                                        </a>

                                                        <span class="float-right">
                                                            <small>x</small> <?php echo $OrderListing->quantity; ?>
                                                        </span>
                                                    </h6>
                                                    
                                                    <small class="light-gray">
                                                        <span>
                                                            <?php

                                                            amount(($OrderListing->unit_price / $OrderListing->unit_weight));
                                                            echo '/' . $OrderListing->weight_units;

                                                            ?>
                                                        </span>
                                                        
                                                        <span class="float-right">
                                                            <?php amount($OrderListing->total); ?>
                                                        </span>
                                                    </small>
                                                </div>
                                            </div>
                            
                                            <?php

                                        }

                                        ?>
                                    
                                        <div class="fable">
                                            <cell>
                                                <?php echo ucfirst($OrderGrower->Exchange->type); ?>
                                            </cell>
                                            
                                            <cell>
                                                <?php echo $OrderGrower->Exchange->address_line_1 . ' ' . $OrderGrower->Exchange->address_line_2; ?>
                                            </cell>
                                            
                                            <cell>
                                                <?php amount($OrderGrower->Exchange->fee); ?>
                                            </cell>
                                        </div>
                                    </div>
                                </div>
                
                                <?php

                            }

                            ?>

                        </div>
                    </div>

                    <?php

                    $i++;

                }

                ?>

            </div>

            <?php

        } else {
            echo 'You have no past orders!';
        }

        ?>
    </div>
</main>