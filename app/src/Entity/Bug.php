<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/** @ORM\Entity(repositoryClass="App\Repository\BugRepository") */
class Bug
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /** @ORM\Column(type="integer") */
    private int $bugId = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="bugs")
     * @ORM\JoinColumn(nullable=false)
     */
    private Project $project;

    /** @ORM\Column(type="smallint") */
    private int $priority = 1;

    /** @ORM\Column(type="date_immutable") */
    private DateTimeImmutable $due;

    /** @ORM\Column(type="string", length=128) */
    private string $title = '';

    /** @ORM\Column(type="text") */
    private string $summary = '';

    /** @ORM\Column(type="text") */
    private string $reproduce = '';

    /** @ORM\Column(type="text") */
    private string $expected = '';

    /** @ORM\Column(type="text") */
    private string $actual = '';

    /**
     * @var Collection<Comment>
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="bug")
     */
    private Collection $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reportedBugs")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $reporter;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="assignedBug")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $assignee;

    /** @ORM\Column(type="smallint") */
    private int $status = 0;

    public function __construct()
    {
        $this->project = new Project();
        $this->comments = new ArrayCollection();
        $this->reporter = new User();
        $this->assignee = new User();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBugId(): int
    {
        return $this->bugId;
    }

    public function setBugId(int $bugId): self
    {
        $this->bugId = $bugId;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getDue(): DateTimeImmutable
    {
        return $this->due;
    }

    public function setDue(DateTimeImmutable $due): self
    {
        $this->due = $due;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getReproduce(): string
    {
        return $this->reproduce;
    }

    public function setReproduce(string $reproduce): self
    {
        $this->reproduce = $reproduce;

        return $this;
    }

    public function getExpected(): string
    {
        return $this->expected;
    }

    public function setExpected(string $expected): self
    {
        $this->expected = $expected;

        return $this;
    }

    public function getActual(): string
    {
        return $this->actual;
    }

    public function setActual(string $actual): self
    {
        $this->actual = $actual;

        return $this;
    }

    /** @return Collection<Comment> */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setBug($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
//            if ($comment->getBug() === $this) {
//                $comment->setBug(null);
//            }
        }

        return $this;
    }

    public function getReporter(): User
    {
        return $this->reporter;
    }

    public function setReporter(User $reporter): self
    {
        $this->reporter = $reporter;

        return $this;
    }

    public function getAssignee(): User
    {
        return $this->assignee;
    }

    public function setAssignee(User $assignee): self
    {
        $this->assignee = $assignee;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
