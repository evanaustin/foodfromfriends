<!-- cont main -->
    <div class="container animated fadeIn">
        <?php
        
        if (isset($messages) && ($messages != false) && count($messages) > 0) {

            ?>

            <div class="alerts"></div>

            <?php 
            
            foreach($messages as $message) {
                $ThisMessage = new Message([
                    'DB' => $DB,
                    'id' => $message['id']
                ]);

                $sent_on = new DateTime($ThisMessage->sent_on);
                $date_sent = $sent_on->format('g:i A n/j/y'); 
                
                $snippet = truncate($ThisMessage->body, 85);

                $Grower = new GrowerOperation([
                    'DB' => $DB,
                    'id' => $ThisMessage->grower_operation_id
                ],[
                    'details' => true
                ]);
                
                ?>

                <fable class="bubble">
                    <cell class="align-center">
                        <div class="user-block">
                            <a href="<?php echo PUBLIC_ROOT . 'grower?id=' . $Grower->id; ?>">
                                <div class="user-photo" style="background-image: url('<?php echo 'https://s3.amazonaws.com/foodfromfriends/' . ENV . $Grower->details['path'] . '.' . $Grower->details['ext']; ?>');"></div>
                            </a>
                                            
                            <div class="user-content">
                                <h5 class="bold margin-btm-25em">
                                    <a href="<?php echo PUBLIC_ROOT . 'grower?id=' . $Grower->id; ?>">
                                        <?php echo $Grower->details['name']; ?>
                                    </a>
                                </h5>

                                <small>
                                    <?php echo $Grower->details['city'] . ', ' . $Grower->details['state']; ?>
                                </small>
                            </div>
                        </div>    
                    </cell>

                    <cell class="justify-center align-center bold muted">
                        <?php echo $date_sent; ?>
                    </cell>
                    
                    <cell class="flexgrow-3 muted">
                    <?php echo '<i class="fa fa-' . (($ThisMessage->sent_by == 'grower') ? (!isset($ThisMessage->read_on) ? 'circle info jackInTheBox animated' : 'reply muted') : 'share muted') . '"></i> &nbsp;'; ?>

                        <a href="<?php echo PUBLIC_ROOT . 'dashboard/messages/inbox/buying/thread?grower=' . $Grower->id; ?>">
                            <?php echo $snippet; ?>
                        </a>
                    </cell>
                </fable>
        
                <?php
        
            }
                    
        } else {

            ?>

            <div class="block strong">
                Your <thick class="brand">buying</thick> inbox is empty!
            </div>

            <?php

        }

        ?>
    </div>
</main>