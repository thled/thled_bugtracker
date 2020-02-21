<?php

declare(strict_types=1);

namespace App\Entity;

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
    private int $id;

    /** @ORM\Column(type="string", length=5) */
    private string $projectId = '';

    /** @ORM\Column(type="string", length=128) */
    private string $name = '';

    /**
     * @var array<string>
     * @ORM\Column(type="json")
     */
    private array $versions = [];

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

    /** @return array<string> */
    public function getVersions(): array
    {
        $versions = $this->versions;

        return array_unique($versions);
    }

    public function addVersions(string $version): self
    {
        $this->versions[] = $version;

        return $this;
    }
}
