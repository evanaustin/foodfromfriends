<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="main container animated fadeIn">
                <div class="row">
                    <div class="col-md-6">
                        <div class="page-title">
                            Create an operation
                        </div>

                        <div class="page-description text-muted small">
                            Howdy neighbor! Setting an operation for yourself assigns any food listings you create to that operation rather than to you as an individual. This clarifies your presence as a grower and lets teams work together through Food From Friends.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="controls">
                            <button type="submit" form="create-new" class="btn btn-primary">
                                <i class="pre fa fa-upload"></i>
                                Create operation
                                <i class="post fa fa-gear loading-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="alert"></div>
                
                <form id="create-new" data-parsley-excluded="[disabled=disabled]">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="operation-type">
                                <div class="form-group">
                                    <label>
                                        Create new operation
                                    </label>

                                    <select name="type" class="custom-select" data-parsley-trigger="submit" required>
                                        <?php
                                    
                                        foreach ($operation_types as $operation_type) {
                                            // should just echo here
                                            ?><option value="<?php echo $operation_type['id']; ?>"><?php echo ucfirst($operation_type['title']); ?></option><?php
                                        }

                                        ?>
                                    </select>

                                    <small id="new-operation-help" class="form-text text-muted">
                                        If you happen to be selling food on behalf of some named operation or entity, you can create that operation here.
                                    </small>
                                </div>
                            </div>
                        
                            <div id="operation-details" style="display:none;">
                                <div class="form-group">
                                    <label>
                                        Name
                                    </label>

                                    <input type="text" name="name" class="form-control" placeholder="Operation name" data-parsley-trigger="submit" disabled>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Bio
                                    </label>
                                    
                                    <textarea type="text" name="bio" class="form-control" rows="4" placeholder="Describe your operation! Food From Friends is built on relationships." disabled></textarea>
                                </div>
                            </div>

                            <div id="existing-operation">
                                <p class="small strong text-muted margin-btm-75pem line-after">
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
                                        
                                    <a href="" class="remove-image float-right" <?php if (empty($User->GrowerOperation->filename)) echo 'style="display: none;"' ?> data-toggle="tooltip" data-placement="left" title="Remove profile photo"><i class="fa fa-trash"></i></a>

                                    <div class="image-box slide-over <?php if (!empty($User->GrowerOperation->filename)) echo 'existing-image'; ?>">
                                        <?php
                                                
                                        if (!empty($User->GrowerOperation->filename)) {
                                            img(ENV . '/grower-operation-images/' . $User->GrowerOperation->filename, $User->GrowerOperation->ext . '?' . time(), 'S3', 'file');
                                        } else {
                                            img('placeholders/default-thumbnail', 'jpg', 'local', 'file');
                                        }

                                        ?>
                                        
                                        <input type="file" name="profile-image" accept="image/png/jpg" <?php //echo (($User->GrowerOperation->type == 'none') ? 'disabled' : 'required'); ?>>
                                        
                                        <div class="overlay-slide">
                                            <i class="fa fa-camera"></i>
                                            Add a new profile photo
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
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->