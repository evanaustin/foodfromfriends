<?php

class Mail {
    private
        $api_key;
    
    public
        $sendgrid,
        $from,
        $to,
        $from_email,
        $to_email;
    
    public function __construct($send) {
        $this->api_key      = SENDGRID_KEY;
       
        $this->sendgrid     = new \SendGrid($this->api_key);

        $this->from_email   = $send['fromEmail'];
        $this->to_email     = $send['toEmail'];

        $this->from         = new SendGrid\Email($send['fromName'], $send['fromEmail']);
        $this->to           = new SendGrid\Email($send['toName'], $send['toEmail']);
    }

    public function thanks_locavore_signup() {
        $subject = 'Hey, you rock for signing up!';

        $body = 'When we launch our platform in your area, you&#39;ll be among the first to be able to buy food from local growers in your neighborhood. We&#39;ll send you a note soon inviting you to finish the sign up process so you can go wild and do your thing.';
        $content = new SendGrid\Content('text/html', $body);

        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        $mail->setTemplateId('367345ae-cfd1-4685-a21d-c6cc8b3c97c6');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
    
    public function thanks_grower_signup() {
        $subject = 'Hey, you rock for signing up!';

        $body = 'When we launch our platform in your area, you&#39;ll be among the first to be able to sell food to locavores in your neighborhood. We&#39;ll send you a note soon inviting you to finish the sign up process so you can go wild and do your thing.';
        $content = new SendGrid\Content('text/html', $body);

        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        $mail->setTemplateId('84fb9a47-8512-4afc-89ad-cee1d174b5cd');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
    
    public function thanks_signup() {
        $subject = 'Welcome to Food From Friends!';
    
        $body = 'You\'re awesome for joining Food From Friends. Our platform is still in its early stages, but you can upload your food listings and build out your presence as a grower in the meantime. Then when we open up to buyers this Fall you\'ll be ready to go!';
        $content = new SendGrid\Content('text/html', $body);
    
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        $mail->setTemplateId('04ad1ecd-1a15-44a1-a329-72632de50d72');
    
        return $this->sendgrid->client->mail()->send()->post($mail);
    }

    public function thanks_early_access_grower_signup() {
        $subject = 'Welcome to Food From Friends!';

        $body = 'Hey!';
        //  You\'re awesome for joing Food From Friends. As you probably already know, we invited you to sign up early because we know you grow good food and we want it on our platform. You can log in any time from the bottom of our <a href="http://foodfromfriends.co#log-in">splash page</a>, flesh out your profile, and add food listings till your heart\'s content!
        $content = new SendGrid\Content('text/html', $body);

        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        $mail->setTemplateId('a1619840-f431-479c-a3e3-2fefb9a673d3');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }

    public function team_invite_grower_signup($TeamOwner, $GrowerOperation, $referral_keys) {
        $subject = 'You\'ve been invited!';

        $ownername = $TeamOwner->first_name . ' ' . $TeamOwner->last_name;
        $operationname = $GrowerOperation->name;

        // ensure that URL is concatenated correctly
        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . "team-member-invitation?invited_by=" . $ownername . "&operation_name=" . $operationname . "&email=" . $this->to_email . "&operation_key=" . $referral_keys['operation'] . "&personal_key=" . $referral_keys['personal']));

        /*
         * Substitution tag method - preferred but inconsistent for some reason
         *
            $subs = new SendGrid\Personalization();

            $subs->addTo($this->to);

            $subs->addSubstitution('%ownername%', $ownername);
            $subs->addSubstitution('%operationname%', $operationname);
            $subs->addSubstitution('%toemail%', $this->to_email);
            $subs->addSubstitution('%operationkey%', $referral_keys['operation']);
            $subs->addSubstitution('%personalkey%', $referral_keys['personal']);
         
            $subs->addSubstitution('-ownername-', $ownername);
            $subs->addSubstitution('-operationname-', $operationname);
            $body = 'Hey! -ownername- has invited you to join the -operationname- team on Food From Friends! Click the link below to sign up and join the team.';
         */

        // hacky but consistent way
        $body = "
            <p>
                Hey! " . $ownername . " has invited you to join the " . $operationname . " team on Food From Friends! Click the link below to sign up and join the team.
            </p>
            
            <p>
                Operation key: <strong>" . $referral_keys['operation'] . "</strong>
            </p>
          
            <p>
                Personal key: <strong>" . $referral_keys['personal'] . "</strong>
            </p>
            
            <a href=\"" . $link . "\" class=\"button bg-green block\">
                Sign up here
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // $mail->addPersonalization($subs);
        
        $mail->setTemplateId('12810469-4c7f-404f-b21c-3272441b8be9');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
    
    public function team_invite_grower_join($TeamOwner, $GrowerOperation, $referral_keys) {
        $subject = 'You\'ve been invited!';
        
        $ownername = $TeamOwner->first_name . ' ' . $TeamOwner->last_name;
        $operationname = $GrowerOperation->name;
        
        // ensure that URL is concatenated correctly
        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . "log-in?email=" . $this->to_email . "&operation_key=" . $referral_keys['operation'] . "&personal_key=" . $referral_keys['personal']));

        /*
         * Substitution tag method - preferred but inconsistent for some reason
         *
            $subs = new SendGrid\Personalization();

            $subs->addTo($this->to);
            
            $subs->addSubstitution('%toemail%', $this->to_email);
            $subs->addSubstitution('%operationkey%', $referral_keys['operation']);
            $subs->addSubstitution('%personalkey%', $referral_keys['personal']);
        
            $subs->addSubstitution('-ownername-', $ownername);
            $subs->addSubstitution('-operationname-', $operationname);
            $body = 'Hey! -ownername- has invited you to join the -operationname- team on Food From Friends! Click the link below to join the team now or enter the following key codes on your grower operation page.';
         */

        // hacky but consistent way
        $body = "
            <p>
                Hey! " . $ownername . " has invited you to join the " . $operationname . " team on Food From Friends! Click the link below to join. Alternatively, you can enter the following key codes on your grower operation page.
            </p>
            
            <p>
                Operation key: <strong>" . $referral_keys['operation'] . "</strong>
            </p>
        
            <p>
                Personal key: <strong>" . $referral_keys['personal'] . "</strong>
            </p>
            
            <a href=\"" . $link . "\" class=\"button bg-green block\">
                Join the team
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // $mail->addPersonalization($subs);
        
        $mail->setTemplateId('94501cdb-4d38-4294-96cd-a62444b63284');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
}

?>