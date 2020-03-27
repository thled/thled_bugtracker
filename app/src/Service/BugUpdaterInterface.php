<?php

declare(strict_types=1);

namespace App\Service;

use App\DataTransferObject\BugUpdateDto;
use App\Entity\Bug;

interface BugUpdaterInterface
{
    public function updateFromDto(Bug $bug, BugUpdateDto $bugDto): void;
}
