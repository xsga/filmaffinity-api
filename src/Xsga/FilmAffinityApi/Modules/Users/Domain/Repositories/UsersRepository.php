<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\App\Domain\Repositories;

use Xsga\FilmAffinityApi\App\Domain\Model\User;

interface UsersRepository
{
    public function getUserByEmail(string $userEmail): ?User;
    public function addUser(User $user): int;
    public function updateUser(User $user): bool;
}
