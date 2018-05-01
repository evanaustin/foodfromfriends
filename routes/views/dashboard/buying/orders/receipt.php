<!-- cont main -->
    <div class="container animated fadeIn">
        <?php

        if ($Order->buyer_account_id == $User->BuyerAccount->id) {

            echo "ID:&nbsp;<strong>{$encrypted_id}</strong><br>";

            $placed_on = new Datetime($Order->Charge->authorized_on);
            echo $placed_on->format('F j, Y');

            ?>

            <h6 class="light-gray margin-top-1em">Item breakdown</h6>

            <div class="table-responsive margin-top-1em">
                <table class="table">
                    <thead>
                        <tr class="w-25">
                            <th>Item</th>
                            <th>Seller</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php

                        foreach ($Order->Growers as $OrderGrower) {

                            $Seller = new GrowerOperation([
                                'DB' => $DB,
                                'id' => $OrderGrower->grower_operation_id
                            ]);

                            foreach ($OrderGrower->FoodListings as $key => $OrderFoodListing) {

                                $FoodListing = new FoodListing([
                                    'DB' => $DB,
                                    'id' => $OrderFoodListing->food_listing_id
                                ]);

                                ?>

                                <tr class="w-25">
                                    <td><?= $FoodListing->title; ?></td>
                                    <td><?= $Seller->name; ?></td>
                                    <td><?= $OrderFoodListing->quantity; ?></td>
                                    <td><?php amount($OrderFoodListing->total); ?></td>
                                </tr>
                                
                                <?php
                            }
                        }

                        ?>
                    </tbody>
                </table>
            </div>

            <h6 class="light-gray margin-top-2em">Seller breakdown</h6>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr class="w-25">
                            <th>Seller</th>
                            <th>Exchange fee</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php

                        foreach ($Order->Growers as $OrderGrower) {

                            $Seller = new GrowerOperation([
                                'DB' => $DB,
                                'id' => $OrderGrower->grower_operation_id
                            ]);


                            ?>

                            <tr class="w-25">
                                <td><?= $Seller->name; ?></td>
                                <td><?php amount($OrderGrower->Exchange->fee); ?></td>
                                <td><?= ucfirst($OrderGrower->Status->current); ?></td>
                                <td><?php amount($OrderGrower->total); ?></td>
                            </tr>
                            
                            <?php
                        
                        }

                        ?>
                    </tbody>
                </table>
            </div>
            
            <h6 class="light-gray margin-top-2em">Order total</h6>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr class="w-25">
                            <th>Subtotal</th>
                            <th>Exchange fees</th>
                            <th>Service fee</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="w-25">
                            <td><?php amount($Order->Charge->subtotal); ?></td>
                            <td><?php amount($Order->Charge->exchange_fees); ?></td>
                            <td><?php amount($Order->Charge->fff_fee); ?></td>
                            <td><?php amount($Order->Charge->total); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php

        } else {

            ?>

            <div class="block strong">
                Oops, looks like you found your way here by mistake &hellip; nothing to see here!
            </div>

            <?php

        }

        ?>
    </div>
</main>