<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Persistence\Infrastructure\Doctrine;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[Table(name: "app_users")]
#[UniqueConstraint(name: "email_unique_idx", columns: ["email"])]
final class UsersEntity
{
    #[Id]
    #[Column(name: "id", type: "integer", precision: 0, scale: 0, nullable: false, unique: true)]
    #[GeneratedValue(strategy: "IDENTITY")]
    private ?int $userId = null;

    #[Column(name: "email", type: "string", length: 150, precision: 0, scale: 0, nullable: false, unique: true)]
    private string $email = '';

    #[Column(name: "password", type: "string", length: 500, precision: 0, scale: 0, nullable: false, unique: false)]
    private string $password = '';

    #[Column(name: "name", type: "string", length: 100, precision: 0, scale: 0, nullable: false, unique: false)]
    private string $name = '';

    #[Column(name: "create_date", type: "datetime", precision: 0, scale: 0, nullable: false, unique: false)]
    private DateTime $createDate;

    #[Column(name: "update_date", type: "datetime", precision: 0, scale: 0, nullable: false, unique: false)]
    private DateTime $updateDate;

    #[Column(name: "status", type: "boolean", precision: 0, scale: 0, nullable: false, unique: false)]
    private bool $status = true;

    public function __construct()
    {
        $this->createDate = new DateTime();
        $this->updateDate = new DateTime();
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setEmail(string $email): UsersEntity
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): UsersEntity
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setName(string $name): UsersEntity
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setCreateDate(DateTime $createDate): UsersEntity
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getCreateDate(): DateTime
    {
        return $this->createDate;
    }

    public function setUpdateDate(DateTime $updateDate): UsersEntity
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getUpdateDate(): DateTime
    {
        return $this->updateDate;
    }

    public function setStatus(bool $status): UsersEntity
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }
}
