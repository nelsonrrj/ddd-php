<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\App;
use Dotenv\Dotenv;

// Cargar variables de entorno específicas para testing
$dotenv = Dotenv::createImmutable(__DIR__ . '/../', '.env.testing');
$dotenv->load();
