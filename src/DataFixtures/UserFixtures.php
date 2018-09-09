<?php

namespace App\DataFixtures;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;

class UserFixtures extends Fixture 
{

    
    

  
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
     $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager) {
        
        
        
        $usersList = array(
            array(
                'firstName' => 'Michał',
                'lastName' => 'Szpyrka',
                'nick' => 'admin',
                'email' => 'michal.szpyrka@gmail.com',
                'password' => '1234',
                'role' => 'ROLE_ADMIN'
            ),
            array(
                'firstName' => 'Piotr',
                'lastName' => 'Niesyczyński',
                'nick' => 'piotr',
                'email' => 'piotr@wartobycrazem.pl',
                'password' => '123',
                'role' => 'ROLE_MODERATOR'
            )
        );
        
        
        foreach($usersList as $userDetails)
        {
            $User = new User();
            $password = $this->encoder->encodePassword($User, $userDetails['password']);
            
            $User->setFirstName($userDetails['firstName'])
            ->setLastName($userDetails['lastName'])     
            ->setEmail($userDetails['email'])
            ->setUsername($userDetails['nick'])
            ->setPassword($password)
            ->setRoles(array($userDetails['role']))
            ->setEnabled(true);
            
            $this->addReference('user_'.$userDetails['nick'], $User);
            $manager->persist($User);        
            
        }
        
        $manager->flush();
    }



}