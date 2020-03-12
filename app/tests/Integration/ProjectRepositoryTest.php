<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\Project;
use App\Repository\Exception\RecordNotFoundException;
use App\Repository\ProjectRepository;
use Ramsey\Uuid\Uuid;

/** @covers \App\Repository\ProjectRepository */
final class ProjectRepositoryTest extends IntegrationTestBase
{
    private ProjectRepository $projectRepo;

    public function setUp(): void
    {
        parent::setUp();

        $this->projectRepo = $this->entityManager->getRepository(Project::class);
    }

    /** @covers \App\Repository\ProjectRepository::get */
    public function testGetThrowsRecordNotFoundException(): void
    {
        $projectId = Uuid::uuid4();

        $this->expectException(RecordNotFoundException::class);

        $this->projectRepo->get($projectId);
    }
}
