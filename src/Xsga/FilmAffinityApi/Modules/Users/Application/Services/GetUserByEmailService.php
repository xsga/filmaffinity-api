<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Services;

use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\UserDto;
use Xsga\FilmAffinityApi\Modules\Users\Application\Mappers\UserToUserDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\GetUser;

final class GetUserByEmailService
{
    public function __construct(
        private GetUser $getUser,
        private UserToUserDto $mapper
    ) {
    }

    public function get(string $userEmail): UserDto
    {
        return $this->mapper->convert($this->getUser->byEmail($userEmail));
    }
}
