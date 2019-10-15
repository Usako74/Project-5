<?php
/**
 * Created by PhpStorm.
 * User: Usako
 * Date: 09/06/2019
 * Time: 13:18
 */

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Task
 * @package App\Entity
 */
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


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}