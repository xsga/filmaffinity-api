<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Users\Application\Dto\UserDto;
use Xsga\FilmAffinityApi\Modules\Users\Application\Mappers\UserToUserDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\GetUser;

final class GetUserByIdService
{
    public function __construct(
        private LoggerInterface $logger,
        private GetUser $getUser,
        private UserToUserDto $mapper
    ) {
    }

    public function get(int $userId): UserDto
    {
        $user = $this->getUser->byId($userId);
        
        return $this->mapper->convert($user);
    }
}
