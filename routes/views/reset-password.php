<div class="main container animated fadeIn">
    <div class="row">
        <div class="col-md-6 offset-3">
            <div class="logo">    
                <?php svg('logos/thin_alt'); ?>
            </div>

            <div class="alerts"></div>

            <?php
            
            if (isset($authentic_token) && $authentic_token === true) {
            
                ?>

                <form id="reset-password">
                    <input type="hidden" name="token-email" value="<?= $JWT->email; ?>">
                    
                    <div class="form-group">
                        <label>
                            Email
                        </label>

                        <div class="input-group w-addon">
                        <input type="email" name="email" class="form-control" aria-describedby="email" placeholder="Enter the email address associated with your account" data-parsley-trigger="submit" required>
                            
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            New password
                        </label>

                        <div class="input-group w-addon">
                            <input type="password" name="password" class="form-control" aria-describedby="password" placeholder="Enter a new password" data-parsley-trigger="submit" data-parsley-minlength="8" required>
                            
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            Confirm new password
                        </label>

                        <div class="input-group w-addon">
                            <input type="password" name="confirm-password" class="form-control" aria-describedby="password" placeholder="Confirm your new password" data-parsley-trigger="submit" data-parsley-minlength="8" required>
                            
                            <span class="input-group-addon">
                                <i class="fa fa-lock"></i>
                            </span>
                        </div>
                    </div>
                
                    <button type="submit" class="btn btn-primary btn-block">Reset password</button>
                </form>

                <?php

            } else {
                echo '<span class="text-muted">The reset password link you are trying to use is either no longer valid or never was valid</span>';
            }

            ?>

        </div>
    </div>
</div>