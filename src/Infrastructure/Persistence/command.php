<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Infrastructure\Persistence\DatabaseConnection;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

$dbConnection = new DatabaseConnection();
$entityManager = $dbConnection->getEntityManager();

ConsoleRunner::run(
  new SingleManagerProvider($entityManager)
);

// php src/Infrastructure/Persistence/command.php orm:schema-tool:create
