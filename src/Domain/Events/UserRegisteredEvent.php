<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Entities\UserEntity;

class UserRegisteredEvent implements DomainEvent
{
    private \DateTimeImmutable $occurredOn;
    
    public function __construct(
        private UserEntity $user
    ) {
        $this->occurredOn = new \DateTimeImmutable();
    }
    
    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
    
    public function eventData(): array
    {
        return [
            'userId' => (string) $this->user->id,
            'userEmail' => (string) $this->user->email,
            'userName' => (string) $this->user->name,
            'registeredAt' => $this->user->createdAt->format('Y-m-d H:i:s'),
        ];
    }
} 