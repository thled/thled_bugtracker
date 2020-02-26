<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IndexController extends BaseController
{
    /** @Route("/", name="index") */
    public function index(): Response
    {
        return $this->render('main.html.twig');
    }
}
