<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\InvalidEmailException;

class UserEmail implements ValueObject
{
    public function __construct(
        private string $email,
    ) {
        $this->validateEmail($email);
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public function jsonSerialize(): string
    {
        return $this->email;
    }

    private function validateEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException($email);
        }
    }
}
