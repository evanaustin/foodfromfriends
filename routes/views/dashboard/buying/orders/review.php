<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Review this order
                </div>
        
                <div class="page-description text-muted small">
                    This is your opportunity to provide feedback regarding the items you received and the seller you received them from. You have three days after item fulfillment to leave a review or, alternatively, <a href="" class="bold">report an issue</a> with the order.
                </div>
            </div>
        
            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="review-order" class="btn btn-success">
                        <i class="pre fa fa-pencil"></i>
                        Submit review
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr>

        <div class="alerts"></div>

        <form id="review-order">
            <input type="hidden" name="ordergrower-id" value="<?= $OrderGrower->id; ?>">

            <div class="row">
                <div class="col-md-3 flexbox flexjustifycenter flexcenter">
                    <div class="user-block">
                        <div class="user-photo" style="background-image: url('<?= 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$Seller->filename}.{$Seller->ext}"; ?>');"></div>
                        
                        <div class="user-content">
                            <h5 class="bold margin-btm-25em">
                                <a href=""><?= $Seller->name; ?></a>
                            </h5>

                            <small>
                                <?= "{$Seller->city}, {$Seller->state}"; ?>
                            </small>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-9">
                    <label>
                        Rate seller
                    </label>

                    <fieldset>
                        <span class="form-star-group">
                            <?php

                            foreach ($scores as $score => $title) {
                                
                                echo "
                                    <div class=\"custom-control custom-radio custom-control-inline\">
                                        <input type=\"radio\" id=\"seller-rating-{$score}\" name=\"seller-score\" class=\"custom-control-input\" value=\"{$score}\">
                                        <label class=\"custom-control-label\" for=\"seller-rating-{$score}\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-title=\"{$title}\">{$score}</label>
                                    </div>
                                ";
                                        

                                // echo "<input type=\"radio\" id=\"seller-rating-{$score}\" name=\"seller-score\" value=\"{$score}\"/>";
                                // echo "<label for=\"seller-rating-{$score}\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-title=\"{$title}\">{$score}</label>";

                            }

                            ?>
                        </span>
                    </fieldset>
                    
                    <div class="form-group no-margin">
                        <label for="seller-review">Write a review</label>
                        <textarea type="text" name="seller-review" rows="3" class="form-control" placeholder="Write a description of your experience buying from this seller"></textarea>
                    </div>
                </div>
            </div>

            <?php

            foreach ($OrderGrower->Items as $OrderItem) {

                $Item = new Item([
                    'DB' => $DB,
                    'id' => $OrderItem->item_id
                ]);
                
                ?>

                <div class="row margin-top-1em">
                    <div class="col-md-3 flexbox flexcenter">
                        <div class="card-alt no-bg">
                            <div class="item-image">
                                <div class="user-photo no-margin" style="background-image: url('<?= (isset($Item->Image->id) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/item-images/{$Item->Image->filename}.{$Item->Image->ext}" : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg') ?>');"></div>
                            </div>

                            <div class="card-body">
                                <h6 class="strong">
                                    <a href="<?= PUBLIC_ROOT . $Seller->link . '/' . $Item->link; ?>">
                                        <?= ucfirst($Item->title); ?>
                                    </a>
                                </h6>
                                
                                <div class="small light-gray">
                                <?= ucfirst(((!empty($OrderItem->measurement) && !empty($OrderItem->metric)) ? "{$OrderItem->measurement} {$OrderItem->metric} {$OrderItem->package_type}" : $OrderItem->package_type)) ?>
                                </div>
                                
                                <?php
                                                        
                                if (!empty($OrderItem->weight) && !empty($OrderItem->units)) {
                                    echo '<small class="light-gray"><span>';
                                    amount(($OrderItem->unit_price / $OrderItem->unit_weight));
                                    echo " / {$OrderItem->weight_units}</span></small>";
                                }
                                
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <label>
                            Rate item
                        </label>

                        <fieldset>
                            <span class="form-star-group">
                                <?php

                                foreach ($scores as $score => $title) {
                                    $item_id = "item-{$OrderItem->item_id}-rating-{$score}";

                                    // echo "<input type=\"radio\" id=\"{$item_id}\" name=\"items[{$OrderItem->item_id}][score]\" value=\"{$score}\"/>";
                                    // echo "<label for=\"{$item_id}\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-title=\"{$title}\">{$score}</label>";

                                    echo "
                                        <div class=\"custom-control custom-radio custom-control-inline\">
                                            <input type=\"radio\" id=\"{$item_id}\" name=\"items[{$OrderItem->item_id}][score]\" class=\"custom-control-input\" value=\"{$score}\">
                                            <label class=\"custom-control-label\" for=\"{$item_id}\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-title=\"{$title}\">{$score}</label>
                                        </div>
                                    ";

                                }

                                ?>
                            </span>

                            <div class="form-group no-margin">
                                <label for="item-<?= $OrderItem->item_id; ?>-review">Write a review</label>
                                <textarea type="text" name="items[<?= $OrderItem->item_id; ?>][review]" rows="3" class="form-control" placeholder="Write a description of your thoughts on this item"></textarea>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <?php

            }

            ?>
        </form>
    </div>
</main>