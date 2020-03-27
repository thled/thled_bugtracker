<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\UserRegisterDto;
use App\Entity\User;

interface UserFactoryInterface
{
    public function createFromDto(UserRegisterDto $userDto): User;
}
