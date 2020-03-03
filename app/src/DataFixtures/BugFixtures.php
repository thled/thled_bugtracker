<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Bug;
use App\Entity\Project;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class BugFixtures extends Fixture implements DependentFixtureInterface
{
    private ObjectManager $manager;

    /** @return array<class-string> */
    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $amountOfBugs = 3;
        $amountOfProjects = 2;
        $this->createBugsForProjects($amountOfBugs, $amountOfProjects);

        $this->manager->flush();
    }

    private function createBugsForProjects(int $amountOfBugs, int $amountOfProjects): void
    {
        for ($projectId = 0; $projectId < $amountOfProjects; $projectId++) {
            $this->createBugsForProject($amountOfBugs, $projectId);
        }
    }

    private function createBugsForProject(int $amountOfBugs, int $projectId): void
    {
        /** @var Project $project */
        $project = $this->getReference(sprintf('project-P%d', $projectId));

        for ($bugId = 0; $bugId < $amountOfBugs; $bugId++) {
            $reporterId = 1;
            $assigneeId = ($projectId * $amountOfBugs) + $bugId;
            $this->createBugForProject($bugId, $project, $reporterId, $assigneeId);
        }
    }

    private function createBugForProject(
        int $bugId,
        Project $project,
        int $reporterId,
        int $assigneeId
    ): void {
        $due = new DateTimeImmutable();

        /** @var User $reporter */
        $reporter = $this->getReference(sprintf('user-po%d', $reporterId));

        /** @var User $assignee */
        $assignee = $this->getReference(sprintf('user-dev%d', $assigneeId));

        $status = 1;
        $priority = 1;
        $title = '';
        $summary = '';
        $reproduce = '';
        $expected = '';
        $actual = '';

        $comments = [];

        $bug = new Bug(
            $bugId,
            $project,
            $due,
            $reporter,
            $assignee,
            $status,
            $priority,
            $title,
            $summary,
            $reproduce,
            $expected,
            $actual,
            $comments,
        );

        $this->manager->persist($bug);
    }
}
