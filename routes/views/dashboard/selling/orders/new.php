<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    New orders
                </div>
        
                <div class="page-description text-muted small">
                    These are orders that have not yet been confirmed. You have 24 hours to confirm an order before it expires and is gone forever. Click into an order to view its details and confirm it.
                </div>
            </div>
        </div>

        <?php
        
        if (isset($new) && ($new != false) && count($new) > 0) {

            ?>

            <div class="alerts"></div>

            <div class="table-responsive margin-top-1em">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Received</th>
                            <th>Expires in</th>
                            <th>Amount</th>
                            <th>Exchange type</th>
                            <th>Buyer</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        
                        $i = 1;

                        foreach($new as $order) {
                            $OrderGrower = new OrderGrower([
                                'DB' => $DB,
                                'id' => $order['id']
                            ]);

                            $time_elapsed   = \Time::elapsed($OrderGrower->Status->placed_on);
                            $time_until     = \Time::until($OrderGrower->Status->placed_on, '24 hours');

                            $ThisUser = new BuyerAccount([
                                'DB' => $DB,
                                'id' => $order['buyer_account_id']
                            ]);

                            ?>

                            <tr>
                                <td scope="row">
                                    <?= $i; ?>
                                </td>

                                <td class="received">
                                    <?= $time_elapsed['full']; ?>
                                </td>
                                
                                <td class="expires-in">
                                    <?= $time_until['full']; ?>
                                </td>

                                <td class="amount">
                                    <?php amount($order['total']); ?>
                                </td>
                                
                                <td class="exchange-type">
                                    <?= ucfirst($OrderGrower->Exchange->type); ?>
                                </td>
                                
                                <td class="buyer">
                                    <?= $ThisUser->name; ?>
                                </td>

                                <td class="details">
                                    <a href="<?= PUBLIC_ROOT . $Routing->template . '/selling/orders/new/view?id=' . $order['id']; ?>">
                                        <i class="fa fa-external-link" data-toggle="tooltip" data-placement="top" data-title="View order details"></i>
                                    </a>
                                </td>
                            </tr>
                    
                            <?php
                    
                            $i++;

                        }
                        
                        ?>

                        <tr>
                            <td colspan=7>
                                <nav aria-label="Table navigation">
                                    <ul class="pagination">
                                        <li class="page-item active">
                                            <a class="page-link" href="#">
                                                1
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php

        } else {

            ?>

            <div class="block margin-top-1em strong">
                You don't have any new orders
            </div>

            <?php
        
        }

        ?>
    </div>
</main>