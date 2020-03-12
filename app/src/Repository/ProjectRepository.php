<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use App\Repository\Exception\RecordNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function get(UuidInterface $projectId): Project
    {
        $project = $this->find($projectId);
        if (!$project instanceof Project) {
            throw new RecordNotFoundException(
                sprintf('No project found with ID "%s".', $projectId->toString()),
            );
        }

        return $project;
    }
}
