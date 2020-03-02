<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bug;
use App\Entity\Project;

/**
 * @method Bug|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bug|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bug[]    findAll()
 * @method Bug[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface BugRepositoryInterface
{
    public function findLatestBugOfProject(Project $project): ?Bug;
}
