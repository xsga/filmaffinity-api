<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Persistence\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Table(name="api_users", uniqueConstraints={@ORM\UniqueConstraint(name="email_unique_idx", columns={"email"})})
 * @ORM\Entity
 */
class DoctrineApiUsersEntity
{
    /**
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id = 0;

    /**
     * @ORM\Column(name="email", type="string", length=150, precision=0, scale=0, nullable=false, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(name="password", type="string", length=500, precision=0, scale=0, nullable=false, unique=false)
     */
    private string $password;

    /**
     * @ORM\Column(name="role", type="string", length=15, precision=0, scale=0, nullable=false, unique=false)
     */
    private string $role;

    /**
     * @ORM\Column(name="create_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private DateTime $createDate;

    /**
     * @ORM\Column(name="enabled", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private int $enabled;

    public function getId(): int
    {
        return $this->id;
    }

    public function setEmail(string $email): DoctrineApiUsersEntity
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): DoctrineApiUsersEntity
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setRole(string $role): DoctrineApiUsersEntity
    {
        $this->role = $role;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setCreateDate(DateTime $createDate): DoctrineApiUsersEntity
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getCreateDate(): DateTime
    {
        return $this->createDate;
    }

    public function setEnabled(int $enabled): DoctrineApiUsersEntity
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getEnabled(): int
    {
        return $this->enabled;
    }
}
