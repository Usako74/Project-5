<?php
/**
 * Created by PhpStorm.
 * User: Usako
 * Date: 09/06/2019
 * Time: 13:18
 */

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Task
{
    /**
     * @Assert\Length(min=2, max=255)
     */
    protected $name;

    /**
     * @Assert\Length(min=2, max=255)
     */
    protected $firstName;

    /**
     * @Assert\Email()
     */
    protected $email;

    /**
     * @Assert\Length(min=10, max=255)
     */
    protected $subject;

    /**
     * @Assert\Length(min=10)
     */
    protected $content;


    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
}