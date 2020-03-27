<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\DataTransferObject\BugCreateDto;
use App\Entity\Bug;
use App\Entity\Project;
use App\Entity\User;
use App\Factory\BugFactory;
use App\Repository\BugRepositoryInterface;
use DateTimeImmutable;
use LogicException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

/** @covers \App\Factory\BugFactory */
final class BugFactoryTest extends TestCase
{
    private BugFactory $bugFactory;
    private ObjectProphecy $bugRepo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bugRepo = $this->prophesize(BugRepositoryInterface::class);
        $this->bugFactory = new BugFactory($this->bugRepo->reveal());
    }

    /** @covers \App\Factory\BugFactory::createFromDto */
    public function testCreateFromDto(): void
    {
        $this->bugRepo
            ->findLatestBugOfProject(Argument::type(Project::class))
            ->willReturn(null);

        $bugDto = $this->createBugDto();

        $bug = $this->bugFactory->createFromDto($bugDto);

        $this->assertBugIsCreated($bug, $bugDto);
    }

    private function createBugDto(): BugCreateDto
    {
        $bugDto = new BugCreateDto();
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

    private function assertBugIsCreated(
        Bug $bug,
        BugCreateDto $bugDto,
        int $bugId = 1
    ): void {
        self::assertSame($bugId, $bug->getBugId());
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

    /** @covers \App\Factory\BugFactory::createFromDto */
    public function testCreateFromDtoAsSecondBug(): void
    {
        $firstBug = $this->prophesize(Bug::class);
        $firstBug->getBugId()->willReturn(1);

        $this->bugRepo
            ->findLatestBugOfProject(Argument::type(Project::class))
            ->willReturn($firstBug->reveal());

        $bugDto = $this->createBugDto();

        $bug = $this->bugFactory->createFromDto($bugDto);

        $this->assertBugIsCreated($bug, $bugDto, 2);
    }

    /** @covers \App\Factory\BugFactory::createFromDto */
    public function testCreateFromDtoThrowsLogicException(): void
    {
        $bugDto = new BugCreateDto();

        $this->expectException(LogicException::class);

        $this->bugFactory->createFromDto($bugDto);
    }
}
