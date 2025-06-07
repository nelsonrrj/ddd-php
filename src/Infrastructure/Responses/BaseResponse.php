<?php

namespace App\Infrastructure\Responses;

use App\Infrastructure\Contracts\Responseable;
use JsonSerializable;

class BaseResponse implements Responseable
{
  public JsonSerializable|array|null $data = null;
  public string $message = 'Success';
  public int $statusCode = 200;
  public array $headers = [];

  public function __construct(
    array|object|null $data = null,
    string $message = 'Success',
    int $statusCode = 200,
    array $headers = [],
  ) {
    $this->data = $data;
    $this->message = $message;
    $this->statusCode = $statusCode;
    $this->headers = $headers;
  }

  public function send(): void
  {
    throw new \Exception('Not implemented');
  }

  public function toArray(): array
  {
    return [
      'data' => $this->data,
      'message' => $this->message,
      'statusCode' => $this->statusCode,
      'headers' => $this->headers
    ];
  }

  public static function fromArray(array $data): static
  {
    return new static(
      $data['data'],
      $data['message'],
      $data['statusCode'],
      $data['headers'] ?? [],
    );
  }
}
