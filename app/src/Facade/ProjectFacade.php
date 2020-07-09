<?php

declare(strict_types=1);

namespace App\Facade;

use App\DataTransferObject\ProjectCreateDto;
use App\DataTransferObject\ProjectUpdateDto;
use App\Entity\Project;
use App\Factory\ProjectDtoFactory;
use App\Factory\ProjectFactoryInterface;
use App\Repository\ProjectRepositoryInterface;
use App\Service\ProjectUpdaterInterface;

final class ProjectFacade implements ProjectFacadeInterface
{
    private ProjectRepositoryInterface $projectRepo;
    private ProjectDtoFactory $projectDtoFactory;
    private ProjectFactoryInterface $projectFactory;
    private ProjectUpdaterInterface $projectUpdater;

    public function __construct(
        ProjectRepositoryInterface $projectRepo,
        ProjectDtoFactory $projectDtoFactory,
        ProjectFactoryInterface $projectFactory,
        ProjectUpdaterInterface $projectUpdater
    ) {
        $this->projectRepo = $projectRepo;
        $this->projectDtoFactory = $projectDtoFactory;
        $this->projectFactory = $projectFactory;
        $this->projectUpdater = $projectUpdater;
    }

    public function mapProjectToUpdateDto(Project $project): ProjectUpdateDto
    {
        return $this->projectDtoFactory->createUpdate($project);
    }

    public function saveProjectFromDto(ProjectCreateDto $projectDto): void
    {
        $project = $this->projectFactory->createFromDto($projectDto);

        $this->projectRepo->save($project);
    }

    public function updateProjectFromDto(Project $project, ProjectUpdateDto $projectDto): void
    {
        $this->projectUpdater->updateFromDto($project, $projectDto);

        $this->projectRepo->save($project);
    }
}
