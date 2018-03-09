<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Edit your basic information
                </div>

                <div class="page-description text-muted small">
                    Woo-hoo! Refine and complete your basic information to get the most out of the Food From Friends community.
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

        <form id="edit-basic-information">
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="form-group">
                        <label>
                            Name
                        </label>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group"> 
                                    <input type="text" name="first-name" class="form-control" aria-describedby="first name" placeholder="First name" value="<?php echo (!empty($User->first_name) ? $User->first_name : '' );?>"  data-parsley-trigger="submit" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="last-name" class="form-control" aria-describedby="last name" placeholder="Last name" value="<?php echo (!empty($User->last_name) ? $User->last_name : '' );?>" data-parsley-trigger="submit" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Email address <i class="fa fa-eye-slash" data-toggle="tooltip" data-placement="right" title="Private"></i>
                                </label>

                                <div class="input-group w-addon">
                                    <input type="email" name="email" class="form-control" placeholder="Enter your email address" value="<?php if (!empty($User->email)) { echo $User->email; } ?>" data-parsley-trigger="change" required>
        
                                    <span class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>

                                <small class="form-text text-muted">
                                    We won’t share your private email address.
                                </small>
                            </div>
                        </div>
        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Phone number <i class="fa fa-eye-slash" data-toggle="tooltip" data-placement="right" title="Private"></i>
                                </label>

                                <div class="input-group w-addon">
                                    <input type="text" name="phone" class="form-control bfh-phone" placeholder="Enter your phone number" value="<?php if (!empty($User->phone)) { echo $User->phone; } ?>" data-format="+1 (ddd) ddd-dddd" data-parsley-length="[17,17]" data-parsley-length-message="This value is incomplete." data-parsley-trigger="change" required>
        
                                    <span class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </span>
                                </div>

                                <small class="form-text text-muted">
                                    This is only shared during order fulfillment.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            Birthday <i class="fa fa-eye-slash" data-toggle="tooltip" data-placement="right" title="Private"></i>
                        </label>

                        <div class="row">
                            <div class="col-md-4">
                                <select name="month" class="custom-select" data-parsley-trigger="submit" required>
                                    <option disabled selected>Month</option>
                                    
                                    <?php 
                                    
                                    foreach ($months as $month) {
                                        if (isset($User->dob)) {
                                            $dob = new DateTime($User->dob, new DateTimeZone('UTC'));
                                            $dob->setTimezone(new DateTimeZone('America/New_York'));
                                        }

                                        echo "<option value=\"{$month}\" " . ((isset($dob) && $month == $dob->format('F')) ? "selected" : "") . ">{$month}</option>";
                                    }

                                    ?>
                                </select>
                            </div>
                        
                            <div class="col-md-4">
                                <select name="day" class="custom-select" data-parsley-trigger="submit" required>
                                    <option disabled selected>Day</option>
                                    
                                    <?php 
                                    
                                    for ($i=1; $i <= 31; $i++) {
                                        if (isset($User->dob)) {
                                            $dob = new DateTime($User->dob, new DateTimeZone('UTC'));
                                            $dob->setTimezone(new DateTimeZone('America/New_York'));
                                        }

                                        echo "<option value=\"{$i}\" " . ((isset($dob) && $i == $dob->format('d')) ? "selected" : "") . ">{$i}</option>";
                                    }
                                    
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <select name="year" class="custom-select" data-parsley-trigger="submit" required>
                                    <option disabled selected>Year</option>
                                    
                                    <?php 
                                    
                                    for ($i = (date('Y') - 18); $i >= (date('Y') - 120); $i--) {
                                        if (isset($User->dob)) {
                                            $dob = new DateTime($User->dob, new DateTimeZone('UTC'));
                                            $dob->setTimezone(new DateTimeZone('America/New_York'));
                                        }

                                        echo "<option value=\"{$i}\" " . ((isset($dob) && $i == $dob->format('Y')) ? "selected" : "") . ">{$i}</option>";
                                    }
                                    
                                    ?>
                                </select>
                            </div>
                        </div>

                        <small class="form-text text-muted">
                            We use this data for analysis and never share it with other users.
                        </small>
                    </div>

                    <div class="form-group">
                        <label>
                            Gender <i class="fa fa-eye-slash" data-toggle="tooltip" data-placement="right" title="Private"></i>
                        </label>

                        <select name="gender" class="custom-select" data-parsley-trigger="submit" required>
                            <option disabled selected <?php if (!empty($User->gender )) { echo 'hidden'; } ?>>Unknown</option>

                            <option value="male" <?php if ($User->gender == 'male') { echo 'selected'; } ?>>
                                Male
                            </option>
                        
                            <option value="female" <?php if ($User->gender == 'female') { echo 'selected'; } ?>>
                                Female
                            </option>
                        
                            <option value="other" <?php if ($User->gender == 'other') { echo 'selected'; } ?>>
                                Other
                            </option>
                        </select>

                        <small class="form-text text-muted">
                            We use this data for analysis and never share it with other users.
                        </small>
                    </div>

                    <label>
                        Where do you live?
                    </label>

                    <div class="form-group">
                        <input type="text" name="address-line-1" class="form-control" placeholder="Street address" value="<?php if (!empty($User->address_line_1)) { echo $User->address_line_1; } ?>" data-parsley-trigger="change" required>
                    </div>

                    <div class="form-group">
                        <input type="text" name="address-line-2" class="form-control" placeholder="Apt, Suite, Bldg. (optional)" value="<?php if (!empty($User->address_line_2)) { echo $User->address_line_2; } ?>" data-parsley-trigger="change">
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <input type="text" name="city" class="form-control" placeholder="City" value="<?php if (!empty($User->city)) { echo $User->city; } ?>" data-parsley-trigger="change" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" placeholder="State" value="<?php if (!empty($User->state)) { echo $User->state; } ?>" data-parsley-pattern="^[A-Z]{2}$" data-parsley-length="[2,2]" data-parsley-length-message="This abbreviation should be exactly 2 characters long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <input type="text" name="zipcode" class="form-control" placeholder="Zip code" value="<?php if (!empty($User->zipcode)) { echo $User->zipcode; } ?>" data-parsley-type="digits" data-parsley-length="[5,5]" data-parsley-length-message="This value should be exactly 5 digits long" data-parsley-trigger="change" required>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label>
                            Bio
                        </label>
                        
                        <textarea type="text" name="bio" class="form-control" rows="4" placeholder="Describe yourself! Food From Friends is built on relationships."><?php if (!empty($User->bio)) { echo $User->bio ; } ?></textarea>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <label>
                        Profile photo
                    </label>
                        
                    <a href="" class="remove-image float-right" <?php if (empty($User->filename)) echo 'style="display: none;"' ?> data-toggle="tooltip" data-placement="left" title="Remove profile photo"><i class="fa fa-trash"></i></a>

                    <div class="image-box slide-over <?php if (!empty($User->filename)) echo 'existing-image'; ?>">
                        <div class="image-container">
                            <?php
                                    
                            if (!empty($User->filename)) {
                                img(ENV . '/profile-photos/' . $User->filename, $User->ext . '?' . time(), 'S3', 'file img-fluid');
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

                    <small id="profilePhotoHelp" class="form-text text-muted <?php if (!empty($User->filename)) echo 'hidden'; ?>">
                        Upload a good photo of your face to make more friends!
                    </small>
                </div>
            </div>
        </form>
    </div>
</main>