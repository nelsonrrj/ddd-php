<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\UserEntity;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\UserEmail;

interface UserRepository
{
  public function save(UserEntity $user): UserEntity;
  public function findById(UserId $id): UserEntity | null;
  public function delete(UserId $id): void;
  public function findByEmail(UserEmail $email): UserEntity | null;
}
