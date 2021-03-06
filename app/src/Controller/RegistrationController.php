<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\UserRegisterDto;
use App\Factory\UserFactoryInterface;
use App\Form\RegistrationType;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RegistrationController extends BaseController
{
    /** @Route("/register", name="register") */
    public function register(
        Request $request,
        UserFactoryInterface $userFactory,
        UserRepositoryInterface $userRepo
    ): Response {
        $registerUser = new UserRegisterDto();
        $form = $this->createForm(RegistrationType::class, $registerUser);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userFactory->createFromDto($registerUser);

            $userRepo->save($user);

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'registrationForm' => $form->createView(),
            ],
        );
    }
}
