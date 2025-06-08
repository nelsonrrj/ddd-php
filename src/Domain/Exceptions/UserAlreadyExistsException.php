<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class UserAlreadyExistsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('User already exists', 422);
    }
}
