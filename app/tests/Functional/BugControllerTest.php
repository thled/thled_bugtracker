<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Bug;
use App\Entity\Project;
use App\Entity\User;
use DateTimeImmutable;

/** @covers \App\Controller\BugController */
final class BugControllerTest extends FunctionalTestBase
{
    public function testListAmount(): void
    {
        $this->logInAsPo();

        $this->client->request('GET', '/bug/list');

        $this->assertAmountOfBugs();
    }

    private function assertAmountOfBugs(): void
    {
        $crawler = $this->client->getCrawler();
        $tableRows = $crawler->filter('table tbody tr');
        self::assertCount(
            6,
            $tableRows,
            'Bug list does not contain right amount of Bugs.',
        );
    }

    public function testListBugProps(): void
    {
        $this->logInAsPo();

        $this->client->request('GET', '/bug/list');

        $this->assertBugProps();
    }

    private function assertBugProps(): void
    {
        /** @var Bug $bug */
        $bug = $this->fixtures->getReference('bug-P0-0');
        $content = $this->client->getResponse()->getContent();
        if (!is_string($content)) {
            self::fail('No response content.');
        }

        $id = sprintf(
            '%s-%s',
            $bug->getProject()->getProjectId(),
            $bug->getBugId(),
        );
        self::assertStringContainsString($id, $content, 'Bug list does not contain #.');
        self::assertStringContainsString(
            $bug->getTitle(),
            $content,
            'Bug list does not contain title.',
        );
        self::assertStringContainsString(
            $bug->getAssignee()->getUsername(),
            $content,
            'Bug list does not contain assignee.',
        );
        self::assertStringContainsString(
            (string)$bug->getStatus(),
            $content,
            'Bug list does not contain status.',
        );
        self::assertStringContainsString(
            (string)$bug->getPriority(),
            $content,
            'Bug list does not contain priority.',
        );
        self::assertStringContainsString(
            $bug->getDue()->format('m/d, &#039;y'),
            $content,
            'Bug list does not contain due date.',
        );
    }

    public function testAdd(): void
    {
        $this->logInAsPo();
        $this->client->request('GET', '/bug/add');

        $bugToAdd = $this->createBug();


        $this->submitCreateFormWithBug($bugToAdd);


        $this->assertBugInDb($bugToAdd);
    }

    private function createBug(): Bug
    {
        $bugId = 1;
        /** @var Project $project */
        $project = $this->fixtures->getReference('project-P2');
        /** @var User $reporter */
        $reporter = $this->fixtures->getReference('user-po0');
        /** @var User $assignee */
        $assignee = $this->fixtures->getReference('user-dev6');
        $due = new DateTimeImmutable();

        return new Bug(
            $bugId,
            $project,
            $reporter,
            $assignee,
            $due,
        );
    }

    private function submitCreateFormWithBug(Bug $bugToAdd): void
    {
        $this->client->submitForm(
            'Create',
            [
                'bug_create[project]' => $bugToAdd->getProject()->getId(),
                'bug_create[status]' => $bugToAdd->getStatus(),
                'bug_create[priority]' => $bugToAdd->getPriority(),
                'bug_create[due][month]' => (int)$bugToAdd->getDue()->format('m'),
                'bug_create[due][day]' => (int)$bugToAdd->getDue()->format('d'),
                'bug_create[due][year]' => (int)$bugToAdd->getDue()->format('Y'),
                'bug_create[title]' => $bugToAdd->getTitle(),
                'bug_create[summary]' => $bugToAdd->getSummary(),
                'bug_create[reproduce]' => $bugToAdd->getReproduce(),
                'bug_create[expected]' => $bugToAdd->getExpected(),
                'bug_create[actual]' => $bugToAdd->getActual(),
                'bug_create[assignee]' => $bugToAdd->getAssignee()->getId(),
            ],
        );
    }

    private function assertBugInDb(Bug $bug): void
    {
        $bugDb = $this->findBugInDb($bug);
        if (!$bugDb instanceof Bug) {
            self::fail('Cannot find Bug in DB.');
        }

        self::assertSame($bug->getBugId(), $bugDb->getBugId());
        self::assertTrue($bug->getProject()->getId()->equals($bugDb->getProject()->getId()));
        self::assertSame($bug->getStatus(), $bugDb->getStatus());
        self::assertSame($bug->getPriority(), $bugDb->getPriority());
        self::assertSame(
            $bug->getDue()->format('Ymd'),
            $bugDb->getDue()->format('Ymd'),
        );
        self::assertSame($bug->getTitle(), $bugDb->getTitle());
        self::assertSame($bug->getSummary(), $bugDb->getSummary());
        self::assertSame($bug->getReproduce(), $bugDb->getReproduce());
        self::assertSame($bug->getExpected(), $bugDb->getExpected());
        self::assertSame($bug->getActual(), $bugDb->getActual());
        self::assertTrue($bug->getReporter()->getId()->equals($bugDb->getReporter()->getId()));
        self::assertTrue($bug->getAssignee()->getId()->equals($bugDb->getAssignee()->getId()));
    }

