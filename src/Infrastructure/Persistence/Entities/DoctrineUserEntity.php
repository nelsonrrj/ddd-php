<?php

namespace App\Infrastructure\Persistence\Entities;

use App\Domain\Entities\UserEntity;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\UserEmail;
use App\Domain\ValueObjects\UserName;
use App\Domain\ValueObjects\UserPassword;
use App\Infrastructure\Persistence\Repositories\DoctrineUserRepository;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineUserRepository::class)]
#[ORM\Table(name: 'users')]
class DoctrineUserEntity
{
  public function __construct(
    string $email,
    string $name,
    string $password,
    \DateTime $createdAt,
  ) {
    $this->email = $email;
    $this->name = $name;
    $this->password = $password;
    $this->createdAt = $createdAt;
  }

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  public ?int $id;

  #[ORM\Column(type: 'string', length: 255, unique: true)]
  public string $email;

  #[ORM\Column(type: 'string', length: 255)]
  public string $name;

  #[ORM\Column(type: 'string', length: 255)]
  public string $password;

  #[ORM\Column(type: 'datetime')]
  public \DateTime $createdAt;

  public function toUserEntity(): UserEntity
  {
    return new UserEntity(
      id: new UserId($this->id),
      email: new UserEmail($this->email),
      name: new UserName($this->name),
      password: new UserPassword($this->password, true),
      createdAt: $this->createdAt,
    );
  }

  public static function fromUserEntity(UserEntity $user): self
  {
    $entity = new self(
      (string) $user->email,
      (string) $user->name,
      (string) $user->password,
      $user->createdAt,
    );
    $entity->id = $user->id ? (int) $user->id : null;
    return $entity;
  }
}
