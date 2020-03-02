<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\CreateBugDto;
use App\Entity\Bug;

interface BugFactoryInterface
{
    public function createFromCreateBugDto(CreateBugDto $bugDto): Bug;
}
