<div id="log-in-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>

                <h3 class="modal-title">Welcome back</h3>
            </div>
            
            <div class="modal-body">
                <form id="log-in" class="parsley" data-parsley-validate>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="email" class="form-control" aria-describedby="email" placeholder="Email address" data-parsley-trigger="submit" required>

                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-group">
                            <input type="password" class="form-control" aria-describedby="password" placeholder="Password" data-parsley-trigger="submit" required>
                            
                            <span class="input-group-addon">
                                <i class="fa fa-key"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember_me" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Remember me</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Log in</button>
                </form>

                <div id="forgot-password">
                    <a href="">Forgot password?</a>
                </div>
            </div>

            <div class="modal-footer">
                <h6>Don't have an account? <a href="" data-dismiss="modal" data-toggle="modal" data-target="#sign-up-modal">Sign up</a></h6>
            </div>
        </div>
    </div>
</div>