<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="main container-fluid animated fadeIn">
                <div class="seamless total-blocks">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="block">
                                <div class="value">
                                    $0.00
                                </div>

                                <div class="descriptor">
                                    Amount sold
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="block">
                                <div class="value">
                                    0
                                </div>

                                <div class="descriptor">
                                    Unique sales
                                </div>
                            </div>
                        </div>    
                        
                        <div class="col-md-4">
                            <div class="block">
                                <div class="value">
                                    0
                                </div>

                                <div class="descriptor">
                                    Unique buyers
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
                                
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="height:5px;"></div>
                                </div>

                                <?php
                                
                                foreach($requirements as $requirement => $data) {
                                    echo "<a href=\"" . PUBLIC_ROOT . $data['link'] . "\" class=\"list-group-item list-group-item-action " . (($data['status'] == 'complete') ? 'disabled' : '') . "\">" . ucfirst($requirement);
                                    
                                    if ($data['status'] == 'complete') {
                                        echo '<i class="fa fa-check"></i>';
                                    }

                                    echo '</a>';
                                }
                                
                                ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="goals list-group">
                                <div class="list-group-item heading">
                                    Goals
                                </div>

                                <div class="progress">
                                    <div class="progress-bar bg-success" style="height:5px;"></div>
                                </div>
                                
                                <?php
                                
                                foreach($goals as $goal => $data) {
                                    echo "<a href=\"" . PUBLIC_ROOT . $data['link'] . "\" class=\"list-group-item list-group-item-action " . (($data['status'] == 'complete') ? 'disabled' : '') . "\">" . ucfirst($goal);

                                    if ($data['status'] == 'complete') {
                                        echo '<i class="fa fa-check"></i>';
                                    }

                                    echo '</a>';
                                }
                                
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end div.main.contianer -->
        </div> <!-- end main -->
    </div> <!-- end div.row -->
</div> <!-- end div.container-fluid -->

<?php
console_log($User->GrowerOperation->get_team_members());
?>