<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Orders under review
                </div>
        
                <div class="page-description text-muted small">
                    These are orders that have been fulfilled and are under review by the buyer. An order will clear this stage three days after fulfillment or when the buyer rates you as a seller, whichever is sooner. Click into an order to view its details.
                </div>
            </div>
        </div>

        <?php

        if (isset($under_review) && ($under_review != false) && count($under_review) > 0) {

            ?>

            <div class="alerts"></div>

            <div class="table-responsive margin-top-1em">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fulfilled on</th>
                            <th>Amount</th>
                            <th>Buyer</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        
                        $i = 1;

                        foreach($under_review as $order) {
                            $fulfilled_on = new DateTime($order['fulfilled_on'], new DateTimeZone('UTC'));
                            $fulfilled_on->setTimezone(new DateTimeZone($User->timezone));

                            $ThisUser = new BuyerAccount([
                                'DB' => $DB,
                                'id' => $order['buyer_account_id']
                            ]);

                            ?>
                            
                            <tr>
                                <td scope="row">
                                    <?= $i; ?>
                                </td>

                                <td clas="completed-on">
                                    <?= $fulfilled_on->format('F j, Y'); ?>
                                </td>
                                
                                <td class="amount">
                                    <?php amount($order['total']); ?>
                                </td>

                                <td class="buyer">
                                    <?= $ThisUser->name; ?>
                                </td>
                                
                                <td class="details">
                                    <a href="<?= PUBLIC_ROOT . $Routing->template . '/selling/orders/under-review/view?id=' . $order['id']; ?>">
                                        <i class="fa fa-external-link" data-toggle="tooltip" data-placement="top" data-title="View order details"></i>
                                    </a>
                                </td>
                            </tr>
                    
                            <?php
                    
                            $i++;

                        }
                        
                        ?>

                        <tr>
                            <td colspan=5>
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
                You don't have any orders under review
            </div>

            <?php
        }

        ?>
    </div>
</main>