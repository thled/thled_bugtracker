<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\DataTransferObject\UserRegisterDto;
use App\Factory\UserFactory;
use App\Service\RegistrationServiceInterface;
use LogicException;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/** @covers \App\Factory\UserFactory */
final class UserFactoryTest extends TestCase
{
    private UserFactory $userFactory;
    private ObjectProphecy $registration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registration = $this->prophesize(RegistrationServiceInterface::class);
        $this->userFactory = new UserFactory($this->registration->reveal());
    }

    /** @covers \App\Factory\UserFactory::createFromDto */
    public function testCreateFromDto(): void
    {
        $userDto = $this->createUserDto();

        $user = $this->userFactory->createFromDto($userDto);

        self::assertSame($userDto->email, $user->getUsername());
    }

    private function createUserDto(): UserRegisterDto
    {
        $userDto = new UserRegisterDto();
        $userDto->email = 'foo@example.com';
        $userDto->plainPassword = 'pa$$word';
        $userDto->agreeTerms = true;

        return $userDto;
    }

    /** @covers \App\Factory\UserFactory::createFromDto */
    public function testCreateFromDtoThrowsLogicException(): void
    {
        $userDto = new UserRegisterDto();

        $this->expectException(LogicException::class);

        $this->userFactory->createFromDto($userDto);
    }
}
