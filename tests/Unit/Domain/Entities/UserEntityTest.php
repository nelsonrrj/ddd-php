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

  /**
   * Tests that a UserEntity can be created with all parameters including ID and createdAt.
   * 
   * This test verifies that:
   * - A UserEntity can be instantiated with all required value objects
   * - All properties are properly assigned and accessible
   * - The entity maintains immutability by returning the same object references
   * - Both ID and createdAt can be explicitly set during construction
   */
  public function testUserEntityCanBeCreated(): void
  {
    $user = new UserEntity(
      id: $this->userId,
      email: $this->userEmail,
      name: $this->userName,
      password: $this->userPassword,
      createdAt: $this->createdAt
    );

    $this->assertSame($this->userId, $user->id);
    $this->assertSame($this->userEmail, $user->email);
    $this->assertSame($this->userName, $user->name);
    $this->assertSame($this->userPassword, $user->password);
    $this->assertSame($this->createdAt, $user->createdAt);
  }

  /**
   * Tests that a UserEntity can be created without an ID (for new entities).
   * 
   * This test verifies that:
   * - A UserEntity can be created with a null ID (typical for new entities before persistence)
   * - All other required properties are properly set
   * - The entity handles the null ID case gracefully
   * - This supports the common pattern of creating entities before database persistence
   */
  public function testUserEntityCanBeCreatedWithoutId(): void
  {
    $user = new UserEntity(
      id: null,
      email: $this->userEmail,
      name: $this->userName,
      password: $this->userPassword,
      createdAt: $this->createdAt
    );

    $this->assertNull($user->id);
    $this->assertSame($this->userEmail, $user->email);
    $this->assertSame($this->userName, $user->name);
    $this->assertSame($this->userPassword, $user->password);
    $this->assertSame($this->createdAt, $user->createdAt);
  }

  /**
   * Tests that a UserEntity automatically sets createdAt to current time when not provided.
   * 
   * This test verifies that:
   * - When createdAt is not provided, the entity sets it to the current timestamp
   * - The automatically set createdAt falls within the expected time range
   * - The entity provides sensible defaults for timestamp fields
   * - The creation time is captured accurately during entity instantiation
   */
  public function testUserEntityCanBeCreatedWithDefaultCreatedAt(): void
  {
    $beforeCreation = new \DateTime();

    $user = new UserEntity(
      id: $this->userId,
      email: $this->userEmail,
      name: $this->userName,
      password: $this->userPassword
    );

    $afterCreation = new \DateTime();

    $this->assertSame($this->userId, $user->id);
    $this->assertSame($this->userEmail, $user->email);
    $this->assertSame($this->userName, $user->name);
    $this->assertSame($this->userPassword, $user->password);

    $this->assertGreaterThanOrEqual($beforeCreation, $user->createdAt);
    $this->assertLessThanOrEqual($afterCreation, $user->createdAt);
  }

  /**
   * Tests that UserEntity can be properly serialized to JSON with security considerations.
   * 
   * This test verifies that:
   * - The entity can be JSON serialized for API responses
   * - All public fields (id, email, name, createdAt) are included in serialization
   * - Sensitive data (password) is excluded from JSON serialization for security
   * - The serialized data maintains proper format and data types
   * - The JsonSerializable interface is properly implemented
   */
  public function testUserEntityJsonSerialization(): void
  {
    $user = new UserEntity(
      id: $this->userId,
      email: $this->userEmail,
      name: $this->userName,
      password: $this->userPassword,
      createdAt: $this->createdAt
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
