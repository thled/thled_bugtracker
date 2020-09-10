<?php

declare(strict_types=1);

namespace App\Facade;

use App\DataTransferObject\UserShowDto;
use App\Entity\User;
use App\Factory\UserDtoFactoryInterface;

final class UserFacade implements UserFacadeInterface
{
    private UserDtoFactoryInterface $userDtoFactory;

    public function __construct(UserDtoFactoryInterface $userDtoFactory)
    {
        $this->userDtoFactory = $userDtoFactory;
    }

    public function mapUserToShowDto(User $user): UserShowDto
    {
        return $this->userDtoFactory->createShow($user);
    }
}
