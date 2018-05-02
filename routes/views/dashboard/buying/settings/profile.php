<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Your buyer profile
                </div>

                <div class="page-description text-muted small">
                    Edit your buyer profile information. <span id="live-link">Click <a href="<?= PUBLIC_ROOT . $User->BuyerAccount->link; ?>" class="bold">here</a> to view your live profile.</span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="edit-profile" class="btn btn-success">
                        <i class="pre fa fa-floppy-o"></i>
                        Save changes
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <hr>

        <div class="alerts"></div>
        
        <form id="edit-profile">
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="form-group">
                        <label>
                            Name
                        </label>

                        <div class="input-group"> 
                            <input type="text" name="name" class="form-control" placeholder="Buyer name" value="<?php if (!empty($User->BuyerAccount->name)) echo $User->BuyerAccount->name ?>"  data-parsley-trigger="change" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            Buyer type
                        </label>

                        <select name="type" class="custom-select" data-parsley-trigger="submit" required>
                            <?php
                        
                            foreach ($buyer_account_types as $buyer_account_type) {
                                echo "<option value=\"{$buyer_account_type['id']}\" " . ($buyer_account_type['title'] == $User->BuyerAccount->type ? 'selected' : '') . ">" . ucfirst($buyer_account_type['title']) . "</option>";
                            }

                            ?>
                        </select>
                    </div>
                    
                    <label>
                        Location <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Your exact address will only be shared with sellers during order delivery"></i>
                    </label>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" name="address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($User->BuyerAccount->Address->address_line_1)) { echo $User->BuyerAccount->Address->address_line_1; } ?>" data-parsley-trigger="change" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, etc." value="<?php if (!empty($User->BuyerAccount->Address->address_line_2)) { echo $User->BuyerAccount->Address->address_line_2; } ?>" data-parsley-trigger="change">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <input type="text" name="city" class="form-control" placeholder="City" value="<?php if (!empty($User->BuyerAccount->Address->city)) { echo $User->BuyerAccount->Address->city; } ?>" data-parsley-trigger="change" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" placeholder="State" value="<?php if (!empty($User->BuyerAccount->Address->state)) { echo $User->BuyerAccount->Address->state; } ?>" data-parsley-pattern="^[a-zA-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <input type="text" name="zipcode" class="form-control" placeholder="Zip code" value="<?php if (!empty($User->BuyerAccount->Address->zipcode)) { echo $User->BuyerAccount->Address->zipcode; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            Bio
                        </label>
                        
                        <textarea type="text" name="bio" class="form-control" rows="4" placeholder="Describe yourself! Food From Friends is built on relationships."><?php if (!empty($User->BuyerAccount->bio)) { echo $User->BuyerAccount->bio; } ?></textarea>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <label>
                        Profile photo
                    </label>
                        
                    <a href="" class="remove-image float-right" <?php if (empty($User->BuyerAccount->Image->filename)) echo 'style="display: none;"' ?> data-toggle="tooltip" data-placement="left" title="Remove profile photo"><i class="fa fa-trash"></i></a>

                    <div class="image-box slide-over <?php if (!empty($User->BuyerAccount->Image->filename)) echo 'existing-image'; ?>">
                        <div class="image-container">
                            <?php
                                    
                            if (!empty($User->BuyerAccount->Image->filename)) {
                                img(ENV . "/{$User->BuyerAccount->Image->path}/{$User->BuyerAccount->Image->filename}", $User->BuyerAccount->Image->ext . '?' . time(), [
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
                                Add a new profile photo
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>