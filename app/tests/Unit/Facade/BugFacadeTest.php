<?php

declare(strict_types=1);

namespace App\Tests\Unit\Facade;

use App\DataTransferObject\BugCreateDto;
use App\DataTransferObject\BugUpdateDto;
use App\Entity\Bug;
use App\Facade\BugFacade;
use App\Factory\BugDtoFactoryInterface;
use App\Factory\BugFactoryInterface;
use App\Repository\BugRepositoryInterface;
use App\Service\BugUpdaterInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/** @covers \App\Facade\BugFacade */
final class BugFacadeTest extends TestCase
{
    private BugFacade $bugFacade;
    private ObjectProphecy $bugDtoFactory;
    private ObjectProphecy $bugRepo;
    private ObjectProphecy $bugFactory;
    private ObjectProphecy $bugUpdater;

    protected function setUp(): void
    {
        $this->bugRepo = $this->prophesize(BugRepositoryInterface::class);
        $this->bugDtoFactory = $this->prophesize(BugDtoFactoryInterface::class);
        $this->bugFactory = $this->prophesize(BugFactoryInterface::class);
        $this->bugUpdater = $this->prophesize(BugUpdaterInterface::class);
        $this->bugFacade = new BugFacade(
            $this->bugRepo->reveal(),
            $this->bugDtoFactory->reveal(),
            $this->bugFactory->reveal(),
            $this->bugUpdater->reveal(),
        );
    }

    public function testMapBugToUpdateDto(): void
    {
        $bug = $this->prophesize(Bug::class);

        $bugDto = new BugUpdateDto();
        $this->bugDtoFactory->createUpdate($bug)->willReturn($bugDto);


        $expectedBugDto = $this->bugFacade->mapBugToUpdateDto($bug->reveal());


        self::assertSame($expectedBugDto, $bugDto);
    }

    public function testSaveBugFromDto(): void
    {
        $bugDto = new BugCreateDto();

        $bug = $this->prophesize(Bug::class);
        $this->bugFactory->createFromDto($bugDto)->willReturn($bug->reveal());


        $this->bugFacade->saveBugFromDto($bugDto);


        $this->bugRepo->save($bug->reveal())->shouldBeCalledOnce();
    }

    public function testUpdateBugFromDto(): void
    {
        $bug = $this->prophesize(Bug::class);
        $bugDto = new BugUpdateDto();


        $this->bugFacade->updateBugFromDto($bug->reveal(), $bugDto);


        $this->bugUpdater->updateFromDto($bug->reveal(), $bugDto)->shouldBeCalledOnce();
        $this->bugRepo->save($bug->reveal())->shouldBeCalledOnce();
    }
}
