<?php

declare(strict_types=1);

namespace App\Facade;

use App\DataTransferObject\ProjectCreateDto;
use App\DataTransferObject\ProjectUpdateDto;
use App\Entity\Project;

interface ProjectFacadeInterface
{
    public function mapProjectToUpdateDto(Project $project): ProjectUpdateDto;

    public function saveProjectFromDto(ProjectCreateDto $projectDto): void;

    public function updateProjectFromDto(Project $project, ProjectUpdateDto $projectDto): void;
}
