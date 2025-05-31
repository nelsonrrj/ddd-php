<?php

declare(strict_types=1);

namespace App\Infrastructure\DI\Definitions;

use App\Domain\Repositories\UserRepository;
use App\Infrastructure\Persistence\Repositories\DoctrineUserRepository;

return [
  // Map of repository interfaces to their implementations
  UserRepository::class => \DI\autowire(DoctrineUserRepository::class),
];
