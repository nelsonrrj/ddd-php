<?php

namespace Tests\Integration\Http\Controllers;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\RegisterUserController;
use App\Application\UseCases\RegisterUserUserCase;
use App\Application\DTO\UserResponseDTO;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\UserEmail;
use App\Infrastructure\Repositories\DoctrineUserRepository;
use Tests\Infrastructure\Persistence\DatabaseTestConnection;

class RegisterUserControllerIntegrationTest extends TestCase
{
  private RegisterUserController $controller;
  private UserRepository $userRepository;
  private RegisterUserUserCase $registerUserUseCase;
  private DatabaseTestConnection $dbConnection;

  protected function setUp(): void
  {
    $this->dbConnection = new DatabaseTestConnection();
    $entityManager = $this->dbConnection->getEntityManager();

    $this->userRepository = new DoctrineUserRepository($entityManager);

    $this->registerUserUseCase = new RegisterUserUserCase($this->userRepository);

    $this->controller = new RegisterUserController($this->registerUserUseCase);
  }

  public function testRegisterUser(): void
  {
    $userData = [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => '1234AD.qwed'
    ];

    $response = $this->controller->register($userData);

    $this->assertInstanceOf(UserResponseDTO::class, $response);

    $responseData = json_decode(json_encode($response), true);

    $this->assertEquals($userData['name'], $responseData['name']);
    $this->assertEquals($userData['email'], $responseData['email']);
    $this->assertArrayHasKey('id', $responseData);
    $this->assertArrayHasKey('createdAt', $responseData);

    $savedUser = $this->userRepository->findByEmail(new UserEmail($userData['email']));
    $this->assertNotNull($savedUser);
    $this->assertEquals($userData['name'], (string)$savedUser->name);
  }
}
