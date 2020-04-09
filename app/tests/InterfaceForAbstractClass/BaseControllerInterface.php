<?php

declare(strict_types=1);

namespace App\Tests\InterfaceForAbstractClass;

use App\Entity\User;
use Psr\Container\ContainerInterface;

interface BaseControllerInterface
{
    public function getUser(): User;

    public function setContainer(ContainerInterface $container): ?ContainerInterface;
}
