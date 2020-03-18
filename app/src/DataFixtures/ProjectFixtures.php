<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;

final class ProjectFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager): void
    {
        $amountOfProjects = 3;
        $this->createProjects($amountOfProjects);

        $manager->flush();
    }

    private function createProjects(int $amountOfProjects): void
    {
        for ($i = 0; $i < $amountOfProjects; $i++) {
            $projectId = sprintf('P%s', $i);
            $name = sprintf('Project_%s', $i);
            $this->createProject($projectId, $name);
        }
    }

    private function createProject(string $projectId, string $name): void
    {
        $project = new Project($projectId, $name);

        $this->persistAndReference($project, $projectId);
    }

    private function persistAndReference(Project $project, string $projectId): void
    {
        $this->manager->persist($project);

        $referenceName = sprintf('project-%s', $projectId);
        $this->addReference($referenceName, $project);
    }
}
