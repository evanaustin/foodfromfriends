<!-- cont main -->
    <div class="container-fluid animated fadeIn">
        <div class="seamless total-blocks">
            <div class="row">
                <div class="col-md-4">
                    <div class="block">
                        <div class="value">
                            <?= amount($amount_paid); ?>
                        </div>

                        <div class="descriptor">
                            Amount earned
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="block">
                        <div class="value">
                            <?= count($new_orders); ?>
                        </div>

                        <div class="descriptor">
                            New orders
                        </div>
                    </div>
                </div>    
                
                <div class="col-md-4">
                    <div class="block">
                        <div class="value">
                            <?= count($pending_orders); ?>
                        </div>

                        <div class="descriptor">
                            Pending orders
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="seamless list-blocks">
            <div class="row">
                <div class="col-md-6">
                    <div class="requirements list-group">
                        <div class="list-group-item heading">
                            Requirements
                        </div>

                        <div class="description"></div>
                        
                        <div class="list-ticks">
                            <div class="progress">
                                <div class="progress-bar" style="width: 5px;"></div>
                            </div>

                            <?php
                            
                            foreach($requirements as $requirement => $data) {
                                if ($data['status'] == 'complete') {
                                    echo '<p class="list-group-item disabled">' . ucfirst($requirement) . '<i class="fa fa-check"></i></p>';
                                } else {
                                    echo '<a href="' . PUBLIC_ROOT . $data['link'] . '" class="list-group-item list-group-item-action ' . (($data['status'] == 'complete') ? 'disabled' : '') . '">' . ucfirst($requirement) . '<i class="fa fa-angle-right"></i></a>';
                                }
                            }
                            
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="goals list-group">
                        <div class="list-group-item heading">
                            Goals
                        </div>

                        <div class="description"></div>
                        
                        <div class="list-ticks">
                            <div class="progress">
                                <div class="progress-bar" style="width:5px;"></div>
                            </div>

                            <?php
                            
                            foreach($goals as $goal => $data) {
                                if ($data['status'] == 'complete') {
                                    echo '<p class="list-group-item disabled">' . ucfirst($goal) . '<i class="fa fa-check"></i></p>';
                                } else {
                                    echo '<a href="' . PUBLIC_ROOT . $data['link'] . '" class="list-group-item list-group-item-action ' . (($data['status'] == 'complete') ? 'disabled' : '') . '">' . ucfirst($goal) . '<i class="fa fa-angle-right"></i></a>';
                                }
                            }
                            
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>