<?php

declare(strict_types=1);

namespace App\Doctrine\EventListener;

use Doctrine\DBAL\Schema\PostgreSqlSchemaManager;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * Doctrine issue: https://github.com/doctrine/dbal/issues/1110
 * Fix from: https://gist.github.com/vudaltsov/ec01012d3fe27c9eed59aa7fd9089cf7
 */
final class FixPostgreSqlDefaultSchemaListener
{
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schemaManager = $args
            ->getEntityManager()
            ->getConnection()
            ->getSchemaManager();

        if (!$schemaManager instanceof PostgreSqlSchemaManager) {
            return;
        }

        foreach ($schemaManager->getExistingSchemaSearchPaths() as $namespace) {
            if ($args->getSchema()->hasNamespace($namespace)) {
                continue;
            }

            $args->getSchema()->createNamespace($namespace);
        }
    }
}
