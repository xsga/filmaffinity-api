<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Domain\Repositories;

use Xsga\FilmAffinityApi\Modules\Users\Domain\Model\User;

interface UsersRepository
{
    /** @return User[] */
    public function getAllUsers(): array;

    public function getUserByEmail(string $userEmail): ?User;
    public function getUserById(int $userId): ?User;
    public function createUser(User $user): int;
    public function updatePassword(User $user): bool;
    public function updateUserStatus(User $user): bool;
    public function deleteUser(int $userId): bool;
    public function deleteUserByEmail(string $userEmail): bool;
}
