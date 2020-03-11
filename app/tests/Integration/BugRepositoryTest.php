<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\Bug;
use App\Entity\Project;
use App\Repository\BugRepository;

/** @covers \App\Repository\BugRepository */
final class BugRepositoryTest extends IntegrationTestBase
{
    private BugRepository $bugRepo;

    public function setUp(): void
    {
        parent::setUp();

        $this->bugRepo = $this->entityManager->getRepository(Bug::class);
    }

    /** @covers \App\Repository\BugRepository::findLatestBugOfProject */
    public function testFindLatestBugOfProject(): void
    {
        /** @var Project $project */
        $project = $this->fixtures->getReference('project-P0');

        $latestBug = $this->bugRepo->findLatestBugOfProject($project);
        if (!$latestBug instanceof Bug) {
            self::fail('Project has no Bugs.');
        }

        /** @var Bug $expectedLatestBug */
        $expectedLatestBug = $this->fixtures->getReference('bug-P0-2');
        self::assertTrue($expectedLatestBug->getId()->equals($latestBug->getId()));
    }

    /** @covers \App\Repository\BugRepository::findLatestBugOfProject */
    public function testFindLatestBugOfProjectWithoutBugs(): void
    {
        /** @var Project $project */
        $project = $this->fixtures->getReference('project-P2');

        $latestBug = $this->bugRepo->findLatestBugOfProject($project);

        self::assertNull($latestBug);
    }
}
