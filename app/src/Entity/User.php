<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table("`user`")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message="user.email.not_blank")
     * @Assert\Length(max="180", maxMessage="user.email.max")
     * @Assert\Email(message="user.email.email")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email = '';

    /**
     * @var array<string>
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /** @ORM\Column(type="string") */
    private string $password = '';

    /**
     * @var Collection<Comment>
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     */
    private Collection $comments;

    /**
     * @var Collection<Bug>
     * @ORM\OneToMany(targetEntity="App\Entity\Bug", mappedBy="reporter")
     */
    private Collection $reportedBugs;

    /** @ORM\OneToOne(targetEntity="App\Entity\Bug", mappedBy="assignee") */
    private ?Bug $assignedBug;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->reportedBugs = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getEmail();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    /** @return array<string> */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /** @param array<string> $roles */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
//            if ($comment->getAuthor() === $this) {
//                $comment->setAuthor(null);
//            }
        }

        return $this;
    }

    /** @return Collection<Bug> */
    public function getReportedBugs(): Collection
    {
        return $this->reportedBugs;
    }

    public function addReportedBug(Bug $reportedBug): self
    {
        if (!$this->reportedBugs->contains($reportedBug)) {
            $this->reportedBugs[] = $reportedBug;
            $reportedBug->setReporter($this);
        }

        return $this;
    }

    public function removeReportedBug(Bug $reportedBug): self
    {
        if ($this->reportedBugs->contains($reportedBug)) {
            $this->reportedBugs->removeElement($reportedBug);
//            if ($reportedBug->getReporter() === $this) {
//                $reportedBug->setReporter(null);
//            }
        }

        return $this;
    }

    public function getAssignedBug(): ?Bug
    {
        return $this->assignedBug;
    }

    public function setAssignedBug(?Bug $assignedBug): self
    {
        $this->assignedBug = $assignedBug;

//        $newAssignee = $assignedBug === null ? null : $this;
//        if ($assignedBug->getAssignee() !== $newAssignee) {
//            $assignedBug->setAssignee($newAssignee);
//        }

        return $this;
    }
}
