<!-- cont main -->
    <div class="container animated fadeIn">
        <section id="messages">
            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Conversation with <a href="<?php echo PUBLIC_ROOT . $Grower->link; ?>"><?php echo $Grower->name; ?></a>
                    </div>

                    <div class="page-description text-muted small">
                        <?php echo 'This is ' . ((isset($messages) && count($messages) > 0) ? 'the entire' : 'a new') . ' chain of conversation between you and this grower.'; ?>
                    </div>
                </div>
            </div>
            
            <hr>

            <div class="alerts"></div>

            <?php

            if (isset($messages) && ($messages != false) && count($messages) > 0) {

                foreach($messages as $message) {
                    $ThisMessage = new Message([
                        'DB' => $DB,
                        'id' => $message['id']
                    ]);

                    $sent_on = new DateTime($ThisMessage->sent_on, new DateTimeZone('UTC'));
                    $sent_on->setTimezone(new DateTimeZone($User->timezone));

                    if (!isset($prev_sent_on)) {
                        $prev_sent_on = $sent_on;
                        $date_sent = $sent_on->format('n/j/y\, g:i A');

                        $margin = '';
                    } else {
                        $interval = $prev_sent_on->diff($sent_on);
                        $h = $interval->format('%H');
                        $date_sent = ($h <= 1) ? false : $sent_on->format('n/j/y\, g:i A');

                        $margin = 'margin-top-1em';

                        $prev_sent_on = $sent_on;
                    }
                    
                    ?>

                    <div class="row">

                        <?php 
                        
                        if ($date_sent) {
                            
                            ?>

                            <div class="col-md-12 align-center <?php echo $margin; ?>">
                                <span class="light-gray bold tiny"><?php echo $date_sent; ?></span>
                            </div>

                            <?php 
                    
                        }

                        if ($ThisMessage->sent_by == 'user') {
                            
                            ?>

                            <div class="col-md-9 offset-md-3">
                                <fable class="message right-message">
                                    <cell class="flexend">
                                        <div class="bubble inline-block align-left muted">
                                            <?php echo $ThisMessage->body; ?>
                                        </div>
                                    </cell>

                                    <cell class="justify-center flexcenter flexgrow-0 margin-left-1em">
                                        <div class="user-photo no-margin d-none d-md-block" style="background-image: url('<?php echo (!empty($User->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $User->filename . '.' . $User->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>');"></div>
                                    </cell>
                                </fable>
                            </div>

                            <?php

                        } else if ($ThisMessage->sent_by == 'grower') {
                            
                            ?>

                            <div class="col-md-9">
                                <fable class="message left-message">
                                    <cell class="justify-center flexcenter flexgrow-0 margin-right-1em">
                                        <a href="<?php echo PUBLIC_ROOT . $Grower->link; ?>">
                                            <div class="user-photo no-margin d-none d-md-block" style="background-image: url('<?php echo 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$Grower->filename}.{$Grower->ext}"; ?>');"></div>
                                        </a>
                                    </cell>

                                    <cell>
                                        <div class="bubble inline-block align-left brand-bg">
                                            <?php echo $ThisMessage->body; ?>
                                        </div>
                                    </cell>
                                </fable>
                            </div>

                            <?php

                            // mark grower's messages as read by buyer
                            $ThisMessage->update([
                                'read_on' => \Time::now()
                            ]);

                        }

                        ?>
            
                    </div>

                    <?php

                }

            }

            ?>
        </section>
            
        <section id="new-message">
            <form id="send-message">
                <input type="hidden" name="filename" value="<?php echo $User->filename;?>">
                <input type="hidden" name="fileext" value="<?php echo $User->ext;?>">

                <input type="hidden" name="user-id" value="<?php echo $User->id;?>">
                <input type="hidden" name="grower-operation-id" value="<?php echo $Grower->id;?>">
                <input type="hidden" name="sent-by" value="user">

                <div class="input-group w-addon">
                    <textarea name="message" placeholder="Enter message&hellip;" rows="1" autofocus></textarea>

                    <button class="input-group-addon btn btn-success">
                        <i class="fa fa-arrow-circle-up"></i>
                    </button>
                </div>
            </form>
        </section>
    </div>
</main>