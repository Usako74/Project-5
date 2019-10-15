<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostLikeRepository")
 */
class PostLike
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="likes")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users")
     */
    private $user;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Project|null
     */
    public function getPost(): ?Project
    {
        return $this->post;
    }

    /**
     * @param Project|null $post
     * @return PostLike
     */
    public function setPost(?Project $post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return Users|null
     */
    public function getUser(): ?Users
    {
        return $this->user;
    }

    /**
     * @param Users|null $user
     * @return PostLike
     */
    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }
}
