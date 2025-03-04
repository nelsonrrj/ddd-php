<?php

namespace App\Application\DTO;

use JsonSerializable;

interface ResponseDTO extends JsonSerializable
{
  public function getStatusCode(): int;
}
