<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\UserEntity;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\UserEmail;
use App\Domain\ValueObjects\UserId;
use App\Infrastructure\Persistence\Entities\DoctrineUserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineUserRepository extends EntityRepository implements UserRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager, $entityManager->getClassMetadata(DoctrineUserEntity::class));
    }

    public function save(UserEntity $user): UserEntity
    {
        $this->entityManager->persist(DoctrineUserEntity::fromUserEntity($user));
        $this->entityManager->flush();

        return $this->findByEmail($user->email);
    }

    public function findById(UserId $id): ?UserEntity
    {
        return $this->find($id)->toUserEntity();
    }

    public function findByEmail(UserEmail $email): ?UserEntity
    {
        $result = $this->findOneBy(['email' => $email]);

        if (!$result) {
            return null;
        }

        return $result->toUserEntity();
    }

    public function delete(UserId $id): void
    {
        $user = $this->findById($id);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
