<?php

declare(strict_types=1);

namespace App\Infrastructure\DI\Definitions;

use App\Domain\Repositories\UserRepository;
use App\Infrastructure\Persistence\Repositories\DoctrineUserRepository;

use function DI\autowire;

return [
    UserRepository::class => autowire(DoctrineUserRepository::class),
];
