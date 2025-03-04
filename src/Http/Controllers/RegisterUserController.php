<?php

namespace App\Http\Controllers;

use App\Application\UseCases\RegisterUserUserCase;
use App\Application\DTO\RegisterRequestDTO;
use App\Application\DTO\ResponseDTO;
use App\Application\DTO\ExceptionResponseDTO;
use Exception;

class RegisterUserController
{
  public function __construct(
    private RegisterUserUserCase $registerUserUserCase,
  ) {}

  public function register(array $data): ResponseDTO
  {
    try {
      return $this->registerUserUserCase->execute(RegisterRequestDTO::fromArray($data));
    } catch (Exception $e) {
      return new ExceptionResponseDTO($e->getMessage(), $e->getCode());
    }
  }
}
