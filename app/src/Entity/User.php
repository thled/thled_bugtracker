<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table("`user`")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends BaseEntity implements UserInterface
{
    /** @ORM\Column(type="string", length=180, unique=true) */
    private string $email;

    /**
     * @var array<string>
     * @ORM\Column(type="json")
     */
    private array $roles;

    /** @ORM\Column(type="string") */
    private string $password;

    /**
     * @var Collection<Bug>
     * @ORM\OneToMany(targetEntity="App\Entity\Bug", mappedBy="reporter")
     */
    private Collection $reportedBugs;

    /** @ORM\OneToOne(targetEntity="App\Entity\Bug", mappedBy="assignee") */
    private ?Bug $assignedBug;

    /**
     * @param array<string> $roles
     * @param array<Bug> $reportedBugs
     */
    public function __construct(
        string $email,
        array $roles = [],
        string $password = '',
        array $reportedBugs = [],
        ?Bug $assignedBug = null
    ) {
        parent::__construct();

        $this->email = $email;
        $this->roles = $roles;
        $this->password = $password;
        $this->reportedBugs = new ArrayCollection($reportedBugs);
        $this->assignedBug = $assignedBug;
    }

    public function __toString(): string
    {
        return $this->getUsername();
    }

    /**
     * Serialize/Unserialize methods are needed
     * because Doctrine has issues with typed properties (PHP7.4).
     * Especially if they are private (and I want them to be private).
     * (related: https://github.com/symfony/symfony/issues/35660 and
     * https://github.com/doctrine/common/pull/882)
     *
     * @inheritDoc
     * @return array<mixed>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     */
    public function __serialize(): array
    {
        return [
            'id' => $this->getId(),
            'password' => $this->getPassword(),
            'username' => $this->getUsername(),
            'roles' => $this->getRoles(),
        ];
    }

    /**
     * @inheritDoc
     * @param array<mixed> $data
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     */
    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->password = $data['password'];
        $this->email = $data['username'];
        $this->roles = $data['roles'];
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

    /** @return array<Bug> */
    public function getReportedBugs(): array
    {
        return $this->reportedBugs->toArray();
    }

    public function getAssignedBug(): ?Bug
    {
        return $this->assignedBug;
    }
}
