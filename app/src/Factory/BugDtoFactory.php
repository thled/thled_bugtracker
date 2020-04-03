<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\BugUpdateDto;
use App\Entity\Bug;

final class BugDtoFactory implements BugDtoFactoryInterface
{
    public function createUpdate(Bug $bug): BugUpdateDto
    {
        $bugDto = new BugUpdateDto();
        $bugDto->status = $bug->getStatus();
        $bugDto->priority = $bug->getPriority();
        $bugDto->due = $bug->getDue();
        $bugDto->title = $bug->getTitle();
        $bugDto->summary = $bug->getSummary();
        $bugDto->reproduce = $bug->getReproduce();
        $bugDto->expected = $bug->getExpected();
        $bugDto->actual = $bug->getActual();
        $bugDto->assignee = $bug->getAssignee();

        return $bugDto;
    }
}
