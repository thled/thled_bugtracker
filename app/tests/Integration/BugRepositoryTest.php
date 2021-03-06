<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\Bug;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\BugRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;

/** @covers \App\Repository\BugRepository */
final class BugRepositoryTest extends IntegrationTestBase
{
    private BugRepository $bugRepo;
    private ProjectRepository $projectRepo;
    private UserRepository $userRepo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bugRepo = $this->entityManager->getRepository(Bug::class);
        $this->projectRepo = $this->entityManager->getRepository(Project::class);
        $this->userRepo = $this->entityManager->getRepository(User::class);
    }

    public function testSave(): void
    {
        $bugId = 1;
        $project = $this->getProject();
        $reporter = $this->getUser('user-po0');
        $assignee = $this->getUser('user-dev6');
        $due = new DateTimeImmutable();
        $bug = new Bug(
            $bugId,
            $project,
            $reporter,
            $assignee,
            $due,
        );

        $this->bugRepo->save($bug);

        $savedBug = $this->bugRepo->findOneBy(['bugId' => $bugId, 'project' => $project]);
        self::assertInstanceOf(Bug::class, $savedBug);
    }

    private function getProject(): Project
    {
        /** @var Project $projectFixture */
        $projectFixture = $this->fixtures->getReference('project-P2');

        return $this->projectRepo->get($projectFixture->getId());
    }

    private function getUser(string $refName): User
    {
        /** @var User $reporterFixture */
        $reporterFixture = $this->fixtures->getReference($refName);

        return $this->userRepo->get($reporterFixture->getId());
    }

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

    public function testFindLatestBugOfProjectWithoutBugs(): void
    {
        /** @var Project $project */
        $project = $this->fixtures->getReference('project-P2');

        $latestBug = $this->bugRepo->findLatestBugOfProject($project);

        self::assertNull($latestBug);
    }
}
