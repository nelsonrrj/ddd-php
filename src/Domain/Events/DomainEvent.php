<?php

declare(strict_types=1);

namespace App\Domain\Events;

interface DomainEvent
{
    public function occurredOn(): \DateTimeImmutable;
}
