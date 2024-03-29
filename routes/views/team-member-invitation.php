<div class="main container animated fadeIn">
    <div class="row">
        <div class="col-md-6 offset-3">
            <div class="logo">    
                <?php svg('logos/thin_alt'); ?>
            </div>

            <p>
                <?= 'Welcome! ' . $invited_by . ' has requested your assistance in managing ' . $operation_name . ' on Food From Friends. Fill out the form below to sign up and join the team.'; ?>
            </p>

            <div class="alerts"></div>

            <form id="sign-up">
                <input type="hidden" name="operation-key" value="<?= $operation_key; ?>">
                <input type="hidden" name="personal-key" value="<?= $personal_key; ?>">

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
                        <input type="email" name="email" value="<?= $email; ?>" class="form-control" aria-describedby="email" placeholder="Email address" data-parsley-trigger="submit" required>

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