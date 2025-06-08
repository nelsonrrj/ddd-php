<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use Stringable;

interface ValueObject extends \JsonSerializable, Stringable {}
