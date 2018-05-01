<!-- cont main -->
    <div class="container animated fadeIn">
        <section id="messages">
            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        Conversation with <?= $BuyerAccount->name; ?>
                    </div>

                    <div class="page-description text-muted small">
                        <?= 'This is ' . ((isset($messages) && count($messages) > 0) ? 'the entire' : 'a new') . ' chain of conversation between you and this customer.'; ?>
                    </div>
                </div>
            </div>
            
            <hr>

            <div class="alerts"></div>

            <?php if (isset($messages) && $messages != false && count($messages) > 0): ?>

                <?php foreach($messages as $message): ?>

                    <?php

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

                        <?php if ($date_sent): ?>

                            <div class="col-md-12 align-center <?= $margin; ?>">
                                <span class="light-gray bold tiny"><?= $date_sent; ?></span>
                            </div>

                        <?php endif; ?>

                        <?php if ($ThisMessage->sent_by == 'seller'): ?>

                            <div class="col-md-9 offset-md-3">
                                <fable class="message right-message">
                                    <cell class="flexend">
                                        <div class="bubble inline-block align-left muted">
                                            <?= $ThisMessage->body; ?>
                                        </div>
                                    </cell>

                                    <cell class="justify-center flexcenter flexgrow-0 margin-left-1em">
                                    <div class="user-photo no-margin d-none d-md-block" style="background-image: url('<?= 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$Grower->filename}.{$Grower->ext}"; ?>');"></div>
                                    </cell>
                                </fable>
                            </div>

                        <?php elseif ($ThisMessage->sent_by == 'buyer'): ?>
                            
                            <div class="col-md-9">
                                <fable class="message left-message">
                                    <cell class="justify-center flexcenter flexgrow-0 margin-right-1em">
                                        <a href="<?= PUBLIC_ROOT . $Grower->link; ?>">
                                            <div class="user-photo no-margin d-none d-md-block" style="background-image: url('<?= (!empty($BuyerAccount->Image->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/{$BuyerAccount->Image->path}/{$BuyerAccount->Image->filename}.{$BuyerAccount->Image->ext}" : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>');"></div>
                                        </a>
                                    </cell>

                                    <cell>
                                        <div class="bubble inline-block align-left brand-bg">
                                            <?= $ThisMessage->body; ?>
                                        </div>
                                    </cell>
                                </fable>
                            </div>

                            <?php $ThisMessage->update([
                                'read_on' => \Time::now()
                            ]); ?>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="block">
                There are no messages between you and this customer yet. Send the first one using the text box below.
                </div>

            <?php endif; ?>

        </section>
            
        <section id="new-message">
            <form id="send-message">
                <input type="hidden" name="filename" value="<?= $User->GrowerOperation->filename;?>">
                <input type="hidden" name="fileext" value="<?= $User->GrowerOperation->ext;?>">

                <input type="hidden" name="buyer-account-id" value="<?= $BuyerAccount->id;?>">
                <input type="hidden" name="grower-operation-id" value="<?= $User->GrowerOperation->id;?>">
                <input type="hidden" name="sent-by" value="seller">

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