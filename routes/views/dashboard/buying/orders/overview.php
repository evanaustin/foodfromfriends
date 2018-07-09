<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Your orders
                </div>
        
                <div class="page-description text-muted small">
                    These are the orders you've placed. Each order tab is clickable and color-coded according to the order's status.
                </div>
            </div>
        
            <div class="col-md-6">
                <keymap class="align-right">
                    <key>
                        <span class="rounded-circle waiting-bg" data-toggle="tooltip" data-placement="bottom" data-title="Not yet confirmed">&nbsp;</span>
                    </key>
        
                    <key>
                        <span class="rounded-circle warning-bg" data-toggle="tooltip" data-placement="bottom" data-title="Pending fulfillment">&nbsp;</span>
                    </key>
                    
                    <key>
                        <span class="rounded-circle info-bg" data-toggle="tooltip" data-placement="bottom" data-title="Open for review">&nbsp;</span>
                    </key>
                    
                    <key>
                        <span class="rounded-circle success-bg" data-toggle="tooltip" data-placement="bottom" data-title="Completed">&nbsp;</span>
                    </key>
                    
                    <key>
                        <span class="rounded-circle danger-bg" data-toggle="tooltip" data-placement="bottom" data-title="Failed">&nbsp;</span>
                    </key>
                </keymap>
            </div>
        </div>

        <?php if (isset($placed) && ($placed != false) && count($placed) > 0): ?>

            <hr>

            <div class="alerts"></div>

            <ledger class="orders">

                <?php $i = 1 ?>

                <?php foreach ($placed as $order): ?>

                    <?php $Order = new Order([
                        'DB' => $DB,
                        'id' => $order['id']
                    ]) ?>

                    <?php

                    $seller_count = count($Order->Growers);

                    $placed_on = new Datetime($Order->Charge->authorized_on);
                    $placed_on = $placed_on->format('F j, Y');

                    $encrypted_id = substr($Order->Charge->stripe_charge_id, -4) . '-' . $Order->id * 3;

                    // $tab_highlight = 'tab-' . (!isset($Order->completed_on) ? 'warning' : 'success');

                    ?>
                    
                    <div class="<?= ($i == 1) ? 'opened' : 'closed'; ?> record">
                        <div class="tab <?php //echo $tab_highlight; ?>" data-toggle="collapse" data-target="#order-<?= $Order->id;?>" aria-controls="order-<?= $Order->id ;?>" aria-label="Toggle order" aria-expanded="<?= ($i == 1) ? 'true' : 'false'; ?>"></div>
                        
                        <fable>
                            <cell>
                                <h5>#&nbsp;<strong><?= $i; ?></strong></h5>
                            </cell>
                            
                            <cell>
                                <h6>ID:&nbsp;<strong><?= $encrypted_id; ?></strong></h6>
                            </cell>
                            
                            <cell>
                                <h6><?= "<strong>{$seller_count}</strong>&nbsp;seller" . (($seller_count > 1) ? 's' : ''); ?></h6>
                            </cell>
                            
                            <cell>
                                <h6>Placed:&nbsp;<strong><?= $placed_on; ?></strong></h6>
                            </cell>
                            
                            <cell class="justify-center">
                                <h5 class="strong"><?php amount($Order->total); ?></h5>
                            </cell>

                            <cell class="actions flexgrow-0">
                                <a href="<?= PUBLIC_ROOT . 'dashboard/buying/orders/receipt?id=' . $encrypted_id; ?>" class="btn btn-muted" data-toggle="tooltip" data-placement="left" data-title="View receipt"><i class="fa fa-file"></i></a>
                            </cell>
                        </fable>
                    
                        <ledger class="collapse <?= ($i == 1) ? 'show' : ''; ?>" id="order-<?= $Order->id;?>">

                            <?php foreach ($Order->Growers as $OrderGrower): ?>

                                <?php $ThisGrowerOperation = new GrowerOperation([
                                    'DB' => $DB,
                                    'id' => $OrderGrower->grower_operation_id
                                ],[
                                    'details' => true
                                ]);

                                $item_count = count($OrderGrower->Items);

                                $tab_highlight = 'tab-';

                                // Determine status settings
                                if ($OrderGrower->Status->current == 'not yet confirmed') {
                                    $tab_highlight .= 'waiting';

                                    $time_until = \Time::until($OrderGrower->Status->placed_on, '24 hours');

                                    $status = 'Not confirmed <i class="fa fa-clock-o brand" data-toggle="tooltip" data-placement="top" data-title="The seller has ' . $time_until['full'] . ' to confirm this order"></i>';

                                    $actions = [
                                        'message',
                                        'cancel order'
                                    ];
                                } else if ($OrderGrower->Status->current == 'expired') {
                                    $tab_highlight .= 'danger';
                                    $status = 'Expired <i class="fa fa-exclamation-circle danger" data-toggle="tooltip" data-placement="top" data-title="You have not been charged for this order"></i>';
                                    
                                    $actions = [
                                        'message'
                                    ];
                                } else if ($OrderGrower->Status->current == 'rejected') {
                                    $tab_highlight .= 'danger';
                                    $status = 'Rejected <i class="fa fa-exclamation-circle danger" data-toggle="tooltip" data-placement="top" data-title="You have not been charged for this order"></i>';
                                    
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
                                    $status = 'You cancelled <i class="fa fa-exclamation-circle danger" data-toggle="tooltip" data-placement="top" data-title="You have been refunded the amount for this order"></i>';
                                    
                                    $actions = [
                                        'message',
                                        'view receipt'
                                    ];
                                } else if ($OrderGrower->Status->current == 'cancelled by seller') {
                                    $tab_highlight .= 'danger';
                                    $status = 'Seller cancelled <i class="fa fa-exclamation-circle danger" data-toggle="tooltip" data-placement="top" data-title="You have been refunded the amount for this order"></i>';
                                
                                    $actions = [
                                        'message',
                                        'view receipt'
                                    ];
                                } else if ($OrderGrower->Status->current == 'open for review') {
                                    $tab_highlight .= 'info';

                                    $time_until = \Time::until($OrderGrower->Status->fulfilled_on, '3 days');

                                    $status = 'Open for review <i class="fa fa-clock-o brand" data-toggle="tooltip" data-placement="top" data-title="You have ' . $time_until['full'] . ' to leave a review or report an issue"></i>';
                                
                                    $actions = [
                                        'leave a review',
                                        'report an issue'
                                    ];
                                } else if ($OrderGrower->Status->current == 'issue reported') {
                                    $tab_highlight .= 'info';

                                    $status = 'Issue reported <i class="fa fa-question-circle info" data-toggle="tooltip" data-placement="top" data-title="Customer service is working to resolve this issue"></i>';
                                
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
                                } ?>
                                
                                <div class="closed record animated fadeIn">
                                    <div class="<?= $tab_highlight; ?>" data-toggle="collapse" data-target="#suborder-<?= $OrderGrower->id;?>" aria-controls="suborder-<?= $OrderGrower->id ;?>" aria-label="Toggle suborder"></div>
                                    
                                    <fable>
                                        <cell class="min-third">
                                            <div class="user-block">
                                                <div class="user-photo d-none d-md-block" style="background-image: url('<?= 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$ThisGrowerOperation->filename}.{$ThisGrowerOperation->ext}"; ?>');"></div>
                                                
                                                <div class="user-content">
                                                    <h5 class="bold margin-btm-25em">
                                                        <a href="<?= PUBLIC_ROOT . $ThisGrowerOperation->link; ?>">
                                                            <?= $ThisGrowerOperation->name; ?>
                                                        </a>
                                                    </h5>

                                                    <small>
                                                        <?= "{$ThisGrowerOperation->city}, {$ThisGrowerOperation->state}"; ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </cell>

                                        <cell class="justify-center d-block d-md-flex align-center d-align-left">
                                            <h6>
                                                <?= $status ?>
                                            </h6>
                                        </cell>
                                        
                                        <cell class="justify-center d-none d-md-flex">
                                            <h6>
                                                <?= "<strong>{$item_count}</strong> item" . (($item_count > 1) ? 's' : ''); ?>
                                            </h6>
                                        </cell>
                                        
                                        <cell class="justify-center d-none d-md-flex">
                                            <h6>
                                                <?= ($OrderGrower->Exchange->type == 'delivery') ? 'Delivery' : 'Meetup' ?>
                                            </h6>
                                        </cell>
                                        
                                        <cell class="justify-center d-block d-md-flex align-center d-align-left">
                                            <h6 class="bold">
                                                <?php amount($OrderGrower->total) ?>
                                            </h6>
                                        </cell>

                                        <cell class="actions flexgrow-0">

                                            <?php foreach ($actions as $action) {
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
                                            } ?>

                                        </cell>
                                    </fable>
                                    
                                    <div class="collapse" id="suborder-<?= $OrderGrower->id;?>">
                                        
                                        <?php foreach ($OrderGrower->Items as $OrderItem): ?>

                                            <?php $ThisItem = new Item([
                                                'DB' => $DB,
                                                'id' => $OrderItem->item_id
                                            ]) ?>

                                            <div class="item card-alt animated fadeIn">
                                                <div class="item-image">
                                                    <div class="user-photo no-margin" style="background-image: url('<?= (isset($ThisItem->Image->id) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/item-images/{$ThisItem->Image->filename}.{$ThisItem->Image->ext}" : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg') ?>');"></div>
                                                </div>

                                                <div class="card-body">
                                                    <h6 class="strong">
                                                        <a href="<?= PUBLIC_ROOT . $ThisGrowerOperation->link . '/' . $ThisItem->link; ?>">
                                                            <?= ucfirst($ThisItem->title); ?>
                                                        </a>

                                                        <span class="float-right">
                                                            <small>
                                                                x
                                                            </small>

                                                            <?= $OrderItem->quantity; ?>
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
                                    
                                        <fable>
                                            <cell class="bold">
                                                <?= ($OrderGrower->Exchange->type == 'delivery') ? 'Delivery' : 'Meetup' ?> details
                                            </cell>
                                            
                                            <cell class="justify-end bold">
                                                
                                                <?php if ($OrderGrower->Exchange->type == 'delivery'): ?>
                                                    
                                                    <?= _amount($OrderGrower->Exchange->fee) ?>
                                                
                                                <?php else: ?>
                                                    
                                                    Free

                                                <?php endif ?>

                                            </cell>
                                        </fable>

                                        <div class="callout">
                                            <h6>
                                                Location
                                            </h6>
                                            
                                            <p>
                                                <?= $OrderGrower->Exchange->address_line_1 . (($OrderGrower->Exchange->address_line_2) ? " {$OrderGrower->Exchange->address_line_2}" : '') . ", {$OrderGrower->Exchange->city} {$OrderGrower->Exchange->state} {$OrderGrower->Exchange->zipcode}" ?>
                                            </p>
                                        </div>

                                        <?php if ($OrderGrower->Exchange->type != 'delivery'): ?>

                                            <div class="callout">
                                                <h6>
                                                    Time
                                                </h6>

                                                <p>
                                                    <?= $OrderGrower->Exchange->time ?>
                                                </p>
                                            </div>

                                        <?php endif ?>

                                    </div>
                                </div>
                
                            <?php endforeach ?>

                        </ledger>
                    </div>

                    <?php $i++ ?>

                <?php endforeach ?>

            </ledger>

        <?php else: ?>
            
            <div class="block margin-top-1em strong">
                You don't have any placed orders
            </div>

        <?php endif ?>

    </div>
</main>