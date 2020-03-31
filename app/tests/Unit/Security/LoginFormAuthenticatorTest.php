<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Security\LoginFormAuthenticator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/** @covers \App\Security\LoginFormAuthenticator */
final class LoginFormAuthenticatorTest extends TestCase
{
    private const CREDENTIALS = [
        'email' => 'fooMail',
        'password' => 'fooPW',
        'csrf_token' => 'fooToken',
    ];

    private LoginFormAuthenticator $loginFormAuthenticator;
    private ObjectProphecy $csrfTokenManager;
    private ObjectProphecy $passwordEncoder;
    private ObjectProphecy $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $this->csrfTokenManager = $this->prophesize(CsrfTokenManagerInterface::class);
        $this->passwordEncoder = $this->prophesize(UserPasswordEncoderInterface::class);

        $this->loginFormAuthenticator = new LoginFormAuthenticator(
            $this->urlGenerator->reveal(),
            $this->csrfTokenManager->reveal(),
            $this->passwordEncoder->reveal(),
        );
    }

    /**
     * @covers \App\Security\LoginFormAuthenticator::supports
     * @dataProvider providerRoutes
     */
    public function testSupports(string $route, string $method, bool $expectedSupport): void
    {
        $request = $this->prophesize(Request::class);

        $parameterBag = $this->prophesize(ParameterBagInterface::class);
        $parameterBag->get('_route')->willReturn($route);
        $request->attributes = $parameterBag;

        $request->isMethod(Argument::any())->willReturn(false);
        $request->isMethod($method)->willReturn(true);

        $isSupported = $this->loginFormAuthenticator->supports($request->reveal());

        self::assertSame($expectedSupport, $isSupported);
    }

    /** @return array<array<string|bool>> */
    public function providerRoutes(): array
    {
        return [
            [
                'route' => 'login',
                'method' => 'POST',
                'isSupported' => true,
            ],
            [
                'route' => 'login',
                'method' => 'GET',
                'isSupported' => false,
            ],
            [
                'route' => 'register',
                'method' => 'POST',
                'isSupported' => false,
            ],
        ];
    }

    /** @covers \App\Security\LoginFormAuthenticator::getCredentials */
    public function testGetCredentials(): void
    {
        $request = $this->prophesize(Request::class);
        $parameterBag = $this->prophesize(ParameterBagInterface::class);
        $parameterBag->get('email')->willReturn(self::CREDENTIALS['email']);
        $parameterBag->get('password')->willReturn(self::CREDENTIALS['password']);
        $parameterBag->get('_csrf_token')->willReturn(self::CREDENTIALS['csrf_token']);
        $request->request = $parameterBag;

        $session = $this->prophesize(Session::class);
        $session->set(Argument::type('string'), Argument::type('string'))
            ->shouldBeCalledOnce();
        $request->getSession()->willReturn($session);

        $actualCredentials = $this->loginFormAuthenticator->getCredentials($request->reveal());

        self::assertSame(self::CREDENTIALS, $actualCredentials);
    }

    /** @covers \App\Security\LoginFormAuthenticator::getUser */
    public function testGetUser(): void
    {
        $userProvider = $this->prophesize(UserProviderInterface::class);
        $expectedUser = $this->prophesize(UserInterface::class);
        $userProvider->loadUserByUsername(self::CREDENTIALS['email'])
            ->willReturn($expectedUser->reveal());

        $this->csrfTokenManager->isTokenValid(Argument::type(CsrfToken::class))
            ->willReturn(true);

        $user = $this->loginFormAuthenticator->getUser(
            self::CREDENTIALS,
            $userProvider->reveal(),
        );

        self::assertSame($expectedUser->reveal(), $user);
    }

    /** @covers \App\Security\LoginFormAuthenticator::getUser */
    public function testGetUserThrowsInvalidCsrfTokenException(): void
    {
        $userProvider = $this->prophesize(UserProviderInterface::class);

        $this->csrfTokenManager->isTokenValid(Argument::type(CsrfToken::class))
            ->willReturn(false);

        $this->expectException(InvalidCsrfTokenException::class);

        $this->loginFormAuthenticator->getUser(self::CREDENTIALS, $userProvider->reveal());
    }

    /**
     * @covers \App\Security\LoginFormAuthenticator::checkCredentials
     * @dataProvider providePasswordValidity
     */
    public function testCheckCredentials(bool $isPasswordValid, bool $expectedPasswordValid): void
    {
        $user = $this->prophesize(UserInterface::class);

        $this->passwordEncoder->isPasswordValid($user, Argument::type('string'))
            ->willReturn($isPasswordValid);

        $credentialsCheck = $this->loginFormAuthenticator->checkCredentials(
            self::CREDENTIALS,
            $user->reveal(),
        );

        self::assertSame($expectedPasswordValid, $credentialsCheck);
    }

    /** @return array<array<bool>> */
    public function providePasswordValidity(): array
    {
        return [
            [
                'isPasswordValid' => true,
                'expectedPasswordValid' => true,
            ],
            [
                'isPasswordValid' => false,
                'expectedPasswordValid' => false,
            ],
        ];
    }

    /** @covers \App\Security\LoginFormAuthenticator::getPassword */
    public function testGetPassword(): void
    {
        $password = $this->loginFormAuthenticator->getPassword(self::CREDENTIALS);

        self::assertSame(self::CREDENTIALS['password'], $password);
    }

    /**
     * @covers \App\Security\LoginFormAuthenticator::onAuthenticationSuccess
     * @dataProvider provideTargetUrl
     */
    public function testOnAuthenticationSuccess(
        ?string $targetUrl,
        string $expectedTargetUrl
    ): void {
        $request = $this->prophesize(Request::class);
        $session = $this->prophesize(Session::class);
        $session->get(Argument::type('string'))->willReturn($targetUrl);
        $request->getSession()->willReturn($session);

        $token = $this->prophesize(TokenInterface::class);
        $providerKey = 'main';

        $this->urlGenerator->generate('index')->willReturn('/');

        $response = $this->loginFormAuthenticator->onAuthenticationSuccess(
            $request->reveal(),
            $token->reveal(),
            $providerKey,
        );

        self::assertSame($expectedTargetUrl, $response->getTargetUrl());
    }

    /** @return array<array<string|null>> */
    public function provideTargetUrl(): array
    {
        return [
            [
                'targetUrl' => '/bug',
                'expectedTargetUrl' => '/bug',
            ],
            [
                'targetUrl' => null,
                'expectedTargetUrl' => '/',
            ],
        ];
    }

    /** @covers \App\Security\LoginFormAuthenticator::getLoginUrl */
    public function testGetLoginUrl(): void
    {
        $targetUrl = '/login';
        $this->urlGenerator->generate('login')->willReturn($targetUrl);

        $request = $this->prophesize(Request::class);

        $response = $this->loginFormAuthenticator->start($request->reveal());

        self::assertSame($targetUrl, $response->getTargetUrl());
    }
}
