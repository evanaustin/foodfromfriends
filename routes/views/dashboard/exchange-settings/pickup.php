<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="main container animated fadeIn">
                <div class="row">
                    <div class="col-md-6">
                        <div class="page-title">
                            Set your pickup preferencs
                        </div>

                        <div class="page-description text-muted small">
                            After purchase, let your customers come to location of your choice and pick up their food at a time that works for you.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="controls">
                            <button type="submit" form="save-pickup" class="btn btn-primary">
                                <i class="pre fa fa-floppy-o"></i>
                                Save changes
                                <i class="post fa fa-gear loading-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <hr>

                <div class="row">
                    <div class="col-md-6">                       
                        <div class="alert"></div>

                        <form id="save-pickup" data-parsley-excluded="[disabled=disabled]">
                            <div id="pickup-setting">
                                <div class="form-group">
                                    <label>Do you want to offer free pickup?</label>
                                    
                                    <div class="radio-box">
                                        <label class="custom-control custom-radio">
                                            <input name="is-offered" type="radio" value="1" class="custom-control-input" <?php if ($details['is_offered'] == 1) { echo 'checked'; } ?>>
                                            
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">
                                                Yes
                                            </span>
                                        </label>

                                        <label class="custom-control custom-radio">
                                            <input name="is-offered" type="radio" value="0" class="custom-control-input" <?php if ($details['is_offered'] == 0) { echo 'checked'; } ?>> 
                                            
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">
                                                No
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="instructions" class="form-group" <?php if (!$details['is_offered']) { echo 'style="display:none;"'; } ?>>
                                <label>
                                    Instructions
                                </label>

                                <textarea name="instructions" class="form-control" rows="4" placeholder="Where can people find their food?" <?php if (!$details['is_offered']) { echo 'disabled'; } else { echo 'required'; } ?>><?php if (!empty($details['instructions'])) { echo $details['instructions']; } ?></textarea>
                            </div>

                            <div id="availability" class="form-group" <?php if (!$details['is_offered']) { echo 'style="display:none;"'; } ?>>
                                <label>
                                    Availability
                                </label>

                                <textarea name="availability" class="form-control" rows="4" placeholder="When should people pick up their food?" <?php if (!$details['is_offered']) { echo 'disabled'; } else { echo 'required'; } ?>><?php if (!empty($details['availability'])) { echo $details['availability']; } ?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->