<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <pre>
                <?php //print_r($growers); ?>
            </pre>

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
                                        <a href="<?php echo PUBLIC_ROOT . 'grower?id=' . $grower['id']; ?>">
                                            <img src="<?php echo $grower['filename']; ?>" class="card-img-top"/>
                                        </a>
                                        
                                        <div class="card-block d-flex flex-row">
                                            <div class="listing-info d-flex flex-column">
                                                <div class="title">
                                                    <?php echo '<div class="name">' . $grower['name'] . '</div><div class="rating">' . /*$grower['stars'] .*/ '</div>'; ?>
                                                </div>
                                                
                                                <div class="distance">
                                                    <?php echo (!empty($distance) ? $grower['distance']['length'] . ' ' . $grower['distance']['units'] . ' away' : $grower['city'] . ', ' . $grower['state']); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="<?php echo PUBLIC_ROOT . 'grower?id=' . $grower['id']; ?>">
                                            <div class="card-footer">
                                                <?php echo '<strong>' . $grower['listing_count'] . '</strong>' . 'listing'  . ($grower['listing_count'] > 1 ? 's' : '') . '<span class="float-right"><i class="fa fa-angle-right"></i></span>'; ?>
                                            </div>
                                        </a>
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