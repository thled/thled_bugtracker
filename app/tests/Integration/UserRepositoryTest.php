<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\User;
use App\Repository\UserRepository;
use LogicException;

/** @covers \App\Repository\UserRepository */
final class UserRepositoryTest extends IntegrationTestBase
{
    private UserRepository $userRepo;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepo = $this->entityManager->getRepository(User::class);
    }

    /** @covers \App\Repository\UserRepository::upgradePassword */
    public function testUpgradePassword(): void
    {
        $user = $this->userRepo->find(1);
        if (!$user instanceof User) {
            throw new LogicException('No user found.');
        }
        $newEncodedPassword = '321nimda';

        $this->userRepo->upgradePassword($user, $newEncodedPassword);

        $this->entityManager->clear();
        $userWithUpgradedPassword = $this->userRepo->find(1);
        if (!$userWithUpgradedPassword instanceof User) {
            throw new LogicException('No user found.');
        }
        $upgradedPassword = $userWithUpgradedPassword->getPassword();

        self::assertSame($newEncodedPassword, $upgradedPassword);
    }

    /** @covers \App\Repository\UserRepository::save */
    public function testSave(): void
    {
        $user = new User();
        $email = 'foobar@example.com';
        $user->setEmail($email);

        $this->userRepo->save($user);

        $savedUser = $this->userRepo->findOneBy(['email' => $email]);
        self::assertInstanceOf(User::class, $savedUser);
    }
}
