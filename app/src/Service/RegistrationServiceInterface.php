<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;

interface RegistrationServiceInterface
{
    public function encodePasswordInUser(User $user, string $passwordPlain): void;
}
