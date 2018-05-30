<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Your items
                    &nbsp;
                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/overview-grid">
                        <i class="fa fa-th" data-toggle="tooltip" data-title="Switch to grid view" data-placement="right"></i>
                    </a>
                </div>

                <div class="page-description text-muted small">
                    These are all of your items. Update pricing and availability settings from this page, or click an 'Edit' icon to change an item's other settings.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="edit-items" class="btn btn-success">
                        <i class="pre fa fa-floppy-o"></i>
                        Save changes
                        <i class="post fa fa-gear loading-icon save"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <hr>

        <div class="alerts"></div>
        
        <?php if (!empty($hashed_items)): ?>
        
            <ledger class="orders">

                <?php foreach ($hashed_items as $category_id => $items): ?>

                    <div class="opened record">
                        <div class="tab" data-toggle="collapse" data-target="#order-<?= $Order->id;?>" aria-controls="order-<?= $Order->id ;?>" aria-label="Toggle order" aria-expanded="<?= ($i == 1) ? 'true' : 'false'; ?>"></div>
                        
                        <fable>
                            <cell>
                                <h5><strong><?= ucfirst($hashed_categories[$category_id]) ?></strong></h5>
                            </cell>
                            
                            <cell>
                                <!-- <h6>ID:&nbsp;<strong><?= $encrypted_id; ?></strong></h6> -->
                            </cell>
                            
                            <cell>
                                <!-- <h6><?= "<strong>{$seller_count}</strong>&nbsp;seller" . (($seller_count > 1) ? 's' : ''); ?></h6> -->
                            </cell>
                            
                            <cell>
                                <!-- <h6>Placed:&nbsp;<strong><?= $placed_on; ?></strong></h6> -->
                            </cell>
                            
                            <cell class="justify-center">
                                <!-- <h5 class="strong"><?php amount($Order->total); ?></h5> -->
                            </cell>

                            <cell class="actions flexgrow-0">
                                <!-- <a href="<?= PUBLIC_ROOT . 'dashboard/buying/orders/receipt?id=' . $encrypted_id; ?>" class="btn btn-muted" data-toggle="tooltip" data-placement="left" data-title="View receipt"><i class="fa fa-file"></i></a> -->
                            </cell>
                        </fable>
                    
                        <ledger class="collapse show" id="order-<?= $category_id ?>">

                            <?php

                            foreach ($items as $item_id => $Item) {

                                // $item_count = count($OrderGrower->FoodListings);

                                // $tab_highlight = 'tab-';

                                // Determine status settings
                                /* if ($OrderGrower->Status->current == 'not yet confirmed') {
                                    $tab_highlight .= 'waiting';

                                    $time_until = \Time::until($OrderGrower->Status->placed_on, '24 hours');

                                    $status = 'Not confirmed <i class="fa fa-clock-o" data-toggle="tooltip" data-placement="top" data-title="The seller has ' . $time_until['full'] . ' to confirm this order"></i>';

                                    $actions = [
                                        'message',
                                        'cancel order'
                                    ];
                                } else if ($OrderGrower->Status->current == 'expired') {
                                    $tab_highlight .= 'danger';
                                    $status = 'Expired <i class="fa fa-exclamation-circle" data-toggle="tooltip" data-placement="top" data-title="You have not been charged for this order"></i>';
                                    
                                    $actions = [
                                        'message'
                                    ];
                                } else if ($OrderGrower->Status->current == 'rejected') {
                                    $tab_highlight .= 'danger';
                                    $status = 'Rejected <i class="fa fa-exclamation-circle" data-toggle="tooltip" data-placement="top" data-title="You have not been charged for this order"></i>';
                                    
                                    $actions    = [
                                        'message'
                                    ];
                                } else if ($OrderGrower->Status->current == 'pending fulfillment') {
                                    $tab_highlight .= 'warning';
                                    $status = 'Pending fulfillment';
                                
                                    $actions = [
                                        'message',
                                        'cancel order'
                                    ];
                                } else if ($OrderGrower->Status->current == 'cancelled by buyer') {
                                    $tab_highlight .= 'danger';
                                    $status = 'You cancelled <i class="fa fa-exclamation-circle" data-toggle="tooltip" data-placement="top" data-title="You have been refunded the amount for this order"></i>';
                                    
                                    $actions = [
                                        'message',
                                        'view receipt'
                                    ];
                                } else if ($OrderGrower->Status->current == 'cancelled by seller') {
                                    $tab_highlight .= 'danger';
                                    $status = 'Seller cancelled <i class="fa fa-exclamation-circle" data-toggle="tooltip" data-placement="top" data-title="You have been refunded the amount for this order"></i>';
                                
                                    $actions = [
                                        'message',
                                        'view receipt'
                                    ];
                                } else if ($OrderGrower->Status->current == 'open for review') {
                                    $tab_highlight .= 'info';

                                    $time_until = \Time::until($OrderGrower->Status->fulfilled_on, '3 days');

                                    $status = 'Open for review <i class="fa fa-clock-o" data-toggle="tooltip" data-placement="top" data-title="You have ' . $time_until['full'] . ' to leave a review or report an issue"></i>';
                                
                                    $actions = [
                                        'leave a review',
                                        'report an issue'
                                    ];
                                } else if ($OrderGrower->Status->current == 'issue reported') {
                                    $tab_highlight .= 'info';

                                    $status = 'Issue reported <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" data-title="Customer service is working to resolve this issue"></i>';
                                
                                    $actions = [
                                        'leave a review',
                                        'message'
                                    ];
                                } else if ($OrderGrower->Status->current == 'completed') {
                                    $tab_highlight .= 'success';
                                    $status = 'Completed';
                                    
                                    $actions = [
                                        'message',
                                        'view receipt'
                                    ];
                                } */

                                ?>
                                
                                <div class="closed record animated fadeIn">
                                    <div class="tab" data-toggle="collapse" data-target="#item-<?= $Item->id ?>" aria-controls="suborder-<?= $Item->id ?>" aria-label="Toggle suborder"></div>
                                    
                                    <fable>
                                        <cell class="min-third">
                                            <div class="user-block">
                                                <div class="user-photo d-none d-md-block" style="background-image: url('<?= 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/{$Item->Image->path}/{$Item->Image->filename}.{$Item->Image->ext}" ?>');"></div>
                                                
                                                <div class="user-content">
                                                    <h5 class="bold margin-btm-25em">
                                                        <a href="<?= PUBLIC_ROOT . $Item->link ?>">
                                                            <?= $Item->title ?>
                                                        </a>
                                                    </h5>

                                                    <small>
                                                        <!-- <?= "{$ThisGrowerOperation->city}, {$ThisGrowerOperation->state}"; ?> -->
                                                    </small>
                                                </div>
                                            </div>
                                        </cell>

                                        <cell class="justify-center d-block d-md-flex align-center d-align-left">
                                            <!-- <h6><?= $status; ?></h6> -->
                                        </cell>
                                        
                                        <cell class="justify-center d-none d-md-flex">
                                            <!-- <h6><?= "<strong>{$item_count}</strong> item" . (($item_count > 1) ? 's' : ''); ?></h6> -->
                                        </cell>
                                        
                                        <cell class="justify-center d-none d-md-flex">
                                            <!-- <h6><?= ucfirst($OrderGrower->Exchange->type); ?></h6> -->
                                        </cell>
                                        
                                        <cell class="justify-center d-block d-md-flex align-center d-align-left">
                                            <!-- <h6 class="bold"><?php amount($OrderGrower->total); ?></h6> -->
                                        </cell>

                                        <cell class="actions flexgrow-0">

                                            <?php
                                            
                                            /* foreach ($actions as $action) {
                                                switch($action) {
                                                    case 'message':
                                                        echo '<a href="' . PUBLIC_ROOT . 'dashboard/buying/messages/thread?seller=' . $ThisGrowerOperation->id . '" class="btn btn-muted" data-toggle="tooltip" data-placement="left" data-title="Message seller"><i class="fa fa-envelope"></i></a>';
                                                        break;
                                                    // case 'view receipt':
                                                    //     echo '<a href="" class="btn btn-light" data-toggle="tooltip" data-placement="left" data-title="View receipt"><i class="fa fa-file"></i></a>';
                                                    //     break;
                                                    case 'leave a review':
                                                        echo '<a href="' . PUBLIC_ROOT . 'dashboard/buying/orders/review?id=' . $OrderGrower->id . '" class="btn btn-success" data-toggle="tooltip" data-placement="left" data-title="Leave a review"><i class="fa fa-commenting"></i></a>';
                                                        break;
                                                    case 'report an issue':
                                                        echo '<a href="' . PUBLIC_ROOT . 'dashboard/buying/orders/report?id=' . $OrderGrower->id . '" class="report-issue btn btn-warning" data-toggle="tooltip" data-placement="left" data-title="Report an issue" data-ordergrower-id="' . $OrderGrower->id .'"><i class="fa fa-flag"></i></a>';
                                                        break;
                                                    case 'cancel order':
                                                        echo '<a class="cancel-order btn btn-danger" data-toggle="tooltip" data-placement="left" data-title="Cancel order" data-ordergrower-id="' . $OrderGrower->id .'"><i class="fa fa-times"></i></a>';
                                                        break;
                                                }
                                            } */
                                            
                                            ?>

                                        </cell>
                                    </fable>
                                    
                                    <!-- <div class="collapse" id="suborder-<?= $OrderGrower->id;?>">
                                        <?php

                                        foreach ($OrderGrower->FoodListings as $OrderListing) {

                                            $ThisFoodListing = new Item([
                                                'DB' => $DB,
                                                'id' => $OrderListing->food_listing_id
                                            ]);

                                            ?>

                                            <div class="item card-alt animated fadeIn">
                                                <div class="item-image">
                                                    <?php
                                                    
                                                    img(ENV . '/items/fl.' . $ThisFoodListing->id, $ThisFoodListing->ext, [
                                                        'server'    => 'S3',
                                                        'class'     => 'img-fluid'
                                                    ]);
                                                    
                                                    ?>
                                                </div>

                                                <div class="card-body">
                                                    <h6 class="strong">
                                                        <a href="<?= PUBLIC_ROOT . $ThisGrowerOperation->link . '/' . $ThisFoodListing->link; ?>">
                                                            <?= ucfirst($ThisFoodListing->title); ?>
                                                        </a>

                                                        <span class="float-right">
                                                            <small>x</small> <?= $OrderListing->quantity; ?>
                                                        </span>
                                                    </h6>
                                                    
                                                    <small class="light-gray">
                                                        <?php
                                                        
                                                        if (!empty($OrderListing->weight) && !empty($OrderListing->units)) {
                                                            echo '<span>';
                                                            amount(($OrderListing->unit_price / $OrderListing->unit_weight));
                                                            echo " / {$OrderListing->weight_units}";
                                                            echo '</span>';
                                                        }
                                                        
                                                        ?>
                                                        
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
                                                <?= ucfirst($OrderGrower->Exchange->type); ?> details
                                            </cell>
                                            
                                            <cell class="justify-end bold">
                                                
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
                                                <?= $OrderGrower->Exchange->address_line_1 . (($OrderGrower->Exchange->address_line_2) ? ' ' . $OrderGrower->Exchange->address_line_2 : '') . ', '. $OrderGrower->Exchange->city . ' ' . $OrderGrower->Exchange->state . ' ' . $OrderGrower->Exchange->zipcode; ?>
                                            </p>
                                        </div>

                                        <?php if ($OrderGrower->Exchange->type != 'delivery'): ?>

                                            <div class="callout">
                                                <h6>
                                                    Time
                                                </h6>

                                                <p>
                                                    <?= $OrderGrower->Exchange->time; ?>
                                                </p>
                                            </div>

                                            <div class="callout">
                                                <h6>
                                                    Instructions
                                                </h6>

                                                <p>
                                                    <?= $OrderGrower->Exchange->instructions; ?>
                                                </p>
                                            </div>

                                        <?php endif; ?>

                                    </div> -->
                                </div>

                                <?php

                            }

                            ?>

                        </ledger>
                    </div>

                <?php endforeach; ?>

            </ledger>

        <?php else: ?>

            <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/add-new" class="btn btn-primary">
                Create your first item
            </a>

        <?php endif; ?>

        </div>
    </div>
</main>