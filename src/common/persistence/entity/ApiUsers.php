<?php

namespace api\common\persistence\entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiUsers
 *
 * @ORM\Table(name="api_users", uniqueConstraints={@ORM\UniqueConstraint(name="email_unique_idx", columns={"email"})})
 * @ORM\Entity
 */
class ApiUsers
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, precision=0, scale=0, nullable=false, unique=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=500, precision=0, scale=0, nullable=false, unique=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname_1", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $surname1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="surname_2", type="string", length=100, precision=0, scale=0, nullable=true, unique=false)
     */
    private $surname2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date", precision=0, scale=0, nullable=false, unique=false)
     */
    private $birthdate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email_2", type="string", length=150, precision=0, scale=0, nullable=true, unique=false)
     */
    private $email2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_1", type="string", length=25, precision=0, scale=0, nullable=true, unique=false)
     */
    private $phone1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_2", type="string", length=25, precision=0, scale=0, nullable=true, unique=false)
     */
    private $phone2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $createDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $lastLogin;

    /**
     * @var int
     *
     * @ORM\Column(name="enabled", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $enabled;

    /**
     * @var string|null
     *
     * @ORM\Column(name="token", type="string", length=500, precision=0, scale=0, nullable=true, unique=false)
     */
    private $token;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return ApiUsers
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return ApiUsers
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return ApiUsers
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname1.
     *
     * @param string $surname1
     *
     * @return ApiUsers
     */
    public function setSurname1($surname1)
    {
        $this->surname1 = $surname1;

        return $this;
    }

    /**
     * Get surname1.
     *
     * @return string
     */
    public function getSurname1()
    {
        return $this->surname1;
    }

    /**
     * Set surname2.
     *
     * @param string|null $surname2
     *
     * @return ApiUsers
     */
    public function setSurname2($surname2 = null)
    {
        $this->surname2 = $surname2;

        return $this;
    }

    /**
     * Get surname2.
     *
     * @return string|null
     */
    public function getSurname2()
    {
        return $this->surname2;
    }

    /**
     * Set birthdate.
     *
     * @param \DateTime $birthdate
     *
     * @return ApiUsers
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate.
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set email2.
     *
     * @param string|null $email2
     *
     * @return ApiUsers
     */
    public function setEmail2($email2 = null)
    {
        $this->email2 = $email2;

        return $this;
    }

    /**
     * Get email2.
     *
     * @return string|null
     */
    public function getEmail2()
    {
        return $this->email2;
    }

    /**
     * Set phone1.
     *
     * @param string|null $phone1
     *
     * @return ApiUsers
     */
    public function setPhone1($phone1 = null)
    {
        $this->phone1 = $phone1;

        return $this;
    }

    /**
     * Get phone1.
     *
     * @return string|null
     */
    public function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * Set phone2.
     *
     * @param string|null $phone2
     *
     * @return ApiUsers
     */
    public function setPhone2($phone2 = null)
    {
        $this->phone2 = $phone2;

        return $this;
    }

    /**
     * Get phone2.
     *
     * @return string|null
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return ApiUsers
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate.
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set lastLogin.
     *
     * @param \DateTime $lastLogin
     *
     * @return ApiUsers
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin.
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set enabled.
     *
     * @param int $enabled
     *
     * @return ApiUsers
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return int
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set token.
     *
     * @param string|null $token
     *
     * @return ApiUsers
     */
    public function setToken($token = null)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token.
     *
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }
}
