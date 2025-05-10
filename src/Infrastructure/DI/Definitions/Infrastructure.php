<?php

declare(strict_types=1);

namespace App\Infrastructure\DI\Definitions;

use App\Infrastructure\Persistence\DatabaseConnection;
use App\Infrastructure\Persistence\DatabaseConnectionParams;

return [
  // Define infrastructure components

  // Database connection configuration
  DatabaseConnection::class => function () {
    return new DatabaseConnection(new DatabaseConnectionParams(
      'pdo_mysql',
      $_ENV['DB_HOST'],
      intval($_ENV['DB_PORT']),
      $_ENV['DB_DATABASE'],
      $_ENV['DB_USERNAME'],
      $_ENV['DB_PASSWORD'],
    ));
  },
];
