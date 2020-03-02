<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\DataTransferObject\CreateBugDto;
use App\Entity\Bug;
use App\Entity\Project;
use App\Entity\User;
use App\Factory\BugFactory;
use App\Repository\BugRepositoryInterface;
use DateTimeImmutable;
use LogicException;
use PHPUnit\Framework\TestCase;

/** @covers \App\Factory\BugFactory */
final class BugFactoryTest extends TestCase
{
    private BugFactory $bugFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $bugRepo = $this->prophesize(BugRepositoryInterface::class);
        $this->bugFactory = new BugFactory($bugRepo->reveal());
    }

    /** @covers \App\Factory\BugFactory::createFromCreateBugDto */
    public function testCreateFromCreateBugDto(): void
    {
        $bugDto = $this->createBugDto();

        $bug = $this->bugFactory->createFromCreateBugDto($bugDto);

        $this->assertBugIsCreated($bug, $bugDto);
    }

    private function createBugDto(): CreateBugDto
    {
        $bugDto = new CreateBugDto();
        $bugDto->project = $this->prophesize(Project::class)->reveal();
        $bugDto->status = 1;
        $bugDto->priority = 2;
        $bugDto->due = $this->prophesize(DateTimeImmutable::class)->reveal();
        $bugDto->title = 'fooTitle';
        $bugDto->summary = 'fooSum';
        $bugDto->reproduce = 'fooRep';
        $bugDto->expected = 'fooExp';
        $bugDto->actual = 'fooAct';
        $bugDto->reporter = $this->prophesize(User::class)->reveal();
        $bugDto->assignee = $this->prophesize(User::class)->reveal();

        return $bugDto;
    }

    private function assertBugIsCreated(Bug $bug, CreateBugDto $bugDto): void
    {
        self::assertSame(1, $bug->getBugId());
        self::assertSame($bugDto->project, $bug->getProject());
        self::assertSame($bugDto->status, $bug->getStatus());
        self::assertSame($bugDto->priority, $bug->getPriority());
        self::assertSame($bugDto->due, $bug->getDue());
        self::assertSame($bugDto->title, $bug->getTitle());
        self::assertSame($bugDto->summary, $bug->getSummary());
        self::assertSame($bugDto->reproduce, $bug->getReproduce());
        self::assertSame($bugDto->expected, $bug->getExpected());
        self::assertSame($bugDto->actual, $bug->getActual());
        self::assertSame($bugDto->reporter, $bug->getReporter());
        self::assertSame($bugDto->assignee, $bug->getAssignee());
    }

    /** @covers \App\Factory\BugFactory::createFromCreateBugDto */
    public function testCreateFromCreateBugDtoThrowsLogicException(): void
    {
        $bugDto = new CreateBugDto();

        $this->expectException(LogicException::class);

        $this->bugFactory->createFromCreateBugDto($bugDto);
    }
}
