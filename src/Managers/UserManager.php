<?php

namespace App\Managers;



use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use App\Libs\Mailer;
use App\Exception\UserException;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UserManager
{
    /**
     * @var Doctrine
     */
    protected $Doctrine;
    
    /**
     *
     * @var Router
     */
    protected $Router;
    
    /**
     *
     * @var Templating
     */
    protected $Templating;
    
    /**
     *
     * @var EncoderFactory
     */
    protected $EncoderFactory;
    
    /**
     *
     * @var Mailer
     */
    protected $Mailer;
    
    public function __construct(Doctrine $Doctrine, Router $Router, \Twig_Environment $Templating, Mailer $Mailer, UserPasswordEncoderInterface $EncodefFactory)
    {
       $this->Doctrine = $Doctrine;
       $this->Router = $Router;
       $this->Templating =  $Templating;
       $this->EncoderFactory =  $EncodefFactory;
       $this->Mailer = $Mailer;
    }
    
    protected function generateActionToken()
    {
       return substr( md5(uniqid(NULL, TRUE)), 0, 20);
    }
        
    protected function getRandomPassword($length = 8)
    {
       $alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPRSTQUWXYZ0123456789';
       $alphaLength = strlen($alphabet) -1;
       $pass = [];
       
       for($i=0; $i<$length; $i++)
       {
           $n = rand(0, $alphaLength);
           $pass[] = $alphabet[$n];
       }
       
       return implode($pass);
    }
    
    public function sendResetPasswordLink($userEmail)
    {
        $Repo = $this->Doctrine->getRepository(User::class);
        $User = $Repo->findOneByEmail($userEmail);
        
        if(null === $User)
        {
            throw new UserException('Nie znaleziono takiego użytkownika!');
        }
        
        $User->setActionToken($this->generateActionToken());
        
        $em = $this->Doctrine->getManager();
        $em->persist($User);
        $em->flush();
        
        $urlParams = array('actionToken' => $User->getActionToken());
        $resetUrl = $this->Router->generate('user_resetPassword', $urlParams, UrlGeneratorInterface::ABSOLUTE_URL);
        
        $emailBody = $this->Templating->render('email/resetPasswdLink.html.twig', array(
            'resetUrl' => $resetUrl
        ));
        
        $this->Mailer->sendEmail(['biuro@wartobycrazem.pl' => 'Warto Być Razem'], $User->getEmail(), 'Link resetujący hasło', $emailBody);
        return true;
    }
    
    public function resetPassword($actionToken)
    {
        $Repo = $this->Doctrine->getRepository(User::class);
        $User = $Repo->findOneByActionToken($actionToken);
        
        if(null === $User)
        {
            throw new UserException('Nieprawidłowe parametry akcji!');
        }
        
        
        
        $plainPassword = $this->getRandomPassword();
        
        $encodePasswd = $this->EncoderFactory->encodePassword($User, $plainPassword);
        
        $User->setPassword($encodePasswd);
        $User->setActionToken(null);
        $User->setEnabled(true);
        
        $em = $this->Doctrine->getManager();
        $em->persist($User);
        $em->flush();
        
        $msgBody = $this->Templating->render('email/newPassword.html.twig',
                array(
                    'plainPassword' => $plainPassword
                ));
        $this->Mailer->sendEmail(['biuro@wartobycrazem.pl' => 'Warto Być Razem'], $User->getEmail(), 'Nowe hasło', $msgBody);
        return true;
    }
    
    public function registerUser(User $User)
    {
       if(null !== $User->getId())
       {
           throw new UserException('Użytkownik już istnieje!');
       }
       
       $encodePassword = $this->EncoderFactory->encodePassword($User, $User->getPlainPassword());
       
       $User->setPassword($encodePassword);
       $User->setActionToken($this->generateActionToken());
       $User->setRoles(array('ROLE_USER'));
       $User->setEnabled(false);
       
       $em = $this->Doctrine->getManager();
       $em->persist($User);
       $em->flush();
       
       $params = array(
           'actionToken' => $User->getActionToken()
       );
       $activateAccount = $this->Router->generate('activate_account', $params, UrlGeneratorInterface::ABSOLUTE_URL);
       
       $msgBody = $this->Templating->render('email/newAccount.html.twig', array(
        'activateAccount' => $activateAccount
        ));
       

       
       $this->Mailer->sendEmail(['biuro@wartobycrazem.pl' => 'Warto Być Razem'], $User->getEmail(), 'Aktywacja nowego konta', $msgBody);
       
       return true;
    }
    

    public function activateAccount($actionToken)
    {
        $User = $this->Doctrine->getRepository(User::class)
                ->findOneByActionToken($actionToken);
        
        if(null === $User)
        {
            throw new UserException('Błędny kod aktywacyjny!');
        }
        
        $User->setActionToken(null);
        $User->setEnabled(true);
        
        $em = $this->Doctrine->getManager();
        $em->persist($User);
        $em->flush();
        
        return true;
    }
    
    public function changePassword(User $User)
    {
        if(null === $User->getPlainPassword())
        {
            throw new UserException('Nie podano nowego hasła!');
        }
        
        $encodePassword = $this->EncoderFactory->encodePassword($User, $User->getPlainPassword());
        
        $User->setPassword($encodePassword);
        
        $em = $this->Doctrine->getManager();
        $em->persist($User);
        $em->flush();
        
        return true;
    }
    
}