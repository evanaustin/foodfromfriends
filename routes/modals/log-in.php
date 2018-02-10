<div id="log-in-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Log in</h3>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <div class="alerts"></div>

                <form id="log-in">
                    <input type="hidden" name="redirect">

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
                                <i class="fa fa-key"></i>
                            </span>
                        </div>
                    </div>

                    <!-- <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember_me" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Remember me</span>
                        </label>
                    </div> -->
                    
                    <button type="submit" class="btn btn-primary btn-block">Log in</button>
                </form>

                <!-- <div id="forgot-password">
                    <a href="">Forgot password?</a>
                </div> -->
            </div>

            <div class="modal-footer <?php if ($Routing->landing == 'splash') { echo 'hidden'; } ?>">
                Don't have an account? <a href="" data-dismiss="modal" data-toggle="modal" data-target="#sign-up-modal">Sign up</a>
            </div>
        </div>
    </div>
</div>