<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

abstract class BaseController extends AbstractController
{
    protected function getUser(): User
    {
        $user = parent::getUser();
        if (!$user instanceof User) {
            throw new UnsupportedUserException('Logged in User is not of Type "User".');
        }

        return $user;
    }
}
