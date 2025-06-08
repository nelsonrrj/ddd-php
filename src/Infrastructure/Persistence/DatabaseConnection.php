<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Exceptions\DatabaseConnectionException;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class DatabaseConnection
{
    private readonly DatabaseConnectionParams $connectionParams;
    private readonly string $entitiesPath;
    private readonly bool $isDevMode;
    private ?EntityManager $entityManager = null;

    public function __construct(DatabaseConnectionParams $connectionParams, string $entitiesPath, bool $isDevMode = true)
    {
        $this->connectionParams = $connectionParams;
        $this->entitiesPath = $entitiesPath;
        $this->isDevMode = $isDevMode ?? true;
    }

    public function createEntityManager(): EntityManager
    {
        try {
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [$this->entitiesPath],
                isDevMode: $this->isDevMode,
            );

            $connection = DriverManager::getConnection(
                $this->connectionParams->toArray(),
                $config,
            );

            $this->entityManager = new EntityManager($connection, $config);

            return $this->entityManager;
        } catch (\Exception $e) {
            throw new DatabaseConnectionException('Error creating entity manager: ' . $e->getMessage());
        }
    }

    public function getEntityManager(): EntityManager
    {
        if (!$this->entityManager) {
            return $this->createEntityManager();
        }

        return $this->entityManager;
    }
}
