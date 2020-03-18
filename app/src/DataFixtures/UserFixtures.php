<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends BaseFixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->createAdmin();

        $amountOfProjectOwners = 2;
        $this->createProjectOwners($amountOfProjectOwners);

        $amountOfDevelopers = 8;
        $this->createDevelopers($amountOfDevelopers);

        $manager->flush();
    }

    private function createAdmin(): void
    {
        $email = 'admin@example.com';
        $plainPassword = 'admin123';
        $roles = ['ROLE_ADMIN'];

        $this->createUser($email, $plainPassword, $roles);
    }

    private function createProjectOwners(int $amountOfPos): void
    {
        for ($i = 0; $i < $amountOfPos; $i++) {
            $name = sprintf('po%s', $i);
            $this->createProjectOwner($name);
        }
    }

    private function createProjectOwner(string $name): void
    {
        $email = sprintf('%s@example.com', $name);
        $plainPassword = 'admin123';
        $roles = ['ROLE_PO'];

        $this->createUser($email, $plainPassword, $roles);
    }

    private function createDevelopers(int $amountOfDevs): void
    {
        for ($i = 0; $i < $amountOfDevs; $i++) {
            $name = sprintf('dev%s', $i);
            $this->createDeveloper($name);
        }
    }

    private function createDeveloper(string $name): void
    {
        $email = sprintf('%s@example.com', $name);
        $plainPassword = 'admin123';
        $roles = ['ROLE_DEV'];

        $this->createUser($email, $plainPassword, $roles);
    }

    /** @param array<string> $roles */
    private function createUser(
        string $email,
        string $plainPassword,
        array $roles
    ): void {
        $user = new User($email, $roles);
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);

        $this->persistAndReference($user, $email);
    }

    private function persistAndReference(User $user, string $email): void
    {
        $this->manager->persist($user);

        $nameAndDomain = explode('@', $email);
        $name = reset($nameAndDomain);
        $referenceName = sprintf('user-%s', $name);
        $this->addReference($referenceName, $user);
    }
}
