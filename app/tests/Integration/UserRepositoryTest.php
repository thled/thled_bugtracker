<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\User;
use App\Repository\Exception\RecordNotFoundException;
use App\Repository\UserRepository;
use LogicException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

/** @covers \App\Repository\UserRepository */
final class UserRepositoryTest extends IntegrationTestBase
{
    private UserRepository $userRepo;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepo = $this->entityManager->getRepository(User::class);
    }

    /** @covers \App\Repository\UserRepository::save */
    public function testSave(): void
    {
        $email = 'foobar@example.com';
        $user = new User($email);

        $this->userRepo->save($user);

        $savedUser = $this->userRepo->findOneBy(['email' => $email]);
        self::assertInstanceOf(User::class, $savedUser);
    }

    /** @covers \App\Repository\UserRepository::upgradePassword */
    public function testUpgradePassword(): void
    {
        $user = $this->getAdmin();
        $newEncodedPassword = '321nimda';

        $this->userRepo->upgradePassword($user, $newEncodedPassword);

        $this->assertPasswordUpgraded($newEncodedPassword, $user);
    }

    private function getAdmin(): User
    {
        /** @var User $userFixture */
        $userFixture = $this->fixtures->getReference('user-admin');

        return $this->userRepo->get($userFixture->getId());
    }

    private function assertPasswordUpgraded(string $newEncodedPassword, User $user): void
    {
        $this->entityManager->clear();
        $userWithUpgradedPassword = $this->userRepo->find($user->getId());
        if (!$userWithUpgradedPassword instanceof User) {
            throw new LogicException('No user found.');
        }
        $upgradedPassword = $userWithUpgradedPassword->getPassword();

        self::assertSame($newEncodedPassword, $upgradedPassword);
    }

    /** @covers \App\Repository\UserRepository::upgradePassword */
    public function testUpgradePasswordThrowsUnsupportedUserException(): void
    {
        $user = $this->prophesize(UserInterface::class);

        $this->expectException(UnsupportedUserException::class);

        $this->userRepo->upgradePassword($user->reveal(), '');
    }

    /** @covers \App\Repository\UserRepository::get */
    public function testGetThrowsRecordNotFoundException(): void
    {
        $userId = Uuid::uuid4();

        $this->expectException(RecordNotFoundException::class);

        $this->userRepo->get($userId);
    }
}
