<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class InvalidEmailException extends DomainException
{
    public function __construct(string $email, int $code = 422)
    {
        parent::__construct('Invalid email', $code);
    }
}
