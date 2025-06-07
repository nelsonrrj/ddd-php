<?php

namespace App\Infrastructure\Responses;

use JsonSerializable;

class JsonResponse extends BaseResponse
{
  private array $defaultHeaders = [
    'Content-Type' => 'application/json',
  ];

  public function __construct(
    JsonSerializable|array|null $data,
    string $message = 'Success',
    int $statusCode = 200,
    array $headers = [],
  ) {
    $mergedHeaders = array_merge($this->defaultHeaders, $headers);
    parent::__construct($data, $message, $statusCode, $mergedHeaders);
  }

  public function send(): void
  {
    foreach ($this->headers as $key => $value) {
      header($key . ': ' . $value);
    }

    http_response_code($this->statusCode);

    echo json_encode([
      'message' => $this->message,
      'data' => $this->data,
    ], JSON_PRETTY_PRINT);

    exit();
  }
}
