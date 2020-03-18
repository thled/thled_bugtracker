<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Entity\Project;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateBugDto
{
    /** @Assert\NotBlank(message="bug.project.not_blank") */
    public ?Project $project = null;

    /** @Assert\Choice(choices={0, 1, 2, 3}, message="bug.status.choice") */
    public ?int $status = null;

    /** @Assert\Choice(choices={0, 1, 2, 3}, message="bug.priority.choice") */
    public ?int $priority = null;
    public ?DateTimeImmutable $due = null;

    /** @Assert\Length(max="128", maxMessage="bug.title.max") */
    public ?string $title = null;
    public ?string $summary = null;
    public ?string $reproduce = null;
    public ?string $expected = null;
    public ?string $actual = null;
    public ?User $reporter = null;

    /** @Assert\NotBlank(message="bug.assignee.not_blank") */
    public ?User $assignee = null;
}
