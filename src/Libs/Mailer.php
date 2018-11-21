<?php

namespace App\Libs;


class Mailer
{
    
    /**
     *
     * @var \Swift_Mailer
     */
    private $swiftMailer;
    
    
    
    public function __construct(\Swift_Mailer $swiftMailer, string $from, $to, $subject, $msgBody)
    {
        $this->swiftMailer = $swiftMailer;
       
    }
    
    public function sendEmail(array $from, $to, $subject, $msgBody) 
    {
        $message = (new \Swift_Message())
                ->setFrom($from)
                ->setTo($to)
                ->setSubject($subject)
                ->setBody($msgBody, 'text/html');


        $this->swiftMailer->send($message);
    }
}
