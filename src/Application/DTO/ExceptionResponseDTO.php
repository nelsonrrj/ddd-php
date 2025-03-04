<?php

namespace App\Application\DTO;

class ExceptionResponseDTO implements ResponseDTO
{
  public function __construct(private string $message, private int $statusCode = 500) {}

  public function jsonSerialize(): array
  {
    return ['message' => $this->message];
  }

  public function getStatusCode(): int
  {
    return $this->statusCode;
  }
}
