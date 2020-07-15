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

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectRepo = $this->entityManager->getRepository(Project::class);
    }

    public function testGet(): void
    {
        $projectFromDb = $this->projectRepo->findOneBy(['projectId' => 'P0']);
        if (!$projectFromDb instanceof Project) {
            self::fail('Cannot find project in DB.');
        }
        $projectId = $projectFromDb->getId();

        $project = $this->projectRepo->get($projectId);

        self::assertSame($projectId, $project->getId());
    }

    public function testGetThrowsRecordNotFoundException(): void
    {
        $projectId = Uuid::uuid4();

        $this->expectException(RecordNotFoundException::class);

        $this->projectRepo->get($projectId);
    }

    public function testSaveTheProjectInTheDb(): void
    {
        $project = new Project('FOO', 'Foo Project');

        $this->projectRepo->save($project);

        $savedProject = $this->projectRepo->findOneBy(['projectId' => 'FOO']);
        self::assertInstanceOf(Project::class, $savedProject);
    }
}
