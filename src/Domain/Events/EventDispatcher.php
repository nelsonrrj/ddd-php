<?php

namespace App\Domain\Events;

interface EventDispatcher
{
  public function dispatch(DomainEvent $event): void;
  public function addListener(string $eventClass, callable $listener): void;
}
