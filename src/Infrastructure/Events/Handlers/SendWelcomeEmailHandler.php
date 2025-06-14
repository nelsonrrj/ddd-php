<?php

declare(strict_types=1);

namespace App\Infrastructure\Events\Handlers;

use App\Domain\Events\DomainEvent;
use App\Domain\Events\EventHandler;
use App\Domain\Events\UserRegisteredEvent;

class SendWelcomeEmailHandler implements EventHandler
{
    public function execute(DomainEvent $event): void
    {
        if (!$event instanceof UserRegisteredEvent) {
            return;
        }

        $user = $event->eventData();

        $this->simulateEmailSending($user['userEmail'], $user['userName']);
    }
    
    private function simulateEmailSending(string $email, string $name): void
    {
        $emailData = [
            'to' => $email,
            'subject' => 'Bienvenido a nuestra plataforma!',
            'body' => "Hola {$name}, ¡Bienvenido a nuestra plataforma!",
            'sent_at' => date('Y-m-d H:i:s'),
        ];
        
        error_log(sprintf(
            "[EMAIL SIMULADO] Enviando correo de bienvenida:\n%s",
            json_encode($emailData, JSON_PRETTY_PRINT)
        ));
    }
} 