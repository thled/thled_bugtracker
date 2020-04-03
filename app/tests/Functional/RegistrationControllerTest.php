<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\User;

/** @covers \App\Controller\RegistrationController */
final class RegistrationControllerTest extends FunctionalTestBase
{
    private const EMAIL = 'foobar@example.com';
    private const PASSWORD = 'admin123';

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

    /** @dataProvider provideViolations */
    public function testRegisterUserValidation(
        string $email,
        string $password,
        bool $agree,
        string $violation
    ): void {
        $this->client->request('GET', '/register');

        $fieldValues = [
            'registration[email]' => $email,
            'registration[plainPassword]' => $password,
        ];
        if ($agree) {
            $fieldValues['registration[agreeTerms]'] = '1';
        }

        $this->client->submitForm('Register', $fieldValues);

        self::assertStringContainsString(
            $violation,
            $this->client->getResponse()->getContent(),
            sprintf('Validation for "%s" violation failed.', $violation),
        );
    }

    /** @return array<array<string|bool>> */
    public function provideViolations(): array
    {
        return [
            [
                'email' => '',
                'password' => self::PASSWORD,
                'agree' => true,
                'violation' => 'Email is required.',
            ],
            [
                'email' => 'fooexample.com',
                'password' => self::PASSWORD,
                'agree' => true,
                'violation' => 'Email needs to be a valid email address.',
            ],
            [
                'email' => 'admin@example.com',
                'password' => self::PASSWORD,
                'agree' => true,
                'violation' => 'There is already an account with this email.',
            ],
            [
                'email' => self::EMAIL,
                'password' => '',
                'agree' => true,
                'violation' => 'Please enter a password.',
            ],
            [
                'email' => self::EMAIL,
                'password' => 'foo',
                'agree' => true,
                'violation' => 'Your password should be at least 6 characters.',
            ],
            [
                'email' => self::EMAIL,
                'password' => self::PASSWORD,
                'agree' => false,
                'violation' => 'You should agree to our terms.',
            ],
        ];
    }
}
