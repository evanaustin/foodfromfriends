<!-- cont main -->
    <div class="container animated fadeIn">
        <?php

        if (isset($pending) && count($pending) > 0) {

            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Pending orders placed
                    </div>

                    <div class="page-description text-muted small">
                        This is a collection of all your pending orders placed, sorted by most recent.
                    </div>
                </div>
            </div>

            <div class="alerts"></div>

            <table class="table datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Placed on</th>
                        <th>Amount</th>
                        <th>Details</th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    
                    $i = 1;

                    foreach($pending as $order) {
                        
                        /* $ThisOrder = new Order([
                            'DB' => $DB,
                            'id' => $order['id']
                        ]);

                        foreach($ThisOrder->Growers as $ThisOrderGrower) {
                            if (!isset($ThisOrderGrower->fulfilled_on)) {
                                $status = 'incomplete';
                                break;
                            }
                        } */

                        // if (!isset($status)) $status = 'complete';

                        ?>
                        
                        <tr class="table-light">
                            <td scope="row">
                                <?php echo $i; ?>
                            </td>
                            
                            <td>
                                <?php

                                $placed_on = new DateTime($order['placed_on']);
                                echo $placed_on->format('F d, Y');
                                
                                ?>
                            </td>
                            
                            <td>
                                $<?php echo number_format($order['total'] / 100, 2); ?>
                            </td>

                            <td>
                                <a href="<?php echo PUBLIC_ROOT . $Routing->template . '/account/orders-placed/pending/view?id=' . $order['id']; ?>">View</a>
                            </td>
                        </tr>

                        <?php

                        $i++;
                    
                    }
                    
                    ?>

                    <tr>
                        <td colspan=3>
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
            echo 'You have no past orders!';
        }

        ?>
    </div>
</main>