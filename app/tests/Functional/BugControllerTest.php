<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Bug;
use App\Entity\Project;
use App\Entity\User;
use DateTimeImmutable;

/** @covers \App\Controller\BugController */
class BugControllerTest extends FunctionalTestBase
{
    private const PO = 'po0@example.com';
    private const DEV = 'dev0@example.com';

    /** @covers \App\Controller\BugController::add */
    public function testAdd(): void
    {
        $this->logIn(self::PO);
        $this->client->request('GET', '/bug/add');

        $bugToAdd = $this->createBug();
        $this->submitFormWithBug($bugToAdd);

        $this->assertBugIsCreated($bugToAdd);
    }

    private function createBug(): Bug
    {
        $projectRepo = $this->entityManager->getRepository(Project::class);
        $userRepo = $this->entityManager->getRepository(User::class);

        $bugId = 1;
        /** @var Project $project */
        $project = $projectRepo->find(1);
        $due = new DateTimeImmutable();
        /** @var User $reporter */
        $reporter = $userRepo->findOneBy(['email' => self::PO]);
        /** @var User $assignee */
        $assignee = $userRepo->findOneBy(['email' => self::DEV]);

        return new Bug(
            $bugId,
            $project,
            $due,
            $reporter,
            $assignee,
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

    private function assertBugIsCreated(Bug $bugToAdd): void
    {
        $bugAdded = $this->findBugInDb($bugToAdd);
        if (!$bugAdded instanceof Bug) {
            self::fail('Cannot find Bug in DB.');
        }

        self::assertSame($bugToAdd->getBugId(), $bugAdded->getBugId());
        self::assertSame($bugToAdd->getProject()->getId(), $bugAdded->getProject()->getId());
        self::assertSame($bugToAdd->getStatus(), $bugAdded->getStatus());
        self::assertSame($bugToAdd->getPriority(), $bugAdded->getPriority());
        self::assertSame($bugToAdd->getDue()->format('Ymd'), $bugAdded->getDue()->format('Ymd'));
        self::assertSame($bugToAdd->getTitle(), $bugAdded->getTitle());
        self::assertSame($bugToAdd->getSummary(), $bugAdded->getSummary());
        self::assertSame($bugToAdd->getReproduce(), $bugAdded->getReproduce());
        self::assertSame($bugToAdd->getExpected(), $bugAdded->getExpected());
        self::assertSame($bugToAdd->getActual(), $bugAdded->getActual());
        self::assertSame($bugToAdd->getReporter()->getId(), $bugAdded->getReporter()->getId());
        self::assertSame($bugToAdd->getAssignee()->getId(), $bugAdded->getAssignee()->getId());
    }
}
