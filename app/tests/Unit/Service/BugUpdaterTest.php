<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\DataTransferObject\BugUpdateDto;
use App\Entity\Bug;
use App\Entity\Project;
use App\Entity\User;
use App\Service\BugUpdater;
use DateTimeImmutable;
use LogicException;
use PHPUnit\Framework\TestCase;

/** @covers \App\Service\BugUpdater */
final class BugUpdaterTest extends TestCase
{
    private BugUpdater $bugUpdater;

    protected function setUp(): void
    {
        $this->bugUpdater = new BugUpdater();
    }

    public function testUpdateFromDto(): void
    {
        $bugId = 1;
        $project = new Project('P1');
        $reporter = $assignee = new User('foo@example.com');
        $bug = $this->createBug($bugId, $project, $reporter, $assignee);

        $newStatus = 2;
        $newPriority = 3;
        $tomorrow = new DateTimeImmutable('tomorrow');
        $newTitle = 'newTitle';
        $newSummary = 'newSum';
        $newReproduce = 'newRep';
        $newExpected = 'newExp';
        $newActual = 'newAct';
        $newAssignee = new User('bar@example.com');
        $bugDto = $this->createBugDto(
            $newStatus,
            $newPriority,
            $tomorrow,
            $newTitle,
            $newSummary,
            $newReproduce,
            $newExpected,
            $newActual,
            $newAssignee,
        );


        $this->bugUpdater->updateFromDto($bug, $bugDto);


        $this->assertUpdatedBug(
            $bug,
            $bugId,
            $project,
            $newStatus,
            $newPriority,
            $tomorrow,
            $newTitle,
            $newSummary,
            $newReproduce,
            $newExpected,
            $newActual,
            $reporter,
            $newAssignee,
        );
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
            'oldTitle',
            'oldSum',
            'oldRep',
            'oldExp',
            'oldAct',
        );
    }

    private function createBugDto(
        ?int $status,
        ?int $priority,
        ?DateTimeImmutable $due,
        ?string $title,
        ?string $summary,
        ?string $reproduce,
        ?string $expected,
        ?string $actual,
        ?User $assignee
    ): BugUpdateDto {
        $bugDto = new BugUpdateDto();
        $bugDto->status = $status;
        $bugDto->priority = $priority;
        $bugDto->due = $due;
        $bugDto->title = $title;
        $bugDto->summary = $summary;
        $bugDto->reproduce = $reproduce;
        $bugDto->expected = $expected;
        $bugDto->actual = $actual;
        $bugDto->assignee = $assignee;

        return $bugDto;
    }

    private function assertUpdatedBug(
        Bug $bug,
        ?int $bugId,
        ?Project $project,
        ?int $status,
        ?int $priority,
        ?DateTimeImmutable $due,
        ?string $title,
        ?string $summary,
        ?string $reproduce,
        ?string $expected,
        ?string $actual,
        ?User $reporter,
        ?User $assignee
    ): void {
        self::assertSame($bugId, $bug->getBugId());
        self::assertSame($project, $bug->getProject());
        self::assertSame($status, $bug->getStatus());
        self::assertSame($priority, $bug->getPriority());
        self::assertSame($due, $bug->getDue());
        self::assertSame($title, $bug->getTitle());
        self::assertSame($summary, $bug->getSummary());
        self::assertSame($reproduce, $bug->getReproduce());
        self::assertSame($expected, $bug->getExpected());
        self::assertSame($actual, $bug->getActual());
        self::assertSame($reporter, $bug->getReporter());
        self::assertSame($assignee, $bug->getAssignee());
        self::assertSame([], $bug->getComments());
    }

    public function testUpdateFromDtoNoDue(): void
    {
        $bug = $this->createBug(
            1,
            new Project('P1'),
            new User('a@b.c'),
            new User('d@e.f'),
        );

        $due = null;
        $bugDto = $this->createBugDto(
            0,
            0,
            $due,
            'title',
            'summary',
            'reproduce',
            'expected',
            'actual',
            new User('foo@example.com'),
        );

        $this->bugUpdater->updateFromDto($bug, $bugDto);

        self::assertSame('1970-01-01', $bug->getDue()->format('Y-m-d'));
    }

    public function testUpdateFromDtoThrowsLogicException(): void
    {
        $bug = $this->createBug(
            1,
            new Project('P1'),
            new User('a@b.c'),
            new User('d@e.f'),
        );
        $bugDto = new BugUpdateDto();
        $bugDto->assignee = null;

        $this->expectException(LogicException::class);

        $this->bugUpdater->updateFromDto($bug, $bugDto);
    }
}
