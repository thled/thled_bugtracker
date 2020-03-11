<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\User;
use App\Service\RegistrationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/** @covers \App\Service\RegistrationService */
final class RegistrationServiceTest extends TestCase
{
    /** @covers \App\Service\RegistrationService::encodePasswordInUser */
    public function testEncodePasswordInUser(): void
    {
        $passwordEncoder = $this->prophesize(UserPasswordEncoderInterface::class);
        $registrationService = new RegistrationService($passwordEncoder->reveal());

        $plainPassword = 'admin123';
        $user = new User($plainPassword);

        $encodedPassword = '321nimda';
        $passwordEncoder->encodePassword($user, $plainPassword)->willReturn($encodedPassword);

        $registrationService->encodePasswordInUser($user, $plainPassword);

        $userPassword = $user->getPassword();
        self::assertSame($encodedPassword, $userPassword);
    }
}
