<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\User;

class RegistrationControllerTest extends FunctionalTestBase
{
    private const EMAIL = 'foobar@example.com';
    private const PASSWORD = 'admin123';

    public function testRegisterUser(): void
    {
        $this->client->request('GET', '/register');

        $this->client->submitForm(
            'Register',
            [
                'registration_form[email]' => self::EMAIL,
                'registration_form[plainPassword]' => self::PASSWORD,
                'registration_form[agreeTerms]' => '1',
            ],
        );

        self::assertUserIsCreated();
    }

    private function assertUserIsCreated(): void
    {
        $userRepo = self::$container->get('doctrine')->getRepository(User::class);
        $registeredUser = $userRepo->findOneBy(['email' => self::EMAIL]);

        self::assertNotNull($registeredUser, 'Cannot find new registered user.');
    }
}
