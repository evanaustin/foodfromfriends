<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="titlebar">
                <div class="container">
                    Your food listings
                </div>
            </div>
            
            <div class="container">
                <?php
                
                $i = 1;

                foreach($listings as $listing) {
                    if ($i % 3 == 1) {
                        echo '<div class="card-deck">';
                    }

                    ?>

                    <div class="card">
                        <?php
                        
                        if (!empty($listing['filename'])) {
                            img('user/' . $listing['filename'], $listing['ext'], 'S3', 'card-img-top');
                        } else {
                            img('placeholders/default-thumbnail', 'jpg', 'local', 'card-img-top');
                        }

                        ?>

                        <div class="card-block">
                            <h4 class="card-title"><?php echo ucfirst((empty($listing['other_subcategory']) ? ($listing['subcategory_title']) : $listing['other_subcategory'])); ?></h4>
                            <h6 class="card-subtitle mb-2 text-muted">$<?php echo number_format($listing['price'] / 100, 2); ?></h6>
                            <p class="card-text"><?php echo $listing['quantity'] . ' in stock • ' . (($listing['is_available']) ? 'Available' : 'Unavailable'); ?></p>
                            <a href="<?php echo PUBLIC_ROOT . 'dashboard/food-listings/view?id=' . $listing['id']; ?>" class="btn btn-primary">Edit listing</a>
                        </div>
                    </div>
                    
                    <?php

                    if ($i % 3 == 0) {
                        echo '</div>';
                    }

                    $i++;
                }

                ?>
                    
            </div>
        <!-- cont main -->
    <!-- cont div.row -->
<!-- cont div.container-fluid -->