<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Repository\Exception\RecordNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[] findAll()
 * @method User[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class UserRepository extends ServiceEntityRepository implements
    UserRepositoryInterface,
    PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(UserInterface $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /** Used to upgrade (rehash) the user's password automatically over time. */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user)),
            );
        }

        $user->setPassword($newEncodedPassword);

        $this->_em->flush();
    }

    public function get(UuidInterface $userId): User
    {
        $user = $this->find($userId);
        if (!$user instanceof User) {
            throw new RecordNotFoundException(
                sprintf('No user found with ID "%s".', $userId->toString()),
            );
        }

        return $user;
    }
}
