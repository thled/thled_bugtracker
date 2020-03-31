<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[] findAll()
 * @method User[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface UserRepositoryInterface
{
    public function save(UserInterface $user): void;

    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void;

    public function get(UuidInterface $userId): User;
}
