<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Domain\Repository\SessionRepositoryInterface;
use App\Auth\Infrastructure\Value\SessionId;
use App\User\Domain\Value\UserId;

class SessionRepository implements SessionRepositoryInterface
{
    public function createSessionForUser(UserId $userId, SessionId $sessionId): void
    {

    }

    public function destroyUserSession(UserId $userId): void
    {

    }
}