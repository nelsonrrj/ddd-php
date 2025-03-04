<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\UserEntity;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\UserEmail;
use App\Domain\ValueObjects\UserName;
use App\Domain\ValueObjects\UserPassword;

class UserEntityTest extends TestCase
{
  private UserId $userId;
  private UserEmail $userEmail;
  private UserName $userName;
  private UserPassword $userPassword;
  private \DateTime $createdAt;

  protected function setUp(): void
  {
    $this->userId = new UserId(1);
    $this->userEmail = new UserEmail('test@example.com');
    $this->userName = new UserName('Test User');
    $this->userPassword = new UserPassword('1234AD.qwed');
    $this->createdAt = new \DateTime('2023-01-01 12:00:00');
  }

  public function testUserEntityCanBeCreated(): void
  {
    $user = new UserEntity(
      $this->userId,
      $this->userEmail,
      $this->userName,
      $this->userPassword,
      $this->createdAt
    );

    $this->assertSame($this->userId, $user->id);
    $this->assertSame($this->userEmail, $user->email);
    $this->assertSame($this->userName, $user->name);
    $this->assertSame($this->userPassword, $user->password);
    $this->assertSame($this->createdAt, $user->createdAt);
  }

  public function testUserEntityCanBeCreatedWithoutId(): void
  {
    $user = new UserEntity(
      null,
      $this->userEmail,
      $this->userName,
      $this->userPassword,
      $this->createdAt
    );

    $this->assertNull($user->id);
    $this->assertSame($this->userEmail, $user->email);
    $this->assertSame($this->userName, $user->name);
    $this->assertSame($this->userPassword, $user->password);
    $this->assertSame($this->createdAt, $user->createdAt);
  }

  public function testUserEntityCanBeCreatedWithDefaultCreatedAt(): void
  {
    $beforeCreation = new \DateTime();

    $user = new UserEntity(
      $this->userId,
      $this->userEmail,
      $this->userName,
      $this->userPassword
    );

    $afterCreation = new \DateTime();

    $this->assertSame($this->userId, $user->id);
    $this->assertSame($this->userEmail, $user->email);
    $this->assertSame($this->userName, $user->name);
    $this->assertSame($this->userPassword, $user->password);

    $this->assertGreaterThanOrEqual($beforeCreation, $user->createdAt);
    $this->assertLessThanOrEqual($afterCreation, $user->createdAt);
  }

  public function testUserEntityJsonSerialization(): void
  {
    $user = new UserEntity(
      $this->userId,
      $this->userEmail,
      $this->userName,
      $this->userPassword,
      $this->createdAt
    );

    $serialized = json_encode($user);
    $deserialized = json_decode($serialized, true);

    $this->assertIsArray($deserialized);
    $this->assertArrayHasKey('id', $deserialized);
    $this->assertArrayHasKey('email', $deserialized);
    $this->assertArrayHasKey('name', $deserialized);
    $this->assertArrayHasKey('createdAt', $deserialized);

    // Password should not be included in JSON serialization
    $this->assertArrayNotHasKey('password', $deserialized);

    $this->assertEquals((string) $this->userId, $deserialized['id']);
    $this->assertEquals((string) $this->userEmail, $deserialized['email']);
    $this->assertEquals((string) $this->userName, $deserialized['name']);
    $this->assertEquals($this->createdAt->format('Y-m-d H:i:s'), $deserialized['createdAt']);
  }
}
