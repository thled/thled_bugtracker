<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\User;

class SecurityControllerTest extends FunctionalTestBase
{
    private const PASSWORD = 'admin123';

    public function testLogin(): void
    {
        $this->client->request('GET', '/login');

        /** @var User $user */
        $user = $this->fixtures->getReference('user-admin');

        $this->client->submitForm(
            'Sign in',
            [
                'email' => $user->getEmail(),
                'password' => self::PASSWORD,
            ],
        );

        self::assertTrue($this->client->getResponse()->isRedirect('/'));
    }

    public function testLogout(): void
    {
        $this->logIn('admin@example.com');

        $this->client->request('GET', '/logout');

        $this->client->request('GET', '/');
        self::assertTrue($this->client->getResponse()->isRedirect('/login'));
    }
}
