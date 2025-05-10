<?php

namespace App\Infrastructure\Persistence;

class DatabaseConnectionParams
{
  public function __construct(
    public string $driver,
    public string $host,
    public int $port,
    public string $dbname,
    public string $user,
    public string $password,
  ) {}

  public function toArray(): array
  {
    return [
      'driver' => $this->driver,
      'host' => $this->host,
      'port' => $this->port,
      'dbname' => $this->dbname,
      'user' => $this->user,
      'password' => $this->password,
    ];
  }
}