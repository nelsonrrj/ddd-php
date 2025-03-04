<?php

namespace App\Application\DTO;

class RegisterRequestDTO
{
  public function __construct(
    public string $name,
    public string $email,
    public string $password,
  ) {}

  public static function fromArray(array $data): RegisterRequestDTO
  {
    return new RegisterRequestDTO(
      $data['name'],
      $data['email'],
      $data['password'],
    );
  }
}
