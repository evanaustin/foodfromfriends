<div id="img-zoom-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <!-- <span id="zoom-title"></span> -->
                    <?= $Seller->name; ?>
                </h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <!-- <div id="zoom-src"></span> -->
                
                <?= _img(ENV . '/grower-operation-images/' . $Seller->filename, $Seller->ext, [
                    'server'    => 'S3',
                    'class'     => 'img-fluid rounded drop-shadow'
                ]); ?>
            </div>
        </div>
    </div>
</div>

<div id="exchange-option-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Exchange option</h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <div class="alerts"></div>

                <form id="set-exchange-option">
                        
                    <?php if ($Seller->Delivery && $Seller->Delivery->is_offered): ?>

                        <?php $in_range = (isset($delivery_distance) && $delivery_distance <= $Seller->Delivery->distance); ?>
                        
                        <div class="exchange callout bubble <?php if (!$in_range) echo 'danger disabled' ?>" data-exchange-option="delivery">
                            <div class="muted font-18 thick">
                                Delivery
                            </div>
                            
                            <div>
                                Will deliver within: <strong><?= $Seller->Delivery->distance ?></strong> miles <?php if (!$in_range && isset($delivery_distance)) echo "(you are <span class=\"warning strong\">{$delivery_distance}</span> miles away)" ?>
                            </div>

                            <?php if ($Seller->Delivery->delivery_type == 'conditional'): ?>
                                
                                <div>
                                    Free delivery within: <?= $Seller->Delivery->free_distance ?> miles
                                </div>

                            <?php endif; ?>

                            <div>
                                <?= ($Seller->Delivery->delivery_type == 'free' ? 'Free' : 'Rate: $' . number_format($Seller->Delivery->fee / 100, 2) . ' ' . str_replace('-', ' ', $Seller->Delivery->pricing_rate)); ?>
                            </div>
                        </div>

                    <?php endif; ?>

                    <?php if ($Seller->Pickup && $Seller->Pickup->is_offered): ?>
                        
                        <div class="exchange callout bubble" data-exchange-option="pickup">
                            <div class="muted font-18 thick">
                                Pickup
                            </div>

                            <div>
                                <?= "{$Seller->city}, {$Seller->state}"; ?>
                            </div>
                            
                            <?php
                            
                            if (isset($distance) && $distance['length'] > 0) {
                                echo "<div>{$distance['length']} {$distance['units']} away</div>";
                            }

                            ?>
                        </div>

                    <?php endif; ?>

                    <?php if ($Seller->Meetup && $Seller->Meetup->is_offered): ?>
                        
                        <div class="exchange callout bubble" data-exchange-option="meetup">
                            <div class="muted font-18 thick">
                                Meetup
                            </div>

                            <div>
                                <?= $Seller->Meetup->address_line_1 . (($Seller->Meetup->address_line_2) ? ", {$Seller->Meetup->address_line_2}" : '') ?><br>
                                <?= "{$Seller->Meetup->city}, {$Seller->Meetup->state} {$Seller->Meetup->zipcode}" ?><br>
                                <?= $Seller->Meetup->time ?>
                            </div>
                        </div>

                    <?php endif; ?>
                    
                    <button type="submit" class="btn btn-block btn-primary">
                        Save <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="delivery-address-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Delivery address</h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <form id="edit-delivery-address">
                    <label>
                        Enter the address you want your items delivered to
                    </label>

                    <div class="form-group">
                        <input type="text" name="address-line-1" class="form-control" placeholder="Street address" data-parsley-trigger="change" required>
                    </div>

                    <div class="form-group">
                        <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" data-parsley-trigger="change">
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <input type="text" name="city" class="form-control" placeholder="City" data-parsley-trigger="change" required>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" placeholder="State" data-parsley-pattern="^[A-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                        
                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <input type="text" name="zipcode" class="form-control" placeholder="Zip code" parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea name="instructions" class="form-control" placeholder="Enter any special delivery instructions the seller should know"></textarea>
                    </div>

                    <button type="submit" class="btn btn-block btn-primary">
                        Save <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>