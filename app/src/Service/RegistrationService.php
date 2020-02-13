<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class RegistrationService
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function encodePasswordInUser(User $user, string $passwordPlain): void
    {
        $passwordEncoded = $this->passwordEncoder->encodePassword(
            $user,
            $passwordPlain,
        );
        $user->setPassword($passwordEncoded);
    }
}
