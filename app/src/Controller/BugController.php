<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BugController extends AbstractController
{
    /** @Route("/bug/add", name="bug_add") */
    public function create(): Response
    {
        return $this->render('bug/add.html.twig');
    }
}
