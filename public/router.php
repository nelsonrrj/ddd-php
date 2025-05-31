<?php

use App\Infrastructure\Controllers\RegisterUserController;
use App\Infrastructure\DI\ContainerFactory;
use App\Infrastructure\Exceptions\InfrastructureException;

try {
  $container = ContainerFactory::getContainer();

  if ($_SERVER['REQUEST_URI'] === '/users' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $registerUserController = $container->get(RegisterUserController::class);

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