    private function findBugInDb(Bug $bugToAdd): ?Bug
    {
        $bugRepo = $this->entityManager->getRepository(Bug::class);

        return $bugRepo->findOneBy(
            [
                'project' => $bugToAdd->getProject(),
                'bugId' => $bugToAdd->getBugId(),
            ],
        );
    }

    public function testAddValidation(): void
    {
        $this->logInAsPo();
        $this->client->request('GET', '/bug/add');

        $blankProject = '';
        $nonExistingStatus = 42;
        $nonExistingPriority = 42;
        $tooLongTitle =
            'This title is over 128 characters long and therefor too long!
            The validator should mark this as invalid.
            Another sentence too reach the limit.';
        $blankAssignee = '';


        $this->submitCreateFormWithInvalidInputs(
            $blankProject,
            $nonExistingStatus,
            $nonExistingPriority,
            $tooLongTitle,
            $blankAssignee,
        );


        $this->assertViolations();
    }

    private function submitCreateFormWithInvalidInputs(
        string $blankProject,
        int $nonExistingStatus,
        int $nonExistingPriority,
        string $tooLongTitle,
        string $blankAssignee
    ): void {
        $this->client->submitForm(
            'Create',
            [
                'bug_create[project]' => $blankProject,
                'bug_create[status]' => $nonExistingStatus,
                'bug_create[priority]' => $nonExistingPriority,
                'bug_create[due][month]' => 1,
                'bug_create[due][day]' => 1,
                'bug_create[due][year]' => (int)(new DateTimeImmutable())->format('Y'),
                'bug_create[title]' => $tooLongTitle,
                'bug_create[summary]' => '',
                'bug_create[reproduce]' => '',
                'bug_create[expected]' => '',
                'bug_create[actual]' => '',
                'bug_create[assignee]' => $blankAssignee,
            ],
        );
    }

    private function assertViolations(): void
    {
        $content = $this->client->getResponse()->getContent();
        if (!is_string($content)) {
            self::fail('No response content.');
        }

        $violationBlankProject = 'It must be assigned to a Project.';
        self::assertStringContainsString(
            $violationBlankProject,
            $content,
            sprintf('Validation for "%s" violation failed.', $violationBlankProject),
        );

        $violationNonExistingStatus = 'Choose a valid status.';
        self::assertStringContainsString(
            $violationNonExistingStatus,
            $content,
            sprintf('Validation for "%s" violation failed.', $violationNonExistingStatus),
        );

        $violationNonExistingPriority = 'Choose a valid priority.';
        self::assertStringContainsString(
            $violationNonExistingPriority,
            $content,
            sprintf('Validation for "%s" violation failed.', $violationNonExistingPriority),
        );

        $violationTooLongTitle = 'Title cannot be longer than 128 characters.';
        self::assertStringContainsString(
            $violationTooLongTitle,
            $content,
            sprintf('Validation for "%s" violation failed.', $violationTooLongTitle),
        );

        $violationBlankAssignee = 'It must be assigned to a User.';
        self::assertStringContainsString(
            $violationBlankAssignee,
            $content,
            sprintf('Validation for "%s" violation failed.', $violationBlankAssignee),
        );
    }

    public function testEdit(): void
    {
        $this->logInAsPo();

        /** @var Bug $bug */
        $bug = $this->fixtures->getReference('bug-P1-1');
        $this->client->request('GET', '/bug/edit/' . $bug->getId());

        $newTitle = 'newTitle';


        $this->submitEditFormWithBugAndNewTitle($bug, $newTitle);


        $bug->setTitle($newTitle);
        $this->assertBugInDb($bug);
    }

    private function submitEditFormWithBugAndNewTitle(Bug $bug, string $newTitle): void
    {
        $this->client->submitForm(
            'Update',
            [
                'bug_edit[status]' => $bug->getStatus(),
                'bug_edit[priority]' => $bug->getPriority(),
                'bug_edit[due][month]' => (int)$bug->getDue()->format('m'),
                'bug_edit[due][day]' => (int)$bug->getDue()->format('d'),
                'bug_edit[due][year]' => (int)$bug->getDue()->format('Y'),
                'bug_edit[title]' => $newTitle,
                'bug_edit[summary]' => $bug->getSummary(),
                'bug_edit[reproduce]' => $bug->getReproduce(),
                'bug_edit[expected]' => $bug->getExpected(),
                'bug_edit[actual]' => $bug->getActual(),
                'bug_edit[assignee]' => $bug->getAssignee()->getId(),
            ],
        );
    }
}
