<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\User;

/** @covers \App\Controller\RegistrationController */
final class RegistrationControllerTest extends FunctionalTestBase
{
    private const EMAIL = 'foobar@example.com';
    private const PASSWORD = 'admin123';

    /** @covers \App\Controller\RegistrationController::register */
    public function testRegisterUser(): void
    {
        $this->client->request('GET', '/register');

        $this->client->submitForm(
            'Register',
            [
                'registration[email]' => self::EMAIL,
                'registration[plainPassword]' => self::PASSWORD,
                'registration[agreeTerms]' => '1',
            ],
        );

        $this->assertUserIsCreated();
    }

    private function assertUserIsCreated(): void
    {
        $userRepo = self::$container->get('doctrine')->getRepository(User::class);
        $registeredUser = $userRepo->findOneBy(['email' => self::EMAIL]);

        self::assertNotNull($registeredUser, 'Cannot find new registered user.');
    }
}
