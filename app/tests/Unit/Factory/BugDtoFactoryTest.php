<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\DataTransferObject\BugUpdateDto;
use App\Entity\Bug;
use App\Entity\Project;
use App\Entity\User;
use App\Factory\BugDtoFactory;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** @covers \App\Factory\BugDtoFactory */
final class BugDtoFactoryTest extends TestCase
{
    public function testCreateFromDto(): void
    {
        $bugId = 1;
        $project = new Project('P1');
        $reporter = $assignee = new User('foo@example.com');
        $bug = $this->createBug($bugId, $project, $reporter, $assignee);

        $bugDtoFactory = new BugDtoFactory();


        $bugDto = $bugDtoFactory->createUpdate($bug);


        $this->assertBugDtoIsCreated($bugDto, $bug);
    }

    private function createBug(
        int $bugId,
        Project $project,
        User $reporter,
        User $assignee
    ): Bug {
        $due = new DateTimeImmutable();

        return new Bug(
            $bugId,
            $project,
            $reporter,
            $assignee,
            $due,
            0,
            0,
            'Title',
            'Sum',
            'Rep',
            'Exp',
            'Act',
        );
    }

    private function assertBugDtoIsCreated(BugUpdateDto $bugDto, Bug $bug): void
    {
        if (!$bugDto->due instanceof DateTimeInterface) {
            throw new InvalidArgumentException('BugDto must have a due.');
        }

        self::assertSame($bug->getStatus(), $bugDto->status);
        self::assertSame($bug->getPriority(), $bugDto->priority);
        self::assertSame(
            $bug->getDue()->format('Y-m-d'),
            $bugDto->due->format('Y-m-d'),
        );
        self::assertSame($bug->getTitle(), $bugDto->title);
        self::assertSame($bug->getSummary(), $bugDto->summary);
        self::assertSame($bug->getReproduce(), $bugDto->reproduce);
        self::assertSame($bug->getExpected(), $bugDto->expected);
        self::assertSame($bug->getActual(), $bugDto->actual);
        self::assertSame($bug->getAssignee(), $bugDto->assignee);
    }
}
