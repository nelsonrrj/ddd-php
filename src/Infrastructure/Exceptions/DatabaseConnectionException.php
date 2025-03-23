<?php

namespace App\Infrastructure\Exceptions;

class DatabaseConnectionException extends InfrastructureException
{
  public function __construct($message = "Database connection error", $code = 500)
  {
    parent::__construct($message, $code);
  }
}
