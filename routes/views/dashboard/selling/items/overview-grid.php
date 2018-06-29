<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Your items
                    &nbsp;
                    <a href="<?= PUBLIC_ROOT ?>dashboard/selling/items/overview">
                        <i class="fa fa-bars" data-toggle="tooltip" data-title="Switch to stack view" data-placement="right"></i>
                    </a>
                </div>

                <div class="page-description text-muted small">
                    <?= $msg; ?>
                </div>
            </div>
        </div>
        
        <hr>

        <div class="alerts"></div>

        <div class="items">
        
        <?php
        
        if ($item_count > 0) {
        
            ?>

            <div class="row">

            <?php

            foreach($items as $item) {
                $Item = new Item([
                    'DB' => $DB,
                    'id' => $item['id']
                ]);

                ?>

                <div class="col-md-4">
                    <div class="card animated zoomIn">
                        <div class="card-img-top"> 
                            <a href="<?= PUBLIC_ROOT . 'dashboard/selling/items/edit?id=' . $Item->id; ?>">
                                <?php
                                
                                if (!empty($Item->filename)) {
                                    img(ENV . '/items/' . $Item->filename, $Item->ext, [
                                        'server'    => 'S3',
                                        'class'     => 'img-fluid animated fadeIn hidden'
                                    ]);
                                    
                                    ?>

                                    <div class="loading">
                                        <i class="fa fa-circle-o-notch loading-icon"></i>
                                    </div>

                                    <?php

                                } else {
                                    img('placeholders/default-thumbnail', 'jpg', [
                                        'server'    => 'local', 
                                        'class'     => 'animated fadeIn img-fluid'
                                    ]);
                                }

                                ?>
                            </a>
                        </div>

                        <div class="card-body d-flex flex-row">
                            <div class="item-info d-flex flex-column">
                                <h4 class="card-title">
                                    <a href="<?= PUBLIC_ROOT . 'dashboard/selling/items/edit?id=' . $Item->id; ?>">
                                        <?= $Item->title; ?>
                                    </a>
                                </h4>
                                
                                <h6 class="card-subtitle">
                                    <?= '$' . number_format($Item->price / 100, 2) . (!empty($Item->weight) && !empty($Item->units) ? ' • $' . number_format(($Item->price / $Item->weight) / 100, 2) . '/' . $Item->units : ''); ?>
                                </h6>

                                <p class="card-text">
                                    <?php
                                    
                                    if (!$Item->is_available) {
                                        $niblet = 'bg-faded text-muted';
                                        $availability = 'text-muted';
                                    } else {
                                        $niblet = 'text-white';
                                        $availability = 'text-success';

                                        if ($Item->quantity == 0) {
                                            $niblet .= ' bg-danger';
                                        } else if ($Item->quantity > 0 && $Item->quantity < 6) {
                                            $niblet .= ' bg-warning';
                                        } else if ($Item->quantity > 5) {
                                            $niblet .= ' bg-success';
                                        }
                                    }

                                    echo "<span class=\"quantity {$niblet}\">{$Item->quantity}</span> in stock • <span class=\"{$availability}\">" . (($Item->is_available) ? 'Available' : 'Unavailable') . "</span>";
                                    
                                    ?>
                                </p>
                            </div>

                            <div class="item-controls d-flex flex-column">
                                <a href="<?= PUBLIC_ROOT . 'dashboard/selling/items/edit?id=' . $Item->id; ?>" data-toggle="tooltip" data-placement="left" title="Edit item">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            
                                <a href="<?= PUBLIC_ROOT . $User->GrowerOperation->link . '/' . $Item->link; ?>" data-toggle="tooltip" data-placement="left" title="View item">
                                    <i class="fa fa-eye"></i>
                                </a> 
                                
                                <a href="" class="remove-item" data-id="<?= $Item->id; ?>" data-toggle="tooltip" data-placement="left" title="Delete item">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php

            }

            ?>

            </div>

            <?php

        } else {

            ?>

            <a href="<?= PUBLIC_ROOT . 'dashboard/selling/items/add-new'; ?>" class="btn btn-primary">
                Let's go create your first item!
            </a>

            <?php
            
        }

        ?>

        </div>
    </div>
</main>