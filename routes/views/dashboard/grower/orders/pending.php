<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Pending orders
                </div>
        
                <div class="page-description text-muted small">
                    These are orders that have been confirmed but still need to be fulfilled. Click into an order to view its details and mark it fulfilled.
                </div>
            </div>
        </div>

        <?php

        if (isset($pending) && ($pending != false) && count($pending) > 0) {

            ?>

            <div class="alerts"></div>

            <div class="table-responsive margin-top-1em">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Confirmed</th>
                            <th>Amount</th>
                            <th>Exchange type</th>
                            <th>Buyer</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        
                        $i = 1;

                        foreach($pending as $order) {
                            $time_elapsed = \Time::elapsed($order['confirmed_on']);

                            $OrderGrower = new OrderGrower([
                                'DB' => $DB,
                                'id' => $order['id']
                            ]);
                            
                            $ThisUser = new User([
                                'DB' => $DB,
                                'id' => $order['user_id']
                            ]);

                            ?>
                            
                            <tr>
                                <td scope="row">
                                    <?= $i; ?>
                                </td>

                                <td class="confirmed">
                                    <?= $time_elapsed['full']; ?>
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
                                    <a href="<?= PUBLIC_ROOT . $Routing->template . '/grower/orders/pending/view?id=' . $order['id']; ?>">
                                        <i class="fa fa-external-link" data-toggle="tooltip" data-placement="top" data-title="View order details"></i>
                                    </a>
                                </td>
                            </tr>
                    
                            <?php
                    
                            $i++;

                        }
                        
                        ?>

                        <tr>
                            <td colspan=6>
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
                You don't have any pending orders
            </div>

            <?php

        }

        ?>
    </div>
</main>