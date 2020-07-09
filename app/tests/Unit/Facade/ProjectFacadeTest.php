<?php

declare(strict_types=1);

namespace App\Tests\Unit\Facade;

use App\DataTransferObject\ProjectCreateDto;
use App\DataTransferObject\ProjectUpdateDto;
use App\Entity\Project;
use App\Facade\ProjectFacade;
use App\Facade\ProjectFacadeInterface;
use App\Factory\ProjectDtoFactoryInterface;
use App\Factory\ProjectFactoryInterface;
use App\Repository\ProjectRepositoryInterface;
use App\Service\ProjectUpdaterInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/** @covers \App\Facade\ProjectFacade */
final class ProjectFacadeTest extends TestCase
{
    private ProjectFacadeInterface $projectFacade;
    private ObjectProphecy $projectDtoFactory;
    private ObjectProphecy $projectRepo;
    private ObjectProphecy $projectFactory;
    private ObjectProphecy $projectUpdater;

    protected function setUp(): void
    {
        $this->projectRepo = $this->prophesize(ProjectRepositoryInterface::class);
        $this->projectDtoFactory = $this->prophesize(ProjectDtoFactoryInterface::class);
        $this->projectFactory = $this->prophesize(ProjectFactoryInterface::class);
        $this->projectUpdater = $this->prophesize(ProjectUpdaterInterface::class);
        $this->projectFacade = new ProjectFacade(
            $this->projectRepo->reveal(),
            $this->projectDtoFactory->reveal(),
            $this->projectFactory->reveal(),
            $this->projectUpdater->reveal(),
        );
    }

    public function testMapProjectToUpdateDto(): void
    {
        $project = new Project('FOO', 'Foo Project');
        $expectedProjectDto = new ProjectUpdateDto();
        $this->projectDtoFactory->createUpdate($project)->willReturn($expectedProjectDto);

        $projectDto = $this->projectFacade->mapProjectToUpdateDto($project);

        self::assertSame($expectedProjectDto, $projectDto, 'Mapping from Project to UpdateDto failed.');
    }

    public function testSaveProjectFromDto(): void
    {
        $projectDto = new ProjectCreateDto();
        $project = new Project('FOO', 'Foo Project');
        $this->projectFactory->createFromDto($projectDto)->willReturn($project);

        $this->projectFacade->saveProjectFromDto($projectDto);

        $this->projectRepo->save($project)->shouldBeCalledOnce();
    }

    public function testUpdateProjectFromDto(): void
    {
        $project = new Project('FOO', 'Foo Project');
        $projectDto = new ProjectUpdateDto();

        $this->projectFacade->updateProjectFromDto($project, $projectDto);

        $this->projectUpdater->updateFromDto($project, $projectDto)->shouldBeCalledOnce();
        $this->projectRepo->save($project)->shouldBeCalledOnce();
    }
}
