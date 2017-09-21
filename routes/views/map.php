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
                            
                            foreach ($growers as $grower) {
                                
                                ?>

                                <div class="<?php echo $tile_width; ?>">
                                    <div class="card animated zoomIn">
                                    
                                        <?php
                                    
                                        img(ENV . '/profile-photos/' . $grower['filename'], $grower['ext'], 'S3', 'card-img-top');

                                        ?>

                                        <div class="card-block d-flex flex-row">
                                            <div class="listing-info d-flex flex-column">
                                                <div class="title">
                                                    <?php echo '<div class="name">' . $grower['first_name'] . '</div><div class="rating">' . $grower['stars'] . '</div>'; ?>
                                                </div>
                                                
                                                <div class="distance">
                                                    <?php echo (!empty($distance) ? $grower['distance']['length'] . ' ' . $grower['distance']['units'] . ' away' : $grower['city'] . ', ' . $grower['state']); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer">
                                            <?php echo '<strong>' . $grower['listings'] . '</strong>' . 'listing'  . ($grower['listings'] > 1 ? 's' : ''); ?>
                                            <?php // echo '<strong>' . $grower['listings'] . '</strong>' . 'listing'  . ($grower['listings'] > 1 ? 's' : '') . '<span class="float-right"><i class="fa fa-angle-right"></i></span>'; ?>
                                        </div>
                                    </div>
                                </div>

                                <?php

                            }

                            ?>
                        </div>
                    </div>

                    <nav id="footer" class="navbar">
                        <span class="nav-link">© Food From Friends</span>
                    </nav>
                </div>
            </div>
        </div> <!-- end main -->
    </div> <!-- end div.row -->
</div> <!-- end div.container-fluid -->

<script>
var data = <?php echo json_encode($data); ?>;
</script>