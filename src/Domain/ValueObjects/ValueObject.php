<?php

namespace App\Domain\ValueObjects;

use JsonSerializable;
use Stringable;

interface ValueObject extends JsonSerializable, Stringable {}
