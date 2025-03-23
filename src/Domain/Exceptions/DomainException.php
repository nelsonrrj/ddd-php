<?php

namespace App\Domain\Exceptions;

use Exception;

class DomainException extends Exception
{
  public function __construct($message = "Domain exception", $code = 500)
  {
    parent::__construct($message, $code);
  }
}
