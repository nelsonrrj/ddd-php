<?php

namespace App\Infrastructure\Exceptions;

use Exception;

class InfrastructureException extends Exception
{
  public function __construct($message = "Infrastructure error", $code = 500)
  {
    parent::__construct($message, $code);
  }
}
