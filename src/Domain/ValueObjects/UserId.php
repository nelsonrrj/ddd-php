<?php

namespace App\Domain\ValueObjects;

class UserId implements ValueObject
{
  public function __construct(
    private int $id,
  ) {}

  public function getValue(): int
  {
    return $this->id;
  }

  public function jsonSerialize(): int
  {
    return $this->id;
  }

  public function __toString(): string
  {
    return (string) $this->id;
  }
}
