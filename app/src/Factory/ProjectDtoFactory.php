<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\ProjectUpdateDto;
use App\Entity\Project;

final class ProjectDtoFactory implements ProjectDtoFactoryInterface
{
    public function createUpdate(Project $project): ProjectUpdateDto
    {
        $projectDto = new ProjectUpdateDto();
        $projectDto->name = $project->getName();

        return $projectDto;
    }
}
