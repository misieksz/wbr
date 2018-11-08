<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @UniqueEntity(fields="username")
 * @UniqueEntity(fields="email")
 */
class User implements AdvancedUserInterface, \Serializable
{
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    
    /**
     *
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\NotBlank(groups={"Register", "ChangeDetails"})
     * @Assert\Length(
     * min=5,
     * max=20,
     * groups={"Register", "ChangeDetails", "userManage"}
     * 
     * )
     */
    private $username;
    
     /**
     *
     * @ORM\Column(type="string", length=120, unique=true)
     
     * @Assert\Email(groups={"Register"})
     * @Assert\Length(
     * max=120,
      * groups={"Register", "userManage"})
     */
    private $email;
    
     /**
     *
     * @ORM\Column(type="string", length=120)
     * @Assert\NotBlank(groups={"Register"})
     * @Assert\Length(
     * min=3,
     * max=20,
      * groups={"Register", "userManage"})   
      */
    private $firstName;
    
     /**
     *
     * @ORM\Column(type="string", length=120)
     * @Assert\NotBlank(groups={"Register"})
     * @Assert\Length(
     * min=3,
     * max=20,
      * groups={"Register", "userManage"})   
      */
    private $lastName;
    
     /**
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;
    
      /** 
      * @Assert\NotBlank(groups={"ChangePassword"})
       * @Assert\Length(
       * min=8,
       * groups={"ChangePassword"})
       *
       */
    private $plainPassword;
    

    /**
     *
     * @ORM\Column(name="account_non_expired", type="boolean")
     */
    private $accountNonExpired = true;
    
      /**
     *
     * @ORM\Column(name="account_non_locked", type="boolean")
     */
    private $accountNonLocked = true;
    
     /**
     *
     * @ORM\Column(name="credentials_non_expired", type="boolean")
     */
    private $credentialsNonExpired = true;
    
      /**
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled = false;
    
    /**
     *
     * @ORM\Column(type="array")
     */
    private $roles;
    
    /**
     *
     * @ORM\Column(name="action_token", type="string", length=20, nullable=true)
     */
    private $actionToken;
    
    /**
     *
     * @ORM\Column(name="register_date", type="datetime")
     */
    private $registerDate;
   
    /**
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $avatar;
    
    
    public function __construct()
    {
        $this->registerDate = new \DateTime();
    }
    
    public function eraseCredentials() {
        return $this->plainPassword;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getPlainPassword()
    {
        return $this->plainPassword;
        
    }
    
    public function getFirstName() {
        return $this->firstName;
    }
    
   public function getLastName() {
        return $this->lastName;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRoles() {
        return $this->roles;
    }
    
    public function rolesToString()
    {
        $newVal = null;
        foreach($this->roles as $key => $val)
        {
            $newVal = $val;
        }
        return $newVal;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getRegisterDate() {
        return $this->registerDate;
    } 
    
    public function getActionToken() {
        return $this->actionToken;
    }    
    
    public function getSalt() {
        return null;
    }

    public function getUsername() {
        return $this->username;
    }

    public function isAccountNonExpired() {
        return $this->accountNonExpired;
    }

    public function isAccountNonLocked() {
        return $this->accountNonLocked;
    }

    public function isCredentialsNonExpired() {
        return $this->credentialsNonExpired;
    }

    public function isEnabled(): bool {
        return $this->enabled;
    }

    
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }
    
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }
    
    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function setAccountNonExpired($accountNonExpired) {
        $this->accountNonExpired = $accountNonExpired;
        return $this;
    }

    public function setAccountNonLocked($accountNonLocked) {
        $this->accountNonLocked = $accountNonLocked;
        return $this;
    }

    public function setCredentialsNonExpired($credentialsNonExpired) {
        $this->credentialsNonExpired = $credentialsNonExpired;
        return $this;
    }

    public function setEnabled($enabled) {
        $this->enabled = $enabled;
        return $this;
    }

    public function setRoles($roles) {
        $this->roles = $roles;
        return $this;
    }

    public function setActionToken($actionToken) {
        $this->actionToken = $actionToken;
        return $this;
    }

    public function setRegisterDate($registerDate) {
        $this->registerDate = $registerDate;
        return $this;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
        return $this;
    }

    /** @see \Serializable::serialize() */
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->enabled
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized): void {
        list(
             $this->id,
             $this->username,
             $this->password,
             $this->enabled
             ) = unserialize($serialized);
    }
    
    

}
