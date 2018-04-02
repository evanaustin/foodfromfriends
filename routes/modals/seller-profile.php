<div id="img-zoom-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <!-- <span id="zoom-title"></span> -->
                    <?php echo $GrowerOperation->name; ?>
                </h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <!-- <div id="zoom-src"></span> -->
                <?php
                
                img(ENV . '/grower-operation-images/' . $GrowerOperation->filename, $GrowerOperation->ext, [
                    'server'    => 'S3',
                    'class'     => 'img-fluid rounded drop-shadow'
                ]);
                
                ?>
            </div>
        </div>
    </div>
</div>