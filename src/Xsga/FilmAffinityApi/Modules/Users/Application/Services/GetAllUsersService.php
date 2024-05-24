<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Services;

use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\UserDto;
use Xsga\FilmAffinityApi\Modules\Users\Application\Mappers\UserToUserDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Repositories\UsersRepository;

final class GetAllUsersService
{
    public function __construct(
        private UsersRepository $usersRepository,
        private UserToUserDto $mapper
    ) {
    }

    /**
     * @return UserDto[]
     */
    public function get(): array
    {
        $users = $this->usersRepository->getAllUsers();

        return $this->mapper->convertArray($users);
    }
}
