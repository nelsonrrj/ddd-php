<?php

declare(strict_types=1);

namespace App\Domain\Events;

interface EventHandler
{
    public function execute(DomainEvent $event): void;
} 