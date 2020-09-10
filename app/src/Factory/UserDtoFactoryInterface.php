<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\UserShowDto;
use App\Entity\User;

interface UserDtoFactoryInterface
{
    public function createShow(User $user): UserShowDto;
}
