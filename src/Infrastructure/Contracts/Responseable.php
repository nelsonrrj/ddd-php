<?php

declare(strict_types=1);

namespace App\Infrastructure\Contracts;

interface Responseable
{
    public function send(): void;
}
