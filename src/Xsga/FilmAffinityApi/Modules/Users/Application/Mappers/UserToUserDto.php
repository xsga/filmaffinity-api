<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Mappers;

use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\UserDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;

final class UserToUserDto
{
    public function __construct(private string $dateTimeMask)
    {
    }

    public function convert(User $user): UserDto
    {
        $userDto = new UserDto();

        $userDto->userId     = $user->userId();
        $userDto->email      = $user->email();
        $userDto->hashedPass = $user->password();
        $userDto->status     = $user->status();
        $userDto->createDate = $user->createDate()->format($this->dateTimeMask);
        $userDto->updateDate = $user->updateDate()->format($this->dateTimeMask);

        return $userDto;
    }

    /**
     * @param User[] $users
     *
     * @return UserDto[]
     */
    public function convertArray(array $users): array
    {
        $out = [];

        foreach ($users as $user) {
            $out[] = $this->convert($user);
        }

        return $out;
    }
}
