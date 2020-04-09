<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\User;
use App\Service\RegistrationService;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/** @covers \App\Service\RegistrationService */
final class RegistrationServiceTest extends TestCase
{
    private RegistrationService $registrationService;
    private ObjectProphecy $passwordEncoder;

    protected function setUp(): void
    {
        $this->passwordEncoder = $this->prophesize(UserPasswordEncoderInterface::class);
        $this->registrationService = new RegistrationService($this->passwordEncoder->reveal());
    }

    public function testEncodePasswordInUser(): void
    {
        $plainPassword = 'admin123';
        $user = new User('foo@example.com');

        $encodedPassword = '321nimda';
        $this->passwordEncoder->encodePassword($user, $plainPassword)
            ->willReturn($encodedPassword);


        $this->registrationService->encodePasswordInUser($user, $plainPassword);


        self::assertSame($encodedPassword, $user->getPassword());
    }
}
