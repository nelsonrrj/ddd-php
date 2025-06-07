<?php

namespace App\Application\DTO;

use App\Domain\Entities\UserEntity;

class UserResponseDTO implements ResponseDTO
{
  public function __construct(private UserEntity $user) {}

  public function jsonSerialize(): array
  {
    return $this->user->jsonSerialize();
  }
}
