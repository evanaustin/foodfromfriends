<div class="main container animated fadeIn">
    <div class="row">
        <div class="col-md-6 offset-3">
            <div class="logo">    
                <?php svg('logos/thin_alt'); ?>
            </div>

            <div class="alert"></div>

            <form id="log-in">
                <input type="hidden" name="operation-key" value="<?php echo $operation_key; ?>">
                <input type="hidden" name="personal-key" value="<?php echo $personal_key; ?>">

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

                <button type="submit" class="btn btn-primary btn-block">Log in</button>
            </form>
        </div>
    </div>
</div>