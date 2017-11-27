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
                                    <a href="<?php echo PUBLIC_ROOT . 'grower?id=' . $grower['id']; ?>">
                                        <div class="card animated zoomIn">
                                            <div class="card-img-top">
                                                <?php img(ENV . $grower['path'], $grower['ext'] . '?' . time(), 'S3', 'img-fluid animated fadeIn hidden'); ?>

                                                <div class="loading">
                                                    <i class="fa fa-circle-o-notch loading-icon"></i>
                                                </div>
                                            </div>

                                            <div class="card-block d-flex flex-row">
                                                <div class="listing-info d-flex flex-column">
                                                    <div class="card-title">
                                                        <?php //echo '<div class="name">' . $grower['name'] . '</div><div class="rating">' . $grower['stars'] . '</div>'; ?>
                                                        <?php echo '<div class="name">' . $grower['name'] . '</div>'; ?>
                                                    </div>
                                                    
                                                    <div class="distance">
                                                        <?php echo (!empty($distance) ? $grower['distance']['length'] . ' ' . $grower['distance']['units'] . ' away' : $grower['city'] . ', ' . $grower['state']); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- <a href="<?php //echo PUBLIC_ROOT . 'grower?id=' . $grower['id']; ?>"> -->
                                                <div class="card-footer">
                                                    <?php echo '<strong>' . $grower['listing_count'] . '</strong>' . 'listing'  . ($grower['listing_count'] > 1 ? 's' : '') . '<span class="float-right"><i class="fa fa-angle-right"></i></span>'; ?>
                                                </div>
                                            <!-- </a> -->
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