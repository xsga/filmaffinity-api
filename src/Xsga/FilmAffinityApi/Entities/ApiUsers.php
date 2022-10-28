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
     * @var int
     *
     * @access private
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = 0;

    /**
     * User email.
     *
     * @var string
     *
     * @access private
     *
     * @ORM\Column(name="email", type="string", length=150, precision=0, scale=0, nullable=false, unique=true)
     */
    private $email;

    /**
     * User password.
     *
     * @var string
     *
     * @access private
     *
     * @ORM\Column(name="password", type="string", length=500, precision=0, scale=0, nullable=false, unique=false)
     */
    private $password;

    /**
     * User role.
     *
     * @var string
     *
     * @access private
     *
     * @ORM\Column(name="role", type="string", length=15, precision=0, scale=0, nullable=false, unique=false)
     */
    private $role;

    /**
     * Create date.
     *
     * @var DateTime
     *
     * @access private
     *
     * @ORM\Column(name="create_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $createDate;

    /**
     * User enabled.
     *
     * @var int
     *
     * @access private
     *
     * @ORM\Column(name="enabled", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $enabled;

    /**
     * Get id.
     *
     * @return int
     *
     * @access public
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return ApiUsers
     *
     * @access public
     */
    public function setEmail($email): ApiUsers
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     *
     * @access public
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return ApiUsers
     *
     * @access public
     */
    public function setPassword($password): ApiUsers
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     *
     * @access public
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set role.
     *
     * @param string $role
     *
     * @return ApiUsers
     *
     * @access public
     */
    public function setRole($role): ApiUsers
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return string
     *
     * @access public
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Set createDate.
     *
     * @param DateTime $createDate
     *
     * @return ApiUsers
     *
     * @access public
     */
    public function setCreateDate($createDate): ApiUsers
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate.
     *
     * @return DateTime
     *
     * @access public
     */
    public function getCreateDate(): DateTime
    {
        return $this->createDate;
    }

    /**
     * Set enabled.
     *
     * @param int $enabled
     *
     * @return ApiUsers
     *
     * @access public
     */
    public function setEnabled($enabled): ApiUsers
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return int
     *
     * @access public
     */
    public function getEnabled(): int
    {
        return $this->enabled;
    }
}
