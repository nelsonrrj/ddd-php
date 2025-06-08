<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

class DatabaseConnectionParams
{
    public function __construct(
        public readonly string $driver,
        public readonly string $host,
        public readonly int $port,
        public readonly string $dbname,
        public readonly string $user,
        public readonly string $password,
        public readonly string $charset = 'utf8mb4',
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
            'charset' => $this->charset,
        ];
    }
}
