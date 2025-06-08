<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\UserEntity;
use App\Domain\ValueObjects\UserEmail;
use App\Domain\ValueObjects\UserId;

interface UserRepository
{
    public function save(UserEntity $user): UserEntity;

    public function findById(UserId $id): ?UserEntity;

    public function delete(UserId $id): void;

    public function findByEmail(UserEmail $email): ?UserEntity;
}
