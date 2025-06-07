<?php

namespace Tests\Unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Domain\ValueObjects\UserPassword;
use App\Domain\Exceptions\WeakPasswordException;

class UserPasswordTest extends TestCase
{
  /**
   * Tests that a strong password meeting all requirements can be successfully created.
   * 
   * This test verifies that:
   * - A password with uppercase, lowercase, numbers, and special characters is accepted
   * - The password is properly hashed using PHP's password_hash function
   * - The original password can be verified against the hashed version
   * - Strong passwords that meet security requirements are allowed
   */
  public function testStrongPasswordCanBeCreated(): void
  {
    $password = 'StrongP4ssw0rd!';
    $userPassword = new UserPassword($password);

    $this->assertTrue(password_verify($password, (string)$userPassword));
  }

  /**
   * Tests that a password shorter than the minimum length is rejected.
   * 
   * This test verifies that:
   * - Passwords below the minimum length requirement throw WeakPasswordException
   * - Length validation is enforced at the value object level
   * - Security requirements prevent short, easily guessable passwords
   */
  public function testShortPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('Short1!');
  }

  /**
   * Tests that a password without uppercase letters is rejected.
   * 
   * This test verifies that:
   * - Passwords must contain at least one uppercase letter
   * - Character complexity requirements are enforced
   * - Password strength validation includes uppercase letter checking
   */
  public function testPasswordWithoutUppercaseThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('password123!');
  }

  /**
   * Tests that a password without numbers is rejected.
   * 
   * This test verifies that:
   * - Passwords must contain at least one numeric digit
   * - Numeric character requirements are enforced for security
   * - Password complexity validation includes number checking
   */
  public function testPasswordWithoutNumberThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('Password!');
  }

  /**
   * Tests that a password without special characters is rejected.
   * 
   * This test verifies that:
   * - Passwords must contain at least one special character
   * - Special character requirements enhance password security
   * - Password validation enforces character diversity requirements
   */
  public function testPasswordWithoutSpecialCharacterThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('Password123');
  }

  /**
   * Tests that an empty password is rejected.
   * 
   * This test verifies that:
   * - Empty passwords are not allowed
   * - Basic non-empty validation is enforced
   * - The value object prevents creation with no password content
   */
  public function testEmptyPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('');
  }

  /**
   * Tests that a password that is too short is rejected.
   * 
   * This test verifies that:
   * - Passwords below minimum length are rejected even with complexity
   * - Length requirements take precedence in validation
   * - Short passwords are considered weak regardless of character variety
   */
  public function testTooShortPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('Abc1!');
  }

  /**
   * Tests that a password containing only letters is rejected.
   * 
   * This test verifies that:
   * - Passwords with only alphabetic characters are considered weak
   * - Character diversity requirements prevent single-type passwords
   * - Letter-only passwords fail complexity validation
   */
  public function testOnlyLettersPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('PasswordOnly');
  }

  /**
   * Tests that a password containing only numbers is rejected.
   * 
   * This test verifies that:
   * - Numeric-only passwords are considered weak
   * - Password complexity requires character type diversity
   * - Number-only passwords fail security requirements
   */
  public function testOnlyNumbersPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('12345678');
  }

  /**
   * Tests that a password containing only special characters is rejected.
   * 
   * This test verifies that:
   * - Special character-only passwords are considered weak
   * - Password validation requires mixed character types
   * - Single character type passwords fail complexity requirements
   */
  public function testOnlySpecialCharsPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('!@#$%^&*()');
  }
}
