<?php

declare(strict_types=1);

namespace App\Service;

use App\DataTransferObject\BugUpdateDto;
use App\Entity\Bug;
use App\Entity\User;
use DateTimeImmutable;
use DateTimeInterface;
use LogicException;

final class BugUpdater implements BugUpdaterInterface
{
    public function updateFromDto(Bug $bug, BugUpdateDto $bugDto): void
    {
        $due = $bugDto->due;
        if (!$due instanceof DateTimeInterface) {
            $due = (new DateTimeImmutable())->setTimestamp(0);
        }

        $assignee = $bugDto->assignee;
        if (!$assignee instanceof User) {
            throw new LogicException('Form validation failed.');
        }

        $bug->setStatus($bugDto->status ?? 0);
        $bug->setPriority($bugDto->priority ?? 0);
        $bug->setDue($due);
        $bug->setTitle($bugDto->title ?? '');
        $bug->setSummary($bugDto->summary ?? '');
        $bug->setReproduce($bugDto->reproduce ?? '');
        $bug->setExpected($bugDto->expected ?? '');
        $bug->setActual($bugDto->actual ?? '');
        $bug->setAssignee($assignee);
    }
}
