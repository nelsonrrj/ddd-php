<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

class InfrastructureException extends \Exception
{
    public function __construct($message = 'Infrastructure error', $code = 500)
    {
        parent::__construct($message, $code);
    }
}
