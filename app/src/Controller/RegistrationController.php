<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RegistrationController extends AbstractController
{
    /** @Route("/register", name="register") */
    public function register(
        Request $request,
        RegistrationService $registration
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordPlain = $form->get('plainPassword')->getData();
            $registration->encodePasswordInUser($user, $passwordPlain);

            $registration->saveUser($user);

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
