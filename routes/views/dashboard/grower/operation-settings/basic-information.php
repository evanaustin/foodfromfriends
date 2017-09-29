<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="main container animated fadeIn">
                <div class="row">
                    <div class="col-md-6">
                        <div class="page-title">
                            Edit your basic information
                        </div>

                        <div class="page-description text-muted small">
                            Howdy neighbor! If you're selling food on behalf of some named operation or entity, you can set you're listings to belong to that operation. If not, you don't need to change anything here!
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="controls">
                            <button type="submit" form="edit-basic-information" class="btn btn-primary">
                                <i class="pre fa fa-floppy-o"></i>
                                Save changes
                                <i class="post fa fa-gear loading-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="alert"></div>

                <form id="edit-basic-information" data-parsley-excluded="[disabled=disabled]">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="operation-type">
                                <div class="form-group">
                                    <label>
                                        Operation type
                                    </label>

                                    <select name="month" class="custom-select" data-parsley-trigger="submit" required>
                                        <?php 
                                    
                                        foreach ($operation_types as $operation_type) {
                                            ?><option val="<?php echo $operation_type['id']; ?>"><?php echo ucfirst($operation_type['title']); ?></option><?php
                                        }

                                        ?>
                                    </select>
                                </div>
                            </div>
                        
                            <div id="operation-details" <?php // if (!$details['is_offered']) { echo 'style="display:none;"'; } ?>>
                                <div class="form-group">
                                    <label>
                                        Name
                                    </label>

                                    <input type="text" name="first-name" class="form-control" aria-describedby="first name" placeholder="First name" value="<?php echo (!empty($User->first_name) ? $User->first_name : '' );?>"  data-parsley-trigger="submit" required>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Bio
                                    </label>
                                    
                                    <textarea type="text" name="bio" class="form-control" rows="4" placeholder="Describe yourself! Food From Friends is built on relationships."><?php if (!empty($User->bio)) { echo $User->bio ; } ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div id="operation-image" <?php // if (!$details['is_offered']) { echo 'style="display:none;"'; } ?>>
                                <label>
                                    Profile photo
                                </label>
                                    
                                <a href="" class="remove-image float-right" <?php if (empty($User->filename)) echo 'style="display: none;"' ?> data-toggle="tooltip" data-placement="left" title="Remove profile photo"><i class="fa fa-trash"></i></a>

                                <div class="image-box slide-over <?php if (!empty($User->filename)) echo 'existing-image'; ?>">
                                    <?php
                                            
                                    if (!empty($User->filename)) {
                                        img(ENV . '/profile-photos/' . $User->filename, $User->ext . '?' . time(), 'S3', 'file');
                                    } else {
                                        img('placeholders/default-thumbnail', 'jpg', 'local', 'file');
                                    }

                                    ?>
                                    
                                    <input type="file" name="profile-image" accept="image/png/jpg">
                                    
                                    <div class="overlay-slide">
                                        <i class="fa fa-camera"></i>
                                        Add a new profile photo
                                    </div>
                                </div>

                                <small id="profilePhotoHelp" class="form-text text-muted <?php if (!empty($User->filename)) echo 'hidden'; ?>">
                                    Upload a good photo of your face to make more friends!
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->