<div id="img-zoom-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <!-- <span id="zoom-title"></span> -->
                    <?= $SellerAccount->name; ?>
                </h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <!-- <div id="zoom-src"></span> -->
                
                <?= _img(ENV . '/grower-operation-images/' . $SellerAccount->filename, $SellerAccount->ext, [
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
                <h3 class="modal-title">
                    Exchange option
                </h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span> 
                </button>
            </div>
            
            <div class="modal-body">
                <div class="alerts"></div>

                <form id="set-exchange-option">
                        
                    <?php if ($SellerAccount->Delivery && $SellerAccount->Delivery->is_offered): ?>

                        <?php $in_range = (isset($distance_miles) && $distance_miles <= $SellerAccount->Delivery->distance); ?>
                        
                        <div class="exchange callout bubble <?php if (!$in_range) echo 'danger disabled' ?>" data-exchange="delivery">
                            <div class="muted font-18 thick">
                                Delivery
                            </div>
                            
                            <div>
                                Will deliver within: <?= $SellerAccount->Delivery->distance ?> miles 

                                <?php if (!$in_range && isset($distance_miles)): ?>

                                    (you are <span class="warning strong"><?= $distance_miles ?></span> miles away)
                                
                                <?php endif;?>

                            </div>

                            <?php if ($SellerAccount->Delivery->delivery_type == 'conditional'): ?>
                                
                                <div>
                                    Free delivery within: <?= $SellerAccount->Delivery->free_distance ?> miles
                                </div>

                            <?php endif; ?>

                            <?php if ($in_range): ?>

                                <div>
                                    Deliver to: <?= "{$User->BuyerAccount->Address->address_line_1}, {$User->BuyerAccount->Address->city}, {$User->BuyerAccount->Address->state} {$User->BuyerAccount->Address->zipcode}" ?> <a href="<?= PUBLIC_ROOT ?>dashboard/buying/settings/profile"><strong>(edit)</strong></a>
                                </div>

                                <div>
                                    Cost: <?= ($SellerAccount->Delivery->delivery_type == 'free' ? 'Free' : 'Rate: $' . number_format($SellerAccount->Delivery->fee / 100, 2) . ' ' . str_replace('-', ' ', $SellerAccount->Delivery->pricing_rate)); ?>
                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                    <?php /* if ($SellerAccount->Meetup && $SellerAccount->Meetup->is_offered): ?>
                        
                        <div class="exchange callout bubble" data-exchange-option="meetup">
                            <div class="muted font-18 thick">
                                Meetup
                            </div>

                            <div>
                                <?= $SellerAccount->Meetup->address_line_1 . (($SellerAccount->Meetup->address_line_2) ? ", {$SellerAccount->Meetup->address_line_2}" : '') ?><br>
                                <?= "{$SellerAccount->Meetup->city}, {$SellerAccount->Meetup->state} {$SellerAccount->Meetup->zipcode}" ?><br>
                                <?= $SellerAccount->Meetup->time ?>
                            </div>
                        </div>

                    <?php endif; */ ?>

                    <?php if (!empty($meetups)): ?>
                            
                        <?php foreach ($meetups as $meetup): ?>
                        
                            <div class="exchange callout bubble <?php if (!empty($meetup['deadline']) || !empty($meetup['order_minimum'])) echo 'warning text-muted' ?>" data-exchange="<?= $meetup['id'] ?>">
                                <div class="muted font-18 thick">
                                    <i class="fa fa-map-signs" data-toggle="tooltip" data-title="Meet here at the time specified to pick up your order"></i>
                                    <?= (!empty($meetup['title'])) ? $meetup['title'] : "{$meetup['address_line_1']} {$meetup['address_line_2']}" ?>
                                </div>
                                
                                <div class="<?php if (empty($meetup['title'])) echo 'hidden' ?>">
                                    <?= "{$meetup['address_line_1']} {$meetup['address_line_2']}" ?>
                                </div>
                                
                                <div>
                                    <?= "{$meetup['city']}, {$meetup['state']}" ?>
                                </div>
                                
                                <div>
                                    <?= "{$meetup['day']} &bull; {$meetup['start_time']} &ndash; {$meetup['end_time']}" ?>
                                </div>

                                <?php if (!empty($meetup['deadline'])): ?>
                                        
                                    <div>
                                        Order
                                        <strong class="warning"><?= $meetup['deadline'] ?></strong>
                                        hours in advance
                                    </div>
                                
                                <?php endif ?>

                                <?php if (!empty($meetup['order_minimum'])): ?>
                        
                                    <div>
                                        <strong class="warning"><?= _amount($meetup['order_minimum']) ?></strong>
                                        minimum order
                                    </div>
                                
                                <?php endif ?>

                            </div>
                    
                        <?php endforeach ?>

                    <?php endif ?>
                    
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
                                <input type="text" name="state" class="form-control" placeholder="State" data-parsley-pattern="^[a-zA-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
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