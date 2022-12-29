<?php

/**
 * ApiUsers.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace Xsga\FilmAffinityApi\Entities;

/**
 * Import dependencies.
 */
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * ApiUsers
 *
 * @ORM\Table(name="api_users", uniqueConstraints={@ORM\UniqueConstraint(name="email_unique_idx", columns={"email"})})
 * @ORM\Entity
 */
class ApiUsers
{
    /**
     * User ID.
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id = 0;

    /**
     * User email.
     *
     * @ORM\Column(name="email", type="string", length=150, precision=0, scale=0, nullable=false, unique=true)
     */
    private string $email;

    /**
     * User password.
     *
     * @ORM\Column(name="password", type="string", length=500, precision=0, scale=0, nullable=false, unique=false)
     */
    private string $password;

    /**
     * User role.
     *
     * @ORM\Column(name="role", type="string", length=15, precision=0, scale=0, nullable=false, unique=false)
     */
    private string $role;

    /**
     * Create date.
     *
     * @ORM\Column(name="create_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private DateTime $createDate;

    /**
     * User enabled.
     *
     * @ORM\Column(name="enabled", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private int $enabled;

    /**
     * Get id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set email.
     */
    public function setEmail(string $email): ApiUsers
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set password.
     */
    public function setPassword(string $password): ApiUsers
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set role.
     */
    public function setRole(string $role): ApiUsers
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Set createDate.
     */
    public function setCreateDate(DateTime $createDate): ApiUsers
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate.
     */
    public function getCreateDate(): DateTime
    {
        return $this->createDate;
    }

    /**
     * Set enabled.
     */
    public function setEnabled(int $enabled): ApiUsers
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled.
     */
    public function getEnabled(): int
    {
        return $this->enabled;
    }
}
