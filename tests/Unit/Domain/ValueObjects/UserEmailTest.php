<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\Exceptions\InvalidEmailException;
use App\Domain\ValueObjects\UserEmail;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class UserEmailTest extends TestCase
{
    /**
     * Tests that a valid email address can be successfully created as a UserEmail value object.
     *
     * This test verifies that:
     * - A properly formatted email address is accepted by the UserEmail constructor
     * - The email value can be retrieved as a string using __toString()
     * - The email value can be JSON serialized properly
     * - The value object maintains the original email format
     */
    public function testValidEmailCanBeCreated(): void
    {
        $email = 'test@example.com';
        $userEmail = new UserEmail($email);

        $this->assertEquals($email, (string) $userEmail);
        $this->assertEquals($email, $userEmail->jsonSerialize());
    }

    /**
     * Tests that an invalid email format throws an InvalidEmailException.
     *
     * This test verifies that:
     * - Email addresses that don't follow proper format are rejected
     * - The appropriate domain exception is thrown for invalid formats
     * - Input validation is enforced at the value object level
     */
    public function testInvalidEmailThrowsException(): void
    {
        $this->expectException(InvalidEmailException::class);
        new UserEmail('invalid-email');
    }

    /**
     * Tests that an empty string is rejected as an invalid email.
     *
     * This test verifies that:
     * - Empty email addresses are not allowed
     * - The value object enforces non-empty email requirements
     * - Proper validation prevents creation of empty email value objects
     */
    public function testEmptyStringThrowsException(): void
    {
        $this->expectException(InvalidEmailException::class);
        new UserEmail('');
    }

    /**
     * Tests that an email without the @ symbol is rejected.
     *
     * This test verifies that:
     * - Email addresses must contain the @ symbol to be valid
     * - Basic email format validation is enforced
     * - Malformed email addresses are properly rejected
     */
    public function testMissingAtSymbolThrowsException(): void
    {
        $this->expectException(InvalidEmailException::class);
        new UserEmail('testexample.com');
    }

    /**
     * Tests that an email with @ but no domain is rejected.
     *
     * This test verifies that:
     * - Email addresses must have a domain part after the @ symbol
     * - Incomplete email formats are properly validated
     * - The domain portion is required for a valid email
     */
    public function testMissingDomainThrowsException(): void
    {
        $this->expectException(InvalidEmailException::class);
        new UserEmail('test@');
    }

    /**
     * Tests that an email with an invalid domain format is rejected.
     *
     * This test verifies that:
     * - Domain names must follow proper format (include TLD)
     * - Email validation includes domain format checking
     * - Incomplete domain names are not accepted
     */
    public function testInvalidDomainThrowsException(): void
    {
        $this->expectException(InvalidEmailException::class);
        new UserEmail('test@example');
    }

    /**
     * Tests that an email containing spaces is rejected.
     *
     * This test verifies that:
     * - Email addresses cannot contain whitespace characters
     * - Proper character validation is enforced
     * - Invalid characters in email addresses are detected and rejected
     */
    public function testSpacesInEmailThrowsException(): void
    {
        $this->expectException(InvalidEmailException::class);
        new UserEmail('test user@example.com');
    }
}
