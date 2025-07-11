<?php

declare(strict_types=1);

use App\Infrastructure\Responses\JsonResponse;
use App\App;

// Load routes from external file
$routes = require_once __DIR__ . '/routes.php';

$url = parse_url($_SERVER['REQUEST_URI']);
$path = $url['path'];

if (array_key_exists($path, $routes)) {
    processRoute($routes[$path]);
} else {
    $response = new JsonResponse(null, 'Not Found', 404);
    $response->send();
}

function processRoute(array $route): void
{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== $route[2]) {
            throw new \Exception('Method not allowed', 405);
        }
        $app = App::getInstance();
        $controller = $app->getContainer()->get($route[0]);
        $method = $route[1];
        $response = $controller->$method(getInput());
        $response->send();
    } catch (\Throwable $e) {
        $response = new JsonResponse(null, $e->getMessage(), $e->getCode());
        $response->send();
    }
}

function getInput(): ?array
{
    return json_decode(file_get_contents('php://input'), true);
}
