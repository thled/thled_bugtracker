<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\BugFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BugController extends BaseController
{
    /** @Route("/bug/add", name="bug_add") */
    public function add(): Response
    {
        $form = $this->createForm(BugFormType::class);

        return $this->render(
            'bug/add.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }
}
