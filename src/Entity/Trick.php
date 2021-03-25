<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @ORM\Table(name="trick")
 */

 class Trick
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
     *      message = "Le titre ne peut être vide."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(
     *      message = "La catégorie ne peut être vide."
     * )
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     */
    private $mainpic;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *      message = "Le contenu ne peut être vide."
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rgpd = '1';

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateedit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $editor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="trick")
     */
    private $comments;

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
     * Get message = "Le titre ne peut être vide."
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set message = "Le titre ne peut être vide."
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of mainpic
     */ 
    public function getMainpic()
    {
        return $this->mainpic;
    }

    /**
     * Set the value of mainpic
     *
     * @return  self
     */ 
    public function setMainpic($mainpic)
    {
        $this->mainpic = $mainpic;

        return $this;
    }

    /**
     * Get message = "Le contenu ne peut être vide."
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set message = "Le contenu ne peut être vide."
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

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
     * Get the value of dateedit
     */ 
    public function getDateedit()
    {
        return $this->dateedit;
    }

    /**
     * Set the value of dateedit
     *
     * @return  self
     */ 
    public function setDateedit($dateedit)
    {
        $this->dateedit = $dateedit;

        return $this;
    }

    /**
     * Get the value of author
     */ 
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */ 
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of editor
     */ 
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Set the value of editor
     *
     * @return  self
     */ 
    public function setEditor($editor)
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Get the value of comments
     */ 
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set the value of comments
     *
     * @return  self
     */ 
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get message = "La catégorie ne peut être vide."
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set message = "La catégorie ne peut être vide."
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }
 }