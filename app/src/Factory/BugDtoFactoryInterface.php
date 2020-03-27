<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\BugUpdateDto;
use App\Entity\Bug;

interface BugDtoFactoryInterface
{
    public function createUpdate(Bug $bug): BugUpdateDto;
}
