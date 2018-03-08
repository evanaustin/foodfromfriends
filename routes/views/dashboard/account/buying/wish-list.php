<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Your wish list
                </div>
        
                <div class="page-description text-muted small">
                    Select your desired items below to build a wish list. Sellers can see this data to make informed decisions about what they offer!
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="wish-list" class="btn btn-success">
                        <i class="pre fa fa-pencil"></i>
                        Save preferences
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr>

        <form id="wish-list">
            <div class="row">
                <?php
                
                $Slug = new Slug([
                    'DB' => $DB
                ]);

                foreach ($category_assns as $category_id => $category) {
                    
                    ?>
                    
                    <div class="col-12 category margin-top-btm-1em" data-cat="<?php echo $category_id; ?>">
                        <div class="callout">
                            <h5 class="thick margin-btm-1em">
                                <?php echo ucfirst($category['title']); ?>
                            </h5>
                            
                            <div class="row">
                            
                                <?php
    
                                foreach ($category['subcategories'] as $subcategory_id => $subcategory) {
                                    
                                    ?>
                                    
                                    <div class="col-6 col-md-4 col-lg-3 subcategory">
                                        
                                        <?php
                                        
                                        /* if (!empty($subcategory['varieties'])) {
    
                                            ?>
    
                                            <div class="dropdown">
                                                <div id="<?php echo "dropdown-{$subcategory_id}-btn"; ?>" class="btn btn-secondary btn-block dropdown-toggle margin-btm-50em" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-sub="<?php echo $subcategory_id; ?>">
                                                    <?php echo ucfirst($subcategory['title']); ?>
                                                </div>
    
                                                <div class="dropdown-menu" aria-labelledby="<?php echo "dropdown-{$subcategory_id}-btn"; ?>">
                                                    
                                                    <?php
    
                                                    foreach ($subcategory['varieties'] as $id => $variety) {
                                                        console_log(json_encode($id));
                                                        echo "<a class=\"dropdown-item variety\" data-vari=\"{$id}\">{$variety['title']}</a>";
                                                    }
    
                                                    ?>
    
                                                </div>
                                            </div>
    
                                            <?php
    
                                        } else {
                                             */
                                            if (isset($extant_subcategories[$subcategory_id])) {
                                                echo "<div class=\"btn btn-secondary btn-block margin-btm-50em active\" data-id=\"{$extant_subcategories[$subcategory_id]}\" data-sub=\"{$subcategory_id}\">";
                                                echo ucfirst($subcategory['title']);
                                                echo "</div>";
                                            } else {
                                                echo "<div class=\"btn btn-secondary btn-block margin-btm-50em\" data-sub=\"{$subcategory_id}\">";
                                                echo ucfirst($subcategory['title']);
                                                echo "</div>";
                                            }
                                        // }
                                        
                                        ?>
                                    </div>
    
                                    <?php
                                    
                                }
    
                                ?>
    
                            </div>
                        </div>    
                    </div>

                    <?php
                }
                
                ?>
            </div>
        </form>
    </div>
</main>

<script>
    var extant_wishlist = <?php echo json_encode($wishlist); ?>
</script>