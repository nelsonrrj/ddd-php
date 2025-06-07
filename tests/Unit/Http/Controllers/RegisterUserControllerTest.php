<?php

namespace Tests\Unit\Http\Controllers;

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\RegisterUserUserCase;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\UserEmail;
use App\Domain\ValueObjects\UserName;
use App\Domain\ValueObjects\UserPassword;
use App\Domain\Entities\UserEntity;
use App\Domain\Exceptions\UserAlreadyExistsException;
use App\Infrastructure\Controllers\RegisterUserController;
use Tests\Mocks\Domain\Repositories\MockUserRepository;

class RegisterUserControllerTest extends TestCase
{
  private RegisterUserController $controller;
  private MockUserRepository $userRepository;
  private RegisterUserUserCase $registerUserUseCase;

  protected function setUp(): void
  {
    $this->userRepository = new MockUserRepository();
    $this->registerUserUseCase = new RegisterUserUserCase($this->userRepository);
    $this->controller = new RegisterUserController($this->registerUserUseCase);
  }

  /**
   * Tests the successful registration of a user through the controller.
   * 
   * This test verifies that:
   * - The controller can process user registration data from an array format
   * - The registration process returns proper response data
   * - The response contains all required user fields (name, email, id, createdAt)
   * - The controller properly delegates to the use case layer
   * - The returned data matches the input data
   */
  public function testRegisterUser(): void
  {
    $userData = [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => '1234AD.qwed'
    ];

    $response = $this->controller->register($userData);
    $data = json_decode(json_encode($response->data), true);

    $this->assertEquals('Test User', $data['name']);
    $this->assertEquals('test@example.com', $data['email']);
    $this->assertArrayHasKey('id', $data);
    $this->assertArrayHasKey('createdAt', $data);
  }

  /**
   * Tests that attempting to register a user with an existing email throws an exception.
   * 
   * This test verifies that:
   * - An existing user can be saved to the repository
   * - The controller properly handles duplicate email scenarios
   * - UserAlreadyExistsException is thrown when attempting to register with existing email
   * - The controller enforces business rules about unique email addresses
   * - Exception handling works correctly at the controller level
   */
  public function testRegisterExistingUser(): void
  {
    $email = 'existing@example.com';
    $existingUser = new UserEntity(
      id: new UserId(1),
      email: new UserEmail($email),
      name: new UserName('Existing User'),
      password: new UserPassword('1234AD.qwed')
    );

    $this->userRepository->save($existingUser);

    $userData = [
      'name' => 'Another User',
      'email' => $email,
      'password' => '1234AD.qwed'
    ];

    $this->expectException(UserAlreadyExistsException::class);
    $this->controller->register($userData);
  }
}
