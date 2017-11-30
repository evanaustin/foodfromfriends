<!-- cont main -->
<div class="container animated fadeIn">
<?php

if (isset($Order) && $Order->user_id == $User->id) {

    ?>

    <div class="row">
        <div class="col-md-6">
            <div class="page-title">
                Completed order placed
            </div>
                
            <div class="page-description text-muted small">
                <strong>Order ID</strong>: <?php echo substr($Order->stripe_charge_id, -7) . $Order->id; ?>
            </div>
        </div>

        <!-- <div class="col-md-6">
            <div class="controls">
                <input id="ordergrower-id" type="hidden" value="<?php //echo $OrderGrower->id; ?>">

                <button id="confirm-order" class="btn btn-primary <?php //if (isset($OrderGrower->confirmed_on)) { echo 'hidden'; } ?>">
                    <i class="pre fa fa-check"></i>
                    Confirm order
                    <i class="post fa fa-gear loading-icon save"></i>
                </button>

                <button id="fulfill-order" class="btn btn-primary <?php //if (!isset($OrderGrower->confirmed_on)) { echo 'hidden'; } ?>">
                    <i class="pre fa fa-check"></i>
                    Mark as fulfilled
                    <i class="post fa fa-gear loading-icon save"></i>
                </button>
            </div>
        </div> -->
    </div>

    <hr>

    <div class="alerts"></div>

    <div class="seamless total-blocks">
        <div class="row">
            <div class="col-md-4">
                <div id="exchange-method" class="block animated zoomIn">
                    <div class="value">
                        $<?php echo number_format($Order->total / 100, 2); ?>
                    </div>

                    <div class="descriptor">
                        Order total
                    </div>
                </div>

                <div id="placed-on" class="block animated zoomIn">
                    <div class="callout">
                        <h6>
                            Order details
                        </h6>
                        
                        <p>
                            Placed: 
                            
                            <?php 
                                $time_elapsed = $Time->time_elapsed($Order->placed_on);
                                echo $time_elapsed['full'];
                            ?>
                        </p>
                    </div>
                </div>

                <div id="exchange-info" class="block animated zoomIn">
                    <div class="callout">
                        <h6>
                            Exchange method
                        </h6>
                        
                        <p>
                            <?php echo ucfirst($OrderGrower->exchange_option); ?>
                        </p>
                    </div>

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
                                Delivery fee
                            </h6>

                            <p>
                                $<?php echo number_format($OrderGrower->exchange_fee / 100, 2); ?>
                            </p>
                        </div>

                        <div class="callout">
                            <h6>
                                Time to fulfill
                            </h6>

                            <p>
                                <?php
                                
                                $now        = new DateTime($Time->now());
                                $placed_on  = new DateTime($Order->placed_on);
                                $deadline   = date_add($placed_on, date_interval_create_from_date_string('3 days'));

                                $interval   = $now->diff($deadline);

                                // echo '<span class="warning">';

                                if ($interval->format('%a') > 1) {
                                    echo $interval->format('%a days');
                                } else if ($interval->format('%a') == 1) {
                                    echo $interval->format('%a day %h hours');
                                } else if ($interval->format('%h') < 24 && $interval->format('%h') > 0) {
                                    echo $interval->format('%h hours');
                                } else if ($interval->format('%h') == 0 && $interval->format('%i') > 0) {
                                    echo $interval->format('%i minutes');
                                } else if ($interval->format('%H') == 0 && $interval->format('%i') == 0) {
                                    echo 'Expired';
                                }

                                // echo '</span>';
                                
                                ?>
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
                                <?php echo $User->GrowerOperation->Pickup->time; ?>
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

            <div class="col-md-4">
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
        </div>
    </div>

    <?php

} else {
    echo 'This is an invalid ID!' . $Order->id . 'foo';
}

?>
</div>
</main>

<script>
/* var data    = <?php //echo json_encode($data); ?>;
var lat     = <?php //echo $Buyer->latitude; ?>;
var lng     = <?php //echo $Buyer->longitude; ?>; */
</script>