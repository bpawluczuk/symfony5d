<?php

declare(strict_types=1);

namespace App\User\Domain\Factory;

use App\User\Infrastructure\Query\UserReadModel;
use App\User\Domain\Query\UserReadModelInterface;

class UserReadModelFactory
{
    public function create(string $id, string $username): UserReadModelInterface
    {
        return new UserReadModel(
            $id,
            $username,
        );
    }
}