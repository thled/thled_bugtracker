<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\CreateBugData;
use App\Entity\Bug;
use App\Entity\Project;
use App\Entity\User;
use App\Form\BugType;
use DateTimeImmutable;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BugController extends BaseController
{
    /** @Route("/bug/add", name="bug_add") */
    public function add(Request $request): Response
    {
        $createBugData = new CreateBugData();
        $createBugData->reporter = $this->getUser();

        $form = $this->createForm(BugType::class, $createBugData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bugId = 1; // todo: calculate real id
            $project = $createBugData->project;
            $due = $createBugData->due;
            $reporter = $createBugData->reporter;
            $assignee = $createBugData->assignee;

            if (
                (!$project instanceof Project) ||
                (!$due instanceof DateTimeImmutable) ||
                (!$assignee instanceof User)
            ) {
                throw new LogicException('Form validation failed.');
            }

            $bug = new Bug(
                $bugId,
                $project,
                $due,
                $reporter,
                $assignee,
                $createBugData->status ?? 0,
                $createBugData->priority ?? 0,
                $createBugData->title ?? '',
                $createBugData->summary ?? '',
                $createBugData->reproduce ?? '',
                $createBugData->expected ?? '',
                $createBugData->actual ?? '',
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bug);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'bug/add.html.twig',
            ['form' => $form->createView()],
        );
    }
}
