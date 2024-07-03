<?php

use Mailjet\Resources;

require 'vendor/autoload.php';

class Mailer
{
    private $mailjet;

    public function __construct()
    {
        $this->mailjet = new \Mailjet\Client('81ea24ce778b6e7ecc44af9aaaca1da3', '418bade66c7e26bbc9fb672efadd6512', true, ['version' => 'v3.1']);
    }

    public function sendMailjet($to, $subject, $body)
    {
        $message = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "balogbalesz1234@gmail.com",
                        'Name' => "Lite"
                    ],
                    'To' => [
                        [
                            'Email' => $to,
                            'Name' => $to
                        ]
                    ],
                    'Subject' => $subject,
                    'TextPart' => strip_tags($body),
                    'HTMLPart' => $body
                ]
            ]
        ];
        $response = $this->mailjet->post(Resources::$Email, ['body' => $message]);
        return $response->success();
    }
}

?>
