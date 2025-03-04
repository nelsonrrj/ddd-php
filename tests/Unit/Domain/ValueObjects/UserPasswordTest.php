<?php

namespace Tests\Unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Domain\ValueObjects\UserPassword;
use App\Domain\Exceptions\WeakPasswordException;

class UserPasswordTest extends TestCase
{
  public function testStrongPasswordCanBeCreated(): void
  {
    $password = 'StrongP4ssw0rd!';
    $userPassword = new UserPassword($password);

    $this->assertNotEquals($password, (string)$userPassword);
    $this->assertNotEquals($password, $userPassword->jsonSerialize());

    $this->assertTrue(password_verify($password, (string)$userPassword));
  }

  public function testShortPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('Short1!');
  }

  public function testPasswordWithoutUppercaseThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('password123!');
  }

  public function testPasswordWithoutNumberThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('Password!');
  }

  public function testPasswordWithoutSpecialCharacterThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('Password123');
  }

  public function testEmptyPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('');
  }

  public function testTooShortPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('Abc1!');
  }

  public function testOnlyLettersPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('PasswordOnly');
  }

  public function testOnlyNumbersPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('12345678');
  }

  public function testOnlySpecialCharsPasswordThrowsException(): void
  {
    $this->expectException(WeakPasswordException::class);
    new UserPassword('!@#$%^&*()');
  }
}
