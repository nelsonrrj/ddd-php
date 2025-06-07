<?php

namespace Tests\Unit\Application\UseCases;

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\RegisterUserUserCase;
use App\Application\DTO\RegisterRequestDTO;
use App\Domain\Entities\UserEntity;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\UserEmail;
use App\Domain\ValueObjects\UserName;
use App\Domain\ValueObjects\UserPassword;
use App\Domain\Exceptions\UserAlreadyExistsException;
use Tests\Mocks\Domain\Repositories\MockUserRepository;

class RegisterUserUserCaseTest extends TestCase
{
  private MockUserRepository $userRepository;
  private RegisterUserUserCase $registerUserUseCase;
  private UserPassword $strongPassword;

  protected function setUp(): void
  {
    $this->userRepository = new MockUserRepository();
    $this->registerUserUseCase = new RegisterUserUserCase($this->userRepository);
    $this->strongPassword = new UserPassword('1234AD.qwed');
  }

  /**
   * Tests the successful registration of a new user through the use case.
   * 
   * This test verifies that:
   * - A new user can be registered with valid data using RegisterRequestDTO
   * - The use case returns a proper response DTO with user information
   * - The response contains all required fields (id, name, email, createdAt)
   * - The returned data matches the input data
   * - The user registration process works end-to-end at the application layer
   */
  public function testRegisterNewUser(): void
  {
    $requestDTO = new RegisterRequestDTO(
      name: 'Test User',
      email: 'test@example.com',
      password: $this->strongPassword
    );

    $responseDTO = $this->registerUserUseCase->execute($requestDTO);
    $arrayResponse = json_decode(json_encode($responseDTO), true);

    $this->assertEquals('Test User', $arrayResponse['name']);
    $this->assertEquals('test@example.com', $arrayResponse['email']);

    $this->assertArrayHasKey('id', $arrayResponse);
    $this->assertArrayHasKey('email', $arrayResponse);
    $this->assertArrayHasKey('name', $arrayResponse);
    $this->assertArrayHasKey('createdAt', $arrayResponse);
  }

  /**
   * Tests that attempting to register a user with an existing email throws an exception.
   * 
   * This test verifies that:
   * - An existing user can be saved to the mock repository
   * - Attempting to register another user with the same email throws UserAlreadyExistsException
   * - The use case properly validates for duplicate emails before creating new users
   * - The business rule preventing duplicate registrations is enforced at the application layer
   */
  public function testRegisterExistingUserThrowsException(): void
  {
    $email = 'existing@example.com';

    $existingUser = new UserEntity(
      id: new UserId(1),
      email: new UserEmail($email),
      name: new UserName('Existing User'),
      password: $this->strongPassword
    );

    $this->userRepository->save($existingUser);

    $requestDTO = new RegisterRequestDTO(
      name: 'Another User',
      email: $email,
      password: $this->strongPassword
    );

    $this->expectException(UserAlreadyExistsException::class);
    $this->registerUserUseCase->execute($requestDTO);
  }
}
