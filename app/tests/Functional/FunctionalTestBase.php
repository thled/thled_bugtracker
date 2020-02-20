<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class FunctionalTestBase extends WebTestCase
{
    use FixturesTrait;

    protected ReferenceRepository $fixtures;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $abstractExecutor = $this->loadFixtures(
            [
                UserFixtures::class,
            ],
        );
        if ($abstractExecutor instanceof AbstractExecutor) {
            $this->fixtures = $abstractExecutor->getReferenceRepository();
        }

        $this->client = self::createClient();
    }

    protected function logIn(string $username): void
    {
        $session = self::$container->get('session');

        $firewallName = 'main';
        $firewallContext = 'main';

        $token = new UsernamePasswordToken(
            $username,
            null,
            $firewallName,
            ['ROLE_ADMIN'],
        );
        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
