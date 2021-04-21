<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields="mail", message="L'adresse mail est déjà utilisée.")
 * @UniqueEntity(fields="username", message="Le pseudo est déjà utilisé.")
 */

 class User implements UserInterface
 {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(
     *      message = "Le nom ne peut être vide."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(
     *      message = "Le prénom ne peut être vide."
     * )
     */
    private $surname;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(
     *      message = "Le pseudo ne peut être vide."
     * )
     */
    private $username;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message = "Le mail ne peut être vide.")
     * @Assert\Email(
     *      message = "L'adresse mail '{{ value }}' n'est pas valide."
     * )
     */
    private $mail;

    /**
     * @Assert\NotBlank(message = "Le mot de passe ne peut être vide.")
     * @Assert\Length(max=4096)
     * @Assert\Regex(
     *      pattern = "/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{12,}$/",
     *      match = true,
     *      message = "Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre, un caractère spécial et faire au moins 12 caractères"
     *      
     * )
     */
    private $plainPassword;
    
    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     */
    private $profilepic = "https://www.heberger-image.fr/images/2021/01/13/pic9541eea855b1306b.png";

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $rgpd;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="json")
     */
    private $public = [];

    private $public_name;

    private $public_surname;

    private $public_username;

    private $public_mail;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of surname
     */ 
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set the value of surname
     *
     * @return  self
     */ 
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of mail
     */ 
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set the value of mail
     *
     * @return  self
     */ 
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of profilepic
     */ 
    public function getProfilepic()
    {
        return $this->profilepic;
    }

    /**
     * Set the value of profilepic
     *
     * @return  self
     */ 
    public function setProfilepic($profilepic)
    {
        $this->profilepic = $profilepic;

        return $this;
    }

    /**
     * Get the value of rgpd
     */ 
    public function getRgpd()
    {
        return $this->rgpd;
    }

    /**
     * Set the value of rgpd
     *
     * @return  self
     */ 
    public function setRgpd($rgpd)
    {
        $this->rgpd = $rgpd;

        return $this;
    }

    /**
     * Get the value of date
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of plainPassword
     */ 
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of plainPassword
     *
     * @return  self
     */ 
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function eraseCredentials()
    {
    }

    /**
     * Get the value of public
     */ 
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set the value of public
     *
     * @return  self
     */ 
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get the value of public_name
     */ 
    public function getPublic_name()
    {
        return $this->public_name;
    }

    /**
     * Set the value of public_name
     *
     * @return  self
     */ 
    public function setPublic_name($public_name)
    {
        $this->public_name = $public_name;

        return $this;
    }

    /**
     * Get the value of public_surname
     */ 
    public function getPublic_surname()
    {
        return $this->public_surname;
    }

    /**
     * Set the value of public_surname
     *
     * @return  self
     */ 
    public function setPublic_surname($public_surname)
    {
        $this->public_surname = $public_surname;

        return $this;
    }

    /**
     * Get the value of public_username
     */ 
    public function getPublic_username()
    {
        return $this->public_username;
    }

    /**
     * Set the value of public_username
     *
     * @return  self
     */ 
    public function setPublic_username($public_username)
    {
        $this->public_username = $public_username;

        return $this;
    }

    /**
     * Get the value of public_mail
     */ 
    public function getPublic_mail()
    {
        return $this->public_mail;
    }

    /**
     * Set the value of public_mail
     *
     * @return  self
     */ 
    public function setPublic_mail($public_mail)
    {
        $this->public_mail = $public_mail;

        return $this;
    }
 }