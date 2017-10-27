<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="main container<?php if (isset($User->GrowerOperation) && $User->GrowerOperation->permission == 2) { echo '-fluid'; } ?> animated fadeIn">
                <?php

                if (isset($User->GrowerOperation) && $User->GrowerOperation->permission == 2) {
                    
                    ?>

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

                                    <div class="description"></div>
                                    
                                    <div class="list-ticks">
                                        <div class="progress">
                                            <div class="progress-bar" style="height:5px; width: 5px;"></div>
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
                            </div>
                            
                            <div class="col-md-6">
                                <div class="goals list-group">
                                    <div class="list-group-item heading">
                                        Goals
                                    </div>

                                    <div class="description"></div>
                                    
                                    <div class="list-ticks">
                                        <div class="progress">
                                            <div class="progress-bar" style="height:5px; width:5px;"></div>
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
                    </div>

                    <?php

                } else {
                    echo 'You do not have permission to view this page';
                }

                ?>
            </div> <!-- end div.main.contianer -->
        </div> <!-- end main -->
    </div> <!-- end div.row -->
</div> <!-- end div.container-fluid -->