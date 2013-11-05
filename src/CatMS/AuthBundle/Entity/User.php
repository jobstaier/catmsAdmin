<?php
namespace CatMS\AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints\True;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="CatMS\AuthBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks() 
 * @UniqueEntity(fields={"username"}, message="user.username.exists")
 * @UniqueEntity(fields={"userHash"}, message="user.userHash.exists")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min="4",
     *      minMessage="Your username must have at least {{ limit }} characters."
     * )
     */
    private $username;
    
    /**
     * @ORM\Column(type="string", length=15, unique=true)
     * @Assert\NotBlank()
     */
    private $userHash;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(message="Password cannot be blank")
     * @Assert\Length(
     *      min="5",
     *      minMessage="Your paswword must have at least {{ limit }} characters."
     * )
     */
    private $password;
    
    private $passwordRetype;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Role cannot be blank")
     * @Assert\Choice(choices = {"ROLE_USER", "ROLE_ADMIN", "ROLE_DEVELOPER"}, message = "Choose a valid role.")
     */
    private $roles;
    
    private $setNewPassword = false;
    
    private $gravatar;

    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * @inheritDoc
     */
    public function getPasswordRetype()
    {
        return $this->passwordRetype;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array($this->roles);
    }
    
    public function getUserHash()
    {
        return $this->userHash;
    }
    

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->salt
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPasswordRetype($passwordRetype)
    {
        $this->passwordRetype = $passwordRetype;
    
        return $this;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    
    /**
     * @Assert\True(message = "The typed passwords are not the same")
     */
    public function isPasswordsIdentical()
    {
        if ($this->setNewPassword == true) {
            return ($this->password == $this->passwordRetype);
        } else {
            return true;
        }
    }
    
    public function setNewPassword($bool)
    {
        $this->setNewPassword = $bool;
    }
    
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * Set roles
     *
     * @param string $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    
        return $this;
    }

    public function setUserHash($userHash) 
    {
        $this->userHash = $userHash;
    }
    
    public function setGravatar($gravatarUrl) 
    {
        $this->gravatar = $gravatarUrl;
        return $this;
    }
    
    public function getGravatar()
    {
        return $this->gravatar;
    }
}
