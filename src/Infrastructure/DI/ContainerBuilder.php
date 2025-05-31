<?php

declare(strict_types=1);

namespace App\Infrastructure\DI;

use DI\Container;
use DI\ContainerBuilder as DIContainerBuilder;
use Exception;

class ContainerBuilder
{
  private DIContainerBuilder $builder;

  public function __construct()
  {
    $this->builder = new DIContainerBuilder();
  }

  /**
   * Configure and build the dependency injection container
   *
   * @return Container
   * @throws Exception
   */
  public function build(): Container
  {
    // Add definitions files
    $this->addDefinitions();

    // Configure compiler options in production
    // if ($_ENV['APP_ENV'] === 'production') {
    //   $this->builder->enableCompilation(__DIR__ . '/../../../../var/cache/di');
    //   $this->builder->writeProxiesToFile(true, __DIR__ . '/../../../../var/cache/proxies');
    // }

    return $this->builder->build();
  }

  /**
   * Add definitions files to the container constructor
   */
  private function addDefinitions(): void
  {
    $this->builder->addDefinitions(
        array_merge(
            require __DIR__ . '/Definitions/Repositories.php',
            require __DIR__ . '/Definitions/Infrastructure.php',
            require __DIR__ . '/Definitions/Controllers.php'
        )
    );
  }
}
