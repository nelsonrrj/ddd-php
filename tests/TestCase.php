<?php

declare(strict_types=1);

namespace Tests;

use App\App;
use DI\Container;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class TestCase extends PHPUnitTestCase
{
    public App $app;
    public Container $container;

    public function setUp(): void
    {
        $this->app = App::getInstance();
        $this->container = $this->app->getContainer();
    }
}
