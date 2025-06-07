<?php

namespace App\Infrastructure\Controllers;

use App\Application\UseCases\RegisterUserUserCase;
use App\Application\DTO\RegisterRequestDTO;
use App\Infrastructure\Responses\JsonResponse;

class RegisterUserController
{
  public function __construct(
    private RegisterUserUserCase $registerUserUserCase,
  ) {}

  public function register(array $data): JsonResponse
  {
    $response = $this->registerUserUserCase->execute(RegisterRequestDTO::fromArray($data));

    return JsonResponse::fromArray([
      'data' => $response,
      'message' => 'User created successfully',
      'statusCode' => 201
    ]);
  }
}
