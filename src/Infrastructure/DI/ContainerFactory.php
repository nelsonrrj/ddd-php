<?php

declare(strict_types=1);

namespace App\Infrastructure\DI;

use DI\Container;
use Exception;

class ContainerFactory
{
  private static ?Container $container = null;

  /**
   * Get the container instance
   *
   * @return Container
   * @throws Exception
   */
  public static function getContainer(): Container
  {
    if (self::$container === null) {
      $builder = new ContainerBuilder();
      self::$container = $builder->build();
    }

    return self::$container;
  }
}
