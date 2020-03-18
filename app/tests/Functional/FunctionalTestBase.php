<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\BugFixtures;
use App\DataFixtures\ProjectFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Persistence\ObjectManager;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class FunctionalTestBase extends WebTestCase
{
    use FixturesTrait;

    protected ReferenceRepository $fixtures;
    protected KernelBrowser $client;
    protected ObjectManager $entityManager;

    protected function setUp(): void
    {
        $abstractExecutor = $this->loadFixtures(
            [
                BugFixtures::class,
                ProjectFixtures::class,
                UserFixtures::class,
            ],
        );
        if ($abstractExecutor instanceof AbstractExecutor) {
            $this->fixtures = $abstractExecutor->getReferenceRepository();
        }

        $this->client = self::createClient();
        $this->entityManager = self::$container->get('doctrine')->getManager();
    }

    protected function logIn(User $user): void
    {
        $firewallName = 'main';
        $token = new UsernamePasswordToken(
            $user,
            null,
            $firewallName,
            $user->getRoles(),
        );

        $session = $this->getAndUpdateSessionWithToken($token);

        $this->addNewCookieWithSessionToClient($session);
    }

    private function getAndUpdateSessionWithToken(UsernamePasswordToken $token): Session
    {
        $firewallContext = 'main';
        $session = self::$container->get('session');
        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();

        return $session;
    }

    private function addNewCookieWithSessionToClient(Session $session): void
    {
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
