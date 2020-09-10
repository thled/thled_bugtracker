<?php

declare(strict_types=1);

namespace App\Facade;

use App\DataTransferObject\UserShowDto;
use App\Entity\User;

interface UserFacadeInterface
{
    public function mapUserToShowDto(User $user): UserShowDto;
}
