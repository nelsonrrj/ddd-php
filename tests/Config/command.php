<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Tests\Infrastructure\Persistence\DatabaseTestConnection;

$dbConnection = new DatabaseTestConnection();
$entityManager = $dbConnection->getEntityManager();

ConsoleRunner::run(
  new SingleManagerProvider($entityManager)
);

// php tests/Config/command.php orm:schema-tool:create
