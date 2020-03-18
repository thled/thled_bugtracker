<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\CreateBugDto;
use App\Factory\BugFactoryInterface;
use App\Form\BugType;
use App\Repository\BugRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BugController extends BaseController
{
    /** @Route("/bug/add", name="bug_add") */
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
}
