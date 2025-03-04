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
