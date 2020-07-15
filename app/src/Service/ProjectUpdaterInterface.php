<?php

declare(strict_types=1);

namespace App\Service;

use App\DataTransferObject\ProjectUpdateDto;
use App\Entity\Project;

interface ProjectUpdaterInterface
{
    public function updateFromDto(Project $project, ProjectUpdateDto $projectDto): void;
}
