<?php

declare(strict_types=1);

namespace App\Infrastructure\DI\Definitions;

use App\Infrastructure\Controllers\RegisterUserController;

use function DI\autowire;

return [
  RegisterUserController::class => autowire(RegisterUserController::class),
];
