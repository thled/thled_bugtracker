<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Facade\UserFacadeInterface;
use App\Form\UserShowType;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/user/", name="user_") */
final class UserController extends BaseController
{
    /** @Route("list", name="list") */
    public function list(UserRepositoryInterface $userRepo): Response
    {
        $users = $userRepo->findAll();

        return $this->render(
            'user/list.html.twig',
            ['users' => $users],
        );
    }

    /** @Route("show/{user}", name="show") */
    public function show(User $user, UserFacadeInterface $userFacade): Response
    {
        $userDto = $userFacade->mapUserToShowDto($user);
        $form = $this->createForm(UserShowType::class, $userDto);

        return $this->render(
            'user/show.html.twig',
            ['form' => $form->createView()],
        );
    }

    /** @Route("assignment", name="assignment") */
    public function assignment(): Response
    {
        return $this->redirectToRoute('index');
    }
}
