<?php

declare(strict_types=1);

namespace App\Infrastructure\DI;

use DI\Container;

class ContainerFactory
{
    private static ?Container $container = null;

    /**
     * Get the container instance.
     *
     * @throws \Exception
     */
    public static function getContainer(): Container
    {
        if (null === self::$container) {
            $builder = new ContainerBuilder();
            self::$container = $builder->build();
        }

        return self::$container;
    }
}
