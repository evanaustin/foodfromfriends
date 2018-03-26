<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    <?php echo (isset($User->GrowerOperation) ? 'Edit' : 'Create') . ' your seller profile'; ?>
                </div>

                <div class="page-description text-muted small">
                    Customize your seller profile as it appears to buyers. <?php if (isset($User->GrowerOperation)) { echo "<span id=\"live-link\">View your live profile <a href=\"" . PUBLIC_ROOT . "{$User->GrowerOperation->link}\" class=\"bold\">here <i class=\"fa fa-angle-right\"></i></a></span>"; } ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="edit-basic-information" class="btn btn-success">
                        <i class="pre fa fa-floppy-o"></i>
                        Save changes
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr>

        <div class="alerts"></div>
        
        <form id="edit-basic-information" data-parsley-excluded="[disabled=disabled]">
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
                                    echo "<option value=\"{$operation_type['id']}\" " . ($operation_type['title'] == $User->GrowerOperation->type ? 'selected' : '') . ">" . ucfirst($operation_type['title']) . "</option>";
                                }

                                ?>
                            </select>

                            <small id="type-help" class="form-text text-muted" <?php if ($User->GrowerOperation->type != 'none') echo 'style="display:none"'; ?>>
                                If you're selling on behalf of a named entity, set the operation type here.
                            </small>
                        </div>
                    </div>
                
                    <div id="operation-details">
                        <div id="operation-name" class="form-group" <?php if (!isset($User->GrowerOperation) || $User->GrowerOperation->type == 'none') echo 'style="display:none"'; ?>>
                            <label>
                                Name
                            </label>

                            <input type="text" name="name" class="form-control" placeholder="Operation name" value="<?php echo (!empty($User->GrowerOperation->name) ? $User->GrowerOperation->name : '' );?>"  data-parsley-trigger="submit" required>
                        </div>

                        <label>
                            Where is your operation?
                        </label>

                        <div class="form-group">
                            <input type="text" name="address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($User->GrowerOperation->address_line_1)) echo $User->GrowerOperation->address_line_1; ?>" data-parsley-trigger="change" required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" value="<?php if (!empty($User->GrowerOperation->address_line_2)) echo $User->GrowerOperation->address_line_2; ?>" data-parsley-trigger="change">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control" placeholder="City" value="<?php if (!empty($User->GrowerOperation->city)) echo $User->GrowerOperation->city; ?>" data-parsley-trigger="change" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="state" class="form-control" placeholder="State" value="<?php if (!empty($User->GrowerOperation->state)) echo $User->GrowerOperation->state; ?>" data-parsley-pattern="^[A-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="zipcode" class="form-control" placeholder="Zip code" value="<?php if (!empty($User->GrowerOperation->zipcode)) { echo $User->GrowerOperation->zipcode; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                Bio
                            </label>
                            
                            <textarea type="text" name="bio" class="form-control" rows="4" placeholder="Describe your operation! Food From Friends is built on relationships."><?php if (!empty($User->GrowerOperation->bio)) echo $User->GrowerOperation->bio; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div id="operation-image">
                        <div class="form-group">
                            <label>
                                Operation photo
                            </label>
                                
                            <a href="" class="remove-image float-right" <?php if (empty($User->GrowerOperation->filename)) echo 'style="display: none;"' ?> data-toggle="tooltip" data-placement="left" title="Remove profile photo"><i class="fa fa-trash"></i></a>

                            <div class="image-box slide-over <?php if (!empty($User->GrowerOperation->filename)) echo 'existing-image'; ?>">
                                <div class="image-container">
                                    <?php
                                            
                                    if (!empty($User->GrowerOperation->filename)) {
                                        img(ENV . '/grower-operation-images/' . $User->GrowerOperation->filename, $User->GrowerOperation->ext . '?' . time(), 'S3', 'file');
                                    } else {
                                        img('placeholders/user-thumbnail', 'jpg', 'local', 'file');
                                    }

                                    ?>
                                    
                                    <input type="file" name="profile-image" accept="image/png/jpg">
                                    
                                    <div class="overlay-slide">
                                        <i class="fa fa-camera"></i>
                                        Add a new profile photo
                                    </div>
                                </div>
                            </div>

                            <small id="operation-photo-help" class="form-text text-muted <?php if (!empty($User->GrowerOperation->filename)) echo 'hidden'; ?>">
                                Buyers like to see you and your operation
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>