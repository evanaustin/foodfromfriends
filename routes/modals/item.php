<div id="img-zoom-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <!-- <span id="zoom-title"></span> -->
                    <?= $FoodListing->title; ?>
                </h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <!-- <div id="zoom-src"></span> -->
                <?php img(ENV . '/items/' . $FoodListing->filename, $FoodListing->ext, [
                    'server'    => 'S3',
                    'class'     => 'img-fluid rounded drop-shadow'
                ]); ?>
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
                    <input type="hidden" name="seller-account-id" value="<?= $GrowerOperation->id ?>"/>

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