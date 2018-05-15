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

                $sent_on = new DateTime($ThisMessage->sent_on, new DateTimeZone('UTC'));
                $sent_on->setTimezone(new DateTimeZone($User->timezone));
                $date_sent = $sent_on->format('g:i A n/j/y'); 
                
                $snippet = truncate($ThisMessage->body, 75);

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
                            <a href="<?= PUBLIC_ROOT . $Grower->link; ?>">
                                <div class="user-photo" style="background-image: url('<?= 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$Grower->filename}.{$Grower->ext}"; ?>');"></div>
                            </a>
                                            
                            <div class="user-content">
                                <h5 class="bold margin-btm-25em">
                                    <a href="<?= PUBLIC_ROOT . $Grower->link; ?>">
                                        <?= $Grower->name; ?>
                                    </a>
                                </h5>

                                <small>
                                    <?= "{$Grower->city}, {$Grower->state}"; ?>
                                </small>
                            </div>
                        </div>    
                    </cell>

                    <cell class="justify-center align-center bold muted">
                        <?= $date_sent; ?>
                    </cell>
                    
                    <cell class="flexgrow-3 muted d-justify-center">
                        <?= '<i class="fa fa-' . (($ThisMessage->sent_by == 'grower') ? (!isset($ThisMessage->read_on) ? 'circle info jackInTheBox animated' : 'reply muted') : 'share muted') . '"></i> &nbsp;'; ?>

                        <a href="<?= PUBLIC_ROOT . 'dashboard/buying/messages/thread?seller=' . $Grower->id; ?>">
                            <?= $snippet; ?>
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