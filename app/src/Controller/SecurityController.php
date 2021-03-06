<?php

declare(strict_types=1);

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends BaseController
{
    /** @Route("/login", name="login") */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ],
        );
    }

    /**
     * @Route("/logout", name="logout")
     * @throws Exception
     * @codeCoverageIgnore
     */
    public function logout(): void
    {
        throw new Exception(
            'This method can be blank - it will be intercepted by the logout key on your firewall',
        );
    }
}
