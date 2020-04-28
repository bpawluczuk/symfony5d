<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Query;

use App\Auth\Domain\Query\SessionReadModelInterface;
use App\Auth\Domain\Query\SessionReadModelRepositoryInterface;
use App\User\Domain\Value\UserId;

class SessionReadModelRepository implements SessionReadModelRepositoryInterface
{
    public function getUserSession(UserId $userId): ?SessionReadModelInterface
    {
        return null;
    }
}