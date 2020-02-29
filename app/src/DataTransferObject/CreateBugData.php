<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Entity\Project;
use App\Entity\User;
use DateTimeImmutable;

final class CreateBugData
{
    public ?Project $project = null;
    public ?int $status = null;
    public ?int $priority = null;
    public ?DateTimeImmutable $due = null;
    public ?string $title = null;
    public ?string $summary = null;
    public ?string $reproduce = null;
    public ?string $expected = null;
    public ?string $actual = null;
    public ?User $reporter = null;
    public ?User $assignee = null;
}
