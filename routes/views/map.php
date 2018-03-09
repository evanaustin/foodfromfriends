<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="main">
                <div id="mapbox">
                    <div id="map"></div>
                </div>

                <div id="scrollbox">
                    <div id="list">
                        <div class="row">
                            <?php
                            
                            foreach ($Growers as $Grower) {
                                
                                ?>

                                <div class="col-6">
                                    <a href="<?php echo PUBLIC_ROOT . $Grower->link; ?>">
                                        <div class="card animated fadeIn">
                                            <div class="card-img-top">
                                                <?php img(ENV . $Grower->details['path'], $Grower->details['ext'] /* . '?' . time() */, 'S3', 'img-fluid animated fadeIn hidden'); ?>

                                                <div class="loading">
                                                    <i class="fa fa-circle-o-notch loading-icon"></i>
                                                </div>
                                            </div>

                                            <div class="card-body d-flex flex-row">
                                                <div class="listing-info d-flex flex-column">
                                                    <div class="card-title">
                                                        <?php echo "<div class=\"name\">{$Grower->name}</div>"; ?>
                                                    </div>
                                                    
                                                    <div class="distance">
                                                        <?php echo "<span class=\"brand\">" . stars($Grower->average_rating) . "</span>&nbsp;&bull;&nbsp;" . (!empty($Grower->distance['length']) ? "{$Grower->distance['length']} {$Grower->distance['units']} away" : "{$Grower->details['city']}, {$Grower->details['state']}"); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer">
                                                <?php echo "<strong>{$Grower->listing_count}</strong>listing"  . ($Grower->listing_count > 1 ? 's' : '') . '<span class="float-right"><i class="fa fa-angle-right"></i></span>'; ?>
                                            </div>
                                        </div>

                                        <!-- <i class="fa fa-circle-o-notch loading-icon"></i> -->
                                    </a>
                                </div>

                                <?php

                            }

                            ?>
                        </div>
                    </div>

                    <nav id="footer" class="navbar">
                        <span class="nav-link">© Food From Friends, Inc.</span>
                    </nav>
                </div>
            </div>
        </div> <!-- end main -->
    </div> <!-- end div.row -->
</div> <!-- end div.container-fluid -->

<script>
    var data = <?php echo json_encode($data); ?>;
</script>