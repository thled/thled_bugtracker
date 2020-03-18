<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="App\Repository\CommentRepository") */
class Comment extends BaseEntity
{
    /** @ORM\Column(type="text") */
    private string $content;

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

    public function __construct(User $author, Bug $bug, string $content = '')
    {
        parent::__construct();

        $this->content = $content;
        $this->author = $author;
        $this->bug = $bug;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getBug(): Bug
    {
        return $this->bug;
    }
}
