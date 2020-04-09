<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\UserRegisterDto;
use App\Entity\User;
use App\Service\RegistrationServiceInterface;
use LogicException;

final class UserFactory implements UserFactoryInterface
{
    private RegistrationServiceInterface $registration;

    public function __construct(RegistrationServiceInterface $registration)
    {
        $this->registration = $registration;
    }

    public function createFromDto(UserRegisterDto $userDto): User
    {
        $email = $userDto->email;
        $plainPassword = $userDto->plainPassword;

        if (!is_string($email) || !is_string($plainPassword)) {
            throw new LogicException('Form validation failed.');
        }

        $user = new User($email);
        $this->registration->encodePasswordInUser($user, $plainPassword);

        return $user;
    }
}
