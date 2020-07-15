<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\ProjectCreateDto;
use App\Entity\Project;

final class ProjectFactory implements ProjectFactoryInterface
{
    public function createFromDto(ProjectCreateDto $projectDto): Project
    {
        return new Project(
            $projectDto->projectId ?? '',
            $projectDto->name ?? '',
        );
    }
}
