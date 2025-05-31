<?php

declare(strict_types=1);

namespace App\Infrastructure\DI\Definitions;

use App\Domain\Events\EventDispatcher;
use App\Infrastructure\Persistence\DatabaseConnection;
use App\Infrastructure\Persistence\DatabaseConnectionParams;
use Doctrine\ORM\EntityManager;

use DI\Container;
use Doctrine\ORM\EntityManagerInterface;

return [
  // Define infrastructure components

  // Database connection configuration
  DatabaseConnection::class => function () {
    $config = require __DIR__ . '/../../../../config/database.php';
    $entitiesPath = __DIR__ . '/../../../../src/Infrastructure/Persistence/Entities';
    return new DatabaseConnection(new DatabaseConnectionParams(...$config), $entitiesPath);
  },

  EntityManagerInterface::class => function (Container $container) {
    return $container->get(DatabaseConnection::class)->getEntityManager();
  },
];
