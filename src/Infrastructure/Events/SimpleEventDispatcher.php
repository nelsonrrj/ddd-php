<?php

declare(strict_types=1);

namespace App\Infrastructure\Events;

use App\Domain\Events\DomainEvent;
use App\Domain\Events\EventDispatcher;
use App\Domain\Events\EventHandler;

class SimpleEventDispatcher implements EventDispatcher
{
    /**
     * @var EventHandler[]
     */
    private array $handlers = [];
    
    public function dispatch(DomainEvent $event): void
    {
        /** @var string $eventClass */
        $eventClass = get_class($event);

        if (isset($this->handlers[$eventClass])) {
            /** @var EventHandler $handler */
            foreach ($this->handlers[$eventClass] as $handler) {
                $handler->execute($event);
            }
        }
    }

    public function addListener(string $eventClass, EventHandler $listener): void
    {
        if (!isset($this->handlers[$eventClass])) {
            $this->handlers[$eventClass] = [];
        }

        $this->handlers[$eventClass][] = $listener;
    }
} 