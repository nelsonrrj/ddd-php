<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Application\UseCases\RegisterUserUserCase;
use App\Config\EventHandler;
use App\Http\Controllers\RegisterUserController;
use App\Infrastructure\DI\ContainerFactory;
use App\Infrastructure\Events\EventDispatcher;
use App\Infrastructure\Persistence\DatabaseConnection;
use App\Infrastructure\Repositories\DoctrineUserRepository;
use App\Infrastructure\Exceptions\InfrastructureException;

try {
  $container = ContainerFactory::getContainer();
  $dbConnection = $container->get(DatabaseConnection::class);
  $entityManager = $dbConnection->getEntityManager();

  $eventDispatcher = new EventDispatcher();
  $eventHandler = new EventHandler($eventDispatcher);
  $eventHandler->setup();

  if ($_SERVER['REQUEST_URI'] === '/users' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $userRepository = new DoctrineUserRepository($entityManager);
    $registerUserUserCase = new RegisterUserUserCase($userRepository, $eventDispatcher);
    $registerUserController = new RegisterUserController($registerUserUserCase);

    $input = json_decode(file_get_contents('php://input'), true);

    $response = $registerUserController->register($input);
    http_response_code($response->getStatusCode());
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
  }
} catch (DomainException $e) {
  http_response_code($e->getCode());
  echo json_encode(['error' => $e->getMessage()]);
  exit();
} catch (InfrastructureException $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
  exit();
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
  exit();
}

http_response_code(404);
echo json_encode(['error' => 'Not Found']);
exit();