<?php

declare(strict_types=1);

namespace App\Infrastructure\Events;

use App\Domain\Events\UserRegisteredEvent;
use App\Infrastructure\EventHandlers\SendWelcomeEmailHandler;

class EventHandler
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
    ) {}

    public function setup(): void
    {
        $this->eventDispatcher->addListener(
            UserRegisteredEvent::class,
            new SendWelcomeEmailHandler(),
        );
    }
}
