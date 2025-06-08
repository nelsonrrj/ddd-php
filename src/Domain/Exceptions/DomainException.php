<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class DomainException extends \Exception
{
    public function __construct($message = 'Domain exception', $code = 500)
    {
        parent::__construct($message, $code);
    }
}
