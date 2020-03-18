<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="App\Repository\ProjectRepository") */
class Project extends BaseEntity
{
    /** @ORM\Column(type="string", length=5) */
    private string $projectId;

    /** @ORM\Column(type="string", length=128) */
    private string $name;

    /**
     * @var Collection<Bug>
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Bug",
     *     mappedBy="project",
     *     orphanRemoval=true
     * )
     */
    private Collection $bugs;

    /** @param array<Bug> $bugs */
    public function __construct(
        string $projectId,
        string $name = '',
        array $bugs = []
    ) {
        parent::__construct();

        $this->projectId = $projectId;
        $this->name = $name;
        $this->bugs = new ArrayCollection($bugs);
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->getName(), $this->getProjectId());
    }

    public function getProjectId(): string
    {
        return $this->projectId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** @return array<Bug> */
    public function getBugs(): array
    {
        return $this->bugs->toArray();
    }
}
