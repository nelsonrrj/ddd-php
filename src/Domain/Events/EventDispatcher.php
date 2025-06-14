<?php

declare(strict_types=1);

namespace App\Domain\Events;

interface EventDispatcher
{
    /**
     * @param DomainEvent $event The event to dispatch
     *
     * @example
     * $eventDispatcher->dispatch(new UserRegisteredEvent($user));
     */
    public function dispatch(DomainEvent $event): void;

    /**
     * @param string $eventClass The class name of the event to listen for
     * @param EventHandler $listener The event handler to be called when the event is dispatched
     *
     * @example
     * $eventDispatcher->addListener(
     *     UserRegisteredEvent::class,
     *     new SendWelcomeEmailHandler()
     * );
     */
    public function addListener(string $eventClass, EventHandler $listener): void;
}
