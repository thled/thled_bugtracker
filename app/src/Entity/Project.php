<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/** @ORM\Entity(repositoryClass="App\Repository\ProjectRepository") */
class Project
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /** @ORM\Column(type="string", length=5) */
    private string $projectId = '';

    /** @ORM\Column(type="string", length=128) */
    private string $name = '';

    /**
     * @var Collection<Bug>
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Bug",
     *     mappedBy="project",
     *     orphanRemoval=true
     * )
     */
    private Collection $bugs;

    public function __construct()
    {
        $this->bugs = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->getName(), $this->getProjectId());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectId(): string
    {
        return $this->projectId;
    }

    public function setProjectId(string $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /** @return Collection<Bug> */
    public function getBugs(): Collection
    {
        return $this->bugs;
    }
}
