<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\BugCreateDto;
use App\Entity\Bug;

interface BugFactoryInterface
{
    public function createFromDto(BugCreateDto $bugDto): Bug;
}
