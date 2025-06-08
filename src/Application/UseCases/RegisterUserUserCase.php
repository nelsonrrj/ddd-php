<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTO\RegisterRequestDTO;
use App\Application\DTO\UserResponseDTO;
use App\Domain\Entities\UserEntity;
use App\Domain\Exceptions\UserAlreadyExistsException;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\UserEmail;
use App\Domain\ValueObjects\UserName;
use App\Domain\ValueObjects\UserPassword;

class RegisterUserUserCase
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function execute(RegisterRequestDTO $request): UserResponseDTO
    {
        $user = new UserEntity(
            email: new UserEmail($request->email),
            name: new UserName($request->name),
            password: new UserPassword($request->password),
        );

        $userExists = $this->userRepository->findByEmail($user->email);

        if ($userExists) {
            throw new UserAlreadyExistsException($user->email);
        }

        $newUser = $this->userRepository->save($user);

        return new UserResponseDTO($newUser);
    }
}
