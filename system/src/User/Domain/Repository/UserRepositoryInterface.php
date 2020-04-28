<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\Value\UserId;
use App\User\Domain\Value\Username;

interface UserRepositoryInterface
{
    public function getAll(): array;

    public function getById(UserId $userId): ?User;

    public function getByUsername(Username $username): ?User;

    public function save(User $user): void;
}