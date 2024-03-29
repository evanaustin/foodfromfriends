<div id="suggest-item-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Suggest a new item type</h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <div class="alerts"></div>

                <form id="suggest-item-form">
                    <div class="form-group">
                        <label>
                            Type
                        </label>

                        <input type="text" name="type" class="form-control" placeholder="What kind of item you would like us to add?" data-parsley-trigger="change" required>
                    </div>

                    <div class="form-group">
                        <label>
                            Comments (optional)
                        </label>

                        <textarea name="comments" class="form-control" placeholder="Anything else we should know about this item type?"></textarea>
                    </div>

                    <button type="submit" class="btn btn-block btn-primary">
                        Submit <i class="post fa fa-gear loading-icon suggest-item-submit"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>