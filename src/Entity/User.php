<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $surname;

    /**
     * @ORM\Column(type="string")
     */
    private $username;
    
    /**
     * @ORM\Column(type="string")
     */
    private $mail;
    
    /**
     * @ORM\Column(type="string")
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

 }