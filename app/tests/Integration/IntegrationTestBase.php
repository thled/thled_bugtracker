<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\DataFixtures\BugFixtures;
use App\DataFixtures\ProjectFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Persistence\ObjectManager;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class IntegrationTestBase extends KernelTestCase
{
    use FixturesTrait;

    protected ReferenceRepository $fixtures;
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

        self::bootKernel();

        $this->entityManager = self::$container
            ->get('doctrine')
            ->getManager();
    }
}
