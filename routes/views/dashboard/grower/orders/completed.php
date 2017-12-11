<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Completed orders
                </div>
        
                <div class="page-description text-muted small">
                    These are orders that have been fulfilled and require no further action. Click into an order to view its details.
                </div>
            </div>
        </div>

        <?php

        if (isset($completed) && ($completed != false) && count($completed) > 0) {

            ?>

            <div class="alerts"></div>

            <table class="table table-default datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Completed on</th>
                        <th>Amount</th>
                        <th>Buyer</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    
                    $i = 1;

                    foreach($completed as $order) {
                        $fulfilled_on = new DateTime($order['fulfilled_on']);

                        $ThisUser = new User([
                            'DB' => $DB,
                            'id' => $order['user_id']
                        ]);

                        ?>
                        
                        <tr>
                            <td scope="row">
                                <?php echo $i; ?>
                            </td>

                            <td clas="completed-on">
                                <?php echo $fulfilled_on->format('F d, Y'); ?>
                            </td>
                            
                            <td class="amount">
                                $<?php echo number_format($order['total'] / 100, 2); ?>
                            </td>

                            <td class="buyer">
                                <?php echo $ThisUser->name; ?>
                            </td>
                            
                            <td class="details">
                                <a href="<?php echo PUBLIC_ROOT . $Routing->template . '/grower/orders/completed/view?id=' . $order['id']; ?>">
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
                        <!-- <td colspan=7> -->
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

            <?php

        } else {
            ?>
            
            <div class="block margin-top-1em healthy">
                You don't have any completed orders
            </div>

            <?php
        }

        ?>
    </div>
</main>