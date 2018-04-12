<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Create an operation
                </div>

                <div class="page-description text-muted small">
                    Sell publicly as some kind of named entity rather than just as an individual. This clarifies your presence to buyers and allows teams to work together.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="create-new" class="btn btn-success">
                        <i class="pre fa fa-upload"></i>
                        Create operation
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr>

        <div class="alerts"></div>
        
        <form id="create-new" data-parsley-excluded="[disabled=disabled]">
            <div class="row">
                <div class="col-md-8">
                    <div id="operation-type">
                        <div class="form-group">
                            <label>
                                Operation type
                            </label>

                            <select name="type" class="custom-select" data-parsley-trigger="submit" required>
                                <?php
                            
                                foreach ($operation_types as $operation_type) {
                                    if ($operation_type['title'] == 'individual') {
                                        echo '<option disabled selected>Select an operation type</option>';
                                    } else {
                                        echo "<option value=\"{$operation_type['id']}\">" . ucfirst($operation_type['title']) . "</option>";
                                    }
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                
                    <div id="operation-details" style="display:none;">
                        <div class="form-group">
                            <label>
                                Name
                            </label>

                            <input type="text" name="name" class="form-control" placeholder="Operation name" data-parsley-trigger="submit" disabled>
                        </div>

                        <label>
                            Where is your operation?
                        </label>

                        <div class="form-group">
                            <input type="text" name="address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($User->GrowerOperation->address_line_1)) { echo $User->GrowerOperation->address_line_1; } ?>" data-parsley-trigger="change" <?= (($User->GrowerOperation->type == 'individual') ? 'disabled' : 'required'); ?>>
                        </div>

                        <div class="form-group">
                            <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" value="<?php if (!empty($User->GrowerOperation->address_line_2)) { echo $User->GrowerOperation->address_line_2; } ?>" data-parsley-trigger="change" <?php if ($User->GrowerOperation->type == 'individual') { echo 'disabled'; } ?>>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control" placeholder="City" value="<?php if (!empty($User->GrowerOperation->city)) { echo $User->GrowerOperation->city; } ?>" data-parsley-trigger="change" <?= (($User->GrowerOperation->type == 'individual') ? 'disabled' : 'required'); ?>>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="state" class="form-control" placeholder="State" value="<?php if (!empty($User->GrowerOperation->state)) { echo $User->GrowerOperation->state; } ?>" data-parsley-pattern="^[A-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" <?= (($User->GrowerOperation->type == 'individual') ? 'disabled' : 'required'); ?>>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="zipcode" class="form-control" placeholder="Zip code" value="<?php if (!empty($User->GrowerOperation->zipcode)) { echo $User->GrowerOperation->zipcode; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" <?= (($User->GrowerOperation->type == 'individual') ? 'disabled' : 'required'); ?>>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                Bio
                            </label>
                            
                            <textarea type="text" name="bio" class="form-control" rows="4" placeholder="Describe your operation! Food From Friends is built on relationships." disabled></textarea>
                        </div>
                    </div>

                    <div id="existing-operation">
                        <p class="small strong text-muted margin-btm-75em line-after">
                            OR
                        </p>

                        <div class="form-group">
                            <label>
                                Join existing operation
                            </label>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="operation-key" class="form-control" placeholder="The operation's referral key" data-parsley-trigger="submit">
                                </div>

                                <div class="col-md-6">
                                    <input type="text" name="personal-key" class="form-control" placeholder="Your personal referral key" data-parsley-trigger="submit">
                                </div>
                            </div>

                            <small class="form-text text-muted">
                                If you were invited to join an operation that's already on Food From Friends, enter the two keys provided via email here.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div id="operation-image" style="display:none;">
                        <div class="form-group">
                            <label>
                                Operation photo
                            </label>
                                
                            <a href="" class="remove-image float-right" style="display: none;" data-toggle="tooltip" data-placement="left" title="Remove profile photo"><i class="fa fa-trash"></i></a>

                            <div class="image-box slide-over">
                                <div class="image-container">
                                    <?php
                                            
                                    img('placeholders/default-thumbnail', 'jpg', [
                                        'server'    => 'local', 
                                        'class'     => 'file'
                                    ]);

                                    ?>
                                    
                                    <input type="file" name="profile-image" accept="image/png/jpg" <?php //echo (($User->GrowerOperation->type == 'individual') ? 'disabled' : 'required'); ?>>
                                    
                                    <div class="overlay-slide">
                                        <i class="fa fa-camera"></i>
                                        Add a new profile photo
                                    </div>
                                </div>
                            </div>

                            <small id="operation-photo-help" class="form-text text-muted <?php if (!empty($User->GrowerOperation->filename)) echo 'hidden'; ?>">
                                Smiling faces on site at your operation are most engaging!
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>