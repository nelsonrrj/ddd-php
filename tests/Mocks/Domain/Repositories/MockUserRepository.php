<?php

namespace Tests\Mocks\Domain\Repositories;

use App\Domain\Entities\UserEntity;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\UserEmail;

class MockUserRepository implements UserRepository
{
  private array $users = [];
  private int $nextId = 1;

  public function save(UserEntity $user): UserEntity
  {
    $savedUser = new UserEntity(
      id: new UserId($this->nextId++),
      email: $user->email,
      name: $user->name,
      password: $user->password,
      createdAt: $user->createdAt
    );

    $this->users[] = $savedUser;

    return $savedUser;
  }

  public function findById(UserId $id): UserEntity|null
  {
    foreach ($this->users as $user) {
      if ((string) $user->id === (string) $id) {
        return $user;
      }
    }

    return null;
  }

  public function delete(UserId $id): void
  {
    $this->users = array_filter(
      $this->users,
      fn($user) => $user->id !== $id
    );
  }

  public function findByEmail(UserEmail $email): UserEntity|null
  {
    foreach ($this->users as $user) {
      if ((string) $user->email === (string) $email) {
        return $user;
      }
    }

    return null;
  }
}
