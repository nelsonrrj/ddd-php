<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

class UserName implements ValueObject
{
    public function __construct(
        private string $name,
    ) {
        $this->validateName($name);
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): string
    {
        return $this->name;
    }

    private function validateName(string $name): void
    {
        if (strlen($name) < 3 || strlen($name) > 255) {
            throw new \InvalidArgumentException('Name must be between 3 and 255 characters long');
        }
    }
}
