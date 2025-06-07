<?php

declare(strict_types=1);

namespace App\Infrastructure\DI\Definitions;

use App\Infrastructure\Persistence\DatabaseConnection;
use App\Infrastructure\Persistence\DatabaseConnectionParams;

use DI\Container;
use Doctrine\ORM\EntityManagerInterface;

return [
  DatabaseConnectionParams::class => function () {
    return new DatabaseConnectionParams(
      driver: getenv('DB_DRIVER'),
      host: getenv('DB_HOST'),
      port: (int) getenv('DB_PORT'),
      dbname: getenv('DB_DATABASE'),
      user: getenv('DB_USERNAME'),
      password: getenv('DB_PASSWORD'),
    );
  },

  DatabaseConnection::class => function (Container $container) {
    $entitiesPath = __DIR__ . '/../../../../src/Infrastructure/Persistence/Entities';
    return new DatabaseConnection($container->get(DatabaseConnectionParams::class), $entitiesPath);
  },

  EntityManagerInterface::class => function (Container $container) {
    return $container->get(DatabaseConnection::class)->getEntityManager();
  },
];
