<?php

namespace App\Infrastructure\Responses;

use JsonSerializable;

class JsonResponse
{
  public static function setHeaders(array $headers): void
  {
    foreach ($headers as $key => $value) {
      header($key . ': ' . $value);
    }
  }

  public static function setStatusCode(int $statusCode): void
  {
    http_response_code($statusCode);
  }

  public static function send(JsonSerializable|array|null $data = null, int $statusCode = 200, string $message = 'Success', array $headers = []): void
  {
    self::setHeaders($headers);
    self::setStatusCode($statusCode);

    echo json_encode([
      'message' => $message,
      'data' => $data,
    ], JSON_PRETTY_PRINT);

    exit();
  }
}
