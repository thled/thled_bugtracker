<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\ProjectCreateDto;
use App\Entity\Project;

interface ProjectFactoryInterface
{
    public function createFromDto(ProjectCreateDto $projectDto): Project;
}
