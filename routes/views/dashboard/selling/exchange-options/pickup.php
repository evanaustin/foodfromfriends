<!-- cont main -->
    <div class="container animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="page-title">
                    Set your pickup preferencs
                </div>

                <div class="page-description text-muted small">
                    After purchase, let your customers come to a location of your choice and pick up their food at a time that works for you.
                </div>
            </div>

            <div class="col-md-6">
                <div class="controls">
                    <button type="submit" form="save-pickup" class="btn btn-success">
                        <i class="pre fa fa-floppy-o"></i>
                        Save changes
                        <i class="post fa fa-gear loading-icon"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <hr>

        <div class="alerts"></div>

        <div class="row">
            <div class="col-md-6">                       
                <form id="save-pickup" data-parsley-excluded="[disabled=disabled]">
                    <div id="pickup-setting">
                        <div class="form-group">
                            <label>Do you want to offer free pickup?</label>
                            
                            <div class="radio-box">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="yes-offered" name="is-offered" class="custom-control-input" value="1" <?php if ($details['is_offered'] == 1) { echo 'checked'; } ?>>
                                    <label class="custom-control-label" for="yes-offered">Yes</label>
                                </div>
                                
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no-offered" name="is-offered" class="custom-control-input" value="0" <?php if ($details['is_offered'] == 0) { echo 'checked'; } ?>>
                                    <label class="custom-control-label" for="no-offered">No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="instructions" class="form-group" <?php if (!$details['is_offered']) { echo 'style="display:none;"'; } ?>>
                        <label>
                            Instructions
                        </label>

                        <textarea name="instructions" class="form-control" rows="4" placeholder="Where can people find their food?" <?= (!$details['is_offered']) ? 'disabled' : 'required'; ?>><?php if (!empty($details['instructions'])) { echo $details['instructions']; } ?></textarea>
                    </div>

                    <div id="time" class="form-group" <?php if (!$details['is_offered']) { echo 'style="display:none;"'; } ?>>
                        <label>
                            Availability
                        </label>

                        <textarea name="time" class="form-control" rows="4" placeholder="When should people pick up their food?" <?= (!$details['is_offered']) ? 'disabled' : 'required'; ?>><?php if (!empty($details['time'])) { echo $details['time']; } ?></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>