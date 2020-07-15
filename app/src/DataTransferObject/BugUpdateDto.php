<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Entity\User;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class BugUpdateDto implements DataTransferObjectInterface
{
    /** @Assert\Choice(choices={0, 1, 2, 3}, message="bug.status.choice") */
    public ?int $status = null;

    /** @Assert\Choice(choices={0, 1, 2, 3}, message="bug.priority.choice") */
    public ?int $priority = null;
    public ?DateTimeInterface $due = null;

    /** @Assert\Length(max="128", maxMessage="bug.title.max") */
    public ?string $title = null;
    public ?string $summary = null;
    public ?string $reproduce = null;
    public ?string $expected = null;
    public ?string $actual = null;

    /** @Assert\NotBlank(message="bug.assignee.not_blank") */
    public ?User $assignee = null;
}
