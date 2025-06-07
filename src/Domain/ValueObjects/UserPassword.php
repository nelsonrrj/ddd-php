<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\WeakPasswordException;

class UserPassword implements ValueObject
{
  public function __construct(
    private string $password,
    private bool $isHashed = false
  ) {
    if (!$this->isHashed) {
      $this->validatePassword($password);
      $this->password = $this->hashPassword($password);
    }
  }

  private function validatePassword(string $password): void
  {
    if (strlen($password) < 8) {
      throw new WeakPasswordException($password);
    }

    // Check if the password contains at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
      throw new WeakPasswordException($password);
    }

    // Check if the password contains at least one number
    if (!preg_match('/[0-9]/', $password)) {
      throw new WeakPasswordException($password);
    }

    // Check if the password contains at least one special character
    if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
      throw new WeakPasswordException($password);
    }
  }

  private function hashPassword(string $plainPassword): string
  {
    return password_hash($plainPassword, PASSWORD_DEFAULT);
  }

  public function comparePassword(string $plainPassword): bool
  {
    return password_verify($plainPassword, $this->password);
  }

  public function jsonSerialize(): string
  {
    return $this->password;
  }

  public function __toString(): string
  {
    return $this->password;
  }
}
