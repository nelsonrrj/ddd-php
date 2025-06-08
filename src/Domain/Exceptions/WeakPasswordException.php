<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class WeakPasswordException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Weak password', 422);
    }
}
