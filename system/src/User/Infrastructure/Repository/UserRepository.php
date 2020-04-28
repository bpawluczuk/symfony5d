<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Value\UserId;
use App\User\Domain\Value\Username;

class UserRepository implements UserRepositoryInterface
{
    public function getAll(): array
    {
        return [];
    }

    public function getById(UserId $userId): ?User
    {
        return null;
    }

    public function getByUsername(Username $username): ?User
    {
        return null;
    }

    public function save(User $user): void
    {

    }
}