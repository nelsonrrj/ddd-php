<?php

namespace App\Infrastructure\Controllers;

use App\Application\UseCases\RegisterUserUserCase;
use App\Application\DTO\RegisterRequestDTO;
use App\Application\DTO\ResponseDTO;

class RegisterUserController
{
  public function __construct(
    private RegisterUserUserCase $registerUserUserCase,
  ) {}

  public function register(array $data): ResponseDTO
  {
    return $this->registerUserUserCase->execute(RegisterRequestDTO::fromArray($data));
  }
}
