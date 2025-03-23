<?php

namespace App\Domain\Exceptions;

class UserAlreadyExistsException extends DomainException
{
  public function __construct()
  {
    parent::__construct("User already exists", 422);
  }
}
