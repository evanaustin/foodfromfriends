<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Your placed orders
                </div>
        
                <div class="page-description text-muted small">
                    Check this out &mdash; these are the orders you've placed. Each order tab is clickable and color-coded according to the order's status.
                </div>
            </div>
        
            <div class="col-md-6">
                <keymap class="align-right">
                    <key>
                        <span>
                            Awaiting seller confirmation
                        </span>
        
                        <span class="badge badge-info rounded-circle">&nbsp;</span>
                    </key>
        
                    <key>
                        <span>
                            Refunded (buyer canceled/seller canceled/rejected/expired)
                        </span>
        
                        <span class="badge badge-danger rounded-circle">&nbsp;</span>
                    </key>
                    
                    <key>
                        <span>
                            Pending (fulfillment pending/awaiting review/reported)
                        </span>
        
                        <span class="badge badge-warning rounded-circle">&nbsp;</span>
                    </key>
                    
                    <key>
                        <span>
                            Completed
                        </span>
        
                        <span class="badge badge-success rounded-circle">&nbsp;</span>
                    </key>
                </keymap>
            </div>
        </div>

        <?php

        if (isset($placed) && ($placed != false) && count($placed) > 0) {

            ?>

            <hr>

            <div class="alerts"></div>

            <ledger class="orders">

                <?php

                $i = 1;

                foreach ($placed as $order) {

                    $Order = new Order([
                        'DB' => $DB,
                        'id' => $order['id']
                    ]);

                    $seller_count = count($Order->Growers);

                    $placed_on = new Datetime($Order->placed_on);
                    $placed_on = $placed_on->format('F j, Y');

                    $tab_highlight = 'tab-' . (!isset($Order->completed_on) ? 'warning' : 'success');

                    ?>
                    
                    <div class="<?php echo ($i == 1) ? 'opened' : 'closed'; ?> record">
                        <div class="tab <?php //echo $tab_highlight; ?>" data-toggle="collapse" data-target="#order-<?php echo $Order->id;?>" aria-controls="order-<?php echo $Order->id ;?>" aria-label="Toggle order" aria-expanded="<?php echo ($i == 1) ? 'true' : 'false'; ?>"></div>
                        
                        <fable>
                            <cell>
                                <h5>#&nbsp;<strong><?php echo $i; ?></strong></h5>
                            </cell>
                            
                            <cell>
                                <h6>ID:&nbsp;<strong><?php echo $Order->id . '0' . substr((pow($Order->id, 3) - pow($Order->id, 2)), 0, 3); ?></strong></h6>
                            </cell>
                            
                            <cell>
                                <h6><?php echo '<strong>' . $seller_count . '</strong>' . '&nbsp;' . 'seller' . (($seller_count > 1) ? 's' : ''); ?></h6>
                            </cell>
                            
                            <cell>
                                <h6>Placed:&nbsp;<strong><?php echo $placed_on; ?></strong></h6>
                            </cell>
                            
                            <cell>
                                <h5 class="strong"><?php amount($Order->total); ?></h5>
                            </cell>
                        </fable>
                    
                        <ledger class="collapse <?php echo ($i == 1) ? 'show' : ''; ?>" id="order-<?php echo $Order->id;?>">

                            <?php

                            foreach ($Order->Growers as $OrderGrower) {

                                $ThisGrowerOperation = new GrowerOperation([
                                    'DB' => $DB,
                                    'id' => $OrderGrower->grower_operation_id
                                ],[
                                    'details' => true
                                ]);

                                $item_count = count($OrderGrower->FoodListings);

                                $tab_highlight = 'tab-';

                                // determine table highlight color
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
                                
                                <div class="closed record animated fadeIn">
                                    <div class="<?php echo $tab_highlight; ?>" data-toggle="collapse" data-target="#suborder-<?php echo $OrderGrower->id;?>" aria-controls="suborder-<?php echo $OrderGrower->id ;?>" aria-label="Toggle suborder"></div>
                                    
                                    <fable>
                                        <cell class="min-third">
                                            <div class="user-block">
                                                <div class="user-photo" style="background-image: url('<?php echo 'https://s3.amazonaws.com/foodfromfriends/' . ENV . $ThisGrowerOperation->details['path'] . '.' . $ThisGrowerOperation->details['ext']; ?>');"></div>
                                                
                                                <div class="user-content">
                                                    <h5 class="bold">
                                                        <?php echo $ThisGrowerOperation->details['name']; ?>
                                                        
                                                        <!-- ! dynamically construct -->
                                                        <!-- <small class="listing-rating">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </small> -->
                                                    </h5>
                                                </div>
                                            </div>
                                        </cell>

                                        <cell class="justify-center">
                                            <h6><?php echo '<healthy>' . $item_count . '</healthy>' . '&nbsp;' . 'item' . (($item_count > 1) ? 's' : ''); ?></h6>
                                        </cell>
                                        
                                        <cell class="justify-center">
                                            <h6 class="healthy"><?php echo ucfirst($OrderGrower->Exchange->type); ?></h6>
                                        </cell>

                                        <cell>
                                            <h6 class="bold"><?php amount($OrderGrower->total); ?></h6>
                                        </cell>
                                    </fable>
                                    
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
                                    
                                        <fable>
                                            <cell class="bold">
                                                <?php echo ucfirst($OrderGrower->Exchange->type); ?>
                                            </cell>
                                            
                                            <cell class="bold">
                                                <?php
                            
                                                if ($OrderGrower->Exchange->type == 'delivery') {
                                                    amount($OrderGrower->Exchange->fee);
                                                } else {
                                                    echo 'Free';
                                                }
                                                
                                                ?>
                                            </cell>
                                        </fable>

                                        <div class="callout">
                                            <h6>
                                                Location
                                            </h6>
                                            
                                            <p>
                                                <?php echo $OrderGrower->Exchange->address_line_1 . (($OrderGrower->Exchange->address_line_2) ? ' ' . $OrderGrower->Exchange->address_line_2 : '') . ', '. $OrderGrower->Exchange->city . ' ' . $OrderGrower->Exchange->state . ' ' . $OrderGrower->Exchange->zipcode; ?>
                                            </p>
                                        </div>

                                        <?php

                                        if ($OrderGrower->Exchange->type != 'delivery') {
                                            ?>

                                            <div class="callout">
                                                <h6>
                                                    Time
                                                </h6>

                                                <p>
                                                    <?php echo $OrderGrower->Exchange->time; ?>
                                                </p>
                                            </div>

                                            <div class="callout">
                                                <h6>
                                                    Instructions
                                                </h6>

                                                <p>
                                                    <?php echo $OrderGrower->Exchange->instructions; ?>
                                                </p>
                                            </div>

                                            <?php
                                        }

                                        ?>
                                    </div>
                                </div>
                
                                <?php

                            }

                            ?>

                        </ledger>
                    </div>

                    <?php

                    $i++;

                }

                ?>

            </ledger>

            <?php

        } else {
            ?>
            
            <div class="block margin-top-1em healthy">
                You don't have any placed orders
            </div>

            <?php
        }

        ?>
    </div>
</main>