<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\UserShowDto;
use App\Entity\User;

final class UserDtoFactory implements UserDtoFactoryInterface
{
    public function createShow(User $user): UserShowDto
    {
        $userDto = new UserShowDto();
        $userDto->username = $user->getUsername();
        $userDto->roles = $user->getRoles();

        return $userDto;
    }
}
