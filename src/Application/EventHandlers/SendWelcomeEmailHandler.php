<?php

declare(strict_types=1);

namespace App\Application\EventHandlers;

use App\Domain\Events\UserRegisteredEvent;

class SendWelcomeEmailHandler
{
  public function __invoke(UserRegisteredEvent $event): void
  {
    $user = $event->getUser();

    // TODO: Implement the logic to send the welcome email
  }
}
