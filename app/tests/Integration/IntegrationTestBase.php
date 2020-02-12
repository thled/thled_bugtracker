<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class IntegrationTestBase extends KernelTestCase
{
    use FixturesTrait;

    protected ReferenceRepository $fixtures;

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

        self::bootKernel();
    }
}
