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
    /** @covers \App\Controller\BugController::add */
    public function testAdd(): void
    {
        $this->logIn('po0@example.com');
        $this->client->request('GET', '/bug/add');

        $bugToAdd = $this->createBug();
        $this->submitFormWithBug($bugToAdd);

        $this->assertBugIsCreated($bugToAdd);
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

    private function submitFormWithBug(Bug $bugToAdd): void
    {
        $this->client->submitForm(
            'Create',
            [
                'bug[project]' => $bugToAdd->getProject()->getId(),
                'bug[status]' => $bugToAdd->getStatus(),
                'bug[priority]' => $bugToAdd->getPriority(),
                'bug[due][month]' => (int)$bugToAdd->getDue()->format('m'),
                'bug[due][day]' => (int)$bugToAdd->getDue()->format('d'),
                'bug[due][year]' => (int)$bugToAdd->getDue()->format('Y'),
                'bug[title]' => $bugToAdd->getTitle(),
                'bug[summary]' => $bugToAdd->getSummary(),
                'bug[reproduce]' => $bugToAdd->getReproduce(),
                'bug[expected]' => $bugToAdd->getExpected(),
                'bug[actual]' => $bugToAdd->getActual(),
                'bug[assignee]' => $bugToAdd->getAssignee()->getId(),
            ],
        );
    }

    private function assertBugIsCreated(Bug $bugToAdd): void
    {
        $bugAdded = $this->findBugInDb($bugToAdd);
        if (!$bugAdded instanceof Bug) {
            self::fail('Cannot find Bug in DB.');
        }

        self::assertSame($bugToAdd->getBugId(), $bugAdded->getBugId());
        self::assertTrue($bugToAdd->getProject()->getId()->equals($bugAdded->getProject()->getId()));
        self::assertSame($bugToAdd->getStatus(), $bugAdded->getStatus());
        self::assertSame($bugToAdd->getPriority(), $bugAdded->getPriority());
        self::assertSame($bugToAdd->getDue()->format('Ymd'), $bugAdded->getDue()->format('Ymd'));
        self::assertSame($bugToAdd->getTitle(), $bugAdded->getTitle());
        self::assertSame($bugToAdd->getSummary(), $bugAdded->getSummary());
        self::assertSame($bugToAdd->getReproduce(), $bugAdded->getReproduce());
        self::assertSame($bugToAdd->getExpected(), $bugAdded->getExpected());
        self::assertSame($bugToAdd->getActual(), $bugAdded->getActual());
        self::assertTrue($bugToAdd->getReporter()->getId()->equals($bugAdded->getReporter()->getId()));
        self::assertTrue($bugToAdd->getAssignee()->getId()->equals($bugAdded->getAssignee()->getId()));
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

    /** @covers \App\Controller\BugController::add */
    public function testAddValidation(): void
    {
        $this->logIn('po0@example.com');
        $this->client->request('GET', '/bug/add');

        $blankProject = '';
        $nonExistingStatus = 42;
        $nonExistingPriority = 42;
        $tooLongTitle =
            'This title is over 128 characters long and therefor too long!
            The validator should mark this as invalid.
            Another sentence too reach the limit.';
        $blankAssignee = '';
        $this->client->submitForm(
            'Create',
            [
                'bug[project]' => $blankProject,
                'bug[status]' => $nonExistingStatus,
                'bug[priority]' => $nonExistingPriority,
                'bug[due][month]' => 1,
                'bug[due][day]' => 1,
                'bug[due][year]' => (int)(new DateTimeImmutable())->format('Y'),
                'bug[title]' => $tooLongTitle,
                'bug[summary]' => '',
                'bug[reproduce]' => '',
                'bug[expected]' => '',
                'bug[actual]' => '',
                'bug[assignee]' => $blankAssignee,
            ],
        );

        $this->assertViolations();
    }

    private function assertViolations(): void
    {
        $violationBlankProject = 'It must be assigned to a Project.';
        self::assertContains(
            $violationBlankProject,
            $this->client->getResponse()->getContent(),
            sprintf('Validation for "%s" violation failed.', $violationBlankProject),
        );

        $violationNonExistingStatus = 'Choose a valid status.';
        self::assertContains(
            $violationNonExistingStatus,
            $this->client->getResponse()->getContent(),
            sprintf('Validation for "%s" violation failed.', $violationNonExistingStatus),
        );

        $violationNonExistingPriority = 'Choose a valid priority.';
        self::assertContains(
            $violationNonExistingPriority,
            $this->client->getResponse()->getContent(),
            sprintf('Validation for "%s" violation failed.', $violationNonExistingPriority),
        );

        $violationTooLongTitle = 'Title cannot be longer than 128 characters.';
        self::assertContains(
            $violationTooLongTitle,
            $this->client->getResponse()->getContent(),
            sprintf('Validation for "%s" violation failed.', $violationTooLongTitle),
        );

        $violationBlankAssignee = 'It must be assigned to a User.';
        self::assertContains(
            $violationBlankAssignee,
            $this->client->getResponse()->getContent(),
            sprintf('Validation for "%s" violation failed.', $violationBlankAssignee),
        );
    }
}
