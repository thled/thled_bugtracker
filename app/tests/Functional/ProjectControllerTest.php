<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Project;

/** @covers \App\Controller\ProjectController */
final class ProjectControllerTest extends FunctionalTestBase
{
    public function testListAmount(): void
    {
        $this->logInAsPo();

        $this->client->request('GET', '/project/list');

        $this->assertAmountOfProjects();
    }

    private function assertAmountOfProjects(): void
    {
        $crawler = $this->client->getCrawler();
        $tableRows = $crawler->filter('table tbody tr');
        self::assertCount(
            3,
            $tableRows,
            'Project list does not contain right amount of Projects.',
        );
    }

    public function testListProjectProps(): void
    {
        $this->logInAsPo();

        $this->client->request('GET', '/project/list');

        $this->assertProjectProps();
    }

    private function assertProjectProps(): void
    {
        /** @var Project $project */
        $project = $this->fixtures->getReference('project-P0');
        $content = $this->client->getResponse()->getContent();
        if (!is_string($content)) {
            self::fail('No response content.');
        }

        self::assertStringContainsString(
            $project->getProjectId(),
            $content,
            'Project list does not contain #.',
        );
        self::assertStringContainsString(
            $project->getName(),
            $content,
            'Project list does not contain name.',
        );
    }

    public function testAdd(): void
    {
        $this->logInAsPo();
        $this->client->request('GET', '/project/add');
        $projectToAdd = new Project(
            'FOO',
            'Foo Project',
        );

        $this->submitCreateFormWithProject($projectToAdd);

        $this->assertProjectInDb($projectToAdd);
    }

    private function submitCreateFormWithProject(Project $projectToAdd): void
    {
        $this->client->submitForm(
            'Create',
            [
                'project_create[projectId]' => $projectToAdd->getProjectId(),
                'project_create[name]' => $projectToAdd->getName(),
            ],
        );
    }

    private function assertProjectInDb(Project $project): void
    {
        $projectDb = $this->getProjectInDb($project);
        self::assertSame($project->getProjectId(), $projectDb->getProjectId());
        self::assertSame($project->getName(), $projectDb->getName());
    }

    private function getProjectInDb(Project $projectToAdd): Project
    {
        $projectRepo = $this->manager->getRepository(Project::class);

        $projectDb = $projectRepo->findOneBy(['projectId' => $projectToAdd->getProjectId()]);
        if (!$projectDb instanceof Project) {
            self::fail('Cannot find Project in DB.');
        }

        return $projectDb;
    }

    public function testAddValidation(): void
    {
        $this->logInAsPo();
        $this->client->request('GET', '/project/add');

        $tooLongProjectId = 'TooLongProjectId';
        $tooLongName =
            'This Name is over 128 characters long and therefor too long!
            The validator should mark this as invalid.
            Another sentence too reach the limit.';


        $this->submitCreateFormWithInvalidInputs(
            $tooLongProjectId,
            $tooLongName,
        );


        $this->assertViolations();
    }

    private function submitCreateFormWithInvalidInputs(
        string $tooLongProjectId,
        string $tooLongName
    ): void {
        $this->client->submitForm(
            'Create',
            [
                'project_create[projectId]' => $tooLongProjectId,
                'project_create[name]' => $tooLongName,
            ],
        );
    }

    private function assertViolations(): void
    {
        $content = $this->client->getResponse()->getContent();
        if (!is_string($content)) {
            self::fail('No response content.');
        }

        $violationTooLongProjectId = 'ID cannot be longer than 5 characters.';
        self::assertStringContainsString(
            $violationTooLongProjectId,
            $content,
            sprintf('Validation for "%s" violation failed.', $violationTooLongProjectId),
        );

        $violationTooLongName = 'Name cannot be longer than 128 characters.';
        self::assertStringContainsString(
            $violationTooLongName,
            $content,
            sprintf('Validation for "%s" violation failed.', $violationTooLongName),
        );
    }

    public function testEdit(): void
    {
        $this->logInAsPo();

        /** @var Project $project */
        $project = $this->fixtures->getReference('project-P0');
        $this->client->request('GET', '/project/edit/' . $project->getId());

        $newName = 'newName';


        $this->submitEditFormWithProjectAndNewName($newName);


        $project->setName($newName);
        $this->assertProjectInDb($project);
    }

    private function submitEditFormWithProjectAndNewName(string $newTitle): void
    {
        $this->client->submitForm(
            'Update',
            ['project_edit[name]' => $newTitle],
        );
    }
}
