<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\CreateBugDto;
use App\Entity\Bug;
use App\Factory\BugFactoryInterface;
use App\Form\BugType;
use App\Repository\BugRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/bug/", name="bug_") */
final class BugController extends BaseController
{
    /** @Route("list", name="list") */
    public function list(BugRepositoryInterface $bugRepo): Response
    {
        $bugs = $bugRepo->findAll();

        return $this->render(
            'bug/list.html.twig',
            ['bugs' => $bugs],
        );
    }

    /** @Route("add", name="add") */
    public function add(
        Request $request,
        BugFactoryInterface $bugFactory,
        BugRepositoryInterface $bugRepo
    ): Response {
        $bugDto = new CreateBugDto();
        $bugDto->reporter = $this->getUser();
        $form = $this->createForm(BugType::class, $bugDto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bug = $bugFactory->createFromCreateBugDto($bugDto);

            $bugRepo->save($bug);

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'bug/add.html.twig',
            ['form' => $form->createView()],
        );
    }

    /** @Route("edit/{bug}", name="edit") */
    public function edit(Bug $bug): Response
    {
        return $this->render(
            'bug/edit.html.twig',
            ['bugId' => $bug->getBugId()],
        );
    }
}
