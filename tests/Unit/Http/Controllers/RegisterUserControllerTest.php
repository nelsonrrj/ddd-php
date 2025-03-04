<?php

namespace Tests\Unit\Http\Controllers;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\RegisterUserController;
use App\Application\UseCases\RegisterUserUserCase;
use App\Application\DTO\UserResponseDTO;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\UserEmail;
use App\Domain\ValueObjects\UserName;
use App\Domain\ValueObjects\UserPassword;
use App\Domain\Entities\UserEntity;
use App\Domain\Exceptions\UserAlreadyExistsException;
use Tests\Mocks\Domain\Repositories\MockUserRepository;
use App\Infrastructure\Persistence\DatabaseConnection;

class RegisterUserControllerTest extends TestCase
{
  private RegisterUserController $controller;
  private MockUserRepository $userRepository;
  private RegisterUserUserCase $registerUserUseCase;

  protected function setUp(): void
  {
    // Usar el repositorio mock en lugar de uno real que se conecte a la base de datos
    $this->userRepository = new MockUserRepository();
    $this->registerUserUseCase = new RegisterUserUserCase($this->userRepository);
    $this->controller = new RegisterUserController($this->registerUserUseCase);
  }

  public function testRegisterUser(): void
  {
    // Datos de prueba
    $userData = [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => '1234AD.qwed'
    ];

    // Ejecutar el método register del controlador
    $response = $this->controller->register($userData);

    // Verificar que la respuesta es del tipo correcto
    $this->assertInstanceOf(UserResponseDTO::class, $response);

    // Convertir la respuesta a array para verificar los datos
    $responseData = json_decode(json_encode($response), true);

    // Verificar los datos de la respuesta
    $this->assertEquals('Test User', $responseData['name']);
    $this->assertEquals('test@example.com', $responseData['email']);
    $this->assertArrayHasKey('id', $responseData);
    $this->assertArrayHasKey('createdAt', $responseData);
  }

  public function testRegisterExistingUser(): void
  {
    // Crear un usuario existente
    $email = 'existing@example.com';
    $existingUser = new UserEntity(
      id: new UserId(1),
      email: new UserEmail($email),
      name: new UserName('Existing User'),
      password: new UserPassword('1234AD.qwed')
    );

    // Guardar el usuario en el repositorio mock
    $this->userRepository->save($existingUser);

    // Datos para intentar registrar un usuario con el mismo email
    $userData = [
      'name' => 'Another User',
      'email' => $email,
      'password' => '1234AD.qwed'
    ];

    // Verificar que se lanza la excepción esperada
    $this->expectException(UserAlreadyExistsException::class);
    $this->controller->register($userData);
  }
}
