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
    
        $body = 'Your local food system just got a whole lot more easy and fun, and our platform was just made a whole lot brighter by your presence.';
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
    
    public function user_new_message_notification($User, $GrowerOperation, $message) {
        $subject = "New message from {$GrowerOperation->name}";
        
        $route = (ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/messages/inbox/buying/thread?grower=' . $GrowerOperation->id;

        $token = [
            'user_id' => $User->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        $link = urldecode(urlencode($route . '&token=' . $jwt));

        $body = "
            <h1>
                {$subject}
            </h1>

            <blockquote>
                {$message}
            </blockquote>
            
            <a href=\"{$link}\" class=\"button bg-green block\">
                View message thread
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
    
    public function grower_new_message_notification($Member, $GrowerOperation, $User, $message) {
        $subject = "New message from {$User->name}";
        
        $route = (ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/messages/inbox/selling/thread?user=' . $User->id . (($GrowerOperation->type != 'individual') ? '&grower=' . $GrowerOperation->id : '');

        $token = [
            'user_id' => $Member->id,
            'grower_operation_id' => $GrowerOperation->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        $link = urldecode(urlencode($route . '&token=' . $jwt));

        $body = "
            <h1>
                {$subject}
            </h1>

            <blockquote>
                {$message}
            </blockquote>
            
            <a href=\"{$link}\" class=\"button bg-green block\">
                View message thread
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }

    public function new_order_notification($Member, $GrowerOperation, $OrderGrower, $Buyer) {
        $subject = "New order - {$GrowerOperation->name}";
        
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
                Good news, {$Member->first_name}!
            </p>
            
            <p>
                {$Buyer->name} has requested to place an order from " . (($GrowerOperation->type == 'individual') ? "you" : "<strong>{$GrowerOperation->name}</strong>") . ".
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

    /* public function order_expiration_warning($Member, $GrowerOperation, $OrderGrower, $Buyer) {} */
    
    public function confirmed_order_notification($Buyer, $OrderGrower, $GrowerOperation) {
        $subject = "Confirmed order - {$GrowerOperation->name}";
        
        $token = [
            'user_id' => $Buyer->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        // @todo scroll to order
        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/account/buying/orders?token=' . $jwt));

        $body = "
            <h1>
                Your order has been confirmed!
            </h1>

            <hr>

            <p>
                Good news, {$Buyer->first_name}!
            </p>
            
            <p>
                {$GrowerOperation->name} has confirmed your order, which means this order is now ready for fulfillment.
            </p>
            
            <p>
                If you selected <strong>Pickup</strong> or <strong>Meetup</strong> as your exchange option from {$GrowerOperation->name}, remember to go receive your order.
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
    
    public function rejected_order_notification($Buyer, $OrderGrower, $GrowerOperation) {
        $subject = "Rejected order - {$GrowerOperation->name}";
        
        $token = [
            'user_id' => $Buyer->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'map?token=' . $jwt));

        $body = "
            <h1>
                Your order was rejected
            </h1>

            <hr>

            <p>
                Hi {$Buyer->first_name},
            </p>
            
            <p>
                Sorry to say that {$GrowerOperation->name} has rejected your order. Don't take it personally! This usually means that the seller couldn't fulfill the item for some reason. You will not be charged for your order to {$GrowerOperation->name}.
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
    
    public function expired_order_notification($Buyer, $OrderGrower, $GrowerOperation) {
        $subject = "Expired order - {$GrowerOperation->name}";
        
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
                Hi {$Buyer->first_name},
            </p>
                
            <p>
                Sorry to say that {$GrowerOperation->name} has let your order expire. You will not be charged for your order to {$GrowerOperation->name}.
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

    public function seller_cancelled_order_notification($Buyer, $OrderGrower, $GrowerOperation) {
        $subject = "Cancelled order - {$GrowerOperation->name}";
        
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
                Hi {$Buyer->first_name},
            </p>
            
            <p>
                Sorry to say that {$GrowerOperation->name} has cancelled your order. You will not be charged for this order to {$GrowerOperation->name}.
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

    public function buyer_cancelled_order_notification($Member, $GrowerOperation, $OrderGrower, $Buyer) {
        $subject = "Cancelled order - {$GrowerOperation->name}";
        
        $token = [
            'user_id' => $Member->id,
            'grower_operation_id' => $GrowerOperation->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/grower/orders/failed/view?id=' . $OrderGrower->id . '&token=' . $jwt));

        $body = "
            <h1>
                Order cancellation
            </h1>

            <hr>

            <p>
                Hi {$Member->first_name},
            </p>
                
            <p>
                Sorry to say {$Buyer->name} has cancelled their order from " . (($GrowerOperation->type == 'individual') ? "you" : "<strong>{$GrowerOperation->name}</strong>") . ". You are no longer responsible for fulfilling this order.
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
    
    /* public function order_fulfillment_reminder() {} */

    public function fulfilled_order_notification($Buyer, $OrderGrower, $GrowerOperation) {
        $subject = "Fulfilled order - {$GrowerOperation->name}";
        
        
        $token = [
            'user_id' => $Buyer->id
        ];
        
        $jwt = JWT::encode($token, JWT_KEY);
        
        $base_route = (ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/account/buying/';

        $review_route = 'review?id=' . $OrderGrower->id;
        $review_link = urldecode(urlencode($review_route . '?token=' . $jwt));
        
        $review_route = 'report?id=' . $OrderGrower->id;
        $report_link = urldecode(urlencode($report_route . '?token=' . $jwt));

        $body = "
            <h1>
                Your order has been fulfilled!
            </h1>

            <hr>

            <p>
                Hi {$Buyer->first_name},
            </p>

            <p>
                {$GrowerOperation->name} has marked your order as fulfilled. If you believe this was done in error, you can <a href=\"{$report_link}\">report an issue</a>.
            </p>

            <p>
                Otherwise, you have three days to review {$GrowerOperation->name}. Be kind and be honest!
            </p>
            
            <a href=\"" . $review_link . "\" class=\"button bg-green block\">
                Review {$GrowerOperation->name}
            </a>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
    
    public function reviewed_order_notification($Member, $GrowerOperation, $OrderGrower, $Buyer) {
        $subject = "New review - {$GrowerOperation->name}";
        
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
                Hi {$Member->first_name}!
            </p>

            <p>
                {$Buyer->name} has left " . (($GrowerOperation->type == 'individual') ? "you" : "<strong>{$GrowerOperation->name}</strong>") . " a new review. This order is now cleared and marked for payout.
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
    
    public function reported_order_seller_notification($Member, $GrowerOperation, $OrderGrower, $Buyer) {
        $subject = "New issue reported - {$GrowerOperation->name}";
        
        $token = [
            'user_id' => $Member->id,
            'grower_operation_id' => $GrowerOperation->id
        ];

        $jwt = JWT::encode($token, JWT_KEY);

        $link = urldecode(urlencode((ENV == 'dev' ? 'localhost:8888' : '') . PUBLIC_ROOT . 'dashboard/grower/orders/under-review/view?id=' . $OrderGrower->id . '&token=' . $jwt));

        $body = "
            <h1>
                Issue reported
            </h1>

            <hr>

            <p>
                Hi {$Member->first_name},
            </p>

            <p>
                This is an automated notice that {$Buyer->name} has reported an issue with an order from " . (($GrowerOperation->type == 'individual') ? "you" : "<strong>{$GrowerOperation->name}</strong>") . ". A Food From Friends representative will be in touch with you soon to resolve this problem.
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
    
    public function reported_order_admin_notification($Buyer, $GrowerOperation, $OrderGrower, $message) {
        $subject = "Issue reported - {$GrowerOperation->name}";
        
        $body = "
            <p>
                <strong>{$Buyer->name}</strong> reported an issue with <strong>{$GrowerOperation->name}</strong>:
            </p>

            <blockquote>
                {$message}
            </blockquote>

            <hr>

            <pre>
                <strong>Suborder ID</strong>: {$OrderGrower->id}
            </pre>

            <pre>
                <strong>Buyer ID</strong>: {$Buyer->id}
            </pre>
            
            <pre>
                <strong>Grower ID</strong>: {$GrowerOperation->id}
            </pre>
        ";
        
        $content = new SendGrid\Content('text/html', $body);
        
        $mail = new SendGrid\Mail($this->from, $subject, $this->to, $content);
        
        // Template: Canvas
        $mail->setTemplateId('02993730-61db-46c5-a806-783072e6fb79');

        return $this->sendgrid->client->mail()->send()->post($mail);
    }
    
    /* public function payout_notification() {} */

}

?>