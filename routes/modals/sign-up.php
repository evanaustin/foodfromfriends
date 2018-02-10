<div id="sign-up-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Sign up</h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <div class="alerts"></div>

                <form id="sign-up">
                    <input type="hidden" name="redirect">

                    <div class="form-group">
                         <div class="input-group w-addon"> 
                            <input type="text" name="first-name" class="form-control" aria-describedby="first name" placeholder="First name" data-parsley-trigger="submit" required>

                             <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                         </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-group w-addon">
                            <input type="text" name="last-name" class="form-control" aria-describedby="last name" placeholder="Last name" data-parsley-trigger="submit" required>

                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group w-addon">
                            <input type="email" name="email" class="form-control" aria-describedby="email" placeholder="Email address" data-parsley-trigger="submit" required>

                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-group w-addon">
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
                        By clicking <strong>Sign up</strong>, you agree to the Food From Friends <a href="<?php echo PUBLIC_ROOT . 'info/terms-of-service'; ?>" target="_blank">Terms of Service</a> and <a href="<?php echo PUBLIC_ROOT . 'info/privacy-policy'; ?>" target="_blank">Privacy Policy</a>.
                    </small>

                    <button type="submit" class="btn btn-primary btn-block">Sign up</button>
                </form>
            </div>

            <div class="modal-footer">
                Already have an account? <a href="" data-dismiss="modal" data-toggle="modal" data-target="#log-in-modal">Log in</a>
            </div>
        </div>
    </div>
</div>