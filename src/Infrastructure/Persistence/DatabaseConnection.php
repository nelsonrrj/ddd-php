<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Exception;

class DatabaseConnection
{
  private array $connectionParams;
  private string $entitiesPath;
  private bool $isDevMode;
  private ?EntityManager $entityManager = null;

  public function __construct(array $connectionParams = [], bool $isDevMode = true)
  {
    $this->connectionParams = $connectionParams ?: require __DIR__ . '/../../Config/DatabaseConfig.php';
    $this->entitiesPath = __DIR__ . '/Entities';
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
        $this->connectionParams,
        $config
      );

      $this->entityManager = new EntityManager($connection, $config);
      return $this->entityManager;
    } catch (\Exception $e) {
      throw new \Exception("Error creating entity manager: " . $e->getMessage());
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
