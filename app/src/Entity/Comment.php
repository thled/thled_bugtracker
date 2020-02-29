<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/** @ORM\Entity(repositoryClass="App\Repository\CommentRepository") */
class Comment
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /** @ORM\Column(type="text") */
    private string $content = '';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bug", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private Bug $bug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getBug(): Bug
    {
        return $this->bug;
    }

    public function setBug(Bug $bug): self
    {
        $this->bug = $bug;

        return $this;
    }
}
