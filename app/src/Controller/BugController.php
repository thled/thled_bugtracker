<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\BugCreateDto;
use App\Entity\Bug;
use App\Facade\BugFacadeInterface;
use App\Form\BugCreateType;
use App\Form\BugEditType;
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
        BugFacadeInterface $bugFacade
    ): Response {
        $bugDto = new BugCreateDto();
        $bugDto->reporter = $this->getUser();
        $form = $this->createForm(BugCreateType::class, $bugDto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bugFacade->saveBugFromDto($bugDto);

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'bug/add.html.twig',
            ['form' => $form->createView()],
        );
    }

    /** @Route("edit/{bug}", name="edit") */
    public function edit(
        Bug $bug,
        Request $request,
        BugFacadeInterface $bugFacade
    ): Response {
        $bugDto = $bugFacade->mapBugToUpdateDto($bug);
        $form = $this->createForm(BugEditType::class, $bugDto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bugFacade->updateBugFromDto($bug, $bugDto);

            return $this->redirectToRoute(
                'bug_edit',
                ['bug' => $bug->getId()],
            );
        }

        return $this->render(
            'bug/edit.html.twig',
            ['form' => $form->createView()],
        );
    }
}
