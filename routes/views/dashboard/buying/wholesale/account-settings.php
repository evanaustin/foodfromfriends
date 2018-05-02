<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    <?= (isset($User->BuyerAccount) ? 'Edit your' : 'Create a') . ' wholesale buying account'; ?>
                </div>

                <div class="page-description text-muted small">
                    Build out your profile as a wholesale buyer. This information will be available to the public. <?php if (isset($User->BuyerAccount)) { echo "<span id=\"live-link\">View your live profile <a href=\"" . PUBLIC_ROOT . "{$User->BuyerAccount->link}\" class=\"bold\">here <i class=\"fa fa-angle-right\"></i></a></span>"; } ?>
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

                                <?php foreach ($buyer_account_types as $buyer_account_type) {
                                    if ($buyer_account_type['id'] == 1) continue;
                                    echo "<option value=\"{$buyer_account_type['id']}\" " . ($buyer_account_type['title'] == $User->BuyerAccount->type ? 'selected' : '') . ">" . ucfirst($buyer_account_type['title']) . "</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                
                    <div id="operation-details">
                        <div class="form-group">
                            <label>
                                Name
                            </label>

                            <input type="text" name="name" class="form-control" placeholder="Wholesale account name" value="<?= (!empty($User->BuyerAccount->name) ? $User->BuyerAccount->name : '' );?>"  data-parsley-trigger="submit" required>
                        </div>

                        <label>
                            Where is your business located? 
                            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Your exact address will not be shown on your wholesale account profile" data-placement="right"></i>
                        </label>

                        <div class="form-group">
                            <input type="text" name="address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($User->BuyerAccount->address_line_1)) echo $User->BuyerAccount->address_line_1; ?>" data-parsley-trigger="change" required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" value="<?php if (!empty($User->BuyerAccount->address_line_2)) echo $User->BuyerAccount->address_line_2; ?>" data-parsley-trigger="change">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control" placeholder="City" value="<?php if (!empty($User->BuyerAccount->city)) echo $User->BuyerAccount->city; ?>" data-parsley-trigger="change" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="state" class="form-control" placeholder="State" value="<?php if (!empty($User->BuyerAccount->state)) echo $User->BuyerAccount->state; ?>" data-parsley-pattern="^[a-zA-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="zipcode" class="form-control" placeholder="Zip code" value="<?php if (!empty($User->BuyerAccount->zipcode)) { echo $User->BuyerAccount->zipcode; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                Bio
                            </label>
                            
                            <textarea type="text" name="bio" class="form-control" rows="4" placeholder="Tell your story. Food From Friends is built on relationships."><?php if (!empty($User->BuyerAccount->bio)) echo $User->BuyerAccount->bio; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div id="operation-image">
                        <div class="form-group">
                            <label>
                                Wholesale account photo
                            </label>
                                
                            <a href="" class="remove-image float-right" <?php if (empty($User->BuyerAccount->filename)) echo 'style="display: none;"' ?> data-toggle="tooltip" data-placement="left" title="Remove profile photo"><i class="fa fa-trash"></i></a>

                            <div class="image-box slide-over <?php if (!empty($User->BuyerAccount->filename)) echo 'existing-image'; ?>">
                                <div class="image-container">
                                    <?php
                                            
                                    if (!empty($User->BuyerAccount->filename)) {
                                        img(ENV . '/buyer-account-images/' . $User->BuyerAccount->filename, $User->BuyerAccount->ext . '?' . time(), [
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