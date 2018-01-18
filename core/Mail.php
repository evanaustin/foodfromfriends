<?php

use \Firebase\JWT\JWT;

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
    
    // @todo show items
    public function new_order_notification($Member, $GrowerOperation, $OrderGrower, $Buyer) {
        $subject = "New order - {$GrowerOperation->details['name']}";
        
        $token = [
            'user_id' => $Member->id,
            'grower_operation_id' => $GrowerOperation->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/grower/orders/new/view?id=' . $OrderGrower->id . '&token=' . $jwt));

        $body = "
            <h1>
                You've received a new order!
            </h1>

            <hr>

            <p>
                Good news, {$Member->first_name}! {$Buyer->name} has requested to place an order from " . (($GrowerOperation->type == 'none') ? "you" : "<strong>{$GrowerOperation->details['name']}</strong>") . ".
            </p>

            <p>
                You have <strong>24 hours</strong> to either confirm or reject the order before it expires.
            </p>
            
            <a href=\"{$link}\" class=\"button bg-green block\">
                View order
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }

    /* public function order_expiration_warning() {} */
    
    // @todo show order summary
    public function confirmed_order_notification($Buyer, $OrderGrower, $GrowerOperation) {
        $subject = "Confirmed order - {$GrowerOperation->details['name']}";
        
        $token = [
            'user_id' => $Buyer->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        // @todo scroll to order
        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/account/orders-placed/overview?token=' . $jwt));

        $body = "
            <h1>
                Your order has been confirmed!
            </h1>

            <hr>

            <p>
                Good news, {$Buyer->first_name}! {$GrowerOperation->details['name']} has confirmed your order.
            </p>
            
            <p>
                This order is now ready for fulfillment. If you selected <strong>Pickup</strong> or <strong>Meetup</strong> as your exchange option from {$GrowerOperation->details['name']}, remember to go receive your order.
            </p>
            
            <a href=\"" . $link . "\" class=\"button bg-green block\">
                View order
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
    
    // @todo show order summary
    public function rejected_order_notification($Buyer, $OrderGrower, $GrowerOperation) {
        $subject = "Rejected order - {$GrowerOperation->details['name']}";
        
        $token = [
            'user_id' => $Buyer->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'map?token=' . $jwt));

        $body = "
            <h1>
                Your order has been rejected
            </h1>

            <hr>

            <p>
                Hi, {$Buyer->first_name}. Sorry to say that {$GrowerOperation->details['name']} has rejected your order. Don't take it personally! This usually means that the seller couldn't fulfill the item for some reason.
            </p>
            
            <p>
                You will not be charged for your order to {$GrowerOperation->details['name']}.
            </p>
            
            <a href=\"" . $link . "\" class=\"button bg-green block\">
                Find other sellers
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
    
    // @todo show order summary
    public function expired_order_notification($Buyer, $OrderGrower, $GrowerOperation) {
        $subject = "Expired order - {$GrowerOperation->details['name']}";
        
        $token = [
            'user_id' => $Buyer->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'map?token=' . $jwt));

        $body = "
            <h1>
                Your order has expired
            </h1>

            <hr>

            <p>
                Hi, {$Buyer->first_name}. Sorry to say that {$GrowerOperation->details['name']} has let your order expire. You will not be charged for your order to {$GrowerOperation->details['name']}.
            </p>
            
            <a href=\"" . $link . "\" class=\"button bg-green block\">
                Find other sellers
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }

    // ! @todo link to a new 'failed' order view
    // @todo show order summary
    public function buyer_cancelled_order_notification($Member, $GrowerOperation, $OrderGrower, $Buyer) {
        /* $subject = "Cancelled order - {$GrowerOperation->details['name']}";
        
        $token = [
            'user_id' => $Member->id,
            'grower_operation_id' => $GrowerOperation->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        // $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/grower/orders/new/view?id=' . $OrderGrower->id . '&token=' . $jwt));

        $body = "
            <h1>
                Order cancellation
            </h1>

            <hr>

            <p>
                Hi, {$Member->first_name}. Sorry to say{$Buyer->name} has cancelled their order from " . (($GrowerOperation->type == 'none') ? "you" : "<strong>{$GrowerOperation->details['name']}</strong>") . ".
            </p>

            <p>
                You are no longer responsible for fulfilling this order.
            </p>
            
            <a href=\"{$link}\" class=\"button bg-green block\">
                View order
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail); */
    }
    
    // @todo show order summary
    public function seller_cancelled_order_notification($Buyer, $OrderGrower, $GrowerOperation) {
        $subject = "Cancelled order - {$GrowerOperation->details['name']}";
        
        $token = [
            'user_id' => $Buyer->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'map?token=' . $jwt));

        $body = "
            <h1>
                Your order has been cancelled
            </h1>

            <hr>

            <p>
                Hi, {$Buyer->first_name}. Sorry to say that {$GrowerOperation->details['name']} has cancelled your order. You will not be charged for this order to {$GrowerOperation->details['name']}.
            </p>
            
            <a href=\"" . $link . "\" class=\"button bg-green block\">
                Find other sellers
            </a>
        ";

        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
    
    /* public function order_fulfillment_reminder() {} */

    // @todo show order summary
    public function fulfilled_order_notification($Buyer, $OrderGrower, $GrowerOperation) {
        $subject = "Fulfilled order - {$GrowerOperation->details['name']}";
        
        $route = (ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/account/orders-placed/review?id=' . $OrderGrower->id;

        $token = [
            'user_id' => $Buyer->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        $link = urldecode(urlencode($route . '?token=' . $jwt));

        $report_link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/account/orders-placed/overview?token=' . $jwt));

        $body = "
            <h1>
                Your order has been fulfilled!
            </h1>

            <hr>

            <p>
                Hi, {$Buyer->first_name}. {$GrowerOperation->details['name']} has marked your order as fulfilled. If you believe this was done in error, please report an issue through your <a href=\"{$report_link}\">order overview</a>.
            </p>

            <p>
                Otherwise, you have three days to review {$GrowerOperation->details['name']}. Be kind and be honest!
            </p>
            
            <a href=\"" . $link . "\" class=\"button bg-green block\">
                Review {$GrowerOperation->details['name']}
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
    
    // ! @todo this needs to link to a 'completed' order view with review
    // @todo show score(s) & review(s)
    public function reviewed_order_notification($Member, $GrowerOperation, $OrderGrower, $Buyer) {
        $subject = "New review - {$GrowerOperation->details['name']}";
        
        $token = [
            'user_id' => $Member->id,
            'grower_operation_id' => $GrowerOperation->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/grower/orders/completed/view?id=' . $OrderGrower->id . '&token=' . $jwt));

        $body = "
            <h1>
                You got a new review!
            </h1>

            <hr>

            <p>
                Hi {$Member->first_name}! {$Buyer->name} has left " . (($GrowerOperation->type == 'none') ? "you" : "<strong>{$GrowerOperation->details['name']}</strong>") . " a new review.
            </p>

            <p>
                This order is now cleared and marked for payout.
            </p>
            
            <a href=\"{$link}\" class=\"button bg-green block\">
                Read review
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
    
    public function reported_order_notification($Member, $GrowerOperation, $OrderGrower, $Buyer) {
        //
    }
    
    /* public function payout_notification() {} */

}

?>