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
                                    <a href="<?= PUBLIC_ROOT . $Grower->link; ?>">
                                        <div class="card animated fadeIn">
                                            <div class="card-img-top">
                                                <?php
                                                
                                                img(ENV . "/grower-operation-images/{$Grower->filename}", $Grower->ext /* . '?' . time() */, [
                                                    'server'    => 'S3',
                                                    'class'     => 'img-fluid animated fadeIn hidden'
                                                ]);
                                                
                                                ?>

                                                <div class="loading">
                                                    <i class="fa fa-circle-o-notch loading-icon"></i>
                                                </div>
                                            </div>

                                            <div class="card-body d-flex flex-row">
                                                <div class="item-info d-flex flex-column">
                                                    <div class="card-title">
                                                        <?= "<div class=\"name\">{$Grower->name}</div>"; ?>
                                                    </div>
                                                    
                                                    <div class="small-gray padding-top-15em">
                                                        <?= "<span class=\"brand\">" . stars($Grower->average_rating) . "</span>&nbsp;&bull;&nbsp;" . (($Grower->type == 'individual' || $Grower->type == 'other') ? 'Grower' : ucfirst($Grower->type)); ?>
                                                    </div>
                                                    
                                                    <div class="small-gray padding-top-10em">
                                                        <?= (!empty($Grower->distance['length']) ? "{$Grower->distance['length']} {$Grower->distance['units']} away" : "{$Grower->city}, {$Grower->state}"); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer">
                                                <?= "<strong>{$Grower->item_count}</strong>item"  . ($Grower->item_count > 1 ? 's' : '') . ' for sale<span class="float-right"><i class="fa fa-angle-right"></i></span>'; ?>
                                            </div>
                                        </div>

                                        <!-- <i class="fa fa-circle-o-notch loading-icon"></i> -->
                                    </a>
                                </div>

                                <?php

                            }

                            ?>

                            <div class="<?= (!isset($User->GrowerOperation) ? 'col-12' : 'hidden'); ?>">
                                <a id="start-selling" href="<?= PUBLIC_ROOT . 'dashboard/selling/items/add-new'; ?>" class="btn btn-cta btn-block margin-top-btm-50em">List your items</a>
                            </div>

                            <!-- <div class="<?php //echo (empty($wishlist) ? 'col-12' : 'hidden'); ?>">
                                <a id="build-wish-list" href="<?php //echo PUBLIC_ROOT . 'dashboard/buying/orders/wish-list'; ?>" class="btn btn-cta btn-block margin-top-btm-50em">Build a wish list</a>
                            </div> -->
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
    var data = <?= json_encode($data); ?>;
</script>