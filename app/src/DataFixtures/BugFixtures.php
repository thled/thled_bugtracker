<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Bug;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use LogicException;

final class BugFixtures extends BaseFixture implements DependentFixtureInterface
{
    /** @return array<class-string> */
    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class,
            UserFixtures::class,
        ];
    }

    public function loadData(ObjectManager $manager): void
    {
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
            $reporterId = 0;
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
        $due = $this->faker->dateTimeBetween('+5 days', '+10 days');

        /** @var User $reporter */
        $reporter = $this->getReference(sprintf('user-po%d', $reporterId));

        /** @var User $assignee */
        $assignee = $this->getReference(sprintf('user-dev%d', $assigneeId));

        $status = 1;
        $priority = 1;
        $title = ucfirst($this->createRandomTitle());
        $summary = $this->createRandomSummary();

        $reproduce = $this->createRandomStepsToReproduce();

        $expected = $this->faker->sentence();
        $actual = $this->faker->sentence();

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

        $this->persistAndReference($bug, $bugId, $project->getProjectId());
    }

    private function createRandomTitle(): string
    {
        $title = $this->faker->words($this->faker->numberBetween(1, 5), true);
        if (is_string($title)) {
            return $title;
        }

        throw new LogicException('Faker returns the wrong type.');
    }

    private function createRandomSummary(): string
    {
        $summary = $this->faker->paragraphs($this->faker->numberBetween(1, 3), true);
        if (is_string($summary)) {
            return $summary;
        }

        throw new LogicException('Faker returns the wrong type.');
    }

    private function createRandomStepsToReproduce(): string
    {
        $stepsToReproduce = $this->faker->sentences($this->faker->numberBetween(3, 5));
        if (!is_array($stepsToReproduce)) {
            throw new LogicException('Faker returns the wrong type.');
        }

        foreach ($stepsToReproduce as $key => $step) {
            $stepsToReproduce[$key] = sprintf('%d. %s', $key + 1, $step);
        }

        return implode("\n", $stepsToReproduce);
    }

    private function persistAndReference(Bug $bug, int $bugId, string $projectId): void
    {
        $this->manager->persist($bug);

        $referenceName = sprintf('bug-%s-%s', $projectId, $bugId);
        $this->setReference($referenceName, $bug);
    }
}
