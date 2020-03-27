<?php

declare(strict_types=1);

namespace App\Facade;

use App\DataTransferObject\BugCreateDto;
use App\DataTransferObject\BugUpdateDto;
use App\Entity\Bug;

interface BugFacadeInterface
{
    public function mapBugToUpdateDto(Bug $bug): BugUpdateDto;

    public function saveBugFromDto(BugCreateDto $bugDto): void;

    public function updateBugFromDto(Bug $bug, BugUpdateDto $bugDto): void;
}
