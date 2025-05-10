<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\UserEmail;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\UserName;
use App\Domain\ValueObjects\UserPassword;
use DateTime;
use JsonSerializable;

class UserEntity implements JsonSerializable
{
  public function __construct(
    public UserEmail $email,
    public UserName $name,
    public UserPassword $password,
    public ?UserId $id = null,
    public DateTime $createdAt = new DateTime(),
  ) {}

  public function jsonSerialize(): array
  {
    return [
      'id' => $this->id,
      'email' => $this->email,
      'name' => $this->name,
      'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
    ];
  }
}
