<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\BugCreateDto;
use App\Entity\Bug;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\BugRepositoryInterface;
use DateTimeImmutable;
use LogicException;

final class BugFactory implements BugFactoryInterface
{
    private BugRepositoryInterface $bugRepo;

    public function __construct(BugRepositoryInterface $bugRepo)
    {
        $this->bugRepo = $bugRepo;
    }

    public function createFromDto(BugCreateDto $bugDto): Bug
    {
        $project = $bugDto->project;
        $reporter = $bugDto->reporter;
        $assignee = $bugDto->assignee;

        if (
            (!$project instanceof Project) ||
            (!$reporter instanceof User) ||
            (!$assignee instanceof User)
        ) {
            throw new LogicException('Form validation failed.');
        }

        $bugId = $this->getNextBugIdOfProject($project);

        $due = $bugDto->due;
        if (!$due instanceof DateTimeImmutable) {
            $due = (new DateTimeImmutable())->setTimestamp(0);
        }

        return new Bug(
            $bugId,
            $project,
            $reporter,
            $assignee,
            $due,
            $bugDto->status ?? 0,
            $bugDto->priority ?? 0,
            $bugDto->title ?? '',
            $bugDto->summary ?? '',
            $bugDto->reproduce ?? '',
            $bugDto->expected ?? '',
            $bugDto->actual ?? '',
        );
    }

    private function getNextBugIdOfProject(Project $project): int
    {
        $latestBug = $this->bugRepo->findLatestBugOfProject($project);
        if ($latestBug instanceof Bug) {
            return $latestBug->getBugId() + 1;
        }

        return 1;
    }
}
