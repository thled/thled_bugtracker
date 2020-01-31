<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalTestBase extends WebTestCase
{
    use FixturesTrait;

    protected ReferenceRepository $fixtures;
    protected KernelBrowser $client;

    public function setUp(): void
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
}
