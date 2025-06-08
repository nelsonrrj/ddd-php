<?php

declare(strict_types=1);

namespace App\Infrastructure\Events;

use App\Domain\Events\DomainEvent;
use App\Domain\Events\EventDispatcher as DomainEventDispatcher;

class EventDispatcher implements DomainEventDispatcher
{
    private array $listeners = [];

    public function addListener(string $eventClass, callable $listener): void
    {
        if (!isset($this->listeners[$eventClass])) {
            $this->listeners[$eventClass] = [];
        }

        $this->listeners[$eventClass][] = $listener;
    }

    public function dispatch(DomainEvent $event): void
    {
        $eventClass = get_class($event);

        if (isset($this->listeners[$eventClass])) {
            foreach ($this->listeners[$eventClass] as $listener) {
                call_user_func($listener, $event);
            }
        }
    }
}
