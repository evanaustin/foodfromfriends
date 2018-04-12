<!-- cont main -->
    <div class="container animated fadeIn">
        <?php
        
        if (isset($messages)) {
            
            if ($messages != false && count($messages) > 0) {

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

                    $Customer = new User([
                        'DB' => $DB,
                        'id' => $ThisMessage->user_id
                    ]);
                    
                    ?>

                    <fable class="bubble">
                        <cell class="align-center">
                            <div class="user-block">
                                <div class="user-photo" style="background-image: url('<?= 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $Customer->filename . '.' . $Customer->ext; ?>');"></div>
                                                
                                <div class="user-content">
                                    <h5 class="bold margin-btm-25em">
                                        <?= $Customer->first_name; ?>
                                    </h5>

                                    <small>
                                        <?= $Customer->city . ', ' . $Customer->state; ?>
                                    </small>
                                </div>
                            </div>    
                        </cell>

                        <cell class="justify-center align-center bold muted">
                            <?= $date_sent; ?>
                        </cell>
                        
                        <cell class="flexgrow-3 muted d-justify-center">
                            <?= '<i class="fa fa-' . (($ThisMessage->sent_by == 'user') ? (!isset($ThisMessage->read_on) ? 'circle info jackInTheBox animated' : 'reply muted') : 'share muted') . '"></i> &nbsp;'; ?>

                            <a href="<?= PUBLIC_ROOT . 'dashboard/messages/inbox/selling/thread?' . (isset($grower_operation_id) ? 'grower=' . $grower_operation_id . '&' : '') . 'user=' . $Customer->id; ?>">
                                <?= $snippet; ?>
                            </a>
                        </cell>
                    </fable>
            
                    <?php
            
                }
                        
            } else {

                ?>

                <div class="block strong">
                    <?= ($User->GrowerOperation->type != 'individual' ? $User->GrowerOperation->name . '\'s' : 'Your'); ?> <thick class="brand">selling</thick> inbox is empty!
                </div>

                <?php

            }
        
        } else {
            
            ?>

            <div class="block strong">
                Oops, looks like you found your way here by mistake &hellip; nothing to see here!
            </div>

            <?php

        }

        ?>
    </div>
</main>