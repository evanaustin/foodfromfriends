<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="container">
                <h4 class="title">Edit profile:</h4>

                <hr>

                <div class="alert"></div>

                <form id="edit-profile" data-parsley-validate>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Required information</label>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group"> 
                                    <input type="text" name="first_name" class="form-control" aria-describedby="first name" placeholder="First name" value="<?php echo (!empty($User->first_name) ? $User->first_name : '' );?>"  data-parsley-trigger="submit" required>

                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="last_name" class="form-control" aria-describedby="last name" placeholder="Last name" value="<?php echo (!empty($User->last_name) ? $User->last_name : '' );?>" data-parsley-trigger="submit" required>

                                    <span class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="email" name="email" class="form-control" aria-describedby="email" placeholder="Email address" value="<?php echo (!empty($User->email) ? $User->email : '' );?>" data-parsley-trigger="submit" required>

                                    <span class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="phone" name="phone" class="form-control" aria-describedby="phone" placeholder="Phone-number" value="<?php echo (!empty($User->phone) ? $User->phone : '' );?>"data-parsley-trigger="submit" required>

                                    <span class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>We won’t share your private email address with other Food From Friends users. <a>Learn more.</a></p>
                        </div>
                        <div class="col-md-6">
                           <p>This is only shared once you have a confirmed food listing with another Food From Friends user. This is how we can all get in touch.</p>
                        </div>
                    </div>

                    <label>Address</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="address-line-1" class="form-control" aria-describedby="address-line-1" placeholder="Address" value="<?php echo (!empty($User->address_line_1) ? $User->address_line_1 : '' );?>" data-parsley-trigger="submit" required >
                            </div>    
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="address-line-2" class="form-control " aria-describedby="address-line-2" placeholder="Apt No." value="<?php echo (!empty($User->address_line_2) ? $User->address_line_2 : '' );?>"  data-parsley-trigger="submit" required >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="city" class="form-control " aria-describedby="city" placeholder="City" value="<?php echo (!empty($User->city) ? $User->city : '' );?>" data-parsley-trigger="submit" required >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="state" class="form-control" aria-describedby="state" placeholder="State" value="<?php echo (!empty($User->state) ? $User->state : '' );?>"  data-parsley-trigger="submit" required >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="zipcode" class="form-control" aria-describedby="zip code" placeholder="Zip code" value="<?php echo (!empty($User->zipcode) ? $User->zipcode : '' );?>"   data-parsley-trigger="submit" required >
                            </div>
                        </div>
                    </div> 
                     <p>This information is only shared when an food order is placed and is used to make sure that food dilveries and pickups go smoothly.  </p>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Birthday</label>
                        </div>
                        <div class="col-md-6">
                            <label>I am</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="month" class="custom-select" data-parsley-trigger="submit" required>
                                    <option selected >Month</option>
                                    
                                    <?php 
                                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                        
                                        foreach ($months as $month) {
                                            echo "<option val='{$month}'>{$month}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="day" class="custom-select" data-parsley-trigger="submit" required>
                                    <option selected >Day</option>
                                    
                                    <?php 
                                        for ($i=1; $i <= 31; $i++) {
                                            echo "<option value='{$i}'>{$i}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="year" class="custom-select" data-parsley-trigger="submit" required>
                                    <option selected >Year</option>
                                    
                                    <?php 
                                        for ($i = (date('Y') - 18); $i >= (date('Y') - 120); $i--) {
                                            echo "<option value='{$i}'>{$i}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="gender" class="custom-select" data-parsley-trigger="submit" required>
                                    <option selected disabled >gender</option>
                                    <option value="female" <?php if($User->gender == 'female'){echo "selected";} ?>>Female</option>
                                    <option value="male" <?php if($User->gender == 'male'){echo "selected";} ?>>Male</option>
                                    <option value="other" <?php if($User->gender == 'other'){echo "selected";} ?>>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>The magical day you were dropped from the sky by a stork. We use this data for analysis and never share it with other users.</p>
                        </div>
                        <div class="col-md-6">
                           <p>We use this data for analysis and never share it with other users.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Bio</label>
                        <textarea type="text" name="bio" class="form-control" rows="4" placeholder=""><?php echo (!empty($User->bio) ? $User->bio : '' );?> </textarea>
                    </div>
                   <p> Food From Friends is built on relationships. Help other people get to know you.</p>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        Edit profile
                    </button>
                </form>
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->