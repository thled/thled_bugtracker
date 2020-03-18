<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

/** @ORM\Entity(repositoryClass="App\Repository\BugRepository") */
class Bug extends BaseEntity
{
    /** @ORM\Column(type="integer") */
    private int $bugId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="bugs")
     * @ORM\JoinColumn(nullable=false)
     */
    private Project $project;

    /** @ORM\Column(type="smallint") */
    private int $status;

    /** @ORM\Column(type="smallint") */
    private int $priority;

    /** @ORM\Column(type="date_immutable") */
    private DateTimeImmutable $due;

    /** @ORM\Column(type="string", length=128) */
    private string $title;

    /** @ORM\Column(type="text") */
    private string $summary;

    /** @ORM\Column(type="text") */
    private string $reproduce;

    /** @ORM\Column(type="text") */
    private string $expected;

    /** @ORM\Column(type="text") */
    private string $actual;

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

    /**
     * @var Collection<Comment>
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="bug")
     */
    private Collection $comments;

    /** @param array<Comment> $comments */
    public function __construct(
        int $bugId,
        Project $project,
        User $reporter,
        User $assignee,
        DateTimeInterface $due,
        int $status = 0,
        int $priority = 0,
        string $title = '',
        string $summary = '',
        string $reproduce = '',
        string $expected = '',
        string $actual = '',
        array $comments = []
    ) {
        parent::__construct();

        $this->bugId = $bugId;
        $this->project = $project;
        $this->status = $status;
        $this->priority = $priority;
        $this->due = $this->castToDateTimeImmutable($due);
        $this->title = $title;
        $this->summary = $summary;
        $this->reproduce = $reproduce;
        $this->expected = $expected;
        $this->actual = $actual;
        $this->comments = new ArrayCollection($comments);
        $this->reporter = $reporter;
        $this->assignee = $assignee;
    }

    private function castToDateTimeImmutable(DateTimeInterface $due): DateTimeImmutable
    {
        if ($due instanceof DateTimeImmutable) {
            return $due;
        }

        if ($due instanceof DateTime) {
            return DateTimeImmutable::createFromMutable($due);
        }

        throw new InvalidTypeException('Due has an invalid type.');
    }

    public function getBugId(): int
    {
        return $this->bugId;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getDue(): DateTimeInterface
    {
        return $this->due;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getReproduce(): string
    {
        return $this->reproduce;
    }

    public function getExpected(): string
    {
        return $this->expected;
    }

    public function getActual(): string
    {
        return $this->actual;
    }

    public function getReporter(): User
    {
        return $this->reporter;
    }

    public function getAssignee(): User
    {
        return $this->assignee;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /** @return array<Comment> */
    public function getComments(): array
    {
        return $this->comments->toArray();
    }
}
