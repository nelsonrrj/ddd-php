<?php

declare(strict_types=1);

namespace App\Infrastructure\Events;

use App\Infrastructure\Events\EventDispatcher;
use App\Application\EventHandlers\SendWelcomeEmailHandler;
use App\Domain\Events\UserRegisteredEvent;

class EventHandler
{
  public function __construct(
    private EventDispatcher $eventDispatcher
  ) {}

  public function setup(): void
  {
    $this->eventDispatcher->addListener(
      UserRegisteredEvent::class,
      new SendWelcomeEmailHandler()
    );
  }
}
