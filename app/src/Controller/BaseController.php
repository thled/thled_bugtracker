<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

abstract class BaseController extends AbstractController
{
    /** @codeCoverageIgnore */
    protected function getUser(): User
    {
        if (!$this->isSecurityBundleAvailable()) {
            throw new LogicException(
                'The SecurityBundle is not registered in your application.
                 Try running "composer require symfony/security-bundle".',
            );
        }

        $token = $this->container->get('security.token_storage')->getToken();
        if (!$token instanceof TokenInterface) {
            throw new LogicException('Token is not of type "Token".');
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new LogicException('User is not of type "User".');
        }

        return $user;
    }

    private function isSecurityBundleAvailable(): bool
    {
        return $this->container->has('security.token_storage');
    }
}
