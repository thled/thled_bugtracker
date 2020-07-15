<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\ProjectUpdateDto;
use App\Entity\Project;

interface ProjectDtoFactoryInterface
{
    public function createUpdate(Project $project): ProjectUpdateDto;
}
