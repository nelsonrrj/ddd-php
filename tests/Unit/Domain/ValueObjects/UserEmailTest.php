<?php

namespace Tests\Unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Domain\ValueObjects\UserEmail;
use App\Domain\Exceptions\InvalidEmailException;

class UserEmailTest extends TestCase
{
  public function testValidEmailCanBeCreated(): void
  {
    $email = 'test@example.com';
    $userEmail = new UserEmail($email);

    $this->assertEquals($email, (string)$userEmail);
    $this->assertEquals($email, $userEmail->jsonSerialize());
  }

  public function testInvalidEmailThrowsException(): void
  {
    $this->expectException(InvalidEmailException::class);
    new UserEmail('invalid-email');
  }

  public function testEmptyStringThrowsException(): void
  {
    $this->expectException(InvalidEmailException::class);
    new UserEmail('');
  }

  public function testMissingAtSymbolThrowsException(): void
  {
    $this->expectException(InvalidEmailException::class);
    new UserEmail('testexample.com');
  }

  public function testMissingDomainThrowsException(): void
  {
    $this->expectException(InvalidEmailException::class);
    new UserEmail('test@');
  }

  public function testInvalidDomainThrowsException(): void
  {
    $this->expectException(InvalidEmailException::class);
    new UserEmail('test@example');
  }

  public function testSpacesInEmailThrowsException(): void
  {
    $this->expectException(InvalidEmailException::class);
    new UserEmail('test user@example.com');
  }
}
