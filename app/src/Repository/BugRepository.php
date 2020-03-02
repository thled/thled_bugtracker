<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bug;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Bug|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bug|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bug[]    findAll()
 * @method Bug[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class BugRepository extends ServiceEntityRepository implements BugRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bug::class);
    }

    public function findLatestBugOfProject(Project $project): ?Bug
    {
        $qb = $this->createQueryBuilder('b')
            ->andWhere('b.project = :project')
            ->orderBy('b.bugId', 'DESC')
            ->setMaxResults(1)
            ->setParameter('project', $project);
        $q = $qb->getQuery();

        return $q->getOneOrNullResult();
    }
}
