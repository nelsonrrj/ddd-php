<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\UserRepository;
use App\Domain\Entities\UserEntity;
use App\Domain\ValueObjects\UserEmail;
use App\Domain\ValueObjects\UserName;
use App\Domain\ValueObjects\UserPassword;
use App\Application\DTO\RegisterRequestDTO;
use App\Application\DTO\UserResponseDTO;
use App\Domain\Exceptions\UserAlreadyExistsException;
use App\Domain\Events\EventDispatcher;
use App\Domain\Events\UserRegisteredEvent;

class RegisterUserUserCase
{
  public function __construct(
    private UserRepository $userRepository,
    private EventDispatcher $eventDispatcher
  ) {}

  public function execute(RegisterRequestDTO $request): UserResponseDTO
  {
    $user = new UserEntity(
      id: null,
      email: new UserEmail($request->email),
      name: new UserName($request->name),
      password: new UserPassword($request->password),
    );

    $userExists = $this->userRepository->findByEmail($user->email);

    if ($userExists) {
      throw new UserAlreadyExistsException($user->email);
    }

    $newUser = $this->userRepository->save($user);

    $this->eventDispatcher->dispatch(new UserRegisteredEvent($newUser));

    return new UserResponseDTO($newUser);
  }
}
