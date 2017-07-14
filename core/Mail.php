<?php

class Mail {
    private
        $api_key;
    
    public
        $sendgrid,
        $from,
        $to;
    
    public function __construct($send) {
        $this->api_key = SENDGRID_APIKEY;
       
        $this->sendgrid = new \SendGrid($this->api_key);

        $this->from = new SendGrid\Email($send['fromName'], $send['fromEmail']);
        $this->to = new SendGrid\Email($send['toName'], $send['toEmail']);
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
}

?>