<?php

declare(strict_types=1);

namespace App;

use App\Infrastructure\DI\ContainerFactory;
use DI\Container;

class App
{
    private static ?App $instance = null;

    private Container $container;

    private function __construct()
    {
        $this->container = ContainerFactory::getContainer();
    }

    public static function getInstance(): App
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getContainer(): Container
    {
        return $this->container;
    }
}
