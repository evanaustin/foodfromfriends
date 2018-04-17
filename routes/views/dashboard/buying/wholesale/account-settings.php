<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    <?= (isset($User->WholesaleAccount) ? 'Edit your' : 'Create a') . ' wholesale buying account'; ?>
                </div>

                <div class="page-description text-muted small">
                    Build out your profile as a wholesale buyer. This information will be available to the public. <?php if (isset($User->WholesaleAccount)) { echo "<span id=\"live-link\">View your live profile <a href=\"" . PUBLIC_ROOT . "{$User->WholesaleAccount->link}\" class=\"bold\">here <i class=\"fa fa-angle-right\"></i></a></span>"; } ?>
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
                                Wholesale account type
                            </label>

                            <select name="type" class="custom-select" data-parsley-trigger="submit" required>
                                <option selected disabled>Select a wholesale account type</option>

                                <?php foreach ($wholesale_account_types as $wholesale_account_type) {
                                    echo "<option value=\"{$wholesale_account_type['id']}\" " . ($wholesale_account_type['title'] == $User->WholesaleAccount->type ? 'selected' : '') . ">" . ucfirst($wholesale_account_type['title']) . "</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                
                    <div id="operation-details">
                        <div class="form-group">
                            <label>
                                Name
                            </label>

                            <input type="text" name="name" class="form-control" placeholder="Wholesale account name" value="<?= (!empty($User->WholesaleAccount->name) ? $User->WholesaleAccount->name : '' );?>"  data-parsley-trigger="submit" required>
                        </div>

                        <label>
                            Where is your business located? 
                            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Your exact address will not be shown on your wholesale account profile" data-placement="right"></i>
                        </label>

                        <div class="form-group">
                            <input type="text" name="address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($User->WholesaleAccount->address_line_1)) echo $User->WholesaleAccount->address_line_1; ?>" data-parsley-trigger="change" required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" value="<?php if (!empty($User->WholesaleAccount->address_line_2)) echo $User->WholesaleAccount->address_line_2; ?>" data-parsley-trigger="change">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control" placeholder="City" value="<?php if (!empty($User->WholesaleAccount->city)) echo $User->WholesaleAccount->city; ?>" data-parsley-trigger="change" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="state" class="form-control" placeholder="State" value="<?php if (!empty($User->WholesaleAccount->state)) echo $User->WholesaleAccount->state; ?>" data-parsley-pattern="^[A-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="zipcode" class="form-control" placeholder="Zip code" value="<?php if (!empty($User->WholesaleAccount->zipcode)) { echo $User->WholesaleAccount->zipcode; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                Bio
                            </label>
                            
                            <textarea type="text" name="bio" class="form-control" rows="4" placeholder="Tell your story. Food From Friends is built on relationships."><?php if (!empty($User->WholesaleAccount->bio)) echo $User->WholesaleAccount->bio; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div id="operation-image">
                        <div class="form-group">
                            <label>
                                Wholesale account photo
                            </label>
                                
                            <a href="" class="remove-image float-right" <?php if (empty($User->WholesaleAccount->filename)) echo 'style="display: none;"' ?> data-toggle="tooltip" data-placement="left" title="Remove profile photo"><i class="fa fa-trash"></i></a>

                            <div class="image-box slide-over <?php if (!empty($User->WholesaleAccount->filename)) echo 'existing-image'; ?>">
                                <div class="image-container">
                                    <?php
                                            
                                    if (!empty($User->WholesaleAccount->filename)) {
                                        img(ENV . '/wholesale-account-images/' . $User->WholesaleAccount->filename, $User->WholesaleAccount->ext . '?' . time(), [
                                            'server'    => 'S3',
                                            'class'     => 'img-fluid file'
                                        ]);
                                    } else {
                                        img('placeholders/user-thumbnail', 'jpg', [
                                            'server'    => 'local', 
                                            'class'     => 'file'
                                        ]);
                                    }

                                    ?>
                                    
                                    <input type="file" name="profile-image" accept="image/png/jpg">
                                    
                                    <div class="overlay-slide">
                                        <i class="fa fa-camera"></i>
                                        Add a new wholesale account photo
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