<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use Ramsey\Uuid\UuidInterface;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[] findAll()
 * @method Project[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface ProjectRepositoryInterface
{
    public function get(UuidInterface $projectId): Project;

    public function save(Project $project): void;
}
