<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Report this order
                </div>
        
                <div class="page-description text-muted small">
                    You can choose to report an issue with this order. We'll reach out to the seller and work to resolve the problem.
                </div>
            </div>
        
            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="report-order" class="btn btn-warning">
                        <i class="pre fa fa-pencil"></i>
                        Report issue
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr>

        <div class="alerts"></div>

        <form id="report-order">
            <input type="hidden" name="ordergrower-id" value="<?php echo $OrderGrower->id; ?>">

            <div class="row margin-btm-2em">
                <div class="col-md-3 flexbox flexjustifycenter flexcenter">
                    <div class="user-block">
                        <div class="user-photo" style="background-image: url('<?php echo 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$Seller->filename}.{$Seller->ext}"; ?>');"></div>
                        
                        <div class="user-content">
                            <h5 class="bold margin-btm-25em">
                                <a href="<?php echo PUBLIC_ROOT . $Seller->link; ?>"><?php echo $Seller->name; ?></a>
                            </h5>

                            <small>
                                <?php echo "{$Seller->city}, {$Seller->state}"; ?>
                            </small>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-9">
                    <div class="form-group no-margin">
                        <label for="seller-review">Tell us about the problem <i class="fa fa-eye-slash" data-toggle="tooltip" data-placement="right" title="This message will not be shown to the seller or to the public"></i></label>
                        <textarea type="text" name="message" rows="3" class="form-control" placeholder="Write a description of your problem with this order so that customer service can follow up."></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>