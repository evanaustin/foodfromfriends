<div id="sign-up-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>

                <h3 class="modal-title">Join the community</h3>
            </div>
            
            <div class="modal-body">
                <div class="alert"></div>

                <form id="sign-up">
                    <div class="form-group">
                         <div class="input-group"> 
                            <input type="text" name="first_name" class="form-control" aria-describedby="first name" placeholder="First name" data-parsley-trigger="submit" required>

                             <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                         </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="last_name" class="form-control" aria-describedby="last name" placeholder="Last name" data-parsley-trigger="submit" required>

                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <input type="email" name="email" class="form-control" aria-describedby="email" placeholder="Email address" data-parsley-trigger="submit" required>

                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" aria-describedby="password" placeholder="Password" data-parsley-trigger="submit" data-parsley-minlength="8" required>
                            
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                        </div>
                    </div>

                    <strong>Birthday</strong>

                    <small class="form-text text-muted">To sign up, you must be 18 or older. Other people won’t see your birthday.</small>
                        
                    <div class="select-row">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="month" class="custom-select" data-parsley-trigger="submit" required>
                                        <option selected disabled>Month</option>
                                        
                                        <?php 
                                            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                            
                                            foreach ($months as $month) {
                                                echo "<option val='{$month}'>{$month}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="day" class="custom-select" data-parsley-trigger="submit" required>
                                        <option selected disabled>Day</option>
                                        
                                        <?php 
                                            for ($i=1; $i <= 31; $i++) {
                                                echo "<option value='{$i}'>{$i}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="year" class="custom-select" data-parsley-trigger="submit" required>
                                        <option selected disabled>Year</option>
                                        
                                        <?php 
                                            for ($i = (date('Y') - 18); $i >= (date('Y') - 120); $i--) {
                                                echo "<option value='{$i}'>{$i}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <small>
                        By clicking Sign up, I agree to Food From Friends’s Terms of Service, Payments Terms of Service, Privacy Policy, and Nondiscrimination Policy.
                    </small>

                    <button type="submit" class="btn btn-primary btn-block">Sign up</button>
                </form>
            </div>

            <div class="modal-footer">
                <h6>Already have an account? <a href="" data-dismiss="modal" data-toggle="modal" data-target="#log-in-modal">Log in</a></h6>
            </div>
        </div>
    </div>
</div>