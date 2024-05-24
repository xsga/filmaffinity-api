<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\App\Domain\Model;

use DateTime;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\Users\UserCreateDate;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\Users\UserEmail;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\Users\UserId;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\Users\UserName;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\Users\UserPassword;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\Users\UserStatus;
use Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects\Users\UserUpdateDate;

final class User
{
    private readonly UserId $userId;
    private readonly UserEmail $email;
    private UserPassword $hashedPassword;
    private UserName $name;
    private UserStatus $status;
    private readonly UserCreateDate $createDate;
    private UserUpdateDate $updateDate;

    /**
     * @param Role[] $roles
     */
    public function __construct(
        int $userId,
        string $email,
        string $password,
        bool $rawPassword,
        string $name,
        bool $status,
        DateTime $createDate,
        DateTime $updateDate
    ) {
        $this->userId         = new UserId($userId);
        $this->email          = new UserEmail($email);
        $this->hashedPassword = new UserPassword($password, $rawPassword);
        $this->name           = new UserName($name);
        $this->status         = new UserStatus($status);
        $this->createDate     = new UserCreateDate($createDate);
        $this->updateDate     = new UserUpdateDate($updateDate);
    }

    public function userId(): int
    {
        return $this->userId->value();
    }

    public function email(): string
    {
        return $this->email->value();
    }

    public function password(): string
    {
        return $this->hashedPassword->value();
    }

    public function name(): string
    {
        return $this->name->value();
    }

    public function status(): bool
    {
        return $this->status->value();
    }

    public function createDate(): DateTime
    {
        return $this->createDate->value();
    }

    public function updateDate(): DateTime
    {
        return $this->updateDate->value();
    }

    public function updatePassword(string $newPassword): void
    {
        $this->hashedPassword = new UserPassword($newPassword);
        $this->updateDate     = new UserUpdateDate(new DateTime());
    }

    public function updateName(string $newName): void
    {
        $this->name       = new UserName($newName);
        $this->updateDate = new UserUpdateDate(new DateTime());
    }

    public function enable(): void
    {
        $this->status     = new UserStatus(true);
        $this->updateDate = new UserUpdateDate(new DateTime());
    }

    public function disable(): void
    {
        $this->status     = new UserStatus(false);
        $this->updateDate = new UserUpdateDate(new DateTime());
    }
}
