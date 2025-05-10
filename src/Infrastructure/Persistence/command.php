<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Infrastructure\DI\ContainerFactory;
use App\Infrastructure\Persistence\DatabaseConnection;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

$container = ContainerFactory::getContainer();
$dbConnection = $container->get(DatabaseConnection::class);
$entityManager = $dbConnection->getEntityManager();

ConsoleRunner::run(
  new SingleManagerProvider($entityManager)
);

// php src/Infrastructure/Persistence/command.php orm:schema-tool:create
