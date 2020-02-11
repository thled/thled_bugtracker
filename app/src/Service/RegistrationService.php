<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class RegistrationService
{
    private EntityManagerInterface $em;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function saveUser(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
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
