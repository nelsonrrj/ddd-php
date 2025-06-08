<?php

declare(strict_types=1);

use App\Infrastructure\Controllers\RegisterUserController;

return [
    '/users' => [RegisterUserController::class, 'register', 'POST'],
]; 