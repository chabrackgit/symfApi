<?php

namespace App\Entity;

use App\Entity\Article;
use Symfony\Component\Validator\Constraints as Assert;

class Contact{
    
    /**
     * firstname
     *
     * @var mixed
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    private $firstname;

    /**
     * lastname
     *
     * @var mixed
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    private $lastname;

    /**
     * phone
     *
     * @var mixed
     * @Assert\NotBlank()
     * @Assert\Regex(
     *      pattern="/[0-9]{10}/"
     * )
     */
    private $phone;

    /**
     * email
     *
     * @var mixed
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * message
     *
     * @var mixed
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */
    private $message;

    /**
     * message
     *
     * @var Article
     */
    private $article;


    /**
     * Get firstname
     *
     * @return  mixed
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set firstname
     *
     * @param  mixed  $firstname  firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return  mixed
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set lastname
     *
     * @param  mixed  $lastname  lastname
     *
     * @return  self
     */ 
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get pattern="/[0-9]{10}/"
     *
     * @return  mixed
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set pattern="/[0-9]{10}/"
     *
     * @param  mixed  $phone  pattern="/[0-9]{10}/"
     *
     * @return  self
     */ 
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get email
     *
     * @return  mixed
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param  mixed  $email  email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get message
     *
     * @return  mixed
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message
     *
     * @param  mixed  $message  message
     *
     * @return  self
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return  Article
     */ 
    public function getArticle() : Article
    {
        return $this->article;
    }

    /**
     * Set message
     *
     * @param  Article  $article  message
     *
     * @return  self
     */ 
    public function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }
}