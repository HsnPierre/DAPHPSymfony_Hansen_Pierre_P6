<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @ORM\Table(name="user")
 */

 class User
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
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message = "Le mot de passe ne peut être vide.")
     * @Assert\Regex(
     *      pattern = "/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{12,}$/",
     *      match = true,
     *      message = "Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre, un caractère spécial et faire au moins 12 caractères"
     *      
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     */
    private $profilepic;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rgpd;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;


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
 }