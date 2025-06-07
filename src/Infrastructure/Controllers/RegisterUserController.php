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

  public function register(array $data)
  {
    $response = $this->registerUserUserCase->execute(RegisterRequestDTO::fromArray($data));

    return JsonResponse::send($response, 201, 'User created successfully');
  }
}
