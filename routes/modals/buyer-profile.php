<div id="img-zoom-modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <!-- <span id="zoom-title"></span> -->
                    <?= $ThisUser->name; ?>
                </h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> 
                </button>
            </div>
            
            <div class="modal-body">
                <!-- <div id="zoom-src"></span> -->
                <?php
                
                img(ENV . '/profile-photos/' . $ThisUser->filename, $ThisUser->ext, [
                    'server'    => 'S3',
                    'class'     => 'img-fluid rounded drop-shadow'
                ]);
                
                ?>
            </div>
        </div>
    </div>
</div>