<?php

namespace App\Domain\Exceptions;

class WeakPasswordException extends DomainException
{
  public function __construct()
  {
    parent::__construct("Weak password", 422);
  }
}
