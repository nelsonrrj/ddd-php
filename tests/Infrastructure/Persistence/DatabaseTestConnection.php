<?php

namespace Tests\Infrastructure\Persistence;

use App\Infrastructure\Persistence\DatabaseConnection;

class DatabaseTestConnection extends DatabaseConnection
{
  public function __construct()
  {
    // Cargar la configuración de la base de datos de prueba
    $testConnectionParams = require __DIR__ . '/../../Config/TestDatabaseConfig.php';

    // Llamar al constructor padre con la configuración de prueba
    parent::__construct($testConnectionParams, true);
  }
}
