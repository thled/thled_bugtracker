<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\RegisterUserDto;
use App\Entity\User;

interface UserFactoryInterface
{
    public function createFromRegisterUserDto(RegisterUserDto $userDto): User;
}
