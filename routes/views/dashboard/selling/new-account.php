<!-- cont main -->
<div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Create a new seller account
                </div>

                <div class="page-description text-muted small">
                    Add your new seller profile information.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="create-new-account" class="btn btn-success">
                        <i class="pre fa fa-floppy-o"></i>
                        Create account
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr>

        <div class="alerts"></div>
        
        <form id="create-new-account" data-parsley-excluded="[disabled=disabled]">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>
                            Name
                        </label>

                        <input type="text" name="name" class="form-control" placeholder="Seller name" data-parsley-trigger="submit" required>
                    </div>    
                
                    <div class="form-group">
                        <label>
                            Seller type
                        </label>

                        <select name="type" class="custom-select" data-parsley-trigger="submit" required>
                            <option disabled selected>Select a seller account type</option>
                            <?php foreach ($operation_types as $operation_type) {
                                echo "<option value=\"{$operation_type['id']}\">" . ucfirst($operation_type['title']) . "</option>";
                            } ?>
                        </select>
                    </div>
                
                    <label>
                        Location <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Your exact address will not be shown on your seller profile" data-placement="right"></i>
                    </label>

                    <div class="form-group">
                        <input type="text" name="address-line-1" class="form-control" placeholder="Street address" data-parsley-trigger="change" required>
                    </div>

                    <div class="form-group">
                        <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" data-parsley-trigger="change">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="city" class="form-control" placeholder="City" data-parsley-trigger="change" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" placeholder="State" data-parsley-pattern="^[a-zA-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="zipcode" class="form-control" placeholder="Zip code" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            Bio
                        </label>
                        
                        <textarea type="text" name="bio" class="form-control" rows="4" placeholder="Tell your story. Food From Friends is built on relationships."></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div id="operation-image">
                        <div class="form-group">
                            <label>
                                Seller photo
                            </label>
                                
                            <a href="" class="remove-image float-right" style="display: none;" data-toggle="tooltip" data-placement="left" title="Remove profile photo"><i class="fa fa-trash"></i></a>

                            <div class="image-box slide-over">
                                <div class="image-container">
                                    <?php img('placeholders/user-thumbnail', 'jpg', [
                                        'server'    => 'local', 
                                        'class'     => 'file'
                                    ]); ?>
                                    
                                    <input type="file" name="profile-image" accept="image/png/jpg">
                                    
                                    <div class="overlay-slide">
                                        <i class="fa fa-camera"></i>
                                        Add a new profile photo
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>