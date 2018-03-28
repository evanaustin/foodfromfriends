<div id="reset-password-link-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Reset password</h3>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <div class="alerts"></div>

                <form id="reset-password-link">
                    <input type="hidden" name="redirect">

                    <div class="form-group margin-btm-1em">
                        <div class="input-group w-addon">
                            <input type="email" name="email" class="form-control" aria-describedby="email" placeholder="Enter your account's email address" data-parsley-trigger="submit" required>
                            
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Send reset link</button>
                </form>
            </div>

            <div class="modal-footer">
                <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#log-in-modal">Back to login</a>
            </div>
        </div>
    </div>
</div>