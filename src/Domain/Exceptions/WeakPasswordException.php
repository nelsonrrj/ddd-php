<?php

namespace App\Domain\Exceptions;

use Exception;

class WeakPasswordException extends Exception
{
  public function __construct()
  {
    parent::__construct("Weak password", 422);
  }
}
