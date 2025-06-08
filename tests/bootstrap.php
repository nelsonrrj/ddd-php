<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar variables de entorno específicas para testing
$dotenv = Dotenv::createImmutable(__DIR__ . '/../', '.env.testing');
$dotenv->load();
