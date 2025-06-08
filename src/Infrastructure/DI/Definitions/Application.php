<?php

declare(strict_types=1);

namespace App\Infrastructure\DI\Definitions;

use App\Application\UseCases\RegisterUserUserCase;

use function DI\autowire;

return [
    RegisterUserUserCase::class => autowire(RegisterUserUserCase::class),
];
