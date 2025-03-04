<?php

namespace App\Domain\Exceptions;

use Exception;

class InvalidEmailException extends Exception
{
  public function __construct(string $email, int $code = 422)
  {
    parent::__construct("Invalid email", $code);
  }
}
