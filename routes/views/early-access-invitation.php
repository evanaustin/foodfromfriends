<div class="main container animated fadeIn">
    <div class="row">
        <div class="col-md-6 offset-3">
            <div class="logo">    
                <?php svg('logos/thin_alt'); ?>
            </div>

            <p>Lucky you! You've been specially chosen for early access to the Food From Friends <small><strong>BETA</strong></small>. Register here to upload all the different food you produce and be first in line to sell when we open the flood gates to locavores in your area.</p>

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

                <small class="form-text">To sign up, you must be 18 or older. Other people won’t see your birthday.</small>
                    
                <div class="select-row">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="month" class="custom-select" data-parsley-trigger="submit" required>
                                    <option selected disabled>Month</option>
                                    
                                    <?php 
                                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                        
                                        foreach ($months as $month) {
                                            echo "<option value='{$month}'>{$month}</option>";
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

                <small class="form-text">
                    By clicking 'Sign up', you agree to Food From Friends’ <a href="#">Terms of Service</a>.
                </small>

                <button type="submit" class="btn btn-primary btn-block">Sign up</button>
            </form>
        </div>
    </div>
</div>