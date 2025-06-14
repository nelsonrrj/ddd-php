<?php

declare(strict_types=1);

namespace App\Infrastructure\DI\Definitions;

use App\Domain\Events\EventDispatcher;
use App\Domain\Events\UserRegisteredEvent;
use App\Infrastructure\Events\Handlers\SendWelcomeEmailHandler;
use App\Infrastructure\Events\SimpleEventDispatcher;
use App\Infrastructure\Persistence\DatabaseConnection;
use App\Infrastructure\Persistence\DatabaseConnectionParams;

use DI\Container;
use Doctrine\ORM\EntityManagerInterface;

use function DI\autowire;

return [
    DatabaseConnectionParams::class => function () {
        return new DatabaseConnectionParams(
            driver: getenv('DB_DRIVER'),
            host: getenv('DB_HOST'),
            port: (int) getenv('DB_PORT'),
            dbname: getenv('DB_DATABASE'),
            user: getenv('DB_USERNAME'),
            password: getenv('DB_PASSWORD'),
        );
    },

    DatabaseConnection::class => function (Container $container) {
        $entitiesPath = __DIR__ . '/../../../../src/Infrastructure/Persistence/Entities';

        return new DatabaseConnection($container->get(DatabaseConnectionParams::class), $entitiesPath);
    },

    EntityManagerInterface::class => function (Container $container) {
        return $container->get(DatabaseConnection::class)->getEntityManager();
    },

    SendWelcomeEmailHandler::class => autowire(SendWelcomeEmailHandler::class),

    EventDispatcher::class => function (Container $container) {
        $dispatcher = new SimpleEventDispatcher();

        // Register the event listener
        $dispatcher->addListener(UserRegisteredEvent::class, $container->get(SendWelcomeEmailHandler::class));

        return $dispatcher;
    },
];
