<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;




class Contact {


    /**
     *
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=40)
     */
    private $name;
    
    /**
     *
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;
    
    /**
     *
     * @Assert\Length(max=30)
     */
    private $subject;
    /**
     *
     * @Assert\NotBlank
     * @Assert\Length(min=10)
     */
    private $message;
    
    
    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    
    public function sendEmail($mailer) {
        $paramNames = array('name', 'email', 'subject', 'message');
        $formData = array();

        foreach ($paramNames as $name) {
            $formData[$name] = $this->{$name};
        }

        $msgBody = "Użytkownik: {$formData['name']}\nWiadomość:\n{$formData['message']}";
        $message = (new \Swift_Message())
                ->setFrom($formData['email'])
                ->setTo('michal.szpyrka@gmail.com')
                ->setSubject($formData['subject'])
                ->setBody($msgBody);


        $mailer->send($message);
    }

}
